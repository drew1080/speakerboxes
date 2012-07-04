<!-- BEGIN full-logo -->
<div class="full-header">
			
<!-- BEGIN main -->
<div class="main">

	<!-- BEGIN g720 #tagline -->
	<div class="g720" id="tagline">
		
		<?php if ( $onetop = get_option('of_one_top') ) { ?>
		
			<h1 class="tagline-option"><?php echo stripslashes( $onetop ) ?></h1> 
			
		<?php } ?>
			
		<?php if  ( !$onetop  ) { ?>
			
			<h1 class="tagline-option">You are reading this because you have no tagline!</h1> 
			
		<?php } ?>
			
		<?php if ( $onebottom = get_option('of_one_bottom') ) { ?>
			
			<p class="tag-bottom"><?php echo stripslashes( $onebottom ) ?></p>
			
		<?php } ?>
			
		<?php if  ( !$onebottom  ) { ?>
			
			<p class="tag-bottom">To solve this, go to Fundamental Options > Taglines & Portfolio</p>
			
		<?php } ?>
			
	<!-- END g720 #tagline -->
	</div>
	

		

