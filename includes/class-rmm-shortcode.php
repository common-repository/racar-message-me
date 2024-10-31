<?php 
/*
*
* class Shorcode
* class-rmm-shortcode.php
*
*/
/**
 * Prevent direct access to the script.
 */
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'rmm_Shortcode' ) ) {	
	class rmm_Shortcode {
		
		public static function init() {
			add_shortcode( 'rmm_shortcode_code_to_paste', array( __CLASS__ , 'get_the_shortcode' ) , 10 , 1 );
        }

		/*public function error_msg( $type , $pid ) {
			$error_msg = __( 'Please review the featured post group of id $s' , 'rpo-textdomain' );
			$error_msg = printf( esc_html__( 'Please review the featured post group of id %1$s. Seems like a $type or more weren´t selected' , 'rpo-domain' ), $pid , $type );
			return $error_msg;
		}*/

		public static function get_the_shortcode( $atts ) {
			$html = '';
			/*if( $atts ) {
				$pid = $atts['id'];
				$html = '<div id="rpg-initiative-calculator">Shortcode '.$pid.' Output with</div>';
			} else {
				$html = '<div id="rpg-initiative-calculator">Shortcode Output only</div>';
			}*/

			$html = '
				<style type="text/css">
					#rpg-initiative-calculator .top {
					    display: inline-flex;
					    justify-content: space-around;
					    width: 100%;
					}
				</style>
			';

			$html .= '
				<script type="text/javascript">
					$(function () {
					    $(\'input[name="weapon-speed"]\').hide();

					    //show it when the checkbox is clicked
					    $(\'input[name="weapon"]\').on(\'click\', function () {
					        if ($(this).prop(\'checked\')) {
					            $(\'input[name="weapon-speed"]\').fadeIn();
					        } else {
					            $(\'input[name="weapon-speed"]\').hide();
					        }
					    });
					});
				</script>
			';
			
			$html .= '
				<div id="rpg-initiative-calculator">
					<div class="calc-frm">
						<form id="pre-calc" method="post" action="">
							<div class="top">
								<div class="quadro1">
									<h4>Fator Inicial</h4>
									<input type="number" name="d10" />
									<input type="text" name="d10" />
									<input type="number" name="d10" />
									<label for="d10">d10</label>
								</div>
								<div class="quadro2">
									<h4>Modificadores Padrão</h4>
									<div class="mod-option">
										<input type="checkbox" id="hasted" name="hasted">
										<label for="hasted">Hasted</label>
									</div>
									<div class="mod-option">
										<input type="checkbox" id="slowed" name="slowed">
										<label for="slowed">Slowed</label>
									</div>
								</div>
								<div class="quadro3">
									<h4>Modificadores Opcionais</h4>
									<div class="mod-option">
										<input type="checkbox" id="weapon" name="weapon">
										<label for="weapon">Weapon</label>
										<input type="number" id="weapon-speed" name="weapon-speed">
										<label for="weapon-speed">Weapon Speed</label>
									</div>
									<div class="mod-option">
										<input type="checkbox" id="breath-weapon" name="breath-weapon">
										<label for="breath-weapon">Breath Weapon</label>
									</div>
								</div>
							</div>
						</form>
						<div class="bottom">
							bottom
						</div>
					</div>
				</div>
			';




			return $html;

		}


		
	} // end of class
} //endif class

$rmm_shortcode = new rmm_Shortcode();