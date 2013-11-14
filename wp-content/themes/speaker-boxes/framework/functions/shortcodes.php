<?php
// Allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');


// Box
function box($atts, $content = null) {
	return '
<div class="bs">'.$content.'</div>
';
}

add_shortcode('box', 'box');


// Blockquotes
function blockleft($atts, $content = null) {
	return '<p class="blockquote-left">'.$content.'</p>';
}

add_shortcode('blockleft','blockleft');

// Blockquotes
function blockbox($atts, $content = null) {
	return '<p class="blockquote-box">'.$content.'</p>';
}

add_shortcode('blockbox','blockbox');



// Toggle
function siiimple_toggle_content( $atts, $content = null ) {
	extract(shortcode_atts(array(
        'title'      => '',
    ), $atts));
	
	$out .= '<h3 class="toggle"><a href="#">' .$title. '</a></h3>';
	$out .= '<div class="toggle_content" style="display: none;">';
	$out .= '<div class="block">';
	$out .= do_shortcode($content);
	$out .= '</div>';
	$out .= '</div>';
	
   return $out;
}
add_shortcode('toggle', 'siiimple_toggle_content');



// Google Maps
function do_googleMaps($atts, $content = null) {
   extract(shortcode_atts(array(
      "width" => '640',
      "height" => '480',
      "src" => ''
   ), $atts));
   return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$src.'&amp;output=embed" ></iframe>';
}
add_shortcode("googlemap", "do_googleMaps");



// TweetMeme
function tweetmeme(){
	return '<div class="tweetmeme"><script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script></div>';
}
add_shortcode('tweet', 'tweetmeme');



// Download Button
function download($atts, $content = null) {
	extract(shortcode_atts(array(
		"url" => ''
	), $atts));
	return '<a class="download" href="'.$url.'">'.$content.'</a>';
}
add_shortcode('download', 'download');



// Columns
function half($atts, $content = null) {
	return '
<div class="half">'.$content.'</div>
';
}
function half_last($atts, $content = null) {
	return '
<div class="half-last">'.$content.'</div>
<br style="clear: both;" />';
}
add_shortcode('half', 'half');
add_shortcode('half_last', 'half_last');



// Tooltips
add_shortcode('tooltip', 'siiimple_tooltip');
	function siiimple_tooltip($atts, $content = null) {
		extract(shortcode_atts(array(
			'text' => 'Make sure to include text.',
			'color' => 'light'
		), $atts));
		
		switch($color) {
			
			case 'blue':
				$class = 'tooltip-blue';
				break;
			
			case 'orange':
				$class = 'tooltip-orange';
				break;
			
			case 'green':
				$class = 'tooltip-green';
				break;
			
			case 'purple':
				$class = 'tooltip-purple';
				break;
			
			case 'pink':
				$class = 'tooltip-pink';
				break;
			
			case 'red':
				$class = 'tooltip-red';
				break;
			
			case 'grey':
				$class = 'tooltip-grey';
				break;
			
			case 'light':
				$class = 'tooltip-light';
				break;
			
			case 'black':
				$class = 'tooltip-black';
				break;
			
		}
		return '<span class="tip_trigger">'.do_shortcode($content).'<span class="tip '.$class.'">'.$text.'</span></span>';
		
	}
?>