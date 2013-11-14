<?php
$settings = get_option('_wpmp_settings');
$currency_sign = get_option('_wpmp_curr_sign','$');
 
$cart = "<div class='wp-marketplace'><form method='post' class='abc' action='' name='cart_form'><input type='hidden' name='wpmp_update_cart' value='1' /><table class='wpdm_cart'><tr class='cart_header'><th style='width:20px !important'></th><th>".__("Title","wpmarketplace")."</th><th>".__("Unit Price","wpmarketplace")."</th><th> ".__("Coupon Code","wpmarketplace")."</th><th>".__("Quantity","wpmarketplace")."</th><th class='amt'>".__("Total","wpmarketplace")."</th></tr>";
if(is_array($cart_items)){
    //print_r($cart_items);
foreach($cart_items as $item){
    $prices=0;
    $variations="";
    @extract(get_post_meta($item['ID'],"wpmp_list_opts",true));
    $svariation = array() ;
    foreach($variation as $key=>$value){
        foreach($value as $optionkey=>$optionvalue){
          if($optionkey!="vname"){
             if($item['variation']){                
                foreach($item['variation'] as $var){                   
                    if($var==$optionkey){
                        $prices+=$optionvalue['option_price'];
                        $svariation[] = $optionvalue['option_name'].": ".($optionvalue['option_price']>0?'+':'').$currency_sign.number_format(doubleval($optionvalue['option_price']),2);
                        $variations .= '<input type="hidden" name="cart_items['.$item[ID].'][variation][]" value="'.$optionkey.'">';
                    }
                }
            }    
          }
        }
    }
    if($svariation)
    $variations .= "<small><i>".implode(", ",$svariation)."</i></small>";     
    
    if($item['coupon_discount']){$discount_amount=(($item['coupon_discount']/100)* ($item['price']+$prices)*$item[quantity]);$discount_style="style='color:#008000; text-decoration:underline;'";$discount_title='Discounted $'.$discount_amount." for coupon code '$discount_amount'";} else{ $discount_amount="";$discount_style="";$discount_title="";}
    if($item['error']){$coupon_style="style='border:1px solid #ff0000;'";$title=$item['error'];} else {$coupon_style="";$title="";}
    //filter for adding various message after cart item
    $cart_item_info="";
    $cart_item_info = apply_filters("wpmp_cart_item_info", $cart_item_info, $item['ID']);
    
    $cart .= "<tr id='cart_item_{$item[ID]}'><td><a class='wpmp_cart_delete_item' href='#' onclick='return wpmp_pp_remove_cart_item($item[ID])'><i class='icon icon-trash'></i></a></td><td class='cart_item_title'>$item[post_title]<br>$variations".$cart_item_info."<input type='hidden' name='cart_items[$item[ID]][license]' value='$item[license]'></td><td class='cart_item_unit_price' $discount_style ><span class='ttip' title='$discount_title'>".$currency_sign.number_format($item[price],2)."</span><input type='hidden' name='cart_items[$item[ID]][price]' value='$item[price]'></td><td><input $coupon_style title='$title' type='text' name='cart_items[$item[ID]][coupon]' value='$item[coupon]' id='$item[ID]' class='ttip' size=3 /><input type='hidden' name='cart_items[$item[ID]][coupon_amount]' value='$discount_amount' id='$item[ID]_dis'  /></td><td class='cart_item_quantity'><input type='text' name='cart_items[$item[ID]][quantity]' value='$item[quantity]' size=3 /></td><td class='cart_item_subtotal amt'>".$currency_sign.number_format((($item['price']+$prices)*$item['quantity'])-$discount_amount,2)." <input type='hidden' name='cart_items[$item[ID]][item_total]' value='".number_format((($item['price']+$prices)*$item['quantity'])-$discount_amount,2)."'></td></tr>";
    
}}
$cart .= "

<tr><td colspan=5 align=right class='text-right'>".__("Subtotal:","wpmarketplace")."</td><td class='amt' id='wpmp_cart_subtotal'>".$currency_sign.wpmp_get_cart_subtotal()."</td></tr>
<tr><td colspan=5 align=right class='text-right'>".__("Discount:","wpmarketplace")."</td><td class='amt' id='wpmp_cart_discount'>".$currency_sign.wpmp_get_cart_discount()."</td></tr>
<tr><td colspan=5 align=right class='text-right'>".__("Total:","wpmarketplace")."</td><td class='amt' id='wpmp_cart_total'>".     $currency_sign.number_format((double)str_replace(',','',wpmp_get_cart_subtotal())
- (double)str_replace(',','',wpmp_get_cart_discount()),2)."</td></tr>
<tr><td colspan=2><button type='button' class='btn btn-info ' onclick='location.href=\"".$settings['continue_shopping_url']."\"'><i class='icon-white icon-repeat'></i> ".__("Continue Shopping","wpmarketplace")."</button></td><td colspan=4 align=right class='text-right'><button class='btn btn-primary' type='button' onclick='document.cart_form.submit();'><i class='icon-white icon-edit'></i> ".__("Update Cart","wpmarketplace")."</button> <button class='btn btn-success' type='button' onclick='location.href=\"".get_permalink($settings['check_page_id'])."\"'><i class='icon-white icon-shopping-cart'></i> ".__("Checkout","wpmarketplace")."</button></td></tr>
</table>

</form></div>

<script language='JavaScript'>
<!--
    function  wpmp_pp_remove_cart_item(id){
           if(!confirm('Are you sure?')) return false;
           jQuery('#cart_item_'+id+' *').css('color','#ccc');
           jQuery.post('".home_url('?wpmp_remove_cart_item=')."'+id
           ,function(res){ 
           var obj = jQuery.parseJSON(res);
           
           jQuery('#cart_item_'+id).fadeOut().remove(); 
           jQuery('#wpmp_cart_total').html(obj.cart_total); 
           jQuery('#wpmp_cart_discount').html(obj.cart_discount); 
           jQuery('#wpmp_cart_subtotal').html(obj.cart_subtotal); });
           return false;
    }
    
jQuery(function(){
    jQuery('.ttip').tooltip();
});
      
//-->
</script>

";

if(count($cart_items)==0) $cart = __("No item in cart.","wpmarketplace")."<br/><a href='".$settings['continue_shopping_url']."'>".__("Continue shopping","wpmarketplace")."</a>";
