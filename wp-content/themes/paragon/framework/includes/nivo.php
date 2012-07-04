<script type="text/javascript">
jQuery(document).ready(function($){
	$('#slider').nivoSlider({
		effect:'<?php if ($nivoeff = get_option('of_nivo_slider_eff')) : ?><?php echo $nivoeff ?><?php else : ?>random<?php endif; ?>',
		slices:<?php if ($nivoslices = get_option('of_nivo_slider_slices')) : ?><?php echo $nivoslices ?><?php else : ?>15<?php endif; ?>,
		animSpeed:<?php if ($nivospeed = get_option('of_nivo_slider_speed')) : ?><?php echo $nivospeed ?><?php else : ?>500<?php endif; ?>,
		pauseTime:<?php if ($nivotime = get_option('of_nivo_slider_time')) : ?><?php echo $nivotime ?><?php else : ?>500<?php endif; ?>,
		directionNavHide:false,
		pauseOnHover:true,
		captionOpacity:1.0
	});
});
</script>

<div class="main-wrap" style="width:100%">

	<div id="slider-wrap" class="main">
	
		<div id="slider-container">
		
		<?php if(get_option('of_nivo_slider_caption') == 'true') : ?>
  
  			<div id="slider" class="nivoSlider">
    			
    			<?php
    			$captions = array();
    			$tmp = $wp_query;
    			$wp_query = new WP_Query('post_type=slide');
    			if($wp_query->have_posts()) :
        		while($wp_query->have_posts()) :
            	$wp_query->the_post();
            	$captions[] = get_the_title($post->ID);
            	$video = get_post_meta($post->ID, 'siiimple_video', true);
				$vimeo = get_post_meta($post->ID, 'siiimple_vimeo', true);
    			?>
    			
    			<?php $thumb = get_post_thumbnail_id(); $image = vt_resize( $thumb,'' , 940, 290, true ); ?>
    			
    			<?php if ( $thumb ) { ?>
    	
 				<a href="<?php the_permalink(); ?>">
        		<img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" class="" title="#caption<?php echo count($captions)-1; ?>" alt="<?php the_title_attribute(); ?>" />
        		</a>
    	
    			<?php } elseif ($vimeo) { ?>
    			
    			<object width="740" height="290"><param name="movie" value="<?php echo $video; ?>"><param name="allowFullScreen" value="true"><param name="allowscriptaccess" value="always"><embed src="<?php echo $video; ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="940" height="290"></object>
    			
    			<?php } ?>
    
    			<?php endwhile; endif; $wp_query = $tmp; ?>

			</div><!-- close #slider -->

			<?php foreach($captions as $key => $caption) : ?>
    
    		<div id="caption<?php echo $key; ?>" class="nivo-html-caption">
        
        	<?php echo $caption; ?>
    			
    		</div><!-- end caption -->

			<?php endforeach; ?>
				
			<?php endif; ?>
			
			<?php if(get_option('of_nivo_slider_caption') == 'false') : ?>
    
    			<div id="slider" class="nivoSlider">
    			
    			<?php
    			$captions = array();
    			$tmp = $wp_query;
    			$wp_query = new WP_Query('post_type=slide');
    			if($wp_query->have_posts()) :
        		while($wp_query->have_posts()) :
            	$wp_query->the_post();
            	$captions[] = get_the_title($post->ID);
    			?>
    			
    			<?php $thumb = get_post_thumbnail_id(); $image = vt_resize( $thumb,'' , 940, 290, true ); ?>
        		<a href="<?php the_permalink(); ?>"><img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" class="" title="" alt="" />
        		</a>
    
    			<?php endwhile; endif; $wp_query = $tmp; ?>

			</div><!-- close #slider -->

		<?php endif; ?>

		</div><!-- slider-container -->
		
	</div><!-- end slider wrap -->
	
</div><!-- end main-wrap -->