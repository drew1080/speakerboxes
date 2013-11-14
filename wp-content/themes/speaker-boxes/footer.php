<!-- BEGIN full-footer -->
<div class="full-footer">

<!-- CLEAR -->
<div class="clear" style="height:1px;">&nbsp;</div>
	
	<!-- BEGIN main -->
	<div class="main">
	
		<!-- BEGIN back-top -->
		<p id="back-top" title="Back To Top" class="tip"> 
		
			<a href="#top">
		
				<!-- BACK TO TOP -->
				<span></span>
		
			</a>
		
		<!-- END back-top --> 
		</p>
	
		<?php if ( $footer = get_option('of_footer') ) { ?>
		
			<p class="footer"><?php echo stripslashes ( $footer) ; ?></p>
		
		<?php } else { ?>

		<!-- BEGIN footer -->
		<p class="footer"><?php bloginfo('name'); ?> is proudly powered by <a href="http://wordpress.org/">WordPress <?php bloginfo('version'); ?></a> <a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a> <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a>. <!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. --></p>
		
		<?php } ?>
	
	<!-- END main -->
	</div>
	
<!-- END full-footer -->	
</div>
		
<?php wp_footer(); ?>

<?php if ( $ga = get_option('of_ga_code') ) { ?>
<?php echo stripslashes( $ga ); ?> 
<?php } ?>

<!-- END body -->
</body>

<!-- END html -->
</html>