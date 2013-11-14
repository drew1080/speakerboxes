<!-- BEGIN choose slider -->
<?php $slider = get_option('of_slider'); ?>
	
<?php if ( $slider == 'nivo' ) { 
	
	include (TEMPLATEPATH . '/framework/includes/nivo.php');	
	
	} elseif ( $slider == 'anything' ) {
	
	include (TEMPLATEPATH . '/framework/includes/anythingSlider.php');
	
	} elseif ( $slider == 'kwicks' ) {
	
	include (TEMPLATEPATH . '/framework/includes/kwicks.php');
	
	} else {
	
	include (TEMPLATEPATH . '/framework/includes/demo-slider.php');
	
	}
	
	?>
	
<!-- END choose slider -->
<div class="shadow">&nbsp;</div>

	<!-- END main -->
	</div>

<!-- END main -->
</div>
