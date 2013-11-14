

<div class="step a-item" id="csor">
    
<form method='post' action='' id="order_form">
<?php
    $order = new Order();
    $order_info = $order->GetOrder($_SESSION['orderid']);
    $payment_method = $order_info->payment_method;
    $ship_method = $order_info->shipping_method;
    $ship_amount = $order_info->shipping_cost;
    $ooder_total = $order->CalcOrderTotal($_SESSION['orderid']);
?>

<?php
$settings = maybe_unserialize(get_option('_wpmp_settings'));
$currency_sign = get_option('_wpmp_curr_sign','$');
//print_r($currency_sign); echo "hello world";
 
//calculate shipping
if($settings['calc_shipping']==1){
    $ship=wpmp_calculate_shipping();
   
     $shipping_row = '<tr><td colspan=5 align=right>'.__('Shipping','wpmarketplace').' ('.$ship['method'].'):</td><td class="amt" id="s_cost">'.$currency_sign.number_format($ship['cost'],2).'</td></tr>';
}else{
    $shipping_row="";
}

//calculate tax

$tax_summery=$order->wpmp_calculate_tax();

if(count($tax_summery)>0){
    foreach($tax_summery as $taxrow){
        $tax_row.="<tr><td colspan=5 align=right>".$taxrow['label'].":</td><td class='amt' >".$currency_sign.number_format($taxrow['rates'],2)."</td></tr>"; 
    }
}else{
    $tax_row="";
}



 
 $grand_total = '<tr><td colspan=5 align=right>'.__('Grand Total:','wpmarketplace').'</td><td id="g_total" class="amt">'.$currency_sign.number_format($ooder_total,2).'</td></tr>';
 
    global $wpdb;
    $cart_data = wpmp_get_cart_data();
    //print_r($cart_data);
    foreach($cart_data as $pid=>$cdt){
        extract($cdt);
        if($pid){
            $cart_items[$pid] = get_post($pid,ARRAY_A);
            $cart_items[$pid]['quantity'] =  $quantity;
           $cart_items[$pid]['discount'] =  $discount;
            $cart_items[$pid]['variation'] =  $variation;
            $cart_items[$pid]['price'] =  (double)$price;
            if($coupon){
                $valid_coupon=check_coupon($pid,$coupon);
                if($valid_coupon!=0){
                    $cart_items[$pid]['coupon'] =  $coupon;
                    $cart_items[$pid]['coupon_discount'] =  $valid_coupon;
                }
                else
                    $cart_items[$pid]['error'] =  "Coupon does not exist";
            }
        }
    }
    
$cart = "<table class='wpdm_cart'><tr class='cart_header'><th style='width:20px !important'></th><th>".__("Title","wpmarketplace")."</th><th>".__("Unit Price","wpmarketplace")."</th><th> ".__("Coupon Code","wpmarketplace")."</th><th>".__("Quantity","wpmarketplace")."</th><th class='amt'>".__("Total","wpmarketplace")."</th></tr>";
if(is_array($cart_items)){
    //print_r($cart_items);
foreach($cart_items as $item){

    $prices=0;
    $variations="";
    @extract(get_post_meta($item['ID'],"wpmp_list_opts",true));
    //print_r($item['variation']);
    foreach($variation as $key=>$value){
        foreach($value as $optionkey=>$optionvalue){
          if($optionkey!="vname"){
             if($item['variation']){
                $cart.='<tr style="display:none">'; 
                foreach($item['variation'] as $var){                   
                    if($var==$optionkey){
                        $prices+=$optionvalue['option_price'];
                        $variations.=$optionvalue['option_name'].": ".number_format(doubleval($optionvalue['option_price']),2)." ";
                        $cart.='<td><input type="hidden" name="cart_items['.$item[ID].'][variation][]" value="'.$optionkey.'"></td>';
                    }
                }
                $cart.='</tr>';
        
            }    
          }
        }//echo $variations;
    }     

   
    if($item['coupon_discount']){$discount_amount=(($item['coupon_discount']/100)* ($item['price']+$prices)*$item[quantity]);$discount_style="style='color:#008000; text-decoration:underline;'";$discount_title='Discounted '.$currency_sign.$discount_amount." for coupon code '$discount_amount'";} else{ $discount_amount="";$discount_style="";$discount_title="";}
    if($item['error']){$coupon_style="style='border:1px solid #ff0000;'";$title=$item['error'];} else {$coupon_style="";$title="";}
    //filter for adding various message after cart item
    $cart_item_info="";
    $cart_item_info = apply_filters("wpmp_cart_item_info", $cart_item_info,$item['ID']);
    $cart .= "<tr id='cart_item_{$item[ID]}'><td></td><td class='cart_item_title'>$item[post_title]<br><small><i>$variations</i></small>".$cart_item_info."<input type='hidden' name='cart_items[$item[ID]][license]' value='$item[license]'></td><td class='cart_item_unit_price' $discount_style title='$discount_title'>".$currency_sign.number_format($item[price],2)."<input type='hidden' name='cart_items[$item[ID]][price]' value='$item[price]'></td><td><input $coupon_style title='$title' type='hidden' name='cart_items[$item[ID]][coupon]' value='$item[coupon]' id='$item[ID]' size=3 /><input type='hidden' name='cart_items[$item[ID]][coupon_amount]' value='$discount_amount' id='$item[ID]_dis' size=3 />$item[coupon]</td><td class='cart_item_quantity'><input type='hidden' name='cart_items[$item[ID]][quantity]' value='$item[quantity]' size=3 />$item[quantity]</td><td class='cart_item_subtotal amt'><input type='hidden' name='cart_items[$item[ID]][item_total]' value='".number_format((($item['price']+$prices)*$item['quantity'])-$discount_amount,2)."'>".$currency_sign.number_format((($item['price']+$prices)*$item['quantity'])-$discount_amount,2)." </td></tr>";
    
}}
$cart .= "

<tr><td colspan=5 align=right>".__("Cart Subtotal:","wpmarketplace")."</td><td class='amt' id='wpmp_cart_subtotal'>".$currency_sign.wpmp_get_cart_subtotal()."</td></tr>

".$tax_row."
<tr><td colspan=5 align=right>".__("Discount:","wpmarketplace")."</td><td class='amt' id='wpmp_cart_discount'><input type='hidden' name='cart_discount' value='".wpmp_get_cart_discount()."'>".$currency_sign.wpmp_get_cart_discount()."</td></tr>

".$shipping_row."
".$grand_total."
</table>





";
echo $cart;
?>
 <p id="order_comments_field" class="form-row notes">
                    <label  class="" for="order_comments"><?php echo __("Order Notes","wpmarketplace"); ?></label>
                    <textarea rows="2" cols="5" placeholder="Notes about your order, e.g. special notes for delivery." id="order_comments" class="input-text" name="order_comments"></textarea>
 </p>
<button id="order_back" class="button btn" type="button"><?php echo __("Back","wpmarketplace");?></button> 
<button id="order_btn" class="button btn btn-success" type="submit"><?php echo __("Place Order","wpmarketplace");?></button>
<input type='hidden' name='payment_system' id="payment_system" value='<?php echo $payment_method;?>' />
<input type='hidden' name='order_total' id="order_total" value='<?php echo $payment_method;?>' />
<input type='hidden' name='ship_method' id="ship_method" value='<?php echo $ship_method;?>' />
<input type='hidden' name='ship_currency' id="ship_currency" value='' />
<input type='hidden' name='ship_amount' id="ship_amount" value='<?php echo $ship_amount;?>' />
</form>
<div id="wpmpplaceorder"></div>
</div>
