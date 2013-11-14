
    <h2><?php echo __("Comissions","wpmarketplace");?></h2>
   
      <form action="" method="post">
     <table width="30%" style="margin: 10px;">
     <tr><th align="left"><?php echo __("Role","wpmarketplace");?></th><th align="left"><?php echo __("comission (%)","wpmarketplace");?></th></tr>
     <tr><td><?php echo __("Guest (guest)","wpmarketplace");?> </td><td><input type="text" size="8" name="comission[guest]" value="<?php echo $comission['guest']; ?>"></td></tr>     
         <?php
    global $wp_roles;
    $roles = array_reverse($wp_roles->role_names);
    foreach( $roles as $role => $name ) { 
    if(  isset($currentAccess) ) $sel = (in_array($role,$currentAccess))?'checked':'';
    ?>
    <tr><td><?php echo $name; ?> (<?php echo $role; ?>) </td><td><input type="text" size="8" name="comission[<?php echo $role; ?>]" value="<?php echo $comission[$role]; ?>"></td></tr>     
    
    <?php } ?>  
    <tr><td colspan="2"><input type="submit" class="button button-primary" value="Submit" name="csub"></td></tr>   
    </table>
    </form>
    
  <h2><?php echo __("Payout Duration","wpmarketplace");?></h2>
    
    <form action="" method="post">
<?php echo __("Duration of payout to mature :","wpmarketplace");?> <input type="text" name="payout_duration" value="<?php echo $payout_duration;?>" >  <?php echo __("Days","wpmarketplace");?>

<input type="submit" class="button button-primary" name="psub" value="Submit">
</form>
    