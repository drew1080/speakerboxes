<?php
/**
Plugin Name: Slides JS Plus
Plugin URI: http://jeffreydev.com/plugins/slides-js-plus
Description: Slidejs Slideshow Plugin.  Allow for multiple slideshows and full control over each gallery, slider or static image.
Version: 1.0.4
Author: JeffreyDev
Author URI: http://jeffreydev.com
*/




    if(!defined( 'SLIDEJS_BASE_URL' )) {
        define( 'SLIDEJS_BASE_URL', plugin_dir_url(__FILE__) );
    }
    
    
    
    /* SLIDEJS INCLUDES */
    include_once dirname( __FILE__ ) . '/includes/classes/Slide_Js_Setup.php';
    include_once dirname( __FILE__ ) . '/includes/classes/Slide_Js_Slide_Meta.php';
    include_once dirname( __FILE__ ) . '/includes/classes/Slide_JS.php';
    
    
    
    
    /* SLIDESHOW SETUP */
    new Slide_Js_Setup();
    
    /* SLIDESHOW META BOX */
    new Slide_Js_Slide_Meta();
    
    /* SLIDESHOW SHORTCODE */
    new Slide_JS();
    
  