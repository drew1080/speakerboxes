<?php
// Define Directory Constants
define('SIIIMPLE_FRAMEWORK', TEMPLATEPATH . '/framework');
define('SIIIMPLE_ADMIN', SIIIMPLE_FRAMEWORK . '/admin');
define('SIIIMPLE_FUNCTIONS', SIIIMPLE_FRAMEWORK . '/functions');
define('SIIIMPLE_INCLUDES', SIIIMPLE_FRAMEWORK . '/includes');
define('SIIIMPLE_JS', get_template_directory_uri() . '/framework/scripts' );

// Define Folder Constants
define('SIIIMPLE_SCRIPTS_FOLDER', get_bloginfo('template_url') . '/framework/scripts');

// Load Header Scripts - jQuery, Cufon, etc.
require_once(SIIIMPLE_FUNCTIONS . '/scripts.php');

// Load Header Scripts - jQuery, Cufon, etc.
require_once(SIIIMPLE_FUNCTIONS . '/meta-box.php');

// Load Header Scripts - jQuery, Cufon, etc.
require_once(SIIIMPLE_FUNCTIONS . '/sidebars.php');

// Load Header Scripts - jQuery, Cufon, etc.
require_once(SIIIMPLE_FUNCTIONS . '/post-types.php');

// Load Header Scripts - jQuery, Cufon, etc.
require_once(SIIIMPLE_FUNCTIONS . '/meta.php');

// Load Some Basic Theme Functions - Widgets, etc.
require_once(SIIIMPLE_FUNCTIONS . '/basicFunctions.php');

// Load Some Basic Theme Functions - Widgets, etc.
require_once(SIIIMPLE_FUNCTIONS . '/widgets.php');

// Load Some Basic Theme Functions - Widgets, etc.
require_once(SIIIMPLE_FUNCTIONS . '/twitter-widget.php');

// Video Sidebar Plugin - Widgets, etc.
require_once(SIIIMPLE_FUNCTIONS . '/video-sidebar-widgets/video-sidebar-widgets.php');

// Load Custom Shortcodes
require_once(SIIIMPLE_FUNCTIONS . '/shortcodes.php');

?>
<?php
/*-----------------------------------------------------------------------------------*/
/* Options Framework Functions
/*-----------------------------------------------------------------------------------*/

/* Set the file path based on whether the Options Framework is in a parent theme or child theme */

if ( STYLESHEETPATH == TEMPLATEPATH ) {
	define('OF_FILEPATH', TEMPLATEPATH);
	define('OF_DIRECTORY', get_bloginfo('template_directory'));
} else {
	define('OF_FILEPATH', STYLESHEETPATH);
	define('OF_DIRECTORY', get_bloginfo('stylesheet_directory'));
}


// Custom Taxonomy Code  
// Custom Taxonomy Code  
// Custom Taxonomy Code  
add_action( 'init', 'build_taxonomies', 0 ); 

function build_taxonomies() {

register_taxonomy( 'portfolio_sorting', 'portfolio', array( 'hierarchical' => true, 'label' => 'Portfolio Sorting', 'query_var' => true, 'rewrite' => true ) ); 


}
// Custom Taxonomy Code  
// Custom Taxonomy Code  
// Custom Taxonomy Code  


/* These files build out the options interface.  Likely won't need to edit these. */

require_once (OF_FILEPATH . '/admin/admin-functions.php');		// Custom functions and plugins
require_once (OF_FILEPATH . '/admin/admin-interface.php');		// Admin Interfaces (options,framework, seo)

/* These files build out the theme specific options and associated functions. */

require_once (OF_FILEPATH . '/admin/theme-options.php'); 		// Options panel settings and custom settings
require_once (OF_FILEPATH . '/admin/theme-functions.php'); 	// Theme actions based on options settings

?>