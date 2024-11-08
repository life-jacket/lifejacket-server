<?php
namespace LifeJacket\Server\REST;

abstract class Base {
	protected $namespace          = 'lifejacket/v1';
	protected $endpoint           = 'plugin/(?P<slug>[a-zA-Z0-9-]+)';
	protected $args               = [];
	protected $methods            = 'GET';
	protected $use_authentication = false;

	protected $url;

	public function __construct() {
		$this->args = [
			'methods'             => $this->get_methods(),
			'callback'            => [ $this, 'plugin_callback' ],
			'permission_callback' => [ $this, 'permission_callback' ],
		];
	}

	public function init() {
		add_action( 'rest_api_init', [ $this, 'init_endpoint' ] );
	}

	public function init_endpoint() {
		register_rest_route(
			$this->get_namespace(),
			$this->get_endpoint(),
			$this->get_args(),
		);
	}

	public function get_namespace() {
		return $this->namespace;
	}

	public function set_namespace( $api_namespace ) {
		$this->namespace = $api_namespace;
	}

	public function get_endpoint() {
		return $this->endpoint;
	}

	public function set_endpoint( $endpoint ) {
		$this->endpoint = $endpoint;
	}

	public function get_args() {
		return $this->args;
	}

	public function set_methods( $methods ) {
		$this->methods = $methods;
	}

	public function get_methods() {
		return $this->methods;
	}

	public function enable_authentication() {
		$this->use_authentication = true;
	}

	public function disable_authentication() {
		$this->use_authentication = false;
	}

	public function is_authentication_enabled() {
		return $this->use_authentication;
	}

	public function plugin_callback( $request ) {
		$this->increment_stats( $request );

		$url_params   = $request->get_url_params();
		$query_params = $request->get_query_params();
		$post_params  = $request->get_body_params();

		$url  = $this->url;
		$url .= stripslashes_deep( $url_params['call'] );
		$url  = add_query_arg( $query_params, $url );

		$args = [
			'method' => $request->get_method(),
		];
		if ( $post_params ) {
			$args['body'] = $post_params;
		}
		$remote_response = wp_remote_request( $url, $args );
		$data            = wp_remote_retrieve_body( $remote_response );

		$response = $this->prepare_response( $data, $request );

		return $response;
	}

	public function increment_stats( $request ) {
		if ( ! \LifeJacket\Server\Plugin::get_instance()->stats ) {
			return;
		}

		$header   = $request->get_header( 'user_agent' );
		$header   = explode( ';', $header )[1] ?? '- n/a -';
		$domain   = trim( $header );
		$url      = $request->get_url_params();
		$endpoint = $this->url . ( $url['call'] ?? '/' );

		\LifeJacket\Server\Plugin::get_instance()->stats->log( $domain, $endpoint );
	}

	public function permission_callback( $request ) {
		if ( $this->is_authentication_enabled() ) {
			return true;
		}

		$app_password = $request->get_header( 'X-WP-Application-Password' );

		if ( empty( $app_password ) ) {
		    // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
			return new \WP_Error( 'rest_forbidden', __( 'Authentication required.' ), array( 'status' => 401 ) );
		}

		$result = \wp_validate_application_password( false );

		if ( \is_wp_error( $result ) ) {
			return new \WP_Error( 'rest_forbidden', $result->get_error_message(), array( 'status' => 403 ) );
		}

		return true;
	}

	abstract protected function prepare_response( $data, $request );
}
