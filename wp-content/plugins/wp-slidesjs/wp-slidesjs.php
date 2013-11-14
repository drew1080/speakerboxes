<?php
/*
Plugin Name: wp-slidesjs
Version: 0.4.1
Plugin URI: http://petermolnar.eu/wordpress/wp-slidesjs
Description: Adds a shortcut function to WordPress to create Slides JS ( http://slidesjs.com/ ) slideshow from posts of a category.
Author: Peter Molnar
Author URI: http://petermolnar.eu/
License: Apache License, Version 2.0
*/

/*  Copyright 2010-2011 Peter Molnar  (email : hello@petermolnar.eu )
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
*/

/* older wordpress fix */
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', get_option( 'siteurl' ) . '/wp-content/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', ABSPATH . 'wp-content/plugins' );

/* wp-slidesjs constants */
define ( 'WP_SLIDESJS_PARAM' , 'wp-slidesjs' );
define ( 'WP_SLIDESJS_OPTION_GROUP' , WP_SLIDESJS_PARAM . '-params' );
define ( 'WP_SLIDESJS_URL' , WP_PLUGIN_URL . '/' . WP_SLIDESJS_PARAM  );
define ( 'WP_SLIDESJS_DIR' , WP_PLUGIN_DIR . '/' . WP_SLIDESJS_PARAM );
define ( 'WP_SLIDESJS_SEPARATOR' , ',' );

