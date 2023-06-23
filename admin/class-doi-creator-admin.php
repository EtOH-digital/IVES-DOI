<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://www.fiverr.com/mustafaje
 * @since      1.0.0
 *
 * @package    Doi_Creator
 * @subpackage Doi_Creator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Doi_Creator
 * @subpackage Doi_Creator/admin
 * @author     GM <gm.software.developer@gmail.com>
 */
class Doi_Creator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        if ( get_post_type() === 'post' ) {
            wp_enqueue_style($this->plugin_name . '-bootstrap-min-css', plugin_dir_url(__FILE__) . 'dist/css/bootstrap.min.css', array(), '1.13.2', 'all');
        }
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/doi-creator-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/doi-creator-admin.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( $this->plugin_name, 'doi_settings',
            array(
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );
        if ( get_post_type() === 'post' ) {
            wp_enqueue_script( $this->plugin_name.'-bootstrap-min-js', plugin_dir_url( __FILE__ ) . 'dist/js/bootstrap.min.js', array( 'jquery' ), '1.13.2', false );
            include_once DOI_CREATOR_PATH . 'admin/partials/xml-form.php';
        }
	}

    function register_settings(){
        register_setting('doi-creator-settings', 'doi_creator_options');
    }

    function admin_menu(){
        add_options_page(
            'DOI Creator Settings',
            'DOI Creator',
            'manage_options',
            'doi-creator',
            array($this, 'main_settings')
        );
        add_options_page(
            'View Submission Logs',
            '',
            '',
            'doi-logs',
            array($this, 'submission_logs')
        );
    }

    function main_settings(){
        include_once DOI_CREATOR_PATH . 'admin/partials/main-settings.php';
    }

    function submission_logs(){
        include_once DOI_CREATOR_PATH . 'admin/partials/submission-logs.php';
    }

    function set_custom_edit_post_columns($columns) {
        $columns['create_doi'] = __( 'Create DOI' );
        return $columns;
    }

    function custom_post_column( $column, $post_id ) {
        switch ( $column ) {
            case 'create_doi' :
                $btn = '<span data-postid="'.esc_attr($post_id).'" data-posttitle="'.esc_attr(get_the_title($post_id)).'" class="btn btn-primary btn-sm createDOI" data-bs-toggle="modal" data-bs-target="#createDOIModal">Create DOI</span>';
                $xml_filename = 'doi-' . $post_id . '.xml';
                $upload_dir = WP_CONTENT_DIR . '/uploads/doi-files/';
                $doi_submit = get_post_meta($post_id, 'doi_submitted', true);
                if( file_exists($upload_dir.$xml_filename) ) {
                    $fileUrl = WP_CONTENT_URL . '/uploads/doi-files/'.$xml_filename;
                    $btn .= '<a class="btn btn-info btn-sm viewXml" href="'.$fileUrl.'" target="_blank">View Xml</a>';
                }
                if( isset($doi_submit) && $doi_submit == 'no' ) {
                    $btn .= '<span data-postid="'.esc_attr($post_id).'" class="btn btn-success btn-sm submitDOI">Submit DOI</span>';
                }
                echo $btn;
                break;
        }
    }

    function generate_doi(){
	    if( isset($_POST['doi_nonce']) && $_POST['doi_nonce'] && wp_verify_nonce($_POST['doi_nonce'], 'submit_doi_nonce')) {
            if ( isset($_POST['post_id']) && $_POST['post_id'] && isset($_POST['series_title']) && $_POST['series_title'] && isset($_POST['series_issn']) && $_POST['series_issn'] && isset($_POST['proceeding_title']) && $_POST['proceeding_title'] && isset($_POST['proceeding_publisher']) && $_POST['proceeding_publisher'] && isset($_POST['proceeding_publish_date']) && $_POST['proceeding_publish_date'] && isset($_POST['conference_paper_contributors_fname']) && $_POST['conference_paper_contributors_fname'] && isset($_POST['conference_paper_contributors_sname']) && $_POST['conference_paper_contributors_sname'] && isset($_POST['conference_paper_title']) && $_POST['conference_paper_title'] && isset($_POST['conference_paper_doi_data']) && $_POST['conference_paper_doi_data'] && isset($_POST['proceeding_publisher_place']) && $_POST['proceeding_publisher_place'] ) {
                $series_title = isset($_POST['series_title']) ? esc_attr($_POST['series_title']) : '';
                $series_issn = isset($_POST['series_issn']) ? esc_attr($_POST['series_issn']) : '';
                $proceeding_title = isset($_POST['proceeding_title']) ? esc_attr($_POST['proceeding_title']) : '';
                $proceeding_publisher = isset($_POST['proceeding_publisher']) ? esc_attr($_POST['proceeding_publisher']) : '';
                $proceeding_publisher_place = isset($_POST['proceeding_publisher_place']) ? esc_attr($_POST['proceeding_publisher_place']) : '';
                $proceeding_publish_date = isset($_POST['proceeding_publish_date']) ? esc_attr($_POST['proceeding_publish_date']) : '';
                $conference_paper_contributors_fname = isset($_POST['conference_paper_contributors_fname']) ? esc_attr($_POST['conference_paper_contributors_fname']) : '';
                $conference_paper_contributors_sname = isset($_POST['conference_paper_contributors_sname']) ? esc_attr($_POST['conference_paper_contributors_sname']) : '';
                $conference_paper_title = isset($_POST['conference_paper_title']) ? esc_attr($_POST['conference_paper_title']) : '';
                $conference_paper_doi_data = isset($_POST['conference_paper_doi_data']) ? esc_attr($_POST['conference_paper_doi_data']) : '';
                $series_doi_data = isset($_POST['series_doi_data']) ? esc_attr($_POST['series_doi_data']) : '';
                $series_contributors_fname = isset($_POST['series_contributors_fname']) ? esc_attr($_POST['series_contributors_fname']) : '';
                $series_contributors_sname = isset($_POST['series_contributors_sname']) ? esc_attr($_POST['series_contributors_sname']) : '';
                $series_number = isset($_POST['series_number']) ? esc_attr($_POST['series_number']) : '';
                $series_isbn = isset($_POST['series_isbn']) ? esc_attr($_POST['series_isbn']) : '';
                $conference_volume = isset($_POST['conference_volume']) ? esc_attr($_POST['conference_volume']) : '';
                $conference_isbn = isset($_POST['conference_isbn']) ? esc_attr($_POST['conference_isbn']) : '';
                $conference_date_start = isset($_POST['conference_date_start']) ? esc_attr($_POST['conference_date_start']) : '';
                $conference_date_end = isset($_POST['conference_date_end']) ? esc_attr($_POST['conference_date_end']) : '';
                $conference_location = isset($_POST['conference_location']) ? esc_attr($_POST['conference_location']) : '';
                $conference_acronym = isset($_POST['conference_acronym']) ? esc_attr($_POST['conference_acronym']) : '';
                $conference_theme = isset($_POST['conference_theme']) ? esc_attr($_POST['conference_theme']) : '';
                $conference_sponsor = isset($_POST['conference_sponsor']) ? esc_attr($_POST['conference_sponsor']) : '';
                $conference_number = isset($_POST['conference_number']) ? esc_attr($_POST['conference_number']) : '';
                $proceedings_subject = isset($_POST['proceedings_subject']) ? esc_attr($_POST['proceedings_subject']) : '';
                $conference_publication_date = isset($_POST['conference_publication_date']) ? esc_attr($_POST['conference_publication_date']) : '';
                $conference_pages = isset($_POST['conference_pages']) ? esc_attr($_POST['conference_pages']) : '';
                $conference_citation_list = isset($_POST['conference_citation_list']) ? esc_attr($_POST['conference_citation_list']) : '';
                $conference_funding = isset($_POST['conference_funding']) ? esc_attr($_POST['conference_funding']) : '';
                $conference_license = isset($_POST['conference_license']) ? esc_attr($_POST['conference_license']) : '';
                $conference_crossmark_version = isset($_POST['conference_crossmark_version']) ? esc_attr($_POST['conference_crossmark_version']) : '';
                $conference_crossmark_policy = isset($_POST['conference_crossmark_policy']) ? esc_attr($_POST['conference_crossmark_policy']) : '';

                //options data
                update_post_meta($_POST['post_id'], 'DOI', $_POST);
                $options = get_option('doi_creator_options', []);
                $login_id = isset($options['login_id']) ? esc_attr($options['login_id']) : '';
                $api_password = isset($options['api_password']) ? esc_attr($options['api_password']) : '';
                $deposit_endpoint = isset($options['deposit_endpoint']) ? esc_attr($options['deposit_endpoint']) : '';
                $test_endpoint = isset($options['test_endpoint']) ? esc_attr($options['test_endpoint']) : '';
                $email = isset($options['email']) ? esc_attr($options['email']) : '';
                $api_env = isset($options['api_env']) ? esc_attr($options['api_env']) : '';
                $auto_submit = isset($options['auto_submit']) ? esc_attr($options['auto_submit']) : '';
                $doi_prefix = isset($options['doi_prefix']) ? esc_attr($options['doi_prefix']) : '';

                $doi_suffix = uniqid();
                $doi = $doi_prefix . '/' . $doi_suffix;

                $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><doi_batch></doi_batch>');
                $xml->addAttribute('xmlns', 'http://www.crossref.org/schema/5.3.0');
                $xml->addAttribute('version', '5.3.0');
                //set head data
                $data = $xml->addChild('head');
                $data->addChild('doi_batch_id', 'test.x');
                $data->addChild('timestamp', date('YmdHis', current_time('timestamp', 0)));
                $data2 = $data->addChild('depositor');
                $data2->addChild('depositor_name', 'Crossref');
                $data2->addChild('email_address', $email);
                $data->addChild('registrant', 'Society of Metadata Idealists');
                $body = $xml->addChild('body');
                $conference = $body->addChild('conference');

                //start adding data into xml form form
                $data28 = $conference->addChild('event_metadata');
                $data28->addChild('conference_name', $proceeding_title);
                $data28->addChild('conference_theme', $conference_theme);
                $data28->addChild('conference_acronym', $conference_acronym);
                $data28->addChild('conference_sponsor', $conference_sponsor);
                $data28->addChild('conference_number', $conference_number);
                $data28->addChild('conference_location', $conference_location);

                $conferenceDateM = date("M", strtotime( $conference_date_start) );
                $conferenceDateStartD = date("d", strtotime( $conference_date_start) );
                $conferenceDateEndD = date("d", strtotime( $conference_date_end) );
                $conferenceDateY = date("Y", strtotime( $conference_date_start) );
                $data33 = $data28->addChild('conference_date', $conferenceDateM.'. '.$conferenceDateStartD.'-'.$conferenceDateEndD.', '.$conferenceDateY);
                $data33->addAttribute('start_day', date("d", strtotime($conference_date_start)));
                $data33->addAttribute('start_month', date("m", strtotime($conference_date_start)));
                $data33->addAttribute('start_year', date("Y", strtotime($conference_date_start)));
                $data33->addAttribute('end_day', date("d", strtotime($conference_date_end)));
                $data33->addAttribute('end_month', date("m", strtotime($conference_date_end)));
                $data33->addAttribute('end_year', date("Y", strtotime($conference_date_end)));

                $data1 = $conference->addChild('proceedings_series_metadata');
                $data2 = $data1->addChild('series_metadata');

                $data22 = $data2->addChild('contributors');
                $data23 = $data22->addChild('person_name');
                $data23->addAttribute('sequence', 'first');
                $data23->addAttribute('contributor_role', 'chair');
                $data23->addChild('given_name', $series_contributors_fname);
                $data23->addChild('surname', $series_contributors_sname);
                $data24 = $data23->addChild('affiliations');
                $data25 = $data24->addChild('institution');
                $data26 = $data25->addChild('institution_id', 'https://ror.org/05bp8ka05');
                $data26->addAttribute('type', 'ror');
                $data27 = $data23->addChild('ORCID', 'https://orcid.org/0000-0002-4011-3590');
                $data27->addAttribute('authenticated', 'true');

                $data3 = $data2->addChild('titles');
                $data3->addChild('title', $series_title);
                $data2->addChild('issn', $series_issn);

                $data21 = $data2->addChild('doi_data');
                $data21->addChild('doi', $doi);
                $data21->addChild('resource', $series_doi_data);

                $data1->addChild('proceedings_title', $proceeding_title);
                $data1->addChild('volume', $conference_volume);
                $data1->addChild('proceedings_subject', $proceedings_subject);
                $data4 = $data1->addChild('publisher');
                $data4->addChild('publisher_name', $proceeding_publisher);
                $data4->addChild('publisher_place', $proceeding_publisher_place);

                $data5 = $data1->addChild('publication_date');
                $data5->addAttribute('media_type', 'online');
                $data5->addChild('month', date("m", strtotime($proceeding_publish_date)));
                $data5->addChild('day', date("d", strtotime($proceeding_publish_date)));
                $data5->addChild('year', date("Y", strtotime($proceeding_publish_date)));

                $noisbn = $data1->addChild('noisbn');
                $noisbn->addAttribute('reason', 'simple_series');

                $data6 = $conference->addChild('conference_paper');
                $data6->addAttribute('language', 'en');
                $data6->addAttribute('publication_type', 'full_text');

                $data7 = $data6->addChild('contributors');
                $data8 = $data7->addChild('person_name');
                $data8->addAttribute('sequence', 'first');
                $data8->addAttribute('contributor_role', 'author');
                $data8->addChild('given_name', $conference_paper_contributors_fname);
                $data8->addChild('surname', $conference_paper_contributors_sname);
                $data9 = $data8->addChild('affiliations');
                $data10 = $data9->addChild('institution');
                $data11 = $data10->addChild('institution_id', 'https://ror.org/05bp8ka05');
                $data11->addAttribute('type', 'ror');
                $data12 = $data8->addChild('ORCID', 'https://orcid.org/0000-0002-1825-0097');
                $data12->addAttribute('authenticated', 'true');
                $data13 = $data6->addChild('titles');
                $data13->addChild('title', $conference_paper_title);

                $data29 = $data6->addChild('publication_date');
                $data29->addAttribute('media_type', 'online');
                $data29->addChild('month', date("m", strtotime($conference_publication_date)));
                $data29->addChild('day', date("d", strtotime($conference_publication_date)));
                $data29->addChild('year', date("Y", strtotime($conference_publication_date)));

                $data32 = $data6->addChild('crossmark');
                $data32->addChild('crossmark_version', $conference_crossmark_version);
                $data32->addChild('crossmark_policy', $conference_crossmark_policy);

                $data14 = $data6->addChild('doi_data');
                $data14->addChild('doi', $doi);
                $data14->addChild('resource', $conference_paper_doi_data);
                $data15 = $data14->addChild('collection');
                $data15->addAttribute('property', 'crawler-based');
                $data16 = $data15->addChild('item');
                $data16->addAttribute('crawler', 'iParadigms');
                $data16->addChild('resource', 'https://www.crossref.org/faqs.html');

                $data17 = $data14->addChild('collection');
                $data17->addAttribute('property', 'text-mining');
                $data18 = $data17->addChild('item');
                $data18->addChild('resource', 'https://www.crossref.org/example.xml');

                $data19 = $data14->addChild('collection');
                $data19->addAttribute('property', 'link-header');
                $data20 = $data19->addChild('item');
                $data20->addAttribute('link_header_relationship', 'dul');
                $data20->addChild('resource', 'http://www.crossref.org/exampleDULendpoint');

                $data30 = $data6->addChild('citation_list');
                $data31 = $data30->addChild('citation');
                $data31->addAttribute('key', 'ref1');
                $data31->addChild('doi', $conference_citation_list);

                $xml_filename = 'doi-' . $_POST['post_id'] . '.xml';
                Header('Content-type: application/vnd.crossref+xml');
                $xmlFile = $xml->asXML();
                $upload_dir = WP_CONTENT_DIR . '/uploads/doi-files/';
                if ( ! is_dir($upload_dir) ){
                    mkdir($upload_dir);
                }
                if( !file_exists($upload_dir.$xml_filename) ) {
                    file_put_contents($upload_dir . $xml_filename, $xmlFile);
                } else {
                    file_put_contents($upload_dir . $xml_filename, $xmlFile);
                }
                $filePath = $upload_dir = WP_CONTENT_DIR . '/uploads/doi-files/'.$xml_filename;
                $fileUrl = $upload_dir = WP_CONTENT_URL . '/uploads/doi-files/'.$xml_filename;

                //sent request
                $boundary = wp_generate_password( 24 );
                $headers = array(
                    'Accept' => 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*',
                    'Accept-Language' => 'en-us',
                    'Content-Type' => 'multipart/form-data; application/json; text/xml; application/xml; boundary='.$boundary,
                    'Content-Disposition' => 'form-data; name="fname"; filename='.$xml_filename
                );

                if ( $filePath ) {
                    $payload = '--' . $boundary;
                    $payload .= "\r\n";
                    $payload .= 'Content-Disposition: form-data; name="' . $xml_filename .
                        '"; filename="' . basename( $filePath ) . '"' . "\r\n";
                    $payload .= "\r\n";
                    $payload .= file_get_contents( $filePath );
                    $payload .= "\r\n";
                }
                $payload .= '--' . $boundary . '--';
                if(isset($api_env) && $api_env == 'test'){
                    $url = $test_endpoint.'?operation=doMDUpload&login_id='.$login_id.'&login_passwd='.$api_password;
                } else {
                    $url = $deposit_endpoint.'?operation=doMDUpload&login_id='.$login_id.'&login_passwd='.$api_password;
                }
                if(isset($auto_submit) && $auto_submit ){
                    $response = wp_remote_post(
                        $url,
                        array(
                            'method' 	  => 'POST',
                            'headers'   => $headers,
                            'body'      => $payload,
                            'filename'  => $xml_filename
                        )
                    );
                    if (is_wp_error($response)) {
                        $error_message = $response->get_error_message();
                        wp_send_json_error($error_message);
                    } else {
                        $response_code = wp_remote_retrieve_response_code($response);
                        if ($response_code === 200) {
                            $response_body = wp_remote_retrieve_body($response);
                            update_post_meta($_POST['post_id'], 'doi_submitted', 'yes');
                            update_post_meta($_POST['post_id'], 'doi_response', $response_body);
                            $response = array('success' => true, 'msg' => 'DOI submit successfully!', 'code' => $response_code, 'body' => $response_body, 'file_url' => $fileUrl);
                        } else {
                            $response = array('success' => false, 'msg' => 'Something went to wrong!', 'file_url' => '');
                        }
                    }
                } else {
                    update_post_meta($_POST['post_id'], 'doi_submitted', 'no');
                    $response = array('success' => true, 'msg' => 'DOI create successfully!', 'file_url' => $fileUrl);
                }

            } else {
                $response = array('success' => false, 'msg' => 'Required fields are missing!', 'file_url' => '');
            }
        } else {
            $response = array('success' => false, 'msg' => 'Security nonce mismatched, reload and try again!', 'file_url' => '');
        }
        wp_send_json($response);
        wp_die();
    }

    function submit_doi(){
	    if( isset($_POST['post_id']) && $_POST['post_id'] ){
	        //options data
            $options = get_option('doi_creator_options', []);
            $login_id = isset($options['login_id']) ? esc_attr($options['login_id']) : '';
            $api_password = isset($options['api_password']) ? esc_attr($options['api_password']) : '';
            $deposit_endpoint = isset($options['deposit_endpoint']) ? esc_attr($options['deposit_endpoint']) : '';
            $test_endpoint = isset($options['test_endpoint']) ? esc_attr($options['test_endpoint']) : '';
            $api_env = isset($options['api_env']) ? esc_attr($options['api_env']) : '';

            $xml_filename = 'doi-' . $_POST['post_id'] . '.xml';
            $filePath = $upload_dir = WP_CONTENT_DIR . '/uploads/doi-files/'.$xml_filename;

            //sent request
            $boundary = wp_generate_password( 24 );
            $headers = array(
                'Accept' => 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*',
                'Accept-Language' => 'en-us',
                'Content-Type' => 'multipart/form-data; application/json; text/xml; application/xml; boundary='.$boundary,
                'Content-Disposition' => 'form-data; name="fname"; filename='.$xml_filename
            );

            if ( $filePath ) {
                $payload = '--' . $boundary;
                $payload .= "\r\n";
                $payload .= 'Content-Disposition: form-data; name="' . $xml_filename .
                    '"; filename="' . basename( $filePath ) . '"' . "\r\n";
                $payload .= "\r\n";
                $payload .= file_get_contents( $filePath );
                $payload .= "\r\n";
            }
            $payload .= '--' . $boundary . '--';
            if(isset($api_env) && $api_env == 'test'){
                $url = $test_endpoint.'?operation=doMDUpload&login_id='.$login_id.'&login_passwd='.$api_password;
            } else {
                $url = $deposit_endpoint.'?operation=doMDUpload&login_id='.$login_id.'&login_passwd='.$api_password;
            }
            $response = wp_remote_post(
                $url,
                array(
                    'method' 	  => 'POST',
                    'headers'   => $headers,
                    'body'      => $payload,
                    'filename'  => $xml_filename
                )
            );
            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                wp_send_json_error($error_message);
            } else {
                $response_code = wp_remote_retrieve_response_code($response);
                if ($response_code === 200) {
                    $response_body = wp_remote_retrieve_body($response);
                    update_post_meta($_POST['post_id'], 'doi_submitted', 'yes');
                    update_post_meta($_POST['post_id'], 'doi_response', $response_body);
                    $response = array('success' => true, 'msg' => 'DOI submit successfully!', 'code' => $response_code, 'body' => $response_body);
                } else {
                    $response = array('success' => false, 'msg' => 'Something went to wrong!');
                }
            }
        } else {
            $response = array('success' => false, 'msg' => 'Something went to wrong!');
        }
        wp_send_json($response);
        wp_die();
    }

}
