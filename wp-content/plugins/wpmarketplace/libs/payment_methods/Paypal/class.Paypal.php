<?php
if(!class_exists('Paypal')){
class Paypal extends CommonVers{
    var $TestMode;
    
    var $GatewayUrl = "https://www.Paypal.com/cgi-bin/webscr";
    var $GatewayUrl_TestMode = "https://www.sandbox.Paypal.com/cgi-bin/webscr";
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
    var $order_id;
    
    
    function Paypal($TestMode = 0){
        $this->TestMode = $TestMode;                
        if($TestMode==1)
        $this->GatewayUrl = $this->GatewayUrl_TestMode;
        
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        $this->Enabled= isset($settings['Paypal']['enabled'])?$settings['Paypal']['enabled']:"";
        $this->ReturnUrl = $settings['Paypal']['return_url'];
        //$this->NotifyUrl = home_url('/Paypal/notify/');
        $this->NotifyUrl = home_url('?action=wpmp-payment-notification&class=Paypal');
        $this->CancelUrl = $settings['Paypal']['cancel_url'];
        $this->Business =  $settings['Paypal']['Paypal_email'];
        $this->TestMode =  $settings['Paypal']['Paypal_mode'];
        //$this->Currency =  $settings['Paypal']['currency'];
        $this->Currency =  get_option('_wpmp_curr_name','USD');
        
        if($settings['Paypal']['Paypal_mode']=='sandbox')            
        $this->GatewayUrl = $this->GatewayUrl_TestMode;
    }
    
    
    function ConfigOptions(){    
        
        
        
        if($this->Enabled)$enabled='checked="checked"';
        else $enabled = "";
        
        $data='<table>
<tr><td>'.__("Enable/Disable:","wpmarketplace").'</td><td><input type="checkbox" value="1" '.$enabled.' name="_wpmp_settings[Paypal][enabled]" style=""> '.__("Enable Paypal","wpmarketplace").'</td></tr>
<tr><td>'.__("Paypal Mode:","wpmarketplace").'</td><td><select id="Paypal_mode" name="_wpmp_settings[Paypal][Paypal_mode]"><option value="live">Live</option><option value="sandbox" >SandBox</option></select></td></tr>
<tr><td>'.__("Paypal Email:","wpmarketplace").'</td><td><input type="text" name="_wpmp_settings[Paypal][Paypal_email]" value="'.$this->Business.'" /></td></tr>
<tr><td>'.__("Cancel Url:","wpmarketplace").'</td><td><input type="text" name="_wpmp_settings[Paypal][cancel_url]" value="'.$this->CancelUrl.'" /></td></tr>
<tr><td>'.__("Return Url:","wpmarketplace").'</td><td><input type="text" name="_wpmp_settings[Paypal][return_url]" value="'.$this->ReturnUrl.'" /></td></tr>

</table>
<script>
select_my_list("Paypal_mode","'.$this->TestMode.'");
</script>
';
        return $data;
    }
    
    function ShowPaymentForm($AutoSubmit = 0){
        
        if($AutoSubmit==1) $hide = "display:none;'";
        $Paypal = plugins_url().'/wpdm-premium-packages/images/Paypal.png';
        $Form = " 
                    <form method='post' style='margin:0px;' name='_wpdm_bnf_{$this->InvoiceNo}' id='_wpdm_bnf_{$this->InvoiceNo}' action='{$this->GatewayUrl}'>

                    <input type='hidden' name='business' value='{$this->Business}' />

                    <input type='hidden' name='cmd' value='_xclick' />
                    <!-- the next three need to be created -->
                    <input type='hidden' name='return' value='{$this->ReturnUrl}' />
                    <input type='hidden' name='cancel_return' value='{$this->CancelUrl}' />
                    <input type='hidden' name='notify_url' value='{$this->NotifyUrl}' />
                    <input type='hidden' name='rm' value='2' />
                    <input type='hidden' name='currency_code' value='{$this->Currency}' />
                    <input type='hidden' name='lc' value='US' />
                    <input type='hidden' name='bn' value='toolkit-php' />

                    <input type='hidden' name='cbt' value='Continue' />
                    
                    <!-- Payment Page Information -->
                    <input type='hidden' name='no_shipping' value='' />
                    <input type='hidden' name='no_note' value='1' />
                    <input type='hidden' name='cn' value='Comments' />
                    <input type='hidden' name='cs' value='' />
                    
                    <!-- Product Information -->
                    <input type='hidden' name='item_name' value='{$this->OrderTitle}' />
                    <input type='hidden' name='amount' value='{$this->Amount}' />

                    <input type='hidden' name='quantity' value='1' />
                    <input type='hidden' name='item_number' value='{$this->InvoiceNo}' />
                    <input type='hidden' name='email' value='{$this->ClientEmail}' />
                    <input type='hidden' name='custom' value='{$this->Custom}' />
                    
                    <!-- Shipping and Misc Information -->
                     
                    <input type='hidden' name='invoice' value='{$this->InvoiceNo}' />

                    <noscript><p>Your browser doesn't support Javscript, click the button below to process the transaction.</p>
                    <a style=\"{$hide}\" href=\"#\" onclick=\"jQuery('#_wpdm_bnf').submit();return false;\">Buy Now&nbsp;<img align=right alt=\"Paypal\" src=\"$Paypal\" /></a>                    </noscript>
                    </form>
         
        
        ";
        
        if($AutoSubmit==1)
        $Form .= "<center>Proceeding to Paypal....</center><script language=javascript>setTimeout('document._wpdm_bnf_{$this->InvoiceNo}.submit()',2000);</script>";
        
        return $Form;
        
        
    }
    
    
    function VerifyPayment() {

          // parse the Paypal URL
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


         if(function_exists('curl_init')){
             $ch = curl_init($this->GatewayUrl);
             curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
             curl_setopt($ch, CURLOPT_POST, 1);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
             curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
             curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
             $this->ipn_response = curl_exec($ch);
             curl_close($ch);

           } else {

          // open the connection to Paypal
          $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30); 
          if(!$fp) {
              
             // could not open the connection.  If loggin is on, the error message
             // will be in the log.
             $this->last_error = "fsockopen error no. $errnum: $errstr";
             $this->log_ipn_results(false);       
             return false;
             
          } else { 
     
             // Post the data back to Paypal
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

          }}

          if (strpos($this->ipn_response, "ERIFIED")) {
      
             // Valid IPN transaction.             
             return true;       
             
          } else {
      
             // Invalid IPN transaction.  Check the log for details.
             $this->VerificationError = 'IPN Validation Failed.';             
             return false;
         
      }
      
   }
   
   function VerifyNotification(){
       
       if($_POST){
           $this->order_id=$_POST['invoice'];
           return $this->VerifyPayment();
       }
       else die("Problem occured in payment.");
   }
    
    
}
}
?>