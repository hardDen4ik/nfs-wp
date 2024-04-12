<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Agni_Header_Builder {

    public $header_builder_menu_icon = '';

    public $header_builder_menu_position = null;


    public function __construct(){

        $this->includes();

        add_action( 'agni_insert_header_builder', array( $this, 'header_builder_contents' ) );
        add_action( 'admin_enqueue_scripts',  array( $this, 'enqueue_scripts' ) );
    }

    public function includes(){
        require_once 'class-header-rest-api.php';
    }


    public function header_builder_contents(){

        wp_enqueue_style( 'agni-header-builder-react-style');
        wp_enqueue_script( 'agni-header-builder-react-script');

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Agni Header Builder', 'agni-cartify' ); ?></h1>
            <div id="agni-header-builder" class="agni-header-builder">
                <div id="agni-header-builder-contents" class="agni-header-builder-contents"></div>
                <div id="agni-header-builder-panel" class="agni-header-builder-panel"></div>
            </div>
        </div>
        <?php

    }

    public function existing_header_layouts(){
        return get_option('agni_header_builder_headers_list');
    }


    public function existing_header_presets(){
        $header_presets = array();

        if( class_exists( 'Agni_Header_Block' ) ){
            $header_presets = Agni_Header_Block::get_header_json_file( 'header-presets' );
        }
        return $header_presets;
    }

    public function agni_registered_menus( $empty = false ) {
        $menu_list = array();
        // if( $empty == true ){
        //     $menu_list = array("" => "Inherit");
        // }
        $menus = get_terms('nav_menu', array( 'hide_empty' => true ));
        foreach($menus as $menu){
          $menu_list[ $menu->slug ] = $menu->name;
        } 
        return $menu_list;
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

        
        wp_register_style( 'agni-icons', AGNI_PLUGIN_URL . 'assets/css/agni-header-builder/agni-icons.min.css', array(), AGNI_PLUGIN_VERSION );
        wp_register_style( 'agni-header-builder-react-style', AGNI_PLUGIN_URL . 'assets/css/agni-header-builder/main.css', array(), AGNI_PLUGIN_VERSION );

        wp_enqueue_style( 'agni-icons' );
        
        wp_register_script( 'agni-header-builder-react-script', AGNI_PLUGIN_URL . 'assets/js/agni-header-builder/main.js', array(), AGNI_PLUGIN_VERSION, true );
        wp_localize_script('agni-header-builder-react-script', 'agni_header_builder', array(
            'nonce'     => wp_create_nonce('wp_rest'),
            'siteurl'   => esc_url_raw( site_url() ),
			'resturl'   => esc_url_raw( rest_url('agni-header-builder/v1') ),
            // 'apipath'   => 'wp-json/wp/v2',
            // 'builderurl' => 'wp-admin/admin.php?page=agni_header_builder', //menu_page_url('agni_header_builder', false)
            'content'   => $this->existing_header_layouts(),
            'presets'   => $this->existing_header_presets(),
            'menulist'  => $this->agni_registered_menus(),
            'fontslist' => $this->get_fonts_list(),
            'blockslist' => Agni_Cartify_Helper::get_posttype_posts_list( array( 'post_type' => 'agni_block') ),
            'assetsurl' => AGNI_PLUGIN_URL . 'assets/img/',
            'themeAssetsUrl' => AGNI_PLUGIN_URL . 'assets/img/' //AGNI_FRAMEWORK_IMG_URL
        ));
    }
    
}

$agni_header_builder = new Agni_Header_Builder();
