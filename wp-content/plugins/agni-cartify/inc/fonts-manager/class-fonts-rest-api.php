<?php 

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class AgniFontsRestApi{

    private $agni_fonts_dirname = 'agni-fonts';

    private $font_dirname;

    public function __construct(){

        add_action( 'rest_api_init', array($this, 'register_fonts_list_api' ), 10 );
    }

    public function register_fonts_list_api(){

        $current_user_can = current_user_can( 'edit_posts' );

        register_rest_route( 'agni-fonts/v1', '/fonts', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_fonts_list'),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );
        // register_rest_route( 'agni-fonts/v1', '/fonts/(?P<id>\d+)', array(
        //     'methods' => WP_REST_Server::EDITABLE,
        //     'callback' => array($this, 'edit_fonts_list'),
        // ) );

        register_rest_route( 'agni-fonts/v1', '/fonts_delete', array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => array( $this, 'delete_fonts_list' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
         ) );
        

        register_rest_route( 'agni-fonts/v1', '/adobe_fonts', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'edit_fonts_list_adobe_fonts' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
         ) );
        register_rest_route( 'agni-fonts/v1', '/google_fonts', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'edit_fonts_list_google_fonts' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
         ) );
         register_rest_route( 'agni-fonts/v1', '/custom_fonts', array(
            'methods' => WP_REST_Server::EDITABLE,
            // 'callback' => array( $this, 'edit_fonts_list_custom_fonts' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
         ) );

         
    }
    public function get_fonts_list( WP_REST_Request $request ){
        $fonts_list = get_option( 'agni_font_manager_list' );

        return $fonts_list;
    }
    // public function get_options( WP_REST_Request $request ) {
    //     $id = $request['id'];
    //     $results = get_option( 'agni_header_builder_headers_list' );
    //     return $results[$id];
    //  }

    public function edit_fonts_list_adobe_fonts( WP_REST_Request $request ){

        $typekit_id = $request['id'];

        $fonts_list = get_option('agni_font_manager_list');
        $fonts_list['adobe_fonts']['families'] = array();

        $typekit_response = wp_remote_get('https://typekit.com/api/v1/json/kits/' . $typekit_id . '/published');
        $typekit_response_body = json_decode( wp_remote_retrieve_body( $typekit_response ), true );

        $font_families = $typekit_response_body['kit']['families'];

        // print_r($font_families);

        foreach( $font_families as $family ){
            $family_variations = array();
            foreach( $family['variations'] as $variation){
                $string = 'n3';
                $variation = preg_replace( '/(\w)(\d{1,2})/','${2}00\1', $variation );
                $family_variations[] = str_replace( 'n', '', $variation );

            }
            $fonts_list['adobe_fonts']['families'][] = array(
                'name' => $family['name'],
                'variants' => $family_variations,
            );
        }


        $fonts_list['adobe_fonts']['typekit_id'] = $typekit_id;
        // $fonts_list['adobe_fonts']['families'] = ''

        // update_option( 'agni_font_manager_list', array() );
        update_option( 'agni_font_manager_list', $fonts_list );

        return $fonts_list['adobe_fonts'];

    }

     public function edit_fonts_list_google_fonts( WP_REST_Request $request ){

        $font = json_decode($request['font'], true);

        // print_r($font);

        $fonts_list = get_option('agni_font_manager_list');

        // $font = array(
        //     'name' => $font['name'],
        //     'variants' => $font['variants'],
        // );

        foreach( $fonts_list['google_fonts']['families'] as $key => $family ){
            if($family['name'] == $font['name']){
                
                $font['variants'] = array_unique( array_merge((array)$family['variants'], (array)$font['variants'] ) );
                unset( $fonts_list['google_fonts']['families'][$key] );
            }
        }

        $fonts_list['google_fonts']['families'][] = $font;

        // print_r( $fonts_list['google_fonts']['families'] );

        // update_option( 'agni_font_manager_list', array());
        update_option( 'agni_font_manager_list', $fonts_list );

        return $fonts_list['google_fonts'];

     }

    public function edit_fonts_list_custom_fonts( WP_REST_Request $request ) {
        
        $files = $request->get_file_params();

        // print_r($request['font_name'] );
        echo "Files:";
        // print_r($files );
        echo "Files Listed";

        if( !isset( $files ) ){
            return ;
        }

        $fonts_list = get_option('agni_font_manager_list');
        // $fonts_array = array();

        // foreach( $fonts_list['custom_fonts']['families'] as $font_family){
        //     $fonts_array[] = $font_family->name;
        // }

        $fonts = json_decode( $request['fonts'], true );

        $this->font_dirname = $fonts["name"];


        // print_r($fonts);

        $this->create_fonts_dir();

        $success_msg = $error_msg = $msg = '';

        $allowed_file_types = array('eot' =>'application/vnd.ms-fontobject', 'otf|ttf' =>'application/font-sfnt','woff' =>'application/font-woff', 'woff2' => 'application/octet-stream', 'svg' => 'image/svg+xml'); //'woff2' => 'font/woff2',  'application/font-woff2', 'application/octet-stream'

        $upload_overrides = array( 'test_form' => false, 'unique_filename_callback' => array($this, 'font_file_overwrite'), 'mimes' => $allowed_file_types );

        foreach( $files as $key => $file ){
            // print_r( $file );


            if(!function_exists('wp_handle_upload')){
                require_once(ABSPATH . 'wp-admin/includes/file.php');
            }

            add_filter( 'upload_dir', array($this, 'fonts_upload_dir') );

            add_filter('upload_mimes', array( $this, 'font_mime_types') );

            $movefile = wp_handle_upload( $file, $upload_overrides );
            // print_r($movefile);

            remove_filter('upload_mimes', array( $this, 'font_mime_types') );

            // remove_filter( 'wp_check_filetype_and_ext', array($this, 'disable_mime_check'), 10, 4 );

            remove_filter( 'upload_dir', array($this, 'fonts_upload_dir') );

            if ( $movefile && !isset( $movefile['error'] ) ) {
                $success_msg .= '<p><strong>'.$file['name'].'</strong> Uploaded Successfully.</p>';
            } else {
                $error_msg .= '<p><strong>'.$file['name'].'</strong> '.$movefile['error'].'</p>';
            }

        }

        // if(!empty($success_msg)){
        //     echo '<div class="updated">'.$success_msg.'</div>';
        // }
        // if(!empty($error_msg)){
        //     echo '<div class="error">'.$error_msg.'</div>';
        // }
        if(!empty($success_msg)){
            $msg .= '<div class="updated">'.$success_msg.'</div>';
        }
        if(!empty($error_msg)){
            $msg .= '<div class="error">'.$error_msg.'</div>';
        }

        foreach( $fonts_list['custom_fonts']['families'] as $key => $family ){
            if($family['name'] == $fonts['name']){
                $fonts['content'] = array_replace_recursive((array)$family['content'], (array)$fonts['content']);
                unset($fonts_list['custom_fonts']['families'][$key]);
            }
        }
        // print_r($fonts);
        
        $fonts_list['custom_fonts']['families'][] = $fonts;

        // print_r($fonts_list['custom_fonts']['families']);

        // update_option( 'agni_font_manager_list', array() );
        update_option( 'agni_font_manager_list', $fonts_list );

        // return $fonts_list['custom_fonts'];


        // $url_param = $request->get_url_params();
        // $header_id = $url_param['id'];

        // $results = (array)get_option( 'agni_header_builder_headers_list' );
        // $option      = array(
        //    'firstName' => $request['firstName'],
        //    'lastName' => $request['lastName'],
        // );
        // //$option_json = wp_json_encode( $option );
        // //$results[100] = $option_json;
        // $results[$header_id] = $option;
        // $results      = update_option( 'agni_header_builder_headers_list', $results );
        // //update_option( 'agni_header_builder_headers_list', $parameters );
        // //$results = $parameters;
        // return $results;
    }

    public function delete_fonts_list( WP_REST_Request $request ){
        $font_src = $request['src'];
        $font_name = $request['name'];

        // if( $font_src == 'custom_fonts' ){

        // }
        // else{

        // }


        $this->font_dirname = $font_name;

        $fonts_list = get_option( 'agni_font_manager_list' );
        
        if( $font_src == 'custom_fonts' ){

            add_filter( 'upload_dir', array($this, 'fonts_upload_dir') );

            // print_r( wp_upload_dir() );

            $upload_dir = wp_upload_dir();
            // $file_dirname = $upload_dir['basedir'].'/'.$custom_dirname;

            foreach( $fonts_list['custom_fonts']['families'] as $key => $family){
                if($family['name'] == $font_name){     
                    foreach( $family['content'] as $weight){
                        foreach( $weight['extensions'] as $extension ){
                            wp_delete_file( $upload_dir['path'] . '/' . $weight['filename'] . $extension );
                        }
                    }

                    unset( $fonts_list['custom_fonts']['families'][$key] );
                }
            }

            rmdir($upload_dir['path']);

            remove_filter( 'upload_dir', array($this, 'fonts_upload_dir') );

        }
        else if( $font_src == 'google_fonts' ){

            foreach( $fonts_list['google_fonts']['families'] as $key => $family){
                if($family['name'] == $font_name){    

                    unset( $fonts_list['google_fonts']['families'][$key] );
                }
            }

        }
        else{
            $fonts_list['adobe_fonts'] = array();
        }
        // if(rmdir($upload_dir['path'])){
		// 	echo '<div class="updated"><p><strong>'.$font_name.'</strong> Removed successfully.</p></div>';
		// }
		// else{
		// 	echo '<div class="error"><p>Error on removing '.$font_name.'</p></div>';
		// }

        // wp_delete_file( $weight['filename'] );

        // print_r( $fonts_list );

        update_option( 'agni_font_manager_list', $fonts_list );

        return $fonts_list;
    }
}

$AgniFontsRestApi = new AgniFontsRestApi();