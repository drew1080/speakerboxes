<?php 
$data = get_post_meta($post->ID,"wpmp_list_opts",true);
//echo "<pre>";print_r($data); echo "</pre>";
if(!empty($data)) {
    @extract($data); 
}

    ?>
    <div style="width: 50%;float: left;">
    <div class="postbox" style="width: 96%;float: left;">
    <h3 id="variation_heading"><?php if(isset($price_variation)) echo "Variation Options";else echo "Pricing";?></h3>
    <table   width="100%" style="margin: 10px;" >
    <tr><td width="250px">
    <table><tr id="base_price" ><td>
    <?php echo __('Base Price','wpmarketplace'); ?></td><td><input type="text" size="16" id="price_label" name="wpmp_list[base_price]"  value="<?php if(isset($base_price))echo number_format((double)$base_price,2);?>">
    </td></tr>
    <tr id="sales_price"><td><abbr title="<?php echo __('If product has a discounted price which is lower then regular price (base price)','wpmarketplace'); ?>" ><?php echo __('Sales Price','wpmarketplace'); ?></abbr></td><td><input type="text" size="16" id="price_labe" name="wpmp_list[sales_price]"  value="<?php if(isset($sales_price))echo number_format((double)$sales_price,2);?>"></td></tr>  
    </table>
    </td></tr> 
     
     
    <tr><td><input type="checkbox" <?php if(isset($price_variation)) echo "checked='checked'"; else echo "";?> name="wpmp_list[price_variation]" id="price_variation" name="price_variation"> Variation</td></tr>       
    </table>
    
    <div id="price_dis_table" style="<?php if(isset($price_variation)) echo ""; else echo "display: none;";?>">
        <div id="vdivs">
        <?php 
    if(isset($variation)){
        //show variations
        //echo "<pre>";        print_r($variation); echo "</pre>";
        foreach($variation as $key=>$vname){
            
            ?>
            <div id="variation_div_<?php echo $key;?>" class="postbox" width="100%" style="margin: 10px; ">
            <img class="delet_vdiv" rel="variation_div_<?php echo $key;?>" title="delete this variation" src="<?php echo plugins_url("wpmarketplace");?>/images/remove.png" alt="">
            <table border="0" id="voption_table_<?php echo $key;?>">
            <tr>
             <td><label>Multiple Select:</label></td>
             <td><input type="checkbox" name="wpmp_list[variation][<?php echo $key;?>][multiple]" placeholder="Multiple Select" <?php if(isset($vname['multiple'])) echo "checked='checked'"; ?> ></td>
            </tr>    
            <tr><td colspan="3"><input type="text" name="wpmp_list[variation][<?php echo $key;?>][vname]" id="" placeholder="variation name" value="<?php echo $vname['vname'];?>"></td></tr>
         <?php
    if($vname){
        foreach($vname as $optionkey=>$optionval){
            if($optionkey!="vname" && $optionkey != "multiple"){
?>
            <tr id="voption<?php echo $optionkey;?>"><td><input type="text" name="wpmp_list[variation][<?php echo $key;?>][<?php echo $optionkey;?>][option_name]"  placeholder="option name" value="<?php echo $optionval['option_name'];?>"></td><td><input type="text" name="wpmp_list[variation][<?php echo $key;?>][<?php echo $optionkey;?>][option_price]" id="" placeholder="price" value="<?php echo $optionval['option_price'];?>"></td><td><img class="delet_voption" rel="voption<?php echo $optionkey;?>" title="delete this option" src="<?php echo plugins_url("wpmarketplace");?>/images/remove.png" alt=""></td></tr>
         <?php
            }
        }
    }
?>
         </table>
         <div style="clear: both;"></div>
         <input type="button" class="button add_voption" rel="<?php echo $key;?>" value="Add Option">
         </div>
            <?php
        }
    }else{
?>
         <div id="variation_div1" class="postbox" width="100%" style="margin: 10px; ">
         <img class="delet_vdiv" rel="variation_div1" title="delete this variation" src="<?php echo plugins_url("wpmarketplace");?>/images/remove.png" alt="">
         <table border="0" id="voption_table_1">
         <tr>
             <td><label>Multiple Select:</label></td>
             <td><input type="checkbox" name="wpmp_list[variation][1][multiple]" placeholder="Multiple Select"></td>
         </tr>
         <tr>
             <td colspan="3"><input type="text" name="wpmp_list[variation][1][vname]" id="" placeholder="variation name"></td>
         </tr>
         <tr id="voption1">
             <td><input type="text" name="wpmp_list[variation][1][1][option_name]"  placeholder="option name"></td>
             <td><input type="text" name="wpmp_list[variation][1][1][option_price]" id="" placeholder="price"></td>
             <td><img class="delet_voption" rel="voption1" title="delete this option" src="<?php echo plugins_url("wpmarketplace");?>/images/remove.png" alt=""></td>
         </tr>
         </table>
         <div style="clear: both;"></div>
         <input type="button" class="button add_voption" rel="1" value="Add Option">
         
         </div>
         <?php
    }
