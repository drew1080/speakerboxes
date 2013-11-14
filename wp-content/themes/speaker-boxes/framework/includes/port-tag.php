<!-- BEGIN full-logo -->
<div class="full-header" id="port">
			
	<!-- BEGIN main -->
	<div class="main">

		<!-- BEGIN g720 #tagline -->
		<div class="g720" id="tagline">
		
			<?php if ( $porttop = get_option('of_port_top') ) { ?>
		
				<h1 class="tagline-option"><?php echo stripslashes( $porttop ) ?></h1> 
			
			<?php } ?>
			
			<?php if  ( !$porttop  ) { ?>
			
				<h1 class="tagline-option">You are reading this because you have no tagline!</h1> 
			
			<?php } ?>
			
			<?php if ( $portbottom = get_option('of_port_bottom') ) { ?>
			
				<p class="tag-bottom"><?php echo stripslashes( $portbottom ) ?></p>
			
			<?php } ?>
			
			<?php if  ( !$portbottom  ) { ?>
			
				<p class="tag-bottom">To solve this, go to Fundamental Options > Taglines & Portfolio</p>
			
			<?php } ?>
			
		<!-- END g720 #tagline -->
		</div>
	
	<!-- END main -->
	</div>

<!-- END full-logo -->
</div>

<div class="fade"></div>