<?php 
/**
 *  
 * includes/class-rmm-plugin.php
 *  
 * @package RaCar Plugin Name
 */

if( ! class_exists( 'rmm_Plugin' ) ) {
	class rmm_Plugin {
	
		public static function init() {
			self::includes();

			add_action( 'init', array( __CLASS__ , 'rmm_load_textdomain' ) );

			//only front end
			if ( false === is_admin() ) {
				add_action( 'wp_enqueue_scripts', array( __CLASS__ , 'rmm_register_frontend_resources' ) );

				//front end logic goes here
				add_action( 'wp_footer' , array( __CLASS__ , 'racar_add_zap_button' ) );
				add_filter( 'kses_allowed_protocols' , array( __CLASS__ , 'add_skype_proto' ) );
			}

			//only back end
			if ( true === is_admin() ) {
				add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'register_admin_resources' ) ) ;
				//back end logic goes here
			}

		}



	
		/**
		 * Includes.
		 */
		private static function includes() {
			/*if ( false === is_admin() ) {

			}*/

			if ( true === is_admin() ) {
				global $rmm_OPTIONSON;
				if( $rmm_OPTIONSON ) {
					include_once 'admin/class-rmm-admin-options.php';
					include_once 'admin/class-rmm-action-list.php';
				}
			}
		}

		/**
		 * Front end Resources
		 * @since release
		 */
		public static function rmm_register_frontend_resources(){
			self::rmm_frontend_script_files();
			self::rmm_frontend_style_files();
		}
		
		/**
		 * Front end Scripts
		 * @since release
		 */
		public static function rmm_frontend_script_files() {
			global $rmm_NOME_JAVASCRIPT;
			global $rmm_DIR_JAVASCRIPT;
			global $rmm_EXT_JAVASCRIPT;
			wp_register_script( $rmm_NOME_JAVASCRIPT, $rmm_DIR_JAVASCRIPT.$rmm_NOME_JAVASCRIPT.$rmm_EXT_JAVASCRIPT, array('jquery') );
			wp_enqueue_script( $rmm_NOME_JAVASCRIPT );
			
		}
		/**
		 * Front end Styles
		 * @since release
		 */
		public static function rmm_frontend_style_files() {
			global $rmm_NOME_STYLESHEET;
			global $rmm_DIR_STYLESHEET;
			global $rmm_EXT_STYLESHEET;
			// Respects SSL, Style.css is relative to the current file
			wp_register_style( $rmm_NOME_STYLESHEET, $rmm_DIR_STYLESHEET.$rmm_NOME_STYLESHEET.$rmm_EXT_STYLESHEET );
			wp_enqueue_style( $rmm_NOME_STYLESHEET );

			wp_register_style( 'racar-fontawesome' , 'https://use.fontawesome.com/releases/v5.8.2/css/all.css' );
			wp_enqueue_style( 'racar-fontawesome' );
		}

		
		/**
		 * Admin Resources
		 * @since release
		 */
		public static function register_admin_resources( $hook ) {
			global $rmm_page_hook;
			if( $hook != $rmm_page_hook ) return;
			self::rmm_register_admin_scripts();
			self::rmm_register_admin_styles();
		}

		/**
		 * Admin Styles
		 * @since release
		 */
		public static function rmm_register_admin_styles() {
			global $rmm_NOME_ADMIN_STYLESHEET;
			global $rmm_DIR_ADMIN_STYLESHEET;
			global $rmm_EXT_ADMIN_STYLESHEET;
			wp_register_style( $rmm_NOME_ADMIN_STYLESHEET, $rmm_DIR_ADMIN_STYLESHEET.$rmm_NOME_ADMIN_STYLESHEET.$rmm_EXT_ADMIN_STYLESHEET , array() , '0.9' );
			wp_enqueue_style( $rmm_NOME_ADMIN_STYLESHEET );
		}
		
		/**
		 * Admin Scripts
		 * @since release
		 */
		public static function rmm_register_admin_scripts() {
			global $rmm_NOME_ADMIN_JAVASCRIPT;
			global $rmm_DIR_ADMIN_JAVASCRIPT;
			global $rmm_EXT_ADMIN_JAVASCRIPT;
			wp_register_script( $rmm_NOME_ADMIN_JAVASCRIPT, $rmm_DIR_ADMIN_JAVASCRIPT.$rmm_NOME_ADMIN_JAVASCRIPT.$rmm_EXT_ADMIN_JAVASCRIPT , array( 'jquery', 'wp-color-picker' ) , '1.0' , true );
			wp_enqueue_script( $rmm_NOME_ADMIN_JAVASCRIPT );
		}
		
		
		/**
		 * Load plugin textdomain.
		 *
		 * @since 1.0.0
		 */
		public static function rmm_load_textdomain() {
			$textdomain_loaded = load_plugin_textdomain( 'racar-message-me', false, basename( dirname( __DIR__ ) ) . '/languages' ); //rmm-plugin-main-folder/languages
		}


		public static function racar_add_zap_button(){
			$settings = get_option( 'rmm_settings' );
			
			if( isset( $settings['rmm_button_side'] ) ) {
				$button_side = $settings['rmm_button_side'] . ': 30px;';
				$buttons_behind = $settings['rmm_button_side'] . ': 30px;';
				$title_side = $settings['rmm_button_side'] . ': 110%';
			} else {
				$button_side = 'left: 30px;';
				$buttons_behind = 'left: 30px;';
				$title_side = 'left: 110%';
			}
			$contacts = array();
			$main_icon_style = '';
			$wa_number = '';
    			if( isset( $settings['rmm_main_icon_color'] ) ) {
    				$main_icon_style .= 'color: ' . $settings['rmm_main_icon_color'] . '; ';
    			}

    			if( isset( $settings['rmm_main_background_color'] ) ) {
					$main_icon_style .= 'background: ' . $settings['rmm_main_background_color'] . '; ';
				}

				if( isset( $settings['rmm_main_text'] ) AND ! empty( $settings['rmm_main_text'] ) ) {
					$main_icon_title = $settings['rmm_main_text'];
				} else {
					$main_icon_title = 'Chat with an attendant!';
				}

				if( isset( $settings['rmm_whatsapp_number'] ) AND ! empty( $settings['rmm_whatsapp_number'] ) ) {
					$contacts['wa'] = '
						<div id="racar-whatsapp" class="racar-whatsapp">
							<a href="https://wa.me/' . $settings['rmm_whatsapp_number'] . '" title="" target="_blank" rel="nofollow">
								<i class="fab fa-whatsapp"></i>
							</a>
						</div>
					';
				}

				if( isset( $settings['rmm_messenger_username'] ) AND ! empty( $settings['rmm_messenger_username'] ) ) {
					$contacts['messenger'] = '
						<div id="racar-facebook" class="racar-facebook-messenger">
							<a href="https://m.me/' . $settings['rmm_messenger_username'] . '" title="" target="_blank" rel="nofollow">
								<i class="fab fa-facebook-messenger"></i>
							</a>
						</div>
					';
				}

				if( isset( $settings['rmm_instagram_username'] ) AND ! empty( $settings['rmm_instagram_username'] ) ) {
					$contacts['ig'] = '
						<div id="racar-instagram" class="racar-instagram">
							<a href="https://ig.me/m/' . $settings['rmm_instagram_username'] . '" title="" target="_blank" rel="nofollow">
								<i class="fab fa-instagram"></i>
							</a>
						</div>
					';
				}

				if( isset( $settings['rmm_skype_username'] ) AND ! empty( $settings['rmm_skype_username'] ) ) {
					$contacts['skype'] = '
						<div id="racar-skype" class="racar-skype">
							<a href="skype:'. $settings['rmm_skype_username'] .'?chat" title="" rel="nofollow">
								<i class="fab fa-skype"></i>
							</a>
						</div>
					';
				}

				if( isset( $settings['rmm_telegram_username'] ) AND ! empty( $settings['rmm_telegram_username'] ) ) {
					$contacts['tg'] = '
						<div id="racar-telegram" class="racar-telegram">
							<a href="//t.me/' . $settings['rmm_telegram_username'] . '" title="" target="_blank" rel="nofollow">
								<i class="fab fa-telegram"></i>
							</a>
						</div>
					';
				}

		?>
			<style type="text/css">
				#racar-contactme .fa-comment-dots:before, [data-title]:after {
					<?php echo esc_html($main_icon_style); ?>
				}
				#racar-contactme, .rmm-item {
					<?php echo esc_html($button_side); ?>
				}
				.behind {
					<?php echo esc_html($buttons_behind); ?>
				}
				[data-title]:after {
					<?php echo esc_html($title_side); ?>
				}
			</style>
			<div id="racar-contact-frm">
				<div id="racar-contactme">
					<a href="javascript:void(0);" data-title="<?php echo esc_html($main_icon_title); ?>" rel="nofollow">
					<i class="far fa-comment-dots"></i>
					</a>
				</div>
				<?php 
					$n = 1;
					global $allowed_html;
					foreach( $contacts as $key => $value ) {
						?>
							<div id="<?php echo esc_attr( "rmm-item-$n" ); ?>" class="rmm-item behind"><?php echo wp_kses( $value , $allowed_html ); ?></div>
						<?php 
						$n++;
					}
				?>
			</div>
		<?php 
		}

		public static function add_skype_proto( $protocols ) {
			$protocols[] = 'skype';
			return $protocols;
		}

	}
}



	