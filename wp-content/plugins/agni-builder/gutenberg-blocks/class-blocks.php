<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if( !class_exists('AgniBuilderBlocks') ){
    class AgniBuilderBlocks{

        // static $font_icons_list = array(
        //     'font_awesome_brands' => self::$font_awesome_brands,
        //     'font_awesome_solid' => self::$font_awesome_solid,
        //     'font_awesome_regular' => self::$font_awesome_regular,
        //     'line_icons' => self::$line_icons,
        // );
    


        public function __construct(){

            add_action( 'enqueue_block_editor_assets', array($this, 'enqueueBlockEditorAssets'), 99 );

            add_filter( 'block_categories_all', array( $this, 'insertBlockCategory'), 10, 2 );

            // add_filter( 'block_editor_settings_all', array( $this, 'removeBlockStyles' ), 10, 2 );
            
        }


        // function removeBlockStyles($editor_settings, $post) {
        //     unset($editor_settings['styles'][0]);

        //     return $editor_settings;
        // }

        function insertBlockCategory( $categories ) {
            $category_slugs = wp_list_pluck( $categories, 'slug' );

            return in_array( 'agni-blocks', $category_slugs, true ) ? $categories : array_merge(
                $categories,
                array(
                    array(
                        'slug' => 'agni-blocks',
                        'title' => esc_html__( 'Agni Blocks', 'agni-builder' ),
                    ),
                )
            );
        }


        public function enqueueBlockEditorAssets(){
            $theme_icons = array();
            $decor_icons = array();

            $instagram_token = $google_map_api = '';

            if( class_exists( 'Cartify_SVG_Icons' ) ){
                $theme_icons = Cartify_SVG_Icons::$common_icons;
                $decor_icons = Cartify_SVG_Icons::$decor_icons;
            }



            if( function_exists( 'cartify_get_theme_option' ) ){
                $instagram_token = esc_attr( cartify_get_theme_option('api_settings_instagram_token', '') );
                $google_map_api = esc_attr( cartify_get_theme_option('api_settings_google_map_api', '') );
            }
            
            $gutenberg_scripts_dir = AGNI_BUILDER_PLUGIN_URL . 'assets/js/gutenberg-blocks';

            $script_asset_dir = $gutenberg_scripts_dir . '/main.assets.php';

            if( ini_get("allow_url_include") && file_exists( $script_asset_dir ) ){
                $script_asset = include_once( $script_asset_dir ); 
                
                $script_dependencies = $script_asset['dependencies'];
                $script_version = $script_asset['version'];
            }
            else{
                $script_dependencies = array( 'lodash', 'react', 'wp-block-editor', 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-keycodes', 'wp-primitives' );
                $script_version = AGNI_BUILDER_PLUGIN_VERSION;
            }

            // $get_registered_image_sizes = wp_get_registered_image_subsizes();

            // Registering CSS
            wp_register_style( 'slick', AGNI_BUILDER_PLUGIN_URL . 'assets/css/vendor/slick.min.css', array(), AGNI_BUILDER_PLUGIN_VERSION );
            wp_register_style( 'agni-builder-animista', AGNI_BUILDER_PLUGIN_URL . 'assets/css/vendor/animista.min.css', array(), AGNI_BUILDER_PLUGIN_VERSION );
            
            wp_enqueue_style( 'agni-builder-blocks-editor-styles', AGNI_BUILDER_PLUGIN_URL . 'assets/css/gutenberg-blocks/main.css', array(), AGNI_BUILDER_PLUGIN_VERSION );

            // Registering Script
            wp_register_script( 'slick', AGNI_BUILDER_PLUGIN_URL . 'assets/js/vendor/slick.min.js', array( 'jquery' ), AGNI_BUILDER_PLUGIN_VERSION, true );

            wp_enqueue_script(
                'agni-builder-blocks-editor-script', 
                $gutenberg_scripts_dir . '/main.js', 
                $script_dependencies,
                $script_version, 
                true
            );
            wp_set_script_translations( 'agni-builder-blocks-editor-script', 'agni-builder' );
            wp_localize_script( 'agni-builder-blocks-editor-script', 'agni_builder_blocks', array(
                'nonce'         => wp_create_nonce('wp_rest'),
                'siteurl'       => esc_url_raw( site_url() ),
                'resturl'       => esc_url_raw( rest_url() ),
                'plugindir'     => AGNI_BUILDER_PLUGIN_URL,
                'uploaddir'     => wp_get_upload_dir(),
                'themeimgurl' =>  AGNI_BUILDER_PLUGIN_URL . 'assets/img',
                'themeiconsurl' =>  AGNI_BUILDER_PLUGIN_URL . 'assets/icons', //AGNI_FRAMEWORK_ICONS_URL,
                'theme_icons'   => $theme_icons, //class_exists( 'Cartify_SVG_Icons' ) ? Cartify_SVG_Icons::$common_icons
                'decor_icons'   => $decor_icons,
                'timezone'      => wp_timezone_string(),
                'instagram_token' => $instagram_token,
                'google_map_api' => $google_map_api,
                'google_map_marker' => AGNI_BUILDER_PLUGIN_URL . 'assets/img/marker.png',
                'wc_placeholder_img' => get_option( 'woocommerce_placeholder_image', 0 ),
                'breakpoints' => array(
                    'desktop' => '1440',
                    'laptop' => '1024',
                    'tab' => '667',
                    'mobile' => ''
                ),
                'global_color_palette' => array(
                    array( 'name' => 'Accent', 'color' => '#FCDFB0' ),
                    array( 'name' => 'Text Primary', 'color' => '#222' ),
                    // array( 'name' => 'Text Secondary', 'color' => '#777' ),
                    // array( 'name' => 'Text Light', 'color' => '#f0f0f0' ),
                    array( 'name' => 'Border Light', 'color' => '#ccc' ),
                    array( 'name' => 'Border Lighter', 'color' => '#ddd' ),
                    array( 'name' => 'Background Light', 'color' => '#eee' ),
                    array( 'name' => 'Background Lighter', 'color' => '#f5f5f5' ),
                    array( 'name' => 'White', 'color' => '#fff' ),
                    // array( 'name' => 'borderDark', 'color' => '#003' ),
                    // array( 'name' => 'borderDarker', 'color' => '#00f' ),
                    // array( 'name' => 'borderAccent', 'color' => '#00f' ),
                    // array( 'name' => 'backgroundDark', 'color' => '#00f' ),
                    // array( 'name' => 'backgroundDarker', 'color' => '#00f' ),
                    // array( 'name' => 'backgroundAccent', 'color' => '#00f' ),
                    // array( 'name' => 'accentLight', 'color' => '#FCDFB0' ),
                ),
                // 'image_sizes'   => $get_registered_image_sizes,
                // 'fonticonslist' => self::$font_list,
                // 'apipath' => 'wp-json/wp/v2',
                // 'posturl' => 'wp/v2/' . $current_post_type_rest_base . '/' . $current_post_id,
                // 'postcontent' => $current_post_content,
                // 'postcontentarray' => parse_blocks($current_post_content),
                // 'agni_builder' => $this->builder_mode
            ));

            // if( !empty( $google_map_api ) ){
            //     wp_register_script( 'googleapi', '//maps.google.com/maps/api/js?key=' . $google_map_api );
            // }
        }
        
    }
}

$agni_builder_blocks = new AgniBuilderBlocks();