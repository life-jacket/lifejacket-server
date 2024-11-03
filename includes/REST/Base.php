<?php
namespace LifeJacket\Server\REST;

abstract class Base {
    protected $namespace = 'lifejacket/v1';
    protected $endpoint = 'plugin/(?P<slug>[a-zA-Z0-9-]+)';
    protected $args = [];
    protected $methods = 'GET';
    protected $use_authentication = false;

    public function __construct() {
        $this->args = [
            'methods' => $this->get_methods(),
            'callback' => [ $this, 'plugin_callback' ],
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

    public function set_namespace( $namespace ) {
        $this->namespace = $namespace;
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

    abstract public function plugin_callback( $request );

    public function permission_callback( $request ) {
        if ( $this->is_authentication_enabled() ) {
            return true;
        }
        
        $app_password = $request->get_header( 'X-WP-Application-Password' );

        if ( empty( $app_password ) ) {
            return new \WP_Error( 'rest_forbidden', __( 'Authentication required.' ), array( 'status' => 401 ) );
        }

        $result = \wp_validate_application_password( false );

        if ( \is_wp_error( $result ) ) {
            return new \WP_Error( 'rest_forbidden', $result->get_error_message(), array( 'status' => 403 ) );
        }
    
        return true;
    }

}