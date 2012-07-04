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
			<a href="<?php echo home_url()?>"><img src="<?php echo get_template_directory_uri() . "/framework/images/david-cobb-murals-logo.png" ?>" /></a>
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
			<div class="transparent-border-wrapper about-content-outer-wrapper">
			  <div class="about-content-container">
				  <div class="about-content">
				    <div class="license-left">
  				    <?php the_post_thumbnail( array(225, 250) ); ?> 
    				  <h1>Artistic License</h1>
  				  </div>
  				  <div class="license-right">
  				    <ul>
    				    <li><h3>Name</h3></li>
    				    <li><h2>David Cobb</h2></li>
    				    <li><h3>Date of Birth</h3></li>
    				    <li><h2>September 3, 1981</h2></li>
    				    <li><h3>Hometown</h3></li>
    				    <li><h2>Houston, TX</h2></li>
    				    <li><h3>Currently</h3></li>
    				    <li><h2>Baltimore, MD</h2></li>
    				    <li><h3>Training</h3></li>
    				    <li><h2>BFA, Painting</h2></li>
    				    <li><h2 id="training-item">University of Houston 2005</h2></li>
    				    <li><h3>Favorite Muralist</h3></li>
    				    <li><h2>John Biggers</h2></li>
    				  </ul>
  				  </div>
				  <!-- END about-content-->
				  </div>
				  <!-- END about-content-container-->
				</div>
			<!-- END wrapper-->
			</div>

			<div class="right-content-container">
				<div class="right-content">
				  <h2>A Little Tid-bit</h2>
				  <?php
  				// Post ID for About Page
  				$post = get_post($page_id);
  				$content = $post->post_content;

  				// Show the content for the about page
  				echo $content;
  				?>
				</div>
			</div>
		</div>
		
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