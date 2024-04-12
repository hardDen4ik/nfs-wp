<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Agni_Slider_Builder {

    public $slider_builder_menu_icon = '';

    public $slider_builder_menu_position = 1;

    public function __construct(){

        $this->includes();

        add_action( 'agni_insert_slider_builder', array( $this, 'slider_builder_contents') );
        add_action( 'admin_enqueue_scripts',  array( $this, 'enqueue_scripts' ) );
    }

    public function includes(){
        require_once 'class-slider-rest-api.php';
    }

    public function product_builder_menu_page(){

        // add_menu_page( esc_html__( 'Slider', 'agni-cartify' ), esc_html__( 'Agni Slider', 'agni-cartify' ), 'edit_theme_options', 'agni_slider_builder', array( $this, 'slider_builder_contents' ), $this->slider_builder_menu_icon, $this->slider_builder_menu_position );
        add_submenu_page( 'cartify', esc_html__( 'Slider Builder', 'agni-cartify' ), esc_html__( 'Slider Builder', 'agni-cartify' ), 'edit_theme_options', 'agni_slider_builder', array( $this, 'slider_builder_contents' ), $this->slider_builder_menu_position );

        ?>
        <?php


    }

    public function slider_builder_contents(){

        wp_enqueue_style( 'agni-slider-builder-react-style');
        wp_enqueue_script( 'agni-slider-builder-react-script');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Agni Slider Builder', 'agni-cartify' ); ?></h1>
            <div id="agni-slider-builder" class="agni-slider-builder">
                <div id="agni-slider-builder-contents" class="agni-slider-builder-contents"></div>
                <div id="agni-slider-builder-panel" class="agni-slider-builder-panel"></div>
                <div id="agni-slider-builder-block-options" class="agni-slider-builder-block-options"></div>
            </div>
        </div>

        <?php
        // echo json_encode( get_option('agni_slider_builder_sliders') );

        // $json_encode = '';

        // update_option('agni_slider_builder_sliders', json_decode( $json_encode, true ));

    }

    public function get_blocks_list(){
        $blocks_args = array( 'post_type' => 'agni_block' );
        $blocks = apply_filters('agni_get_posttype_options', $blocks_args, true );

        return $blocks;
    }

    public function get_existing_sliders_list(){
        return get_option('agni_slider_builder_sliders');
    }

    public function get_existing_presets_list(){
        $slider_presets = array();

        if( class_exists( 'Agni_Slider' ) ){
            $slider_presets = Agni_Slider::get_slider_json_file( 'slider-presets' );
        }

        return $slider_presets;
    }

    public function get_fonts_list(){
        $fonts_list = get_option('agni_font_manager_list');
        // print_r($fonts_list);
        $new_fonts_list = array();
        if( !empty( $fonts_list ) ){
            foreach ($fonts_list[0] as $fonts_src => $fonts) {
                if( !empty( $fonts ) ){
                    foreach ($fonts['families'] as $family) {
                        if( $fonts_src == 'custom_fonts' ){
                            $custom_family = array(
                                'name' => $family['name'],
                                'variants' => array()
                            );
                            foreach ($family['content'] as $key => $value) {
                                array_push( $custom_family['variants'], $value['weight']);
                            }
                            array_push($new_fonts_list, $custom_family);
                        }
                        else{
                            array_push($new_fonts_list, $family);
                        }
                    }
                }
            }
        }
        // print_r($new_fonts_list);

        return $new_fonts_list;
    }

    public function enqueue_scripts(){

        wp_enqueue_media();

        wp_register_style( 'agni-slider-builder-react-style', AGNI_PLUGIN_URL . 'assets/css/agni-slider/main.css', array(), AGNI_PLUGIN_VERSION );
        
        wp_register_script( 'agni-slider-builder-react-script', AGNI_PLUGIN_URL . 'assets/js/agni-slider/main.js', array( 'wp-i18n' ), AGNI_PLUGIN_VERSION, true );
        wp_localize_script('agni-slider-builder-react-script', 'agni_slider_builder', array(
            'nonce' => wp_create_nonce('wp_rest'),
            'siteurl' => esc_url_raw( site_url() ),
			'resturl' => esc_url_raw( rest_url('agni-slider-builder/v1') ),
            'apipath' => 'wp-json/wp/v2',
            // 'builderurl' => 'wp-admin/admin.php?page=agni_product_builder', //menu_page_url('agni_product_builder', false)
            'content'   => $this->get_existing_sliders_list(),
            'presets'   => $this->get_existing_presets_list(),
            'blockslist'   => $this->get_blocks_list(),
            'fontslist' => $this->get_fonts_list(),
            'assetsurl' => AGNI_PLUGIN_URL . 'assets/img',
            'themeAssetsUrl' => AGNI_PLUGIN_URL . 'assets/img/' //AGNI_FRAMEWORK_IMG_URL
        ));

    }


}

$agni_slider_builder = new Agni_Slider_Builder();