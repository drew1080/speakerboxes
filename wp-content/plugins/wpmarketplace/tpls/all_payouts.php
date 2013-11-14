<?php
global $wpdb;
$sql = "select * from {$wpdb->prefix}mp_withdraws order by date desc";
$payouts = $wpdb->get_results($sql);
?>
<form action="" method="post">
    <table cellspacing="0" class="widefat fixed">
    <thead>
    <tr>
    <th><?php echo __("Userid","wpmarketplace");?></th>
    <th><?php echo __("Username","wpmarketplace");?></th>
    <th><?php echo __("Amount","wpmarketplace");?></th>
    <th><?php echo __("Status","wpmarketplace");?></th>
    <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($payouts as $payout){
      if($payout->status==0) $st="Pending"; else if ($payout->status==1)$st="Completed";   
      echo "<tr><td>{$payout->uid}</td><td>".__(get_userdata($payout->uid)->display_name,"wpmarketplace")."</td><td>{$payout->amount}</td><td>".__($st,"wpmarketplace")."</td><td><input type='checkbox' name='poutid[]' value='".$payout->id."'></td></tr>";   
    }
    ?>
    <tr><td>
    <select name="payout_status"><option value="-1">Payout Status:</option><option value="0">Pending</option><option value="1">Completed</option><option value="2">Cancel</option></select>
    <input type="submit" name="pschange" value="Apply" class="button">
    </td></tr>
    </tbody>
    </table>
</form>
