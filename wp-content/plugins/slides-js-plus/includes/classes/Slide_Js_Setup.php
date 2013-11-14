<?php
/**
 * Description of Slide_Js_Setup is to 
 * setup the plugin with a CPT.
 *
 * @author Jeff Clark
 */


class Slide_Js_Setup {
    
    var $slide_post_key = "slidejs-slideshow";
    var $slide_post_name = "Slides JS Plus";
    var $slide_post_single_name = "Slide JS Plus";
    var $slide_post_slug = "slidejs";
    var $slide_supports = array('title', 'revisions');
    
    
    
    
    public function __construct() {
        add_action('init', array($this, 'slidejs_create_custom_post_type'));
        add_action('admin_enqueue_scripts', array($this, 'slidejs_include_files'));
        
        // ADDING COLUMNS TO SHOW SHORTCODE  
        add_filter('manage_slidejs-slideshow_posts_columns', array($this, 'slidejs_add_cpt_column_titles'));  
        add_action('manage_slidejs-slideshow_posts_custom_column', array($this, 'slidejs_add_cpt_column_content'));
    }
    
    
    
    
    public function slidejs_include_files(){
        
        global $post;
        $post_type = get_post_type($post);
        
        if(is_admin() & $post_type == 'slidejs-slideshow' ){ 
            wp_enqueue_style('slidejs-styles', SLIDEJS_BASE_URL . 'includes/css/admin-style.css');
            
            /* Image Uploader */
            /* wp_enqueue_media(); // For new media uploader */
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_script('image-upload', SLIDEJS_BASE_URL . 'includes/js/image.js');
            wp_enqueue_style( 'thickbox' );
            
            wp_enqueue_script('slide', SLIDEJS_BASE_URL . 'includes/js/slideshow.js');
            
            
            /* Sortable Scripts */
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable'); 
        }
    }
    
    
    
    
    public function slidejs_create_custom_post_type(){        
        register_post_type( $this->slide_post_key,                
                array(                   
                    'labels' => array(
                    'name' => __($this->slide_post_name),
                    'singular_name' => __($this->slide_post_single_name),
                    ),
                    'public' => true,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'exclude_from_search' => true,
                    'hierarchical' => false,
                    'rewrite' => array('slug' => $this->slide_post_slug),
                    'supports' => $this->slide_supports,
                )
            
        );      
    }
    
    
    
    
    public function slidejs_add_cpt_column_titles($defaults){
        $defaults['short_code'] = 'Shortcode';  
        return $defaults;  
    }
    
    
    
    
    public function slidejs_add_cpt_column_content($column_name){
        global $post;
        
        if ($column_name == 'short_code') {  
            echo '[slides_js id="'.$post->ID.'"]';
        }  

    }

}