<!--<link href="<?php //echo plugins_url('/marketplace/css/tablestyle.css');?>" type="text/css" rel="stylesheet" />-->
<div id="wraper">
<nav>
<ul>
<li><a href='<?php the_permalink();?>'><?php echo __("Dashboard","wpmarketplace");?></a></li>
<li><a href='<?php the_permalink();?>'><?php echo __("My Products","wpmarketplace");?></a></li>
<li><a href='<?php the_permalink();?><?php echo $concat;?>task=new'><?php echo __("Add new Product","wpmarketplace");?></a></li>
<?php do_action('wpmp-user-menu'); ?>
<li><a href='<?php the_permalink();?><?php echo $concat;?>task=earnings'><?php echo __("My Earnings","wpmarketplace");?></a></li>
<li><a href='<?php the_permalink();?><?php echo $concat;?>task=profile'><?php echo __("Edit Profile","wpmarketplace");?></a></li>
</ul>
</nav>