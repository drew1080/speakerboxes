<?php 

/* Meta boxes */

function nimble_settings(){
	add_meta_box("et_post_meta", "ET Settings", "nimble_display_options", "post", "normal", "high");
	add_meta_box("et_post_meta", "ET Settings", "nimble_display_options", "page", "normal", "high");
}
add_action("admin_init", "nimble_settings");

function nimble_display_options($callback_args) {
	global $post, $themename;
	
	$post_type = $callback_args->post_type;
	
	$temp_array = array();

	$temp_array = maybe_unserialize( get_post_meta( get_the_ID(), '_et_nimble_settings', true ) );
	
	$et_is_featured = isset( $temp_array['et_is_featured'] ) ? (bool) $temp_array['et_is_featured'] : false;
	$et_fs_title = isset( $temp_array['et_fs_title'] ) ? $temp_array['et_fs_title'] : '';
	$et_fs_link = isset( $temp_array['et_fs_link'] ) ? $temp_array['et_fs_link'] : '';
	$et_fs_description = isset( $temp_array['et_fs_description'] ) ? $temp_array['et_fs_description'] : '';
	
	wp_nonce_field( basename( __FILE__ ), 'et_settings_nonce' );
?>
	<div id="et_custom_settings" style="margin: 13px 0 17px 4px;">
		<label class="selectit" for="et_is_featured" style="font-weight: bold;">
			<input type="checkbox" name="et_is_featured" id="et_is_featured" value=""<?php checked( $et_is_featured ); ?> /> <?php echo esc_html( sprintf( __('This %s is Featured', $themename), $post_type ) ); ?></label><br/>
		
		<div id="et_settings_featured_options" style="margin-top: 12px;">		
			
			<div class="et_fs_setting" style="display: none; margin: 13px 0 26px 4px;">
				<label for="et_fs_title" style="color: #000; font-weight: bold;"> <?php esc_html_e('Custom Title:',$themename); ?> </label>
				<input type="text" style="width: 30em;" value="<?php echo esc_attr($et_fs_title); ?>" id="et_fs_title" name="et_fs_title" size="67" />
				<br />
				<small style="position: relative; top: 8px;">ex: <code><?php echo htmlspecialchars("Simple <span>&amp;</span> Flexible");?></code></small>
			</div>
			
			<div class="et_fs_setting" style="display: none; margin: 13px 0 26px 4px;">
				<label for="et_fs_description" style="color: #000; font-weight: bold;"> <?php esc_html_e('Description Text:',$themename); ?> </label>
				<input type="text" style="width: 30em;" value="<?php echo esc_attr($et_fs_description); ?>" id="et_fs_description" name="et_fs_description" size="67" />
				<br />
				<small style="position: relative; top: 8px;">ex: <code><?php echo htmlspecialchars("This Is A Description For The Homepage");?></code></small>
			</div>
			
			<div class="et_fs_setting" style="display: none; margin: 13px 0 26px 4px;">
				<label for="et_fs_link" style="color: #000; font-weight: bold;"> <?php esc_html_e('Custom Link:',$themename); ?> </label>
				<input type="text" style="width: 30em;" value="<?php echo esc_url($et_fs_link); ?>" id="et_fs_link" name="et_fs_link" size="67" />
				<br />
			</div>
			
		</div> <!-- #et_settings_featured_options -->
	</div> <!-- #et_custom_settings -->
	
	<?php
}

add_action( 'save_post', 'nimble_save_details', 10, 2 );
function nimble_save_details( $post_id, $post ){
	global $pagenow;
	if ( 'post.php' != $pagenow ) return $post_id;
		
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;
		
	if ( !isset( $_POST['et_settings_nonce'] ) || !wp_verify_nonce( $_POST['et_settings_nonce'], basename( __FILE__ ) ) )
        return $post_id;

	$temp_array = array();
	
	if ( !isset($_POST['et_is_featured']) ) {
		if ( get_post_meta( $post_id, "_et_nimble_settings", true ) ) $temp_array = maybe_unserialize( get_post_meta( $post_id, "_et_nimble_settings", true ) ); 
		$temp_array['et_is_featured'] = 0;
		update_post_meta( $post_id, "_et_nimble_settings", $temp_array );
		
		return $post_id;
	}
	
	$temp_array['et_is_featured'] = isset( $_POST["et_is_featured"] ) ? 1 : 0;
	$temp_array['et_fs_title'] = isset($_POST["et_fs_title"]) ? wp_kses( $_POST["et_fs_title"], array( 'span' => array(), 'strong' => array(), 'br' => array() ) ) : '';
	$temp_array['et_fs_description'] = isset($_POST["et_fs_description"]) ? wp_kses( $_POST["et_fs_description"], array( 'span' => array(), 'strong' => array(), 'br' => array() ) ) : '';
	$temp_array['et_fs_link'] = isset($_POST["et_fs_link"]) ? esc_url_raw($_POST["et_fs_link"]) : '';
		
	update_post_meta( $post_id, "_et_nimble_settings", $temp_array );
}

add_action( 'admin_enqueue_scripts', 'nimble_metabox_upload_scripts' );
function nimble_metabox_upload_scripts( $hook_suffix ) {
	if ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {
		wp_register_script( 'et-categories', get_template_directory_uri() . '/js/et-categories.js', array('jquery') );
		wp_enqueue_script( 'et-categories' );
	}
}