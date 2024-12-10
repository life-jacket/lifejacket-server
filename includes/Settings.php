<?php
namespace LifeJacket\Server;

class Settings {
	protected $rest_prefix = 'lifejacket-server/v1';

	public function init() {
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
		add_action( 'rest_api_init', [ $this, 'register_rest' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets' ] );
	}

	public function register_menu() {
		add_options_page(
			__( 'LifeJacket Server', 'lifejacket' ),
			__( 'LifeJacket Server', 'lifejacket' ),
			'manage_options',
			'lifejacket-server',
			[ $this, 'render_settings' ]
		);
	}

	public function register_rest() {
		register_rest_route(
			$this->rest_prefix,
			'/settings',
			array(
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_options' ],
				'permission_callback' => [ $this, 'get_permissions' ],
			)
		);
		register_rest_route(
			$this->rest_prefix,
			'/settings',
			array(
				'methods'             => 'POST',
				'callback'            => [ $this, 'set_options' ],
				'permission_callback' => [ $this, 'get_permissions' ],
			)
		);
	}

	public function get_permissions() {
		return current_user_can( 'manage_options' );
	}

	public function get_options() {
		$option_names = [
			'require_auth',
			'collect_stats',
		];
		$options      = [];
		$sources      = [];
		foreach ( $option_names as $name ) {
			$option           = Plugin::get_instance()->options->get_with_source( $name );
			$options[ $name ] = $option['value'];
			$sources[ $name ] = $option['source'];
		}
		$response = new \WP_REST_Response(
			[
				'values'  => $options,
				'sources' => $sources,
			]
		);
		return $response;
	}

	public function set_options( $request ) {
		$options = $request->get_json_params();
		update_option( 'lifejacket_server', $options );
		$response = new \WP_REST_Response( 'Data successfully added.', '200' );
		return $response;
	}

	public function admin_assets() {
		$asset = require_once LIFEJACKET_SERVER_PLUGIN_PATH . '/build/index.asset.php';
		wp_register_script(
			'lifejacket-server-settings',
			LIFEJACKET_SERVER_PLUGIN_URL . '/build/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);
		wp_register_style(
			'lifejacket-server-settings',
			LIFEJACKET_SERVER_PLUGIN_URL . '/build/index.css',
			[ 'wp-components' ],
			$asset['version']
		);
		// todo - conditionally load
		wp_enqueue_script( 'lifejacket-server-settings' );
		wp_enqueue_style( 'lifejacket-server-settings' );
	}

	public function render_settings() {
		echo '<div id="lifejacket-server-settings"></div>';
	}
}
