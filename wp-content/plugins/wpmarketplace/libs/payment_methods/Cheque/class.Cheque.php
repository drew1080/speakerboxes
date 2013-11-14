<?php
if(!class_exists('Cheque')){
class Cheque extends CommonVers{
    var $TestMode;
    
    var $GatewayUrl = "https://www.Cheque.com/cgi-bin";
    var $GatewayUrl_TestMode = "https://www.sandbox.Cheque.com/cgi-bin";
    var $Message;
    
    
    function Cheque($TestMode = 0){
        $this->TestMode = $TestMode;                
        if($TestMode==1)
        $this->GatewayUrl = $this->GatewayUrl_TestMode;
        ///$this->LoadSettings();
    }
    
    
    function ConfigOptions(){    
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        $this->Enabled= isset($settings['Cheque']['enabled']) ? $settings['Cheque']['enabled']: "";
        $this->Message = isset($settings['Cheque']['Message']) ? $settings['Cheque']['Message'] : "";
        
        
        if(isset($settings['Cheque_mode']) && $settings['Cheque_mode']=='sandbox')            
        $this->GatewayUrl = $this->GatewayUrl_TestMode;
        
        if($this->Enabled)$enabled='checked="ehecked"';
        else $enabled = "";
        
        $data='<table>
<tr><td>'.__("Enable/Disable:","wpmarketplace").'</td><td><input type="checkbox" value="1" '.$enabled.' name="_wpmp_settings[Cheque][enabled]" style=""> '.__("Enable Cheque","wpmarketplace").'</td></tr>
<tr><td>'.__("Message:","wpmarketplace").'</td><td><textarea name="_wpmp_settings[Cheque][Message]">'.$this->Message.'</textarea></td></tr>
</table>';
        return $data;
    }
    
    function ShowPaymentForm($AutoSubmit = 0){
        
        if($AutoSubmit==1) $hide = "display:none;'";
        $Cheque = plugins_url().'/wpdm-premium-packages/images/Cheque.png';
        $Form = " 
                    <div class='alert alert-success' style='margin-top:10px'> <b>".__("Order Completed.","wpmarketplace")."</b> <br> ".__("Please wait while redirected.","wpmarketplace")."</div>
         
        
        ";
        
        if($AutoSubmit==1)
        $Form .= "<script language='javascript'>location.href='".home_url()."/members/orders/?id=".$_SESSION['orderid']."'</script>";
        
        return $Form;
        
        
    }
    
    
    function VerifyPayment() {

          // parse the Cheque URL
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

          // open the connection to Cheque
          $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30); 
          if(!$fp) {
              
             // could not open the connection.  If loggin is on, the error message
             // will be in the log.
             $this->last_error = "fsockopen error no. $errnum: $errstr";
             $this->log_ipn_results(false);       
             return false;
             
          } else { 
     
             // Post the data back to Cheque
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