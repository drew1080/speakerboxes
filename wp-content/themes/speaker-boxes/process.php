<?php
/*
Template Name: Process
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
<div class="full-middle" id="mural-process">
		
	<?php 
		global $post; // required
		$args = array('post_type' => 'mpm-process-steps', 'posts_per_page' => '-1', 'orderby' => 'menu_order', 'order' => 'asc');
		$custom_posts = get_posts($args);
		$post_counter = 0;
		foreach($custom_posts as $post) : setup_postdata($post);
		  
	?>	
    <!-- BEGIN post -->
    <div class="process-border-wrapper-step-<?php echo $post_counter; ?>">
		  <div class="process-step-default process-step-<?php echo $post_counter; ?>">
  		  <?php if (get_the_title($post->ID) != "") { ?>
  		    <div class="process-title">
      			<h2><?php the_title() ?></h2>
      		</div>
    		<?php } ?>
    		<div class="process-content-step-default process-content-step-<?php echo $post_counter; ?>">
    	    <?php the_content() ?>
    		</div>
  		</div>
		</div>

	<?php 
	  $post_counter ++;
	  endforeach; 
	?>

<!-- END full-middle -->
</div>

<div class="process-image">
  <img src="<?php echo get_template_directory_uri() . "/framework/images/bottle-of-paintbrushes.png" ?>" />
</div>


<br style="clear: both;">
<!-- END WRAP -->
</div>

<?php //get_footer(); ?>