<?php
function wpmp_show_cart(){
    global $wpdb;
    $cart_data = wpmp_get_cart_data();
    //echo "<pre> Cart data "; print_r($cart_data); echo "</pre>";
    //echo "<pre> Cart items "; print_r($cart_items); echo "</pre>";
    
    foreach($cart_data as $pid=>$cdt){
        //echo "<pre> hello "; print_r($cdt); echo "</pre>";
        extract($cdt);
        if($pid){
            $cart_items[$pid] = get_post($pid,ARRAY_A);
            $cart_items[$pid]['quantity'] =  $quantity;
            $cart_items[$pid]['discount'] =  $discount;
            $cart_items[$pid]['variation'] =  $variation;
            $cart_items[$pid]['price'] = (double)$price;
            
            if($cdt['coupon']){
                $valid_coupon=check_coupon($pid,$coupon);
                //echo "Valid coupon $coupon : " . $cdt['coupon'] . " $valid_coupon <br>";
                if($valid_coupon!=0){
                    $cart_items[$pid]['coupon'] =  $coupon;
                    $cart_items[$pid]['coupon_discount'] =  $valid_coupon;
                    //$cart_items[$pid]['coupon_amount'] =  $valid_coupon;
                }
                else
                    $cart_items[$pid]['error'] =  "Coupon does not exist";
            }
        }
    }
   
    include(WP_PLUGIN_DIR."/wpmarketplace/tpls/cart.php");
    return $cart;
}



//checking product coupon whether valid or not
function check_coupon($pid,$coupon){
    @extract(get_post_meta($pid,"wpmp_list_opts",true));
    
    if(is_array($coupon_code)){
        foreach($coupon_code as $key=> $val){
            if($val==$coupon)
                return $coupon_discount[$key];
        }
    }
    return 0;
}

function wpmp_add_to_cart(){ 
    if(isset($_POST['add_to_cart']) && $_POST['add_to_cart']=="add"){
        global $wpdb, $post, $wp_query, $current_user;    
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        $pid= $_POST['pid'];
        $sales_price=0;
        @extract(get_post_meta($pid,"wpmp_list_opts",true));
         
        $pid = $_REQUEST['wpmp_add_to_cart']?$_REQUEST['wpmp_add_to_cart']:$pid;
        if($pid<=0) return;
        $cart_data = wpmp_get_cart_data();
        $q = $_REQUEST['quantity']?$_REQUEST['quantity']:1;
        $q += $cart_data[$pid]['quantity'];
        $price = $price[$license];        
        if($sales_price)$base_price=$sales_price;       
        $cart_data[$pid] = array('quantity'=>$q,'variation'=>$_POST['variation'],'price'=>$base_price,'discount'=>$_POST['discount']);       
        wpmp_update_cart_data($cart_data);
        $settings = get_option('_wpmp_settings');
        if($settings['wpmp_after_addtocart_redirect']==1){
            echo "<script> location.href='".get_permalink($settings['page_id'])."'; </script>";
        }
        else echo "<script> location.href='".$_SERVER['HTTP_REFERER']."'; </script>";
    }
    
}

function wpmp_remove_cart_item(){
    if(!isset($_REQUEST['wpmp_remove_cart_item']) || $_REQUEST['wpmp_remove_cart_item']<=0) return;    
    $cart_data = wpmp_get_cart_data();
    unset($cart_data[$_REQUEST['wpmp_remove_cart_item']]);    
    wpmp_update_cart_data($cart_data);
    $ret['cart_subtotal'] = wpmp_get_cart_subtotal();
    $ret['cart_discount'] = wpmp_get_cart_discount();
    $ret['cart_total'] = wpmp_get_cart_total();
    die(json_encode($ret));
}

function wpmp_update_cart(){
    if(!isset($_REQUEST['wpmp_update_cart']) || (isset($_REQUEST['wpmp_update_cart']) && $_REQUEST['wpmp_update_cart']<=0)) return;
    wpmp_update_cart_data($_POST['cart_items']);
    $ret['cart_subtotal'] = wpmp_get_cart_subtotal();
    $ret['cart_discount'] = wpmp_get_cart_discount();
    $ret['cart_total'] = wpmp_get_cart_total();
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    die(json_encode($ret));
    }
    wpmp_show_cart();
}

