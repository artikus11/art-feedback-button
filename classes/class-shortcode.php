<?php

namespace ART\AFB;

class Shortcode {

	public function setup_hooks() {

		add_shortcode( 'afb', [ $this, 'button' ] );
	}


	public function button( $atts ) {

		wp_enqueue_style( 'afb-style-shortcode' );
		wp_enqueue_script( 'afb-script-shortcode' );
		wp_enqueue_script( 'afb-script-modal' );
		wp_enqueue_script( 'afb-script-mask' );

		$atts = shortcode_atts(
			[
				'label' => 'Заказать звонок',
				'class' => '',
				'emails' => '',
			],
			$atts
		);

		ob_start();

		load_template(
			AFB_PLUGIN_DIR . '/templates/button.php',
			true,
			[
				'label' => $atts['label'] ?? 'Заказать звонок',
				'url'   => rest_url( 'afb/v1/window' ),
				'class' => $atts['class'] ?? '',
				'emails' => $atts['emails'] ? base64_encode($atts['emails']) : '',
			]
		);

		return ob_get_clean();
	}

}