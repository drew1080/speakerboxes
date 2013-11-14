   
<div class="checkout_div wp-marketplace">
    <?php
    global $current_user, $sap;
    
    get_currentuserinfo();
    $cart_items = wpmp_get_cart_items();
    $currency_sign = get_option('_wpmp_curr_sign','$');
    if(count($cart_items)==0) echo __("No item in cart.","wpmarketplace")."<br/><a href='".$settings['continue_shopping_url']."'>".__("Continue shopping","wpmarketplace")."</a></div>";
    else{
    $settings = maybe_unserialize(get_option('_wpmp_settings'));
    include_once("checkout_method.php");            
    include_once("billing.php");
    //include_once("shipping_method.php");
    //include_once("payment_method.php");
    //include_once("order_review.php");
    ?>             
     <div id="wpmp-section-shipping">     
     <div class="step-title">
            <h2><?php echo __("Shipping Method","wpmarketplace");?></h2>
     </div>
      <div id="shipping">
      </div>
     </div>

     <div id="wpmp-section-payment">     
     <div class="step-title">
            <h2><?php echo __("Payment Method","wpmarketplace");?></h2>
     </div>
     <div id="payment">
     </div>
     </div>

     <div id="wpmp-section-review"> 
     <div class="step-title">
            <h2><?php echo __("Order Review","wpmarketplace");?></h2>
        </div>
    <div id="order-review">    
    </div>
    </div>
    <?php
    
    
    /*$data=wpmp_show_cart();        
        echo $data;
 */
?>
    
        
    </div>
    
    <br/>
 
<script type="text/javascript">
jQuery(function(){
jQuery('#shiptobilling-checkbox').click(function(){
    //alert(jQuery('#shiptobilling-checkbox').attr('checked'));
    if(jQuery('#shiptobilling-checkbox').attr('checked')=="checked"){
                jQuery('.col-1').slideUp();
    }
    else{

        jQuery('.col-1').slideDown();
    }
});
   
           
     jQuery('#registerform').validate({
        submitHandler: function(form) {   
           jQuery(form).ajaxSubmit({
               'url': '<?php echo home_url("/?checkout_register=register&wpmpnrd=1");?>',
               'beforeSubmit':function(){
                   //return false;
                   jQuery('#register_btn').attr('disabled','disabled').html('Please wait...');
               },
               'success':function(res){
                   
                    if(res.match(/success/)){
                       // reload after succuessfull registration
                       window.location.reload();
                    } else {                    
                       jQuery('#rmsg').html("<br/><div class='alert alert-danger'>"+res+"</div>");
                       jQuery('#register_btn').removeAttr('disabled').html('Continue');
                    }
                    return false;
               }
           });
        }
      //return false;
      });
      
       
           jQuery('#loginform').validate({
            submitHandler: function(form) {
                jQuery(form).ajaxSubmit({
                   
                   'url': '<?php the_permalink(); echo $sap; ?>checkout_login=login',
                   'beforeSubmit':function(){ 
                       jQuery('#loginbtn').attr('disabled','disabled').html('Please wait...');
                   },
                   'success':function(res){
                       
                       if(res.match(/success/)){
                           // reload after succuessfull login
                           window.location.reload();
                        }else if(res.match(/failed/)){
                            jQuery('#lmsg').html("<br/><div class='alert alert-danger'>Username or Password is not correct!</div>");
                            jQuery('#loginbtn').removeAttr('disabled').html('Login');
                        }
                   }
               });
            }
        });
               
      
      
      jQuery('#billing_form').validate({
            submitHandler: function(form) {
                jQuery(form).ajaxSubmit({
               'url': '<?php echo home_url("?checkout_billing=save");?>',
               'beforeSubmit':function(){
                   jQuery('#bloading_first').slideDown();
               },
               'success':function(res){
                   
                    if(res.match(/error/)){
                        alert(res);
                  
                    } else{
                        jQuery('#bloading_first').slideUp();
                        jQuery('#csb').slideUp();
                        jQuery('#shipping').html(res).slideDown();
                    }
               }
           });
      
            }
      });

      var skippayment = 0;
      jQuery('#shipping_form').live('submit',function(){
           jQuery(this).ajaxSubmit({
               'url': '<?php echo home_url("?checkout_shipping=save");?>',
               'beforeSubmit':function(){
                   jQuery('#shipping_w8').fadeIn();
               },
               'success':function(res){
                   
                    if(res.match(/error/)){
                        alert(res);
                   //jQuery('#loading_first').slideUp();
                    }else{                  //close the shipping div

                         //open the payment div
                        if(!res.match(/id="order_/)){
                            jQuery('#shipping_w8').fadeOut();
                            jQuery('#shipping').slideUp();
                            jQuery('#payment').html(res).slideDown();
                        }
                        else {
                            skippayment = 1;
                            jQuery('#order-review').html(res);
                            jQuery('#shipping_w8').fadeOut();
                            jQuery('#shipping').slideUp();
                            jQuery('#order-review').slideDown();
                        }
                    }
               }
           });
      return false;
      });
      
      jQuery('#payment_form').live("submit",function(){
           jQuery(this).ajaxSubmit({
               'url': '<?php echo home_url("?checkout_payment=save");?>',
               'beforeSubmit':function(){
                   jQuery('#payment_w8').fadeIn();
               },
               'success':function(res){
                   //var obj = jQuery.parseJSON(res); 
                   //alert(obj.success);
                    if(res.match(/error/)){
                        alert(res);
                   
                    }else{
                      jQuery('#payment_w8').fadeOut();
                      jQuery('#payment').slideUp();  
                      jQuery('#order-review').html(res).slideDown();  
                    }
               }
           });
      return false;
      });
      
      jQuery('#order_form').live("submit",function(){
           jQuery(this).ajaxSubmit({
               'url': '<?php echo home_url("?wpmpaction=placeorder");?>',
               'beforeSubmit':function(){
                   jQuery('#loading_first').slideDown();
                   jQuery('#order_btn').attr('disabled','disabled').html('Please wait...');
               },
               'success':function(res){
                 
                   jQuery('#wpmpplaceorder').html(res);
                   
               }
           });
      return false;
      });



jQuery('#billing_back').live("click",function(){ 
     jQuery('#csb').slideUp();
     jQuery('#csl').slideDown();     
});

jQuery('#shipping_back').live("click",function(){ 
     jQuery('#shipping').slideUp();
     jQuery('#csb').slideDown();     
});
jQuery('#pay_back').live("click",function(){ 
     jQuery('#payment').slideUp();
     jQuery('#shipping').slideDown();     
});
jQuery('#order_back').live("click",function(){ 
     jQuery('#csor').slideUp();
    if(skippayment==0)
     jQuery('#payment').slideDown();
    else
     jQuery('#shipping').slideDown();
});



});
</script>
 <?php } ?>