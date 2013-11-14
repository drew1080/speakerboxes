<?php
/*
Plugin Name:    Color Manager
Description:    Enable your clients or theme users to easily change the color scheme of your design. With live preview!
Version:        0.2
Author:         Hassan Derakhshandeh

		* 	Copyright (C) 2011  Hassan Derakhshandeh
		*	http://tween.ir/
		*	hassan.derakhshandeh@gmail.com

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( '-1' );

class Color_Manager {

	function __construct() {
		add_action( 'wp_head', array( &$this, 'css' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		add_action( 'admin_menu', array( &$this, 'admin' ) );
		add_action( 'customize_register', array( &$this, 'customize_register' ) );
	}

	function customize_register( $customizer ) {
		$options = get_option( 'colormanager', array() );
		if( isset( $options['colors'] ) && ! empty( $options['colors'] ) ) {
			foreach( $options['colors'] as $key => $option ) {
				$customizer->add_setting( 'colormanager[colors][' . $key . '][value]', array(
					'type' => 'option',
					'default' => $option['default']
				) );
				$customizer->add_control( new WP_Customize_Color_Control( $customizer, 'colormanager-' . $key, array(
					'label' => $option['label'],
					'section' => 'colors',
					'settings' => 'colormanager[colors][' . $key . '][value]'
				) ) );
			}
		}
	}

	function admin() {
		$page = add_theme_page( __( 'Color Manager' ), __( 'Color Manager' ), 'edit_theme_options', 'color-manager', array( &$this, 'manager' ) );
		add_action( "admin_print_styles-{$page}", array( &$this, 'admin_queue' ) );
	}

	function register_settings() {
		register_setting( 'color_settings', 'colormanager' );
	}

	function admin_queue() {
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'colormanager', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery', 'farbtastic' ), '0.2' );
		wp_localize_script( 'colormanager', 'colormanager', array(
			'count' => count( get_option( 'colormanager' ) )
		) );
	}

	function manager() {
		$options = get_option( 'colormanager' );
		require_once( dirname( __FILE__ ) . '/views/manager.php' );
	}

	function get_color( $option ) {
		if( $option['value'] )
			return $option['value'];
		return $option['default'];
	}

	function css() {
		if( $options = get_option( 'colormanager' ) ) {
			echo '<style id="colormanager-css">';
			foreach( $options['colors'] as $option ) {
				echo $option['selector'] . "{ " . $option['property'] . ': ' . $this->get_color( $option ) . " }\n";
			}
			echo '</style>';
		}
	}
}
$color_manager = new Color_Manager;