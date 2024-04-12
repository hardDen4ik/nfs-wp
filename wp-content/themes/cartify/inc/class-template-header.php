<?php

class Agni_Header{

    public function __construct(){

        $this->includes();

        add_action( 'agni_header', array( $this, 'header_init'), 10, 2 );

                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

    }

    public function includes(){

        require_once AGNI_TEMPLATE_DIR . '/header/class-header-builder-block.php';
        require_once AGNI_TEMPLATE_DIR . '/header/header-builder-block-processor.php';
        require_once AGNI_TEMPLATE_DIR . '/header/header-builder-css-processor.php';

    }

    public function header_init( $header_source, $header_choice ){

        // if( empty( $header_id ) ){
        //     return;
        // }

        // $header_choice = '';// get value from theme options
        // // $header_id = '';
        ?>
        <header id="masthead" class="site-header">
            <?php if($header_source != '3'){ 
                if ($header_source == '2') {
                    do_action( 'agni_header_content_block', $header_choice );
                } else {
                    do_action( 'agni_header_builder_block', $header_choice );
                } 
            } ?>
        </header>
    <?php }

    public function get_existing_headers(){
        return (array)get_option('agni_header_builder_headers_list');
    }

    public function enqueue_scripts(){

        $headers = $this->get_existing_headers();

        foreach ($headers as $key => $header) {
            $header_id = $header['id'];

            $styles = apply_filters( 'agni_header_css', $header );

            // print_r( $styles );

            // register styles
            wp_register_style( 'cartify-header-custom-'.$header_id, AGNI_FRAMEWORK_CSS_URL . '/custom.css' );
            wp_add_inline_style( 'cartify-header-custom-'.$header_id, $styles );

        }


        $post_id = get_the_id();

        if( function_exists('is_shop') && is_shop() ){
            $post_id = wc_get_page_id('shop');
        }

        $header_id = get_post_meta($post_id, 'agni_page_header_choice', true);

        if( get_query_var( 'term' ) ){
            $term = get_queried_object();

            if( isset( $term->term_id ) ){

                $post_id = $term->term_id;

                $header_id = get_term_meta($post_id, 'agni_term_header_id', true);
            }
        }

        if( empty( $header_id ) ){

            foreach ($headers as $key => $header) {
                if( isset( $header['default'] ) && $header['default'] ){
                    $header_id = $header['id'];
                }
                else if( $header['id'] == '0' ){
                    $header_id = $header['id'];
                }
            }
        }

        wp_enqueue_style( 'cartify-header-custom-' . $header_id );


    }

}

$agni_header = new Agni_Header();
