<?php
/*
Plugin Name: WP Media Category
Plugin URI: http://wordpress.org/extend/plugins/wp-media-category/
Description: Include the category taxonomy to your media files. No configuration needed... just Plug&Play!
Version: 0.2.1b
Author: Thiago Belem
Author URI: http://thiagobelem.net/
License: GPL2
*/

/*
Copyright 2010 Thiago Belem (contato@thiagobelem.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WPMediaCategory_VERSION', '0.2.1b');
define('WPMediaCategory_REQUIRE_PHP_VERSION', '5.2.0');

define('WPMediaCategory_PATH', dirname(__FILE__));
define('WPMediaCategory_FOLDER', basename(WPMediaCategory_PATH));
define('WPMediaCategory_URL', WP_PLUGIN_URL . '/' . WPMediaCategory_FOLDER);
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

/**
 * WP Media Category Plugin
 * 
 * Include the category taxonomy to your media files. No configuration needed... just Plug&Play!
 *  
 * @author Thiago Belem
 * @link http://thiagobelem.net/
 * 
 * @package WPMediaCategory
 * @version 0.2.1b
 * 
 * @todo Add support to multiple categories
 */
class WPMediaCategory {
	
	/**
	 * Construtor da WPMediaCategory
	 * 
	 * Inicializa todos os filters, actions e a localização
	 * 
	 * @since 0.1
	 * 
	 * @uses add_action() from WordPress
	 * @uses add_filter() from WordPress
	 * @uses WPMediaCategory::add_admin_scripts()
	 * @uses WPMediaCategory::add_media_category_field()
	 * @uses WPMediaCategory::save_media_category_field()
	 * @uses WPMediaCategory::remove_flash_uploader()
	 * 
	 * @return void
	 */
	public function __construct() {
		$admin_notices = 0;
		$admin_init = 0;
		$attachment_fields_to_edit = 0;
		$attachment_fields_to_save = 0;
		$flash_uploader = 0;		
		
		$this->messages = array(
			'phpversion' =>
				array('text' => __('Please, upgrade your PHP to version %s or greater', __CLASS__),
				'error' => true),
			'json' =>
				array('text' => __('Please, enable the JSON library on your server', __CLASS__),
				'error' => true)		
		);
		
		// Verifica a versão do PHP
		if (version_compare(PHP_VERSION, WPMediaCategory_REQUIRE_PHP_VERSION) < 0) {
			add_action('admin_notices', 'showError_phpversion', $admin_notices++);
		}
		
		// Verifica se tem a função json_encode
		else if (!function_exists('json_encode')) {
			add_action('admin_notices', 'showError_json', $admin_notices++);
		}
		
		// jQuery que cria os selects de categoria
		add_action('admin_init', array(&$this, 'add_admin_scripts'), $admin_init++);
		
		// Editar um anexo
		add_filter('attachment_fields_to_edit', array(&$this, 'add_media_category_field'), $attachment_fields_to_edit++, 2);
		
		// Salvar
		add_filter('attachment_fields_to_save', array(&$this, 'save_media_category_field'), $attachment_fields_to_save++, 2);
		
		// Desabilita o flash upload
		add_filter('flash_uploader', array(&$this, 'remove_flash_uploader'), $flash_uploader++);
		
		// Localização
		load_plugin_textdomain(__CLASS__, false, WPMediaCategory_FOLDER . DS . 'locale' . DS );
	}

	/**
	 * Cria uma lista hierárquica de categorias
	 * 
	 * @since 0.1
	 * 
	 * @uses get_categories() from WordPress
	 * @uses WPMediaCategory::get_category_hierarchical_list()
	 * 
	 * @param integer $parentID - O ID da categoria parent das categorias que serão retornadas
	 * @return array - Um array com a lista de categorias
	 */
	private function get_category_hierarchical_list($parentID = 0) {
		$return = array();
		
		$args = array(
			'hide_empty' => false,
			'parent' => (int)$parentID,
			'hierarchical' => false
		);
		$categorias = get_categories($args);
		
		if (empty($categorias)) return $return;

		foreach ($categorias AS $categoria) {
			$array = array();
			$array['id'] = $categoria->term_id;
			$array['name'] = $categoria->name;
			$array['slug'] = $categoria->category_nicename;
			$array['children'] = $this->get_category_hierarchical_list($categoria->term_id);
			$return[] = $array;
		}
		
		return $return;
	}
	
