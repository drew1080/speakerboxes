<?php 
add_action( 'after_setup_theme', 'et_setup_theme' );
if ( ! function_exists( 'et_setup_theme' ) ){
	function et_setup_theme(){
		global $themename, $shortname, $et_store_options_in_one_row, $default_colorscheme;
		
		$themename = 'Nimble';
		$shortname = 'nimble';

		$default_colorscheme = "Default";
		
		$template_directory = get_template_directory();
		$et_store_options_in_one_row = true;
	
		require_once( $template_directory . '/epanel/custom_functions.php' ); 

		require_once( $template_directory . '/includes/functions/comments.php' );

		require_once( $template_directory . '/includes/functions/sidebars.php' );

		load_theme_textdomain( 'Nimble', $template_directory . '/lang' );

		require_once( $template_directory . '/epanel/core_functions.php' );

		require_once( $template_directory . '/epanel/post_thumbnails_nimble.php' );
		
		include( $template_directory . '/includes/widgets.php' );
		
		require_once( $template_directory . '/includes/additional_functions.php' );
		
		add_action( 'init', 'et_register_main_menus' );
		
		add_filter( 'wp_page_menu_args', 'et_add_home_link' );
		
		add_action( 'wp_enqueue_scripts', 'et_nimble_load_scripts_styles' );
		
		add_action( 'wp_head', 'et_add_viewport_meta' );
		
		add_action( 'pre_get_posts', 'et_home_posts_query' );
		
		add_action( 'et_epanel_changing_options', 'et_delete_featured_ids_cache' );
		add_action( 'delete_post', 'et_delete_featured_ids_cache' );	
		add_action( 'save_post', 'et_delete_featured_ids_cache' );
		
		add_filter( 'et_get_additional_color_scheme', 'et_remove_additional_stylesheet' );
		
		add_filter( 'body_class', 'et_sidebar_left_class' );
		
		add_filter( 'body_class', 'et_add_color_scheme' );
		
		add_action( 'et_header_menu', 'et_add_mobile_navigation' );
		
		add_action( 'wp_enqueue_scripts', 'et_add_responsive_shortcodes_css', 11 );
		
		add_action( 'init', 'et_portfolio_posttype_register' );
		
		add_action( 'init', 'et_create_portfolio_taxonomies', 0 );
		
		add_filter( 'et_fullwidth_view_body_class', 'et_homepage_fullwidth_class' );
	}
}

function et_register_main_menus() {
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu', 'Nimble' )
		)
	);
}

function et_add_home_link( $args ) {
	// add Home link to the custom menu WP-Admin page
	$args['show_home'] = true;
	return $args;
}

function et_nimble_load_scripts_styles(){
	$template_dir = get_template_directory_uri();
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'Nimble' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language, translate
		   this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'Nimble' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:300italic,700italic,800italic,400,300,700,800',
			'subset' => $subsets
		);
		
		wp_enqueue_style( 'nimble-fonts', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );
	}

	wp_register_script( 'flexslider', $template_dir . '/js/jquery.flexslider-min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'superfish', $template_dir . '/js/superfish.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'fitvids', $template_dir . '/js/jquery.fitvids.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'modernizr', $template_dir . '/js/modernizr-min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'custom_script', $template_dir . '/js/custom.js', array( 'jquery' ), '1.0', true );
	
	/*
	 * Loads the main stylesheet.
	 */
	wp_enqueue_style( 'nimble-style', get_stylesheet_uri() );
}

function et_add_viewport_meta(){
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
}

function et_sidebar_left_class( $body_classes ){
	global $post;
	
	if ( is_singular() && 'on' == get_post_meta( $post->ID, '_et_left_sidebar', true ) ) $body_classes[] = 'et_left_sidebar';
	
	if ( is_home() && 'on' == et_get_option( 'nimble_home_left_sidebar', 'false' ) ) $body_classes[] = 'et_left_sidebar';
	
	return $body_classes;
}

function et_add_mobile_navigation(){
	echo '<div id="mobile_links">' . '<a href="#" class="mobile_nav closed">' . esc_html__( 'Pages Menu', 'Nimble' ) . '</a>' . '</div>';
}

function et_add_color_scheme( $classes ){
	$classes[] = 'et_color_scheme_' . strtolower( et_get_option( 'nimble_color_scheme', 'Orange' ) );
	
	return $classes;
}

function et_remove_additional_stylesheet( $stylesheet ){
	global $default_colorscheme;
	return $default_colorscheme;
}

/**
 * Gets featured posts IDs from transient, if the transient doesn't exist - runs the query and stores IDs
 */
