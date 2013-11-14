<!-- BEGIN full-about-section -->
<div class="full-about-section">

<!-- BEGIN main -->
<div class="main">

	<!-- BEGIN g160 -->
	<div class="g160" id="left-home" style="height:100px;">
		
			<?php if ( $maintop = get_option('of_main_top') ) { ?>
		
				<h1 class="title"><?php if ( $tip1 = get_option('of_tip1') ) { ?><span title="<?php echo stripslashes( $tip1 ) ?>"  class="tip"><?php } ?><?php echo stripslashes( $maintop ) ?></span></h1>
			
			<?php } else { ?>
		
				<h1 class="title"><?php if ( $tip2 = get_option('of_tip1') ) { ?><span title="<?php echo stripslashes( $tip2 ) ?>" style="margin-left:15px;" class="tip"><?php } ?>About Myself</span></h1>
			
			<?php } ?>
			
			<?php if ( $maintopcontent = get_option('of_main_top_content') ) { ?>
			
				<p class="home-sub-text"><?php echo stripslashes( $maintopcontent ) ?></p>
			
			<?php } else { ?>
			
				<p class="home-sub-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in libero lorem. Maecenas porta, libero vitae congue porta...</p>
			
			<?php } ?>
			
			
			<?php if ( $maintopread = get_option('of_main_top_read') ) { ?>
			
			<p class="visit-blog"><?php if ( $url = get_option('of_main_top_read_url') ) { ?><a href="<?php echo stripslashes( $url ) ?>"><?php echo stripslashes( $maintopread ) ?></a><?php } else { ?><?php echo stripslashes( $maintopread ) ?><?php } ?></p>
			
			<?php } else { ?>
		
			<p class="visit-blog">Read More</p>
			
			<?php } ?>
		
	<!-- END g160 -->
	</div>

	<!-- BEGIN g160 -->
	<div class="g160" id="home-one">
	
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home I') ) : ?>
					
			<h3 class="sidebar"><?php _e('Sample Text', 'fundamental'); ?></h3>
				
			<p><?php _e('Hello.  Just a brief message here...You can change this in the widget area...', 'fundamental'); ?></p>

			<?php endif; ?>	
	
	<!-- END g160 -->
	</div>
		
	<!-- BEGIN g160 -->
	<div class="g160" id="home-two">
	
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home II') ) : ?>
					
			<h3 class="sidebar"><?php _e('Sample Text', 'fundamental'); ?></h3>
				
			<p><?php _e('Hello.  Just a brief message here...You can change this in the widget area...', 'fundamental'); ?></p>

			<?php endif; ?>	
	
	<!-- END g160 -->
	</div>
		
		<!-- BEGIN g160 -->
		<div class="g160" id="home-three" style="margin-right:0">
	
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home III') ) : ?>
					
			<h3 class="sidebar"><?php _e('Sample Text', 'fundamental'); ?></h3>
				
			<p><?php _e('Hello.  Just a brief message here...You can change this in the widget area...', 'fundamental'); ?></p>

			<?php endif; ?>	
	
	<!-- END g160 -->
	</div>
	
	<!-- CLEAR -->
	<div class="clear"></div>

	<!-- hr -->
	<div class="hr">&nbsp;</div>

	<!-- END main -->
	</div>

<!-- END full-about-section -->
</div>