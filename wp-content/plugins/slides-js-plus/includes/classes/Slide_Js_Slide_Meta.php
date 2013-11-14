<?php
/**
 * Description of Slide_Js_Create is
 * to create each slide with Name, Text, Image,
 * Thumnail and Link
 *
 * @author Jeff Clark
 */

class Slide_Js_Slide_Meta {
    
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'slide_js_meta_box'));
        add_action('publish_slidejs-slideshow', array($this, 'slide_js_save_fields'));
    }
    

    
    
    public function slide_js_meta_box(){
          add_meta_box( 
                'Slides JS Slideshow',
                __( 'Slides JS Plus', 'slidesjs' ),
                array($this, 'slide_js_meta_box_content'),
                'slidejs-slideshow',
                'normal',
                'high'
            );
          
          
          add_meta_box( 
                'Slides JS Plus Shortcode',
                __( 'Slides JS Plus Shortcode', 'slidesjs' ),
                array($this, 'slide_js_meta_box_shortcode_content'),
                'slidejs-slideshow' ,
                'side',
                'default'
            );
          
          add_meta_box( 
                'Slides JS Plus Settings',
                __( 'Slides JS Plus Settings', 'slidesjs' ),
                array($this, 'slide_js_meta_box_settings_content'),
                'slidejs-slideshow' ,
                'side',
                'default'
            );
    }
    
    
    
    
    /**
     * Slideshow images, links,
     * and position.
     * 
     * @global type $post 
     */
    public function slide_js_meta_box_content(){
        global $post;
        
        $count = 0;
        
        $slide_img = maybe_unserialize( get_post_meta( $post->ID, '_slide_img', true ) );
        $slide_link = maybe_unserialize( get_post_meta( $post->ID, '_slide_link', true ) );
        $slide_order = maybe_unserialize( get_post_meta( $post->ID, '_slide_order', true ) );
        
        echo '<script>
                    jQuery(document).ready(function($){
                        $(function() {
                            $( "#sortable" ).sortable();
                            $( "#sortable" ).disableSelection();
                        }); 
                    });
              </script>';
        
        echo '<div id="slidejs-slide">';
        echo '<ul id="sortable">';
        if($slide_img) {
            foreach($slide_img as $img){

                // image upload                 
                echo '<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><p class="slide-wrap"><span class="group"><span class="close-img">&nbsp;</span><label for="new_slides" class="hidden">Slide</label><input class="upload hidden" type="text" name="image[]" value="'.$img.'" />';
                echo '<input class="upload-button hidden" type="button" name="wsl-image-add" value="Upload Image" />';
                echo '<img src="'.esc_attr($img).'" class="slidejs-img"></span>';

                // href link
                echo '<label>Slide Link</label>';
                echo '<input type="text" id="slide-link" name="slide-link[]" value="'.esc_url($slide_link[$count]).'" />';
                
                echo '<a href="#" id="remScnt">Remove</a></li>';
                // Slide order
//                echo '<label>Slide Order</label>';
//                echo '<input type="text" id="slide-order" width="100px" name="slide-order[]" value="'.$slide_order[$count].'" />';
//                
//                echo '</p>';
                
                // 3.5 media uploader
//                echo '<a href="#" class="custom_media_upload">Upload</a>
//                    <img class="custom_media_image" src="" />
//                    <input class="custom_media_url" type="text" name="attachment_url" value="">
//                    <input class="custom_media_id" type="text" name="attachment_id" value="">';

                

                $count++;
            }
        }
        echo '</div></ul>';

        echo '<h2 class="new-slide"><a href="#" id="addSlide">Add Another Slide +</a></h2>';
        
    }
    
    
    
    /**
     * Generates the shortcode to
     * be used in our themes.
     * 
     * @global type $post 
     */
    public function slide_js_meta_box_shortcode_content(){
        global $post;
        echo '<p id="shortcode">[slides_js id="'.$post->ID.'"]</p>';
    }
    
    
    
    /**
     * Slideshow options from.
     * 
     * @global type $post 
     */
    public function slide_js_meta_box_settings_content(){
        global $post;
        
        $slide_type = get_post_meta($post->ID, '_slide-type', true);
        $slide_height = get_post_meta($post->ID, '_slide-height', true);
        $slide_width = get_post_meta($post->ID, '_slide-width', true);
        $slide_speed = get_post_meta($post->ID, '_slide-speed', true);
        $slide_pause_speed = get_post_meta($post->ID, '_slide-pause-speed', true);
        $slide_slide_fade_speed = get_post_meta($post->ID, '_slide-fade-speed', true);
        $slide_preload = get_post_meta($post->ID, '_slide-preload', true);
        $slide_effect = get_post_meta($post->ID, '_slide-effect', true);
        $slide_hover_pause = get_post_meta($post->ID, '_slide-hover-pause', true);
        $slide_next_prev = get_post_meta($post->ID, '_slide-next-prev', true);
        $slide_pagination = get_post_meta($post->ID, '_slide-paginations', true);
        $slide_play = get_post_meta($post->ID, '_slide-play', true);
        
        
        echo '<div class="slide-js-options">';
        echo '<p><center><strong><a href="http://www.jeffreydev.com/email-newsletter/">Sign up news updates</a></strong></center></p>';
        echo '<p><label>SlideShow Style</label>';
        $slide_type == 'gallery' ? $slide_type = 'selected' : '';
        echo '<select name="slide-type">';
        echo '<option value="slider">Slider</option>';
        echo '<option value="gallery" '.$slide_type.'>Gallery</option>';
        echo '</select>';
        
        echo '<p><label>Slideshow Height (px)</label>';
        echo '<input type="text" name="slide-height" value="'.$slide_height.'"/><p>';
        
        echo '<p><label>Slideshow Width (px)</label>';
        echo '<input type="text" name="slide-width" value="'.$slide_width.'"/><p>';
        
        echo '<p><label>Slideshow Play Speed</label>';
        echo '<input type="text" name="slide-play" value="'.$slide_play.'"/><p>';
        
        echo '<p><label>Slideshow Speed : default: 350(ms)</label>';
        echo '<input type="text" name="slide-speed" value="'.$slide_speed.'"/><p>';
        
        echo '<p><label>Pause Speed : default: 3500(ms)</label>';
        echo '<input type="text" name="slide-pause-speed" value="'.$slide_pause_speed.'"/><p>';
        
        echo '<p><label>Fade Speed : default: 500(ms)</label>';
        echo '<input type="text" name="slide-fade-speed" value="'.$slide_slide_fade_speed.'"/><p>';
        
        $selected = ($slide_preload == "0") ? 'selected' : '';
        echo '<p><label>Preloader</label>';
        echo '<select name="slide-preload">';
        echo '<option value="1">True</option>';
        echo '<option value="0" '.$selected.'>False</option>';
        echo '</select>';
        echo '<p>';
        
        $effect = ($slide_effect == 'slide') ? 'selected' : '';
        echo '<p><label>Effect</label>';
        echo '<select name="slide-effect">';
        echo '<option value="fade">Fade</option>';
        echo '<option value="slide" '.$effect.'>Slide</option>';
        echo '</select>';
        echo '<p>';
        
        $hover = ($slide_hover_pause == "0") ? 'selected' : '';
        echo '<p><label>Hover Pause</label>';
        echo '<select name="slide-hover-pause">';
        echo '<option value="1">True</option>';
        echo '<option value="0" '.$hover .'>False</option>';
        echo '</select>';
        echo '<p>';
        
        $next_prev = ($slide_next_prev == "0") ? 'selected' : '';
        echo '<p><label>Generate Next / Prev</label>';
        echo '<select name="slide-next-prev">';
        echo '<option value="1">True</option>';
        echo '<option value="0" '.$next_prev.'>False</option>';
        echo '</select>';
        echo '<p>';
        
        $pag = ($slide_pagination == "1") ? 'selected' : '';
        echo '<p><label>Generate Pagincation</label>';
        echo '<select name="slide-paginations">';
        echo '<option value="0">False</option>';
        echo '<option value="1" '.$pag .'>True</option>';
        echo '</select>';
        echo '<p>';
        
        echo '</div>';
    }
    

    
    
    /**
     * Save all fields generated
     * through the slideshow plugin.
     * 
     * @global type $post 
     */
    public function slide_js_save_fields(){
        global $post;
        
        if( $_POST ) { 
            foreach( $_POST['image'] as $key => $value ) {
                $slide_img[] = $value;
            }
            update_post_meta( $post->ID, '_slide_img', $slide_img ); 

            
            foreach( $_POST['slide-link'] as $key => $value ) {
                $slide_link[] = $value;
            }
            update_post_meta( $post->ID, '_slide_link', $slide_link ); 
            
      
//            foreach( $_POST['slide-order'] as $key => $value ) {
//                $slide_order[] = $value;
//            }
//            update_post_meta( $post->ID, '_slide_order', $slide_order); 
        }
        
        
        /* SLIDESHOW OPTIONS */
        
        $slide_options = array(
            'slide-type' => $_POST['slide-type'],
            'slide-height' => $_POST['slide-height'],
            'slide-width' => $_POST['slide-width'],
            'slide-speed' => $_POST['slide-speed'],
            'slide-pause-speed' => $_POST['slide-pause-speed'],
            'slide-fade-speed' => $_POST['slide-fade-speed'],
            'slide-preload' => $_POST['slide-preload'],
            'slide-effect' => $_POST['slide-effect'],
            'slide-hover-pause' => $_POST['slide-hover-pause'],
            'slide-next-prev' => $_POST['slide-next-prev'],
            'slide-paginations' => $_POST['slide-paginations'],
            'slide-play' => $_POST['slide-play'],
        );
        
        foreach($slide_options as $key => $option) {
            update_post_meta($post->ID, '_'.$key, $option);
        }
   
    }
    
}
