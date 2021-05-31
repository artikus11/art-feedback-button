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
	public function __construct() {

		$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : 'min.';

		$this->setup_hooks();

		$this->rest = new Rest;
		$this->rest->setup_hooks();

		$this->shortcode = new Shortcode;
		$this->shortcode->setup_hooks();
		
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


	/**
	 * @param  string $template_name
	 *
	 * @return string
	 */
	public function get_template( string $template_name ): string {

		$template_path = locate_template( afb()->template_path() . $template_name );

		if ( ! $template_path ) {
			$template_path = sprintf( "%s/templates/%s", afb()->plugin_path(), $template_name );
		}

		return $template_path;
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
