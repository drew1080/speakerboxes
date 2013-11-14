<?php
/*
Template Name: Home Theme
*/
?>
<?php get_header(); ?>

<!-- BEGIN WRAP -->
<div id="wrap">
	
<!-- BEGIN full-middle -->
<div class="full-middle">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
		<div id="main">
			<hr/>
			<h1 class="title"><span><?php the_title(); ?> ID/Lab</span></h1>
		</div>
		
		<?php
		// Post ID for About Page
		$post = get_post($page_id);
		$content = $post->post_content;
		
		// Show the content for the about page
		echo $content;
		?>
		
	<?php endwhile; ?>
	<!-- CLEAR -->
	<div class="clear"></div>
	
	<!-- BEGIN about-people container -->
	<div id="about-people">
		<hr/>
		<!-- BEGIN isotope container -->
		<div id="idlab-isotope-container">
			<?php 
				global $post; // required
				$args = array('post_type' => 'idlab_people', 'posts_per_page' => '-1', 'orderby' => 'menu_order', 'order' => 'asc');
				$custom_posts = get_posts($args);
				foreach($custom_posts as $post) : setup_postdata($post);
			?>	
	
				<!-- BEGIN the margin script -->
				<?php $count++; if($count==2){ $var='style="margin-right:0px;"'; $count=0; } else { $var=""; } ?>
		
				<!-- Get that image resized! -->
				<?php 
				$thumb = get_post_thumbnail_id(); 
				// This controls the image size inside the thumbnail
				if ( $thumb ) {
					$image = vt_resize( $thumb,'' , 226, 130, true );
				} else {
					// Use the default image
					$image = array (
						'url' => get_template_directory_uri() .'/framework/images/post-placeholder-image.jpg',
						'width' => 226,
						'height' => 130
					);
				}
		 		?>

				<!-- Adding link to entire post-->
				<a class="post-link" href="<?php the_permalink() ?>">
					<!-- BEGIN post-tile-->	
					<div class="post-tile" > 

						<!-- BEGIN post -->
						<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
					
							<div class="tile-image">
								<img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" alt="image"/>
							</div>
							<div class="tile-footer">
								<h4 class="title"><?php the_title(); ?></h4> 
							</div>

						<!-- END post -->
						</div>
		
					<!-- END post-tile-->	
					</div>
				<!-- END linking entire post -->
				</a>
		
			<?php endforeach; ?>
		<!-- END isotope container -->
		</div>
	<!-- END about-people container -->
	</div>
	
	
	<!-- CLEAR -->
	<div class="clear"></div>
	
	<?php else: ?>	
  
  	<p><?php _e('Sorry, nothing here.', 'fundamental'); ?></p>

	<?php endif; ?>

<!-- END full-middle -->
</div>

<!-- BEGIN rsidebar -->
<div id="rsidebar" class="fixed-right-sidebar">
	<hr/>
	<?php
	$args = array(
	    'menu'            => 'Main Menu',
	    'walker'          => new IDLab_Nav_Menu
	);
	wp_nav_menu($args);
	?>

<!-- END rsidebar -->
</div>	

<div id="relative-footer" class="relative-right-footer">
	<p>&copy; ID/Lab 2011</p>
</div>

<br style="clear: both;">
<!-- END WRAP -->
</div>




<!-- BEGIN HEADER: must declare header after Isotope alters content -->
<?php include (TEMPLATEPATH . '/framework/includes/fixed-header.php'); ?>

<?php get_footer(); ?>