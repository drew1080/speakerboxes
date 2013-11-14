<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
		<?php
			$et_ptemplate_settings = maybe_unserialize( get_post_meta(get_the_ID(),'et_ptemplate_settings',true) );
			$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;
			
			$thumb = '';
			$width = (int) apply_filters('et_page_image_width',621);
			if ( is_page_template( 'page-full.php' ) || $fullwidth ) $width = (int) apply_filters('et_page_image_full_width', 960);
			$height = (int) apply_filters('et_page_image_height',320);
			$classtext = '';
			$titletext = get_the_title();
			$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Pageimage');
			$thumb = $thumbnail["thumb"];
		?>
		<?php if ( '' != $thumb && 'on' == et_get_option( 'nimble_page_thumbnails', 'false' ) ) { ?>
			<div class="post-thumbnail">
				<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
				<span class="overlay"></span>
			</div> 	<!-- end .post-thumbnail -->
		<?php } ?>
		
		<div class="post_content">
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages','Nimble').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			<?php edit_post_link(esc_attr__('Edit this page','Nimble')); ?>
		</div> 	<!-- end .post_content -->
	</article> <!-- end .post -->
<?php endwhile; // end of the loop. ?>