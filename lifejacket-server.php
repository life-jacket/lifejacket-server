<?php
/**
 * Plugin Name: LifeJacket Server
 * Version: 0.2.0
 * Plugin URI: https://github.com/life-jacket/lifejacket-server
 * Description: A custom update server in your WordPress site. Part of LifeJacket project.
 * Author: LifeJacket
 * Author URI: https://github.com/life-jacket
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

require_once __DIR__ . '/vendor/autoload.php';

define( 'LIFEJACKET_SERVER_PLUGIN_PATH', __DIR__ );
define( 'LIFEJACKET_SERVER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', 'lifejacket_server' );

/**
 * Plugin initalization
 *
 * @return LifeJacket\Server\Plugin
 */
function lifejacket_server(): LifeJacket\Server\Plugin {
	return LifeJacket\Server\Plugin::get_instance();
}
