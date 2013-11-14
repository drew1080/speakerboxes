<?php 
$uiu = WPMP_IMAGE_URL;
$pluginurl = plugins_url();
if(isset($images)) $t = count($images); 
if(get_the_ID()) $imgurl = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
 

if($imgurl!='') {  
$previews = <<<IMGS
<div class='wpmp-thumbnails'> 
<a class='thumbnail' href='{$imgurl}' rel="product-images" title="{$post->post_title}" ><img src='{$pluginurl}/wpmarketplace/libs/timthumb.php?w=600&h=400&zc=1&src={$imgurl}'/></a>
IMGS;

if($images){
foreach($images as $image){
    ++$c;
$previews .= <<<IMGS
<a class='thumbnail pull-left' href='{$uiu}{$image}' rel="product-images" title="{$post->post_title}" ><img src='{$pluginurl}/wpmarketplace/libs/timthumb.php?w=75&h=50&zc=1&src={$uiu}{$image}'/></a>
IMGS;
}
}
$previews .= <<<IMGS
</div> 
IMGS;
} 