<?php

/**
 * Description of Slide_Js is to 
 * build the slideshow based on
 * options selected in the backend;
 *
 * @author Jeff Clark
 */

class Slide_JS {
    
    var $slide_preload = true;
    var $slide_speed = '350';
    var $slide_pause_speed = '3500';
    var $slide_slide_fade_speed = '500';
    var $slide_effect = 'fade';
    var $slide_play = '5000';
    var $slide_hover_pause = true;
    var $slide_next_prev = true;
    var $slide_pagination = true;
    
    
    
    public function __construct() {
        add_shortcode('slides_js', array($this, 'slide_js_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'slide_js_include_slide_js'));
    }
       
    
    
    
    public function slide_js_include_slide_js(){
        //include any needed scripts
        if(!is_admin()){
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'slides js', SLIDEJS_BASE_URL . 'includes/js/slides.min.jquery.js', array('jquery'), '', true );
            wp_enqueue_style( 'gallery-styles', SLIDEJS_BASE_URL . 'includes/css/style.css' );
        }
    }

        
    
    
    /**
     * Slideshow output.  Generates options
     * based on the options set in the backend.
     * 
     * @global type $post
     * @param type $atts
     * @param type $content 
     */
    public function slide_js_shortcode($atts, $content = null){
        // generate a shortcode for use?
        global $post;
        ob_start();
        extract(shortcode_atts(array(
                    'id' => 'id',
                        ), $atts));

        $slide_type = get_post_meta($id, '_slide-type', true);
        $slide_height = get_post_meta($id, '_slide-height', true);
        $slide_width = get_post_meta($id, '_slide-width', true);
        $slide_img = maybe_unserialize( get_post_meta( $id, '_slide_img', true ) );
        $slide_link = maybe_unserialize( get_post_meta( $id, '_slide_link', true ) );
        $slide_order = maybe_unserialize( get_post_meta( $id, '_slide_order', true ) );
        
        /* SLIDESHOW OPTIONS */
        if(get_post_meta($id, '_slide-speed', true) != '' )
                $this->slide_speed = get_post_meta($id, '_slide-speed', true);
                        
        if( get_post_meta($id, '_slide-pause-speed', true) != '')
                $this->slide_pause_speed = get_post_meta($id, '_slide-pause-speed', true);
                
        if( get_post_meta($id, '_slide-fade-speed', true) != '')
                $this->slide_slide_fade_speed = get_post_meta($id, '_slide-fade-speed', true);
        
        if( get_post_meta($id, '_slide-play', true) != '' )
                $this->slide_play = get_post_meta($id, '_slide-play', true);
        
        $this->slide_preload = get_post_meta($id, '_slide-preload', true);
        $this->slide_effect = get_post_meta($id, '_slide-effect', true);
        $this->slide_hover_pause = get_post_meta($id, '_slide-hover-pause', true);
        $this->slide_next_prev = get_post_meta($id, '_slide-next-prev', true);
        $this->slide_pagination = get_post_meta($id, '_slide-paginations', true);
        
        
        $count = 0;
        $countPage = 0;
        $slider_id;
        
        $slide_count = count($slide_img);
        $slide_containter = ( $slide_count == 1 ) ? '' : 'slides_container';
        $slider_id = ($slide_type == 'gallery') ? 'products' : 'slides';
        
        $html = '<div id="'.$slider_id.'">';
        $html .= '<div class="'.$slide_containter.'" style="min-height: 100px; height: '.$slide_height.'px; width: '.$slide_width.'px ">';
            foreach($slide_img as $slide) {
                $html .= '<a href="'.$slide_link[$count].'" style="height: '.$slide_height.'px; width: '.$slide_width.'px"><img src="'.$slide.'" alt="slide_'.$count.'" height="'.$slide_height.'px" width="'.$slide_width.'px" ></a>';
                $count++;
            }
        $html .= '</div>';
        
        if($slider_id == 'products') {
            $html .= '<ul class="pagination">';
                foreach($slide_img as $slide) {
                    $img_id = $this->slide_js_get_img_id($slide);
                    $slide_thumb = wp_get_attachment_image_src($img_id,'thumbnail', true);

                    $html .= '<li><a href="#"><img src="'.$slide_thumb[0].'" alt="slide_'.$countPage.'" class="slide-thumb" ></a></li>';
                    $countPage++;
                }

            $html .= '</ul>';
        }
        $html .= '</div>';
        
       

        ?>

        <?php if($count > 1) : ?>
            <script type="text/javascript">
                jQuery(window).load(function(){
                    jQuery('#<?php echo $slider_id; ?>').slides({
                        preload: <?php echo esc_attr($this->slide_preload )?>,
                        preloadImage: '<?php echo SLIDEJS_BASE_URL ?>includes/images/loading.gif',
                        play: <?php echo esc_attr($this->slide_play) ?>, 
                        pause: <?php echo esc_attr($this->slide_pause_speed) ?>,
                        effect: '<?php echo esc_attr($this->slide_effect) ?>',
                        hoverPause: <?php echo esc_attr($this->slide_hover_pause) ?>,
                        crossfade: true,
                        slideSpeed: <?php echo esc_attr($this->slide_speed) ?>,
                        fadeSpeed: <?php echo esc_attr($this->slide_slide_fade_speed) ?>,
                        generateNextPrev: <?php echo esc_attr($this->slide_next_prev) ?>,
                        generatePagination: <?php echo esc_attr($this->slide_pagination) ?>
                    });
                });
            </script>       
        <?php endif; ?>
       
            
        <?php $output = ob_get_clean(); ?> 
        
        <?php return $html . $output; ?>
        
    <?php     
    }
    
    
    
    /**
     * Get the Image ID so 
     * we can get the thumbnail image
     * for Gallery Style Slideshow
     * 
     * @global type $wpdb
     * @param type $url
     * @return type 
     */
    public function slide_js_get_img_id($url){
	global $wpdb;
        $thepost = $wpdb->get_var( $wpdb->prepare( "SELECT *
	FROM $wpdb->posts WHERE guid = '%s'", $url ) );
        return $thepost;
    }
    
}