<?php
global $wpdb;
global $current_user;
$sql = "select * from {$wpdb->prefix}mp_withdraws where status<>1 order by date desc";
//0 pending balance, 1 completed, 
$payouts = $wpdb->get_results($sql);
?>
<h2><?php echo __("Balance","wpmarketplace");?></h2>
<table cellspacing="0" class="widefat fixed">
<thead>
<tr>
<th><?php echo __("Userid","wpmarketplace");?></th>
<th><?php echo __("Username","wpmarketplace");?></th>
<th><?php echo __("Amount","wpmarketplace");?></th>
<th><?php echo __("Status","wpmarketplace");?></th>
<th><?php echo __("Action","wpmarketplace");?></th>
</tr>
</thead>
<tbody>
<?php

foreach($payouts as $payout){
    $userrole=get_userdata($payout->uid)->roles[0];
 $payform='
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="payPalForm" name="payPalForm" >

<input type="hidden" name="item_number" value="product">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="business" value="'.get_user_meta($payout->uid,'payment_account',true).'">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="return" value="'.home_url().'/">
<input type="hidden" name="notify_url" value="'.home_url().'/?action=withdraw_paypal_notification">
<input name="item_name" type="hidden" id="item_name"  value="">
<input name="amount" type="hidden" id="amount" value="'.($payout->amount-($payout->amount*($comission[$userrole]/100))).'">
<input type="hidden" name="custom" value="'.$payout->id.'" >
<input name="sub" class="button" type="submit" id="sub" value="PayNow">
</form>
';
$pstatus="";
 if($payout->status==0) $pstatus="Pending";  
 echo "<tr><td >{$payout->uid}</td><td >".get_userdata($payout->uid)->display_name."</td><td >{$payout->amount}</td><td >{$pstatus}</td><td >{$payform}</td></tr>";   
}
?>
</tbody>

</table>




