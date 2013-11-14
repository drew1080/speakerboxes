 <?php
    function wpmp_update_shipping(){
        if($_REQUEST['update_shipping']=="update"){
            $cart_data=wpmp_get_cart_data();            
            $cart_data['shipping']['shipping_method']=$_POST['shipping_method'];
            $cart_data['shipping']['shipping_rate']=$_POST['shipping_rate'];
            wpmp_update_cart_data($cart_data);
            
            die();
        }
    }
?>