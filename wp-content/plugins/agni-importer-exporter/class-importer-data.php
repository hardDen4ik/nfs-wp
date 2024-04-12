<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class AgniImporterData{

    public function __construct() {

    }

    public static function getToken(){

        $url = 'https://api.agnihd.com/agni-purchase-verifier/agni-purchase-verifier.php?get_token=1';

        $args = array(
            'headers'     => array(),
            'timeout'     => 60,
            'redirection' => 5,
            'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => true, // make it true for live
        );

        // Make an API request.
        $response = wp_remote_get( esc_url_raw( $url ), $args );

        // Check the response code.
        $response_code    = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        $debugging_information['response_code']   = $response_code;
        $debugging_information['response_cf_ray'] = wp_remote_retrieve_header( $response, 'cf-ray' );
        $debugging_information['response_server'] = wp_remote_retrieve_header( $response, 'server' );
        
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines
            $body    = $response['body']; // use the content

            // return 'https://api.agnihd.com/agni-purchase-verifier/api.php?file=envato-market.zip&token=' . json_decode( $body );
            return json_decode( $body );
        }

        return $response;
        // $response = wp_remote_get( 'https://api.agnihd.com/agni-purchase-verifier/api.php' );
    }


    public static function downloadImportData( $token ){

        
        $result = array();

        $agni_import_info = AgniImporterExporter::get_import_file_info();

        $upload_dir = wp_upload_dir();
        $upload_dir_path = $upload_dir['basedir'];
        
        $agni_import_dir = $upload_dir_path . '/' . $agni_import_info['dirname'];
        $agni_import_file = $upload_dir_path . '/' . $agni_import_info['dirname'] . '/' . $agni_import_info['filename'];
        

        global $wp_filesystem;
        // Initialize the WP filesystem, no more using 'file-put-contents' function
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }


        if ( !$wp_filesystem->exists( $agni_import_dir ) ) {
            $creating_dir = wp_mkdir_p( $agni_import_dir );

            if( !$creating_dir ){
                $result = array( 'error' => esc_html__( 'Cannot able to create folder', 'agni-importer-exporter' ) );

                return $result;
            }
        }


        // $pluginSlug = 'export';
        // $fileSystemDirect = new WP_Filesystem_Direct(false);
        // $fileSystemDirect->rmdir($dir, true);

        $body = array(
            // 'file' => $pluginSlug . '.zip',
            // 'item_code' => Agni_Product_Registration::get_item_code(),
            'purchase_code' => Agni_Product_Registration::get_purchase_code(),
            'domain' => Agni_Product_Registration::get_domain_name()
        );

        // print_r( $body );
        $url = 'https://api.agnihd.com/agni-purchase-verifier/agni-purchase-verifier.php?download_import_data=1' ;


        $args = array(
            'body'  => json_encode( $body ),
            'headers'     => array(
                'Content-Type' => 'application/json',
                // 'Content-Type' => 'application/octet-stream',
                'Authorization' => 'Bearer ' . $token
            ),
            'timeout'     => 120,
            'redirection' => 5,
            'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => true, // make it true for live
        );

        // Make an API request.
        $response = wp_remote_post( esc_url_raw( $url ), $args );

        // Check the response code.
        $response_code    = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        $debugging_information['response_code']   = $response_code;
        $debugging_information['response_cf_ray'] = wp_remote_retrieve_header( $response, 'cf-ray' );
        $debugging_information['response_server'] = wp_remote_retrieve_header( $response, 'server' );
        $source = '';
        // print_r( $response );


        if( is_wp_error( $response ) ){
            $result = array( 'error' => $response->errors );
        }

        if ( is_array( $response ) && !is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines
            $body    = $response['body']; // use the content

            // print_r( $body ); 
            $decoded_body = json_decode($body);
            if( $decoded_body->error ){
                $result = array( 'error' => $body );
            }
            else{

                // $upload_dir = wp_upload_dir();
                // $upload_dir_path = $upload_dir['basedir'];
                
                // $agni_import_dir = $upload_dir_path . '/' . $agni_import_info['dirname'];
                // $agni_import_file = $upload_dir_path . '/' . $agni_import_info['dirname'] . '/' . $agni_import_info['filename'];
                
                // wp_mkdir_p( $agni_import_dir );
                if ( $wp_filesystem->exists( $agni_import_dir ) ) {

                    $temp_zip_name = AgniImporterData::random_string();

                    $pluginAdded = $wp_filesystem->put_contents( $agni_import_dir . "/{$temp_zip_name}.zip", $body );


                    if(!$pluginAdded ) {
                        $result = array( 'error' => esc_html__( 'Failed to add import data file', 'agni-importer-exporter' ) );
                    }
                    else{

                        if ( $wp_filesystem->exists( $agni_import_file ) ) {
                            wp_delete_file( $agni_import_file );
                        }

                        // if ( !$wp_filesystem->exists( $agni_import_file ) ) {
                        $unzip_file = unzip_file( $agni_import_dir . "/{$temp_zip_name}.zip", $agni_import_dir );

                        if( !is_wp_error( $unzip_file ) ){
                            wp_delete_file( $agni_import_dir . "/{$temp_zip_name}.zip" );

                            $result = array( 'success' => esc_html__( 'Import data file added successfully', 'agni-importer-exporter' ) );
                        
                        }
                        else{
                            $result = array( 'error' => esc_html__( 'Failed to extract package', 'agni-importer-exporter' ) );

                        }
                        // }
                        // else{
                        //     $result = array( 'error' => esc_html__( 'File already exist.', 'agni-importer-exporter' ) );
                        // }
                        
                    }
                }
                else{
                    $result = array( 'error' => esc_html__( 'Folder doesn\'t exist.', 'agni-importer-exporter' ) );

                }
            }

        }

        return $result;
    }


    public static function random_string($length = '16') {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
    
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
    
        return $key;
    }
}

$AgniImporterData = new AgniImporterData();