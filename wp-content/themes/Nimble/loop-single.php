<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<?php if (et_get_option('nimble_integration_single_top') <> '' && et_get_option('nimble_integrate_singletop_enable') == 'on') echo (et_get_option('nimble_integration_single_top')); ?>
	
	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
	
	<?php
		$thumb = '';
		$post_type = get_post_type( get_the_ID() );		
		$et_full_post = get_post_meta( get_the_ID(), '_et_full_post', true );
		$width = (int) apply_filters('et_blog_image_width',621);
		if ( 'on' == $et_full_post ) $width = (int) apply_filters( 'et_single_fullwidth_image_width', 960 );
		$height = (int) apply_filters('et_blog_image_height',320);
		if ( 'project' == $post_type ) $height = (int) apply_filters('et_project_single_image_height',9999);
		$classtext = '';
		$titletext = get_the_title();
		$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Projectimage');
		$thumb = $thumbnail["thumb"];
	?>
	
	<?php if ( '' != $thumb && 'on' == et_get_option( 'nimble_thumbnails' ) ) { ?>
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
	
	<?php if (et_get_option('nimble_integration_single_bottom') <> '' && et_get_option('nimble_integrate_singlebottom_enable') == 'on') echo(et_get_option('nimble_integration_single_bottom')); ?>
		
	<?php 
		if ( et_get_option('nimble_468_enable') == 'on' ){
			if ( et_get_option('nimble_468_adsense') <> '' ) echo( et_get_option('nimble_468_adsense') );
			else { ?>
			   <a href="<?php echo esc_url(et_get_option('nimble_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('nimble_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
	<?php 	}    
		}
	?>
	
	<?php 
		if ( 'on' == et_get_option('nimble_show_postcomments') ) comments_template('', true);
	?>
<?php endwhile; // end of the loop. ?>