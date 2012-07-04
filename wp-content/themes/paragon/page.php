<?php get_header(); ?>

<!-- BEGIN full-logo -->
<div class="full-header" id="port">
			
	<!-- BEGIN main -->
	<div class="main">

		<!-- BEGIN g720 #tagline -->
	<div class="g720" id="tagline-single">
		
		<?php if ( $onetop = get_option('of_one_top') ) { ?>
		
			<h1 class="tagline-option"><?php echo stripslashes( $onetop ) ?></h1> 
			
		<?php } ?>
			
		<?php if  ( !$onetop  ) { ?>
			
			<h1 class="tagline-option">You are reading this because you have no tagline!</h1> 
			
		<?php } ?>
			
		<?php if ( $onebottom = get_option('of_one_bottom') ) { ?>
			
			<p class="tag-bottom"><?php echo stripslashes( $onebottom ) ?></p>
			
		<?php } ?>
			
		<?php if  ( !$onebottom  ) { ?>
			
			<p class="tag-bottom">To solve this, go to Fundamental Options > Taglines & Portfolio</p>
			
		<?php } ?>
			
	<!-- END g720 #tagline -->
	</div>

	<!-- END main -->
	</div>

<!-- END full-logo -->
</div>

</div>

<!-- BEGIN main -->
<div class="main">
	
	<!-- BEGIN g450 #content -->
	<div class="g550" id="top-page">
	
	<h2 class="title"><span><?php the_title(); ?></span><span style="color:#ddd; font-size:14px;">//</span></h2>

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<!-- Get that image resized! -->
		<?php $thumb = get_post_thumbnail_id(); $image = vt_resize( $thumb,'' , 640, 240, true ); ?>
			
		<?php if ( $thumb) : ?>
				
		<img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" alt="image"/>
		
				
		<?php else : ?><?php endif; ?>
			
		<!-- BEGIN content-right -->	
		<div class="g480" id="content-right-single">

			<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
		
		<!-- END content-right -->	
		</div>
		
		<div class="clear"></div>
				
			<!-- ALL THOSE COMMENTS -->
			<?php comments_template(); ?>
	
	<!-- END g450 #content -->		
	</div>
	
	<!-- BEGIN single-right -->
	<div class="g550" id="single-right">
		
		<?php get_sidebar(); ?>

	<!-- END post #post -->
	</div>
	
	<!-- END g520 #left -->		
	</div>

<?php endwhile; else: ?>

<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

<!-- END main -->
</div>

<!-- THE BOTTOM STUFF -->
<?php include (TEMPLATEPATH . '/framework/includes/bottom.php'); ?>

<?php get_footer(); ?>