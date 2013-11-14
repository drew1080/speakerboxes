<?php 
    /*
    Plugin Name:  WP Marketplace
    Plugin URI: http://wpeden.com/
    Description: Marketplace Plugin for Wordpress
    Author: Shaon
    Version: 2.2.2
    Author URI: http://wpmarketplaceplugin.com/
    */

    @session_start();  
   

    define('WPMP_UPLOAD_DIR',WP_CONTENT_DIR.'/uploads/wpmp-products/');
    define('WPMP_IMAGE_DIR',WP_CONTENT_DIR.'/uploads/wpmp-previews/');
    define('WPMP_IMAGE_URL',content_url('/uploads/wpmp-previews/'));
    define('WPMP_BASE_DIR',WP_CONTENT_DIR.'/plugins/wpmarketplace/');

    function wpmp_languages() {
        load_plugin_textdomain( 'wpmarketplace', false, dirname(plugin_basename( __FILE__ )).'/languages/' ); 
    }


    include(dirname(__FILE__)."/libs/functions.php");
    include(dirname(__FILE__)."/libs/class.plugin.php");
    include(dirname(__FILE__)."/libs/class.order.php");
    include(dirname(__FILE__)."/libs/class.payment.php");
    include(dirname(__FILE__)."/libs/class.html.php");
    include(dirname(__FILE__)."/libs/cart.php");
    include(dirname(__FILE__)."/libs/print_invoice.php");
    include(dirname(__FILE__)."/libs/hooks.php");
    include(dirname(__FILE__)."/libs/install.php");
    include(dirname(__FILE__)."/libs/stock.php");
    include(dirname(__FILE__)."/widget.php");
    include(dirname(__FILE__)."/libs/custom_user_info.php");
    include(dirname(__FILE__)."/libs/custom_column.php");
    include(dirname(__FILE__)."/featureset/functions.php");


    //auto load default payment mothods
    global $payment_methods;
    $pdir=WP_PLUGIN_DIR."/wpmarketplace/libs/payment_methods/";
    $methods=scandir($pdir,1);
    //array_shift($methods);
    //array_shift($methods);
    foreach($methods as $method){
        if($method !="." && $method !=".."){
            $payment_methods[]=$method;
            if(file_exists($pdir.$method."/class.{$method}.php")){           
                include_once($pdir.$method."/class.{$method}.php");
            }
        }

    }

    global $sap;//seperator
    if(function_exists('get_option')){
        if ( get_option('permalink_structure') != '' ) $sap = '?';
        else $sap = "&";
    }


    $wpmp_plugin = new ahm_plugin('wpmarketplace');

    function wpmp_check_dir(){
        if(!file_exists(WPMP_UPLOAD_DIR))
            @mkdir(WPMP_UPLOAD_DIR,0755);
        if(!file_exists(WPMP_IMAGE_DIR))
            @mkdir(WPMP_IMAGE_DIR,0755);    

        if(!file_exists(WPMP_UPLOAD_DIR)) {
            echo '<div class="updated error">
            <p> '.__("Failed to create product dir autometically. You have to create the dir ","wpmarketplace").' "'.WPMP_UPLOAD_DIR.'" '.__("manually.","wpmarketplace").'</p>
            </div>';
        }
        if(!file_exists(WPMP_IMAGE_DIR)) {
            echo '<div class="updated error">
            <p> '.__("Failed to create product image dir autometically. You have to create the dir ","wpmarketplace").' "'.WPMP_IMAGE_DIR.'" '.__("manually.","wpmarketplace").'</p>

            </div>';
        }
    }

    function wpmp_the_content($content){
        global $post;      
        $settings = get_option('_wpmp_settings');    
        if(!is_single()||!isset($settings['generate_product_page_content'])) return $content;
        if($post->post_type!='wpmarketplace') return $content;     
        @extract(get_post_meta($post->ID,"wpmp_list_opts",true)); 
        include("tpls/product/default.php");
        return $content1;
    }

    //returns live preview url
    function wpmp_live_preview(){

    }

    //returns screen shots url
    function wpmp_screen_shots(){

    }
    //pricing meta box
    function wpmp_meta_box_pricing($post){     

        include(dirname(__FILE__).'/tpls/metaboxes/pricing.php');

    }
    //icon meta box  
    function wpmp_meta_box_icon(){
        global $post;
        @extract(get_post_meta($post->ID,"wpmp_list_opts",true)); 
        $path = "wp-content/plugins/wpmarketplace/images/icons/";
        $scan = scandir( '../'.$path );
        $k = 0;
        foreach( $scan as $v )
        {
            if( $v=='.' or $v=='..' or is_dir('../'.$path.$v) ) continue;

            $fileinfo[$k]['file'] = 'wpmarketplace/images/icons/'.$v;
            $fileinfo[$k]['name'] = $v;
            $k++;
        }


        if( !empty($fileinfo) )
        {

            include dirname(__FILE__).'/libs/icon.php';

        } else {

        ?>
        <div class="updated" style="padding: 5px;">
        <?php echo __("upload your icons on '/wp-content/plugins/wpmarketplace/images/icons/' using ftp","wpmarketplace"); ?></div>

        <?php } ?>

    <?php
    }



    //pricing, icon, tax, stock metabox called from here
    function wpmp_meta_boxes(){
        $settings = maybe_unserialize(get_option('_wpmp_settings'));                                   
        $meta_boxes = array(
            'wpmp-info'=>array('title'=>__('Pricing & Discounts',"wpmarketplace"),'callback'=>'wpmp_meta_box_pricing','position'=>'normal','priority'=>'low'),
            'wpmp-icons'=>array('title'=>__('Icon',"wpmarketplace"),'callback'=>'wpmp_meta_box_icon','position'=>'side','priority'=>'core'),  
            'wpmp-tax-status'=>array('title'=>__('Tax',"wpmarketplace"),'callback'=>'wpmp_meta_box_tax','position'=>'side','priority'=>'core'),                            
            'wpmp-weight'=>array('title'=>__('Weight and Dimension',"wpmarketplace"),'callback'=>'wpmp_meta_box_weight','position'=>'side','priority'=>'core')                            
        );

        //check the settings to add stock metabox
        if(isset($settings['stock']['enable']) && $settings['stock']['enable']==1){
            $meta_boxes['wpmp-stock']=array('title'=>__('Stock',"wpmarketplace"),'callback'=>'wpmp_meta_box_stock','position'=>'side','priority'=>'core'); 
        }                   

        $meta_boxes = apply_filters("wpmp_meta_box", $meta_boxes);
        foreach($meta_boxes as $id=>$meta_box){
            extract($meta_box);
            add_meta_box($id, $title, $callback,'wpmarketplace', $position, $priority);
        }    
    }

    //weight metabox
    function wpmp_meta_box_weight(){
        global $post;
        @extract(get_post_meta($post->ID,"wpmp_list_opts",true));
    ?>
    <label ><?php echo __("Weight","wpmarketplace"); ?></label>: <input type="text" name="wpmp_list[weight]" value="<?php if(isset($weight)) echo $weight;?>"><br />
    <label ><?php echo __("Width","wpmarketplace"); ?></label>: <input type="text" name="wpmp_list[pwidth]" value="<?php if(isset($pwidth)) echo $pwidth;?>"><br />
    <label ><?php echo __("Height","wpmarketplace"); ?></label>: <input type="text" name="wpmp_list[pheight]" value="<?php if(isset($pheight)) echo $pheight;?>"><br />
    <?php
    }
    //tax metabox
    function wpmp_meta_box_tax(){
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        global $post;
        @extract(get_post_meta($post->ID,"wpmp_list_opts",true)); 
        //echo "<script>alert('$tax_status');</script>";
    ?>
    <label ><?php echo __("Tax Status","wpmarketplace"); ?></label> <select  id="mng_tax" name="wpmp_list[tax_status]">
        <option <?php if(isset($tax_status) && $tax_status=="taxable")echo 'selected="selected"';?> value="taxable"><?php echo __("Taxable","wpmarketplace");?></option>
        <option <?php if(isset($tax_status) && $tax_status=="shipping")echo 'selected="selected"';?> value="shipping"><?php echo __("Shipping only","wpmarketplace");?></option>
        <option <?php if(isset($tax_status) && $tax_status=="")echo 'selected="selected"';?> value=""><?php echo __("None","wpmarketplace");?></option>
    </select><br />
    <div id="">
        <label ><?php echo __("Tax Class","wpmarketplace"); ?></label>
        <?php
            $tax_classes= $settings['tax']['tax_class'];
            $textAr = explode("\n", $tax_classes);
        ?>
        <select name="wpmp_list[tax_class]"><?php echo $stock_qty;?>"
            <option value=""><?php echo __("Standard Rate","wpmarketplace");?></option>
            <?php
                foreach($textAr as $class){
                    if($tax_class==$class)$sele= 'selected=selected';else $sele="";

                    echo '<option value="'.$class.'" '.$sele.'>'.__($class,"wpmarketplace").'</option>';
                }  
            ?>
        </select>
    </div>
    <?php
    }
    //stock metabox
    function wpmp_meta_box_stock(){
        global $post;
        @extract(get_post_meta($post->ID,"wpmp_list_opts",true)); 
    ?>
    <label ><?php echo __("Manage Stock","wpmarketplace"); ?></label> <input <?php if($manage_stock==1)echo 'checked="checked"';?> type="checkbox" id="mng_stock" name="wpmp_list[manage_stock]" value="1"><br />
    <div id="stk_qty">
        <label ><?php echo __("Stock Quantity","wpmarketplace"); ?></label><input type="text" name="wpmp_list[stock_qty]" value="<?php echo $stock_qty;?>" size="20"> 
    </div>
    <?php
    }

    function wpmp_save_meta_data($postid, $post){               
        if(isset($_POST['wpmp_list'])){ 
            //echo "<pre>"; print_r($_POST['wpmp_list']); echo "</pre>"; die();
            update_post_meta($postid,"wpmp_list_opts",$_POST['wpmp_list']);  
            foreach($_POST['wpmp_list'] as $k=>$v){
                update_post_meta($postid,$k,$v);
            }
            //sending email if product is approved
            /*$user_info=get_userdata($post->post_author);
            if($user_info->roles[0]!="administrator"){
            //if product published
            if($post->post_status=="publish"){
            $siteurl=home_url("/");
            global $wpdb;                    
            $email = array();
            $subject="Product Approval Notification";
            $message="Your product {$post->post_title} is approved to {$siteurl}";
            $email['subject']=$subject;
            $email['body']=$message;
            $email = apply_filters("product_approval_email", $email);    
            wp_mail($user_info->user_email,$email['subject'],$email['body'],$email['headers']);        
            wp_mail($admin_email,$email['subject'],$email['body'],$email['headers']);
            }
            }*/
            //print_r($_POST);
            //exit;
            //exit;

        }
        if(isset($_POST['post_author'])) {
            $userinfo=get_userdata($_POST['post_author']);

            if($userinfo->roles[0]!="administrator"){
                if($_POST['original_post_status']=="draft" && $_POST['post_status']=="publish"){
                    global $current_user; 
                    $siteurl=home_url("/");
                    $admin_email=get_bloginfo("admin_email");
                    $to= $userinfo->user_email; //post author
                    $from= $current_user->user_email;
                    $link=get_permalink($post->ID);
                    $message="Your product {$post->post_title} {$link} is approved to {$siteurl} ";
                    $email['subject']=$subject;
                    $email['body']=$message;
                    $email['headers'] = 'From:  <'.$from.'>' . "\r\n";
                    $email = apply_filters("product_approval_email", $email);            
                    wp_mail($to,$email['subject'],$email['body'],$email['headers']);
                    //wp_mail($admin_email,$email['subject'],$email['body'],$email['headers']);
                }
            }
        }
    }

    //marketplace settings
    function wpmp_settings(){ 
        include("settings/settings.php");
    }
    //orders list section
    function wpmp_orders(){ 
        $order1 = new Order();
        global $wpdb;
        $wpdb->show_errors();
        $l = 15;
        $currency_sign = get_option('_wpmp_curr_sign','$');
        //if(isset($_GET['paged'])) {
        $p = $_GET['paged']?$_GET['paged']:1;
        $s = ($p-1)*$l;
        //}
//        echo "<pre>";
//        print_r($_REQUEST);
//        echo "</pre>";
        if(isset($_GET['task']) && $_GET['task']=='vieworder'){
            $order = $order1->getOrder($_GET['id']);
            include('tpls/view-order.php');        
        }
        
        else {
            if(isset($_GET['task']) && $_GET['task']=='delete_order'){
                $order_id  = esc_attr($_GET['id']);
                $ret = $wpdb->query( 
                $wpdb->prepare( 
                        "
                        DELETE FROM {$wpdb->prefix}mp_orders
                         WHERE order_id = %s
                        ",
                        $order_id 
                            )
                    );
                if($ret){
                    //echo $ret;
                    $ret = $wpdb->query(
                    $wpdb->prepare( 
                        "
                        DELETE FROM {$wpdb->prefix}mp_order_items
                         WHERE oid = %s
                        ",
                        $order_id 
                            )
                    );
                   //echo $ret;
                   if($ret) $msg = "Record Deleted for Order ID $order_id...";     
                }        
                
            }
            else if(isset($_GET['delete_selected'],$_GET['delete_confirm']) && $_GET['delete_confirm']==1 ){
                $order_ids = $_GET['id'];
                if(!empty($order_ids) && is_array($order_ids)){
                    foreach($order_ids as $key => $order_id){
                        $order_id  = esc_attr($order_id);
                        $ret = $wpdb->query( 
                        $wpdb->prepare( 
                                "
                                DELETE FROM {$wpdb->prefix}mp_orders
                                 WHERE order_id = %s
                                ",
                                $order_id 
                                    )
                            );
                        if($ret){
                            //echo $ret;
                            $ret = $wpdb->query(
                            $wpdb->prepare( 
                                "
                                DELETE FROM {$wpdb->prefix}mp_order_items
                                 WHERE oid = %s
                                ",
                                $order_id 
                                    )
                            );
                           //echo $ret;
                           if($ret) $msg[] = "Record Deleted for Order ID $order_id...";     
                        }
                    }
                }
            }
            else if(isset($_GET['delete_by_payment_sts'],$_GET['delete_all_by_payment_sts']) && $_GET['delete_all_by_payment_sts']!= "" ){
                $payment_status = esc_attr($_GET['delete_all_by_payment_sts']);
                
                $order_ids = $wpdb->get_results( 
                                "
                                SELECT order_id 
                                FROM {$wpdb->prefix}mp_orders
                                WHERE payment_status = '$payment_status'
                                "
                        ,ARRAY_A);
                if($order_ids){
                    foreach($order_ids as $row){
                        print_r($row);
                        $order_id  = $row['order_id'];
                        $ret = $wpdb->query( 
                        $wpdb->prepare( 
                                "
                                DELETE FROM {$wpdb->prefix}mp_orders
                                 WHERE order_id = %s
                                ",
                                $order_id 
                                    )
                            );
                        if($ret){
                            //echo $ret;
                            $ret = $wpdb->query(
                            $wpdb->prepare( 
                                "
                                DELETE FROM {$wpdb->prefix}mp_order_items
                                 WHERE oid = %s
                                ",
                                $order_id 
                                    )
                            );
                           //echo $ret;
                           if($ret) $msg[] = "Record Deleted for Order ID $order_id...";     
                        }
                    }
                }
                
                
            }
            
            
            //$wpdb->print_error();
            if(isset($_REQUEST['oid']) && $_REQUEST['oid'])    
                $qry[] = "order_id='$_REQUEST[oid]'" ;   
            if(isset($_REQUEST['ost']) && $_REQUEST['ost'])    
                $qry[] = "order_status='$_REQUEST[ost]'" ;   
            if(isset($_REQUEST['pst']) && $_REQUEST['pst'])
                $qry[] = "payment_status='$_REQUEST[pst]'";    
            if(isset($_REQUEST['sdate'],$_REQUEST['edate']) && ($_REQUEST['sdate']!=''||$_REQUEST['edate']!='')){
                $_REQUEST['edate'] = $_REQUEST['edate']?$_REQUEST['edate']:$_REQUEST['sdate'];
                $_REQUEST['sdate'] = $_REQUEST['sdate']?$_REQUEST['sdate']:$_REQUEST['edate'];
                $sdate = strtotime("$_REQUEST[sdate] 00:00:00");
                $edate = strtotime("$_REQUEST[edate] 23:59:59");
                $qry[] = "(`date` >=$sdate and `date` <=$edate)";
            }

            if(isset($qry))
                $qry = "where ".implode(" and ", $qry);
            else $qry = "";
            $t = $order1->totalOrders($qry); 
            $orders = $order1->GetAllOrders($qry,$s, $l);
            include('tpls/orders.php');    
        }
    }
    //fronend orders list 
    function wpmp_myorders($content){
        global $current_user, $_ohtml;
        get_currentuserinfo();
        $order = new Order();         
        $myorders = $order->GetOrders($current_user->ID);
        $_ohtml = '';        
        include('tpls/orders_purchases.php');
        $content = str_replace('[my-orders]',$_ohtml, $content);
        return $content;

    }

    //frontend user profile
    function wpmp_user_order(){ 
        global $current_user, $_ohtml;
        get_currentuserinfo();
        $order = new Order();         
        $myorders = $order->GetOrders($current_user->ID);
        $_ohtml = '';  
        $dashboard = true;
        include('tpls/orders_purchases.php');
        return $_ohtml;
    }


    function wpmp_set_post_type( $query ) {  
        if(!is_admin()){
            if(!is_page())
                $query->set( 'post_type', array('post','wpmarketplace'));         
            else
                $query->set( 'post_type', array('post','wpmarketplace','page'));         
        }
        return $query;
    } 

    function wpmp_tabs($attrs,$content){    
        $tabs = explode("|",$attrs['tabs']);
        $html = "<div class='wpmp-tab-container'><ul class='tabs'>";
        foreach($tabs as $tab){
            ++$tn;
            $html .= "<li><a href='#tab{$tn}'>{$tab}</a></li>\n";
        }
        $html .= "</ul>";
        $html .= '<div class="tab_container">';
        $tab_cons = explode("######",$content);
        foreach($tab_cons as $con){
            ++$tc ;  
            $html .= '<div id="tab'.$tc.'" class="tab_content">'.__($con,"wpmarketplace").'</div>';
        }
        $html .= '</div></div>';
        return $html;  
    }

    function wpmp_themes(){
        $process = curl_init('http://wpmarketplaceplugin.com/themes/?clean=1');
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)'; 
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent);    
        curl_setopt($process, CURLOPT_TIMEOUT, 30);    
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        echo curl_exec($process);
        curl_close($process);         
    }

    function wpmp_addons(){
        $process = curl_init('http://wpmarketplaceplugin.com/add-ons/?clean=1');
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)'; 
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent);    
        curl_setopt($process, CURLOPT_TIMEOUT, 30);    
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        echo curl_exec($process);
        curl_close($process);         
    }

    //menus for the marketplace
    function wpmp_menu(){     
        add_submenu_page( 'edit.php?post_type=wpmarketplace', __('Featured Products',"wpmarketplace"), __('Featured Products',"wpmarketplace"), 'level_2', 'featured', 'wpmp_featured_product');    
        add_submenu_page( 'edit.php?post_type=wpmarketplace', __('Payouts',"wpmarketplace"), __('Payouts',"wpmarketplace"), 'level_2', 'payouts', 'wpmp_all_payouts');    
        add_submenu_page( 'edit.php?post_type=wpmarketplace', __('Orders &lsaquo; Marketplace',"wpmarketplace"), __('Orders',"wpmarketplace"), 'level_2', 'orders', 'wpmp_orders');    
        add_submenu_page( 'edit.php?post_type=wpmarketplace', __('Settings &lsaquo; Marketplace',"wpmarketplace"), __('Settings',"wpmarketplace"), 'level_2', 'settings', 'wpmp_settings');    
        add_submenu_page( 'edit.php?post_type=wpmarketplace', __('Themes &lsaquo; Marketplace',"wpmarketplace"), __('Themes',"wpmarketplace"), 'level_2', 'themes', 'wpmp_themes');    
        add_submenu_page( 'edit.php?post_type=wpmarketplace', __('Add Ons &lsaquo; Marketplace',"wpmarketplace"), __('Add Ons',"wpmarketplace"), 'level_2', 'add-ons', 'wpmp_addons');

        $hook = add_submenu_page( 'edit.php?post_type=wpmarketplace', __('Product Report',"wpmarketplace"), "", 'level_2', 'product-report', create_function('', 'require_once WPMP_BASE_DIR . "libs/product_report.php";'));    
        add_action( 'admin_print_scripts-' . $hook, 'wpmp_product_report_scripts' );
        add_action('admin_print_styles-'.$hook,'wpmp_product_report_styles');

    }
    //payouts section
    function wpmp_all_payouts(){
        include_once("tpls/payouts.php"); 
    }
    //featured products
    function wpmp_featured_product(){
        global $wpdb;
        if(isset($_POST['task']) && $_POST['task']=="add_feature"){
            //print_r($_POST);
            foreach($_POST['fids'] as $fpid){
                $wpdb->insert( 
                    "{$wpdb->prefix}mp_feature_products", 
                    array( 
                        'productid' => $fpid, 
                        'startdate' => strtotime($_POST['sdate']) ,
                        'enddate' => strtotime($_POST['edate']) 
                    ), 
                    array( 
                        '%d', 
                        '%d', 
                        '%d' 
                    ) 
                );
            }
        }
        $featured_products=$wpdb->get_results("select * from {$wpdb->prefix}mp_feature_products fp inner join {$wpdb->prefix}posts p on p.ID=fp.productid where p.post_type='wpmarketplace' ");
        include_once('tpls/featured_products.php'); 
    }
    //admin settings options save
    function wpmp_save_settings(){
        /*
        $data = json_encode($_POST['_wpmp_settings']);
        $str = <<< EOD
        <script> console.log($data)</script>   
        EOD;
        echo $str;
        */
        /*
        $array = array(0 => 'blue', 1 => 'red', 2 => 'green', 3 => 'red');
        $key = array_search('green', $array); // $key = 2;
        $key = array_search('red', $array);

        */

        //remove wpmarketplace capabilities
        global $wp_roles; // global class wp-includes/capabilities.php
        $cap = 'wpmarketplace_user';
        $roles = $wp_roles->get_names(); // administrator => Adminis...
        foreach($roles as $key => $value){
            $wp_roles->remove_cap( $key,  $cap);
        }
        //now add roles
        $user_role = $_POST['_wpmp_settings']['user_role'];
        if(!empty($user_role)):
            foreach($user_role as $key => $value){
                $role = get_role( $value );
                $role->add_cap( $cap ); 
            }
            endif;
        //$_POST['']

        update_option('_wpmp_settings',$_POST['_wpmp_settings']);  
        //
        die(__('Settings Saved Successfully',"wpmarketplace"));
    }

    function wpmp_download(){    
        if(!isset($_GET['wpmpfile'])) return;
        global $wpdb, $current_user;
        get_currentuserinfo();
        $order = new Order();
        $odata = $order->GetOrder($_GET['oid']);
        $items = unserialize($odata->items);    
        $meta = get_post_meta($_GET['wpmpfile'],"wpmp_list_opts",true);

        @extract($meta);

        $post = get_post($_GET['wpmpfile']);

        if($base_price==0&&(int)$_GET['wpmpfile']>0){      
            //product jodi free hoi...
            include("libs/process.php");
        }
        if(@in_array($_GET['wpmpfile'],$items)&&$_GET['oid']!=''&&is_user_logged_in()&&$current_user->ID==$odata->uid){   
            //product jodi non free hoi
            @extract(get_post_meta($_GET['wpmpfile'],"wpmp_list_opts",true));
            include("libs/process.php");
        }
    }
    //logging in the user from frontend
    function wpmp_do_login(){
        if((isset($_REQUEST['checkout_login']) && $_REQUEST['checkout_login']=="login") || (isset($_POST['login_form']) && $_POST['login_form']=="login")){
            global $wp_query, $post, $sap;      
            if(!$_POST['login']) return;
            unset($_SESSION['login_error']);
            the_post();
            $creds = array();
            $creds['user_login'] = $_POST['login']['log'];
            $creds['user_password'] = $_POST['login']['pwd'];
            $creds['remember'] = $_POST['rememberme'];
            $user = wp_signon( $creds, false );
            if ( is_wp_error($user) ){                
                $_SESSION['login_error'] = $user->get_error_message();
                //header("location: ".$_POST['permalink'].$sap.'task=login');
                if($_REQUEST['login_form']=="login") header("location: ".$_POST['permalink']);
                die("failed");
            } else {
                //header("location: ".$_POST['permalink']); 
                if($_REQUEST['login_form']=="login") header("location: ".$_POST['permalink']);
                echo 'success';
                die();
            }
        }
    }
    //registering from the frontend
    function wpmp_do_register(){
        if((isset($_REQUEST['checkout_register']) && $_REQUEST['checkout_register']=="register") || (isset($_POST['register_form']) && $_POST['register_form']=="register")){           
            global $wp_query, $sap;
            if(!$_POST['reg'])  die("error");;
            extract($_POST['reg']);
              
            $_SESSION['tmp_reg_info'] = $_POST['reg'];    
            $user_id = username_exists( $user_login );
            if($user_login==''){
                $_SESSION['reg_error'] =  __('Username is Empty!');                     
                die($_SESSION['reg_error']);
            }
            if($user_email==''||!is_email($user_email)){
                $_SESSION['reg_error'] =  __('Invalid Email Address!');        
                die($_SESSION['reg_error']);
            }
            if ( !$user_id ) {
                $user_id = email_exists( $user_email );
                if ( !$user_id ) {
                    //$user_pass = wp_generate_password( 12, false );
                    //echo $user_pass;
                    $user_id = wp_create_user( $user_login, $user_pass, $user_email );
                    $email = get_option('admin_email');
                    $headers = "From: ".get_bloginfo('sitename')." <$email>\r\nContent-type: text/html";
                    $message = "Hello $user_login,<br/>\r\nThanks for registering to ".get_bloginfo('sitename')."<br/>Here is your login info:<br/>\r\nUsername: $user_login<br/>\r\nPassword: $user_pass<br/>\r\n<br/>\r\nThanks<br/><b>".get_bloginfo('sitename')."</b>";
                    //echo $user_id;
                    if($user_id){
                        wp_mail($user_email,"Welcome to ".get_bloginfo('sitename'),$message,$headers);
                        unset($_SESSION['tmp_reg_info']);
                        unset($_SESSION['login_error']);
                        $creds = array();
                        $creds['user_login'] = $user_login;
                        $creds['user_password'] = $user_pass;
                        $creds['remember'] = "forever";
                        $user = wp_signon( $creds, false );
                        //echo $user->get_error_message();exit;
                        if ( is_wp_error($user) ){                
                            $_SESSION['login_error'] = $user->get_error_message();                
                            //if(isset($_REQUEST['wpmpnrd'])) 
                                die("failed");
                            //else
                            //    header("location: ".$_POST['permalink']); 
                        } else {                              
                           // if(isset($_REQUEST['wpmpnrd'])) 
                                die("success");
                            //else
                            //    header("location: ".$_POST['permalink']); 
                        }

                    }
                    //header("location: ".$_POST['permalink'].$sap.'task=login'); 
                    die();
                } else {
                    $_SESSION['reg_error'] =  __('Email already exists.');        
                    //header("location: ".$_POST['permalink'].$sap.'task=register');
                    die($_SESSION['reg_error']);
                }
            } else {
                $_SESSION['reg_error'] =  __('User already exists.');        
                //header("location: ".$_POST['permalink'].$sap.'task=register');
                die($_SESSION['reg_error']);
            }

        }
    }
    //saving billing info from checkout process 
    function wpmp_save_billing_info(){
        if(isset($_REQUEST['checkout_billing']) && $_REQUEST['checkout_billing']=="save"){
            global $current_user;
            get_currentuserinfo();
            $order = new Order();
            if($_SESSION['orderid']){
                $order_info=$order->GetOrder($_SESSION['orderid']);
                if($order_info->order_id){
                    $data=array(
                        'billing_shipping_data'=>serialize($_POST['checkout']),        
                        'cart_data'=>serialize(wpmp_get_cart_data()),        
                        'items'=>serialize(array_keys(wpmp_get_cart_data()))        
                    ); 
                    $order->UpdateOrderItems(wpmp_get_cart_data(),$_SESSION['orderid']); 
                    $insertid = $order->Update($data, $_SESSION['orderid']);
                }else{
                    $cart_data = serialize(wpmp_get_cart_data());
                    $items=serialize(array_keys(wpmp_get_cart_data()));
                    //print_r($cart_data);
                    $insertid=$order->NewOrder($_SESSION['orderid'], "", $items, 0,$current_user->ID,'Processing','Processing',$cart_data,"","","",0.0,serialize($_POST['checkout']));
                    $order->UpdateOrderItems($cart_data,$_SESSION['orderid']);
                }
            }else{
                $cart_data = serialize(wpmp_get_cart_data());
                $items=serialize(array_keys(wpmp_get_cart_data()));
                $insertid=$order->NewOrder(uniqid(), "", $items, 0,$current_user->ID,'Processing','Processing',$cart_data,"","","",0.0,serialize($_POST['checkout']));            
                $_SESSION['orderid']=$insertid;
                $order->UpdateOrderItems($cart_data,$_SESSION['orderid']); 
            }
            update_user_meta($current_user->ID, 'user_billing_shipping', serialize($_POST['checkout']));        
            include_once("tpls/shipping_method.php");
            die();
        }
    }
    //saving shipping info from checkout process 
    function wpmp_save_shipping_info(){
        if(isset($_REQUEST['checkout_shipping']) && $_REQUEST['checkout_shipping']=="save"){
            global $current_user;
            get_currentuserinfo();
            $data=array(
                'shipping_method'=>$_POST['shipping_method'],
                'shipping_cost'=>$_POST['shipping_rate']
            );
            $order = new Order();
            $od = $order->Update($data, $_SESSION['orderid']);
            $order_total= $order->CalcOrderTotal($_SESSION['orderid']);
            $shipping = wpmp_calculate_shipping();
            $order_total = $order_total + $shipping['cost'];
            if($order_total>0)
                include_once("tpls/payment_method.php");
            else {
                $order_info=$order->GetOrder($_SESSION['orderid']);
                include_once("tpls/order_review.php");
            }
            die();
        }
    }
    //saving payment method info from checkout process 
    function wpmp_save_payment_method_info(){
        if(isset($_REQUEST['checkout_payment']) && $_REQUEST['checkout_payment']=="save"){
            global $current_user;
            get_currentuserinfo();

            $data=array(
                'payment_method'=>$_POST['payment_method']        
            );
            $order = new Order();
            $od=$order->Update($data, $_SESSION['orderid']);
            $order_info=$order->GetOrder($_SESSION['orderid']);
            include_once("tpls/order_review.php"); 
            die();
        }
    } 
    //placing order from checkout process 
    function wpmp_place_order(){
        if(isset($_REQUEST['wpmpaction']) && $_REQUEST['wpmpaction']=='placeorder'){
            //save 

            $order = new Order();          
            $order_total= $order->CalcOrderTotal($_SESSION['orderid']);
            $tax=wpmp_calculate_tax();

            $data=array(
                'total'=>$order_total,
                'order_notes'=>$_POST['order_comments'],
                'cart_discount' => $_POST['cart_discount']
            );
            $od = $order->Update($data, $_SESSION['orderid']);
            //update order items
            $order->UpdateOrderItems(serialize($_POST['cart_items']), $_SESSION['orderid']);
            // If order total is not 0 then go to payment gateway
            if($order_total>0){
                $payment = new Payment();
                $payment->InitiateProcessor($_POST['payment_system']);
                $payment->Processor->OrderTitle = 'WPMP Order# '.$_SESSION['orderid'];
                $payment->Processor->InvoiceNo = $_SESSION['orderid'];
                $payment->Processor->Custom = $_SESSION['orderid'];
                $payment->Processor->Amount = $order_total;
                echo $payment->Processor->ShowPaymentForm(1);
            } else {

                // if order total is 0 then complete order immediately
                order::complete_order($_SESSION['orderid']);
                wpmp_js_redirect(wpmp_orders_page());
            }
            wpmp_empty_cart(); 
            die();
        }
    }
    //payment notification process
    function wpmp_payment_notification(){
        if(isset($_REQUEST['action']) && $_REQUEST['action']=="wpmp-payment-notification"){
            //include_once(WP_PLUGIN_DIR."/wpmarketplace/libs/payment_methods/".$_REQUEST['class']."/class.".$_REQUEST['class'].".php");
            $payment_method=new $_REQUEST['class']();
            if($payment_method->VerifyNotification()){
                global $wpdb;              
                order::complete_order($payment_method->order_id);         
            }

        }
    }

    //withdraw money from paypal noti
    function wpmp_withdraw_paypal_notification(){
        if(isset($_REQUEST['action']) && $_REQUEST['action']=="withdraw_paypal_notification"){

            if(isset($_POST["txn_id"]) && isset($_POST["txn_type"])&& $_POST["status"]=="Completed"){
                global $wpdb;
                $wpdb->update( 
                    "{$wpdb->prefix}mp_withdraws", 
                    array(
                        'status' => 1
                    ), 
                    array('id'=>$_POST['custom']),
                    array(
                        '%d'
                    ),
                    array( '%d' )  
                );
            }
        }
    }
    //payment using ajax
    function wpmp_ajax_payfront(){
        if(isset($_POST['task'],$_POST['action']) && $_POST['task']=="paymentfront" && $_POST['action']=="wpmp_ajax_call"){
            $data['order_id']=$_POST['order_id'];
            $data['payment_method']=$_POST['payment_method'];

            PayNow($data);
            die();
        }
    }

    function wpmp_ajax_call(){
        if(function_exists($_POST['execute'])){
            echo call_user_func($_POST['execute']);
            die();
        }
    }
    //function for adding product using shortcode
    function wpmp_front_add_product(){
        if(current_user_can('wpmarketplace_user')):
            include("libs/scode_add-product.php");
            endif;
    }

    //function for adding product using shortcode
    function wpmp_front_product_list(){
        if(current_user_can('wpmarketplace_user')):
            include("libs/scode_my-products.php");
            endif;
    }

    //function for earnings using shortcode
    function wpmp_earnings(){
        if(current_user_can('wpmarketplace_user')):
            include("libs/scode_earnings.php");
            endif;
    }

    //function for members tabs
    function wpmp_frontend(){
        include("tpls/wpmp_frontend.php");
    }
    function wpmp_plu_admin_enqueue() {
        wp_enqueue_script('plupload-all'); 
    }

    function plupload_admin_head() {
        // place js config array for plupload
        $plupload_init = array(
            'runtimes' => 'html5,silverlight,flash,html4',
            'browse_button' => 'plupload-browse-button', // will be adjusted per uploader
            'container' => 'plupload-upload-ui', // will be adjusted per uploader
            'drop_element' => 'drag-drop-area', // will be adjusted per uploader
            'file_data_name' => 'async-upload', // will be adjusted per uploader
            'multiple_queues' => true,
            'max_file_size' => wp_max_upload_size() . 'b',
            'url' => admin_url('admin-ajax.php'),
            'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
            'filters' => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
            'multipart' => true,
            'urlstream_upload' => true,
            'multi_selection' => false, // will be added per uploader
            // additional post data to send to our ajax hook
            'multipart_params' => array(
                '_ajax_nonce' => "", // will be added per uploader
                'action' => 'plupload_action', // the ajax action name
                'imgid' => 0 // will be added per uploader
            )
        );
    ?>
    <script type="text/javascript">
        var base_plupload_config=<?php echo json_encode($plupload_init); ?>;
        var pluginurl = "<?php echo plugins_url("wpmarketplace/"); ?>";
        var wpmp_image_url = "<?php echo WPMP_IMAGE_URL; ?>";
    </script>
    <?php
    }
    function g_plupload_action() {

        // check ajax noonce
        $imgid = $_POST["imgid"];
        check_ajax_referer($imgid . 'pluploadan');

        // handle file upload
        $status = wp_handle_upload($_FILES[$imgid . 'async-upload'], array('test_form' => true, 'action' => 'plupload_action'));

        // send the uploaded file url in response
        echo $status['url'];
        exit;
    }


    function wpmp_move_upload_previewfile(){
        $adpdir = WPMP_IMAGE_DIR;
        $uploads = wp_upload_dir();
        $tempFile=$uploads['basedir'].str_replace("uploads","",strstr($_POST['fileurl'],"uploads"));
        $filename=basename($_POST['fileurl']);
        $fname="wpmp-adp-". time().'-'.$filename;
        $targetFile =  $adpdir.$fname;
        rename($tempFile, $targetFile);
        die($fname);
    }

    function wpmp_move_upload_productfile(){
        $adpdir = WPMP_UPLOAD_DIR;
        $uploads = wp_upload_dir();
        $tempFile=$uploads['basedir'].str_replace("uploads","",strstr($_POST['fileurl'],"uploads"));
        $filename=basename($_POST['fileurl']);
        $fname="wpmp-p-". time().'-'.$filename;
        $targetFile =  $adpdir.$fname;
        rename($tempFile, $targetFile);
        die($fname);
    }

    function wpmp_edit_profile(){
        include(dirname(__FILE__).'/tpls/edit-profile.php');
    }
    function wpmp_my_orders(){

    }

    function wpmp_move_upload_featuredfile(){

        die($_POST['fileurl']);
    }

    function wpmp_update_profile(){
        global $current_user;
        if(!is_user_logged_in()||!isset($_POST['profile'])) return;

        $userdata = $_POST['profile'];
        $userdata['ID'] = $current_user->ID;
        if($_POST['password']==$_POST['cpassword']){
            wp_update_user($userdata);
            $userdata['user_pass'] = $_POST['password'];
            update_user_meta($current_user->ID, 'payment_account',$_POST['payment_account']);
            update_user_meta($current_user->ID, 'phone',$_POST['phone']);
            $_SESSION['member_success'] = __("Profile Updated Successfully","wpmarketplace");

        } else {
            $_SESSION['member_error'][] = __("Confirm Password Not Matched. Profile Update Failed!","wpmarketplace");
        }
        update_user_meta($current_user->ID, 'user_billing_shipping', serialize($_POST['checkout']));

        wpmp_redirect($_SERVER['HTTP_REFERER']);
        die();

    }

    //auto sugession function
    function wpmp_autosuggest(){
        if($_REQUEST['tag']){
            global $wpdb;
            $featured_products=$wpdb->get_results("select * from  {$wpdb->prefix}posts p  where p.post_type='wpmarketplace' and p.post_title like '%{$_REQUEST['tag']}%' ");

            $rtn="[";
            foreach($featured_products as $value){
                $fp[] = array('key'=>$value->ID, 'value'=>$value->post_title);
            }

            echo json_encode($fp);
            die(); 
        } 
    }

    function wpmp_remove_featured(){
        if($_POST['id']){
            global $wpdb;
            $wpdb->query("delete from {$wpdb->prefix}mp_feature_products where id='{$_POST['id']}'");
            die();
        }
    }

    //default currency saving function
    function wpmp_default_currency(){
        update_option('_wpmp_curr_key',$_POST['currency_key']);  
        update_option('_wpmp_curr_name',$_POST['currency_name']);  
        update_option('_wpmp_curr_sign',$_POST['currency_value']);  
        die("success");    
    }

    //default currency delete
    function wpmp_default_currency_del(){
        $cur_key = get_option('_wpmp_curr_key');
        if($cur_key == $_POST['currency_key']){
            delete_option('_wpmp_curr_key');
            delete_option('_wpmp_curr_name');
            delete_option('_wpmp_curr_sign');
        }
    }

    function wpmp_enqueue_scripts(){
        global $wpmp_plugin;
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-form');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker'); 
        wp_enqueue_script('jquery-ui-accordion');

        $settings = get_option('_wpmp_settings');    
        if(is_admin()||!isset($settings['disable_fron_end_css']))
            $wpmp_plugin->load_styles();
        $wpmp_plugin->load_scripts();


    }

    function wpmp_init(){
        add_theme_support('post-thumbnails');
    }

    function add_zip_profile_fields( $user ) {
        // add extra zip fields to user edit page

    ?>

    <table class="form-table">
        <tr><th>Zip/Postal Code</th>
            <td>

                <?php
                    $user_zip = get_user_meta($user->ID,"user_zip",true);


                ?>
                <input type="text" name="user_zip" value="<?php echo $user_zip;?>">
            </td>
        </tr>
    </table>
    <?php
    }

    function save_userzip_data($user_id, $old_user_data){
        update_user_meta($user_id, 'user_zip', $_POST['user_zip']);
    }


    register_activation_hook(__FILE__,'wpmp_install');
    $wpmp_plugin->load_modules();  






