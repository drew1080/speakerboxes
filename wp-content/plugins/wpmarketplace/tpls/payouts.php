<style>
.wrap{
    margin: 0px;
    
}
#wpcontent{
margin-left: 146px;
} 
#icon-options-general{
    margin-left: 15px;
}
</style>
<?php
    if(isset($_POST['psub']))
        update_option("wpmp_payout_duration",$_POST['payout_duration']);
        
    if(isset($_POST['csub']))
        update_option("wpmp_user_comission",$_POST['comission']);
        
    if(isset($_POST['pschange'])){
        global $wpdb;
        //echo $_POST['payout_status'];
        if($_POST['payout_status']!="-1" && $_POST['payout_status']!="2"){
            if($_POST['poutid']){
                foreach($_POST['poutid'] as $payout_id){
                    $wpdb->update( 
                        "{$wpdb->prefix}mp_withdraws", 
                        array( 
                            'status' => $_POST['payout_status']    
                               
                        ), 
                        array( 'ID' => $payout_id ), 
                        array( 
                            '%d',    // value1
                            
                        ), 
                        array( '%d' ) 
                    );
                }
            }
        }
        if($_POST['payout_status']=="2"){
           if($_POST['poutid']){
                foreach($_POST['poutid'] as $payout_id){
                    $wpdb->query("delete from {$wpdb->prefix}mp_withdraws where id={$payout_id}"); 
                        
                }
            } 
        }
    }
        
        
    $payout_duration=get_option("wpmp_payout_duration");
    $comission=get_option("wpmp_user_comission");
?>
<div class="wrap">
<header>
  <div class="icon32" id="icon-options-general"><br></div><h2><?php echo __("Payouts","wpmarketplace");?> <img style="display: none;" id="wdms_loading" src="images/loading.gif" /></h2>
</header>

<nav>
                <ul>
                    <li class="selected"><a href="#tab1"><?php echo __("All Payouts","wpmarketplace");?></a></li>
                    <li><a href="#tab2"><?php echo __("Dues","wpmarketplace");?></a></li>
                    <li><a href="#tab3"><?php echo __("Payout Settings","wpmarketplace");?></a></li>
                    
                    
                </ul>
</nav>
                <section class="tab" id="tab1">
                <?php
                    include_once("all_payouts.php");
                ?>
                </section>
                <section class="tab" id="tab2">
                <?php
                    include_once("payout_dues.php");
                ?>
                </section>
                <section class="tab" id="tab3">
                <?php
                    include_once("payout_settings.php");
                ?>
                </section>
               
                
   </div>         
