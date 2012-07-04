<!DOCTYPE html>

<!-- BEGIN html -->
<html <?php language_attributes(); ?>>

<!-- A Siiimple design by Justin Young (http://justinyoung.me) - Proudly powered by WordPress (http://wordpress.org) -->

<!-- BEGIN head -->
<head>

	<!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	
	<!-- Favicon -->
	<?php if ( $favicon = get_option('of_custom_favicon') ) { ?>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
	<?php }else { ?>
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/framework/images/favicon.ico" type="image/x-icon" />
	<?php }?>
	
	<!-- Title -->
	<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
	
	<!-- Stylesheets -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/framework/styles/<?php echo get_option('of_alt_stylesheet'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/framework/css/nivo-slider.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/framework/css/anythingslider.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/framework/css/theme-minimalist-square.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/framework/css/kwicks.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/framework/css/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/framework/css/superfish.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/framework/css/tweetrotator.css" type="text/css" media="screen" />
	<link href="http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold" rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Lobster+Two:regular,regularitalic" rel="stylesheet" type="text/css">

	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/framework/css/mixed-paint-murals.css" type="text/css" media="screen" />
	
	<!--[if IE]>
	<link href="<?php bloginfo('template_url'); ?>/framework/css/ie.css" rel="stylesheet" type="text/css">
	<![endif]-->
	
	<!-- RSS & Pingbacks -->
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?> RSS Feed" href="<?php if (get_option('of_feedburner')) { echo get_option('of_feedburner'); } else { bloginfo( 'rss2_url' ); } ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<!-- Style Options -->
	<?php include (TEMPLATEPATH . '/framework/styles/style-options.php'); ?>
	
	<!-- Load Fonts -->
	<?php include (TEMPLATEPATH . '/framework/fonts/fonts.php'); ?>
	
	<!-- Theme Hook -->
	<?php wp_head(); ?>
	
	<!--Mixed Paint Mural scripts-->
  
	<!--<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/framework/css/jquery.ad-gallery.css">-->
	
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/framework/css/jquery.jscrollpane.css">
	
	<!--<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/framework/css/smartpaginator.css">-->
	
	<link href='http://fonts.googleapis.com/css?family=Karla:400,700' rel='stylesheet' type='text/css'>
	
	<link href='http://fonts.googleapis.com/css?family=Arvo:400,700,400italic' rel='stylesheet' type='text/css'>
	
	<link href='http://fonts.googleapis.com/css?family=Pontano+Sans' rel='stylesheet' type='text/css'>
	
	<link href='http://fonts.googleapis.com/css?family=Lustria' rel='stylesheet' type='text/css'>
	
	<link href='http://fonts.googleapis.com/css?family=Bitter:400,700' rel='stylesheet' type='text/css'>
	
<!-- END head -->
</head>
	
<!-- BEGIN body -->
<body <?php body_class(); ?>>
	
