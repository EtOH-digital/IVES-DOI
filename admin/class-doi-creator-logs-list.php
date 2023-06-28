<?php

/**
 * The products functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Msydrop_Shipping
 * @subpackage Msydrop_Shipping/includes
 * @author     GM <gm.software.developer@gmail.com>
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if( ! class_exists('Doi_Creator_Logs_List') ){

    class Doi_Creator_Logs_List extends WP_List_Table
    {
        private $per_page = 20;

        function __construct($args = array())
        {
            parent::__construct($args);
        }

        /**
         * Prepare the items for the table to process
         *
         * @return Void
         */
        public function prepare_items()
        {
            global $wpdb;
            $columns = $this->get_columns();
            $hidden = $this->get_hidden_columns();
            $sortable = $this->get_sortable_columns();
            $this->process_bulk_action();

            $data = $this->table_data();
            $this->items = $data;
            $args = array(
                'posts_per_page' => -1,
                'post_type'      => 'post',
                'meta_query' => array(
                    array(
                        'key'       => 'doi_response',
                        'compare'   => 'EXISTS',
                        'value'
                    )
                )
            );
            $posts = get_posts($args);
            $totalItems = count($posts);
            $totalItems = $totalItems > 0 ? $totalItems : 0;

            $this->set_pagination_args( array(
                'total_items' => $totalItems,
                'per_page'    => $this->per_page
            ) );

            $this->_column_headers = array($columns, $hidden, $sortable);
        }

        public function column_cb($item)
        {
            parent::column_cb($item);
            echo '<input type="checkbox" name="log_ids[]" value="'.esc_attr($item->ID).'">';
        }

        /**
         * Override the parent columns method. Defines the columns to use in your listing table
         *
         * @return Array
         */
        public function get_columns()
        {
            $columns = array(
                'cb'         => '<input id="cb-select-all-1" type="checkbox">',
                'title'      => __('Title'),
                'log'        => __('Log')
            );

            return $columns;
        }

        /**
         * Define which columns are hidden
         *
         * @return Array
         */
        public function get_hidden_columns()
        {
            return array();
        }

        /**
         * Define the sortable columns
         *
         * @return Array
         */
        public function get_sortable_columns()
        {
            return array();
        }

        /**
         * Get the table data
         *
         * @return Array
         */
        private function table_data()
        {
            global $wpdb;
            $currentPage = $this->get_pagenum();
            $offset = $this->per_page * ($currentPage - 1);
            $args = array(
                'posts_per_page' => -1,
                'post_type'      => 'post',
                'meta_query' => array(
                    array(
                        'key'       => 'doi_response',
                        'compare'   => 'EXISTS',
                        'value'
                    )
                )
            );
            $data = get_posts($args);
            return $data;
        }

        /**
         * Define what data to show on each column of the table
         *
         * @param  Array $item        Data
         * @param  String $column_name - Current column name
         *
         * @return Mixed
         */
        public function column_default( $item, $column_name )
        {
            switch( $column_name ) {
                case 'title':
                    $title = '<strong><a class="row-title" href="'.get_permalink($item->ID).'">'.$item->post_title.'</a></strong>';
                    return $title;
                case 'log':
                    $log_meta = get_post_meta($item->ID, 'doi_response', true);
                    return $log_meta;

                default:
                    return print_r( $item, true );
            }
        }

        function get_bulk_actions(){

        }

        /**
         * Allows you to process the data by the variables set in the $_GET
         *
         * @return Mixed
         */
        public function process_bulk_action(){

        }

        public function extra_tablenav($which){

        }

    }

}
