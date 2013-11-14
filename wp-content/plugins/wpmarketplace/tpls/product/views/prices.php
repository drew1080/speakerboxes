<?php
global $current_user;
 
$discount = $discount[$current_user->roles[0]];
$link = home_url("/?wpmarketplace=".$post->post_name);
global $post;

 
$prices = "<div id='wpmp-price-area'>".wpmp_html::product_price()."</div><script>jQuery('#wpmp-price-area select').selectpicker();</script>";