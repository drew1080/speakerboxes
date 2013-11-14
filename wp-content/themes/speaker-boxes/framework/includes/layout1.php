<!-- This layout uses Images 210x150px and only shows images -->

<!-- BEGIN full-middle -->
<div class="full-middle" id="layout1">

	<!-- BEGIN main -->
	<div class="main">

		<!-- BEGIN the famous WP Loop -->
		<?php if (have_posts()) : ?>

		<?php $count=0; while (have_posts()) : the_post(); ?>
		
		<!-- BEGIN the margin script -->
		<?php $count++; if($count==4){ $var='style="margin-right:0px !important;"'; $count=0; } else { $var=""; } ?>
		
		<!-- Get that image resized! -->
		<?php 
		$thumb = get_post_thumbnail_id(); 
 		$image = vt_resize( $thumb,'' , 210, 210, true );
 		?>
 		
 		<?php if ( $thumb ) : ?>
 		
 		<!-- Hover Options -->
		<?php $mosaic = get_option('of_mosaic'); ?>
	
		<!-- BEGIN mosaic block bar -->
		<div class="mosaic-block <?php echo $mosaic; ?>" <?php echo $var; ?>> 

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
		
		<?php else : ?>
		
		<!-- BEGIN no-mosaic-block -->
		<div class="no-mosaic-block" <?php echo $var; ?>>
		
			<!-- BEGIN post -->
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>"> 
			
				<!-- BEGIN details -->
				<div class="details"> 
						
					<h4 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4> 
						
					<p class="date"><?php the_time('F jS, Y') ?></p>
			
					<?php wpe_excerpt('wpe_excerptlength_index', 'wpe_excerptmore'); ?>
							
				<!-- END details -->
				</div>
				
			<!-- END post -->
			</div>
			
		<!-- END mosaic block bar-->	
		</div>
		
		<?php endif; ?>

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
		<?php if (function_exists("pagination")) { pagination($additional_loop->max_num_pages);  ?>
    
    	<?php  } else { ?>
    
    	<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				
			<div id="nav-below" class="navigation">
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'paragon' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'paragon' ) ); ?></div>
			</div><!-- #nav-below -->

		<?php endif; ?>

		<?php } ?>

	<!-- END main -->
	</div>

<!-- END pagination-->
</div>

<!-- BEGIN else -->
<?php else : ?>

<h2>It seems you're new here.</h2>
<p>Not to worry.  First thing to do it go add a post...</p>

<?php endif; ?>