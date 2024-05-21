<?php

function agni_child_enqueue_scripts() {
    // Adding parent styles
    wp_enqueue_style( 'agni-parent-style', get_template_directory_uri() . '/style.css', array() );

    // Adding rtl support
    if( is_rtl() ){
	    wp_enqueue_style( 'agni-parent-rtl-style', get_template_directory_uri() . '/rtl.css' );
    }

    // Adding child stylesheet
	//wp_enqueue_style( 'agni-child-style', get_stylesheet_directory_uri() . '/style.css'  );
}
add_action( 'wp_enqueue_scripts', 'agni_child_enqueue_scripts', 9 );

function http_additional_safe_urls( $http_request_args, $url ) {
    // Do not interfere unless the URL matches a pattern we trust.
    // You could expand this to match against multiple strings, or
    // even use a regular expression, depending on your needs.
    if ( $url !== 'https://api.nfsmir.ru/api/catalog/wp-order' && $url !== 'https://api.nfsmir.ru/api/catalog/wp-update') {
        return $http_request_args;
    }

    // Disable the `reject_unsafe_urls` arg.
    $http_request_args['reject_unsafe_urls'] = false;
    return $http_request_args;
};

add_filter( 'http_request_args', 'http_additional_safe_urls', 10, 2 );


add_action('rest_api_init', 'order_by_id');
function order_by_id()
{
    register_rest_route(
        'wc/v3',
        '/get-order',
        array(
            'methods' => 'GET',
            'callback' => 'get_order_by_id',
            'permission_callback' => '__return_true',
        )
    );
    error_reporting(0);
}

function get_order_by_id()
{
    $id = $_GET['id'];
    $resp = [];
    if($id) {
        $order = wc_get_order($id);
        $resp['created_at'] = $order->order_date;
        $resp['amount'] = $order->get_total();
        $resp['wp_id'] = $order->get_id();
        $resp['status'] = $order->get_status();
        foreach ($order->get_items() as $item) {
            $meta_data = json_decode($item->get_meta('_nfsmir_json_data'));
            $resp['products'][$item->get_product_id()] = [];
            foreach ($meta_data as $k => $value) {
                $resp['products'][$item->get_product_id()][] = [
                    'certificate_id' => $k,
                    'status' => $value->data->status,
                    'amount' => wc_get_product($item->get_product_id())->get_price()
                ];
            }
        }

        return $resp;
    } else
        return $resp;
}

add_action('rest_api_init', 'update_order_by_id');
function update_order_by_id()
{
    register_rest_route(
        'wc/v3',
        '/update-order',
        array(
            'methods' => 'GET',
            'callback' => 'get_updated_order_by_id',
            'permission_callback' => '__return_true',
        )
    );
    error_reporting(0);
}

function get_updated_order_by_id()
{
    $id = $_GET['id'];
    $resp = [];
    if($id) {
        $order = wc_get_order($id);
        $resp['created_at'] = $order->order_date;
        $resp['amount'] = $order->get_total();
        $resp['wp_id'] = $order->get_id();
        $resp['status'] = $order->get_status();
        foreach ($order->get_items() as $item) {
            $meta_data = json_decode($item->get_meta('_nfsmir_json_data'));
            $resp['products'][$item->get_product_id()] = [];
            foreach ($meta_data as $k => $value) {
                $resp['products'][$item->get_product_id()][] = [
                    'certificate_id' => $k,
                    'status' => $value->data->status,
                    'card_number' => $value->data->json->cc_number_masked,
                    'hash' => $value->data->json->cc_hash->hash,
                    'payment_system' => $value->data->json->cc_hash->paymentSystem,
                ];
            }
        }

        return $resp;
    } else
        return $resp;
}
?>
