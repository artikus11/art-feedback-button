<?php
namespace ART\AFB;
/**
 * Class AFB
 * Main AFB class, initialized the plugin
 *
 * @class       AFB
 * @version     1.8.0
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
	private static $instance;

	/**
	 * Construct.
	 */
	public function __construct() {

		$this->includes();

		$this->init();
	}


	/**
	 * Load plugin parts.
	 *
	 * @since 2.0.0
	 */
	private function includes() {

		require AFB_PLUGIN_DIR . '/includes/customizer/customizer.php';
	}


	/**
	 * Init.
	 * Initialize plugin parts.
	 *
	 * @since 1.8.0
	 */
	public function init() {

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
