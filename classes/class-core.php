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
	private static ?Core $instance = null;

	/**
	 * @var \ART\AFB\Templater
	 */
	protected Templater $template;

	/**
	 * @var \ART\AFB\Fields
	 */
	public Fields $fields;


	/**
	 * Construct.
	 */
	protected function __construct() {

		( new Rest )->setup_hooks();
		( new Shortcode )->setup_hooks();
		( new Enqueue() )->setup_hooks();

		$this->updater_init();

		$this->fields   = new Fields();
		$this->template = new Templater();
	}


	/**
	 * @param $template_name
	 *
	 * @return string
	 */
	public function get_template( $template_name ): string {

		return $this->template->get_template( $template_name );
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
