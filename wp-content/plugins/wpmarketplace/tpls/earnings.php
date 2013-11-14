<?php
global $wpdb, $current_user;
$uid = $current_user->ID;
$sql = "select p.*,i.price,i.license,i.quantity, o.date from {$wpdb->prefix}mp_orders o,
                      {$wpdb->prefix}mp_order_items i,
                      {$wpdb->prefix}posts p 
                      where p.post_author=$uid and 
                            i.oid=o.order_id and 
                            i.pid=p.ID and 
                            o.payment_status='Completed' order by o.date desc";

$sales = $wpdb->get_results($sql);   
$sql = "select sum(i.price) from {$wpdb->prefix}mp_orders o,
                      {$wpdb->prefix}mp_order_items i,
                      {$wpdb->prefix}posts p 
                      where p.post_author=$uid and 
                            i.oid=o.order_id and 
                            i.pid=p.ID and 
                            o.payment_status='Completed'";

$total_sales = $wpdb->get_var($sql); 

$sql = "select sum(amount) from {$wpdb->prefix}mp_withdraw 
                      where userid=$uid";

$total_withdraws = $wpdb->get_var($sql);
if(empty($total_withdraws))$total_withdraws=0;                                                    
if(get_user_meta($uid,'w_req',true)==1) { delete_user_meta($uid,'w_req');
?>
<blockquote class="success"><b><?php echo __("Withdraw request","wpmarketplace");?></b><br/><?php echo __("Withdraw request submitted successfully.","wpmarketplace");?></blockquote>
<?php } ?>
<style type="text/css">

table td{
    border:none;
}</style>
<table style="margin-left: 20px; border: none; width: 97%"><tr><td><strong><?php echo __("Total Sales:","wpmarketplace");?> <?php echo number_format($total_sales,2); ?> usd</strong></td><td><strong><?php echo __("Total Withdraws:","wpmarketplace");?> <span class="withdraw_balance"><?php echo number_format($total_withdraws,2); ?></span> <?php echo __("usd","wpmarketplace");?></strong></td><td><strong><?php echo __("Balance:","wpmarketplace");?> <span class="actual_balance"><?php echo $balance = number_format($total_sales-$total_withdraws,2); ?></span> <?php echo __("usd","wpmarketplace");?></strong></td><td><form action="" method="post" id="withdraw"><input style="display: inline;margin: 0; height: 10px; width: 40px;" type="text" name="amount" id="amount"><input type="hidden" name="action" value="withdraw_request"><input type="hidden" name="withdraw" value="1"><input   style="font-weight:normal;font-size:9pt" class="button green"  type="submit" value="Withdraw"><span id="loading" style="display: none;"><img src="wp-admin/images/loading.gif" alt=""> <?php echo __("saving...","wpmarketplace");?></span>
</form></td></tr></table>
<table class="wpmp-my-orders">
<tr><th><?php echo __("Date","wpmarketplace");?><th><?php echo __("Item","wpmarketplace");?></th><th><?php echo __("License","wpmarketplace");?><th><?php echo __("Quantity","wpmarketplace");?></th><th><?php echo __("Price","wpmarketplace");?></th></tr>

<?php foreach($sales as $sale){ ?>
<tr><td><?php echo date("Y-m-d H:i",$sale->date); ?></td><td><?php echo $sale->post_title; ?></td><td><?php echo $sale->license; ?></td><td><?php echo $sale->quantity; ?></td><td><?php echo $sale->price; ?></td></tr>    
<?php } ?>
</table>

<script type="text/javascript">
var wd=<?php echo $total_withdraws;?>;
var ab=<?php echo $balance;?>;
jQuery('#withdraw').submit(function(){
    if(parseInt(jQuery('#amount').val())<=ab){
        jQuery(this).ajaxSubmit({
        'url':'<?php echo home_url();?>/',
        'beforeSubmit' : function(){
            jQuery('#loading').fadeIn();
        },
        'success':function(res){
           jQuery('#loading').fadeOut();
           jQuery('.withdraw_balance').text(parseInt(jQuery('#amount').val())+parseInt(jQuery('.withdraw_balance').text())); 
           ab=(parseInt(jQuery('.actual_balance').text()))-jQuery('#amount').val();
           jQuery('.actual_balance').text(ab);
           wd = wd+parseInt(jQuery('#amount').val());
          
        }
    });
    }else{
        alert("you can withdraw maximum "+(ab));
    }
    return false;
});
</script>