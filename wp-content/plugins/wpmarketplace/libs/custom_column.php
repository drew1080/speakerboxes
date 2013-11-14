<?php

add_filter('manage_edit-wpmarketplace_columns', 'add_new_wpmarketplace_columns');
function add_new_wpmarketplace_columns($wpmarketplace_columns){
    //echo "<pre>"; print_r($wpmarketplace_columns); echo "</pre>";
   // die();
    
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = __('Title', 'wpmarketplace');
    $new_columns['author'] = __('Author','wpmarketplace');
    $new_columns['comments'] = __('<span class="vers"><div title="Comments" class="comment-grey-bubble"></div></span>','wpmarketplace');
    $new_columns['no_of_sale'] = __('Sales Quantity','wpmarketplace');
    $new_columns['total_income'] = __('Total Sales','wpmarketplace');
    $new_columns['graph'] = __('Sales Graph','wpmarketplace');
    $new_columns['date'] = __('Date', 'wpmarketplace');
 
    return $new_columns;
    
}

add_action('manage_wpmarketplace_posts_custom_column', 'manage_wpmarketplace_columns', 10, 2);
function manage_wpmarketplace_columns($column_name, $id) {
    global $wpdb;
    $count = 0;
    $income = 0;
    $query = "select * from `{$wpdb->prefix}mp_order_items` where pid=$id";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $currency_sign = get_option('_wpmp_curr_sign','$');
    if($result){
        //$count = $wpdb->num_rows;
        foreach ($result as $row){
            $order_id = $row['oid'];
            $get_res = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}mp_orders` where order_id='$order_id' and payment_status='Completed'",ARRAY_A );
            if(!empty($get_res)){
                $income += $get_res['total'];
                $count+= $row['quantity'];
            }
        }
    }
    switch ($column_name) {
    case 'no_of_sale':
        
        echo "$count";
            break;
 
    case 'total_income':
        echo "$currency_sign $income"; 
        break;
    case 'graph':
        echo "<a href='edit.php?post_type=wpmarketplace&page=product-report&post_id=$id'>View Graph</a>";
    default:
        break;
    } // end switch
}   

