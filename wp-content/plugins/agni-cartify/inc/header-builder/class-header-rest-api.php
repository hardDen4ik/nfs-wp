<?php

class AgniHeaderRestApi {
    public function __construct(){

        add_action( 'rest_api_init', array($this, 'register_api' ), 10 );
    }

    public function register_api(){

        $current_user_can = current_user_can( 'edit_posts' );

        register_rest_route( 'agni-header-builder/v1', '/headers', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_header_list' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );
        register_rest_route( 'agni-header-builder/v1', '/headers/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_header' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );

        register_rest_route( 'agni-header-builder/v1', '/headers/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'update_header' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );

        register_rest_route( 'agni-header-builder/v1', '/headers/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => array( $this, 'delete_header' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );

    }

    public function get_header_list( WP_REST_Request $request ){
        $agni_header_builder_header_custom = get_option( 'agni_header_builder_headers_list' );

        return $agni_header_builder_header_custom;
    }
    public function get_header( WP_REST_Request $request ) {
        $id = $request['id'];
        $results = get_option( 'agni_header_builder_headers_list' );
        return $results[$id];
     }

    public function update_header( WP_REST_Request $request ) {

        $url_param = $request->get_url_params();
        $header_id = $url_param['id'];
        $header = $request->get_param( $url_param['id'] );

        // if( $header[] )
        if( !isset( $header['sticky'] ) || $header['sticky'] == false ){
            foreach ($header['content'] as $deviceKey => $deviceValue) {
                foreach ($deviceValue as $key => $row) {
                    if($row['rowName'] == 'sticky'){
                        unset($header['content'][$deviceKey][$key]);
                    }
                }
            }
        }
        // print_r( $header );

        // if( $header['default'] == true )

        $results = (array)get_option( 'agni_header_builder_headers_list' );

        $header_key = array_search($header_id, array_column($results, 'id'));


        if( false !== $header_key ){

            $results[$header_key] = $header;
        }
        else{
            $results[] = $header;
        }

        if( $header['default'] == true ){
            foreach ($results as $key => $existingHeader) {
                if( $header_key !== $key ){
                    unset($results[$key]['default']);
                    $results[$key]['default'] = false;
                }
            }
        }

        // print_r($results);
        $results = update_option( 'agni_header_builder_headers_list', $results );
        // return $results;
        return 'Success!';
    }

    public function delete_header( WP_REST_Request $request ){
        $url_param = $request->get_url_params();
        $header_id = $url_param['id'];

        $results = (array)get_option( 'agni_header_builder_headers_list' );

        $header_key = array_search($header_id, array_column($results, 'id'));

        if( false !== $header_key ){
            
            array_splice($results, $header_key, 1);
        
            // print_r($results);
            $results = update_option( 'agni_header_builder_headers_list', $results );

            return wp_send_json_success( 'Deleted!' );
        }
        else{
            return wp_send_json_error( 'Header Not found!' );
        }

    }
}

$AgniHeaderRestApi = new AgniHeaderRestApi();


?>