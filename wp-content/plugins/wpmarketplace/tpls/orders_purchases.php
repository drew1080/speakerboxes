<?php  
global $wpdb, $sap;
$orderurl = get_permalink(get_the_ID());
$loginurl = home_url("/wp-login.php?redirect_to=".urlencode($orderurl));
if ( !is_user_logged_in() ) {

$_ohtml =<<<SIGNIN
      
<center>
Please <a href="{$loginurl}" class="simplemodal-login"><b>Log In or Register</b></a> to access this page
</center>

SIGNIN;

     
} else { 
if($_GET['id']==''&&$_GET['item']=='')    {
    $orderid=__("Order Id","wpmarketplace");
    $date=__("Date","wpmarketplace");
    $payment_status=__("Payment Status","wpmarketplace");
$_ohtml = <<<ROW
<table class="table" width="100%" cellspacing="0">
<tr>
    <th>$orderid</th>
    <th>$date</th>
    <th style="width: 180px;">$payment_status</th>
    
</tr>

ROW;


foreach($myorders as $order){ 
    $date = date("Y-m-d h:i a",$order->date);
    $items = unserialize($order->items);    
    if($dashboard){
        $zurl = $orderurl . $sap."section=my-orders-sc&";
    }
    else {
        $zurl = $orderurl . $sap;
    }
    
    $_ohtml .= <<<ROW
                    <tr class="order">
                        <td><a href='{$zurl}id={$order->order_id}'>{$order->order_id}</a></td>
                        <td>{$date}</td>
                        <td>{$order->order_status}</td>
                        
                    </tr>                    
ROW;

}
$homeurl = home_url('/');
$_ohtml .=<<<END
</table>
<script language="JavaScript">
<!--
  function getkey(file, order_id){
      jQuery('#lic_'+file+'_'+order_id).html('Please Wait...');
      jQuery.post('{$homeurl}',{action:'wpdm_pp_ajax_call',execute:'getlicensekey',fileid:file,orderid:order_id},function(res){
           jQuery('#lic_'+file+'_'+order_id).html("<input type=text style='width:150px;border:0px' readonly=readonly onclick='this.select()' value='"+res+"' />");
      });
  }
//-->
</script>

END;
}
$odetails   = __("Order Details","wpmarketplace");
$ostatus    = __("Order Status","wpmarketplace");
$prdct      = __("Product","wpmarketplace");
$qnt        = __("Quantity","wpmarketplace");
$unit       = __("Unit Price","wpmarketplace");
$coup       = __("Coupon Discount","wpmarketplace");
$role_dis   = __("Role Discount","wpmarketplace");
$ttl        = __("Total","wpmarketplace");
$dnl        = __("Download","wpmarketplace");
$csign = get_option('_wpmp_curr_sign','$');

if($_GET['id']!=''&&$_GET['item']==''){
$order = $order->GetOrder($_GET['id']);
$cart_data = unserialize($order->cart_data);
$items = Order::GetOrderItems($order->order_id);
$order->title = $order->title?$order->title:'Order # '.$order->order_id;

//echo "<pre> order items = "; print_r($items); echo "</pre>";


$_ohtml = <<<OTH
    
<table class="table" width="100%" cellspacing="0">
<caption><b>{$order->title} &#187; $odetails </b> <br>
$ostatus : {$order->order_status}
&nbsp;</caption>
<tr>
    <th>$prdct</th>
    <th>$qnt</th>     
    <th>$unit</th>     
    <th>$coup</th>
    <th>$role_dis</th>
    <th class='text-right' align='right'>$ttl</th>     
    <th class='text-right' align='right'>$dnl</th>
</tr>

OTH;

$total=0;

foreach($items as $item){
    
    $ditem = get_post($item['pid']);
     $meta = get_post_meta($ditem->ID,'wpmp_list_opts',true);
     $price = $item['price']*$item['quantity'];
     
     $discount_r = $meta['discount'][$role];
     //$discount = $price*($discount_r/100);
     //$aprice = $price - $discount;
     
     
     $prices=0;
    $variations="";
    //print_r(get_post_meta($oitem->pid,"wpmp_list_opts",true));
    
     @extract(get_post_meta($item['pid'],"wpmp_list_opts",true));
    foreach($variation as $key=>$value){
        foreach($value as $optionkey=>$optionvalue){
          if($optionkey!="vname"){
             if($cart_data[$item['pid']]['variation']){
                //echo "adfadf";
                foreach($cart_data[$item['pid']]['variation'] as $var){
                //echo  $optionkey;                  
                    if($var==$optionkey){
                        $prices+=$optionvalue['option_price'];
                        $variations.=$optionvalue['option_name']."=".$optionvalue['option_price']." ";
                        
                    }
                }
                
        
            }    
          }
        }
    } 
    
    $discount=($discount_r/100)*($item['price']+$prices);
    
    
    //$itotal = number_format($item['price']*$item['quantity'],2);
    //$item['price'] = number_format($item['price'],2);
    $itotal = number_format(((($item['price']+$prices)*$item['quantity'])-$discount-$item['coupon_amount']),2);
    $total += $itotal;
    $download_link = home_url("/?wpmpfile={$item['pid']}&oid={$order->order_id}");    
    //$licenseurl = home_url("/?task=getlicensekey&file={$itemid}&oid={$order->order_id}");     
    $_ohtml .= <<<ITEM
                    <tr class="item">
                        <td>{$ditem->post_title} <br> {$variations}</td>
                        <td>{$item[quantity]}</td>                       
                        <td>{$csign}{$item[price]}</td>
                        <td>{$csign}{$item[coupon_amount]}</td>
                        <td>{$csign}{$discount}</td>
                        <td class='text-right' align='right'>{$csign}{$itotal}</td>
ITEM;
@extract(get_post_meta($item['pid'],"wpmp_list_opts",true));
if($digital_activate){
if($order->payment_status=='Completed'){
$_ohtml .= <<<ITEM
                                           
                        <td class='text-right' align='right'><a href="{$download_link}">$dnl</a></td>                        
                    </tr>
ITEM;
}else{
$_ohtml .= <<<ITEM
                        <td  class='text-right' align='right'>&mdash;</td>                        
                    </tr>
ITEM;
}
} else {
  $_ohtml .= "<td  class='text-right' align='right'>&mdash;</td>";  
}

}
$dsct=__("Discount","wpmarketplace");
$shping=__("Shipping","wpmarketplace");
$cdetails=__("Customer details","wpmarketplace");
$eml=__("Email","wpmarketplace");
$bling=__("Billing Address","wpmarketplace");
$shing_ad=__("Shipping Address","wpmarketplace");
$vdlink=__("If you still want to complete this order:","wpmarketplace");
$pnow=__("Pay Now","wpmarketplace");

$usermeta=unserialize(get_user_meta($current_user->ID, 'user_billing_shipping',true));
@extract($usermeta);
$order->cart_discount = number_format($order->cart_discount,2);
$order->shipping_cost = number_format($order->shipping_cost,2);
$order->total = number_format($order->total,2);
$_ohtml .= <<<ITEM
                        <tr class="item">
                        <td colspan="5" class='text-right' align='right'><b>$dsct</b></td>                        
                        <td class='text-right' align='right'><b>{$csign}{$order->cart_discount}</b></td>
                        <td>&nbsp;</td>                       
                    </tr>
                    <tr class="item">
                        <td colspan="5" class='text-right' align='right'><b>$shping</b></td>                        
                        <td class='text-right' align='right'><b>{$csign}{$order->shipping_cost}</b></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="item">
                        <td colspan="5" class='text-right' align='right'><b>$ttl</b></td>                        
                        <td class='text-right' align='right'><b>{$csign}{$order->total}</b></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                    <td colspan="7">
                    <header>
                        <h2>$cdetails</h2>
                    </header>
                    
                    <dl class="customer_details">
<dt>{$eml}:</dt><dd>$current_user->user_email</dd></dl>

<div class="col2-set addresses" style="width:580px;">

    <div class="col-1" style="float:left">

    
        <header class="title">
            <h3>$bling</h3>
        </header>
        <address><p>
            $billing[first_name] $billing[last_name]<br>
$billing[company]<br>
$billing[address_1]<br>
$billing[address_2]<br>
$billing[city]<br>
$billing[state]<br>
$billing[postcode]<br>
$billing[country]        </p></address>


    </div><!-- /.col-1 -->
    
    <div class="col-2" style="float:right; margin:5px;">
    
        <header class="title">
            <h3>$shing_ad</h3>
        </header>
        <address><p>
            $shippingin[first_name] $shippingin[last_name]<br>
$shippingin[company]<br>
$shippingin[address_1]<br>
$shippingin[address_2]<br>
$shippingin[city]<br>
$shippingin[state]<br>
$shippingin[postcode]<br>
$shippingin[country]        </p></address>

    </div><!-- /.col-2 -->

</div>

                    </td>
                    </tr>
ITEM;

if($order->payment_status!='Completed'){
    $purl = home_url('/?pay_now='.$order->order_id);
    $_ohtml .= <<<PAY
    <tr class="items"><td colspan="7">$vdlink <div id="proceed_{$order->order_id}" class='pull-right'>    
          <a class='btn' onclick="return proceed2payment_{$order->order_id}(this)" href="#"><b>$pnow</b></a>        
         <script>
         function proceed2payment_{$order->order_id}(ob){
            jQuery('#proceed_{$order->order_id}').html('Processing...');
             
            jQuery.post('{$purl}',{action:'wpmp_pp_ajax_call',execute:'PayNow',order_id:'{$order->order_id}'},function(res){
                jQuery('#proceed_{$order->order_id}').html(res);
                });
                
                return false;
         }
         </script>
     
    </div></td></tr>
PAY;
}    

$homeurl = home_url('/');
$_ohtml .=<<<EOT
</table>
<script language="JavaScript">
<!--
  function getkey(file, order_id){
      jQuery('#lic_'+file+'_'+order_id).html('Please Wait...');
      jQuery.post('{$homeurl}',{action:'wpdm_pp_ajax_call',execute:'getlicensekey',fileid:file,orderid:order_id},function(res){
           jQuery('#lic_'+file+'_'+order_id).html("<input type=text style='width:150px;border:0px' readonly=readonly onclick='this.select()' value='"+res+"' />");
      });
  }
//-->
</script>

EOT;

}
    $_ohtml = "<div class='wp-marketplace'>{$_ohtml}</div>"; 
}
?>