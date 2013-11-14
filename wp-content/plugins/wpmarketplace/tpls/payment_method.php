<?php
    global $payment_methods;
    //echo  PrepaidCredits::checkCredits();
?> 
<form action="" name="payment_form" id="payment_form" method="post"> 
<div class="step a-item" id="csp">
<?php echo __("Select Payment Method:","wpmarketplace"); ?><br/> 
<select name="payment_m" id="payment_method" >
  <?php
    
    $settings = maybe_unserialize(get_option('_wpmp_settings'));
    $payment_methods = apply_filters('payment_method', $payment_methods); 
    
     foreach($payment_methods as $payment_method){  
        if(class_exists($payment_method)){
            if($settings[$payment_method]['enabled']){

                echo '<option value="'.$payment_method.'">'.$payment_method.'</option>';

            }
        }
    }
    
  ?>
</select>
<input type="hidden" name="payment_method" id="payment__method">
 <br>
<br><button id="pay_back" class="button btn" type="button"><span><span><?php echo __("Back","wpmarketplace");?></span></span></button>
<button id="pay_btn" class="button btn btn-success" type="submit"><span><span><?php echo __("Continue","wpmarketplace");?></span></span></button> <div class="hide pull-right" id="payment_w8"><img src='<?php echo admin_url('/images/loading.gif'); ?>' /></div>
</div>
</form>
<script type="text/javascript">
window.onload=pay_method();
jQuery('#payment_method').change(function(){
    pay_method();
});

function pay_method(){
    jQuery('#payment__method').val(jQuery('#payment_method').val());
}
</script>