<?php
    
    global $wpdb;
    $order->items = unserialize($order->items);
    $oitems = $wpdb->get_results("select * from {$wpdb->prefix}mp_order_items where oid='{$order->order_id}'");
    $user = new WP_User( $order->uid );
    $role = $user->roles[0];
    $tax = $order1->wpmp_calculate_tax($order->order_id);
    
    $settings = maybe_unserialize(get_option('_wpmp_settings')); 
    $total_coupon = get_all_coupon(unserialize($order->cart_data));
    
    
?>
<div class="wrap">
    <div class="icon32"><img src='<?php echo plugins_url('wpmarketplace/images/order.png');?>' /></div>
<h2>View Order <img id="lng" style="display: none;" src="images/loading.gif" /></h2>
<div id="msg" style="padding: 5px 10px;display: none;" class="message updated"><?php echo __("Message","wpmarketplace");?></div>
<div style="float: right;width:400px;">
<table class="widefat fixed">
<tr><th align="left" colspan="2"><?php echo __("Order Summary","wpmarketplace");?></th></tr>
<tr><td><?php echo __("Order ID:","wpmarketplace");?></td><td><?php echo $order->order_id;  ?></td></tr>
<tr><td><?php echo __("Coupon Discount:","wpmarketplace");?></td><td><?php echo $currency_sign.$total_coupon;  ?></td></tr>
<tr><td><?php echo __("Order Discount:","wpmarketplace");?></td><td><?php echo $currency_sign.$order->cart_discount;  ?></td></tr>
<?php 
if(count($tax)>0){
    foreach($tax as $taxrow){
      ?>
      <tr><td><?php echo $taxrow['label'];?></td><td><?php echo $currency_sign. $taxrow['rates'];?></td></tr>
      <?php  
    }
}
?>
<tr><td><?php echo __("Shipping:","wpmarketplace");?></td><td><?php echo $currency_sign.$order->shipping_cost;  ?></td></tr>
<tr><td><?php echo __("Order Total:","wpmarketplace");?></td><td><?php echo $currency_sign.number_format($order->total,2);  ?></td></tr>
<tr><td><?php echo __("Order Date:","wpmarketplace");?></td><td><?php echo date("M d, Y",$order->date);  ?></td></tr>
</table>
</div>
<div style="float: left;width: 500px;">
<table class="widefat fixed">
<tr><th align="left" colspan="2"><?php echo __("Customer Info","wpmarketplace");?></th></tr>
<tr><td><?php echo __("Customer ID:","wpmarketplace");?></td><td><?php echo $user->ID; ?></td></tr>
<tr><td><?php echo __("Customer Name:","wpmarketplace");?></td><td><?php echo $user->display_name; ?></td></tr>
<tr><td><?php echo __("Customer Email:","wpmarketplace");?></td><td><?php echo $user->user_email; ?></td></tr>
</table>
</div>
<div style="clear: both;"></div>
<h2 style="font-size: 12pt"><?php echo __("Order Items","wpmarketplace");?></h2>
<table width="100%" cellspacing="0" class="widefat fixed">
<thead>
<tr><th align="left"><?php echo __("Item Name","wpmarketplace");?></th>
    <th align="left"><?php echo __("Unit Price","wpmarketplace");?></th>
    <th align="left"><?php echo __("Quantity","wpmarketplace");?></th>
    <th align="left"><?php echo __("Discount","wpmarketplace");?></th>
    <th align="left"><?php echo __("Coupon Code","wpmarketplace");?></th>
    <th align="left"><?php echo __("Coupon Discount","wpmarketplace");?></th>
    <th align="left"><?php echo __("Total","wpmarketplace");?></th>
    <th align="left"><?php echo __("Subtotal","wpmarketplace");?></th>
</tr>
</thead>
<?php 
$cart_data = unserialize($order->cart_data); 
 //print_r($cart_data);
