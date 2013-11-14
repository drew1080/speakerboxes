<?php
/*
Template Name: About
*/
?>
<?php get_header(); ?>
<!-- BEGIN full-logo -->
<div class="full-header" id="blog">

	<!-- BEGIN main -->
	<div class="main">
		<div class="page-title">
		  <h2><?php echo get_the_title(); ?></h2>
		</div>
		<div id="header-image">
			<a href="<?php echo home_url()?>"><img src="<?php echo get_template_directory_uri() . "/framework/images/speakerboxes-logo.png" ?>" /></a>
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
		
		<div class="content">
		
    <div class="contact-content-container">
      <div class="contact-content">
        <h2>FIND OUT MORE</h2>
        <p><strong>Email: </strong><a href="mailto:<?php echo antispambot(get_site_option('admin_email')); ?>"><?php echo antispambot(get_site_option('admin_email')); ?></a></p>
      <?php
        // Post ID for Contact Page
        // MPM TODO get by name
        $page_id = 42;
        $page_data = get_page( $page_id );
        $content = apply_filters( 'the_content', $page_data->post_content );
  
        // Show the content for the about page
        echo $content;
        ?>
      </div>
    </div>
	</div>
		
	<?php endwhile; ?>
	
	<?php endif; ?>

<!-- END full-middle -->
</div>


<br style="clear: both;">
<!-- END WRAP -->
</div>

<?php //get_footer(); ?>