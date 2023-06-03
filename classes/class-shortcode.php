<?php

namespace ART\AFB;

class Shortcode {

	public function setup_hooks(): void {

		add_shortcode( 'afb', [ $this, 'button' ] );

		add_action( 'wp_footer', array( $this, 'add_modal_wrapper' ) );
	}


	/**
	 * @return void
	 *
	 * @todo переделать вызов кнопки - по нажатию показывать что окно открылось потом только загружать форму
	 */
	public function add_modal_wrapper(): void{
		load_template(
			afb()->get_template( 'modal.php' ),
		);
	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function button( $atts ) {

		wp_enqueue_style( 'afb-styles' );
		wp_enqueue_script( 'afb-scripts' );

		$atts = shortcode_atts(
			[
				'label'  => 'Заказать звонок',
				'class'  => '',
				'emails' => '',
			],
			$atts
		);

		ob_start();

		load_template(
			afb()->get_template( 'button.php' ),
			false,
			[
				'label'  => $atts['label'] ?? 'Заказать звонок',
				'url'    => rest_url( 'afb/v1/window' ),
				'class'  => $atts['class'] ?? '',
				'emails' => $atts['emails'] ? base64_encode( $atts['emails'] ) : '',
			]
		);

		return ob_get_clean();
	}

}