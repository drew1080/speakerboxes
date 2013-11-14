<?php

function wpmp_popular_files($start,$limit){
    global $wpdb;
    $files=$wpdb->get_results("select *, sum(oi.price) as price_total from {$wpdb->prefix}mp_orders o inner join {$wpdb->prefix}mp_order_items oi on oi.oid=o.order_id inner join {$wpdb->prefix}posts p on oi.pid=p.ID where p.post_type='wpmarketplace'and o.payment_status='Completed'  group by  oi.pid order by price_total desc limit $start, $limit");
    
    return $files;
}
//number of popular files
function wpmp_total_popular_files(){
    global $wpdb;
    $files=$wpdb->get_var("select distinct count(distinct pid) from {$wpdb->prefix}mp_orders o inner join {$wpdb->prefix}mp_order_items oi on oi.oid=o.order_id inner join {$wpdb->prefix}posts p on oi.pid=p.ID where p.post_type='wpmarketplace' and o.payment_status='Completed'");    
    
    return $files;
}
//number of total sales
function wpmp_total_purchase($pid=''){
     global $wpdb;
     if(!$pid) $pid = get_the_ID();
     $sales = $wpdb->get_var("select count(*) from {$wpdb->prefix}mp_orders o, {$wpdb->prefix}mp_order_items oi where oi.oid=o.order_id and oi.pid='$pid' and o.payment_status='Completed'");
     return $sales;
}
 
//the function for adding the product from the frontend
function wpmp_add_product(){ 
    if(isset($_POST['__product_wpmp']) && wp_verify_nonce($_POST['__product_wpmp'],'wpmp-product')&&$_POST['task']==''){ //echo "here";exit; 
        if( $_POST['post_type']=="wpmarketplace"){
            global $current_user, $wpdb;
            get_currentuserinfo();    
            $settings = get_option('_wpmp_settings');
            $pstatus=$settings['fstatus']?$settings['fstatus']:"draft";
            $my_post = array(
             'post_title' => $_POST['product']['post_title'],
             'post_content' => $_POST['product']['post_content'],
             'post_excerpt' => $_POST['product']['post_excerpt'],
             'post_status' => $pstatus,
             'post_author' => $current_user->ID,
             'post_type' => "wpmarketplace" 
             
            );

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

             //set the product type
            wp_set_post_terms($postid,$_POST['product_type'], "ptype"); 

            foreach($_POST['wpmp_list'] as $k=>$v){
                update_post_meta($postid,$k,$v);
             
            }


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
            
            //send admin email
            if($pstatus=="draft"){
                //get user emai
                global $current_user;
                get_currentuserinfo();
                mail($current_user->user_email,"New Product Added","Your product is successfully added and is waiting to admin review. You will be notified if your product is accepetd or rejected.");
                
                //now send notification to site admin about newly added product
                $admin_email = get_bloginfo('admin_email');
                mail($admin_email,"Product Review", "New Product is added by user " .$current_user->user_login . ". Please review this product to add your store.");
                
                //add a new post meta to identify only drafted post
                if ( ! update_post_meta ($postid, '_z_user_review', '1' ) ){
                    add_post_meta( $postid, '_z_user_review', '1',true );
                }
            }
            
        }
          
        header("Location: ".$_SERVER['HTTP_REFERER']);
        die();
     }
}


//Send notification before delete product
//add_action( 'before_delete_post', 'notify_product_rejected' );
add_action('wp_trash_post', 'notify_product_rejected');
function notify_product_rejected($post_id){
    global $post_type;   
    if ( $post_type != 'wpmarketplace' ) return;

        $post = get_post($post_id);
        $post_meta = get_post_meta($post_id,"_z_user_review",true);
        if($post_meta != ""):

            $author = get_userdata($post->post_author);
            $author_email = $author->user_email;
            $email_subject = "Your product has been rejected.";

            ob_start(); ?>

            <html>
                <head>
                    <title>New post at <?php bloginfo( 'name' ) ?></title>
                </head>
                <body>
                    <p>
                        Hi <?php echo $author->user_firstname ?>,
                    </p>
                    <p>
                        Your product <?php the_title() ?> has been rejected.
                    </p>
                </body>
            </html>

            <?php

            $message = ob_get_contents();

            ob_end_clean();

            wp_mail( $author_email, $email_subject, $message );
        endif;
    
}

