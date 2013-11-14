<?php get_header(); ?>
<!-- BEGIN full-logo -->
<div class="full-header" id="blog">

	<!-- BEGIN main -->
	<div class="main">
		<div class="page-title">
		  <h2><?php echo in_category( '4' ) ? "Goddard Schools" : "The Mixed Bag"; ?></h2>
		</div>
		<div id="header-image">
			<a href="<?php echo home_url()?>"><img src="<?php echo get_template_directory_uri() . "/framework/images/david-cobb-murals-logo.png" ?>" /></a>
		</div>

	<!-- END .main -->
	</div>

<!-- END full-header -->
</div>

<div class="clear"></div>

<div id="wrap-single">
	
	<div class="nav">
		<?php wp_nav_menu(); ?>
	</div>
<div class="clear"></div>
<!-- BEGIN main -->
<div class="main">
	
	<!-- BEGIN project-content-->
	<div class="project-content">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="project-page-left">
			  <div id="project-large-images-paging-wrapper">
			
  			  <div class="project-large-images" >
    				<?php
				
    				$project_title = get_the_title($post->ID);
				
    				$images = get_children(array(
    					'post_parent'    => $post->ID,
    					'post_type'      => 'attachment',
    					'numberposts'    => -1, // show all
    					'post_status'    => null,
    					'post_mime_type' => 'image',
    					'orderby' => 'menu_order',
    					'order' => 'ASC', 
    				));
				
    				//echo '<div class="page_navigation"></div>';
				
    				if (count($images) > 0) {

    					// Show the first image
    					foreach ( $images as $image ) {
    					  $attachment   = get_post($image->ID);
    						$attimg   = wp_get_attachment_image($image->ID,'large');
    						$atturl   = wp_get_attachment_url($image->ID);
    						break;
    					}

    					echo '<div id="large-image-'. $image->ID .'" class="large-image-wrapper"><div class="large-project-image"><p>' . $attimg . '</p></div>';
    					echo '<div class="project-title" id="title-'. $image->ID .'">';
    					echo '<h3 class="title"><span>';
    		      echo in_category( '4' ) ? "Goddard School of&nbsp;" : "";
    		      echo the_title();
    		      echo '</span></h3>';
    			    echo '</div>';
    					echo '<div id="desc-'. $image->ID .'" class="image-description">' . $attachment->post_content . '</div></div>';

    					if (count($images) > 1) {
    						// Start loop for additional images and ready them for lightbox
    						//echo '<div style="display: none;">';
						
    						$image_counter = 0;
    						//  Add each subsequent image for lightbox gallery
    						foreach ( $images as $image ) {
    							if ($image_counter > 0) {
    							  $attachment   = get_post($image->ID);
    								$attimg   = wp_get_attachment_image($image->ID,'large');
    								$atturl   = wp_get_attachment_url($image->ID);

    								echo '<div id="large-image-'. $image->ID .'" class="large-image-wrapper"><div class="large-project-image"><p>' . $attimg . '</p></div>';
    								echo '<div class="project-title" id="title-'. $image->ID .'">';
    								echo '<h3 class="title"><span>';
          		      echo in_category( '4' ) ? "Goddard School of&nbsp;" : "";
          		      echo the_title();
          		      echo '</span></h3>';
          			    echo '</div>';
    							  echo '<div id="desc-'. $image->ID .'" class="image-description">' . $attachment->post_content . '</div></div>';
    							}
    							$image_counter++;
    						}

    						// Closing the hidden div
    						//echo '</div>';
    					}
    				}
    				?>
    			<!-- END project-large-images -->
    			</div>
			
    		  <div class="page_navigation"></div>
    		  <div class="clear"></div>
    		  
    		<!-- END project-large-images-paging-wrapper -->
  			</div>
  		<!-- END project-left -->
			</div>
			
			<div class="project-page-right">
			
			  <?php
			
  			$images = get_children(array(
  				'post_parent'    => $post->ID,
  				'post_type'      => 'attachment',
  				'numberposts'    => -1, // show all
  				'post_status'    => null,
  				'post_mime_type' => 'image',
  				'orderby' => 'menu_order',
  				'order' => 'ASC', 
  			));
			
  			if (count($images) > 0) {
				
  				// Starting HTML for images
  				echo '<div class="project-images scroll-pane">';

  				if (count($images) > 1) {
					
  					$image_counter = 0;
					
  					foreach ( $images as $image ) {
  						if ($image_counter > -1) {
  							$attimg   = wp_get_attachment_image($image->ID, array(76, 76));
  							$atturl   = wp_get_attachment_url($image->ID);

  							echo '<a href="#" id="' . $image->ID . '" longdesc="' . $image_counter . '" 
  							onclick="changeProjectDisplayImage(this, '. $image->ID .')"
  							class="project-image">'.$attimg.'</a>';
  						}
  						$image_counter++;
  					}
  				}

  				// Closing the images_auto div
  				echo '</div>';
  			}
  			?>
			
			  <div class="clear"></div>
  			<div id="related-projects-list" class="related-projects">
  			  <h3><?php echo in_category( '4' ) ? "OTHER GODDARD PROJECTS" : "MORE IN THE MIXED BAG"; ?></h3>
			  
  				<ul class="related-projects-content">
  				  <?php 
  				    global $post; // required
  				    $currect_category = in_category( '4' ) ? "goddard-school" : "business-residential";
      				$args = array(
      				  'post_type' => 'mpm-projects', 
      				  'category_name' => $currect_category, 
      				  'posts_per_page' => '-1', 
      				  'orderby' => 'menu_order', 
      				  'order' => 'asc',
      					'post__not_in' => array($post->ID));
      				$custom_posts = get_posts($args);
      				foreach($custom_posts as $post) : setup_postdata($post);
            ?>
              <li>
                <a href="<?php echo get_permalink() ?>"><?php the_post_thumbnail( array(100,100) ) ?></a>
              </li>
            <?php endforeach; ?>
  				</ul>
  				<div class="clear"></div>
  				<div class="page_navigation"></div>
  			</div>
  		<!-- END project-right -->
			</div>
			

		<?php endwhile; ?>

		<?php endif; ?>
		
	<!-- END project-content -->
	</div>

<!-- END main -->
</div>


<?php //get_footer(); ?>