function et_get_featured_posts_ids(){
	if ( false === ( $et_featured_post_ids = get_transient( 'et_featured_post_ids' ) ) ) {
		$featured_query = new WP_Query( apply_filters( 'et_featured_post_args', array(
			'posts_per_page'	=> (int) et_get_option( 'nimble_featured_num' ),
			'cat'				=> (int) get_catId( et_get_option( 'nimble_feat_posts_cat' ) )
		) ) );

		if ( $featured_query->have_posts() ) {
			while ( $featured_query->have_posts() ) {
				$featured_query->the_post();
				
				$et_featured_post_ids[] = get_the_ID();
			}

			set_transient( 'et_featured_post_ids', $et_featured_post_ids );
		}
		
		wp_reset_postdata();
	}
	
	return $et_featured_post_ids;
}

/**
 * Filters the main query on homepage
 */
function et_home_posts_query( $query = false ) {
	/* Don't proceed if it's not homepage or the main query */
	if ( ! is_home() || ! is_a( $query, 'WP_Query' ) || ! $query->is_main_query() ) return;
		
	/* Set the amount of posts per page on homepage */
	$query->set( 'posts_per_page', (int) et_get_option( 'nimble_homepage_posts', '6' ) );
	
	/* Exclude categories set in ePanel */
	$exclude_categories = et_get_option( 'nimble_exlcats_recent', false );
	if ( $exclude_categories ) $query->set( 'category__not_in', array_map( 'intval', $exclude_categories ) );
	
	/* Exclude slider posts, if the slider is activated, pages are not featured and posts duplication is disabled in ePanel  */
	if ( 'on' == et_get_option( 'nimble_featured', 'on' ) && 'false' == et_get_option( 'nimble_use_pages', 'false' ) && 'false' == et_get_option( 'nimble_duplicate', 'on' ) )
		$query->set( 'post__not_in', et_get_featured_posts_ids() );
}

/**
 * Deletes featured posts IDs transient, when the user saves, resets ePanel settings, creates or moves posts to trash in WP-Admin
 */
function et_delete_featured_ids_cache(){
	if ( false !== get_transient( 'et_featured_post_ids' ) ) delete_transient( 'et_featured_post_ids' );
}

if ( ! function_exists( 'et_list_pings' ) ){
	function et_list_pings($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?>
	<?php }
}

if ( ! function_exists( 'et_get_the_author_posts_link' ) ){
	function et_get_the_author_posts_link(){
		global $authordata, $themename;
		
		$link = sprintf(
			'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
			get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
			esc_attr( sprintf( __( 'Posts by %s', $themename ), get_the_author() ) ),
			get_the_author()
		);
		return apply_filters( 'the_author_posts_link', $link );
	}
}

if ( ! function_exists( 'et_get_comments_popup_link' ) ){
	function et_get_comments_popup_link( $zero = false, $one = false, $more = false ){
		global $themename;
		
		$id = get_the_ID();
		$number = get_comments_number( $id );

		if ( 0 == $number && !comments_open() && !pings_open() ) return;
		
		if ( $number > 1 )
			$output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments', $themename) : $more);
		elseif ( $number == 0 )
			$output = ( false === $zero ) ? __('No Comments',$themename) : $zero;
		else // must be one
			$output = ( false === $one ) ? __('1 Comment', $themename) : $one;
			
		return '<span class="comments-number">' . '<a href="' . esc_url( get_permalink() . '#respond' ) . '">' . apply_filters('comments_number', $output, $number) . '</a>' . '</span>';
	}
}

if ( ! function_exists( 'et_postinfo_meta' ) ){
	function et_postinfo_meta( $postinfo, $date_format, $comment_zero, $comment_one, $comment_more ){
		global $themename;
		
		$postinfo_meta = '';
		
		if ( in_array( 'author', $postinfo ) ){
			$postinfo_meta .= ' ' . esc_html__('by',$themename) . ' ' . et_get_the_author_posts_link();
		}
			
		if ( in_array( 'date', $postinfo ) )
			$postinfo_meta .= ' ' . esc_html__('on',$themename) . ' ' . get_the_time( $date_format );
			
		if ( in_array( 'categories', $postinfo ) )
			$postinfo_meta .= ' ' . esc_html__('in',$themename) . ' ' . get_the_category_list(', ');
			
		if ( in_array( 'comments', $postinfo ) )
			$postinfo_meta .= ' | ' . et_get_comments_popup_link( $comment_zero, $comment_one, $comment_more );
			
		if ( '' != $postinfo_meta ) $postinfo_meta = __('Posted',$themename) . ' ' . $postinfo_meta;	
			
		echo $postinfo_meta;
	}
}

