<?php

/**
 * Plugin Name: Agni Font Manager
 * Plugin URI: http://agnidesigns.com
 * Description: This is the plugin to add fonts of Cartify eCommerce WordPress theme.
 * Version: 1.0.1-beta
 * Author: AgniHD 
 * Author URI: http://agnidesigns.com
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: agni-font-manager
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class AgniFontsManager {

    private $agni_fonts_dirname = 'agni-fonts';

    private $font_dirname;

    public function __construct() {


        // add_filter( 'upload_mimes', array( $this, 'font_mime_types') );

        // add_filter( 'wp_check_filetype_and_ext', array($this, 'disable_mime_check'), 10, 4 );


        $this->fonts_list_rest_api();

        add_action( 'agni_insert_fonts_manager', array( $this, 'agni_fonts_callback') );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'generate_fonts_css' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'generate_fonts_css' ) );

    }

    // function includes(){
    //     require_once 'class-fonts-rest-api.php';
    // }

    function disable_mime_check( $data, $file, $filename, $mimes ) {
        $wp_filetype = wp_check_filetype( $filename, $mimes );
    
        $ext = $wp_filetype['ext'];
        $type = $wp_filetype['type'];
        $proper_filename = $data['proper_filename'];
    
        return compact( 'ext', 'type', 'proper_filename' );
    }

    public function font_mime_types($mimes) {
        $mimes['otf'] = 'application/font-sfnt';
        $mimes['ttf'] = 'application/x-font-ttf';
        $mimes['woff'] = 'application/font-woff';
        $mimes['woff2'] = 'application/octet-stream'; //'font/woff2';

        $mimes['svg'] = 'image/svg+xml';
        $mimes['eot'] = 'application/vnd.ms-fontobject';

        return $mimes;
    }

    public function get_agni_fonts_dir( $path = 'dir' ){
        $upload_dir = wp_upload_dir();
        $upload_dir_path = $upload_dir['basedir'];

        if( $path == 'url' ){
            $upload_dir_path = $upload_dir['baseurl'];
        }

        return $upload_dir_path.'/'.$this->agni_fonts_dirname;

    }

    public function create_fonts_dir(){
        $agni_fonts_dir = $this->get_agni_fonts_dir();
        $font_dir = $agni_fonts_dir.'/'.$this->font_dirname;
        wp_mkdir_p( $font_dir );
    }

    public function fonts_upload_dir( $dir ) {

        if( isset($this->agni_fonts_dirname) && isset( $this->font_dirname ) ){
            return array(
                'path'   => $dir['basedir'] . '/'.$this->agni_fonts_dirname.'/'.$this->font_dirname,
                'url'    => $dir['baseurl'] . '/'.$this->agni_fonts_dirname.'/'.$this->font_dirname,
                'subdir' => '/'.$this->agni_fonts_dirname.'/'.$this->font_dirname,
            ) + $dir;
        }
        
    }

    public function font_file_overwrite($dir, $name, $ext){
        //return $name.$ext;
        return $name;
    }


    public function fonts_list_rest_api(){

        add_action( 'rest_api_init', array($this, 'register_fonts_list_api' ), 10 );

    }

    // agni_header_builder_header_custom
    // agni_header_builder_header_default
    // agni_header_builder_header_presets


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
            'callback' => array( $this, 'edit_fonts_list_custom_fonts' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
         ) );

         register_rest_route( 'agni-fonts/v1', '/custom_fonts_files_upload', array(
             'methods' => WP_REST_Server::EDITABLE,
             'callback' => array( $this, 'upload_font_files_custom_fonts' ),
             'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
         ) );

         
    }
    public function get_fonts_list( WP_REST_Request $request ){
        $fonts_list = get_option( 'agni_font_manager_list' )[0];

        return $fonts_list;
    }
    // public function get_options( WP_REST_Request $request ) {
    //     $id = $request['id'];
    //     $results = get_option( 'agni_header_builder_headers_list' );
    //     return $results[$id];
    //  }

    public function edit_fonts_list_adobe_fonts( WP_REST_Request $request ){

        $typekit_id = $request['id'];

        $fonts_list = get_option('agni_font_manager_list')[0];
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
        update_option( 'agni_font_manager_list', array($fonts_list) );

        return $fonts_list['adobe_fonts'];

    }

     public function edit_fonts_list_google_fonts( WP_REST_Request $request ){

        $font = json_decode($request['font'], true);

        // print_r($font);

        $fonts_list = get_option('agni_font_manager_list')[0];


        foreach( $fonts_list['google_fonts']['families'] as $key => $family ){
            if($family['name'] == $font['name']){
                
                $font['variants'] = array_unique( array_merge((array)$family['variants'], (array)$font['variants'] ) );
                unset( $fonts_list['google_fonts']['families'][$key] );
            }
        }

        $fonts_list['google_fonts']['families'][] = $font;

        update_option( 'agni_font_manager_list', array( $fonts_list ) );

        return $fonts_list['google_fonts'];

     }

     public function upload_font_files_custom_fonts( WP_REST_Request $request ){

        $files = $request->get_file_params();

        if( !isset( $files ) ){
            return ;
        }

        $fonts = json_decode( $request['fonts'], true );

        $this->font_dirname = $fonts["name"];


        // print_r($fonts);

        $this->create_fonts_dir();

        $success_msg = $error_msg = $msg = '';

        $allowed_file_types = array('eot' =>'application/vnd.ms-fontobject', 'otf' =>'application/font-sfnt', 'ttf' => 'application/x-font-ttf', 'woff' =>'application/font-woff', 'woff2' => 'application/octet-stream', 'svg' => 'image/svg+xml'); //'woff2' => 'font/woff2',  'application/font-woff2', 'application/octet-stream'

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

        if(!empty($success_msg)){
            $msg .= '<div class="updated">'.$success_msg.'</div>';
        }
        if(!empty($error_msg)){
            $msg .= '<div class="error">'.$error_msg.'</div>';
        }

        return $msg;
     }

    public function edit_fonts_list_custom_fonts( WP_REST_Request $request ) {
        

        $fonts_list = get_option('agni_font_manager_list')[0];

        $fonts = json_decode( $request['fonts'], true );

        $this->font_dirname = $fonts["name"];


        foreach( $fonts_list['custom_fonts']['families'] as $key => $family ){
            if($family['name'] == $fonts['name']){
                $fonts['content'] = array_replace_recursive((array)$family['content'], (array)$fonts['content']);
                unset($fonts_list['custom_fonts']['families'][$key]);
            }
        }
        // print_r($fonts);
        
        $fonts_list['custom_fonts']['families'][] = $fonts;

        if( !isset( $fonts_list['custom_fonts']['fonts_url'] ) ){
            $fonts_url = $this->get_agni_fonts_dir( $path = 'url' );
            $fonts_list['custom_fonts']['fonts_url'] = $fonts_url;
        }

        update_option( 'agni_font_manager_list', array($fonts_list) );

        return $fonts_list['custom_fonts'];

    }

    public function delete_fonts_list( WP_REST_Request $request ){
        $font_src = $request['src'];
        $font_name = $request['name'];

        

        $this->font_dirname = $font_name;

        $fonts_list = get_option( 'agni_font_manager_list' )[0];
        
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
            if( empty( $fonts_list['custom_fonts']['families'] ) ){
                unset( $fonts_list['custom_fonts']['fonts_url'] );
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

        update_option( 'agni_font_manager_list', array($fonts_list) );

        return $fonts_list;
    }


    public function agni_fonts_callback(){


        wp_enqueue_style( 'agni-font-manager-react-style');
        wp_enqueue_script( 'agni-font-manager-react-script');
        ?>
        
        <div class="wrap">
            <h1><?php echo esc_html__( 'Agni Fonts Manager', 'agni-cartify' ); ?></h1>
            <div id="agni-font-manager" class="agni-font-manager"></div>
        </div>

        <?php

        // print_r( get_option('agni_font_manager_list') );
        
    }

    public function generate_fonts_css(){
        $fonts_list_array = get_option('agni_font_manager_list');

        if( empty( $fonts_list_array ) ){
            return;
        }
        
        $fonts_list = $fonts_list_array[0];
        $fonts_list_custom_fonts = isset( $fonts_list['custom_fonts']['families'] )?$fonts_list['custom_fonts']['families']: '';
        $fonts_list_google_fonts = isset( $fonts_list['google_fonts']['families'] )?$fonts_list['google_fonts']['families']: '';
        $fonts_list_adobe_fonts = isset( $fonts_list['adobe_fonts'] )?$fonts_list['adobe_fonts']: '';

        $styles = '';
        $font_face = '';

        if( !empty( $fonts_list_custom_fonts ) ){
            foreach( $fonts_list_custom_fonts as $font_family ){
                $font_family_name = $font_family['name'];
                $font_family_content = $font_family['content'];


                foreach( $font_family_content as $font_family_weight){
                    $font_family_weight_filename = $font_family_weight['filename'];
                    $font_family_weight_extensions = $font_family_weight['extensions'];
                    $font_family_weight_weight = $font_family_weight['weight'];
                    $font_family_weight_italic = '';

                    if( strpos($font_family_weight_weight, 'i') !== false ){
                        $font_family_weight_weight =  str_replace( 'i', '', $font_family_weight_weight );
                        $font_family_weight_italic = 'italic';
                    }
                    
                    $src = array();
                    foreach( $font_family_weight_extensions as $key => $extenstion ){

                        $src[$key] = 'url("' . $this->get_agni_fonts_dir( $path = 'url') .'/' . $font_family_name .'/'.$font_family_weight_filename . $extenstion .'")';

                        switch( $extenstion ){
                            // case '.eot':
                            //     $src[]= "url('${$this->get_agni_fonts_dir() .'/' . $font_family_name .'/'.$font_family_content->$font_family_weight_filename . $extenstion}?#iefix') format('embedded-opentype')";
                            //     break;
                            case '.woff2':
                                $src[$key] .= ' format("woff2")';
                                break;
                            case '.woff':
                                $src[$key] .= ' format("woff")';
                                break;
                            case '.ttf':
                                $src[$key] .= ' format("truetype")';
                                break;
                            case '.otf':
                                $src[$key] .= ' format("opentype")';
                                break;
                            case '.svg':
                                $src[$key] .= ' format("svg")';
                                break;
                        }

                    }
                    $src_combined = implode(', ', $src);

                    $font_face .= '@font-face{
                        font-family: "'. $font_family_name .'";
                        src: '. $src_combined .';
                        font-weight: '. $font_family_weight_weight .';
                        '.(!empty($font_family_weight_italic)?'font-style: ' . $font_family_weight_italic . ';' : ''). '
                    }
                    ';
                }

            }
        }

        if( !empty($font_face) ){

            // print_r( $font_face );

            wp_enqueue_style('agni-font-manager-custom-fonts', AGNI_PLUGIN_URL . 'assets/css/custom.css');
            wp_add_inline_style('agni-font-manager-custom-fonts', $font_face);
        }




        $google_fonts_param = '';
        $google_fonts_param_array = array();
        if( !empty($fonts_list_google_fonts) ){
            $google_fonts_list = $fonts_list_google_fonts;
            foreach( $google_fonts_list as $font_family_key => $font_family ){
                $google_fonts_list[$font_family_key]['name'] = urlencode($font_family['name']);

                // foreach($font_family['variants'] as $variant_key => $variant){
                //     $variant = ($variant == 'regular')?'400': $variant;
                //     $variant = str_replace('italic', 'i', $variant);
                //     $google_fonts_list[$font_family_key]['variants'][$variant_key] = $variant;
                // }
                $font_family_detail = $google_fonts_list[$font_family_key];
                $google_fonts_param_array[] = $font_family_detail['name'] . ':' . implode(',', $font_family_detail['variants']);
            }

            $google_fonts_param = implode('|', $google_fonts_param_array);

        }

        if( !empty( $google_fonts_param ) ){
            wp_enqueue_style( 'agni-font-manager-google-fonts', '//fonts.googleapis.com/css?family=' . esc_attr( $google_fonts_param ) );
        }

        $adobe_fonts_typekit_id = '';
        if( !empty( $fonts_list_adobe_fonts ) ){
            $adobe_fonts_typekit_id = $fonts_list_adobe_fonts['typekit_id'];
        }

        if( !empty( $adobe_fonts_typekit_id ) ){
            wp_enqueue_style( 'agni-font-manager-adobe-fonts', '//use.typekit.net/'. esc_attr( $adobe_fonts_typekit_id  ).'.css' );
        }
        

        // echo '<div>';
        // print_r($fonts_list);
        // echo '</div>';
    }
   

    public function existing_fonts_list(){
        $fonts_list = get_option('agni_font_manager_list');
        if( empty( $fonts_list ) ){
            return;
        }

        return $fonts_list[0];
    }


    public function enqueue_scripts(){

        wp_enqueue_media();

        wp_register_style( 'agni-font-manager-react-style', AGNI_PLUGIN_URL . 'assets/css/agni-fonts-manager/main.css', array(), AGNI_PLUGIN_VERSION );
        
        wp_register_script( 'agni-font-manager-react-script', AGNI_PLUGIN_URL . 'assets/js/agni-fonts-manager/main.js', array(), AGNI_PLUGIN_VERSION, true );
        wp_localize_script('agni-font-manager-react-script', 'agni_font_manager', array(
            'nonce' => wp_create_nonce('wp_rest'),
            'siteurl' => esc_url_raw( site_url() ),
			'resturl' => esc_url_raw( rest_url('agni-fonts/v1') ),
            'apipath' => 'wp-json/wp/v2',
            // 'builderurl' => 'wp-admin/admin.php?page=agni_header_builder', //menu_page_url('agni_header_builder', false)
            'fontslist' => $this->existing_fonts_list(),
            'assetsurl' => AGNI_PLUGIN_URL . 'assets/img/'
        ));
    }

    
}

$AgniFontsManager = new AgniFontsManager();
