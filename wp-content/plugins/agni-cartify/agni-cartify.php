<?php

/**
 * Plugin Name: Agni Cartify
 * Plugin URI: http://agnidesigns.com
 * Description: This is core plugin of Cartify eCommerce WordPress theme.
 * Version: 1.0.7
 * Author: AgniHD
 * Author URI: http://agnidesigns.com
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: agni-cartify
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Agni_Cartify {

    public function __construct() {

        $this->define_constants();


        add_action( 'init', array( $this, 'textdomain'), 1 );

        add_action( 'woocommerce_init', array( $this, 'requires_plugins_loaded'), 1 );

        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );;

        add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );

        $this->includes();
        $this->requires();


    }

    public function define_constants(){
        if( ! function_exists('get_plugin_data') ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $plugin_data = get_plugin_data( __FILE__ );

        define( 'AGNI_PLUGIN_TEXTDOMAIN', $plugin_data['TextDomain'] );
        define( 'AGNI_PLUGIN_VERSION', '1.0.0' );
        define( 'AGNI_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // pointing exact plugin folder url.
        define( 'AGNI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // pointing exact plugin folder directory.
        define( 'AGNI_PLUGIN_FILE_PATH', plugin_basename( __FILE__ ) ); // pointing plugin file.
    }

    public function textdomain(){
        load_plugin_textdomain( 'agni-cartify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    public function includes(){
        include( 'inc/class-helper.php' );
    }

    public function requires(){

        /* Custom Post Types */
        require_once( 'inc/custom-posttypes.php' );

        /* Custom Rest API calls */
        require_once( 'inc/custom-rest-api.php' );

        /* Custom Post Meta */
        // require_once( 'inc/custom-meta-boxes.php' );
        require_once( 'inc/meta-boxes/custom-meta-boxes.php' );

        /* Admin Menu Panel */
        require_once( 'inc/admin/system-status.php' );

        /* Agni Header Builder */
        require_once( 'inc/header-builder/class-header-builder.php' );

        /* Agni Product Builder */
        require_once( 'inc/product-builder/class-product-builder.php' );

        // /* Agni Builder */
        // require_once( 'inc/builder' );

        /* Agni Slider */
        require_once( 'inc/slider/class-slider-builder.php' );

        /* Agni Fonts Manager */
        require_once( 'inc/fonts-manager/class-fonts-manager.php' );

        /* Agni Plugins Installer */
        // require_once( 'inc/product-registration/class-product-registration.php' );

        /* Agni Plugins Installer */
        // require_once( 'inc/plugins-installer/class-plugins-installer.php' );

    }


    public function requires_plugins_loaded(){

        // WooCommerce function for theme.
        if( class_exists( 'WooCommerce' ) ){

            // Product Filter
            require_once( 'inc/widgets/product-filters/class-product-filters.php' );

        }
    }


    public function enqueue_scripts(){

        wp_register_style( 'agni-cartify-styles', AGNI_PLUGIN_URL . 'assets/css/main.css' );
        wp_enqueue_style( 'agni-cartify-styles' );


    }

    public function admin_enqueue_scripts(){

        wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');

        // wp_register_style( 'agni-cartify-styles-admin', AGNI_PLUGIN_URL . 'assets/css/admin/main.css' );
        // wp_enqueue_style( 'agni-cartify-styles-admin' );


        wp_register_script( 'agni-cartify-scripts-admin', AGNI_PLUGIN_URL . 'assets/js/admin-scripts.js' );
        wp_enqueue_script( 'agni-cartify-scripts-admin' );

    }
}

$agni_cartify = new Agni_Cartify();
