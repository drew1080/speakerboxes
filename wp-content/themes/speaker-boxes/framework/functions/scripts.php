<?php
function siiimple_enqueue_scripts() {
	if(!is_admin()){
		wp_deregister_script( 'jquery' );
		
		wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');
		
		wp_enqueue_script( 'Custom', SIIIMPLE_JS .'/custom.js', array('jquery'));
		
		wp_enqueue_script( 'Isotope', SIIIMPLE_JS .'/jquery.isotope.min.js', array('jquery'));
		
		wp_enqueue_script( 'Rotate', SIIIMPLE_JS .'/jquery.rotate.js', array('jquery'));
		
		//wp_enqueue_script( 'mosaic', SIIIMPLE_JS .'/mosaic.1.0.1.min.js', array('jquery'));
		
		//wp_enqueue_script( 'ad-gallery', SIIIMPLE_JS .'/jquery.ad-gallery.js', array('jquery'));
		
		wp_enqueue_script( 'cycle', SIIIMPLE_JS .'/jquery.cycle.all.js', array('jquery'));
		
		//wp_enqueue_script( 'pajinate', SIIIMPLE_JS .'/jquery.pajinate.min.js', array('jquery'));
		
		wp_enqueue_script( 'pajinate', SIIIMPLE_JS .'/jquery.pajinate.js', array('jquery'));
		
		wp_enqueue_script( 'jscrollpane', SIIIMPLE_JS .'/jquery.jscrollpane.min.js', array('jquery'));
		
		wp_enqueue_script( 'mousewheel', SIIIMPLE_JS .'/jquery.mousewheel.js', array('jquery'));
		
		//wp_enqueue_script( 'smartpaginator', SIIIMPLE_JS .'/smartpaginator.js', array('jquery'));
		
		}
}
add_action('wp_print_scripts', 'siiimple_enqueue_scripts');
add_action('wp_print_styles', 'siiimple_enqueue_scripts');
?>