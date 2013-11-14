<!-- Necessary Script -->
<script type="text/javascript">
jQuery(document).ready(function($){
	$('.kwicks').kwicks({
	min: 20,
	max:700,
	sticky: false // Makes the first slide visible if true
	});
});
</script>

<!-- BEGIN accordian-wrap -->
<div id="accordion-wrap">

<!-- BEGIN ul.kwicks -->
<ul class="kwicks">

<!-- BEGIN Kwicks Slider 1 -->
<?php if ($img1 = get_option('of_accordion_slider_image_1')) { ?>

	<!-- BEGIN li -->
	<li>
	
			<a href="<?php if ($img1url = get_option('of_accordion_slider_image_1_url')) { ?><?php echo $img1url ?><?php } ?>">
		
			<?php if ($img1title = get_option('of_accordion_slider_image_1_title')) { ?>
    	
    			<h4 class="title-kwicks"><?php echo $img1title ?></h4>
    	
    		<?php } ?>
    
    		<!-- BEGIN Kwick Title -->
    		<?php if ($img1title = get_option('of_accordion_slider_image_1_title')) { ?>
    	
    		<?php if ($img1description = get_option('of_accordion_slider_image_1_description')) { ?>
    		
    			<p style="font-family:'Droid Sans' !important;"><?php echo $img1description ?></p>
    		
    		<?php } ?>
    	
    		<?php } ?>
    
    	
    		<!-- BEGIN Kwick Img -->
    		<?php if ($img1 = get_option('of_accordion_slider_image_1')) { ?>
    	
    		<img src="<?php bloginfo('template_directory'); ?>/framework/scripts/timthumb.php?src=<?php echo $img1; ?>&amp;h=290&amp;w=760&amp;zc=1" alt="<?php the_title(); ?>" width="760" height="290" />
    	
    		<!-- End Kwick Img -->
    		<?php } ?>
		
			<!-- Shadow -->
			<span class="shadow"></span>
		
			</a>
			
	<!-- END li -->
	</li>

<!-- END Kwick Slider 1 -->
<?php } ?>

<!-- BEGIN Kwicks Slider 2 -->
<?php if ($img2 = get_option('of_accordion_slider_image_2')) { ?>

		<!-- BEGIN li -->
		<li>
	
			<a href="<?php if ($img1url = get_option('of_accordion_slider_image_2_url')) { ?><?php echo $img1url ?><?php } ?>">
		
			<?php if ($img2title = get_option('of_accordion_slider_image_2_title')) { ?>
    	
    			<h4 class="title-kwicks"><?php echo $img2title ?></h4>
    	
    		<?php } ?>
    
    		<!-- BEGIN Kwick Title -->
    		<?php if ($img2title = get_option('of_accordion_slider_image_2_title')) { ?>
    	
    		<?php if ($img2description = get_option('of_accordion_slider_image_2_description')) { ?>
    		
    			<p style="font-family:'Droid Sans' !important;"><?php echo $img2description ?></p>
    		
    		<?php } ?>
    	
    		<?php } ?>
    
    		<!-- BEGIN Kwick Img -->
    		<?php if ($img2 = get_option('of_accordion_slider_image_2')) { ?>
    	
    		<img src="<?php bloginfo('template_directory'); ?>/framework/scripts/timthumb.php?src=<?php echo $img2; ?>&amp;h=290&amp;w=760&amp;zc=1" alt="<?php the_title(); ?>" width="760" height="290" />
    	
    		<!-- End Kwick Img -->
    		<?php } ?>
		
			<!-- Shadow -->
			<span class="shadow"></span>
		
			</a>
	
	<!-- END li -->
	</li>

<!-- END Kwick Slider 2 -->
<?php } ?>

