<?php
	wp_enqueue_script( 'flexslider' );

	$featured_slider_class = '';
	if ( 'on' == et_get_option( 'nimble_slider_auto', 'on' ) ) $featured_slider_class .= ' et_slider_auto et_slider_speed_' . et_get_option( 'nimble_slider_autospeed', '7000' );
?>
<div id="featured" class="<?php echo esc_attr( 'flexslider' . $featured_slider_class ); ?>">
	<ul class="slides">
	<?php
		$featured_cat = et_get_option( 'nimble_feat_cat' );
		$featured_num = (int) et_get_option( 'nimble_featured_num', '3' );
		
		if ( 'false' == et_get_option( 'nimble_use_pages', 'false' ) ) {
			$featured_query = new WP_Query( apply_filters( 'et_featured_post_args', array(
				'posts_per_page' 	=> $featured_num,
				'cat' 				=> (int) get_catId( et_get_option( 'nimble_feat_posts_cat' ) )
			) ) );
		} else {
			global $pages_number;
			
			if ( '' != et_get_option( 'nimble_feat_pages' ) ) $featured_num = count( et_get_option( 'nimble_feat_pages' ) );
			else $featured_num = $pages_number;
			
			$featured_query = new WP_Query(
				apply_filters( 'et_featured_page_args',
					array(	'post_type'			=> 'page',
							'orderby'			=> 'menu_order',
							'order' 			=> 'ASC',
							'post__in' 			=> (array) array_map( 'intval', et_get_option( 'nimble_feat_pages' ) ),
							'posts_per_page' 	=> (int) $featured_num
						)
				)	
			);
		}
	
		while ( $featured_query->have_posts() ) : $featured_query->the_post();
			$et_nimble_settings = maybe_unserialize( get_post_meta(get_the_ID(),'_et_nimble_settings',true) );
			
			$link = isset( $et_nimble_settings['et_fs_link'] ) && !empty($et_nimble_settings['et_fs_link']) ? $et_nimble_settings['et_fs_link'] : get_permalink();
			$title = isset( $et_nimble_settings['et_fs_title'] ) && !empty($et_nimble_settings['et_fs_title']) ? $et_nimble_settings['et_fs_title'] : get_the_title();
			$description = isset( $et_nimble_settings['et_fs_description'] ) && !empty($et_nimble_settings['et_fs_description']) ? $et_nimble_settings['et_fs_description'] : truncate_post(40,false);
		?>
			<li class="slide">					
				<h2><a href="<?php echo esc_url( $link ); ?>"><?php echo $title; ?></a></h2>
				<div class="description"><?php echo $description; ?></div>
				
				<a href="<?php echo esc_url( $link ); ?>">							
					<?php
						$width = (int) apply_filters( 'slider_image_width', 960 );
						$height = (int) apply_filters( 'slider_image_height', 295 );
						$title = get_the_title();
						$thumbnail = get_thumbnail( $width, $height, '', $title, $title, false, 'Featured' );
						$thumb = $thumbnail["thumb"];
						
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $title, $width, $height, '' );
					?>
				</a>
			</li> <!-- end .slide -->
	<?php
		endwhile;
		wp_reset_postdata();
	?>
	</ul>
</div> <!-- end #featured -->