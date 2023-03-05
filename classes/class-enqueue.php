<?php
/**
 * Файл обработки скриптов и стилей
 *
 * @see     https://wpruse.ru
 * @package art-woocommerce-fast-order/classes
 * @version 1.0.0
 */

namespace ART\AFB;

class Enqueue {

	private string $suffix;


	public function __construct() {

		$this->suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}


	public function setup_hooks(): void {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ], 100 );

	}


	/**
	 * Подключаем нужные стили и скрипты
	 */
	public function enqueue(): void {
		wp_register_style(
			'afb-styles',
			AFB_PLUGIN_URI . 'assets/css/afb-styles.' . $this->suffix . 'css',
			[],
			AFB_PLUGIN_VER
		);

		wp_register_script(
			'afb-scripts',
			AFB_PLUGIN_URI . 'assets/js/afb-scripts.' . $this->suffix . 'js',
			[ 'jquery' ],
			AFB_PLUGIN_VER,
			true
		);
	}


}