// SEND EMAIL ONCE POST IS PUBLISHED
add_action( 'publish_post', 'notify_product_accepted' );
function notify_product_accepted($post_id) {
    
    //only my custom post type
    global $post_type;   
    if ( $post_type != 'wpmarketplace' ) return;
    
    //echo "<pre>";    print_r($_POST); echo "</pre>";
    if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' )) {
        $post = get_post($post_id);
        $post_meta = get_post_meta($post_id,"_z_user_review",TRUE);
        if($post_meta != ""):
        
            $author = get_userdata($post->post_author);
            $author_email = $author->user_email;
            $email_subject = "Your post has been published.";

            ob_start(); ?>

            <html>
                <head>
                    <title>New post at <?php bloginfo( 'name' ) ?></title>
                </head>
                <body>
                    <p>
                        Hi <?php echo $author->user_firstname ?>,
                    </p>
                    <p>
                        Your product <a href="<?php echo get_permalink($post->ID) ?>"><?php the_title_attribute() ?></a> has been published.
                    </p>
                </body>
            </html>

            <?php

            $message = ob_get_contents();

            ob_end_clean();

            wp_mail( $author_email, $email_subject, $message );
        endif;
    }
    //wpmarket@wpmarketplaceplugin.com
}



 
 ///for withdraw request
 function wpmp_withdraw_request(){
     global $wpdb, $current_user;

     $uid = $current_user->ID;

     if(isset($_POST['withdraw'],$_POST['withdraw_amount']) && $_POST['withdraw']==1 && $_POST['withdraw_amount']>0){
    
        $wpdb->insert( 
        "{$wpdb->prefix}mp_withdraws",
        array( 
            'uid' => $uid,
             'date' => time(),
             'amount' => $_POST['withdraw_amount'],
             'status' => 0
        ), 
        array( 
            '%d', 
            '%d', 
            '%f', 
            '%d' 
        ) 
    );
    header("Location: ".$_SERVER['HTTP_REFERER']);
    die();    
    }
     
 }
 
 //count the total number of product
 function total_product(){
   global $wpdb;
   $total_product=$wpdb->get_var("select count(ID) from {$wpdb->prefix}posts where post_type='wpmarketplace' and post_status='publish'");
   return $total_product;  
 }
 //featured products
 function feature_products($show=10){
    global $wpdb;
    $files=$wpdb->get_results("select * from {$wpdb->prefix}mp_feature_products fp inner join {$wpdb->prefix}posts p on p.ID=fp.productid where p.post_type='wpmarketplace' and ".time()." between startdate and enddate limit 0,{$show}");
    return $files;
 }
 
 function featured_products($show=10){
    global $wpdb;
    $files=$wpdb->get_results("select * from {$wpdb->prefix}mp_feature_products fp inner join {$wpdb->prefix}posts p on p.ID=fp.productid where p.post_type='wpmarketplace' and p.post_status='publish' and ".time()." between startdate and enddate limit 0,{$show}");
    return $files;
 }
 
 //top rated products
 function top_rate_products($show=10){
    global $wpdb;
    $querystr = "
    SELECT $wpdb->posts.* 
    FROM $wpdb->posts, $wpdb->postmeta
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
    AND $wpdb->postmeta.meta_key = 'avg_rating' 
    AND $wpdb->posts.post_status = 'publish' 
    AND $wpdb->posts.post_type = 'wpmarketplace'
    ORDER BY $wpdb->postmeta.meta_value DESC  limit 0,{$show}
 ";

 $pageposts = $wpdb->get_results($querystr, OBJECT); 
 return $pageposts;
 }


 function wpmp_redirect($url){
     if(!headers_sent())
         header("location: ". $url);
     else
         echo "<script>location.href='{$url}';</script>";
     die();
 }
 function wpmp_js_redirect($url){

         echo "&nbsp;Redirecting...<script>location.href='{$url}';</script>";
     die();
 }


 function wpmp_members_page(){
     $settings = get_option('_wpmp_settings');
     return get_permalink($settings['members_page_id']);
 }
 
  function wpmp_orders_page(){
     $settings = get_option('_wpmp_settings');
     return get_permalink($settings['orders_page_id']);
  }

/**
 * Retrienve Site Commissions on User's Sales
 * @param null $uid
 * @return mixed
 */
function wpmp_site_commission($uid = null){
      global $current_user;
      $user = $current_user;
      if($uid) $user = get_userdata($uid);
      $comission = get_option("wpmp_user_comission");
      $comission =  $comission[$user->roles[0]];
      return $comission;
  }



function wpmp_get_user_earning(){

}


function wpmp_user_dashboard(){
    include(WPMP_BASE_DIR.'/tpls/dashboard.php');
    return $data;
}


function wpmp_product_price($pid){
    $pinfo = get_post_meta($pid,"wpmp_list_opts",true);
    $price = $pinfo['sales_price']?$pinfo['sales_price']:$pinfo['base_price'];
    return number_format($price,2);
}

