<?php
/*
*
* class PLUGIN´S ACTION LIST
* class-rmm_Action_List.php
*
*/
/**
 * Prevent direct access to the script.
 */
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'rmm_Action_List' ) ) {	
	class rmm_Action_List {

		function __construct() {
			add_filter( 'plugin_action_links' , array( $this , 'add_plugin_action_links' ) , 10 , 2 );
		}
		
		
		public function add_plugin_action_links( $links , $file ) {
			global $rmm_basename;
			static $this_plugin;
			global $rmm_OPTIONSON;

			if( ! $this_plugin ) {
				$this_plugin = $rmm_basename;
			}

			// check to make sure we are on the correct plugin
			if( $file == $this_plugin ) {	
				$plugin_links = array();
				// check if plugin has options page and add address
				if( TRUE === $rmm_OPTIONSON ) {
					// link to what ever you want
					//$plugin_links[] = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/widgets.php">Widgets</a>';
					$plugin_links[] = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=rmm-config">' . __( 'Settings' , 'racar-message-me') . '</a>';
					// add the links to the list of links already there
					
				}
				foreach( $plugin_links as $link ) {
					array_unshift( $links , $link );
				}
				// This will be the last link on line
				$links[] = '<a href="https://www.paypal.me/RafaCarvalhido" class="racar-donate" target="_blank">' . esc_html__( 'Donate' , 'racar-message-me') . '</a>';
			}
			return $links;
		}
	}
}

$rmm_Action_List = new rmm_Action_List();