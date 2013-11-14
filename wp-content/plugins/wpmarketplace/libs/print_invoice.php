<?php
function wpmp_print_invoice(){
   if(isset($_GET['task']) && $_GET['task']=="print_invoice"){
       if($_GET['id']!=''&& $_GET['item']==''){
     global $wpdb;
global $current_user, $_ohtml;
    get_currentuserinfo();
    $order = new Order();         
    $myorders = $order->GetOrders($current_user->ID);
    $_ohtml = '';  
     
$order = $order->GetOrder($_GET['id']);
$cart_data = unserialize($order->cart_data);
$items = array_keys($cart_data);

$oitems = $wpdb->get_results("select * from {$wpdb->prefix}mp_order_items where oid='{$order->order_id}'");
$user = new WP_User( $order->uid );
$role = $user->roles[0];
    
$ototal= number_format($order->total,2);
$dat=date("M d, Y",$order->date);
$pluginurl=plugins_url("wpmarketplace/images/");
$orderid_string=__("Order ID:","wpmarketplace");
$ordersummary_string=__("Order Summary","wpmarketplace");
$orderdiscount_string=__("Order Discount:","wpmarketplace");
$ordershipping_string=__("Shipping:","wpmarketplace");
$ordertotal_string=__("Order Total:","wpmarketplace");
$orderdate_string=__("Order Date:","wpmarketplace");
$customerid_string=__("Customer ID:","wpmarketplace");
$customerdetails_string=__("Customer Details","wpmarketplace");
$customername_string=__("Customer Name:","wpmarketplace");
$customeremail_string=__("Customer Email:","wpmarketplace");
$itemname_string=__("Item Name","wpmarketplace");
$unitprice_string=__("Unit Price","wpmarketplace");
$quantity_string=__("Quantity","wpmarketplace");
$discount_string=__("Discount","wpmarketplace");
$subtotal_string=__("Subtotal","wpmarketplace");
$download_string=__("Download","wpmarketplace");
$_ohtml = <<<OTH
<style type="text/css">
tr.odd {
    background: none repeat scroll 0 0 #E1FFE1;
}
</style>
<div class="orderdiv">
<img src="$pluginurl/logo.png"><br>
<div style="float: right;width:350px;">
<table class="widefat fixed">
<tr><th align="left" colspan="2">$ordersummary_string</th></tr>
<tr><td>$orderid_string</td><td>$order->order_id</td></tr>
<tr><td>$orderdiscount_string</td><td>$ $order->cart_discount</td></tr>
<tr><td>$ordershipping_string</td><td>$ $order->shipping_cost</td></tr>
<tr><td>$ordertotal_string</td><td>$ $ototal</td></tr>
<tr><td>$orderdate_string</td><td>$dat</td></tr>
</table>
</div>

<div style="float: left;width: 400px;">
<table class="widefat fixed">
<tr><th align="left" colspan="2">$customerdetails_string</th></tr>
<tr><td>$customerid_string</td><td> $user->ID</td></tr>
<tr><td>$customername_string</td><td> $user->display_name</td></tr>
<tr><td>$customeremail_string</td><td> $user->user_email</td></tr>
</table>
</div>

OTH;

$_ohtml.= '<div style="clear: both;"></div> <hr><table width="100%" cellspacing="0" class="widefat fixed">
<thead>
<tr><th align="left">'.$itemname_string.'</th><th align="left">'.$unitprice_string.'</th><th align="left">'.$quantity_string.'</th><th align="left">'.$discount_string.'</th><th align="left">'.$subtotal_string.'</th></tr>
</thead>';

$cart_data = unserialize($order->cart_data); 
   
 $count=0; 
foreach($oitems as $oitem){  
$count++;  
if($count%2==1) $trclass="odd";else $trclass="even";
     $ditem = get_post($oitem->pid);
     $meta = get_post_meta($ditem->ID,'wpmp_list_opts',true);
     $price = $oitem->price*$oitem->quantity;
     
     $discount_r = $meta['discount'][$role];
     $discount = $price*($discount_r/100);
     $aprice = $price - $discount;
     
     
     $prices=0;
    $variations="";
    //print_r(get_post_meta($oitem->pid,"wpmp_list_opts",true));
      
     @extract(get_post_meta($oitem->pid,"wpmp_list_opts",true));
    foreach($variation as $key=>$value){
        foreach($value as $optionkey=>$optionvalue){
          if($optionkey!="vname"){
             if($cart_data[$oitem->pid]['variation']){
                //echo "adfadf";
                foreach($cart_data[$oitem->pid]['variation'] as $var){
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
     
     $_ohtml.= "<tr class={$trclass}><td>{$ditem->post_title}<br> {$variations}</td>
               <td>$".number_format($oitem->price,2)."</td>
               <td>{$oitem->quantity}</td>
               <td>{$discount_r}%</td>
               <td>$".number_format(($aprice),2)."</td>";
     
        
    $dk = md5($ditem->files);    
    $wpmpdl = base64_encode($ditem->ID.'.'.$order->order_id);  
    $download_link = home_url("/?wpmpfile=$wpmpdl");  
      
    if($order->payment_status=='Completed'){
$_ohtml .= <<<ITEM
                                           
                        <td><a href="{$download_link}">$download_string</a></td>                        
                    
ITEM;
}else{
$_ohtml .= <<<ITEM
                        <td>&mdash;</td>                        
                    
ITEM;
}


 
         $_ohtml.="</tr>";
     
        
}

$_ohtml.= '</table><hr>';



        
if($order->payment_status!='Completed'){
    $purl = home_url('/?pay_now='.$order->order_id);
    $_ohtml .= <<<PAY
    <tr class="items"><td colspan="4">Please complete your payment to view download links. <div id="proceed_{$order->order_id}" style="float:right">    
         <select name="payment_method" id="pgdd_{$order->order_id}" style="padding: 0px; margin: 0px;"><option value="PayPal">PayPal IPN</option>
</select> <a onclick="return proceed2payment_{$order->order_id}(this)" href="#"><b>Pay Now</b></a>        
         <script>
         function proceed2payment_{$order->order_id}(ob){
            jQuery(ob).html('Processing...');
            jQuery('#pgdd_{$order->order_id}').attr('disabled','disabled');
            
            jQuery.post('{$purl}',{task:'paymentfront',action:'wpmp_ajax_call',execute:'PayNow',order_id:'{$order->order_id}',payment_method:jQuery('#pgdd_{$order->order_id}').val()},function(res){
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

window.print();
  function getkey(file, order_id){
      jQuery('#lic_'+file+'_'+order_id).html('Please Wait...');
      jQuery.post('{$homeurl}',{action:'wpmp_ajax_call',execute:'getlicensekey',fileid:file,orderid:order_id},function(res){
           jQuery('#lic_'+file+'_'+order_id).html("<input type=text style='width:150px;border:0px' readonly=readonly onclick='this.select()' value='"+res+"' />");
      });
  }
//-->
</script>

EOT;
  echo $_ohtml;
}
       die();
   } 
}
?>
