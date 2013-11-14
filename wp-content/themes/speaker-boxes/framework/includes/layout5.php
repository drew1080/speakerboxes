<!-- BEGIN full-middle -->
<div class="full-middle" id="layout5-home">

	<!-- BEGIN main -->
	<div class="main">
		
		<div class="slideshow homepage-tiles" >
		
			<?php

			$query_images_args = array(
			    'post_type' => 'attachment', 
					'post_mime_type' =>'image', 
					'post_status' => 'inherit', 
					'posts_per_page' => -1,
					'category_name' => 'goddard-school',
			);

			$query_images = new WP_Query( $query_images_args );
		
			$image_counter = 0; 
			
			foreach ( $query_images->posts as $image ) {
				$attimg   = wp_get_attachment_image($image->ID,'full');
				$atturl   = wp_get_attachment_url($image->ID);
				
				//echo '<div>' . $attimg . '</div>';
				echo $attimg;
			
				/*if ( $image_counter == 0 ) {
					//echo '<div style="float:left; margin-right: 10px"><a href="'.$atturl.'" id="first-cuteness" class="first">'.$attimg.'</a></div>';
					echo $attimg;
				} else {
					echo $attimg;
				}*/
			
				$image_counter++;
			}

			?>
			
			
		<!-- END slidehow-->
	  </div>
	
	<!-- END main -->
	</div>
	
	<div class="homepage-right">
	  <!-- BEGIN welcome-text -->
  	<div class="welcome-text">
  
  	  <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Blog I') ) : ?>

  		<h2 class="title"><span><?php _e('Sample Text', 'paragon'); ?></span><span style="color:#ddd; font-size:14px;">//</span></h2>

  		<p><?php _e('Hello.  Just a brief message here...You can change this in the widget area...', 'fundamental'); ?></p>

  	  <?php endif; ?>
  
  	<!-- END welcome-text -->
  	</div>
	</div>

<!-- END full-middle -->
</div>