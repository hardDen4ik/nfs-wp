<?php 

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Agni_Product_Activation {

    public function __construct(){

        add_action( 'agni_product_activation', array( $this, 'get_remote_buyer_info' ), 10, 1 );
        add_action( 'wp_ajax_agni_product_activation', array( $this, 'get_remote_buyer_info_ajax' ), 10, 1 );
        // add_action( 'wp_ajax_nopriv_agni_product_activation', array( $this, 'get_remote_buyer_info_ajax' ), 10, 1 );

    }

    public function get_remote_buyer_info_ajax(){

        // First check the nonce, if it fails the function will break
        if (!check_ajax_referer('agni_product_registration_nonce', 'security')) {
            return 'Invalid Nonce';
        }

        $args = array(
            // 'item_code' => $_POST['item_code'],
            'domain' => $_POST['domain'],
            'purchase_code' => $_POST['purchase_code'],
            // 'envato_token' => $_POST['envato_token'],
            'email' => $_POST['email'],
            'staging' => $_POST['staging']
        );

        if( isset( $_POST['register'] ) ){
            $args['register'] = $_POST['register'];
        }
        if( isset( $_POST['deregister'] ) ){
            $args['deregister'] = $_POST['deregister'];
        }

        $response = self::get_remote_buyer_info( $args );

        // print_r( $response );

        if( !isset( $response->error ) && current_user_can( 'edit_theme_options' ) ){

            if( isset( $_POST['deregister'] ) && $_POST['deregister'] == '1' ){
                // set_theme_mod( 'agni_product_registration_envato_token', '' );
                // remove_theme_mod( 'agni_product_registration_envato_token' );
                delete_option( 'envato_purchase_code_' . $response->success->item_id );
                delete_option( 'agni_product_registration_email' );
                delete_option( 'agni_product_registration_staging' );
            }
            else{            
                // set_theme_mod( 'agni_product_registration_envato_token', $_POST['envato_token'] );
                update_option( 'envato_purchase_code_' . $response->success->item_id, $_POST['purchase_code'] );
                update_option( 'agni_product_registration_email', $_POST['email'] );
                update_option( 'agni_product_registration_staging', $_POST['staging'] );
            }

        }

        wp_send_json( $response );

        die();

    }

    public static function get_remote_buyer_info( $body_args ){

        $url = 'https://api.agnihd.com/agni-purchase-verifier/agni-purchase-verifier.php';

                $args = array(
            'body'        => json_encode( $body_args ),
            'headers'     => array(
                'Content-Type' => 'application/json' 
            ),
            'timeout'     => 60,
            'redirection' => 5,
            'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => true, // make it true for live
        );

        // Make an API request.
        $response = wp_remote_post( esc_url_raw( $url ), $args );

        // print_r( $response );

        // Check the response code.
        $response_code    = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );


        $debugging_information['response_code']   = $response_code;
        $debugging_information['response_cf_ray'] = wp_remote_retrieve_header( $response, 'cf-ray' );
        $debugging_information['response_server'] = wp_remote_retrieve_header( $response, 'server' );

                if ( is_array( $response ) && ! is_wp_error( $response ) ) {

            $headers = $response['headers']; // array of http header lines
            $body    = $response['body']; // use the content
            // print_r( $body );
            // print_r( json_decode( $body ) );
            return json_decode( $body );
        }

        return $response;

    }


}

$agni_product_activation = new Agni_Product_Activation();