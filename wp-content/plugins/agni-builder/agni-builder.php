<?php
/**
 * Plugin Name: Agni Builder
 * Plugin URI: http://agnidesigns.com
 * Description: Next Generation tool for creating awesome wordpress websites from frontend.
 * Version: 1.0.4
 * Author: AgniHD
 * Author URI: http://agnidesigns.com
 * Text Domain: agni-builder
 * 
 * @since 1.0.0
 * @package AgniBuilder
 */

if( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if( !class_exists( 'AgniBuilder' ) ){
    class AgniBuilder {

        // constructor
        public function __construct(){

            $this->define_constants();

            add_action( 'init', array( $this, 'textdomain'), 1 );

            // initiate all functions
            add_action( 'init', array( $this, 'includes' ) );

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        }

        public function define_constants(){
            if( ! function_exists('get_plugin_data') ){
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            $plugin_data = get_plugin_data( __FILE__ );
    
            // Assign constants.
            define( 'AGNI_BUILDER_PLUGIN_TEXTDOMAIN', $plugin_data['TextDomain'] );
            define( 'AGNI_BUILDER_PLUGIN_VERSION', '1.0.0' );
            define( 'AGNI_BUILDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // pointing exact plugin folder url.
            define( 'AGNI_BUILDER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // pointing exact plugin folder directory.
            define( 'AGNI_BUILDER_PLUGIN_FILE_PATH', plugin_basename( __FILE__ ) ); // pointing plugin file.
    
        }

        public function textdomain(){
            load_plugin_textdomain( 'agni_builder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }

        public function includes(){

            require_once 'inc/class-rest-api.php';
            require_once 'inc/class-blocks-ajax-functions.php';
            require_once 'inc/class-helper.php';

            require_once 'gutenberg-blocks/class-blocks.php';
            require_once 'gutenberg-blocks/class-dynamic-blocks.php';
            require_once 'gutenberg-blocks/class-blocks-css.php';
            require_once 'gutenberg-blocks/class-blocks-css-processor.php';
            require_once 'gutenberg-blocks/class-patterns.php';

        }


        public function enqueue_scripts(){
            $instagram_token = $google_map_api = '';
            
            if( function_exists( 'cartify_get_theme_option' ) ){
                $instagram_token = esc_attr( cartify_get_theme_option('api_settings_instagram_token', '') );
                $google_map_api = esc_attr( cartify_get_theme_option('api_settings_google_map_api', '') );
            }

            // Registering CSS
            wp_register_style('slick', AGNI_BUILDER_PLUGIN_URL . 'assets/css/vendor/slick.min.css', array(), AGNI_BUILDER_PLUGIN_VERSION);
            wp_register_script( 'cartify-photoswipe-style', AGNI_BUILDER_PLUGIN_URL . 'assets/css/vendor/photoswipe.min.css', array(), AGNI_BUILDER_PLUGIN_VERSION );
            wp_register_style('agni-builder-frontend-styles-custom', AGNI_BUILDER_PLUGIN_URL . 'assets/css/custom.css', array(), AGNI_BUILDER_PLUGIN_VERSION);

            // Enqueue CSS
            wp_enqueue_style( 'agni-builder-editor-main', AGNI_BUILDER_PLUGIN_URL . 'assets/css/main.css', array(), AGNI_BUILDER_PLUGIN_VERSION );

            // Registering JS
            wp_register_script( 'slick', AGNI_BUILDER_PLUGIN_URL . 'assets/js/vendor/slick.min.js', array( 'jquery' ), AGNI_BUILDER_PLUGIN_VERSION, true );
            wp_register_script( 'cartify-photoswipe-script', AGNI_BUILDER_PLUGIN_URL . 'assets/js/vendor/photoswipe.min.js', array( 'jquery' ), AGNI_BUILDER_PLUGIN_VERSION, true );

            if( !empty( $google_map_api ) ){
                wp_register_script( 'googleapi', '//maps.google.com/maps/api/js?key=' . $google_map_api );
            }

            // wp_enqueue_style( 'slick' );
            // wp_enqueue_script( 'slick' );
            
            // Enqueueing JS
            wp_enqueue_script( 'agni-builder-frontend-scripts', AGNI_BUILDER_PLUGIN_URL . 'assets/js/frontend/scripts.js', array(), AGNI_BUILDER_PLUGIN_VERSION, true );
            wp_localize_script('agni-builder-frontend-scripts', 'agni_builder_frontend', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                // 'security' => wp_create_nonce('agni_builder_frontend_nonce'),
                'ajaxurl_wc' => class_exists( 'WC_AJAX') ? WC_AJAX::get_endpoint( "%%endpoint%%" ) : '',
                'map_marker' => AGNI_BUILDER_PLUGIN_URL . 'assets/img/marker.png',
                'instagram_token' => $instagram_token,
                'breakpoints' => array(
                    'desktop' => '1440',
                    'laptop' => '1024',
                    'tab' => '667',
                    'mobile' => ''
                )

            ));

            
        }

    }
}

$AgniBuilder = new AgniBuilder();