function wpmp_get_cart_data(){
    global $current_user;
    if(is_user_logged_in()){    
        get_currentuserinfo();
        $cart_id = $current_user->ID."_cart";                
    } else {
    $cart_id = md5($_SERVER['REMOTE_ADDR'])."_cart";
    }
    $cart_data = maybe_unserialize(get_option($cart_id));
    
    //adjust cart id after user log in
    if(is_user_logged_in()&&!$cart_data){
        $cart_id = md5($_SERVER['REMOTE_ADDR'])."_cart";    
        $cart_data = maybe_unserialize(get_option($cart_id));
        delete_option($cart_id);
        $cart_id = $current_user->ID."_cart";                
        update_option($cart_id, $cart_data);
    }
    
    return $cart_data?$cart_data:array();
}

function wpmp_update_cart_data($cart_data){
    global $current_user;
    if(is_user_logged_in()){    
    get_currentuserinfo();
    $cart_id = $current_user->ID."_cart";       
    } else {
    $cart_id = md5($_SERVER['REMOTE_ADDR'])."_cart";
    }
   //check enable stock or not
   $settings = maybe_unserialize(get_option('_wpmp_settings')); 
   //check if quantity of product is more than stock
   if($settings['stock']['enable']==1){ 
        foreach($cart_data as $pid=>$cartitem){
            $post_meta=array();
            $post_meta=get_post_meta($pid,"wpmp_list_opts",true);
            if($post_meta['manage_stock']==1){
                if($cartitem['quantity']>$post_meta['stock_qty']){
                    $cart_data[$pid]['quantity']= $post_meta['stock_qty'];
                }
            }
        }
   }

    $cart_data = update_option($cart_id, $cart_data);
    return $cart_data;
}

function wpmp_get_cart_items(){
    global $current_user, $wpdb;    
    $cart_data = wpmp_get_cart_data();    
    return ($cart_data);
}

function wpmp_get_cart_subtotal(){
    $cart_items = wpmp_get_cart_items();
 
    $total = 0;
    if(is_array($cart_items)){
    
    foreach($cart_items as $pid=>$item)    {
        $prices=0;
        @extract(get_post_meta($pid,"wpmp_list_opts",true));
        if($item['variation']){
            foreach($variation as $key=>$value){
                foreach($value as $optionkey=>$optionvalue){
                  if($optionkey!="vname"){
                        foreach($item['variation'] as $var){                   
                            if($var==$optionkey){
                                $prices+=$optionvalue['option_price'];
                                
                            }
                        }    
                  }
                }
            }     
        }
        if(trim($item['coupon'])!='') $valid_coupon=check_coupon($pid,$item['coupon']);
        else $valid_coupon = false;
        if($valid_coupon!=0){   
             
            $total +=  (($item['price']+$prices)*$item['quantity'])-(($item['price']+$prices)*$item['quantity']*($valid_coupon/100));  
        }else { 
            $total +=  (($item['price']+$prices)*$item['quantity']);
        }
    }}
    
    $total = apply_filters('wpmp_cart_subtotal',$total);
    return number_format($total,2);
}


//calculating discount
function wpmp_get_cart_discount(){
    global $current_user;
    get_currentuserinfo();
    $role = $current_user->roles[0];
    $role = $role?$role:'guest';
    $subtotal = wpmp_get_cart_subtotal();
    $cart_items = wpmp_get_cart_items();
    $discount=0;
    foreach($cart_items as $pid=>$cin){
       $opt = get_post_meta($pid,'wpmp_list_opts',true); 
       $discount += (($cin['price']*$cin['quantity']*$opt['discount'][$role])/100);
    }      
    return number_format($discount,2);
}
//calculating subtotal by subtracting discount
function wpmp_get_cart_total(){   
    return number_format((wpmp_get_cart_subtotal()-wpmp_get_cart_discount()),2);
}

function wpmp_grand_total(){
    $tax=wpmp_calculate_tax();
    return number_format((wpmp_get_cart_subtotal()+$tax['rate']-wpmp_get_cart_discount()),2);
}
//shipping calculation
function wpmp_calculate_shipping(){
    $ship=array();
    $order = new Order();
    $order_info=$order->GetOrder($_SESSION['orderid']);
    $ship['method']=$order_info->shipping_method;
    $ship['cost']=$order_info->shipping_cost;
    return $ship;
}
//tax calculation
function wpmp_calculate_tax(){
    $cartsubtotal=wpmp_get_cart_subtotal();
    $taxr=array();
    $order = new Order();
    $order_info=$order->GetOrder($_SESSION['orderid']);
    $bdata=unserialize($order_info->billing_shipping_data);
    $settings = maybe_unserialize(get_option('_wpmp_settings'));
    if($settings['tax']['enable']==1){
        if($settings['tax']['tax_rate']){
            foreach($settings['tax']['tax_rate'] as $key=> $rate){
                if($rate['country']){
                    foreach($rate['country'] as $r_country){
                        if($r_country==$bdata['shippingin']['country']){
                            $taxr['label']= $rate['label'];
                            $taxr['rate']= (($cartsubtotal*$rate['rate'])/100);
                            break;
                        }
                    } 
                }
            }
        }
    }
   
    return $taxr;
}

