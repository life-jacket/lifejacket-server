<?php
namespace LifeJacket\Server;

class Options {
    protected $options = [];

    protected $default_options = [
        'require_auth' => true,
    ];
    
    protected $network_options = [];
    protected $blog_options = [];

    public function __construct() {
        $this->network_options = get_site_option( 'lifejacket_server', [] );
        $this->blog_options = get_option( 'lifejacket_server', [] );
    }

    public function get( $option ) {
        $value = '';

        if ( isset( $this->options[ $option ] ) ) {
            $value = $this->options[ $option ];
        }

        if ( ! $value ) {
            $value = $this->blog_options[ $option ] ?? $this->network_options[ $option ] ?? '';
        };

        $constant_name = strtoupper( 'LIFEJACKET_SERVER_' . $option );
        if ( ! $value && defined( $constant_name ) ) {
            $value = constant( $constant_name );
        }

        if ( ! $value && isset( $this->default_options[ $option ] ) ) {
            $value = $this->default_options[ $option ];
        }

        $value = $this->set( $option, $value );

        $value = apply_filters( "lifejacket/server/option/get", $value, $option );
        $value = apply_filters( "lifejacket/server/option/get/{$option}", $value );

        return $value;
    }

    protected function set( $option, $value ) {
        $value = apply_filters( "lifejacket/server/option/set", $value, $option );
        $value = apply_filters( "lifejacket/server/option/set/{$option}", $value );
 
        $this->options[ $option ] = $value;
 
        return $this->options[ $option ];
    }

}