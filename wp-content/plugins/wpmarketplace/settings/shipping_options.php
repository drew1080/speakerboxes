<?php //print_r($settings);?>
<div id="shipping-options" class="section" style="display: block;"><h3><?php echo __("Shipping Options","wpmarketplace");?></h3>
<table class="form-table">

                    <tbody><tr valign="top" class="">
                    <th class="titledesc" scope="row"><?php echo __("Shipping calculations","wpmarketplace");?></th>
                    <td class="forminp">
                        <fieldset>
                                    <legend class="screen-reader-text"><span><?php echo __("Shipping calculations","wpmarketplace");?></span></legend>
                    <label for="calc_shipping">
                        <input type="checkbox" value="1" <?php if(isset($settings['calc_shipping']) && $settings['calc_shipping']==1)echo "checked='checked'";?> id="calc_shipping" name="_wpmp_settings[calc_shipping]">
                    <?php echo __("Enable shipping","wpmarketplace");?></label><br>
                                    </fieldset>
                                        
                    </td>
                    </tr>
                                                            </tbody></table>                        <h3><?php echo __("Shipping Methods","wpmarketplace");?></h3>
                       
                        



                        <table cellspacing="0" class=" widefat">
                            <thead>
                                <tr>
                                    <th><?php echo __("Default","wpmarketplace");?></th>
                                    <th><?php echo __("Shipping Method","wpmarketplace");?></th>
                                    <th><?php echo __("Status","wpmarketplace");?></th>
                                </tr>
                            </thead>
                            <tbody class="ui-sortable" style="">
                                <tr style="">
                                    <td width="1%" class="radio">
                                        <input type="radio" <?php if($settings['default_shipping_method']=="flat_rate")echo "checked='checked'";?> value="flat_rate" name="_wpmp_settings[default_shipping_method]">
                                        
                                        </td><td>
                                            <p><strong><?php echo __("Flat Rate","wpmarketplace");?></strong><br>
                                            </p>
                                        </td>
                                        <td><img alt="yes" src="/images/success.png"></td>
                                    </tr><tr style="">
                                    <td width="1%" class="radio">
                                        <input type="radio" <?php if($settings['default_shipping_method']=="free_shipping")echo "checked='checked'";?> value="free_shipping" name="_wpmp_settings[default_shipping_method]">

                                        </td><td>
                                            <p><strong><?php echo __("Free Shipping","wpmarketplace");?></strong><br>
                                            </p>
                                        </td>
                                        <td><img alt="yes" src="/images/success.png"></td>
                                    </tr><tr style="">
                                    <td width="1%" class="radio">
                                        <input type="radio" <?php if($settings['default_shipping_method']=="local-delivery")echo "checked='checked'";?> value="local-delivery" name="_wpmp_settings[default_shipping_method]">
                                        
                                        </td><td>
                                            <p><strong><?php echo __("Local Delivery","wpmarketplace");?></strong><br>
                                           </p>
                                        </td>
                                        <td><img alt="yes" src="/images/success.png"></td>
                                    </tr></tbody>
                        </table>
                        <div style="clear: both;margin-top:20px ;"></div>
                        <h3><?php echo __("Shipping Methods Configuration","wpmarketplace");?></h3>
                        <div id="saccordion">
    <h3><a href="#"><?php echo __("Flat Rate","wpmarketplace");?></a></h3>
    <div>   
    <table class="form-table">
        <tbody><tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Enable/Disable","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Enable/Disable","wpmarketplace");?></span></legend>
<label for="flat_rate_enabled"><input type="checkbox" <?php if(isset($settings['flat_rate_enabled']) && $settings['flat_rate_enabled']==1)echo "checked='checked'";?> class=""  value="1" id="flat_rate_enabled" name="_wpmp_settings[flat_rate_enabled]" style=""> <?php echo __("Enable this shipping method","wpmarketplace");?></label><br>
</fieldset></td>
</tr>
<tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Title","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Title","wpmarketplace");?></span></legend>
<label for="flat_rate_title"><input type="text" value="<?php echo $settings['flat_rate_title'];?>" style="" id="flat_rate_title" name="_wpmp_settings[flat_rate_title]" class="input-text wide-input "><span class="description"><?php echo __("This controls the title which the user sees during checkout.","wpmarketplace");?></span>
</label></fieldset></td>
</tr>
<tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Tax Status","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span>T<?php echo __("Tax Status","wpmarketplace");?></span></legend>
<label for="flat_rate_tax_status"><select class="select " style="" id="flat_rate_tax_status" name="_wpmp_settings[flat_rate_tax_status]"><option value="taxable"  <?php if($settings['flat_rate_tax_status']=="taxable")echo "selected='selected'";?>><?php echo __("Taxable","wpmarketplace");?></option><option value="none" <?php if($settings['flat_rate_tax_status']=="none")echo "selected='selected'";?>>None</option></select></label></fieldset></td>
</tr>
<tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Default Cost","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Default Cost","wpmarketplace");?></span></legend>
<label for="flat_rate_cost"><input type="text" value="<?php echo $settings['flat_rate_cost'];?>" style="" id="flat_rate_cost" name="_wpmp_settings[flat_rate_cost]" class="input-text wide-input "><span class="description"><?php echo __("Cost excluding tax. Enter an amount, e.g. 2.50.","wpmarketplace");?></span>
</label></fieldset></td>
</tr>
<tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Default Handling Fee","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Default Handling Fee","wpmarketplace");?></span></legend>
<label for="flat_rate_fee"><input type="text" value="<?php echo $settings['flat_rate_fee'];?>" style="" id="flat_rate_fee" name="_wpmp_settings[flat_rate_fee]" class="input-text wide-input "><span class="description"><?php echo __("Fee excluding tax. Enter an amount, e.g. 2.50.","wpmarketplace");?></span>
</label></fieldset></td>
</tr>
                    </tbody></table>
    
    </div>
    <h3><a href="#"><?php echo __("Free Shipping","wpmarketplace");?></a></h3>
    <div>
    <div id="shipping-free_shipping" class="section" style="display: block;"> <h3>Free Shipping</h3>
        
        <table class="form-table">
        <tbody><tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Enable/Disable","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Enable/Disable","wpmarketplace");?></span></legend>
