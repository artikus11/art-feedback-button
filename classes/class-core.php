<?php

namespace ART\AFB;

/**
 * Class AFB
 * Main AFB class, initialized the plugin
 *
 * @class       AFB
 * @version     1.0.0
 * @author      Artem Abramovich
 */
class Core {

	/**
	 * Instance of AFB.
	 *
	 * @since  1.8.0
	 * @access private
	 * @var object $instance The instance of AFB.
	 */
	private static object $instance;

	/**
	 * @var string
	 */
	private string $suffix;

	/**
	 * @var \ART\AFB\Rest
	 */
	protected Rest $rest;


	/**
	 * Construct.
	 */
	public function __construct() {

		$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : 'min.';

		$this->setup_hooks();

		$this->rest = new Rest;
		$this->rest->setup_hooks();
	}


	/**
	 * Init.
	 * Initialize plugin parts.
	 *
	 * @since 1.8.0
	 */
	public function setup_hooks() {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );

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
				'url'   => rest_url( 'callback/api/v1/window' ),
				'class' => '',
			]
		);

		return ob_get_clean();
	}


	/**
	 * Подключeние стилей и скриптов
	 *
	 * @return void
	 */
	public function enqueue() {

		wp_register_style(
			'afb-style-shortcode',
			AFB_PLUGIN_URI . 'assets/css/style-afb-shortcode.' . $this->suffix . 'css',
			[],
			AFB_PLUGIN_VER
		);

		wp_register_script(
			'afb-script-shortcode',
			AFB_PLUGIN_URI . 'assets/js/script-afb-shortcode.js',
			[ 'jquery', 'afb-script-modal', 'afb-script-mask' ],
			AFB_PLUGIN_VER,
			true
		);

		wp_register_script(
			'afb-script-modal',
			AFB_PLUGIN_URI . 'assets/js/micromodal.' . $this->suffix . 'js',
			[ 'jquery' ],
			AFB_PLUGIN_VER,
			true
		);

		wp_register_script(
			'afb-script-mask',
			AFB_PLUGIN_URI . 'assets/js/vanilla-masker.' . $this->suffix . 'js',
			[ 'jquery' ],
			AFB_PLUGIN_VER,
			true
		);

	}


	/**
	 * Instance.
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @return object Instance of the class.
	 * @since 1.8.0
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

		return self::$instance;

	}

}
