<?php
namespace LifeJacket\Server\REST;

class API extends Base {
    protected $endpoint = 'api/(?P<call>.+)';
    protected $methods = 'GET,POST';

    public function plugin_callback( $request ) {
        trigger_error( json_encode( $request->get_params() ) );

        $url_params = $request->get_url_params();
        $query_params = $request->get_query_params();
        $post_params = $request->get_body_params();

        $url = 'https://api.wordpress.org/';
        $url .= stripslashes_deep( $url_params['call'] );
        $url = add_query_arg( $query_params, $url );

        $args = [
            'method' => $request->get_method(),
        ];
        if ( $post_params ) {
            $args['body'] = $post_params;
        }
        $remote_response = wp_remote_request( $url, $args );
        $data = wp_remote_retrieve_body( $remote_response );
        $data = json_decode( $data, true );

        $response = new \WP_REST_Response( $data );
        return $response;
    }
}