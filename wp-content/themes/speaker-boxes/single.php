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
			
	<!-- END g720 #tagline -->
	</div>

	<!-- END main -->
	</div>

<!-- END full-logo -->
</div>

<!-- FADE -->
<div class="fade"></div>

<div id="wrap-single">

<!-- BEGIN main -->
<div class="main">
	
	<!-- BEGIN g450 #content -->
	<div class="g550" id="single-left">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<!-- Get that image resized! -->
		<?php $thumb = get_post_thumbnail_id(); $image = vt_resize( $thumb,'' , 230, 200, true );
		$fullthumb = get_post_thumbnail_id();
 		$fullimg = vt_resize( $fullthumb, '', 600, true)
		?>
			
		<?php if ( $thumb) : ?>



				
		<?php else : ?><?php endif; ?>
				
			<!-- BEGIN g480 -->
			<div class="g480" id="content-right">
			
				<h2 class="title"><span><?php the_title(); ?></span></h2>
				
				<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
				
				<div class="g160" id="metadata">

					<ul class="meta">

						<li><?php wp_link_pages('before=<p>&after=</p>&next_or_number=number&pagelink=page %'); ?>
	</li>
						<li> <span>Author &nbsp;&ndash;&nbsp;</span>&nbsp;<?php the_author(); ?></li> 
						<li> <span>Date &nbsp;&ndash;&nbsp;</span>&nbsp;<?php the_time('F jS, Y') ?></li>
						<li> <span>Category &nbsp;&ndash;&nbsp;</span>&nbsp;<?php the_category(', ') ?></li>
						<li> <span>Tags &nbsp;&ndash;&nbsp;</span>&nbsp;<?php the_tags(''); ?></li>
						<li> <span>Comments &nbsp;&ndash;&nbsp;</span>&nbsp;<?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></li> 

					</ul>

				</div>
			
			<!-- END g480 -->
			</div>
			
			
			
			<div class="clear"></div>
				
			<!-- ALL THOSE COMMENTS -->
			<?php comments_template(); ?>
			
			</div>
			
			<!-- BEGIN g550 -->
			<div class="g550" id="single-right">
		
				<?php get_sidebar(); ?>

			<!-- END g550 -->
			</div>
	
	<!-- END g520 #left -->		
	</div>

<?php endwhile; else: ?>

<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

<br style="clear: both;">

<!-- END main -->
</div>

<!-- THE BOTTOM STUFF -->
<?php include (TEMPLATEPATH . '/framework/includes/bottom.php'); ?>

<?php get_footer(); ?>