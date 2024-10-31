<?php 
/**
 * Plugin Name: RaCar Message Me
 * Plugin URI:  https://profissionalwp.dev.br/
 * Description: This plugin allows you to add a customizable button with your social networks inboxes (direct message). The button appears on the left bottom corner of your website. When clicked, it'll take the user to your social network private message inbox.
 * Version:     1.0.1
 * Author:      Rafa Carvalhido
 * Author URI:  https://profissionalwp.dev.br/blog/contato/rafa-carvalhido/
 * Text Domain: racar-message-me
 * Domain Path: /languages
 * Requires at least: 4.9.8
 * Tested up to: 6.3.1
 * WC requires at least: 3.0.0
 * WC tested up to: 8.1.1
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * Copyright © 2019-2023 Rafa Carvalhido
 * @package RaCar Message Me
 */
/*
RaCar Message Me is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
RaCar Message Me is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with RaCar Message Me.
*/

	/*=========================================================================*/ 
	/* SECURITY CHECKS */
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if ( ! defined( 'WPINC' ) ) die; // If this file is called directly, abort.
	
	//if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
	if( wp_doing_ajax() ) {
		return;
	}
	

	//start plugin
	if ( ! class_exists( 'rmm_Plugin' ) ) {
		include_once dirname( __FILE__ ) . '/includes/class-rmm-plugin.php';
		add_action( 'plugins_loaded', array( 'rmm_Plugin', 'init' ) );
	}
	
	
	/*=========================================================================*/
	// SYSTEM VARIABLES

	$rmm_plugin_name = "RaCar Message Me";
	$rmm_VERSION = '1.0.1';
	
	$rmm_NOME_STYLESHEET = 'rmm-stylesheet';
	//the below returns http://site-name.com/wp-contents/plugins/plugin-folder/css/
	$rmm_DIR_STYLESHEET = plugins_url('css/', __FILE__ );
	$rmm_EXT_STYLESHEET = '.css';
	
	$rmm_NOME_JAVASCRIPT = 'rmm-javascript';
	$rmm_DIR_JAVASCRIPT = plugins_url('js/', __FILE__ );
	$rmm_EXT_JAVASCRIPT = '.js';
	
	$rmm_OPTIONSON = TRUE;
	
	$rmm_NOME_ADMIN_STYLESHEET = 'rmm-admin-style';
	$rmm_DIR_ADMIN_STYLESHEET = plugins_url('includes/admin/css/', __FILE__ );
	$rmm_EXT_ADMIN_STYLESHEET = '.css';
	
	$rmm_NOME_ADMIN_JAVASCRIPT = 'rmm-admin-javascript';
	$rmm_DIR_ADMIN_JAVASCRIPT = plugins_url('includes/admin/js/', __FILE__ );
	$rmm_EXT_ADMIN_JAVASCRIPT = '.js';

	$allowed_html = array(
				'div' 	=> array(
							'class' => array(),
							'id'    => array(),
						),
				'a'		=> array(
							'href'		=> array(),
							'title'		=> array(),
							'target'	=> array(),
							'rel'		=> array(),
						),
				'i'		=> array(
							'class'		=> array()
						),
				'input'	=> array(
							'type'		=> array(),
							'class'		=> array(),
							'name'		=> array(),
							'value'		=> array(),
							'checked'	=> array()
						)
			);


	if ( ! defined( 'rmm_PLUGIN_FILE' ) ) {
		define( 'rmm_PLUGIN_FILE', __FILE__ );
		//complete\path-to-site\wp-content\plugins\racar-clear-cart-for-woocommerce\racar-clear-cart-for-woocommerce.php
	}
	if ( ! defined( 'rmm_PLUGIN_FOLDER' ) ) {
		define( 'rmm_PLUGIN_FOLDER', plugin_dir_path( __FILE__ ) );// Example: /home/user/public_html/wp-content/plugins/my-plugin/
	}

	//the below returns plugin-folder/this-file.php // Destrava o action list
	$rmm_basename = plugin_basename( __FILE__ );
	
	/*=========================================================================*/
	
	
?>