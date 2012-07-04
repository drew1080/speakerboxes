<?php

add_action('init','of_options');

if (!function_exists('of_options')) {
function of_options(){
	
// VARIABLES
$themename = get_theme_data(STYLESHEETPATH . '/style.css');
$themename = $themename['Name'];
$shortname = "of";

// Populate OptionsFramework option in array for use in theme
global $of_options;
$of_options = get_option('of_options');

$GLOBALS['template_path'] = OF_DIRECTORY;

//Access the WordPress Categories via an Array
$of_categories = array();  
$of_categories_obj = get_categories('hide_empty=0');
foreach ($of_categories_obj as $of_cat) {
    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
$categories_tmp = array_unshift($of_categories, "Select a category:");    
       
//Access the WordPress Pages via an Array
$of_pages = array();
$of_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($of_pages_obj as $of_page) {
    $of_pages[$of_page->ID] = $of_page->post_name; }
$of_pages_tmp = array_unshift($of_pages, "Select a page:");       

// Image Alignment radio box
$options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 

// Image Links to Options
$options_image_link_to = array("image" => "The Image","post" => "The Post"); 

//Testing 
$img = OF_DIRECTORY . '/_framework/images/graph.png';

// Nivo Slider Options
$options_slide_time = array("5000","6000","7000","8000","9000","10000");//ani time
$options_slide_speed = array("300","400","500","600","700","800");//Slide transition speed
$options_slide_eff = array("sliceDown","sliceDownLeft","sliceUp","sliceUpLeft","sliceUpDown","sliceUpDownLeft","fold","fade","random");
$options_slide_slices = array("5","10","15","20");

// Orbit Slider Options
$options_slide_time_orbit = array("3000","4000","5000","6000","7000","8000","9000","10000");//if timer is enabled, time between transitions
$options_slide_ani_orbit = array("400","500","600","700","800","900","1000");// how fast animtions are
$options_orbit_trans = array("fade", "horizontal-slide", "vertical-slide", "horizontal-push");

$options_select = array($img,"two","three","four","five"); 
$mosaic_select = array("bar","bar2","bar3","fade");
$fonts_select = array("Droid Sans","Francois One","Limelight","Brawler","Carter One","Pacifico","Vibur","Bangers","Anton","Allan","Maiden Orange","Mako");  
$p_select = array("Droid Serif","Arvo","Cardo","PT Serif","EB Garamond","Rokkitt","Lora ","Radley","Kreon","Josefin Slab"); 
$header_size = array("20px","22px","24px","26px","28px","30px","32px");
$paragraph_size = array("11px","12px","13px","14px","15px","16px",);
$options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five"); 

//Stylesheets Reader
$alt_stylesheet_path = OF_FILEPATH . '/framework/styles/';
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}

//More Options
$uploads_arr = wp_upload_dir();
$all_uploads_path = $uploads_arr['path'];
$all_uploads = get_option('of_uploads');
$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");

// Custom Logo
$options = array();

$options[] = array( "name" => "General Options (Logo, CSS, Twitter)",
                    "type" => "heading");
                    
$options[] = array( "name" => "Check For Text Logo",
					"desc" => "Check this to enable a plain text logo rather than an image.",
					"id" => $shortname."_text_logo",
					"std" => "false",
					"type" => "checkbox");
					
$options[] = array( "name" => "Upload Logo",
					"desc" => "Upload a logo for your theme, or specify the image address of your online logo. (http://yoursite.com/logo.png)",
					"id" => $shortname."_logo",
					"std" => "",
					"type" => "upload");
					
$options[] = array( "name" => "Theme Stylesheet",
					"desc" => "Select your themes alternative color scheme.",
					"id" => $shortname."_alt_stylesheet",
					"std" => "dark.css",
					"type" => "select",
					"options" => $alt_stylesheets);
					
$options[] = array( "name" => "Check For Twitter",
					"desc" => "Check this box if you want to show the Twitter feed in Footer",
					"id" => $shortname."_twitter_option",
					"std" => "false",
					"type" => "checkbox");
					
$options[] = array( "name" => "Twitter",
					"desc" => "Enter Your Twitter Username",
					"id" => $shortname."_twitter",
					"std" => "",
					"type" => "text");
					
$options[] = array( "name" => "Twitter #",
					"desc" => "How many Tweets to show in slide",
					"id" => $shortname."_twitter_num",
					"std" => "",
					"type" => "text");
					
$options[] = array( "name" => "Mosaic Effects",//slidetime
					"desc" => "Choose Your Slide Effect For Posts",
					"id" => $shortname."_mosaic",
					"std" => "bar",
					"type" => "select",
					"options" => $mosaic_select);
					
							
					

// Layout Options
$options[] = array( "name" => "Blog Layouts",
					"type" => "heading");
					
$url =  OF_DIRECTORY . '/admin/images/';
$options[] = array( "name" => "Blog Layout Options",
					"desc" => "Select main content and sidebar alignment.",
					"id" => $shortname."_layout",
					"std" => "Layout1",
					"type" => "images",
					"options" => array(
						'Layout1' => $url . 'l1.png',
						'Layout2' => $url . 'l2.png',
						'Layout3' => $url . 'l3.png',
						'Layout4' => $url . 'l4.png',
						'Layout5' => $url . 'l5.png',
						'Layout6' => $url . 'l6.png',
						)
					);
                    


// Slider Options
$options[] = array( "name" => "Slider Options",
                    "type" => "heading"); 
                    
                    $url =  OF_DIRECTORY . '/admin/images/';
$options[] = array( "name" => "Choose Your Slider",
					"desc" => "You can choose between three different sliders.",
					"id" => $shortname."_slider",
					"std" => "",
					"type" => "images",
					"options" => array(
						'nivo' => $url . 'nivo.png',
						'anything' => $url . 'anything.png',
						'kwicks' => $url . 'kwicks.png',
					
						)
					);
					
$options[] = array( "name" => "Turn on Captions for Anything Slider",
					"desc" => "Here you can choose to show the caption title by checking this box.  This will only show captions for the Anything slider.  For options related to Nivo slider go to the Nivo Slider Options.",
					"id" => $shortname."_anything_caption",
					"std" => "false",
					"type" => "checkbox");



// Typography					
$options[] = array( "name" => "Typography",
                    "type" => "heading");
					
$options[] = array( "name" => "Choose Header Fonts",
					"desc" => "Choose Your Fonts For The Main Headers",
					"id" => $shortname."_font_header",
					"std" => "",
					"type" => "select",
					"options" => $fonts_select);
	
 



// Kwicks Slider I
$options[] = array( "name" => "Kwicks Slider",
					"type" => "heading");

$options[] = array( "name" => "Kwicks Image 1",
					"desc" => "Upload and select and image, or paste the url to an image you want to use. ",
					"id" => $shortname."_accordion_slider_image_1",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => "Kwicks Image 1 URL",
					"desc" => "Where should this image take me when I click on it? Enter the url. (This is optional)",
					"id" => $shortname."_accordion_slider_image_1_url",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Kwicks Image 1 Title",
					"desc" => "The title that displays over the image. (This is optional)",
					"id" => $shortname."_accordion_slider_image_1_title",
					"std" => "",
					"type" => "text");
                    
$options[] = array( "name" => "Kwicks Image 1 Text Description",
					"desc" => "The text that shows when accordian is hovered (This is optional)",
					"id" => $shortname."_accordion_slider_image_1_description",
					"std" => "",
					"type" => "textarea");  
					





// Kwicks Slider II                  
$options[] = array( "name" => "Slider Image 2",
					"desc" => "Upload and select and image, or paste the url to an image you want to use. ",
					"id" => $shortname."_accordion_slider_image_2",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => "Slider Image 2 URL",
					"desc" => "Where should this image take me when I click on it? Enter the url. (This is optional)",
					"id" => $shortname."_accordion_slider_image_2_url",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Slider Image 2 Title",
					"desc" => "The title that displays over the image. (This is optional)",
					"id" => $shortname."_accordion_slider_image_2_title",
					"std" => "",
					"type" => "text");
                    
$options[] = array( "name" => "Slider Image 2 Text Description",
					"desc" => "The text that shows when accordian is hovered (This is optional)",
					"id" => $shortname."_accordion_slider_image_2_description",
					"std" => "",
					"type" => "textarea");                      





// Kwicks Slider III
$options[] = array( "name" => "Slider Image 3",
					"desc" => "Upload and select and image, or paste the url to an image you want to use. ",
					"id" => $shortname."_accordion_slider_image_3",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => "Slider Image 3 URL",
					"desc" => "Where should this image take me when I click on it? Enter the url. (This is optional)",
					"id" => $shortname."_accordion_slider_image_3_url",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Slider Image 3 Title",
					"desc" => "The title that displays over the image. (This is optional)",
					"id" => $shortname."_accordion_slider_image_3_title",
					"std" => "",
					"type" => "text");
                    
$options[] = array( "name" => "Slider Image 3 Text Description",
					"desc" => "The text that shows when accordian is hovered (This is optional)",
					"id" => $shortname."_accordion_slider_image_3_description",
					"std" => "",
					"type" => "textarea");                      





// Kwicks Slider IV
$options[] = array( "name" => "Slider Image 4",
					"desc" => "Upload and select and image, or paste the url to an image you want to use. ",
					"id" => $shortname."_accordion_slider_image_4",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => "Slider Image 4 URL",
					"desc" => "Where should this image take me when I click on it? Enter the url. (This is optional)",
					"id" => $shortname."_accordion_slider_image_4_url",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Slider Image 4 Title",
					"desc" => "The title that displays over the image. (This is optional)",
					"id" => $shortname."_accordion_slider_image_4_title",
					"std" => "",
					"type" => "text");
                    
$options[] = array( "name" => "Slider Image 4 Text Description",
					"desc" => "The text that shows when accordian is hovered (This is optional)",
					"id" => $shortname."_accordion_slider_image_4_description",
					"std" => "",
					"type" => "textarea");                      





// Kwicks Slider V
$options[] = array( "name" => "Slider Image 5",
					"desc" => "Upload and select and image, or paste the url to an image you want to use. ",
					"id" => $shortname."_accordion_slider_image_5",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => "Slider Image 5 URL",
					"desc" => "Where should this image take me when I click on it? Enter the url. (This is optional)",
					"id" => $shortname."_accordion_slider_image_5_url",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Slider Image 5 Title",
					"desc" => "The title that displays over the image. (This is optional)",
					"id" => $shortname."_accordion_slider_image_5_title",
					"std" => "",
					"type" => "text");
                    
$options[] = array( "name" => "Slider Image 5 Text Description",
					"desc" => "The text that shows when accordian is hovered (This is optional)",
					"id" => $shortname."_accordion_slider_image_5_description",
					"std" => "",
					"type" => "textarea");
					

// Nivo Slider					
$options[] = array( "name" => "Nivo Slider",
                    "type" => "heading"); 
					
$options[] = array( "name" => "Transition Effect",//eff
					"desc" => "The time between slides.",
					"id" => $shortname."_nivo_slider_eff",
					"std" => "5000",
					"type" => "select",
					"options" => $options_slide_eff);
					
$options[] = array( "name" => "Transition Speed",//ani speed
					"desc" => "The time between slides.",
					"id" => $shortname."_nivo_slider_speed",
					"std" => "500",
					"type" => "select",
					"options" => $options_slide_speed);
					
$options[] = array( "name" => "Display Time",//slidetime
					"desc" => "The time that a slide is displayed. EX: 7000 is 7 seconds.",
					"id" => $shortname."_nivo_slider_time",
					"std" => "7000",
					"type" => "select",
					"options" => $options_slide_time);
					
$options[] = array( "name" => "Slice Count",//slice
					"desc" => "How many slices?",
					"id" => $shortname."_nivo_slider_slices",
					"std" => "15",
					"type" => "select",
					"options" => $options_slide_slices);
					
$options[] = array( "name" => "Turn on Captions",
					"desc" => "Here you can choose to show the caption title by checking this box.",
					"id" => $shortname."_nivo_slider_caption",
					"std" => "false",
					"type" => "checkbox");


// Taglines				
$options[] = array( "name" => "Taglines & Portfolio ",
                    "type" => "heading");
					
$options[] = array( "name" => "1st Tagline (Top)",
					"desc" => "Write something clever...",
					"id" => $shortname."_one_top",
					"std" => "Horray! Welcome to Paragon",
					"type" => "textarea");
					

                    
$options[] = array( "name" => "Top Portfolio Tagline",
					"desc" => "Write something clever...",
					"id" => $shortname."_port_top",
					"std" => "This is my portfolio!",
					"type" => "textarea");
					
$options[] = array( "name" => "Bottom Portfolio Tagline",
					"desc" => "Write something clever...",
					"id" => $shortname."_port_bottom",
					"std" => "Thanks for visiting, checking out my work, come again anytime...",
					"type" => "textarea");
					
$options[] = array( "name" => "How Many Portfolio Items to Display?",
					"desc" => "If you have more posts than you wish to display, the pagination will be activated.",
					"id" => $shortname."_port_num",
					"std" => "",
					"type" => "text");
					
			
// Footer Options 
$options[] = array( "name" => "Footer Options",
					"type" => "heading");  
					
$options[] = array( "name" => "Change text in the Footer?",
					"desc" => "This will change the entire text in the footer and replace it with what you want.  You can use html tags, like < p >< /p > for paragraph, etc.  Also, if you want to keep your rss feed in place use this:<br/> < ?php bloginfo('rss2_url'); ? > (no spaces before brackets)",
					"id" => $shortname."_footer",
					"std" => "",
					"type" => "textarea");
					  
					
$options[] = array( "name" => "Custom Favicon",
					"desc" => "Upload a 16px x 16px Png/Gif image that will represent your website's favicon.",
					"id" => $shortname."_custom_favicon",
					"std" => "",
					"type" => "upload");
					
$options[] = array( "name" => "FeedBurner URL",
					"desc" => "Enter your full FeedBurner URL (or any other preferred feed URL) e.g. http://feeds.feedburner.com/yoururlhere",
					"id" => $shortname."_feedburner",
					"std" => "",
					"type" => "text");
 
                                               
$options[] = array( "name" => "Tracking Code",
					"desc" => "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
					"id" => $shortname."_ga_code",
					"std" => "",
					"type" => "textarea");   
				
					
					
update_option('of_template',$options); 					  
update_option('of_themename',$themename);   
update_option('of_shortname',$shortname);

}
}
?>