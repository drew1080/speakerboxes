<div class="wrap">
<?php //wpmp_discounted_products(); ?>
<?php echo screen_icon('plugins'); ?><h2><?php _e('Feature Set','wpmarketplace');?></h2> 
<br>

<?php
global $wpdb;
//get all rows from databases....
$table = $wpdb->prefix . "mp_feature_set";
$table2 = $wpdb->prefix . "mp_feature_set_meta";

if(isset($_REQUEST['list']) && $_REQUEST['list']==1) {
    require_once WPMP_BASE_DIR . "featureset/show_list_items.php";
}
elseif(isset($_REQUEST['list']) && $_REQUEST['list']==2){
    require_once WPMP_BASE_DIR . "featureset/add_list_items.php";
}
else {
    require_once WPMP_BASE_DIR . "featureset/index.php";
}
?>
</div>
