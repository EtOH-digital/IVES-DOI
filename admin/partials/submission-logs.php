<?php
require_once DOI_CREATOR_PATH . 'admin/class-doi-creator-logs-list.php';
$ziplist = new Doi_Creator_Logs_List();
$ziplist->prepare_items();
?>
<div class="wrap">
    <div id="icon-users" class="icon32"></div>
    <h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form method="get" action="<?php echo esc_url(admin_url('admin.php')); ?>">
        <input type="hidden" name="page" value="g2g-base-products">
        <?php
        $ziplist->display(); ?>
    </form>
</div>