<?php get_header(); ?>

<?php if (have_posts()) : ?>

<!-- BEGIN full-logo -->
<div class="full-header" id="archive">

	<!-- BEGIN main -->
	<div class="main">
			
		<!-- BEGIN g720 #tagline-->
		<div class="g720" id="tagline">
		 
			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
			<?php /* If this is a category archive */ if (is_category()) { ?>
			<h1 class="tagline-option">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h1>
			<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
			<h1 class="tagline-option">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h1>
			<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h1 class="tagline-option">Archive for <?php the_time('F jS, Y'); ?></h1>
			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h1 class="tagline-option">Archive for <?php the_time('F, Y'); ?></h1>
			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h1 class="tagline-option">Archive for <?php the_time('Y'); ?></h1>
			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<h1 class="tagline-option">Author Archive</h1>
			<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h1 class="tagline-option">Blog Archives</h1>
			<?php } ?>
			
		<!-- END g720 #tagline -->
		</div>
	
	<!-- END main -->
	</div>

<!-- END full-tag -->
</div>

<!-- FADE -->
<div class="fade"></div>

<!-- BEGIN full-middle -->
<div class="full-middle">
		
	<!-- BEGIN main -->
	<div class="main">

		<?php $count=0; while (have_posts()) : the_post(); ?>
		
		<!-- BEGIN the margin script -->
		<?php $count++; if($count==4){ $var='style="margin-right:0px !important;"'; $count=0; } else { $var=""; } ?>
		
		<!-- Get that image resized! -->
		<?php 
		$thumb = get_post_thumbnail_id(); 
 		$image = vt_resize( $thumb,'' , 200, 250, true );
 		?>
 		
 		<!-- BEGIN text-wrap -->
 		<div class="text-wrap" <?php echo $var; ?>>
 		
 		<!-- BEGIN mosaic block bar -->
		<div class="mosaic-block circle">
		
			<!-- BEGIN post -->
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				
				<!-- BEGIN mosaic-overlay -->
				<a class="mosaic-overlay" id="zoom" href="<?php echo $fullimg[url]; ?>" width="<?php echo $fullimg[width]; ?>" height="<?php echo $fullimg[height]; ?>" alt="image" rel="gallery">
				&nbsp;
				
				<!-- END mosaic-overlay -->
				</a>				
			
				<!-- BEGIN mosaic-backdrop -->
				<div class="mosaic-backdrop">
				
					<img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" alt="image"/>
				
				<!-- END mosaic-backdrop -->
				</div>
					
			<!-- END post -->
			</div>
		
		<!-- END mosaic block bar-->	
		</div>

		<!-- CLEAR -->
		<div class="clear" style="height:1px;"></div>
			
		<h4 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4> 
						
		<p class="date"><?php the_time('F jS, Y') ?></p>
			
		<?php wpe_excerpt('wpe_excerptlength_index', 'wpe_excerptmore'); ?>
		
	<!-- END text-wrap -->		
	</div>

		<?php endwhile; ?>

	<!-- END main -->
	</div>

<!-- END full-middle -->
</div>

<!-- CLEAR -->
<div class="clear" style="height:60px;"></div>

<!-- BEGIN full-pagination -->
<div class="full-pagination">

	<!-- BEGIN main -->
	<div class="main">

		<!-- BEGIN pagination -->
		<?php if (function_exists("pagination")) { pagination($additional_loop->max_num_pages); } ?>

	<!-- END main -->
	</div>

<!-- END pagination-->
</div>
		
	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2>Sorry, but there aren't any posts with this date.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
		} else {
			echo("<h2>No posts found.</h2>");
		}
		get_search_form();

	endif;
?>

</div>

<!-- THE BOTTOM STUFF -->
<?php include (TEMPLATEPATH . '/framework/includes/bottom.php'); ?>

<?php get_footer(); ?>