if (!class_exists('WPSlidesJS')) {

	/**
	 * main class for wp-slidesjs
	 *
	 */
	class WPSlidesJS {

		var $options = array();
		var $defaults = array();

		/**
		* constructor
		*
		*/
		function __construct() {

			/* register options */
			$this->get_options();

			/* add scripts for non-admin pages */
			if( ! is_admin() && ! is_feed() )
			{
				wp_enqueue_script('jquery');
				wp_enqueue_script( 'slides.jquery.js' , WP_SLIDESJS_URL . '/js/slides.jquery.js' , array('jquery') , '1.0' );

				if ($this->options['defaultCSS'])
					wp_enqueue_style( 'wp-slidesjs.default.css' , WP_SLIDESJS_URL . '/css/wp-slidesjs.default.css', false, '0.1');
			}
			/* add CSS only for admin */
			else
			{
				wp_enqueue_style( 'wp-slidesjs.admin.css' , WP_SLIDESJS_URL . '/css/wp-slidesjs.admin.css', false, '0.1');
			}

			/* on activation */
			register_activation_hook(__FILE__ , array( $this , 'activate') );

			/* on uninstall */
			register_uninstall_hook(__FILE__ , array( $this , 'uninstall') );

			/* init plugin in the admin section */
			add_action('admin_menu', array( $this , 'admin_init') );

			/* register shortcode */
			add_shortcode( WP_SLIDESJS_PARAM , array( $this , 'shortcode') );

		}

		/**
		 * activation hook: save default settings in order to eliminate bugs.
		 *
		 */
		function activate ( ) {
			$this->save_settings();
		}

		/**
		 * init function for admin section
		 *
		 */
		function admin_init () {
			/* register options */
			register_setting( WP_SLIDESJS_OPTION_GROUP , WP_SLIDESJS_PARAM );
			add_option( WP_SLIDESJS_PARAM, $this->options , '' , 'no');

			/* save parameter updates, if there are any */
			if ( isset($_POST[WP_SLIDESJS_PARAM . '-save']) )
			{
				$this->save_settings () ;
				header("Location: options-general.php?page=wp-slidesjs-options&saved=true");
			}

			/* add the options page to admin section for privileged for admin users */
			add_options_page('Edit wp-slidesjs options', __('wp-slidesjs', WP_SLIDESJS_PARAM ), 10, 'wp-slidesjs-options', array ( $this , 'admin_panel' ) );
		}

		/**
		 * settings panel at admin section
		 *
		 */
		function admin_panel ( ) {

			/**
			 * security
			 */
			if( ! function_exists( 'current_user_can' ) || ! current_user_can( 'manage_options' ) ){
				die( );
			}

			/**
			 * if options were saved
			 */
			if ($_GET['saved']=='true') :
			?>

			<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>Settings saved.</strong></p></div>

			<?php
			endif;

			/**
			 * the admin panel itself
			 */
			?>

			<div class="wrap">
			<h2><?php _e( ' wp-slidesjs settings', WP_SLIDESJS_PARAM ) ; ?></h2>
			<form method="post" action="#">

				<fieldset class="grid50">
				<legend><?php _e('Layout options',WP_SLIDESJS_PARAM); ?></legend>
				<dl>

					<dt>
						<label for="generatePagination"><?php _e('Generate pagination', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<input type="checkbox" name="generatePagination" id="generatePagination" value="1" <?php checked($this->options['generatePagination'],true); ?> />
						<span class="description"><?php _e('Generate pagination automatically.', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php $this->print_bool( $this->defaults['generatePagination']); ?></span>
						</p>
					</dd>
					<dt>
						<label for="randomize"><?php _e('Randomize', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<input type="checkbox" name="randomize" id="randomize" value="1" <?php checked($this->options['randomize'],true); ?> />
						<span class="description"><?php _e('Randomize displayed slides order', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php $this->print_bool( $this->defaults['randomize']); ?></span>
						</p>
					</dd>
					<dt>
						<label for="start"><?php _e('index of first slide', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<input type="number" name="start" id="start" value="<?php echo $this->options['start']; ?>" />
						<span class="description"><?php _e('Number of slide to start with.', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php echo $this->defaults['start']; ?></span>
						</p>
					</dd>
					<dt>
						<label for="defaultCSS"><?php _e('Use default CSS?', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<input type="checkbox" name="defaultCSS" id="defaultCSS" value="1" <?php checked($this->options['defaultCSS'],true); ?> />
						<span class="description"><?php _e('Use provided CSS for slides display.', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php $this->print_bool( $this->defaults['defaultCSS']); ?></span>
						</p>
					</dd>
					<dt>
						<label for="contentSource"><?php _e('Content source', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<select name="contentSource" id="contentSource">
							<?php $this->content_source( $this->options['contentSource'] ) ?>
						</select>
						<span class="description"><?php _e('Select content source to be displayed below the post title in the slide.', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php $this->content_source( $this->defaults['contentSource'] , true ) ; ?></span>
						</p>
					</dd>
				</dl>
				</fieldset>

				<fieldset class="grid50">
				<legend><?php _e('Behaviour options',WP_SLIDESJS_PARAM); ?></legend>
				<dl>
					<dt>
						<label for="hoverPause"><?php _e('Pause on hover', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<input type="checkbox" name="hoverPause" id="hoverPause" value="1" <?php checked($this->options['hoverPause'],true); ?> />
						<span class="description"><?php _e('Pause the slideshow while hovering.', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php $this->print_bool( $this->defaults['hoverPause']); ?></span>
						</p>
					</dd>

					<dt>
						<label for="bigTarget"><?php _e('Full size click', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<input type="checkbox" name="bigTarget" id="bigTarget" value="1" <?php checked($this->options['bigTarget'],true); ?> />
						<span class="description"><?php _e('The whole slide will link to next slide on click', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php $this->print_bool( $this->defaults['bigTarget']); ?></span>
						</p>
					</dd>
					<dt>
						<label for="play"><?php _e('Autoslide time', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<input type="number" name="play" id="play" value="<?php echo $this->options['play']; ?>" />
						<span class="description"><?php _e('Timeout for autoslide to the next slide in milliseconds. 0 means disabled.', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php echo $this->defaults['play']; ?></span>
						</p>
					</dd>

					<dt>
						<label for="pause"><?php _e('Pause time', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<input type="number" name="pause" id="pause" value="<?php echo $this->options['pause']; ?>" />
						<span class="description"><?php _e('Wait time after pagination was clicked in milliseconds', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php echo $this->defaults['pause']; ?></span>
						</p>
					</dd>
					<dt>
						<label for="slideSpeed"><?php _e('Slide speed', WP_SLIDESJS_PARAM); ?></label>
					</dt>
					<dd>
						<input type="number" name="slideSpeed" id="slideSpeed" value="<?php echo $this->options['slideSpeed']; ?>" />
						<span class="description"><?php _e('Speed of sliding in milliseconds.', WP_SLIDESJS_PARAM); ?></span>
						<span class="default"><?php _e('Default ', WP_SLIDESJS_PARAM); ?>: <?php echo $this->defaults['slideSpeed']; ?></span>
						</p>
					</dd>
				</dl>
				</fieldset>

				<?php settings_fields( WP_SLIDESJS_OPTION_GROUP ); ?>
				<p class="button-full"><input class="button-primary" type="submit" name="<?php echo WP_SLIDESJS_PARAM; ?>-save" id="<?php echo WP_SLIDESJS_PARAM; ?>-save" value="Save Changes" /></p>
			</form>
			<?php

		}

		/**
		 * content source selector
		 *
		 * @param $current
		 * 	the active or required identifier
		 *
		 * @param $returntext
		 * 	boolean: is true, the description will be returned of $current
		 *
		 * @return
		 * 	prints either description of $current
		 * 	or option list for a <select> input field with $current set as active
		 *
		 */
		function content_source ( $current , $returntext = false ) {

			$elements = array (
				'post_content' => 'content',
				'post_excerpt' => 'excerpt',
			);

			$this->print_select_options ( $elements , $current , $returntext );

		}

		/**
		 * effect selector
		 *
		 * @param $current
		 * 	the active or required identifier
		 *
		 * @param $returntext
		 * 	boolean: is true, the description will be returned of $current
		 *
		 * @return
		 * 	prints either description of $current
		 * 	or option list for a <select> input field with $current set as active
		 *
		 */
		function effect_type ( $current , $returntext = false ) {

			$elements = array (
				'fade' => 'fade',
				'slide' => 'slide',
			);

			$this->print_select_options ( $elements , $current , $returntext );

		}

		/**
		 * parameters array with default values;
		 *
		 * @param $def
		 * 	is false, the function returns with the current settings, if true, the defaults will be returned
		 *
		 */
		function get_options ( ) {
			$defaults = array (
				'pagination'=>true,
				'generatePagination'=>true,
				'slideSpeed'=>350,
				'start'=>1,
				'randomize'=>false,
				'play'=>4000,
				'pause'=>0,
				'hoverPause'=>true,
				'bigTarget'=>false,
				'defaultCSS'=>true,
				'contentSource'=>'post_content',
			);
			$this->defaults = $defaults;

			$this->options = get_option( WP_SLIDESJS_PARAM , $defaults );
		}

		/**
		 * create js param list from options
		 *
		 */
		function options_to_js ( $tabs=0 ) {
			$return = false;

			foreach ( $this->options as $key => $value) {
				if ( is_bool ( $this->defaults[$key] ) )
					$value = empty ( $value ) ? 'false' : 'true';
				elseif ( !is_int ( $this->defaults[$key] ) )
					$value = "'" . $value . "'";

				$return .= str_pad( $key . ": " . $value . ",\n", $tabs , "	");
			}

			return $return;
		}

		/**
		 * save settings function
		 *
		 */
		function save_settings () {

			/**
			 * update params from $_POST
			 */
			foreach ($this->options as $name=>$optionvalue)
			{
				if (!empty($_POST[$name]))
				{
					$update = $_POST[$name];
					if (strlen($update)!=0 && !is_numeric($update))
						$update = stripslashes($update);
				}
				elseif ( ( empty($_POST[$name]) && is_bool ($this->defaults[$name]) ) || is_numeric( $update ) )
				{
					$update = 0;
				}
				else
				{
					$update = $this->defaults[$name];
				}
				$this->options[$name] = $update;
			}
			update_option( WP_SLIDESJS_PARAM , $this->options );
		}

		/**
		 * shortcode function
		 *
		 * @param $atts
		 * 	array of passed attributes in shortcode, for example [wp-slidesjs set=ID]
		 *
		 * @param $content
		 * 	optional content between [wp-slidesjs][/wp-slidesjs]
		 *
		 * @return
		 * 	returns with the HTML code to print out
		 */
		function shortcode( $atts ,  $content = null ) {
			$atts = shortcode_atts(array(
				'limit' => '-1',
				'category_slug' => false,
				'category_id' => 1,
				'post_id' => false,
				'page_id' => false,
			), $atts);
			$limit = array_shift($atts);

			$output = false;
			$posts = array();

			/* get the 'limit' element off the beginning of types */
			foreach ($atts as $type=>$val)
			{
				/* if the values of the variable named by the type if not empty */
				if ( !empty($val))
				{
					/* if the value contains the defined separator, explode into elements */
					$elements = explode( WP_SLIDESJS_SEPARATOR , $val);

					/* search for all elements */
					foreach ($elements as $element )
					{
						$_posts = false;
						switch ( $type )
						{
							case 'category_slug':
								$category = get_category_by_slug( $element );
								$_posts = get_posts( array( 'category' => $category->cat_ID , 'numberposts' => $limit ));
								break;
							case 'category_id':
								$category = get_category ( $element );
								$_posts = get_posts( array( 'category' => $category->cat_ID , 'numberposts' => $limit ));
								break;
							case 'page_id':
							case 'post_id':
								$_posts = get_post( $element );
									if (empty($_posts))
										$_posts = get_page( $element );
								break;
						}

						/* if anything had been found */
						if ( !empty($_posts) )
						{
							/* if  it's not an array, convert it into one */
							if (!is_array($_posts))
								$_posts = array($_posts);

							/* merge with the already found ones */
							$posts = array_merge( $posts, $_posts);
						}
					}
				}
			}

			$contentsource = $this->options['contentSource'];
			foreach ( $posts as $post ) {
				$post_title = htmlspecialchars( stripslashes( $post->post_title ) );

				$bg = '';

				if ( has_post_thumbnail ( $post->ID ) )
				{
					$domsxe = simplexml_load_string( get_the_post_thumbnail( $post->ID ) );
					$thumbnailsrc = $domsxe->attributes()->src;
				}

				$output .= '
					<article class="wp-slidesjs-slide wp-slidesjs-roundtop">
						<div class="wp-slidesjs-title">'.$post->post_title.'</div>
						<div class="wp-slidesjs-content">'.$post->$contentsource.'</div>
					</article>';

			}

			$output = '
			<aside id="wp-slidesjs-'. $category->slug .'" class="wp-slidesjs wp-slidesjs-grad wp-slidesjs-round">
				<section class="wp-slidesjs-container wp-slidesjs-roundtop">
			'. $output .'
				</section>
			</aside>';

			$js = "
			<script type='text/javascript'>
				jQuery(document).ready(function($) {
					jQuery('#wp-slidesjs-". $category->slug ."').slides({
						". $this->options_to_js(5) ."
						pagination: true,
						container: 'wp-slidesjs-container',
						currentClass: 'wp-slidesjs-current',
						paginationClass: 'wp-slidesjs-pages',
					});
				});
			</script>";

			$output = $output . $js;

			return $output;
		}

		/**
		 * prints `true` or `false` depending on a bool variable.
		 *
		 * @param $val
		 * 	The boolen variable to print status of.
		 *
		 */
		function print_bool ( $val ) {
			$bool = $val? 'true' : 'false';
			echo $bool;
		}

		/**
		 * select field processor
		 *
		 * @param sizes
		 * 	array to build <option> values of
		 *
		 * @param $current
		 * 	the current resize type
		 *
		 * @param $returntext
		 * 	boolean: is true, the description will be returned of $current type
		 *
		 * @return
		 * 	prints either description of $current
		 * 	or option list for a <select> input field with $current set as active
		 *
		 */
		function print_select_options ( $sizes, $current, $returntext=false ) {

			if ( $returntext )
			{
				_e( $sizes[ $current ] , WP_SLIDESJS_PARAM);
				return;
			}

			foreach ($sizes as $ext=>$name)
			{
				?>
				<option value="<?php echo $ext ?>" <?php selected( $ext , $current ); ?>>
					<?php _e( $name , WP_SLIDESJS_PARAM); ?>
				</option>
				<?php
			}

		}

		/**
		 * clean up at uninstall
		 *
		 */
		function uninstall ( ) {
			delete_option( WP_SLIDESJS_PARAM );
		}

	}
}

/**
 * instantiate the class
 */
$wp_slidesjs = new WPSlidesJS();


?>
