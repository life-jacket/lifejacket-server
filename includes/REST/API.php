<?php
namespace LifeJacket\Server\REST;

class API extends Base {
    protected $endpoint = 'api/(?P<call>.+)';
    protected $methods = 'GET,POST';

	protected $url = 'https://api.wordpress.org/';

	protected function prepare_response( $data, $request ) {
        $data = json_decode( $data, true );
        $response = new \WP_REST_Response( $data );
		return $response;
	}

}