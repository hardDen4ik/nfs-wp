<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class AgniProductBuilder {

    public $product_builder_menu_icon = '';

    public $product_builder_menu_position = null;

    public function __construct() {

        $this->includes();

        add_action( 'agni_insert_product_builder', array( $this, 'product_builder_contents') );
        add_action( 'admin_enqueue_scripts',  array( $this, 'enqueue_scripts' ) );

    }

    public function includes(){
        require_once 'class-product-rest-api.php';

    }

    // agni_product_builder_header_custom
    // agni_product_builder_header_default
    // agni_product_builder_header_presets


    public function product_builder_contents(){

        wp_enqueue_style( 'agni-product-builder-react-style');
        wp_enqueue_script( 'agni-product-builder-react-script');

        wp_enqueue_style('lineicons');

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Agni Product Layout Builder', 'agni-cartify' ); ?></h1>
            <div id="agni-product-builder" class="agni-product-builder">
                <div id="agni-product-builder-contents" class="agni-product-builder-contents"></div>
                <div id="agni-product-builder-panel" class="agni-product-builder-panel"></div>
            </div>
        </div>

        <?php

    }
    
    public function existing_product_layouts(){
        return get_option('agni_product_builder_layouts_list');
    }

    public function existing_product_presets(){
        return get_option('agni_product_builder_layouts_preset');
    }

    public function enqueue_scripts(){

        wp_enqueue_media();

        wp_register_style( 'agni-product-builder-react-style', AGNI_PLUGIN_URL . 'assets/css/agni-product-builder/main.css', array(), AGNI_PLUGIN_VERSION );
        
        wp_register_script( 'agni-product-builder-react-script', AGNI_PLUGIN_URL . 'assets/js/agni-product-builder/main.js', array(), AGNI_PLUGIN_VERSION, true );
        wp_localize_script( 'agni-product-builder-react-script', 'agni_product_builder', array(
            'nonce'     => wp_create_nonce('wp_rest'),
            'siteurl'   => esc_url_raw( site_url() ),
			'resturl'   => esc_url_raw( rest_url('agni-product-builder/v1') ),
            // 'apipath'   => 'wp-json/wp/v2',
            // 'builderurl' => 'wp-admin/admin.php?page=agni_header_builder', //menu_page_url('agni_header_builder', false)
            'content'   => $this->existing_product_layouts(),
            'presets'   => $this->existing_product_presets(),
            'fontslist' => Agni_Cartify_Helper::get_fonts_list(),
            'blockslist' => Agni_Cartify_Helper::get_posttype_posts_list( array( 'post_type' => 'agni_block') ),
            'productslist' => Agni_Cartify_Helper::get_posttype_posts_list( array( 'post_type' => 'product') ),
            'assetsurl' => AGNI_PLUGIN_URL . 'assets/img',
            'themeAssetsUrl' => AGNI_PLUGIN_URL . 'assets/img/' //AGNI_FRAMEWORK_IMG_URL
        ));
    }
    
}

$AgniProductBuilder = new AgniProductBuilder();