function wpmp_empty_cart(){
    global $current_user;
    if(is_user_logged_in()){    
    get_currentuserinfo();
    $cart_id = $current_user->ID."_cart";       
    } else {
    $cart_id = md5($_SERVER['REMOTE_ADDR'])."_cart";
    }
    delete_option($cart_id);
    if($_SESSION['orderid']){
        $_SESSION['orderid'] = '';
        unset($_SESSION['orderid']);
    }
}

function wpmp_checkout(){
        wp_enqueue_script('jquery');
        $settings = get_option('_wpmp_settings'); 
        include(WP_PLUGIN_DIR."/wpmarketplace/tpls/checkout.php");
}

function wpmp_addtocart_js(){
    if(get_option('wpmp_ajaxed_addtocart',0)==0) return;
?>
<script language="JavaScript">
<!--
  jQuery(function(){
       jQuery('.wpdm-pp-add-to-cart-link').click(function(){
            if(this.href!=''){
                var lbl;
                var obj = jQuery(this);
                lbl = jQuery(this).html();
                jQuery(this).html('<img src="<?php echo plugins_url();?>/wpdm-premium-packages/images/wait.gif"/> adding...');
                jQuery.post(this.href,function(){
                   obj.html('added').unbind('click').click(function(){ return false; });
                })
            
            }
       return false;     
       });
       
       jQuery('.wpdm-pp-add-to-cart-form').submit(function(){
           
           var form = jQuery(this);
           var fid = this.id;
           form.ajaxSubmit({
               'beforeSubmit':function(){                   
                  jQuery('#submit_'+fid).val('adding...').attr('disabled','disabled');
               },
               'success':function(res){
                   jQuery('#submit_'+fid).val('added').attr('disabled','disabled');
               }
           });
            
       return false;     
       });
  });
//-->
</script>
<?php    
}


function wpmp_buynow($content){    
    global $wpdb, $post, $wp_query, $current_user;    
    $settings = maybe_unserialize(get_option('_wpmp_settings'));
    if(!isset($wp_query->query_vars['wpmarketplace'])||$wp_query->query_vars['wpmarketplace']==''||!isset($_REQUEST['buy'])||$_REQUEST['buy']=='')
    return $content;    
    @extract(get_post_meta($post->ID,"wpmp_list_opts",true));
    wpmp_add_to_cart($post->ID, $_REQUEST['buy']);    
    return '';
}

function update_os(){
    global $wpdb;
    $wpdb->update("{$wpdb->prefix}mp_orders",array('order_status'=>$_POST['status']),array('order_id'=>$_POST['order_id']));
    
    $settings = maybe_unserialize(get_option('_wpmp_settings'));
    //reduce stock 
    if($settings['stock']['enable']==1){  
        if($_POST['status']=="Completed"){
            if($settings['stock']['reduce_auto']==1)
                wpmp_reduce_stock($_POST['order_id']);
        }
    } 
    
    $siteurl=home_url("/");
    //email to customer of that order
    $userid=$wpdb->get_var("select uid from {$wpdb->prefix}mp_orders where order_id='".$_POST['order_id']."'");
    $user_info = get_userdata($userid);
    $admin_email=get_bloginfo("admin_email");
    //$from=home_url("/");
    $email = array();
    $subject="Order Status Changed";
    $message="The order {$_POST['order_id']} is changed to {$_POST['status']}"."\n Customer Name is ".$user_info->user_firstname." ".$user_info->lastname."\n Email is ".$user_info->user_email;
    $email['subject']=$subject;
    $email['body']=$message;
    $email['headers'] = 'From:  <'.$admin_email.'>' . "\r\n";
    $email = apply_filters("order_status_change_email", $email);    
    wp_mail($user_info->user_email,$email['subject'],$email['body'],$email['headers']);        
    //wp_mail($admin_email,$email['subject'],$email['body'],$email['headers']);
    //print_r($email);   
    die(__('Order status updated',"wpmarketplace"));
}

