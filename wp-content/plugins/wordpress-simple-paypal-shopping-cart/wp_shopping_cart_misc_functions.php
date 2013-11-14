<?php

/* TODO
- After processing an IPN, call a function to clear all trash orders that are older than 6 hours.
- add a "button_image" parameter in the shortcode to customize the add to cart button
- add an option for the admin email notification.
- add a reset cart button
- Mention the available languages
*/

/* this function gets called when init is fired */
function wp_cart_init_handler()
{
    //Add any common init hook handing code
    if(is_admin())//Init hook handing code for wp-admin
    {
        wpspc_create_orders_page();
    }
    else//Init hook handing code for front end
    {
        wpspc_cart_actions_handler();
        add_filter('ngg_render_template','wp_cart_ngg_template_handler',10,2);
        if(isset($_REQUEST['simple_cart_ipn']))
        {
            include_once('paypal.php');
            wpc_handle_paypal_ipn();
            exit;
        }
    }
}

function wp_cart_admin_init_handler()
{
    wpspc_add_meta_boxes();
}

function wpspsc_number_format_price($price)
{
    $formatted_num = number_format($price,2,'.','');
    return $formatted_num;
}

function wpc_append_values_to_custom_field($name,$value)
{
    $custom_field_val = $_SESSION['wp_cart_custom_values'];
    $new_val = $name.'='.$value;
    if (empty($custom_field_val)){
        $custom_field_val = $new_val;
    }
    else{
        $custom_field_val = $custom_field_val.'&'.$new_val;
    }
    $_SESSION['wp_cart_custom_values'] = $custom_field_val;
    return $custom_field_val;
}

function wp_cart_get_custom_var_array($custom_val_string)
{
    $delimiter = "&";
    $customvariables = array();
    $namevaluecombos = explode($delimiter, $custom_val_string);
    foreach ($namevaluecombos as $keyval_unparsed)
    {
            $equalsignposition = strpos($keyval_unparsed, '=');
            if ($equalsignposition === false)
            {
                $customvariables[$keyval_unparsed] = '';
                continue;
            }
            $key = substr($keyval_unparsed, 0, $equalsignposition);
            $value = substr($keyval_unparsed, $equalsignposition + 1);
            $customvariables[$key] = $value;
    }
    return $customvariables;
}

function wspsc_reset_logfile()
{
    $log_reset = true;
    $logfile = dirname(__FILE__).'/ipn_handle_debug.log';
    $text = '['.date('m/d/Y g:i A').'] - SUCCESS : Log file reset';
    $text .= "\n------------------------------------------------------------------\n\n";
    $fp = fopen($logfile, 'w');
    if($fp != FALSE) {
            @fwrite($fp, $text);
            @fclose($fp);
    }
    else{
            $log_reset = false;	
    }
    return $log_reset;
}

function wp_cart_ngg_template_handler($arg1,$arg2)
{
    if($arg2=="gallery-wp-cart"){
        $template_name = "gallery-wp-cart";
        $gallery_template = WP_CART_PATH. "/lib/$template_name.php";
        return $gallery_template;
    }
    return $arg1;
}

function wpspc_insert_new_record()
{
    //First time adding to the cart
    //$cart_id = uniqid();
    //$_SESSION['simple_cart_id'] = $cart_id;
    $wpsc_order = array(
    'post_title'    => 'WPSC Cart Order',
    'post_type'     => 'wpsc_cart_orders',
    'post_content'  => '',
    'post_status'   => 'trash',
    );
    // Insert the post into the database
    $post_id  = wp_insert_post($wpsc_order);
    if($post_id){
        //echo "post id: ".$post_id;
        $_SESSION['simple_cart_id'] = $post_id;
        $updated_wpsc_order = array(
            'ID'             => $post_id,
            'post_title'    => $post_id,
            'post_type'     => 'wpsc_cart_orders',
        );
        wp_update_post($updated_wpsc_order);
        $status = "In Progress";
        update_post_meta($post_id, 'wpsc_order_status', $status);
        if(isset($_SESSION['simpleCart']) && !empty($_SESSION['simpleCart']))
        {
            update_post_meta( $post_id, 'wpsc_cart_items', $_SESSION['simpleCart']);
        }
    }
}

function wpspc_update_cart_items_record()
{
    if(isset($_SESSION['simpleCart']) && !empty($_SESSION['simpleCart']))
    {
        $post_id = $_SESSION['simple_cart_id'];
        update_post_meta( $post_id, 'wpsc_cart_items', $_SESSION['simpleCart']);
    }
}

function wpspc_apply_dynamic_tags_on_email_body($ipn_data, $args)
{
    $tags = array("{first_name}","{last_name}","{product_details}");
    $vals = array($ipn_data['first_name'], $ipn_data['last_name'], $args['product_details']);

    $body = stripslashes(str_replace($tags, $vals, $args['email_body']));
    return $body;
}

function wpspc_run_activation()
{
    //General options
    add_option('wp_cart_title', __("Your Shopping Cart", "WSPSC"));
    add_option('wp_cart_empty_text', __("Your cart is empty", "WSPSC"));
    add_option('cart_return_from_paypal_url', get_bloginfo('wpurl'));

    //Add Confirmation Email Settings
    add_option("wpspc_send_buyer_email", 1); 
    $from_email_address = get_bloginfo('name')." <sales@your-domain.com>";
    add_option('wpspc_buyer_from_email', $from_email_address);
    $buyer_email_subj = "Thank you for the purchase";
    add_option('wpspc_buyer_email_subj', $buyer_email_subj);
    $email_body .= "Dear {first_name} {last_name}"."\n";
    $email_body .= "\nThank you for your purchase! You ordered the following item(s):\n";
    $email_body .= "\n{product_details}";
    add_option('wpspc_buyer_email_body', $email_body);
}
