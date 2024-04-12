<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Agni_Slider_REST_API{

    public function __construct(){

        add_action( 'rest_api_init', array($this, 'register_api' ), 10 );
    }

    public function register_api(){

        $current_user_can = current_user_can( 'edit_posts' );

        register_rest_route( 'agni-slider-builder/v1', '/sliders', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_slider_list'),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );
        // register_rest_route( 'agni-slider-builder/v1', '/sliders/(?P<id>\d+)', array(
        //     'methods' => WP_REST_Server::READABLE,
        //     'callback' => array($this, 'get_slider'),
        // ) );

        register_rest_route( 'agni-slider-builder/v1', '/sliders/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'update_slider' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );

        register_rest_route( 'agni-slider-builder/v1', '/sliders/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => array( $this, 'delete_slider' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );
    }

    public function get_slider_list( WP_REST_Request $request ){
        $agni_slider_list = get_option( 'agni_slider_builder_sliders' );

        return $agni_slider_list;
    }

    public function update_slider( WP_REST_Request $request ) {
        $results = array();
        
        $url_param = $request->get_url_params();
        $slider_id = $url_param['id'];
        $slider = $request->get_param( $url_param['id'] );

        $results = get_option( 'agni_slider_builder_sliders' );

        if( !empty( $results ) ){
            
            $slider_key = array_search($slider_id, array_column($results, 'id'));

            if( false !== $slider_key ){
                $results[$slider_key] = $slider;
            }
            else{
                $results[] = $slider;
            }
        }
        else{
            $results[] = $slider;
        }

        // print_r($results);
        $results = update_option( 'agni_slider_builder_sliders', $results );
        // return $results;
        $agni_slider_list = get_option( 'agni_slider_builder_sliders' );

        return wp_send_json_success( $agni_slider_list );
    }

    public function delete_slider( WP_REST_Request $request ){
        $url_param = $request->get_url_params();
        $slider_id = $url_param['id'];

        $results = (array)get_option( 'agni_slider_builder_sliders' );

        $slider_key = array_search($slider_id, array_column($results, 'id'));

        if( false !== $slider_key ){
            array_splice($results, $slider_key, 1);
        
            $results = update_option( 'agni_slider_builder_sliders', $results );

            $agni_slider_list = get_option( 'agni_slider_builder_sliders' );
        }
        else{
            $agni_slider_list = get_option( 'agni_slider_builder_sliders' );
        }
        // print_r($agni_slider_list);

        return wp_send_json_success( $agni_slider_list );
    }

}

$agni_slider_rest_api = new Agni_Slider_REST_API();