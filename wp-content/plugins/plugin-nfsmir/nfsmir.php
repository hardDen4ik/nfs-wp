<?php
/*
Plugin Name:  Nfsmir
Plugin URI:   https://www.nfsmir.ru
Description:  A short little description of the plugin. It will be displayed on the Plugins page in WordPress admin area.
Version:      1.0
Author:       Softorium
Author URI:   https://www.softorium.pro
Text Domain:  nfsmir
Domain Path:  /languages
*/

class Nfsmir {
    private $nfsmir_options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'nfsmir_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'nfsmir_page_init' ) );
    }

    public function nfsmir_add_plugin_page() {
        add_options_page(
            'nfsmir', // page_title
            'nfsmir', // menu_title
            'manage_options', // capability
            'nfsmir', // menu_slug
            array( $this, 'nfsmir_create_admin_page' ) // function
        );
    }

    public function nfsmir_create_admin_page() {
        $this->nfsmir_options = get_option( 'nfsmir_option_name' ); ?>

        <div class="wrap">
            <h2>nfsmir</h2>
            <p></p>
            <?php settings_errors(); ?>

            <?php if (isset($this->nfsmir_options['link_processing_url'])) {

                ;
                $url = plugins_url() . '/'. basename(dirname(__FILE__)) . '/qr.php?u=' . $this->nfsmir_options['link_processing_url'];
                echo '<img src="'.$url.'" />';
            } ?>

            <form method="post" action="options.php">
                <?php
                settings_fields( 'nfsmir_option_group' );
                do_settings_sections( 'nfsmir-admin' );
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function nfsmir_page_init() {
        register_setting(
            'nfsmir_option_group', // option_group
            'nfsmir_option_name', // option_name
            array( $this, 'nfsmir_sanitize' ) // sanitize_callback
        );

        add_settings_section(
            'nfsmir_setting_section', // id
            'Settings', // title
            array( $this, 'nfsmir_section_info' ), // callback
            'nfsmir-admin' // page
        );

        add_settings_field(
            'link_processing_url', // id
            'URL обработки ссылок', // title
            array( $this, 'link_processing_url_callback' ), // callback
            'nfsmir-admin', // page
            'nfsmir_setting_section' // section
        );

        add_settings_field(
            'token', // id
            'Токен авторизации запросов URL обработки ссылок', // title
            array( $this, 'token_callback' ), // callback
            'nfsmir-admin', // page
            'nfsmir_setting_section' // section
        );
    }

    public function nfsmir_sanitize($input) {
        $old_options = get_option('nfsmir_option_name');
        $has_errors = false;

        $sanitary_values = array();
        if ( isset( $input['link_processing_url'] ) ) {
            $sanitary_values['link_processing_url'] = sanitize_text_field( $input['link_processing_url'] );

            if (!wp_http_validate_url($sanitary_values['link_processing_url'])) {
                add_settings_error('nfsmir_option_names', 'nfsmir_option_name', __('URL is not valid', 'prefix'), 'error');
                $has_errors = true;
            }
        }

        if ( isset( $input['token'] ) ) {
            $sanitary_values['token'] = sanitize_text_field( $input['token'] );
        }

        if ($has_errors) {
            $sanitary_values = $old_options;
        }

        return $sanitary_values;
    }

    public function nfsmir_section_info() {

    }

    public function link_processing_url_callback() {
        printf(
            '<input class="regular-text" type="text" name="nfsmir_option_name[link_processing_url]" id="link_processing_url" value="%s">',
            isset( $this->nfsmir_options['link_processing_url'] ) ? esc_attr( $this->nfsmir_options['link_processing_url']) : ''
        );
    }

    public function token_callback() {
        printf(
            '<input class="regular-text" type="text" name="nfsmir_option_name[token]" id="token" value="%s">',
            isset( $this->nfsmir_options['token'] ) ? esc_attr( $this->nfsmir_options['token']) : ''
        );
    }
}



if ( is_admin() ) {
    $nfsmir = new Nfsmir();
}

function plugin_activate() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'nfsmir';

    $sql = "CREATE TABLE $table_name (
            id BIGINT(20) NOT NULL AUTO_INCREMENT,
            guid VARCHAR(36) NOT NULL,
            order_item_id BIGINT(20) NOT NULL,
            quantity_item_id BIGINT(20) NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

