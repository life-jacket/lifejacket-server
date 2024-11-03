<?php
/**
 * Plugin Name: LifeJacket Server
 * Version: 0.1.0
 */

require_once __DIR__ . '/vendor/autoload.php';

add_action( 'plugins_loaded', 'lifejacket_server' );

function lifejacket_server() {
    return LifeJacket\Server\Plugin::get_instance();
}