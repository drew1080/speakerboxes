<!-- Necessary Script -->
<script type="text/javascript">
jQuery(document).ready(function(){
 
 $('#slider3').anythingSlider({
   width        : 900,
   height       : 290,
   theme        : 'minimalist-square',
   startStopped : true,
  })
  .anythingSliderFx({
   // '.selector' : [ 'caption', 'distance/size', 'time', 'easing' ]
   // 'distance/size', 'time' and 'easing' are optional parameters
   '.caption-top'    : [ 'caption-Top', '50px' ],
   '.caption-right'  : [ 'caption-Right', '130px', '1000', 'easeOutBounce' ],
   '.caption-bottom' : [ 'caption-Bottom', '50px' ],
   '.caption-left'   : [ 'caption-Left', '130px', '1000', 'easeOutBounce' ]
  })
  /* add a close button (x) to the caption */
  .find('div[class*=caption]')
    .css({ position: 'absolute' })
    .prepend('<span class="close">x</span>')
    .find('.close').click(function(){
      var cap = $(this).parent(),
       ani = { bottom : -50 }; // bottom
      if (cap.is('.caption-top')) { ani = { top: -50 }; }
      if (cap.is('.caption-left')) { ani = { left: -150 }; }
      if (cap.is('.caption-right')) { ani = { right: -150 }; }
      cap.animate(ani, 400, function(){ cap.hide(); } );
    });
});
</script>

<!-- BEGIN AnythingSlider #1 -->
<ul id="slider3">

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
$slideCaption = get_post_meta($post->ID, 'siiimple_caption', true);
?>
    			
<?php $thumb = get_post_thumbnail_id(); $image = vt_resize( $thumb,'' , 900, 290, true ); ?>
        
        <?php if ( $video ) { ?>
    	
    	<li><object width="900" height="290"><param name="movie" value="<?php echo $video; ?>"><param name="allowFullScreen" value="true"><param name="allowscriptaccess" value="always"><embed src="<?php echo $video; ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="900" height="290"></object></li>
    	
    	<?php } elseif ( $vimeo ) { ?>
    	
    	<li><iframe src="<?php echo $vimeo; ?> " width="900" height="290"></iframe></li>
    	
    	<?php } else { ?>
			
		<!-- BEGIN img li -->
		<li>
		
		<a href="<?php the_permalink() ?>">   
           
		<img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" title="#caption<?php echo count($captions)-1; ?>" alt="<?php the_title_attribute(); ?>" />
		
		</a>
           
        <?php if(get_option('of_anything_caption') == 'true') : ?>
           
        <?php if ($slideCaption) { ?> 
           
        <div class="caption-right">
   	 	   
   	 	<?php echo $slideCaption ?>
   			
   		</div>
   			
   		<?php } ?>
           
        <?php endif; ?>
        
        <!-- END img li -->   
        </li>
           
        <?php } ?>
           
        <?php endwhile; endif; $wp_query = $tmp; ?>  
		
<!-- END AnythingSlider #1 -->
</ul> 

