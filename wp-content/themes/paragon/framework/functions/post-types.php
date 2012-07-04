<?php
	add_action('init', 'create_post_types');
	function create_post_types() {
		register_post_type(
			'slide',
			array(
				'labels' => array(
					'name' => __( 'Slider Content' ),
					'singular_name' => __( 'Slide' ),
					'add_new' => __( 'Add New Slide' ),
					'add_new_item' => __( 'Add New slide' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit slide' ),
					'new_item' => __( 'New slide' ),
					'view' => __( 'View slide' ),
					'view_item' => __( 'View slide' ),
					'search_items' => __( 'Search Slides' ),
					'not_found' => __( 'No slides found' ),
					'not_found_in_trash' => __( 'No slides found in the Trash' ),
				),
				'public' => true,
				'show_ui' => true,
				'menu_position' => 5,
				'menu_icon' => get_stylesheet_directory_uri() . '/framework/images/slide-icon.png',
				'query_var' => true,
				'taxonomies' => array('category', 'post_tag'),
				'supports' => array( 'title','thumbnail', 'custom-fields', 'editor', 'comments'),
				'rewrite' => array( 'slug' => 'slide', 'with_front' => false ),
				'can_export' => true
			)
		);
		
		register_post_type(
			'portfolio',
			array(
				'labels' => array(
					'name' => __( 'Portfolio Items' ),
					'singular_name' => __( 'Portfolio' ),
					'add_new' => __( 'Add New Portfolio' ),
					'add_new_item' => __( 'Add New Portfolio' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Portfolio' ),
					'new_item' => __( 'New Portfolio Item' ),
					'view' => __( 'View Portfolio' ),
					'view_item' => __( 'View Portfolio' ),
					'search_items' => __( 'Search Portfolio Items' ),
					'not_found' => __( 'No Portfolio Items found' ),
					'not_found_in_trash' => __( 'No Portfolio items found in the Trash' ),
				),
				'public' => true,
				'show_ui' => true,
				'menu_position' => 5,
				'menu_icon' => get_stylesheet_directory_uri() . '/framework/images/portfolio-icon.png',
				'query_var' => true,
				'taxonomies' => array('category', 'post_tag'),
				'supports' => array( 'title','thumbnail', 'custom-fields', 'editor' ),
				'rewrite' => array( 'slug' => 'portfolio', 'with_front' => false ),
				'can_export' => true
			)
		);

	}
?>