<!-- BEGIN Kwicks Slider 3 -->
<?php if ($img3 = get_option('of_accordion_slider_image_3')) { ?>

		<!-- BEGIN li -->
		<li>
	
			<a href="<?php if ($img3url = get_option('of_accordion_slider_image_3_url')) { ?><?php echo $img1url ?><?php } ?>">
		
			<?php if ($img3title = get_option('of_accordion_slider_image_3_title')) { ?>
    	
    			<h4 class="title-kwicks"><?php echo $img3title ?></h4>
    	
    		<?php } ?>
    
    		<!-- BEGIN Kwick Title -->
    		<?php if ($img3title = get_option('of_accordion_slider_image_3_title')) { ?>
    	
    		<?php if ($img3description = get_option('of_accordion_slider_image_3_description')) { ?>
    		
    			<p style="font-family:'Droid Sans' !important;"><?php echo $img3description ?></p>
    		
    		<?php } ?>
    	
    		<?php } ?>
    
    		<!-- BEGIN Kwick Img -->
    		<?php if ($img3 = get_option('of_accordion_slider_image_3')) { ?>
    	
    		<img src="<?php bloginfo('template_directory'); ?>/framework/scripts/timthumb.php?src=<?php echo $img3; ?>&amp;h=290&amp;w=760&amp;zc=1" alt="<?php the_title(); ?>" width="760" height="290" />
    	
    		<!-- End Kwick Img -->
    		<?php } ?>
		
			<!-- Shadow -->
			<span class="shadow"></span>
		
			</a>
			
	<!-- END li -->
	</li>

<!-- END Kwick Slider 3 -->
<?php } ?>

<!-- BEGIN Kwicks Slider 4 -->
<?php if ($img4 = get_option('of_accordion_slider_image_4')) { ?>

	<!-- BEGIN li -->
	<li>
	
			<a href="<?php if ($img4url = get_option('of_accordion_slider_image_4_url')) { ?><?php echo $img1url ?><?php } ?>">
		
			<?php if ($img4title = get_option('of_accordion_slider_image_4_title')) { ?>
    	
    			<h4 class="title-kwicks"><?php echo $img4title ?></h4>
    	
    		<?php } ?>
    
    		<!-- BEGIN Kwick Title -->
    		<?php if ($img4title = get_option('of_accordion_slider_image_4_title')) { ?>
    	
    		<?php if ($img4description = get_option('of_accordion_slider_image_4_description')) { ?>
    		
    			<p style="font-family:'Droid Sans' !important;"><?php echo $img4description ?></p>
    		
    		<?php } ?>
    	
    		<?php } ?>
    
    		<!-- BEGIN Kwick Img -->
    		<?php if ($img4 = get_option('of_accordion_slider_image_4')) { ?>
    	
    		<img src="<?php bloginfo('template_directory'); ?>/framework/scripts/timthumb.php?src=<?php echo $img4; ?>&amp;h=290&amp;w=760&amp;zc=1" alt="<?php the_title(); ?>" width="760" height="290" />
    	
    		<!-- End Kwick Img -->
    		<?php } ?>
		
			<!-- Shadow -->
			<span class="shadow"></span>
		
			</a>
			
	<!-- END li -->
	</li>

<!-- END Kwick Slider 4 -->
<?php } ?>

<!-- BEGIN Kwicks Slider 5 -->
<?php if ($img5 = get_option('of_accordion_slider_image_5')) { ?>

	<!-- BEGIN li -->
	<li>
	
			<a href="<?php if ($img5url = get_option('of_accordion_slider_image_5_url')) { ?><?php echo $img1url ?><?php } ?>">
		
			<?php if ($img5title = get_option('of_accordion_slider_image_5_title')) { ?>
    	
    			<h4 class="title-kwicks"><?php echo $img5title ?></h4>
    	
    		<?php } ?>
    
    		<!-- BEGIN Kwick Title -->
    		<?php if ($img5title = get_option('of_accordion_slider_image_5_title')) { ?>
    	
    		<?php if ($img5description = get_option('of_accordion_slider_image_5_description')) { ?>
    		
    			<p style="font-family:'Droid Sans' !important;"><?php echo $img5description ?></p>
    		
    		<?php } ?>
    	
    		<?php } ?>
    
    		<!-- BEGIN Kwick Img -->
    		<?php if ($img5 = get_option('of_accordion_slider_image_5')) { ?>
    	
    		<img src="<?php bloginfo('template_directory'); ?>/framework/scripts/timthumb.php?src=<?php echo $img5; ?>&amp;h=290&amp;w=760&amp;zc=1" alt="<?php the_title(); ?>" width="760" height="290" />
    	
    		<!-- End Kwick Img -->
    		<?php } ?>
		
			</a>
			
	<!-- END li -->
	</li>

<!-- END Kwick Slider 5 -->
<?php } ?>

<!-- END ul -->
</ul>

<!-- END accordian-wrap -->
</div>