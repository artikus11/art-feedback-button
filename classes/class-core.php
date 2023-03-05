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
	 * Instance of Selection_Autoparts.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private static ?object $instance = null;

	/**
	 * @var string
	 */
	private string $suffix;

	/**
	 * @var \ART\AFB\Rest
	 */
	protected Rest $rest;

	/**
	 * @var \ART\AFB\Shortcode
	 */
	protected Shortcode $shortcode;

	/**
	 * @var \ART\AFB\Fields
	 */
	public Fields $fields;


	/**
	 * Construct.
	 */
	protected function __construct() {

		$this->setup_hooks();

		$this->updater_init();

		$this->fields = new Fields();
	}


	/**
	 * Init.
	 * Initialize plugin parts.
	 *
	 * @since 1.8.0
	 */
	public function setup_hooks(): void {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );

	}


	/**
	 * Подключeние стилей и скриптов
	 *
	 * @return void
	 */
	public function enqueue(): void {

		wp_register_style(
			'afb-style-shortcode',
			AFB_PLUGIN_URI . 'assets/css/style-afb-shortcode.' . $this->suffix . 'css',
			[],
			AFB_PLUGIN_VER
		);

		wp_register_script(
			'afb-script-shortcode',
			AFB_PLUGIN_URI . 'assets/js/script-afb-shortcode.' . $this->suffix . 'js',
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
	 * @return string
	 */
	public function plugin_url(): string {

		return untrailingslashit( plugins_url( '/', AFB_PLUGIN_FILE ) );
	}


	/**
	 * @return string
	 */
	public function plugin_path(): string {

		return untrailingslashit( AFB_PLUGIN_DIR );
	}


	/**
	 * @return string
	 */
	public function template_path(): string {

		return apply_filters( 'afb_template_path', 'art-feedback-button/' );
	}

	private function updater_init(): void {

		$updater = new Updater( AFB_PLUGIN_AFILE );
		$updater->set_repository( 'art-feedback-button' );
		$updater->set_username( 'artikus11' );
		$updater->set_authorize( 'Z2hwX3FmOHVsOXJVV2pSaVFUVjd3MXVybkpVbWNVT3VCbzBNV0ZCWA==' );
		$updater->init();
	}
	/**
	 * Instance.
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