function wpmp_addtocart_link($id){
    $pinfo = get_post_meta($id,"wpmp_list_opts",true);
    @extract($pinfo);
    $settings = isset($pinfo['settings'])?$pinfo['settings']:array();
    $cart_enable="";
    if(isset($settings['stock']['enable'])&&$settings['stock']['enable']==1){
        if($manage_stock==1){
            if($stock_qty>0)$cart_enable=""; else $cart_enable=" disabled ";
        }
    }
    if(isset($pinfo['price_variation'])&&$pinfo['price_variation'])
        $html = "<a href='".get_permalink($id)."' class='btn btn-info btn-small cart_form'><i class='icon-shopping-cart icon-white'></i> ".__("Add to Cart","wpmarketplace")."</a>";
    else{
        $html = <<<PRICE
                        <form method="post" action="" name="cart_form" class="cart_form">
                        <input type="hidden" name="add_to_cart" value="add">
                        <input type="hidden" name="pid" value="$id">
                         

PRICE;
        $html.='<button '.$cart_enable.' class="btn btn-info btn-small" type="submit" ><i class="icon-shopping-cart icon-white"></i> '.__("Add to Cart","wpmarketplace").'</button></form>';

    }
    return $html;
}


function wpmp_all_products($params){
    include(WPMP_BASE_DIR.'tpls/catalog.php');
	 
}

//testing purpost
function wpmp_all_products_2($params){
    include_once(WPMP_BASE_DIR.'tpls/catalog2.php');
}

add_action("wp_ajax_loadMoreProduct", "loadMoreProduct");
add_action("wp_ajax_nopriv_loadMoreProduct", "loadMoreProduct");
function loadMoreProduct() {

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "loadMore")) {
      exit("No naughty business please");
   }   
   $offset = (int)$_REQUEST['offset'];
   $postperpage = (int)$_REQUEST['postperpage'];
   $imgwidth = (int)$_REQUEST['imgwidth'];
   //$offset -= 1;
   $result['type'] = "error";
   
   $args2 = array(
    'post_type' => 'wpmarketplace',
    'post_status'=>'publish',
    //'orderby' => 'title', 
    //'order' => 'ASC',
   'posts_per_page' => $postperpage, 
   'offset' => $offset
    );

    $the_query = new WP_Query( $args2 );
    $ret = "";
    if($the_query->have_posts()): 
        $result['type']='success'; 
        $result['offset'] = $offset + $postperpage;
        while($the_query->have_posts()): $the_query->the_post();
        //get post category
        //$post_categories = wp_get_post_categories( $post_id );
        $categories1 =  get_the_terms(get_the_ID(),'ptype');
        $cat = "";
        if($categories1){
            foreach($categories1 as $category) {
                //print_r($category);
                $cat .= $category->slug ." ";
            }
        }
        //else echo "no category...";
        
        
        
        
        
        
        $title = sanitize_title( get_the_title(), $fallback_title );
        
        $ret .= "<li style='margin:0 !important;' data-name='$title' class='mix $cat'>";
	$ret .= '<div class="meta name">';
	$ret .= '<div class="img_wrapper loaded">';
	$ret .= get_the_post_thumbnail(get_the_ID(),array($imgwidth,($imgwidth*1.5)));
	$ret .= '</div><div class="titles">';
        $link = get_permalink(get_the_ID());
        $title = get_the_title();
        $ret .= "<h2>$title</h2>";
        $ret .= '<p><em>   </em></p></div></div><div class="meta region">';
	if(function_exists('wpmp_product_price')) $ret .= "<p>" .get_option('_wpmp_curr_sign','$').wpmp_product_price(get_the_ID()) . "</p>";
	$ret .= '</div><div class="meta rec"><ul>';
           if($categories1){
                foreach($categories1 as $category) {
                    //print_r($category);
                    $ret .= "<li>".$category->slug ."</li>";
                }
            }
            else $ret .= "<li>N/A</li>";
            
        $ret .= '</ul></div><div class="meta area">';
        if(function_exists('wpmp_addtocart_link')) $ret .= wpmp_addtocart_link(get_the_ID());
        $ret .= "</div></li>";
        
        
        

        endwhile; 
    endif;             
                
                    wp_reset_postdata();
    $result['products']  = $ret;            
    
   if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      $result = json_encode($result);
      echo $result;
   }
   else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
   }

   die();

}
/*
function loadMoreProduct() {

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "loadMore")) {
      exit("No naughty business please");
   }   
   $offset = (int)$_REQUEST['offset'];
   $postperpage = (int)$_REQUEST['postperpage'];
   $imgwidth = (int)$_REQUEST['imgwidth'];
   //$offset -= 1;
   $result['type'] = "error";
   
   $args2 = array(
    'post_type' => 'wpmarketplace',
    'post_status'=>'publish',
    //'orderby' => 'title', 
    //'order' => 'ASC',
   'posts_per_page' => $postperpage, 
   'offset' => $offset
    );

    $the_query = new WP_Query( $args2 );
    $ret = "";
    if($the_query->have_posts()): 
        $result['type']='success'; 
        $result['offset'] = $offset + $postperpage;
        while($the_query->have_posts()): $the_query->the_post();
        //get post category
        //$post_categories = wp_get_post_categories( $post_id );
        $categories1 =  get_the_terms(get_the_ID(),'ptype');
        $cat = "";
        if($categories1){
            foreach($categories1 as $category) {
                //print_r($category);
                $cat .= $category->slug ." ";
            }
        }
        //else echo "no category...";
        $title = sanitize_title( get_the_title(), $fallback_title );
        $ret .= "<li class='mix $cat' data-cat='$title'>";

        $ret .= '<div class="pin">';
        $ret .='<div class="thumb">';
        $ret .= get_the_post_thumbnail(get_the_ID(),array($imgwidth,($imgwidth*1.5)));
        $ret .= '</div>';
        $ret .= '<div class="thumb-info">';
        $link = get_permalink(get_the_ID());
        $title = get_the_title();
        $ret .= "<h3><a href='$link'>$title</a></h3>";
        $ret .= '<div class="pull-left" style="font-size:10pt; font-weight: bold;">';
        if(function_exists('wpmp_product_price')) $ret .= get_option('_wpmp_curr_sign','$').wpmp_product_price(get_the_ID());
        $ret .= '</div>';
        $ret .= '<div class="pull-right">';
        if(function_exists('wpmp_addtocart_link')) $ret .= wpmp_addtocart_link(get_the_ID());
        $ret .= '</div>';
        $ret .= '<div style="clear: both"></div>';
        $ret .= '</div>';
        $ret .= '</div>';

        $ret .= '</li>';

        endwhile; 
    endif;             
                
                    wp_reset_postdata();
    $result['products']  = $ret;            
    
   if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      $result = json_encode($result);
      echo $result;
   }
   else {
      header("Location: ".$_SERVER["HTTP_REFERER"]);
   }

   die();

}
*/





