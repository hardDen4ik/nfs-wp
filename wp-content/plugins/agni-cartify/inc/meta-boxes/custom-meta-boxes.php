<?php 

class AgniCustomMetaboxes{
    public function __construct(){
        $this->includes();

        add_action( 'init', array( $this, 'page_meta_options') );

        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_meta_scripts' ) );

        
    }

    public function includes(){

        require_once 'class-meta-boxes-terms.php';
        require_once 'class-meta-boxes-wishlist.php';
        require_once 'class-meta-boxes-products.php';
        require_once 'class-meta-boxes-product-categories.php';
        require_once 'class-meta-boxes-product-tags.php';
        require_once 'class-meta-boxes-product-brands.php';

    }
    public function page_meta_options(){

        // // wp_set_object_terms( '3969', array( 'featured' ), 'product_visibility', true );
        // wp_remove_object_terms( '3969', array( 'featured' ), 'product_visibility', true );
        // // wp_set_object_terms( '3940', array( 'exclude-from-search' ), 'product_visibility', true );
        // wp_remove_object_terms( '3940', array( 'exclude-from-search' ), 'product_visibility', true );
        // // wp_set_object_terms( '3940', array( 'exclude-from-catalog' ), 'product_visibility', true );
        // wp_remove_object_terms( '3940', array( 'exclude-from-catalog' ), 'product_visibility', true );
        
        register_meta('post', 'agni_page_title_hide', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
    
        register_meta('post', 'agni_page_title_align', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
    
        register_meta('post', 'agni_page_margin_remove', array(
            'type'        => 'string', 
            'single'    => true,
            'show_in_rest'    => true,
        ));
        register_meta('post', 'agni_page_bg_color', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
        register_meta('post', 'agni_page_bg_gradient', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
        register_meta('post', 'agni_page_header_source', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
        register_meta('post', 'agni_page_header_choice', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
        register_meta('post', 'agni_footer_block_id', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
        register_meta('post', 'agni_page_sidebar_choice', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
        register_meta('post', 'agni_slider_id', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
        register_meta('post', 'agni_product_layout_choice', array(
            'type'        => 'string',
            'single'    => true,
            'show_in_rest'    => true,
        ));
    }


    public static function prepare_headers_list(){
        $headers_list = array();

        $get_headers = get_option( 'agni_header_builder_headers_list' );

        $headers_list[''] = 'Inherit';
        if( !empty( $get_headers ) ){
            foreach ($get_headers as $key => $header) {
                $headers_list[$header['id']] = $header['title'];
            }
        }

        return $headers_list;
    }

    public static function prepare_sliders_list(){
        $sliders_list = array();
        
        $get_sliders = get_option( 'agni_slider_builder_sliders' );

        $sliders_list[''] = 'No slider chosen';
        if( !empty( $get_sliders ) ){
            foreach ($get_sliders as $key => $slider) {
                $sliders_list[$slider['id']] = $slider['title'];
            }
        }

        return $sliders_list;

    }


    public function enqueue_meta_scripts(){

        if( !post_type_supports( get_post_type(), 'custom-fields' ) ){
            return;
        }

        // Enqueue scripts
        wp_enqueue_script(
            'agni-cartify-page-meta-options',
            AGNI_PLUGIN_URL . 'assets/js/custom-meta-options.js',
            array('lodash','wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post'),
            ''
        );
        wp_set_script_translations( 'agni-cartify-page-meta-options', 'agni-cartify' );

        wp_enqueue_script(
            'agni-cartify-page-meta-options-catalog',
            AGNI_PLUGIN_URL . 'assets/js/custom-meta-options-catalog.js',
            array('lodash','wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-core-data', 'wp-plugins', 'wp-edit-post'),
            ''
        );
        wp_set_script_translations( 'agni-cartify-page-meta-options-catalog', 'agni-cartify' );
        // wp_localize_script( 'agni-cartify-page-meta-options', 'agni_cartify_meta', array(

        // ) )
    }

}

$AgniCustomMetaBoxes = new AgniCustomMetaboxes();
