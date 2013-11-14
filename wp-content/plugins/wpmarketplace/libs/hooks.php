<?php

add_action('init', 'wpmp_post_types');
add_action('init', 'wpmp_update_cart');
//add_action( 'init', 'wpmp_update_shipping');
add_action('init', 'wpmp_remove_cart_item');
add_action('wp_loaded', 'wpmp_do_register');
add_action('wp_loaded', 'wpmp_do_login');
add_action('init', 'wpmp_save_billing_info');
add_action('init', 'wpmp_save_shipping_info');
add_action('init', 'wpmp_save_payment_method_info');
add_action('init', 'wpmp_place_order');
//for the payment notification by the user
add_action("init", "wpmp_payment_notification");
//for the withdraw payment notification
add_action("init", "wpmp_withdraw_paypal_notification");
add_action('init', 'ajaxinit');
add_action('init', 'wpmp_add_to_cart');
//the function for adding the product from the frontend
add_action('wp_loaded', 'wpmp_add_product');
//payment from the theme orders panel
add_action('init', 'wpmp_ajax_payfront');

//for withdraw request
add_action('init', 'wpmp_withdraw_request');

//for the print invoice
add_action('init', 'wpmp_print_invoice');

add_action('init', 'register_marketplace_product_taxonomies');
add_action('init', 'wpmp_download', 0);
add_action('the_content', 'wpmp_buynow', 999999);
add_filter('the_content', 'wpmp_myorders');
add_filter('wp_head', 'wpmp_head');
add_filter("the_content", "wpmp_the_content");

add_shortcode('wpmp-checkout', 'wpmp_checkout');
add_shortcode("wpmp-cart", "wpmp_show_cart");
//for the cart page
add_shortcode("wpmp-tabs", "wpmp_tabs");
//short code for the add product
add_shortcode("wpmp-add-product", "wpmp_front_add_product");
//short code for the earnings
add_shortcode("wpmp-earnings", "wpmp_earnings");
//short code for product list
add_shortcode("wpmp-my-products", "wpmp_front_product_list");
//short code for members tabs
add_shortcode("wpmp-frontend", "wpmp_frontend");
//short code for edit profile
add_shortcode("wpmp-edit-profile", "wpmp_edit_profile");
//user orders
add_shortcode('my-orders-sc', 'wpmp_user_order');

add_shortcode("wpmp-orders", "wpmp_orders");
add_shortcode("wpmp-dashboard", "wpmp_user_dashboard");
add_shortcode("wpmp-all-products", "wpmp_all_products");
//testing purpose..
add_shortcode("wpmp-all-products-2", "wpmp_all_products_2");

add_shortcode("wpmp-all-feature-products", "wpmp_all_feature_products");

if (is_admin()) {
	add_action("admin_menu", "wpmp_menu");
	add_action('admin_init', 'wpmp_meta_boxes', 0);
	add_action('save_post', 'wpmp_save_meta_data', 10, 2);
	//add_action('delete_post', 'wpmp_delete_product');
	add_action('wp_ajax_wpmp_save_settings', 'wpmp_save_settings');
	add_action('wp_ajax_wpmp_ajax_call', 'wpmp_ajax_call');
	add_action('wp_ajax_moveuploadprevfile', 'wpmp_move_upload_previewfile');
	add_action('wp_ajax_moveuploadprofile', 'wpmp_move_upload_productfile');
	add_action('wp_ajax_moveuploadfeaturedfile', 'wpmp_move_upload_featuredfile');
	//for auto suggest tool
	add_action('wp_ajax_wpmp_autosuggest', 'wpmp_autosuggest');
	//for removing feature product
	add_action('wp_ajax_wpmp_remove_featured', 'wpmp_remove_featured');

	//add_action( 'wp_ajax_wpmp_save_currencies', 'wpmp_save_currencies');
	//for default currency saving
	add_action('wp_ajax_wpmp_default_currency', 'wpmp_default_currency');
	//for default currency deleting
	add_action('wp_ajax_wpmp_default_currency_del', 'wpmp_default_currency_del');
	//wp_enqueue_script('jquery-form');
}
if(!is_admin())
add_action('init', 'wpmp_delete_product');
add_action('wp_enqueue_scripts', 'wpmp_enqueue_scripts');
add_action('admin_enqueue_scripts', 'wpmp_enqueue_scripts');
add_action('init', 'wpmp_init');
add_action('admin_enqueue_scripts', 'wpmp_plu_admin_enqueue');
add_action("admin_head", "plupload_admin_head");
add_action('wp_ajax_plupload_action', "g_plupload_action");
add_action('admin_notices', 'wpmp_check_dir');

add_action('show_user_profile', 'add_zip_profile_fields');
add_action('edit_user_profile', 'add_zip_profile_fields');
add_action('profile_update', 'save_userzip_data', 10, 2);

add_action('init', 'wpmp_languages');
add_action('init', 'wpmp_update_profile');
