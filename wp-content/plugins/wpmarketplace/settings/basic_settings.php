
<input type="hidden" name="action" value="wpmp_save_settings">
 
<div>
<?php
    global $wpdb;
    $countries=$wpdb->get_results("select * from {$wpdb->prefix}mp_country order by country_name");
?>
Base Country <br>
<select name="_wpmp_settings[base_country]">
<option>---Select Country---</option>
<?php
 foreach($countries as $country){   
?>
<option <?php if($settings['base_country']==$country->country_code) echo 'selected=selected'?> value="<?php echo $country->country_code;?>"><?php echo $country->country_name;?></option>
<?php
 }
?>
</select><br /><br />
<?php echo __("Allowed Countries","wpmarketplace");?> 


<ul id="listbox">
    <li><label for="allowed_cn"><input type="checkbox" name="allowed_cn_all" id="allowed_cn" /> Select All/None</label></li>
<?php
 foreach($countries as $country){   
?>
    <li><label><input <?php if($settings['allow_country'])foreach($settings['allow_country'] as $ac){if($ac==$country->country_code){$select= 'checked="checked"';break;}else $select='';} echo $select;?> type="checkbox" name="_wpmp_settings[allow_country][]" value="<?php echo $country->country_code;?>"><?php echo " " . $country->country_name;?></label></li>
    
    <?php
 }
?>
</ul>

<fieldset><legend><strong><?php echo __("Product Page","wpmarketplace");?></strong></legend>
<input type="checkbox" name="_wpmp_settings[generate_product_page_content]" <?php if($settings['generate_product_page_content']==1) echo 'checked=checked' ?> value="1"> <?php echo __("Auto generate product page contents","wpmarketplace");?><br/>
<input type="checkbox" name="_wpmp_settings[instant_download]" <?php if($settings['instant_download']==1) echo 'checked=checked' ?> value="1"> <?php echo __("Show Download Button for Free Digital Product","wpmarketplace");?>
</fieldset>
<br><br>

<?php echo __("When site members add or update a product:","wpmarketplace");?><br>
<input type="radio" name="_wpmp_settings[fstatus]" id="fstatus" value="draft" <?php if($settings['fstatus']=="draft")echo 'checked="checked"';?>> <?php echo __("Keep Pending for Review","wpmarketplace");?> <input type="radio" name="_wpmp_settings[fstatus]" id="fstatus" value="publish" <?php if(!empty($settings['fstatus'])){if($settings['fstatus']=="publish")echo 'checked="checked"';}else echo 'checked="checked"';?> > <?php echo __("Auto Publish","wpmarketplace");?>

<br><br>
 
<?php echo __("Cart Page :","wpmarketplace");?> <br>
<?php 
//print_r($settings['page_id']);
if($settings['page_id'])
$args = array(
    'name'             => '_wpmp_settings[page_id]',
    'selected' => $settings['page_id']
    );
else
 $args = array(
    'name'             => '_wpmp_settings[page_id]'
    );
wp_dropdown_pages($args); 

?>
<br><br>
 
<?php echo __("Checkout Page :","wpmarketplace");?> <br>
<?php 
if($settings['check_page_id'])
$args = array(
    'name'             => '_wpmp_settings[check_page_id]',
    'selected' => $settings['check_page_id']
    );
else
 $args = array(
    'name'             => '_wpmp_settings[check_page_id]'
    );
wp_dropdown_pages($args); 

?>
<br><br>

<?php echo __("Members Page :","wpmarketplace");?> <br>
<?php
if($settings['check_page_id'])
$args = array(
    'name'             => '_wpmp_settings[members_page_id]',
    'selected' => $settings['members_page_id']
    );
else
 $args = array(
    'name'             => '_wpmp_settings[members_page_id]'
    );
wp_dropdown_pages($args);

?>
<br><br>
 <?php echo __("Orders Page :","wpmarketplace");?> <br>
<?php
if($settings['check_page_id'])
$args = array(
    'name'             => '_wpmp_settings[orders_page_id]',
    'selected' => $settings['orders_page_id']
    );
else
 $args = array(
    'name'             => '_wpmp_settings[orders_page_id]'
    );
wp_dropdown_pages($args);

?>
<br><br>
<?php echo __("Continue Shopping URL:","wpmarketplace");?><br/><input type="text" name="_wpmp_settings[continue_shopping_url]" size="80" id="continue_shopping_url" value="<?php echo $settings['continue_shopping_url']?>" />
<br><br>


<b><?php echo __("Front-end Marketplace Access:","wpmarketplace");?></b><br/>
<small>(<?php echo __("Only selected user roles will be allowed to sell products","wpmarketplace");?>)</small>
<ul class="listbox">
<?php
    global $wp_roles;
    $roles = $wp_roles->get_names();
    $user_role = isset($settings['user_role'])?array_values($settings['user_role']):array();
    foreach($roles as $key => $value){
        if(!empty($user_role) && in_array($key, $user_role)){$selected = 'checked="checked"';}
        else $selected = "";
?>
    <li><label><input type="checkbox" name="_wpmp_settings[user_role][]" value="<?php echo $key;?>" <?php  echo $selected; ?> > <?php echo " ".$value;?> </label></li>
    <?php
 }
?>
</ul>
<br><br>
 
<input type="checkbox" name="_wpmp_settings[disable_fron_end_css]" id="disable_fron_end_css" value="1" <?php if(isset($settings['disable_fron_end_css']) && $settings['disable_fron_end_css']==1)echo "checked='checked'";?>> <?php echo __("Disable plugin CSS from front-end","wpmarketplace");?>
<br>
<br>
<input type="checkbox" name="_wpmp_settings[wpmp_after_addtocart_redirect]" id="wpmp_after_addtocart_redirect" value="1" <?php if($settings['wpmp_after_addtocart_redirect']==1)echo "checked='checked'";?>> <?php echo __("Redirect to shopping cart after a product is added to the cart","wpmarketplace");?>
<br><br>

<?php
    do_action("basic_settings");
?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#allowed_cn').on('click', function () {
            $(this).closest('ul').find(':checkbox').prop('checked', this.checked);
        });
    });
    
</script>    