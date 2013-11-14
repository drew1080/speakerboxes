<?php
/*
Template Name: Projects
*/
?>
<?php get_header(); ?>
<!-- BEGIN full-logo -->
<div class="full-header" id="blog">

	<!-- BEGIN main -->
	<div class="main">
    <div class="page-title">
		  <h2><?php echo get_the_title(); ?></h2>
		</div>
		<div id="header-image">
			<a href="<?php echo home_url()?>"><img src="<?php echo get_template_directory_uri() . "/framework/images/speakerboxes-logo.png" ?>" /></a>
		</div>

	<!-- END .main -->
	</div>

<!-- END full-header -->
</div>
<div class="clear"></div>

<!-- BEGIN WRAP -->
<div id="wrap">
	
	<div class="nav">
		<?php wp_nav_menu(); ?>
	</div>
	
<!-- BEGIN full-middle -->
<div class="full-middle">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
		
	<?php endwhile; ?>

	<!-- CLEAR -->
	<div class="clear"></div>
	
	<!-- BEGIN about-people container -->
	<div id="projects">
		<!-- BEGIN isotope container -->
		<div id="goddard-schools" class="projects-wrapper">
			<div class="projects-title">
				<h2>GODDARD SCHOOLS</h2>
			</div>
			
			<div class="project-tiles">
			<?php 
				global $post; // required
				$args = array('post_type' => 'mpm-projects', 'category_name' => 'goddard-school', 'posts_per_page' => '-1', 'orderby' => 'menu_order', 'order' => 'asc');
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
			<!-- END project-tiles-->
			</div>
			<div class="clear"></div>
			<!--<div class="project-paging">
				TODO Add arrows
				<?php next_posts_link('<') ?>
				<?php previous_posts_link('>') ?>
			</div>-->
		<!-- END goddard-schools  container -->
		</div>
	
	<div id="business-residential" class="projects-wrapper">
		<div class="projects-title">
			<h2>THE MIXED BAG</h2>
		</div>
		
		<div class="project-tiles">
		<?php 
			global $post; // required
			$args = array('post_type' => 'mpm-projects', 'category_name' => 'business-residential', 'posts_per_page' => '-1', 'orderby' => 'menu_order', 'order' => 'asc');
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
		<!-- END project-tiles-->
		</div>
		
		<div class="clear"></div>
		<!--<div class="project-paging">
			TODO Add arrows
		</div>-->
	<!-- END business-residential  container -->
	</div>
	
	<!-- END container -->
	</div>
	
	
	<!-- CLEAR -->
	<div class="clear"></div>
	
	<?php else: ?>	
  
  	<p><?php _e('Sorry, nothing here.', 'fundamental'); ?></p>

	<?php endif; ?>

<!-- END full-middle -->
</div>

<br style="clear: both;">
<!-- END WRAP -->
</div>

<!-- BEGIN HEADER: must declare header after Isotope alters content -->
<?php include (TEMPLATEPATH . '/framework/includes/fixed-header.php'); ?>

<?php //get_footer(); ?>