register_activation_hook( __FILE__, 'plugin_activate' );

function create_unique_order_item($order_item_id, $quantity_item_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nfsmir';

    $guid = GUID();

    $wpdb->insert(
        $table_name,
        array(
            'guid' => $guid,
            'order_item_id' => $order_item_id,
            'quantity_item_id' => $quantity_item_id,
        )
    );

    return $guid;
}

function get_order_item_id($guid) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nfsmir';
    return $wpdb->get_var( "SELECT order_item_id FROM $table_name WHERE `guid` = '$guid'" );
}

/*
 * Retrieve this value with:
 * $nfsmir_options = get_option( 'nfsmir_option_name' ); // Array of All Options
 * $link_processing_url = $nfsmir_options['link_processing_url']; // URL обработки ссылок
 * $token = $nfsmir_options['token']; // Токен авторизации запросов URL обработки ссылок
 */


//add_action('woocommerce_checkout_order_processed', function ($order_id) {
//    $order = wc_get_order( $order_id );
//    foreach( $order->get_items() as $item_id => $item ){
//        wc_add_order_item_meta($item_id, '_nfsmir_json_data', '{}' , true );
//    }
//}, 10, 1);

add_action( 'woocommerce_admin_order_item_headers', 'nfsmir_admin_order_item_headers' );

function nfsmir_admin_order_item_headers( $order ) {
    echo '<th class="status">Статус</th>';
}

add_action( 'woocommerce_admin_order_item_values', 'nfsmir_admin_order_item_values', 9999, 3 );

function nfsmir_admin_order_item_values( $product, $item, $item_id ) {
    $statuses = [
        "ожидает номер карты",
        "ожидает оформления",
        "оформлена",
        "отклонена"
    ];

    if ( $product ) {
        $json_data = $item->get_meta( '_nfsmir_json_data' ) ? $item->get_meta( '_nfsmir_json_data' ) : [];

        echo '<td class="json_data"><div class="view">';

        if ($json_data) {
            $data = json_decode($json_data, true);

            foreach ($data as $guid => $item) {
                if (array_key_exists('status', $item['data'])) {
                    $status = $item['data']['status'];

                    echo '<b>' . $guid . ': </b>';
                    echo $statuses[$status];
                    echo '<br />';
                }
            }
        }

        echo '</div><div class="edit" style="display: none;">';

        if ($json_data) {
            $data = json_decode($json_data, true);

            foreach ($data as $guid => $item) {
                if (array_key_exists('status', $item['data'])) {
                    $status = $item['data']['status'];

                    echo '<b>' . $guid . ': </b>';
                    echo '<select name="_nfsmir_status[' . $item_id . '][' . $guid . ']" class="" />';
                    foreach($statuses as $val => $text) {

                        if ($status == $val) {
                            echo '<option selected value="'.$val.'">' . $text . '</option>';
                        } else {
                            echo '<option value="'.$val.'">' . $text . '</option>';
                        }
                    }
                    echo '</select>';
                    echo '<br />';
                }
            }
        }

        echo '</div></td>';

    }
}

add_action( 'woocommerce_before_save_order_item', 'nfsmir_change_fields', 9999 );

function nfsmir_change_fields( $item ) {
    if ( $item->get_type() !== 'line_item' ) return;
    if ( ! $_POST ) return;
    if ( isset( $_POST['items'] ) ) {
        // ITS AJAX SAVE
        parse_str( rawurldecode( $_POST['items'] ), $output );
    } else {
        $output = $_POST;
    }

    $item_ids = $output['_nfsmir_status'][$item->get_id()];
    $json_data = $item->get_meta( '_nfsmir_json_data' ) ? $item->get_meta( '_nfsmir_json_data' ) : [];

    if ($json_data) {
        $data = json_decode($json_data, true);

        foreach ($item_ids as $guid => $status) {
            if (array_key_exists('status', $data[$guid]['data'])) {
                $data[$guid]['data']['status'] = $status;
            }
        }

        $item->update_meta_data( '_nfsmir_json_data', json_encode($data));
    }
}