<label for="free_shipping_enabled"><input type="checkbox" class="" <?php if(isset($settings['free_shipping_enabled']) && $settings['free_shipping_enabled']==1)echo "checked='checked'";?>  value="1" id="free_shipping_enabled" name="_wpmp_settings[free_shipping_enabled]" style=""> <?php echo __("Enable Free Shipping","wpmarketplace");?></label><br>
</fieldset></td>
</tr>
<tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Title","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Method Title","wpmarketplace");?></span></legend>
<label for="free_shipping_title"><input type="text" value="<?php echo $settings['free_shipping_title'];?>" style="" id="free_shipping_title" name="_wpmp_settings[free_shipping_title]" class="input-text wide-input "><span class="description"><?php echo __("This controls the title which the user sees during checkout.","wpmarketplace");?></span>
</label></fieldset></td>
</tr>
<tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Minimum Order Amount","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span>Minimum Order Amount</span></legend>
<label for="free_shipping_min_amount"><input type="text" value="<?php echo $settings['free_shipping_min_amount'];?>" style="" id="free_shipping_min_amount" name="_wpmp_settings[free_shipping_min_amount]" class="input-text wide-input "><span class="description"><?php echo __("Users will need to spend this amount to get free shipping.","wpmarketplace");?></span>
</label></fieldset></td>
</tr>
        </tbody></table>
        </div>
    </div>
    <h3><a href="#"><?php echo __("Local Delivery","wpmarketplace");?></a></h3>
    <div>
    <div id="shipping-local-delivery" class="section" style="display: block;">        <h3><?php echo __("Local Delivery","wpmarketplace");?></h3>
        <p><?php echo __("Local delivery is a simple shipping method for delivering orders locally.","wpmarketplace");?></p>
        <table class="form-table">
            <tbody><tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Enable","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Enable","wpmarketplace");?></span></legend>
<label for="local-delivery_enabled"><input type="checkbox" class="" <?php if(isset($settings['local-delivery_enabled']) && $settings['local-delivery_enabled']==1)echo "checked='checked'";?>  value="1" id="local-delivery_enabled" name="_wpmp_settings[local-delivery_enabled]" style=""><?php echo __(" Enable local delivery","wpmarketplace");?></label><br>
</fieldset></td>
</tr>
<tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Title","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Title","wpmarketplace");?></span></legend>
<label for="local-delivery_title"><input type="text" value="<?php echo $settings['local-delivery_title'];?>" style="" id="local-delivery_title" name="_wpmp_settings[local-delivery_title]" class="input-text wide-input "><span class="description"><?php echo __("This controls the title which the user sees during checkout.","wpmarketplace");?></span>
</label></fieldset></td>
</tr>
<tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Fee Type","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Fee Type","wpmarketplace");?></span></legend>
<label for="local-delivery_type"><select class="select " style="" id="local-delivery_type" name="_wpmp_settings[local-delivery_type]"><option value="free" <?php if($settings['local-delivery_type']=="free")echo "selected='selected'";?>>Free Delivery</option><option  value="fixed" <?php if($settings['local-delivery_type']=="fixed")echo "selected='selected'";?>>Fixed Amount</option><option value="percent">Percentage of Cart Total</option></select><span class="description"><?php echo __("How to calculate delivery charges","wpmarketplace");?></span>
</label></fieldset></td>
</tr>
<tr valign="top">
<th class="titledesc" scope="row"><?php echo __("Fee","wpmarketplace");?></th>
<td class="forminp">
<fieldset><legend class="screen-reader-text"><span><?php echo __("Fee","wpmarketplace");?></span></legend>
<label for="local-delivery_fee"><input type="text" value="<?php echo $settings['local-delivery_fee'];?>" style="" id="local-delivery_fee" name="_wpmp_settings[local-delivery_fee]" class="input-text wide-input "><span class="description"><?php echo __("What fee do you want to charge for local delivery, disregarded if you choose free.","wpmarketplace");?></span>
</label></fieldset></td>
</tr>
        </tbody></table> </div>
    </div>
    <?php
    do_action("wpmp_new_shipping_method");
?>
</div>

                        </div>
                        
                        <script>
    jQuery(function() {
        jQuery( "#saccordion" ).accordion({
            autoHeight: false,
            navigation: true});
    });
    
    </script>
    <style type="text/css">
    .ui-accordion-content-active{
        height: auto !important;
    }
    </style>
    
    