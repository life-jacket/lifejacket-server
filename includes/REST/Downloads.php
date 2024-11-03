<?php
namespace LifeJacket\Server\REST;

class Downloads extends API {
    protected $endpoint = 'downloads/(?P<call>.+)';
    protected $methods = 'GET,POST';

	protected $url = 'https://downloads.wordpress.org/';

	protected function prepare_response( $data, $request ) {
        $response = new \WP_REST_Response( $data );

        // A workaround to not pass $data via json_encode, but allow headers to be controlled the normal way 
        add_filter( 'rest_pre_serve_request', function() use( $data ){
            // this allow delaying the echo after headers are sent
            echo $data;
            return true;
        });

		return $response;
	}

}