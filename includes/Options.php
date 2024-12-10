<?php
namespace LifeJacket\Server;

/**
 * Plugin options
 */
class Options {
	protected $options = [];
	protected $sources = [];

	protected $default_options = [
		'require_auth'  => true,
		'collect_stats' => false,
	];

	protected $network_options = [];
	protected $blog_options    = [];

	public function __construct() {
		$this->network_options = get_site_option( 'lifejacket_server', [] );
		$this->blog_options    = get_option( 'lifejacket_server', [] );
	}

	public function get_defaults() {
		return $this->default_options;
	}

	public function get( $option ) {
		$value = $this->get_with_source( $option )['value'];
		return $value;
	}

	public function get_with_source( $option ) {
		$value  = null;
		$source = null;

		if ( isset( $this->options[ $option ] ) ) {
			$value  = $this->options[ $option ] ?? null;
			$source = $this->sources[ $option ] ?? null;
		}

		$constant_name = strtoupper( 'LIFEJACKET_SERVER_' . $option );
		if ( null === $value && defined( $constant_name ) ) {
			$value  = constant( $constant_name );
			$source = 'constant';
		}

		if ( null === $value && is_multisite() && isset( $this->network_options[ $option ] ) ) {
			$value  = $this->network_options[ $option ];
			$source = 'network';
		}

		if ( null === $value && isset( $this->blog_options[ $option ] ) ) {
			$value  = $this->blog_options[ $option ];
			$source = 'blog';
		}

		if ( null === $value && isset( $this->default_options[ $option ] ) ) {
			$value  = $this->default_options[ $option ];
			$source = 'default';
		}

		$value = $this->set( $option, $value, $source );

		$value = apply_filters( 'lifejacket/server/option/get', $value, $option );
		$value = apply_filters( "lifejacket/server/option/get/{$option}", $value );

		$source = apply_filters( 'lifejacket/server/option_source/get', $source, $option );
		$source = apply_filters( "lifejacket/server/option_source/get/{$option}", $source );

		return [
			'value'  => $value,
			'source' => $source,
		];
	}

	protected function set( $option, $value, $source ) {
		$value = apply_filters( 'lifejacket/server/option/set', $value, $option );
		$value = apply_filters( "lifejacket/server/option/set/{$option}", $value );

		$this->options[ $option ] = $value;
		$this->sources[ $option ] = $source;

		return $this->options[ $option ];
	}
}
