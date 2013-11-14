<?php //print_r($settings);?>
<div id="tax-options" class="section" style="display: block;"><h3><?php echo __("Tax Options","wpmarketplace");?></h3>
<table class="form-table">

                    <tbody>
                    <tr valign="top" class="">
                    <th class="titledesc" scope="row"><?php echo __("Tax Calculation","wpmarketplace");?></th>
                    <td class="forminp">
                    <label for="calc_shipping">
                    <input type="checkbox" value="1" <?php if(isset($settings['tax']['enable']) && $settings['tax']['enable']==1)echo "checked='checked'";?> id="calc_shipping" name="_wpmp_settings[tax][enable]">
                    <?php echo __("Enable tax calculation","wpmarketplace");?></label><br>
                                    
                                        
                    </td>
                    </tr>
                    <tr valign="top" class="">
                    <th class="titledesc" scope="row"></th>
                    <td class="forminp">                   
                    <input type="checkbox" class="global_low_tax" <?php if(isset($settings['tax']['tax_on_cart']) && $settings['tax']['tax_on_cart']==1)echo 'checked="checked"';?> value="1"  name="_wpmp_settings[tax][tax_on_cart]">                   
                    <?php echo __("Display taxes on cart page","wpmarketplace");?> 
                    </td>
                    </tr> 
                    <tr valign="top" >
                    <th class="titledesc" scope="row"><?php echo __("Additional Tax Classes","wpmarketplace");?> </th>
                    <td class="forminp">                   
                          <textarea cols="50" rows="3" name="_wpmp_settings[tax][tax_class]"><?php echo $settings['tax']['tax_class'];?></textarea> <br /><?php echo __("List 1 per line. This is in addition to the default Standard Rate.","wpmarketplace");?>
                    </td>
                    </tr>
                    
                    <tr valign="top" >
                    <th class="titledesc" scope="row"><?php echo __("Tax Rates","wpmarketplace");?> </th>
                    <td class="forminp">                   
                        <table cellspacing="0" width="100%" class="widefat">
                        <thead>
                        <tr>
                        <th><?php echo __("Action","wpmarketplace");?></th>
                        <th><?php echo __("Countries","wpmarketplace");?></th>
                        <th><?php echo __("Tax Class","wpmarketplace");?></th>
                        <th><?php echo __("Label","wpmarketplace");?></th>
                        <th><?php echo __("Rate(%)","wpmarketplace");?></th>
                        <th><?php echo __("Shipping","wpmarketplace");?></th>
                        </tr>
                        </thead>
                        <tbody id="intr_rate">
                         <?php
                            $tax_classes= $settings['tax']['tax_class'];
                            $textAr = explode("\n", $tax_classes);
                            if(isset($settings['tax']['tax_rate'])){
                                
                                foreach($settings['tax']['tax_rate'] as $key=> $rate){
                                    ?>
                                    <tr id="r_<?php echo $key;?>">
                                    
                                    <td><a href="#" class="del_rate" rel="<?php echo $key;?>">Delete</a></td>
                                    <td>
                                    <ul class="listbox1">
                                    <?php
                                     foreach($countries as $country){   
                                    ?><li><label><input <?php 
                                        if($rate['country']){
                                            foreach($rate['country'] as $r_country){
                                                if($r_country==$country->country_code){
                                                    $select= 'checked="checked"';
                                                    break;
                                                }else $select='';
                                            } echo $select;
                                        }
                                    ?> type="checkbox" name="_wpmp_settings[tax][tax_rate][<?php echo $key;?>][country][]" value="<?php echo $country->country_code;?>"><?php echo $country->country_name;?></label></li>
                                        
                                        <?php
                                     }
                                    ?>
                                    </ul>
                                    </td>
                                    <td>
                                    <select name="_wpmp_settings[tax][tax_rate][<?php echo $key;?>][tax_class]">
                                    <option value="">Standard Rate</option>
                                    <?php
                                      foreach($textAr as $class){
                                           if($rate['tax_class']==$class)$sele= 'selected=selected';else $sele="";
                                          
                                          echo '<option value="'.$class.'" '.$sele.'>'.$class.'</option>';
                                      }  
                                    ?>
                                    </select>
                                    </td>
                                    <td>
                                    <input type="text" name="_wpmp_settings[tax][tax_rate][<?php echo $key;?>][label]" value="<?php echo $rate['label'];?>">
                                    
                                    </td> 
                                    <td>
                                    <input type="text" name="_wpmp_settings[tax][tax_rate][<?php echo $key;?>][rate]" value="<?php echo $rate['rate'];?>">
                                    </td> 
                                    <td><input <?php if($rate['shipping']==1)echo 'checked=checked';?> type="checkbox" name="_wpmp_settings[tax][tax_rate][<?php echo $key;?>][shipping]" value="1"></td>                              
                                    </tr>                                
                                    <?php
                                }
                            }
                        ?>  
                        </tbody>
                        <tfoot>
                        <tr>
                        <td colspan="2"><input class="button" type="button" id="add_tax_rate" value="Add Tax Rate"></td>
                        <td colspan="3" align="right"></td>
                        </tr>
                        </tfoot>
                        
                        
                        </table>
                    </td>
                    </tr> 
                    
                     </tbody></table>  
                        </div>
                        
                      <script type="text/javascript"> 
                      jQuery(function() {
                            jQuery('#add_tax_rate').click(function(){ 
                                 var tmy=new Date().getTime(); 
                                jQuery('#intr_rate').append('<tr id="r_'+tmy+'"><td><a href="#" class="del_rate" rel="'+tmy+'">Delete</a></td><td><ul class="listbox1"><?php
                                     foreach($countries as $country){
                                     ?><li><label><input  type="checkbox" name="_wpmp_settings[tax][tax_rate]['+tmy+'][country][]" value="<?php echo $country->country_code;?>"><?php echo str_replace("'"," ",$country->country_name);?></label></li><?php }
                                    ?></ul></td><td><select name="_wpmp_settings[tax][tax_rate]['+tmy+'][tax_class]"><option value="">Standard Rate</option><?php
                                      foreach($textAr as $class){
                                          
                                          echo '<option value="'.$class.'">'.$class.'</option>';
                                      }  
                                    ?></select></td><td><input type="text" name="_wpmp_settings[tax][tax_rate]['+tmy+'][label]" value="" size="16"></td> <td><input type="text" size="4" name="_wpmp_settings[tax][tax_rate]['+tmy+'][rate]" value=""></td><td><input type="checkbox" name="_wpmp_settings[tax][tax_rate]['+tmy+'][shipping]" value="1"></td></tr>');
                            }); 
                            
                            jQuery('.del_rate').live("click",function(){
                                if(confirm("Are you sure to delete?")){
                                    jQuery('#r_'+jQuery(this).attr("rel")).remove();
                                }
                            }); 
                        });  
                      </script> 
                      <style type="text/css">
                      input[type="text"], textarea {
                          width:auto;
                      }
                      </style>
    
    