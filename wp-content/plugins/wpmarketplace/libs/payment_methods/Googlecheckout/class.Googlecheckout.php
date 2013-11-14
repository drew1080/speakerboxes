<?php
if(!class_exists('Googlecheckout')){
class Googlecheckout extends CommonVers{
    var $TestMode;
    
    var $GatewayUrl = "https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/";
    var $GatewayUrl_TestMode = "https://sandbox.google.com/checkout/api/checkout/v2/checkoutForm/Merchant/";
    var $Business;
    var $ReturnUrl;
    var $NotifyUrl;
    var $CancelUrl;    
    var $Custom;
    var $Enabled;
    var $Currency;
    var $Ship_method;
    var $Ship_amount;
    var $Ship_currency;
    
    function Googlecheckout($TestMode = 0){
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        //print_r($settings);
        $this->TestMode = $TestMode;                
        if($TestMode==1)
        $this->GatewayUrl = $this->GatewayUrl_TestMode;
        
        
        $this->Enabled= isset($settings['Googlecheckout']['enabled']) ? $settings['Googlecheckout']['enabled']: "";
       
        $this->Business =  $settings['Googlecheckout']['Googlecheckout_merchant'];
        $this->TestMode =  $settings['Googlecheckout']['Googlecheckout_mode'];
        //$this->Currency =  $settings['Googlecheckout']['currency'];
        $this->Currency =  $currency_sign = get_option('_wpmp_curr_name','USD');
        
        if($settings['Googlecheckout']['Googlecheckout_mode']=='sandbox')            
            $this->GatewayUrl = $this->GatewayUrl_TestMode.$this->Business;
        else
            $this->GatewayUrl = $this->GatewayUrl.$this->Business;
        
    }
    
    
    function ConfigOptions(){    
        
        
        
        if($this->Enabled)$enabled='checked="checked"';
        else $enabled = "";
        
        $data='<table>
<tr><td>'.__("Enable/Disable:","wpmarketplace").'</td><td><input type="checkbox" value="1" '.$enabled.' name="_wpmp_settings[Googlecheckout][enabled]" style=""> '.__("Enable Googlecheckout","wpmarketplace").'</td></tr>
<tr><td>'.__("Googlecheckout Mode:","wpmarketplace").'</td><td><select id="Googlecheckout_mode" name="_wpmp_settings[Googlecheckout][Googlecheckout_mode]"><option value="live">Live</option><option value="sandbox" >SandBox</option></select></td></tr>
<tr><td>'.__("Googlecheckout Merchant ID:","wpmarketplace").'</td><td><input type="text" name="_wpmp_settings[Googlecheckout][Googlecheckout_merchant]" value="'.$this->Business.'" /></td></tr>

</table>
<script>
select_my_list("Googlecheckout_mode","'.$this->TestMode.'");
</script>
';
        return $data;
    }
    
    function ShowPaymentForm($AutoSubmit = 0){
        
        if($AutoSubmit==1) $hide = "display:none;'";
        $Googlecheckout = plugins_url().'/wpdm-premium-packages/images/Googlecheckout.png';
        $Form = " 
                    <form method='post' name='_wpdm_bnf_{$this->InvoiceNo}' id='_wpdm_bnf_{$this->InvoiceNo}' action='{$this->GatewayUrl}'>

                    <input type='hidden' name='item_name_1' value='{$this->OrderTitle}'/>
                      <input type='hidden' name='item_description_1' value='{$this->InvoiceNo}'/>
                      <input type='hidden' name='item_price_1' value='{$this->Amount}'/>
                      <input type='hidden' name='item_currency_1' value='{$this->Currency}'/>
                      <input type='hidden' name='item_quantity_1' value='1'/>
                      
                      
                      
                      

                    <!-- No tax code -->

                    <!--<input type='hidden' name='ship_method_name_1' value='{$this->Ship_method}'/>
                      <input type='hidden' name='ship_method_price_1' value='{$this->Ship_amount}'/>
                      <input type='hidden' name='ship_method_currency_1' value='USD'/>
                      <input type='hidden' name='ship_method_world_1' value=''/>
                      <input type='hidden'
                        name='checkout-flow-support.merchant-checkout-flow-support.shipping-methods.flat-rate-shipping-1.shipping-restrictions.excluded-areas.postal-area-1.country-code'
                        value='US'/>-->
                      

                    <input type='hidden' name='_charset_' />

                      <!-- Button code -->
                      <input type='image'
                        name='Google Checkout'
                        alt='Fast checkout through Google'
                        src='http://sandbox.google.com/checkout/buttons/checkout.gif?

                    merchant_id='{$this->Business}'&w=180&h=46&style=white&

                    variant=text&loc=en_US'
                        height='46'
                        width='180' />
                    </form>
         
        
        ";
        
        if($AutoSubmit==1)
        $Form .= "<center><b>".__("Processing....","wpmarketplace")."</b></center><script language=javascript>setTimeout('document._wpdm_bnf_{$this->InvoiceNo}.submit()',2000);</script>";
        
        return $Form;
        
        
    }
    
    
    function VerifyPayment() {

          // parse the Googlecheckout URL
          $url_parsed=parse_url($this->GatewayUrl);        

          // generate the post string from the _POST vars aswell as load the
          // _POST vars into an arry so we can play with them from the calling
          // script.
          //print_r($_POST);
          
          $this->InvoiceNo = $_POST['invoice'];
          
          $post_string = '';    
          foreach ($_POST as $field=>$value) { 
             $this->ipn_data["$field"] = $value;
             $post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
          }
          $post_string.="cmd=_notify-validate"; // append ipn command

          // open the connection to Googlecheckout
          $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30); 
          if(!$fp) {
              
             // could not open the connection.  If loggin is on, the error message
             // will be in the log.
             $this->last_error = "fsockopen error no. $errnum: $errstr";
             $this->log_ipn_results(false);       
             return false;
             
          } else { 
     
             // Post the data back to Googlecheckout
             fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
             fputs($fp, "Host: $url_parsed[host]\r\n"); 
             fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
             fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
             fputs($fp, "Connection: close\r\n\r\n"); 
             fputs($fp, $post_string . "\r\n\r\n"); 

             // loop through the response from the server and append to variable
             while(!feof($fp)) { 
                $this->ipn_response .= fgets($fp, 1024); 
             } 

             fclose($fp); // close connection

          }
                              
          if (eregi("VERIFIED",$this->ipn_response)) {
      
             // Valid IPN transaction.             
             return true;       
             
          } else {
      
             // Invalid IPN transaction.  Check the log for details.
             $this->VerificationError = 'IPN Validation Failed.';             
             return false;
         
      }
      
   }
    
    
}
}
?>
