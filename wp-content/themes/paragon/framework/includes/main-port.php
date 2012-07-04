<!-- BEGIN full-middle -->
<div class="full-middle-section">

	<!-- BEGIN main -->
	<div class="main">
	
	<!-- CLEAR -->
	<div class="clear"></div>
	
	<div class="hr"><div class="custom_hr_text"><span class="tip">Recent Portfolio Items</span></div></div>
	
	 <?php if ( $pmainnum = get_option('of_main_port_num') ) { ?>

		<?php query_posts( 'post_type=portfolio&posts_per_page='. $pmainnum .'&paged='.$paged); ?>

		<?php } else { ?>

		<?php query_posts( 'post_type=portfolio&posts_per_page=3'.'&paged='.$paged); ?>

		<?php } ?>
	
		<?php if (have_posts()) : $count=0; while (have_posts()) : the_post(); ?>
		
		<?php $count++; if($count==6){ $var='style="margin-right:0 !important"'; $count=0; } else { $var=""; } ?>  
		
		<!-- Get that image resized! -->
		<?php 
		$thumb = get_post_thumbnail_id(); 
 		$image = vt_resize( $thumb,'' , 125, 125, true );
 		?>
 		
 		<!-- Hover Options -->
		<?php $mosaic = get_option('of_mosaic'); ?>

		<!-- BEGIN mosaic block bar -->
		<div class="mosaic-block <?php echo $mosaic; ?>" <?php echo $var; ?>> 

			<!-- BEGIN post -->
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				
				<!-- BEGIN mosaic overlay -->
				<a class="mosaic-overlay" href="<?php the_permalink() ?>">
				
					<!-- BEGIN details -->
					<div class="details"> 
						
						<h4 class="title"><?php the_title(); ?></h4> 
						
						<p class="date"><?php the_time('F jS') ?></p>
							
					<!-- END details -->
					</div> 
				
				<!-- END mosaic-overlay -->
				</a>
				
				<!-- BEGIN mosaic backdrop -->
				<div class="mosaic-backdrop">
				
					<img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" alt="image"/>
				
				<!-- END mosaic-backdrop -->
				</div>
		
			<!-- END post -->
			</div>

			<!-- END mosaic block bar-->	
			</div>

	<?php endwhile; ?>
	
	<?php endif; ?>
	
	<!-- CLEAR -->
	<div class="clear" style="height:20px;"></div>

<!-- END main -->	
</div>
	
<!-- END FULL MIDDLE SECTION -->	
</div>