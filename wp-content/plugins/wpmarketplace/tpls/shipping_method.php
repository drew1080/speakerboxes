
        <form action="" name="shipping_form" id="shipping_form" method="post"> 
        <div class="step a-item" id="cssh">
        
        <div>
           <?php echo __("Select Shipping","wpmarketplace");?>   <br>
           <select id="shipping_method" name="shipping_method">
            
           <?php
           $ship_methods="";
           $settings = maybe_unserialize(get_option('_wpmp_settings'));
           $currency_sign = get_option('_wpmp_curr_sign','$');
           if($settings['calc_shipping']==1){
              if($settings['flat_rate_enabled']==1){
                  $flat_rate=$settings['flat_rate_cost']+$settings['flat_rate_fee'];
                  if($settings['flat_rate_tax_status']=="taxable"){
                      $flat_rate=$flat_rate;//calculate flat rate tax
                  }
                $ship_methods.= '<option rel="'.$flat_rate.'" value="'.$settings['flat_rate_title'].'">'.$settings['flat_rate_title']." {$currency_sign}".$flat_rate.'</option>';
              }
              if($settings['free_shipping_enabled']==1){
                  if(wpmp_get_cart_subtotal() >= $settings['free_shipping_min_amount']){
                      $ship_methods.= '<option rel="0" value="'.$settings['free_shipping_title'].'">'.$settings['free_shipping_title'].'</option>';
                  }
              }
              if($settings['local-delivery_enabled']==1){
                  if($settings['local-delivery_type']!="free"){
                      if($settings['local-delivery_type']=="fixed")
                        $delivery_fee=$settings['local-delivery_fee'];
                      else  
                        $delivery_fee=(wpmp_get_cart_subtotal()*($settings['local-delivery_fee']/100));
                      
                  }else $delivery_fee=0;
                 $ship_methods.= '<option rel="'.$delivery_fee.'" value="'.$settings['local-delivery_title'].'">'.$settings['local-delivery_title']." {$currency_sign}".$delivery_fee.'</option>'; 
              }
              
           }else
           $ship_methods.= '<option value="0">No shipping</option>';
           $ship_methods=apply_filters("wpmp_apply_shipping_method",$ship_methods);
           echo $ship_methods;
?>           </select>   <br>
<input type="hidden" name="shipping_rate" value="0" id="shipping_rate" />
        </div>
        
<br>
<button id="shipping_back" class="button btn" type="button"><span><span><?php echo __("Back","wpmarketplace");?></span></span></button>
<button id="shipping_btn" class="button btn btn-success" type="submit"><span><span><?php echo __("Continue","wpmarketplace");?></span></span></button> <div class="hide pull-right" id="shipping_w8"><img src="<?php echo admin_url('/images/loading.gif'); ?>" /></div>
        </div>
        </form>
        <script type="text/javascript">
        window.onload=shipping();
        jQuery('#shipping_method').change(function(){
            shipping();
        });
        
        function shipping(){
            jQuery('#shipping_rate').val(jQuery('#shipping_method option:selected').attr("rel"));
        }
        </script>