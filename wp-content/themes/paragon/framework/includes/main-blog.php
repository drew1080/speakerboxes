<!-- BEGIN full-middle -->
<div class="full-middle-section">

	<!-- BEGIN main -->
	<div class="main">
	
	<!-- CLEAR -->
	<div class="clear"></div>
	
	<div class="hr"><div class="custom_hr_text"><span class="tip">Recent Blog Entries</span></div></div> 

		<?php $args=array( 'showposts' => 4 ); $my_query = new WP_Query($args); ?>
		
		<?php if ( $my_query->have_posts() ) { $count=0; while ($my_query->have_posts()) : $my_query->the_post(); ?> 
		
		<?php $count++; if($count==1){ $var='style="margin-left:0px !important"'; $count=0; } else { $var=""; } ?>  
		
		<!-- Get that image resized! -->
		<?php 
		$thumb = get_post_thumbnail_id(); 
 		$image = vt_resize( $thumb,'' , 200, 250, true );
 		$fullthumb = get_post_thumbnail_id();
 		$fullimg = vt_resize( $fullthumb, '', 600, true)
 		?>
 		
 		<!-- BEGIN text-wrap -->
 		<div class="text-wrap" <?php echo $var; ?>>
 		
 			<!-- BEGIN mosaic block bar -->
			<div class="mosaic-block circle"> 

				<!-- BEGIN post -->
				<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			
					<!-- BEGIN mosaic-overlay -->
					<a class="mosaic-overlay zoom" href="<?php echo $fullimg[url]; ?>">
				
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
	
		<?php $count++;  ?>
		<?php endwhile; } //while ($my_query) ?>
		<?php wp_reset_query() ?>
			
	<!-- END MAIN -->
	</div>

<!-- END FULL MIDDLE SECTION -->	
</div>