function wpmp_all_feature_products($params){
    include(WPMP_BASE_DIR.'tpls/catalog_feature.php');
	 
}

//delete product from front-end
function wpmp_delete_product(){
    if(is_user_logged_in()&&isset($_GET['dproduct'])){
        global $current_user;
        $pid = intval($_GET['dproduct']);
        $pro = get_post($pid);
        
        if($current_user->ID==$pro->post_author){
            wp_update_post(array('ID'=>$pid,'post_status'=>'trash'));
            $settings = get_option('_wpmp_settings');
            if($settings['frontend_product_delete_notify']==1){
                wp_mail(get_option('admin_email'),"I had to delete a product","Hi, Sorry, but I had to delete following product for some reason:<br/>{$pro->post_title}","From: {$current_user->user_email}\r\nContent-type: text/html\r\n\r\n");
            }
            $_SESSION['dpmsg'] = 'Product Deleted';
            header("location: ".$_SERVER['HTTP_REFERER']);
            die();
        } 
    }
}

function wpmp_order_completed_mail(){
    
}
 
 function wpmp_head(){
    ?>
    
        <script language="JavaScript">
         <!--
         var wpmp_base_url = '<?php echo plugins_url('/wpmarketplace/'); ?>';
         jQuery(function(){
             jQuery('.wpmp-thumbnails a').lightBox({fixedNavigation:true});
         });  
         //-->
         </script>
    
    <?php 
 }
 
 function wpmp_product_report_scripts(){
     wp_enqueue_script(
		'flot',
		WP_PLUGIN_URL . '/wpmarketplace/js/jquery.flot.js',
		array( 'jquery' )
	);
     wp_enqueue_script(
		'float-resize',
		WP_PLUGIN_URL . '/wpmarketplace/js/jquery.flot.resize.js',
		array( 'jquery' )
	);
     wp_enqueue_script(
		'float-time',
		WP_PLUGIN_URL . '/wpmarketplace/js/jquery.flot.time.js',
		array( 'jquery' )
	);
     $path = WP_PLUGIN_URL . '/wpmarketplace/js/excanvas.min.js';
     echo '<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="'.$path.'"></script><![endif]-->';
     //
     /*
     add_action('wp_head',function(){
         $path = WP_PLUGIN_URL . '/wpmarketplace/js/excanvas.min.js';
         echo '<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="'.$path.'"></script><![endif]-->';
     });
      */
 }
 
 function wpmp_product_report_styles(){
     wp_enqueue_style(
		'float-css',
		WP_PLUGIN_URL . '/wpmarketplace/css/admin/product_report.css'
	);
 }
 
 