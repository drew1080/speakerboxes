<!-- This layout uses Images 300x150px and only shows images with a left sidebar -->

<!-- BEGIN full-middle -->
<div class="full-middle">

	<!-- BEGIN main -->
	<div class="main">
	
		<!-- BEGIN g240 rsidebar -->
		<div class="g240" id="lsidebar-wide" style="width:160px; float:left;">
			
			<?php get_sidebar(); ?>
		
		<!-- END g240 rsidebar -->
		</div>

		<!-- BEGIN the famous WP Loop -->
		<?php if (have_posts()) : ?>

		<?php $count=0; while (have_posts()) : the_post(); ?>
		
		<!-- BEGIN the margin script -->
		<?php $count++; if($count==2){ $var='style="margin-left:0px;"'; $count=0; } else { $var=""; } ?>
		
		<!-- Get that image resized! -->
		<?php 
		$thumb = get_post_thumbnail_id(); 
 		$image = vt_resize( $thumb,'' , 300, 150, true );
 		?>
 		
 		<!-- Hover Options -->
		<?php $mosaic = get_option('of_mosaic'); ?>
	
		<!-- BEGIN mosaic block bar -->
		<div class="mosaic-block-wide-lsidebar <?php echo $mosaic; ?>" > 

			<!-- BEGIN post -->
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				
				<!-- BEGIN mosaic-overlay -->
				<a class="mosaic-overlay" href="<?php the_permalink() ?>">
				
					<!-- BEGIN details -->
					<div class="details"> 
						
						<h4 class="title"><?php the_title(); ?></h4> 
						
						<p class="date"><?php the_time('F jS, Y') ?></p>
							
					<!-- END details -->
					</div> 
				
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

	<!-- END loop -->
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

<!-- BEGIN else -->
<?php else : ?>

<h2>Not Found</h2>
<p>Sorry, but you are looking for something that isn't here.</p>
<?php get_search_form(); ?>

<?php endif; ?>