add_action('woocommerce_checkout_update_order_meta', 'add_order_items_ids', 10, 1);
function add_order_items_ids( $order_id )
{
    if (!$order_id)
        return;

    $order = wc_get_order($order_id);

    if (!$order)
        return;

    foreach ( $order->get_items() as $item_id => $item ) {

        $quantity = $item->get_quantity();

        $json_data = [];

        for ($i = 0; $i < $quantity; $i++) {
            $guid = create_unique_order_item($item_id, $i);

            $json_data[$guid] = ["data" => ["status" => 0, "json" => null]];
        }

        wc_update_order_item_meta( $item_id, '_nfsmir_json_data', json_encode($json_data) );
    }
}

function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function nfsmir_update_data_callback( WP_REST_Request $request ) {
    $nfsmir_options = get_option( 'nfsmir_option_name' );

    if (isset($nfsmir_options['token'])) {
        if ($nfsmir_options['token'] != $request->get_header('Auth')) {
            return new WP_REST_Response(null, 404);
        }
    }

    $guid = $request->get_param('id');

    if (!$guid) {
        return new WP_REST_Response(null, 404);
    }

    $id = get_order_item_id($guid);

    if (!$id) {
        return new WP_REST_Response(null, 404);
    }

    $parameters = $request->get_json_params();

    $json_data = wc_get_order_item_meta($id, '_nfsmir_json_data');

    $data = [];

    if ($json_data) {
        $data =  json_decode($json_data, true);
        $data[$guid]['data']['json'] = $parameters;

        if (array_key_exists('status', $parameters)) {
            $data[$guid]['data']['status'] = $parameters['status'];
            unset($data[$guid]['data']['json']['status']);
        }
    }

    wc_update_order_item_meta($id, '_nfsmir_json_data', json_encode($data));
}

function nfsmir_get_data_callback( WP_REST_Request $request ) {
    $nfsmir_options = get_option( 'nfsmir_option_name' );

    if (isset($nfsmir_options['token'])) {
        if ($nfsmir_options['token'] != $request->get_header('Auth')) {
            return new WP_REST_Response(null, 404);
        }
    }

    $guid = $request->get_param('id');

    if (!$guid) {
        return new WP_REST_Response(null, 404);
    }

    $id = get_order_item_id($guid);

    if (!$id) {
        return new WP_REST_Response(null, 404);
    }

    $json_data = wc_get_order_item_meta($id, '_nfsmir_json_data');

    if (!$json_data) {
        return new WP_REST_Response(null, 404);
    }

    $item = WC_Order_Factory::get_order_item($id);
    $product = wc_get_product($item->get_product_id());
    $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($item->get_product_id()));

    if ($image_data && is_array($image_data)) {
        $image = $image_data[0];
    }

    $data =  json_decode($json_data, true);

    $json = $data[$guid];

    if (!$json) {
        return new WP_REST_Response(null, 404);
    }

    if ($json['data']['json'] != null && array_key_exists('status', $json['data']['json'])) {
        unset($json['data']['json']['status']);
    }

    return [
            'json_data' => $json['data']['json'],
            'status' => $json['data']['status'],
            'description' => $product->get_description(),
            'short_description' => $product->get_short_description(),
            'image' => $image,
            'name' => $item->get_name(),
            'total' => $item->get_total() / $item->get_quantity(),
            'catalog_item_url' => $product->get_permalink()
        ];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'nfsmir/v1', '/update-data/(?P<id>[a-zA-Z0-9-]+)', array(
        'methods' => 'POST',
        'callback' => 'nfsmir_update_data_callback',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return true;
                }
            ),
        ),
    ) );

    register_rest_route( 'nfsmir/v1', '/get-data/(?P<id>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'nfsmir_get_data_callback',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return true;
                }
            ),
        ),
    ) );
} );

add_action( 'generate_qr_and_url', 'generate_qr_and_url' );

function generate_qr_and_url($item_id) {
    $nfsmir_options = get_option( 'nfsmir_option_name' );

    if (isset($nfsmir_options['link_processing_url'])) {

        $json_data = wc_get_order_item_meta($item_id, '_nfsmir_json_data');

        if ($json_data) {
            $data =  json_decode($json_data, true);
            foreach ($data as $guid => $item) {
                $url = str_replace('{id}', $guid, $nfsmir_options['link_processing_url']);
                $src = plugins_url() . '/'. basename(dirname(__FILE__)) . '/qr.php?u=' . $url;
                echo '<div><img src="' . $src . '" /></div>';
                echo '<div><a href="' . $url . '">' . $url . '</a></div>';
            }
        }
    }
}