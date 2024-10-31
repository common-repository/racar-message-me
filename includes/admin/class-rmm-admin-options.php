<?php 
/*
*
* class Options Page
* class_Admin_Options.php
*
*/
/**
 * Prevent direct access to the script.
 */
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'rmm_Admin_Options' ) ) {	
	class rmm_Admin_Options {
		/**
		 * Holds the values to be used in the fields callbacks
		 */
		private $options;

		private	$menu_title = 'RaCar Plugins';
		private	$capability = 'manage_options';
		private	$menu_slug = 'racar-admin-page.php';
		private	$function_main_menu = 'racar_admin_page'; // if altering this, alter throughout this file.
		private	$icon_url = 'dashicons-lightbulb'; //dashicons
		private	$position = 99; 
		
		private $sub_page_title = 'RMM RaCar Message Me';
		private $sub_menu_title = 'Message Me';
		private $capability_sub = 'manage_options';
		private $page_url = 'rmm-config';
		private $function_sub_page = 'rmm_options_page'; // if altering this, alter throughout this file.

		/**
		 * Start up
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'racar_main_admin_menu' ) );
			add_action( 'admin_menu', array( $this, 'rmm_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'remove_admin_submenu' ) );
			add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
		}

		public function racar_main_admin_menu() {
			global $menu;
			global $submenu;
			global $rmm_plugin_name;
			$page_title = "$rmm_plugin_name Options Page";
			if( empty( $GLOBALS['admin_page_hooks']['racar-admin-page.php'] ) ) {
				add_menu_page( 
					$page_title, 
					$this->menu_title, 
					$this->capability, 
					'racar-admin-page.php', 
					array( $this , $this->function_main_menu ),
					$this->icon_url, 
					$this->position
				);
//				remove_submenu_page( 'racar-admin-page.php' , 'racar-admin-page.php' );
			}	
		}
		
		public function remove_admin_submenu() {
			remove_submenu_page( 'racar-admin-page.php' , 'racar-admin-page.php' );
		}
		
		public function racar_admin_page(){
			?>
			<div class="wrap">
				<h1>Plugins RaCar</h1>
				
			</div>
			<?php
		}
		public function rmm_admin_menu() {
			global $rmm_page_hook;
			$rmm_page_hook = add_submenu_page( 
				'racar-admin-page.php',
				$this->sub_page_title,
				$this->sub_menu_title,
				$this->capability_sub,
				$this->page_url,
				array( $this , $this->function_sub_page )
			);
		}
		
		public function rmm_options_page() { 
			global $rmm_plugin_name;
			global $rmm_VERSION;
			?>
				<h2><?php echo esc_html( $rmm_plugin_name ) . ' v.' . esc_html( $rmm_VERSION ); ?></h2>
			<?php 
			// Set class property
			$this->options = get_option( 'rmm_settings' );

			$active_tab = "general-settings";
			if( isset( $_GET["tab"] ) ) {
				switch( $_GET["tab"] ) {

					case 'second-settings':
						$active_tab = "second-settings";
						break;

					case 'third-settings':
						$active_tab = "third-settings";
						break;

					default:
						$active_tab = "general-settings";
						break;
				} 
			} else {
				$active_tab = "general-settings";
			}
			?>
				<h2 class="nav-tab-wrapper">
	                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
	                <?php _e('General Settings', 'racar-message-me'); ?>
	                
	            </h2>
				<form action='options.php' method='post'>
					<?php 
					settings_fields( 'rmm_option_group_1' );
					do_settings_sections( 'rmm-options-page' );
					submit_button();
					?>
				</form>
			<?php
		}		
		
		public function register_plugin_settings() { 
			register_setting(
				'rmm_option_group_1',  // Option group
				'rmm_settings' , // Options name
				array( $this, 'sanitize' ) // Sanitize 
			);

			// if( isset( $_GET["tab"] ) ) {
			// 	switch( $_GET["tab"] ) {
			// 		case "second-settings":
			// 			self::get_second_settings();
			// 		break;
			// 		case "third-settings":
			// 			self::get_third_settings();
			// 		break;
			// 		default:  //general-settings
			// 			self::get_default_settings();
			// 		break;
			// 	}
			// } else {
				self::get_default_settings();
			// }
		}
		
		
		public function get_default_settings() {
			add_settings_section(
				'rmm_section_1', // ID
				__( '', 'racar-message-me' ), // title
				array( $this, 'plugin_settings_section_1_callback' ), // callback
				'rmm-options-page' // Page
			);

			add_settings_field( 
				'rmm_button_side', 
				__( 'Buttons side of the screen', 'racar-message-me' ), 
				array( $this , 'rmm_button_side_render' ), 
				'rmm-options-page', 
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_main_text', 
				__( 'Main button text on hover', 'racar-message-me' ), 
				array( $this , 'rmm_main_text_render' ), 
				'rmm-options-page', 
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_main_background_color',
				__( 'Main Button Background Color', 'racar-message-me' ),
				array( $this, 'rmm_main_background_color_render' ),
				'rmm-options-page',
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_main_icon_color',
				__( 'Main Button Icon Color', 'racar-message-me' ),
				array( $this, 'rmm_main_icon_color_render' ),
				'rmm-options-page',
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_whatsapp_number',
				'<span class="option-name">' . __( 'WhatsApp number', 'racar-message-me' ) . '<span class="quick-exp">(' . __( 'Make sure to type the country and city code before the number', 'racar-message-me' ) . ')</span></span>',
				array( $this, 'rmm_whatsapp_number_render' ),
				'rmm-options-page',
				'rmm_section_1' 
			);


			add_settings_field( 
				'rmm_messenger_username', 
				__( 'Messenger Usertag', 'racar-message-me' ), 
				array( $this , 'rmm_messenger_username_render' ), 
				'rmm-options-page', 
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_instagram_username', 
				__( 'Instagram Usertag', 'racar-message-me' ), 
				array( $this , 'rmm_instagram_username_render' ), 
				'rmm-options-page', 
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_skype_username', 
				__( 'Skype Name', 'racar-message-me' ), 
				array( $this , 'rmm_skype_username_render' ), 
				'rmm-options-page', 
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_telegram_username', 
				__( 'Telegram Username', 'racar-message-me' ), 
				array( $this , 'rmm_telegram_username_render' ), 
				'rmm-options-page', 
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_rate',
				__( 'Plugin Rating', 'racar-message-me' ),
				array( $this, 'rmm_rate_render' ),
				'rmm-options-page',
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_support',
				__( 'Support', 'racar-message-me' ),
				array( $this, 'rmm_support_render' ),
				'rmm-options-page',
				'rmm_section_1' 
			);

			add_settings_field( 
				'rmm_donate',
				__( 'Show your Appreciation', 'racar-message-me' ),
				array( $this, 'rmm_donate_render' ),
				'rmm-options-page',
				'rmm_section_1' 
			);

			add_settings_section(
				'rmm_section_2', // ID
				__( '', 'racar-message-me' ), // title
				array( $this, 'plugin_settings_section_2_callback' ), // callback
				'rmm-options-page' // Page
			);

		}

		

		/**
		 * Sanitize each setting field as needed
		 *
		 * @param array $input Contains all settings fields as array keys
		 */
		public function sanitize( $input ) {
			$new_input = array();

			if( isset( $input['rmm_button_side'] ) )
				$new_input['rmm_button_side'] = sanitize_text_field( $input['rmm_button_side'] );

			if( isset( $input['rmm_main_text'] ) )
				$new_input['rmm_main_text'] = sanitize_text_field( $input['rmm_main_text'] );

			if( isset( $input['rmm_main_background_color'] ) && ! empty( $input['rmm_main_background_color'] ) ) {
				$textcolor = trim( $input['rmm_main_background_color'] );
				$textcolor = strip_tags( stripslashes( $textcolor ) );
				$txt_color = '';
				if( '#' != $textcolor[0]) {
					$textcolor = '#' . $textcolor;
				}
				$new_input['rmm_main_background_color'] = sanitize_hex_color( $textcolor );
			}

			if( isset( $input['rmm_main_icon_color'] ) && ! empty( $input['rmm_main_icon_color'] ) ) {
				$textcolor = trim( $input['rmm_main_icon_color'] );
				$textcolor = strip_tags( stripslashes( $textcolor ) );
				$txt_color = '';
				if( '#' != $textcolor[0]) {
					$textcolor = '#' . $textcolor;
				}
				$new_input['rmm_main_icon_color'] = sanitize_hex_color( $textcolor );
			}

			if( isset( $input['rmm_whatsapp_number'] ) )
				$new_input['rmm_whatsapp_number'] = sanitize_text_field( $input['rmm_whatsapp_number'] );
			
			if( isset( $input['rmm_messenger_username'] ) )
				$new_input['rmm_messenger_username'] = sanitize_text_field( $input['rmm_messenger_username'] );

			if( isset( $input['rmm_instagram_username'] ) )
				$new_input['rmm_instagram_username'] = sanitize_text_field( $input['rmm_instagram_username'] );

			if( isset( $input['rmm_skype_username'] ) )
				$new_input['rmm_skype_username'] = sanitize_text_field( $input['rmm_skype_username'] );

			if( isset( $input['rmm_telegram_username'] ) )
				$new_input['rmm_telegram_username'] = sanitize_text_field( $input['rmm_telegram_username'] );

			return $new_input;
		}


		public function plugin_settings_section_1_callback() { 
			echo __( '', 'racar-message-me' );
		}

		
		public function plugin_settings_section_2_callback() { 
			echo '<br>';
			echo __( '<span class="attention">ATTENTION!</span> If the buttons do not show properly, please install the Font Awesome plugin at ', 'racar-message-me' );
			echo '<a href="https://wordpress.org/plugins/font-awesome/" target="_blank"> https://wordpress.org/plugins/font-awesome/</a>';
		}

		// public function plugin_settings_section_3_callback() { 
		// 	echo __( 'Enter your settings below:', 'racar-message-me' );
		// }

		public function rmm_button_side_render() {
			if( isset( $this->options["rmm_button_side"] ) ){
				echo __( 'Left' , 'racar-message-me' ) . ' <input type="radio" class="rmm_button_side" name="rmm_settings[rmm_button_side]" value="left"';
				echo 'left' == $this->options['rmm_button_side'] ? ' checked' : '';
				echo '/>';
				echo __( 'Right' , 'racar-message-me' ) . ' <input type="radio" class="rmm_button_side" name="rmm_settings[rmm_button_side]" value="right"';
				echo 'right' == $this->options['rmm_button_side'] ? ' checked' : '';
				echo '/>';
			} else {
				echo '<div><input type="radio" class="rmm_button_side" name="rmm_settings[rmm_button_side]" value="left"/>' . __( 'Left' , 'racar-message-me' ) . '</div>';
				echo '<div><input type="radio" class="rmm_button_side" name="rmm_settings[rmm_button_side]" value="right"/>' . __( 'Right' , 'racar-message-me' ) . '</div>';
			}
		}

		
		public function rmm_main_text_render() { 
			printf(
				'<input type="text" id="rmm_main_text" name="rmm_settings[rmm_main_text]" value="%s" placeholder="%s" />',
				isset( $this->options['rmm_main_text'] ) ? esc_html( $this->options['rmm_main_text']) : '',
				__( 'Chat with an attendant!' , 'racar-message-me' )
			);
		}

		public function rmm_main_background_color_render() {
			$val = '';
			if( isset( $this->options["rmm_main_background_color"] ) ){
				$val = $this->options['rmm_main_background_color'];
			}

			echo '<input type="text" id="rmm_main_background_color" class="rmm-colorpicker" name="rmm_settings[rmm_main_background_color]" value="' . esc_html( $val ) . '">';
		}

		public function rmm_main_icon_color_render() {
			$val = '';
			if( isset( $this->options["rmm_main_icon_color"] ) ){
				$val = $this->options['rmm_main_icon_color'];
			}
			
			echo '<input type="text" id="rmm_main_icon_color" class="rmm-colorpicker" name="rmm_settings[rmm_main_icon_color]" value="' . esc_html( $val ) . '">';
		}

		public function rmm_whatsapp_number_render() { 
			printf(
				'<input type="text" id="rmm_whatsapp_number" name="rmm_settings[rmm_whatsapp_number]" value="%s" placeholder="%s" />',
				isset( $this->options['rmm_whatsapp_number'] ) ? esc_html( $this->options['rmm_whatsapp_number']) : '',
				__( '5521989898989' , 'racar-message-me' )
			);
		}

		public function rmm_messenger_username_render() { 
			printf(
				'<input type="text" id="rmm_messenger_username" name="rmm_settings[rmm_messenger_username]" value="%s" placeholder="%s" />',
				isset( $this->options['rmm_messenger_username'] ) ? esc_html( $this->options['rmm_messenger_username']) : '',
				__( 'MyFacebookTag' , 'racar-message-me' )
			);
		}

		public function rmm_instagram_username_render() { 
			printf(
				'<input type="text" id="rmm_instagram_username" name="rmm_settings[rmm_instagram_username]" value="%s" placeholder="%s" />',
				isset( $this->options['rmm_instagram_username'] ) ? esc_html( $this->options['rmm_instagram_username']) : '',
				__( 'MyInstagramTag' , 'racar-message-me' )
			);
		}

		public function rmm_skype_username_render() { 
			printf(
				'<input type="text" id="rmm_skype_username" name="rmm_settings[rmm_skype_username]" value="%s" placeholder="%s" />',
				isset( $this->options['rmm_skype_username'] ) ? esc_html( $this->options['rmm_skype_username']) : '',
				__( 'MySkypeName' , 'racar-message-me' )
			);
		}

		public function rmm_telegram_username_render() { 
			printf(
				'<input type="text" id="rmm_telegram_username" name="rmm_settings[rmm_telegram_username]" value="%s" placeholder="%s" />',
				isset( $this->options['rmm_telegram_username'] ) ? esc_html( $this->options['rmm_telegram_username']) : '',
				__( 'MyTelegramName' , 'racar-message-me' )
			);
		}

		public function rmm_rate_render() {
			global $allowed_html;
			$html = __( 'Do you like this plugin? Please tell me what you like about it ' , 'racar-message-me' ) . ' <a href="https://wordpress.org/plugins/racar-message-me/#reviews" target="_blank">' . __('here' , 'racar-message-me') . '</a>';
			echo wp_kses( $html , $allowed_html );
		}

		public function rmm_support_render() {
			global $allowed_html;
			$html = __( 'Do you need help with this plugin? Please open a ticket' , 'racar-message-me' ) . ' <a href="//wordpress.org/support/plugin/racar-message-me/" target="_blank">' . __('here' , 'racar-message-me') . '</a>';
			echo wp_kses( $html , $allowed_html );
		}

		public function rmm_donate_render() {
			global $allowed_html;
			$html = __( 'Do you want to show your love? Please buy me some coffee' , 'racar-message-me' ) . ' <a href="https://www.paypal.com/paypalme/RafaCarvalhido" target="_blank">' . __('by clicking here' , 'racar-message-me') . '</a>';
			echo wp_kses( $html , $allowed_html );
		}
		
	}
}

$my_settings_page = new rmm_Admin_Options();