	/**
	 * Adiciona o campo de categoria ao formulário de edição de media
	 * 
	 * @since 0.1
	 * 
	 * @uses WPMediaCategory::get_category_hierarchical_list()
	 * @uses wp_get_object_terms() from WordPress
	 * 
	 * @param array $fields - Array com os campos HTML de edição da media
	 * @param object $object - Objeto com os dados da media
	 * @return array - Array com os campos HTML de edição da media
	 */
	public function add_media_category_field($fields, $object) {
		if (!isset($fields['media_category'])) {
									
			$categories_json = json_encode($this->get_category_hierarchical_list());
			
			$html = '';
			$html .= '<script type="text/javascript">var categories = jQuery.parseJSON(\''. $categories_json .'\');</script>' . PHP_EOL;
				
			$categories = (array)wp_get_object_terms($object->ID, 'category');
			if (!empty($categories)) {
				$category = end($categories);
				
				$html .= '<div><input type="hidden" name="attachments['.$object->ID.'][media-categories][]" id="media-category" value="'. $category->term_id .'" /></div>' . PHP_EOL;
				$label = __('New category', __CLASS__);
			} else {
				$html .= '<div><input type="hidden" name="attachments['.$object->ID.'][media-categories][]" id="media-category" /></div>' . PHP_EOL;
				$label = __('Category', __CLASS__);				
			}
			
			$fields['media_category'] = array(
				'label' => $label,
				'input' => 'html',
				'html' => $html,
				'value' => (!empty($categories)) ? $category->term_id : '',
				'helps' => (!empty($categories) AND !empty($category)) ? '<strong>' . __('Current category', __CLASS__) . '</strong>: ' . $category->name : ''
			);
		}
		return $fields;
	}
	
	/**
	 * Salva o campo de categoria de uma media
	 * 
	 * @since 0.1
	 * 
	 * @uses wp_set_object_terms() from WordPress
	 * 
	 * @param array $fields - Array com os campos HTML de edição da media
	 * @param object $object - Objeto com os dados da media
	 * @return array - Array com os campos HTML de edição da media
	 */
	public function save_media_category_field($post, $attachment) {
		
		$categories = array();
		foreach ($attachment['media-categories'] AS $category) {
			$categories[] = (int)$category;
		}
		wp_set_object_terms($post['ID'], $categories, 'category');
		
		return $post;
	}
	
	/**
	 * Insere os scripts (JS e CSS) no wp-admin
	 * 
	 * @uses wp_deregister_script() from WordPress
	 * @uses wp_enqueue_script() from WordPress
	 * 
	 * @since 0.1
	 * 
	 * @return void
	 */
	public function add_admin_scripts() {
		// Insere o jQuery 1.4.2
		wp_enqueue_script(
			'jquery142', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js', false);
		
		wp_enqueue_script(
			'WPMediaCategory-jquery-init',
			WPMediaCategory_URL. '/js/jquery.init.js',
			array('jquery142'), filemtime(WPMediaCategory_PATH . '/js/jquery.init.js'), true);
	}
	
	/**
	 * Exibe uma mensagem de erro no header do admin
	 * 
	 * @since 0.1
	 * 
	 * @param string $message - The message ID
	 * @return void
	 */
	static function show_error($message) {
		// Carrega a mensagem
		$msg = $this->messages[$message];
		
		// Modifica a mensagem (quando necessário)
		switch ($message) {
			case 'phpversion':
				$msg['text'] = sprintf($msg['text'], WPMediaCategory_REQUIRE_PHP_VERSION);
				break;
		}
		
		// Define a classe da mensagem
		$class = (isset($msg['error']) && $msg['error']) ? 'error' : 'updated';
		
		// Insere a mensagem
		echo '<div id="message" class="'. $class .'"><p><strong>'. $msg['text'] .'</strong></p></div>';
	}
	
	/**
	 * Desabilita o flash uploader
	 * 
	 * @since 0.1
	 * 
	 * @return boolean
	 */
	public function remove_flash_uploader() { return false; }
}

$WPMediaCategory = new WPMediaCategory();

function showError_phpversion() { WPMediaCategory::show_error('phpversion'); }
function showError_json() { WPMediaCategory::show_error('json'); }

?>