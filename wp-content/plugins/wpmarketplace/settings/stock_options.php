<?php //print_r($settings);?>
<div id="stock-options" class="section" style="display: block;"><h3><?php echo __("Stock Options","wpmarketplace");?></h3>
<table class="form-table">

                    <tbody>
                    <tr valign="top" class="">
                    <th class="titledesc" scope="row"><?php echo __("Manage stock","wpmarketplace");?></th>
                    <td class="forminp">
                    <label for="calc_shipping">
                    <input type="checkbox" value="1" <?php if(isset($settings['stock']['enable']) && $settings['stock']['enable']==1)echo "checked='checked'";?> id="calc_shipping" name="_wpmp_settings[stock][enable]">
                    <?php echo __("Enable stock management","wpmarketplace");?></label><br>
                                    
                                        
                    </td>
                    </tr>
                    <tr valign="top" class="">
                    <th class="titledesc" scope="row"><?php echo __("Enable Global Low stock (For All Product)","wpmarketplace");?></th>
                    <td class="forminp">                   
                    <input type="checkbox" class="global_low_stock" <?php if(isset($settings['stock']['enable_low_stock']) && $settings['stock']['enable_low_stock']==1)echo 'checked="checked"';?> value="1"  name="_wpmp_settings[stock][enable_low_stock]">                   
                    </td>
                    </tr> <tr valign="top" id="low_stock_row">
                    <th class="titledesc" scope="row"><?php echo __("Low stock threshold for all product ","wpmarketplace");?></th>
                    <td class="forminp">                   
                    <input type="text" class="currency_symbol" value="<?php if($settings['stock']['low_stock'])echo $settings['stock']['low_stock'];else echo 0;?>"  name="_wpmp_settings[stock][low_stock]">                   
                    </td>
                    </tr>
                    
                    <tr valign="top" id="low_stock_row">
                    <th class="titledesc" scope="row"><?php echo __("Admin notification on low stock threshold","wpmarketplace");?> </th>
                    <td class="forminp">                   
                    <input type="checkbox"  value="1" <?php if(isset($settings['stock']['low_stock_notification']) && $settings['stock']['low_stock_notification']==1)echo 'checked="checked"';?>  name="_wpmp_settings[stock][low_stock_notification]">                   
                    </td>
                    </tr> 
                    <tr valign="top" class="">
                    <th class="titledesc" scope="row"><?php echo __("Stock Reduce","wpmarketplace");?></th>
                    <td class="forminp">
                    
                    <input type="checkbox"  value="1" <?php if(isset($settings['stock']['reduce_auto']) && $settings['stock']['reduce_auto']==1) echo "checked=checked";?>  name="_wpmp_settings[stock][reduce_auto]"> <?php echo __("Enable Stock Reduce Automatically","wpmarketplace");?>
                   
                    </td>
                    </tr>
                     </tbody></table>                        
                       
                        



                        </div>
                        
                        
    
    