?>
         </div>
         <input type="button" class="button" id="add_variation" value="Add Variation">
         </div>
        <script type="text/javascript">
        jQuery('#price_variation').click(function(){
            if(jQuery('#price_variation').attr("checked")){
                
                jQuery('#variation_heading').text("Variation Options"); 
                jQuery('#price_dis_table').show();
                
            }else{
                  
                  jQuery('#variation_heading').text("Pricing"); 
                  jQuery('#price_dis_table').hide()  ;
            }
        });
        jQuery('#add_variation').live("click",function (){
            var tm=new Date().getTime();
            jQuery('#vdivs').append('<div id="variation_div_'+tm+'" class="postbox" width="100%" style="margin: 10px; "><img class="delet_vdiv" rel="variation_div_'+tm+'" title="delete this variation" src="<?php echo plugins_url("wpmarketplace");?>/images/remove.png" alt=""><table border="0" id="voption_table_'+tm+'"><tr><td><label>Multiple Select:</label></td><td><input type="checkbox" name="wpmp_list[variation]['+tm+'][multiple]" placeholder="Multiple Select"></td></tr><tr><td colspan="3"><input type="text" name="wpmp_list[variation]['+tm+'][vname]" id="" placeholder="variation name "></td></tr><tr id="voption_'+tm+'"><td><input type="text" name="wpmp_list[variation]['+tm+']['+tm+'][option_name]" id="" placeholder="option name"></td><td><input type="text" name="wpmp_list[variation]['+tm+']['+tm+'][option_price]" id="" placeholder="price"></td><td><img class="delet_voption" rel="voption_'+tm+'" title="delete this option" src="<?php echo plugins_url("wpmarketplace");?>/images/remove.png" alt=""></td></tr></table><div style="clear: both;"></div><input type="button" class="button add_voption" rel="'+tm+'" value="Add Option"></div>');
        });
        jQuery('.delet_vdiv').live("click",function(){
            if(confirm("Are you sure to remove"))
                jQuery('#'+jQuery(this).attr("rel")).remove();
        });
        jQuery('.add_voption').live("click",function (){
            var tm=new Date().getTime();
            jQuery('#voption_table_'+jQuery(this).attr("rel")).append('<tr id="voption_'+tm+'"><td><input type="text" name="wpmp_list[variation]['+jQuery(this).attr("rel")+']['+tm+'][option_name]"  placeholder="option name"></td><td><input type="text" name="wpmp_list[variation]['+jQuery(this).attr("rel")+']['+tm+'][option_price]" id="" placeholder="price"></td><td><img class="delet_voption" rel="voption_'+tm+'" title="delete this option" src="<?php echo plugins_url("wpmarketplace");?>/images/remove.png" alt=""></td></tr>');
        });

        jQuery('.delet_voption').live("click",function(){
            if(confirm("Are you sure to remove"))
                jQuery('#'+jQuery(this).attr("rel")).remove();
        });


        </script>
        </div>
        </div>
    
      <div style="width: 50%; float: left;">
        <div class="postbox" style="width: 100%;float: right;">
        <h3>Role Based Discount</h3>
         <table width="100%" style="margin: 10px;">
         <tr><th align="left">Role</th><th align="left">Discount (%)</th></tr>
         <tr><td width="250px">Guest (guest) </td><td><input type="text" size="8" name="wpmp_list[discount][guest]" value="<?php if(isset($discount['guest'])) echo $discount['guest']; ?>"></td></tr>     
             <?php
        global $wp_roles;
        $roles = array_reverse($wp_roles->role_names);
        foreach( $roles as $role => $name ) { 
        if(  isset($currentAccess) ) $sel = (in_array($role,$currentAccess))?'checked':'';
        
        ?>
        <tr><td><?php echo $name; ?> (<?php echo $role; ?>) </td><td><input type="text" size="8" name="wpmp_list[discount][<?php echo $role; ?>]" value="<?php if(isset($discount[$role])) echo $discount[$role]; ?>"></td></tr>     
        
        <?php } ?>     
        </table>
        </div>
        
        <div class="postbox" style="width: 100%;float: left;">
        <h3>Coupon Discount</h3>
         <table id="coupon_table"  width="100%" style="margin: 10px;">
         <tr><th align="left">Coupon Code</th><th align="left">Discount(%)</th></tr>
         <?php
        if(isset($coupon_code) && count($coupon_code)>0){
            foreach($coupon_code as $coupon_key=>$coupon_val){
            ?>
         <tr><td width="250px"> <input type="text" size="8"  name="wpmp_list[coupon_code][<?php echo $coupon_key?>]"  value="<?php echo $coupon_code[$coupon_key];?>"></td><td><input type="text" size="8" name="wpmp_list[coupon_discount][<?php echo $coupon_key?>]"  value="<?php echo $coupon_discount[$coupon_key];?>"></td></tr> 
              <?php
            }
        }
    ?>    
         </table>
         
         <table  width="100%" style="margin: 10px;"> 
         <tr><td width="250px">Code <input type="text" size="16" id="coupon_code"  value=""></td><td>Discount <input type="text" size="8" id="coupon_discount"  value=""></td></tr> 
         <tr><td width="250px"></td><td><input class="button" type="button" size="8" id="add_coupon" value="Add"></td></tr>         
         </table>
            <script type="text/javascript">
             
            var cdtm=new Date().getTime();
            jQuery('#add_coupon').live("click",function (){
                var coupon_code=jQuery('#coupon_code').val();
                var coupon_discount= jQuery('#coupon_discount').val();
                jQuery('#coupon_table').append('<tr><td width="250px"> <input size="8" type="text" name="wpmp_list[coupon_code]['+cdtm+']" value="'+coupon_code+'"></td><td><input type="text" size="8" name="wpmp_list[coupon_discount]['+cdtm+']" value="'+coupon_discount+'"></td></tr>');
                jQuery('#coupon_code').val("");
                jQuery('#coupon_discount').val("");
            });
            </script>
            </div>
    </div>

    


              
     <div style="clear: both;"></div>