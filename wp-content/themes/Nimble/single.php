<?php get_header(); ?>

<?php $et_full_post = get_post_meta( $post->ID, '_et_full_post', true ); ?>

<div id="main-area">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php get_template_part('loop', 'single'); ?>
			</div> <!-- end #left-area -->
			
			<?php get_sidebar(); ?>
			
		</div> <!-- end #content-area -->
	</div> <!-- end .container -->
</div> <!-- end #main-area -->
	
<?php get_footer(); ?>