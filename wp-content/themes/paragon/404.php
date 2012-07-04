<?php get_header(); ?>

<!-- BEGIN full-logo -->
<div class="full-header" style="height:130px;">

	<!-- BEGIN main -->
	<div class="main">
			
		<!-- BEGIN g720 #tagline-->
		<div class="g720" id="tagline">
		
			<?php if ( $onetop = get_option('of_one_top') ) { ?>
		
			<h1 class="tagline-option"><?php echo stripslashes( $onetop ) ?></h1> 
			
			<?php } ?>
			
			<?php if ( $onebottom = get_option('of_one_bottom') ) { ?>
			
			<p class="tag-bottom"><?php echo stripslashes( $onebottom ) ?></p>
			
			<?php } ?>
			
		<!-- END g720 #tagline -->
		</div>
		
	<div class="clear" style="height:50px;"></div>

	<p class="error">Whoops!  404!  Nothing here.</p>
	
	<!-- END main -->
	</div>

<!-- END full-tag -->
</div>

<div class="clear" style="height:250px;"></div>
	
<!-- THE BOTTOM STUFF -->
<?php include (TEMPLATEPATH . '/framework/includes/bottom.php'); ?>

<?php get_footer(); ?>