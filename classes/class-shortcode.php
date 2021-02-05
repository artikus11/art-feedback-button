<?php

namespace ART\AFB;

class Shortcode {

	public function setup_hooks() {

		add_shortcode( 'afb', [ $this, 'button' ] );
	}


	public function button() {

		wp_enqueue_style( 'afb-style-shortcode' );
		wp_enqueue_script( 'afb-script-shortcode' );
		wp_enqueue_script( 'afb-script-modal' );
		wp_enqueue_script( 'afb-script-mask' );

		ob_start();

		load_template(
			AFB_PLUGIN_DIR . '/templates/button.php',
			true,
			[
				'label' => 'Заказать звонок',
				'url'   => rest_url( 'afb/v1/window' ),
				'class' => '',
			]
		);

		return ob_get_clean();
	}

}