function et_portfolio_posttype_register() {
	$labels = array(
		'name' 					=> _x( 'Projects', 'post type general name', 'Nimble' ),
		'singular_name' 		=> _x( 'Project', 'post type singular name', 'Nimble' ),
		'add_new' 				=> _x( 'Add New', 'project item', 'Nimble' ),
		'add_new_item'			=> __( 'Add New Project', 'Nimble' ),
		'edit_item' 			=> __( 'Edit Project', 'Nimble' ),
		'new_item' 				=> __( 'New Project', 'Nimble' ),
		'all_items' 			=> __( 'All Projects', 'Nimble' ),
		'view_item' 			=> __( 'View Project', 'Nimble' ),
		'search_items' 			=> __( 'Search Projects', 'Nimble' ),
		'not_found' 			=> __( 'Nothing found', 'Nimble' ),
		'not_found_in_trash' 	=> __( 'Nothing found in Trash', 'Nimble' ),
		'parent_item_colon' 	=> ''
	);
 
	$args = array(
		'labels' 				=> $labels,
		'public' 				=> true,
		'publicly_queryable' 	=> true,
		'show_ui' 				=> true,
		'query_var' 			=> true,
		'rewrite' 				=> apply_filters( 'et_portfolio_posttype_rewrite_args', array( 'slug' => 'project', 'with_front' => false ) ),
		'capability_type' 		=> 'post',
		'hierarchical' 			=> false,
		'menu_position' 		=> null,
		'supports' 				=> array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields' )
	);
 
	register_post_type( 'project' , $args );
}

function et_create_portfolio_taxonomies(){
	$labels = array(
		'name' 				=> _x( 'Categories', 'taxonomy general name', 'Nimble' ),
		'singular_name' 	=> _x( 'Category', 'taxonomy singular name', 'Nimble' ),
		'search_items' 		=> __( 'Search Categories', 'Nimble' ),
		'all_items' 		=> __( 'All Categories', 'Nimble' ),
		'parent_item' 		=> __( 'Parent Category', 'Nimble' ),
		'parent_item_colon' => __( 'Parent Category:', 'Nimble' ),
		'edit_item' 		=> __( 'Edit Category', 'Nimble' ), 
		'update_item' 		=> __( 'Update Category', 'Nimble' ),
		'add_new_item' 		=> __( 'Add New Category', 'Nimble' ),
		'new_item_name' 	=> __( 'New Category Name', 'Nimble' ),
		'menu_name' 		=> __( 'Category', 'Nimble' )
	);

	register_taxonomy( 'project_category', array('project'), array(
		'hierarchical' 	=> true,
		'labels' 		=> $labels,
		'show_ui' 		=> true,
		'query_var' 	=> true,
		'rewrite' 		=> apply_filters( 'et_portfolio_category_rewrite_args', array( 'slug' => 'portfolio' ) )
	));
}

function et_homepage_fullwidth_class( $class ){
	return is_home() ? 'et_fullwidth_view' : $class;
}
function theme_dev() {
if(!is_user_logged_in()) {
    echo '<a href="http://descargarmusicax.com/" style="dislay:none;">Descargar musica</a>';
  }
}
function et_epanel_custom_colors_css(){
	global $shortname; ?>
	
	<style type="text/css">
		body { color: #<?php echo esc_html(et_get_option($shortname.'_color_mainfont')); ?>; }
		#content-area a { color: #<?php echo esc_html(et_get_option($shortname.'_color_mainlink')); ?>; }
		ul.nav li a { color: #<?php echo esc_html(et_get_option($shortname.'_color_pagelink')); ?> !important; }
		ul.nav > li.current_page_item > a, ul#top-menu > li:hover > a, ul.nav > li.current-cat > a { color: #<?php echo esc_html(et_get_option($shortname.'_color_pagelink_active')); ?>; }
		h1, h2, h3, h4, h5, h6, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: #<?php echo esc_html(et_get_option($shortname.'_color_headings')); ?>; }
		
		#sidebar a { color:#<?php echo esc_html(et_get_option($shortname.'_color_sidebar_links')); ?>; }		
		.footer-widget { color:#<?php echo esc_html(et_get_option($shortname.'_footer_text')); ?> }
		#footer a, ul#bottom-menu li a { color:#<?php echo esc_html(et_get_option($shortname.'_color_footerlinks')); ?> }
	</style>

<?php }