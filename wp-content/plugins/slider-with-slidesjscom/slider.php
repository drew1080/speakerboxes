<?php 
/*
Plugin Name: Slider With slidejscom 
Plugin URI: http://gençbilişim.net/
Description:  Slider
Version: 1.1
Author: Samet ATABAŞ
Author URI: http://gençbilişim.net/
*/
/**
* Slider Class ı 
* slideri akrana yazdırmak için "slider::addSlider();" komutunu  çağırın
* 
*/
class slider {
	/**
	* eklenti adresini tutan değişken 
	*
	* var string
	*/
	private  $path;
	function __construct(){
	    $this->path = plugin_dir_url(__FILE__);
	    add_action("wp_head", array (&$this, "slider_head"));
	}
	/**
	* wp head a eklenecek fonksiyon 
	*
	* return void
	*/
	public  function slider_head() {
	$head = '
		<!-- slider için -->		
		<link rel="stylesheet" href="'.$this->path .'css/global.css" type="text/css" media="screen" charset="utf-8"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
		<script type="text/javascript" src="'.$this->path .'scripts/slides.min.jquery.js"></script>
		';
		$head.="<script type=\"text/javascript\">
		$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: 'img/loading.gif',
				play: 5000,
				pause: 2500,
				hoverPause: true,
				animationStart: function(current){
					$('.caption').animate({
						bottom:-35
					},100);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationStart on slide: ', current);
					};
				},
				animationComplete: function(current){
					$('.caption').animate({
						bottom:0
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationComplete on slide: ', current);
					};
				},
				slidesLoaded: function() {
					$('.caption').animate({
						bottom:0
					},200);
				}
			});
		});
	</script>
    	<!-- /slider için -->";
    	echo $head;	
	}
	/**
	* slider için tanımlanmış  resim olupolmadığına bakıp  varsa resim linki ve yazı idsini veren fonksiyon 
	*
	*
	* @return array
	*/
	private function get_slider_meta(){
		global $wpdb;
		$resimler=$wpdb->get_results("SELECT post_id,meta_value FROM $wpdb->postmeta WHERE meta_key='sliderImage' ORDER BY meta_id DESC", 'ARRAY_A');
		return $resimler;
	}
	/**
	* slider ı  ekrana basacak fonksiyon 
	*
	*
	* @return string
	*/
	public  function addSlider() {
		$out='
	    <div id="container">
        	<div id="example">
            	<div id="slides">
                	<div class="slides_container">';
					foreach ($this->get_slider_meta() as $resim ){
						$post_id= $resim['post_id'];
						$resim_url= $resim['meta_value'];
						$post= get_post($post_id);
						$out.='
                    	<div class="slide">
                        	<a title="'. $post->post_title.'" href="'.get_permalink($post_id).'">
                            	<img width="570" height="270" alt="Slide 1" src="'.$resim_url.'">
	                        </a>
    	                    <div style="bottom:0" class="caption">
        	                    <p>'.$post->post_title.'</p>
            	            </div>
                	    </div>';
					}
					$out.='
	                </div>
    	            <a class="prev" href="#"><img width="24" height="43" alt="Arrow Prev" src="'.$this->path.'img/arrow-prev.png"></a>
	                <a class="next" href="#"><img width="24" height="43" alt="Arrow Next" src="'.$this->path.'img/arrow-next.png"></a>
    	        </div>
	            <img width="739" height="341" id="frame" alt="Example Frame" src="'.$this->path.'img/example-frame.png">
	        </div>
    	</div> ';
        echo $out;
	}
}
$slider= new slider();
?>