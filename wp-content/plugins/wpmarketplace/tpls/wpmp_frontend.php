<style type="text/css">
.nav-tabs > li {
    list-style: none;
    margin-left: 1px;
}
</style>
<?php
 global $wp_query;
 
if(!is_user_logged_in()){
    if($_REQUEST['task']=='register')
        include("reg-form.php");
   else if($_REQUEST['task']=='forgotpass')
    include("remind_password.php");
   else
    include("login_form.php");
}else{
    $sap = get_option('permalink_structure')?'?':'&';
    $section = $_GET['section']?$_GET['section']:'wpmp-dashboard';
    
    
?>
<div class="wp-marketplace">
<div class="tabbable">
<ul class='nav nav-tabs' id="wpmp-frontend-tabs">
<li class="tab <?php if($section=="wpmp-dashboard")echo "active";?>"><a href='<?php echo get_permalink().$sap; ?>section=wpmp-dashboard' ><?php echo __("Dashboard","wpmarketplace");?></a></li>
<?php $cap = 'wpmarketplace_user'; if(current_user_can($cap)): ?>
<li class="tab <?php if($section=="wpmp-my-products")echo "active";?>"><a href='<?php echo get_permalink().$sap; ?>section=wpmp-my-products' ><?php echo __("My Products","wpmarketplace");?></a></li>
<li class="tab <?php if($section=="wpmp-add-product")echo "active";?>"><a href='<?php echo get_permalink().$sap; ?>section=wpmp-add-product' ><?php echo __("Add Product","wpmarketplace");?></a></li>
<li class="tab <?php if($section=="wpmp-earnings")echo "active";?>"><a href='<?php echo get_permalink().$sap; ?>section=wpmp-earnings' ><?php echo __("Earnings","wpmarketplace");?></a></li>
<?php endif; ?>
<?php echo do_action("wpmarketplace_usermenu"); ?>
<li class="tab <?php if($section=="wpmp-affiliate")echo "active";?>"><a href='<?php echo get_permalink().$sap; ?>section=wpmp-affiliate' ><?php echo __("Affiliate","wpmarketplace");?></a></li>
<li class="tab <?php if($section=="my-orders-sc")echo "active";?>"><a href='<?php echo get_permalink().$sap; ?>section=my-orders-sc' ><?php echo __("My Orders","wpmarketplace");?></a></li>
<li class="tab <?php if($section=="wpmp-edit-profile")echo "active";?>"><a href='<?php echo get_permalink().$sap; ?>section=wpmp-edit-profile' ><?php echo __("Edit Profile","wpmarketplace");?></a></li>
</ul>
<div class="tab-content">
<?php echo do_shortcode("[{$section}]"); ?>
</div>
</div>
</div>
<?php
}
?>

 

