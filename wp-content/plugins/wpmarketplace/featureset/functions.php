<?php

if(is_admin()){
    add_action('admin_init', 'wpmp_featureset_meta_boxes', 0);
    add_action("admin_menu", "wpmp_featureset_menu");
}

function wpmp_featureset_menu(){
    $hook2 = add_submenu_page( 'edit.php?post_type=wpmarketplace', __('Feature List',"wpmarketplace"), __('Feature Set',"wpmarketplace"), 'level_2', 'feature-set', create_function('', 'require_once WPMP_BASE_DIR . "featureset/feature_set.php";'));    
    add_action( 'admin_print_scripts-' . $hook2, 'wpmp_featureset_scripts' );
    add_action('admin_print_styles-'.$hook2,'wpmp_featureset_styles');
}

function wpmp_featureset_meta_boxes(){
    $meta_boxes = array(
                    'wpmp-featureset'=>array('title'=>__('Feature Set',"wpmarketplace"),'callback'=>'wpmp_meta_box_featureset','position'=>'normal','priority'=>'low'),
                   );
    $meta_boxes = apply_filters("wpmp_meta_box", $meta_boxes);
    foreach($meta_boxes as $id=>$meta_box){
        extract($meta_box);
        add_meta_box($id, $title, $callback,'wpmarketplace', $position, $priority);
    } 
}

function wpmp_meta_box_featureset($post){
    wp_nonce_field( 'wpmp_meta_box_featureset', 'wpmp_meta_box_featureset_nonce' );
    require_once WPMP_BASE_DIR . 'featureset/metabox.php';
}

//save metabox data
add_action( 'save_post', 'wpmp_meta_box_featureset_save' );
function wpmp_meta_box_featureset_save($post_id){
    // Check if our nonce is set.
    if ( ! isset( $_POST['wpmp_meta_box_featureset_nonce'] ) )
        return $post_id;
    
    $nonce = $_POST['wpmp_meta_box_featureset_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'wpmp_meta_box_featureset' ) )
      return $post_id;
  
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return $post_id;
    
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    
    $data = serialize($_POST['wpmp_featureset']);
    update_post_meta( $post_id, '_wpmp_featureset', $data );
    update_post_meta($post_id, '_wpmp_featureset_id', $_POST['featureSetId']);

}

function wpmp_featureset_scripts(){
     wp_enqueue_script('thickbox');
     wp_enqueue_script(
		'feature-set-js',
		WP_PLUGIN_URL . '/wpmarketplace/featureset/custom.js',
		array( 'jquery' )
	);
     wp_enqueue_script('jquery-ui-sortable');
 }
 
 function wpmp_featureset_styles(){
     wp_enqueue_style('thickbox');
 }
 
//ajax work
add_action("wp_ajax_wpmp_featurelist_generate", "wpmp_featurelist_generate");
add_action("wp_ajax_nopriv_wpmp_featurelist_generate", "wpmp_featurelist_generate");

function wpmp_featurelist_generate(){
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "wpmp_featurelist_nonce")) {
      exit("No naughty business please");
    }   
   
    $result['type'] = "failed";
    global $wpdb;
   
    $id = $_REQUEST['id'];
    $table = $wpdb->prefix . "mp_feature_set";
    $table2 = $wpdb->prefix . "mp_feature_set_meta";
    
    $row = $wpdb->get_row("select * from {$table} where id='$id'",ARRAY_A);
    $result['feature_name'] = $row['feature_name'];
    
    $results = $wpdb->get_results("SELECT * FROM {$table2} where fid='{$id}' and del='0' and enabled='1'",ARRAY_A);
    $wpdb->flush();
    if(!empty($results)) $result['type']="success";
    foreach ($results as $key => $row){
        $field_type = $row['field_type'];
        $option_name = $row['option_name'];
        $id = $row['id'];
        $option_value = unserialize($row['option_value']);
        $result['data'][$id] = array();
        $result['data'][$id]['id'] = $id;
        $result['data'][$id]['name'] = $option_name;
        $result['data'][$id]['field_type'] = $field_type;
        $opt_value = explode("\r\n", $option_value[0]);  
        $result['data'][$id]['data'] = $opt_value;
        
    }
    
    
    
    
    
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
       $result = json_encode($result);
       echo $result;
    }
    else {
       header("Location: ".$_SERVER["HTTP_REFERER"]);
    }

    die();
}
 
function featurelist_frontend($post_id){
    global $wpdb;
    $table = $wpdb->prefix . "mp_feature_set";
    $table2 = $wpdb->prefix . "mp_feature_set_meta";

    $data1 = get_post_meta($post_id, "_wpmp_featureset", true);
    $data = unserialize($data1);

    if (!empty($data)) {
        $abc = array_keys($data);
        $newid = $abc[0];
        $feature_name = $wpdb->get_var("select feature_name from $table where id='$newid'");
    }
    
    if(isset($data) && is_array($data) && !empty($data)):
        //<h2>$feature_name</h2>
        $str = "<ul class='' id=''>";
        foreach($data as $x => $y){
            
            foreach($y as $id => $arr):
                $option_name = $wpdb->get_var("select option_name from $table2 where id='$id'");
                $str .='<li style="list-style:none"><b>'.$option_name. '</b><ul style="list-style:none;margin:0;">';
                foreach ($arr as $a => $b){
                    $str .= "<li style='margin-left:0;list-style:none'><i class='icon icon-ok'></i> $b</li>";
                }
                $str .= '</ul><br/></li>';
            endforeach;
        }
        $str .= '</ul>';
    endif;
    return $str;
} 
 
 
 
 
 
?>
