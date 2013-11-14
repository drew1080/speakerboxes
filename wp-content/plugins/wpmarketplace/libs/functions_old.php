<?php

function wpmp_popular_files(){
    global $wpdb;
    $files=$wpdb->get_results("select *, sum(oi.price) as price_total from {$wpdb->prefix}mp_orders o inner join {$wpdb->prefix}mp_order_items oi on oi.oid=o.order_id inner join {$wpdb->prefix}posts p on oi.pid=p.ID where p.post_type='wpmarketplace'  group by  oi.pid order by price_total desc");
    return $files;
}
 function wpmp_total_purchase($pid=''){
     global $wpdb;
     if(!$pid) $pid = get_the_ID();
     $sales = $wpdb->get_var("select count(*) from {$wpdb->prefix}mp_orders o, {$wpdb->prefix}mp_order_items oi where oi.oid=o.order_id and oi.pid='$pid' and o.payment_status='Completed'");
     return $sales;
 }
 
 //the function for adding the product from the frontend
 function wpmp_add_product(){
      if(wp_verify_nonce($_POST['__product_wpmp'],'wpmp-product')&&$_POST['task']==''){
      if( $_POST['post_type']=="wpmarketplace"){
          global $current_user, $wpdb;
         get_currentuserinfo();
         
         $my_post = array(
             'post_title' => $_POST['product']['post_title'],
             'post_content' => $_POST['product']['post_content'],
             'post_excerpt' => $_POST['product']['post_excerpt'],
             'post_status' => "draft",
             'post_author' => $current_user->ID,
             'post_type' => "wpmarketplace",
             
          );
          //echo $_POST['id'];
          if($_POST['id']){
              //update post
              $my_post['ID']=$_REQUEST['id'];
              wp_update_post( $my_post );
               $postid= $_REQUEST['id'];  
          }else{
              //insert post
              $postid=wp_insert_post( $my_post );
          }
          
          update_post_meta($postid,"wpmp_list_opts",$_POST['wpmp_list']);  
         foreach($_POST['wpmp_list'] as $k=>$v){
                update_post_meta($postid,$k,$v);
             
         }
      
         //echo $_POST['wpmp_list']['fimage'];
         if($_POST['wpmp_list']['fimage']){
              $wp_filetype = wp_check_filetype(basename($_POST['wpmp_list']['fimage']), null );
              $attachment = array(
                 'post_mime_type' => $wp_filetype['type'],
                 'post_title' => preg_replace('/\.[^.]+$/', '', basename($_POST['wpmp_list']['fimage'])),
                 'post_content' => '',
                 'guid' => $_POST['wpmp_list']['fimage'],
                 'post_status' => 'inherit'
              );
              $attach_id = wp_insert_attachment( $attachment, $_POST['wpmp_list']['fimage'], $postid );
              
              set_post_thumbnail( $postid, $attach_id );
         }
      }
      //echo $_SERVER['HTTP_REFERER'];
    header("Location: ".$_SERVER['HTTP_REFERER']);
    die();
     
 }
 }
 
//add_action("init","wpmp_add_product");