function update_ps(){
    global $wpdb;
    $wpdb->update("{$wpdb->prefix}mp_orders",array('payment_status'=>$_POST['status']),array('order_id'=>$_POST['order_id']));
    die(__('Payment status updated',"wpmarketplace"));
}

function ajaxinit(){
if(isset($_POST['action']) && $_POST['action']=='wpmp_pp_ajax_call'){    
    if(function_exists($_POST['execute']))
        call_user_func($_POST['execute'],$_POST);
        else
        echo __("function not defined!","wpmarketplace");
        
    die();
}
}
  
function PayNow($post_data){    
    global $wpdb,$current_user;
    get_currentuserinfo();
    $order = new Order();
    $corder = $order->GetOrder($post_data['order_id']);    
    $payment = new Payment();
    if($post_data['payment_method']=='')  $post_data['payment_method'] = $corder->payment_method;
    $payment->InitiateProcessor($post_data['payment_method']);
    $payment->Processor->OrderTitle = 'WPMP Order# '.$corder->order_id;
    $payment->Processor->InvoiceNo = $corder->order_id;
    $payment->Processor->Custom = $corder->order_id;
    $payment->Processor->Amount = number_format($corder->total,2);
    echo $payment->Processor->ShowPaymentForm(1);      
} 
function ProcessOrder(){                                                                       
    global $current_user;
    get_currentuserinfo();
    $order = new Order();    
    if(preg_match("@\/payment\/([^\/]+)\/([^\/]+)@is",$_SERVER['REQUEST_URI'],$process)){
        $gateway = $process[1];
        $page = $process[2];        
        $_POST['invoice'] = array_shift(explode("_",$_POST['invoice']));
        $odata = $order->GetOrder($_POST['invoice']);        
        $current_user = get_userdata($odata->uid);
        $uname = $current_user->display_name;
        $uid = $current_user->ID;
        $email = $current_user->user_email;
                
        $myorders = get_option('_wpmp_users_orders',true);
        if($page=='notify'){
        if(!$uid) {
        $uname = str_replace(array("@",'.'),'',$_POST['payer_email']);   
        $password = $_POST['invoice'];
        $email = $_POST['payer_email'];
        $uid = wp_create_user($uname,$password,$_POST['payer_email']);
        $logininfo = "
         Username: $uname<br/>
         Passworf: $password<br/>
        ";
        }    
            
        
        $order->Update(array('order_status'=>$_POST['payment_status'],'payment_status'=>$_POST['payment_status'],'uid'=>$uid), $_POST['invoice']);        
        
        $sitename = get_option('blogname');
        $message = <<<MAIL
                    Hello {$uname},<br/>
                    Thanks for your business with us.<br/>                    
                    Please <a href="{$myorders}">click here</a> to view your purchased items.<br/>
                    {$myorders} <br/>
                    {$logininfo}                    
                    <br/><br/>
                    Regards,<br/>
                    Admin<br/>
                    <b>{$sitename}</b>
                    
MAIL;
        $headers = 'From: '.get_option('blogname').' <'.get_option('admin_email').'>' . "\r\n\\";
        wp_mail( $email, "You order on ".get_option('blogname'), $message, $headers, $attachments );        
        die("OK");
        }
       
        if($page=='return'&&$_POST['payment_status']=='Completed'){
            if(!$current_user->ID){
            $uname = str_replace(array("@",'.'),'',$_POST['payer_email']);   
            $password = $_POST['invoice'];
            $creds = array();
            $creds['user_login'] = $uname;
            $creds['user_password'] = $password;
            $creds['remember'] = true;
            $user = wp_signon( $creds, false );        
            }            
            die("<script>location.href='$myorders';</script>");
        } 
        
        die();
    }
}

function get_all_coupon($data){
    $total = 0;
    foreach($data as $pid => $item){
        $valid_coupon=check_coupon($pid,$item['coupon']);       
        if($valid_coupon != 0) {
            
            $total +=  ($item['item_total']*$item['quantity']*($valid_coupon/100));
        }
    }
    return $total;
    
}

function wpmp_clear_user_cartdata($user_login, $user) {
   delete_option($user->ID."_cart");
}
add_action('wp_login', 'wpmp_clear_user_cartdata', 10, 2);
