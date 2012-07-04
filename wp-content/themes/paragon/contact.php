<?php
/*
Template Name: Contact
*/
?>
<?php get_header(); ?>
<!-- BEGIN full-logo -->
<div class="full-header" id="blog">

	<!-- BEGIN main -->
	<div class="main">
		
		<div id="header-image">
			<a href="<?php echo home_url()?>"><img src="<?php echo "/mixedpaintmurals/wp-content/themes/paragon/framework/images/mixed-paint-murals-logo.png" ?>" /></a>
		</div>

	<!-- END .main -->
	</div>

<!-- END full-header -->
</div>

<div class="clear"></div>

<!-- BEGIN WRAP -->
<div id="wrap">
	
	<div class="nav">
		<?php wp_nav_menu(); ?>
	</div>
	
<!-- BEGIN full-middle -->
<div class="full-middle">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
		<div id="main">
			<h1 class="title"><span><?php the_title(); ?></span></h1>
		</div>
		
		<div class="content">
		<?php
		// Post ID for About Page
		$post = get_post($page_id);
		$content = $post->post_content;
		
		// Show the content for the about page
		echo $content;
		?>
		
		<p><strong>Email: </strong><a href="mailto:<?php echo antispambot(get_site_option('admin_email')); ?>"><?php echo antispambot(get_site_option('admin_email')); ?></a></p>
		</div>
		
	<?php endwhile; ?>
	
	<?php endif; ?>

<!-- END full-middle -->
</div>


<br style="clear: both;">
<!-- END WRAP -->
</div>

<?php get_footer(); ?>