foreach($oitems as $oitem){  
     $ditem = get_post($oitem->pid);
     $meta = get_post_meta($ditem->ID,'wpmp_list_opts',true);
     $price = $oitem->price*$oitem->quantity;
     
     $discount_r = $meta['discount'][$role];
     //$discount = $price*($discount_r/100);
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
    
    $discount=($discount_r/100)*($oitem->price+$prices);
     //echo "ziku - $discount";
     echo "<tr><td>{$ditem->post_title}<br> {$variations}</td>
               <td>".$currency_sign.number_format($oitem->price,2)."</td>
               <td>{$oitem->quantity}</td>
               <td>{$discount_r}%</td>
               <td>{$oitem->coupon}</td>
               <td>{$currency_sign}{$oitem->coupon_amount}</td>
               <td>{$currency_sign}".(($oitem->price+$prices)*$oitem->quantity)."</td>
               <td>".$currency_sign.number_format(((($oitem->price+$prices)*$oitem->quantity)-$discount-$oitem->coupon_amount),2);"</td>
         </tr>";
  
    ?>
    
    <?php
}
?>
</table>
<br />
<b><?php echo __("Order Status:","wpmarketplace");?> 
                <select id="osv" name="order_status">                                    
                <option <?php if($order->order_status=='Pending') echo 'selected="selected"'; ?> value="Pending">Pending</option>
                <option <?php if($order->order_status=='Processing') echo 'selected="selected"'; ?> value="Processing">Processing</option>
                <option <?php if($order->order_status=='Completed') echo 'selected="selected"'; ?> value="Completed">Completed</option>
                <option <?php if($order->order_status=='Canceled') echo 'selected="selected"'; ?> value="Canceled">Canceled</option>
                </select>
</b>   <input type="button" id="update_os" class="button button-secondary" value="Update">
&nbsp;
<b><?php echo __("Payment Status:","wpmarketplace");?> 
                <select id="psv" name="payment_status">                                    
                <option <?php if($order->payment_status=='Pending') echo 'selected="selected"'; ?> value="Pending">Pending</option>
                <option <?php if($order->payment_status=='Processing') echo 'selected="selected"'; ?> value="Processing">Processing</option>
                <option <?php if($order->payment_status=='Completed') echo 'selected="selected"'; ?> value="Completed">Completed</option>
                <option <?php if($order->payment_status=='Canceled') echo 'selected="selected"'; ?> value="Canceled">Canceled</option>
                </select>
</b>   <input id="update_ps" type="button" class="button button-secondary" value="Update">
   <input id="reduce_stock" type="button" class="button button-secondary" value="Reduce Stock">
   <input id="restore_stock" type="button" class="button button-secondary" value="Restore Stock">

</div>
<br /><br />
<?php
     //if($settings['stock']['reduce_auto']==1) echo "Automatic reduce stock is enabled";
?>
<script language="JavaScript">
<!--
  jQuery(function(){
     
      jQuery('#update_os').click(function(){
          jQuery('#lng').fadeIn();
          jQuery.post(ajaxurl,{action:'wpmp_ajax_call',execute:'update_os',order_id:'<?php echo $_GET[id]; ?>',status:jQuery('#osv').val()},function(res){
              jQuery('#msg').html(res).fadeIn();
              jQuery('#lng').fadeOut();
          });
      });
      
      jQuery('#update_ps').click(function(){
          jQuery('#lng').fadeIn();
          jQuery.post(ajaxurl,{action:'wpmp_ajax_call',execute:'update_ps',order_id:'<?php echo $_GET[id]; ?>',status:jQuery('#psv').val()},function(res){
              jQuery('#msg').html(res).fadeIn();
              jQuery('#lng').fadeOut();
          });
      });
      //reduce stock
      jQuery('#reduce_stock').click(function(){
          jQuery('#lng').fadeIn();
          jQuery.post(ajaxurl,{action:'wpmp_ajax_call',execute:'wpmp_reduce_stock',order_id:'<?php echo $_GET[id]; ?>'},function(res){
              jQuery('#msg').html(res).fadeIn();
              jQuery('#lng').fadeOut();
          });
      });
      //restore stock
      jQuery('#restore_stock').click(function(){
          jQuery('#lng').fadeIn();
          jQuery.post(ajaxurl,{action:'wpmp_ajax_call',execute:'wpmp_restore_stock',order_id:'<?php echo $_GET[id]; ?>'},function(res){
              jQuery('#msg').html(res).fadeIn();
              jQuery('#lng').fadeOut();
          });
      });
      
      
  });
//-->
</script>
