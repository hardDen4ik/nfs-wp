<?php

/**
 * Plugin Name: Agni Importer Exporter
 * Plugin URI: http://agnidesigns.com
 * Description: This is core plugin of Cartify eCommerce WordPress theme.
 * Version: 1.0.3
 * Author: AgniHD
 * Author URI: http://agnidesigns.com
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: agni-importer-exporter
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class AgniImporterExporter {

    public function __construct() {

        $this->define_constants();

        add_action( 'init', array( $this, 'textdomain') );

        $this->includes();

        add_action( 'agni_insert_importer_exporter', array( $this, 'importer_exporter_mainpage') );

        add_action( 'rest_api_init', array($this, 'register_api' ), 10 );

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );


    }

    public function define_constants(){
        if( ! function_exists('get_plugin_data') ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $plugin_data = get_plugin_data( __FILE__ );

        // Assign constants.
        define( 'AGNI_IMPORTER_EXPORTER_PLUGIN_TEXTDOMAIN', $plugin_data['TextDomain'] );
        define( 'AGNI_IMPORTER_EXPORTER_PLUGIN_VERSION', '1.0.0' );
        define( 'AGNI_IMPORTER_EXPORTER_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // pointing exact plugin folder url.
        define( 'AGNI_IMPORTER_EXPORTER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // pointing exact plugin folder directory.
        define( 'AGNI_IMPORTER_EXPORTER_PLUGIN_FILE_PATH', plugin_basename( __FILE__ ) ); // pointing plugin file.

    }

    public function textdomain(){
        load_plugin_textdomain( AGNI_IMPORTER_EXPORTER_PLUGIN_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    public function includes(){
        require_once AGNI_IMPORTER_EXPORTER_PLUGIN_PATH . 'class-importer-data.php';
        require_once AGNI_IMPORTER_EXPORTER_PLUGIN_PATH . 'class-parser.php';
        require_once AGNI_IMPORTER_EXPORTER_PLUGIN_PATH . 'import-content-processor.php';
        // require_once AGNI_IMPORTER_EXPORTER_PLUGIN_PATH . 'plugins-installer.php';
    }

    public function register_api(){

        $current_user_can = current_user_can( 'edit_theme_options' );


        register_rest_route( 'agni-import-export/v1', '/get_import_data', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_import_data' ), 
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );

        register_rest_route( 'agni-import-export/v1', '/import_content', array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array( $this, 'import_content' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );


        register_rest_route( 'agni-import-export/v1', '/get_mapped_content', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_mapped_content' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );


        register_rest_route( 'agni-import-export/v1', '/get_total_media', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'get_total_media' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );


    }

    public function get_import_data(){

        global $wp_filesystem;
 
        require_once ( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();

        $demo_json = $this->get_export_url(); // we should replace with remote url request to get json data from server.
        $demo_content_array = '';
        $is_older_version = false;

        if ( $wp_filesystem->exists( $demo_json ) ) {
            $demo_content_array = json_decode( $wp_filesystem->get_contents( $demo_json ), true );
            $is_older_version = $this->export_latest_version_check( $demo_content_array[0]['version'] );
        }

        if( $is_older_version || !$wp_filesystem->exists( $demo_json ) ){

            $token = AgniImporterData::getToken();
            
            $download_import_data = AgniImporterData::downloadImportData( $token );
            
            if( isset($download_import_data['success']) ){
                $demo_content_array = json_decode( $wp_filesystem->get_contents( $demo_json ), true );
            }
            else{
                wp_send_json_error( $download_import_data['error'] );
            }

        }

        wp_send_json_success( $demo_content_array );

        die();

    }


    public function export_latest_version_check( $existing_version ){
        $latest_version = '';

        $export_filename = 'export';
        $version_request_url = 'https://api.agnihd.com/agni-purchase-verifier/agni-purchase-verifier.php?get_demo_version=' . $export_filename;

        $args = array(
            'headers'     => array(),
            'timeout'     => 60,
            'redirection' => 5,
            'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => true, // make it true for live
        );

        // Make an API request.
        $response = wp_remote_get( esc_url_raw( $version_request_url ), $args );

        // Check the response code.
        $response_code    = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        $debugging_information['response_code']   = $response_code;
        $debugging_information['response_cf_ray'] = wp_remote_retrieve_header( $response, 'cf-ray' );
        $debugging_information['response_server'] = wp_remote_retrieve_header( $response, 'server' );
        
        if ( !is_wp_error( $response ) ) {
            $latest_version = $response['body'];
        }

        if( version_compare($latest_version, $existing_version, '>') ){
            return true;
        }

        return false;
    }

    public function import_content( WP_REST_Request $request ){

        $result = '';

        $params = $request->get_params();

        // print_r( $params );

        $individualChoices = array();

        $contentName = $params['content'];
        $options = $params['options'];

        if( isset( $params['individual'] ) ){
            // $individual = $params['individual'];
            $contentName = $params['individual']['content'];
            $individualChoices = $params['individual']['values'];
        }

        // print_r( $individual );

        $get_demo_content = $this->get_demo_content();

        $get_content = array();
        $get_images = array();
        foreach ($get_demo_content as $key => $content) {
            if($content['name'] == $contentName){
                if( !empty( $individualChoices ) ){
                    foreach( $content['content'] as $key => $post){ 
                        if( in_array( $post['id'], $individualChoices ) ){
                            $get_content[] = $post;

                            $get_images = array_merge( $get_images, $this->get_images($contentName, $post) );
                        };
                    }
                    
                }
                else{
                    $get_content = $content['content'];

                    if( isset( $params['individual'] ) ){
                        foreach( $content['content'] as $key => $post){ 

                            $get_images = array_merge( $get_images, $this->get_images($contentName, $post) );
                            
                        }
                    }
                }
            }
        }

        $get_images = array_unique( $get_images );


        if( !isset( $params['individual'] ) || (!empty( $get_images ) && $options['media']) ){
            $get_images_content = array();
            // foreach( $get_demo_content['media']['content'] as $key => $media){ 
            //     if( in_array( $media['id'], $get_images ) ){
            //         $get_images_content[] = $media;
            //     }
            // }

            foreach ($get_demo_content as $key => $content) {
                if( $content['name'] == 'media' ){
                    foreach( $content['content'] as $key => $media){ 
                        if( in_array( $media['id'], $get_images ) ){
                            $get_images_content[] = $media;
                        }
                    }
                }
            }

            // print_r( $get_images_content );
            $result = apply_filters( 'agni_importer_exporter_parser', $get_images_content, 'media', $options );


            // if( $result['success'] && $contentName == 'media' ){
            //     add_action('init', array( $this, 'remove_extra_image_sizes' ), 10);

            //     $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

            //     if( isset( $new_demo_content_options['media'] ) ){
            //         $new_media_ids = $new_demo_content_options['media'];
                    
            //         foreach ($new_media_ids as $key => $attach_id) {

            //             $file = get_attached_file( $attach_id );
                        
            //             require_once( ABSPATH . 'wp-admin/includes/image.php' );

            //             $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

            //             wp_update_attachment_metadata( $attach_id,  $attach_data );

            //         }
            //     }
                
            //     remove_action('init', array( $this, 'remove_extra_image_sizes' ), 10);
            // }

        }


        // $ignore_images = false;
        // if( empty( $get_images ) || $get_images == null){
        //     $ignore_images = true;
        // }

        // wp_send_json_error( $result );
        // die();

        if( (isset( $params['individual'] ) && $contentName == 'pages' ) ){
            $result = apply_filters( 'agni_importer_exporter_parser', $get_content, $contentName, $options );
        }
        else if( $result['success']){
            $result = apply_filters( 'agni_importer_exporter_parser', $get_content, $contentName, $options );
        }

        // return wp_send_json_success( $result );
        // return wp_send_json_success( $contentName . ' donne' );

        // if( !empty( $result ) ){
        return wp_send_json( $result );
        // }
        
        // return wp_send_json_success( $contentName . ' donne' );

    }

    public function get_images( $contentName, $post ){
        $get_images = array();

        if( $contentName == 'products' ){
            if( !empty( $post['images'] ) ){
                foreach ($post['images'] as $key => $image) {
                    $get_images[] = $image['id'];
                }
            }
            if( !empty( $post['variations_products'] ) ){
                foreach ($post['variations_products'] as $key => $variable_product) {
                    $get_images[] = $variable_product['image']['id'];

                    foreach ($variable_product['meta_data'] as $key => $meta) {
                        foreach($meta['value'] as $value){
                            $get_images[] = $value;
                        }
                    }
                }
            }
        }
        else{
            if( $post['featured_media'] !== 0 ){
                $get_images[] = $post['featured_media'];
            }
        }

        return $get_images;
    }


    public function get_mapped_content(){

        $mapped_content = get_option( 'agni_importer_exporter_demo_content_mapping' );

        return wp_send_json_success( $mapped_content );
    }

    public function get_total_media( WP_REST_Request $request ){

        $params = $request->get_params();

        $individualChoices = array();
        $contentName = $params['content'];

        if( !empty( $params['values'] ) ){
            $individualChoices = explode( ",", $params['values'] );
        }

        $get_demo_content = $this->get_demo_content();

        $get_images = array();
        foreach ($get_demo_content as $key => $content) {
            if($content['name'] == $contentName){
                if( !empty( $individualChoices ) ){
                    foreach( $content['content'] as $key => $post){ 
                        if( in_array( $post['id'], $individualChoices ) ){
                            $get_images = array_merge( $get_images, $this->get_images($contentName, $post) );
                        };
                    }
                    
                }
                else{
                    foreach( $content['content'] as $key => $post){ 
                        $get_images = array_merge( $get_images, $this->get_images($contentName, $post) );
                        
                    }
                }
            }
        }

        $get_images = array_unique( $get_images );

        if( !empty( $get_images ) ){
            $get_images_content = array();

            foreach ($get_demo_content as $key => $content) {
                if( $content['name'] == 'media' ){
                    foreach( $content['content'] as $key => $media){ 
                        if( in_array( $media['id'], $get_images ) ){
                            $get_images_content[] = $media['id'];
                        }
                    }
                }
            }

            wp_send_json_success( $get_images_content );

        }

        wp_send_json_success( $get_images );
    }

    public static function get_import_file_info(){

        $agni_import_folder = 'agni-import';
        $agni_import_file = 'export.json';

        return array(
            'dirname' => $agni_import_folder,
            'filename' => $agni_import_file
        );

    }


    public function get_export_url( $path = '' ){

        $agni_import_info = AgniImporterExporter::get_import_file_info();

        $upload_dir = wp_upload_dir();
        $upload_dir_path = $upload_dir['basedir'];
        
        if( $path == 'url' ){
            $upload_dir_path = $upload_dir['baseurl'];
        }

        $agni_import_dir = $upload_dir_path . '/' . $agni_import_info['dirname'] . '/' . $agni_import_info['filename'];

        return $agni_import_dir;
    }

    public function get_demo_content(){

        global $wp_filesystem;
 
        require_once ( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();

        $demo_json = $this->get_export_url(); // we should replace with remote url request to get json data from server.
        $demo_content_array = '';
        
        $demo_content_array = json_decode( $wp_filesystem->get_contents( $demo_json ), true );
       
        return $demo_content_array;
    }

    public function remove_extra_image_sizes() {
        foreach ( get_intermediate_image_sizes() as $size ) {
            if ( !in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
                remove_image_size( $size );
            }
        }
    }


    public function importer_exporter_mainpage(){


        wp_enqueue_style( 'agni-importer-exporter-style');
        wp_enqueue_script( 'agni-importer-exporter-script');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Agni Demo Importer/Exporter', 'agni-importer-exporter' ); ?></h1>
            <div id="agni-importer-exporter" class="agni-importer-exporter"></div>
        </div>
        <?php

    }


    public function admin_enqueue_scripts(){


        $my_theme = wp_get_theme();

        $active_plugins = get_option('active_plugins');



        wp_enqueue_media();

        wp_register_style( 'agni-importer-exporter-style', AGNI_IMPORTER_EXPORTER_PLUGIN_URL . 'assets/css/main.css', array(), AGNI_IMPORTER_EXPORTER_PLUGIN_VERSION );
        
        wp_register_script( 'agni-importer-exporter-script', AGNI_IMPORTER_EXPORTER_PLUGIN_URL . 'assets/js/main.js', array(), AGNI_IMPORTER_EXPORTER_PLUGIN_VERSION, true );
        wp_localize_script('agni-importer-exporter-script', 'agni_import_export', array(
            'nonce' => wp_create_nonce('wp_rest'),
            'siteurl' => esc_url_raw( site_url() ),
            'resturl' => esc_url_raw( rest_url() ),
            'apipath' => 'wp-json/wp/v2',
            'theme_name' => $my_theme->Name,
            'theme_author' => $my_theme->display( 'Author', FALSE ),
            'theme_version' => $my_theme->Version,
            // 'export_url' => $this->get_export_url( 'url' ),
            'assetsurl' => AGNI_IMPORTER_EXPORTER_PLUGIN_URL . 'assets/img/',
            'activeplugins' => $active_plugins,
            'installpluginsurl' => esc_url( admin_url() ) . "admin.php?page=agni_install_plugins",
            // 'builderurl' => 'wp-admin/admin.php?page=agni_init', //menu_page_url('agni_init', false)
            
        ));
    }

    
}

$AgniImporterExporter = new AgniImporterExporter();
