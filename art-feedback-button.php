<?php
/**
 * Plugin Name: Art Feedback Button
 * Plugin URI: wpruse.ru
 * Text Domain: art-feedback-button
 * Domain Path: /languages
 * Description: Плагин обратного звонка. Выводит шорткодом нопку обратного звонка.
 * Version: 1.0.1
 * Author: Artem Abramovich
 * Author URI: https://wpruse.ru/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Copyright Artem Abramovich
 *
 *     This file is part of Art Feedback Button,
 *     a plugin for WordPress.
 *
 *     Art Feedback Button is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 3 of the License, or (at your option)
 *     any later version.
 *
 *     Art Feedback Button is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

namespace ART\AFB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_data = get_file_data(
	__FILE__,
	array(
		'ver'  => 'Version',
		'name' => 'Plugin Name',
	)
);

define( 'AFB_PLUGIN_DIR', __DIR__ );
define( 'AFB_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'AFB_PLUGIN_FILE', plugin_basename( __FILE__ ) );

define( 'AFB_PLUGIN_VER', $plugin_data['ver'] );
define( 'AFB_PLUGIN_NAME', $plugin_data['name'] );

require AFB_PLUGIN_DIR . '/vendor/autoload.php';

$afb = new Core();
