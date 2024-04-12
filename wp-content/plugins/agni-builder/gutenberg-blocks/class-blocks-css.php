<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if( !class_exists( 'AgniBuilderBlocksCss' ) ){
    class AgniBuilderBlocksCss {

        // public static $styles;

        public function __construct(){
            // add_action( 'wp', array( $this, 'generate_styles' ) );
            // add_action( 'wp_head', array( $this, 'enqueue_styles' ), 80 );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

            // add_action( 'enqueue_block_editor_assets', array( $this, 'generate_inner_block_styles' ) );

        }

        public function generate_styles(){
            $styles = '';

            $post_id = get_the_id();
            $function_name = 'get_post_meta';

            if( function_exists('is_shop') && is_shop() ){
                $post_id = wc_get_page_id('shop');
            }


            if( get_query_var( 'term' ) ){
                $term = get_queried_object();

                if( isset( $term->term_id ) ){
                    $function_name = 'get_term_meta';
                    $post_id = $term->term_id;
                }
            }

            $post = get_post( $post_id );

            if( !empty($post->post_content) ){
                $blocks = parse_blocks( $post->post_content );

                $styles .= apply_filters( 'agni_gutenberg_blocks_css', $blocks );

                $this->processParsedBlocksLoop( $blocks );

            }

            // product category content block
			if( is_product_category() || is_product_tag() ){
                $content_block_id = esc_attr( ($function_name)($post_id, 'agni_product_cat_content_block', true) );
				
				$cat_block = get_post( $content_block_id );

				if( !empty($cat_block->post_content) ){
					$content_blocks = parse_blocks( $cat_block->post_content );

					$styles .= apply_filters( 'agni_gutenberg_blocks_css', $content_blocks );
				}
			}



            if( get_query_var( 'term' ) ){
                $footer_block_id = esc_attr( ($function_name)($post_id, 'agni_term_footer_block_id', true) );
            }
            else{
                $footer_block_id = esc_attr( ($function_name)($post_id, 'agni_footer_block_id', true) );
            }

            if( $footer_block_id == '' ){
                if( function_exists( 'cartify_get_theme_option' ) ){
                    $footer_block_id = cartify_get_theme_option( 'footer_settings_content_block_choice', '' );
                }
            }


            $footer_block = get_post( $footer_block_id );

            if( !empty($footer_block->post_content) ){
                $footer_blocks = parse_blocks( $footer_block->post_content );

                $styles .= apply_filters( 'agni_gutenberg_blocks_css', $footer_blocks );
            }
            
            
            if( get_query_var( 'term' ) ){
                $header_id = get_term_meta($post_id, 'agni_term_header_id', true);
            }
            else{
                $header_id = get_post_meta($post_id, 'agni_page_header_choice', true);
            }

            $styles .= $this->get_header_block_styles( $header_id );


            if( class_exists('WooCommerce') && is_product() ){
                $layout_id = get_post_meta($post_id, 'agni_product_layout_choice', true);

                $styles .= $this->get_product_layout_block_styles( $layout_id );
            }


            $get_sidebars_widgets = get_option( 'sidebars_widgets' );

            $get_available_widgets = array();
            if( class_exists('WooCommerce') && ( is_shop() || is_product_category() || is_product_tag() || is_tax( 'product_brand' ) ) ){
                if( isset( $get_sidebars_widgets['cartify-sidebar-2'] ) ){
                    $get_available_widgets[] = $get_sidebars_widgets['cartify-sidebar-2'];
                }
                if( isset( $get_sidebars_widgets['cartify-sidebar-4'] ) ){
                    $get_available_widgets[] = $get_sidebars_widgets['cartify-sidebar-4'];
                }
                
            }
            else if( class_exists('WooCommerce') && is_product() ){
                if( isset( $get_sidebars_widgets['cartify-sidebar-3'] ) ){
                    $get_available_widgets[] = $get_sidebars_widgets['cartify-sidebar-3'];
                }
            }

            $get_all_widget_blocks = get_option( 'widget_block' );

            foreach ($get_available_widgets as $key => $active_widgets) {
                foreach ($active_widgets as $id_base => $widget_id) {
                    if( preg_match( "/block-/i", $widget_id ) ){
                        $instance_id = str_replace( 'block-', '', $widget_id );

                        $widget_blocks = parse_blocks( $get_all_widget_blocks[$instance_id]['content'] );

                        $styles .= apply_filters( 'agni_gutenberg_blocks_css', $widget_blocks );
                    }
                }
            }


            return $styles;
        }

        public function get_header_block_styles( $header_id ){
            $styles = '';

            $block_choices = array();
            $headers = get_option('agni_header_builder_headers_list');

            if( !empty( $headers ) ){
                foreach ($headers as $key => $header) {
					if( empty($header_id) ){
						if($header['default']){
							$header_id = $header['id'];
						}
					}
                    if( $header['id'] == $header_id ){
                        foreach($header['content'] as $device){
                            foreach ($device as $key => $row) {
                                foreach ($row['content'] as $key => $column) {
                                    foreach ($column['content'] as $key => $block) {
                                        if( $block['id'] == 'content-block' ){
                                            array_push($block_choices, $block['settings']['content-block-choice']);
                                            // echo $block['settings']['content-block-choice'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
    
            foreach ($block_choices as $key => $header_block_id) {
                $header_block = get_post( $header_block_id );

                if( !empty($header_block->post_content) ){
                    $header_blocks = parse_blocks( $header_block->post_content );

                    $styles .= apply_filters( 'agni_gutenberg_blocks_css', $header_blocks );
                }
                
            }

            return $styles;
        }

        public function get_product_layout_block_styles( $layout_id ){
            $styles = '';

            $block_choices = array();
            $layout_list = get_option('agni_product_builder_layouts_list');

            $layout_id = !empty( $layout_id ) ? $layout_id : '0';

            if( !empty( $layout_list ) ){
                foreach ($layout_list as $key => $layout) {
                    if( $layout['id'] == $layout_id ){
                        // print_r( $layout['content'] as $placement );
                        foreach ( $layout['content'] as $placement ) {
                            // print_r( $placement['content'] );
                            if( !empty( $placement['content'] ) ){
                                foreach ($placement['content'] as $key => $block) {
                                    $block_choices = $this->processParsedProductLayoutBlockLoop( $block, $block_choices );
                                }
                            }
                        }
                    }
                }
            }
    
            foreach ($block_choices as $key => $header_block_id) {
                $header_block = get_post( $header_block_id );

                if( !empty($header_block->post_content) ){
                    $header_blocks = parse_blocks( $header_block->post_content );

                    $styles .= apply_filters( 'agni_gutenberg_blocks_css', $header_blocks );
                }
                
            }

            return $styles;
        }



        public function processParsedBlocksLoop( $blocks ){
            foreach ($blocks as $key => $block) {
                if( $block['blockName'] == 'core/block' ){
                    
                    $reusable_ref_id = $block['attrs']['ref'];
                    $content_block = get_post( $reusable_ref_id );

                    if( !empty( $content_block->post_content ) ){
                        $content_block_blocks = parse_blocks( $content_block->post_content );

                        $content_block_styles = apply_filters( 'agni_gutenberg_blocks_css', $content_block_blocks );

                        wp_register_style( 'agni-builder-blocks-editor-content-block-' . $reusable_ref_id, AGNI_BUILDER_PLUGIN_URL . 'assets/css/custom.css', array(), AGNI_BUILDER_PLUGIN_VERSION );
                        wp_add_inline_style( 'agni-builder-blocks-editor-content-block-' . $reusable_ref_id, $content_block_styles );
                        wp_enqueue_style( 'agni-builder-blocks-editor-content-block-' . $reusable_ref_id );

                    }
                }

                if( !empty( $block['innerBlocks'] ) ){
                    $this->processParsedBlocksLoop( $block['innerBlocks'] );
                }
            }
        }

        public function processParsedProductLayoutBlockLoop( $block, $block_choices ){
            if( $block['slug'] == 'content_block' ){
                array_push($block_choices, $block['settings']['id']);
            }
            else if( $block['slug'] == 'columns' ){
                foreach ($block['content'] as $key => $column) {
                    foreach ($column['content'] as $key => $inner_block) {
                        $block_choices = $this->processParsedProductLayoutBlockLoop( $inner_block, $block_choices );
                    }
                }
            }

            return $block_choices;
        }


        public function enqueue_styles(){

            $styles = $this->generate_styles();
            
            wp_enqueue_style( 'agni-builder-frontend-styles-custom' );
            wp_add_inline_style( 'agni-builder-frontend-styles-custom', $styles );

            $get_all_nav_menus = wp_get_nav_menus();

            foreach ($get_all_nav_menus as $key => $term) {
                $megamenu_styles = '';

                $term_id = $term->term_id;

                $megamenu_items = wp_get_nav_menu_items( $term_id );

                foreach ($megamenu_items as $megamenu_item) {

                    $megamenu_block_id = get_post_meta( $megamenu_item->ID, 'agni_menu_item_block_choice', true );

                    if( !empty( $megamenu_block_id ) ){

                        if( !wp_style_is( 'agni-builder-frontend-megamenu-' . $megamenu_item->ID . '-' . $megamenu_block_id ) ) {
                        
                            $post = get_post( $megamenu_block_id );

                            if( !empty( $post->post_content ) ){
                                $megamenu_blocks = parse_blocks( $post->post_content );

                                $megamenu_styles .= apply_filters( 'agni_gutenberg_blocks_css', $megamenu_blocks );

                                wp_register_style( 'agni-builder-frontend-megamenu-' . $megamenu_item->ID . '-' . $megamenu_block_id, AGNI_BUILDER_PLUGIN_URL . 'assets/css/custom.css', array(), AGNI_BUILDER_PLUGIN_VERSION );
                                wp_add_inline_style( 'agni-builder-frontend-megamenu-' . $megamenu_item->ID . '-' . $megamenu_block_id, $megamenu_styles );
                                wp_enqueue_style( 'agni-builder-frontend-megamenu-' . $megamenu_item->ID . '-' . $megamenu_block_id );

                                $this->processParsedBlocksLoop( $megamenu_blocks );
                            }
                        }
                    }
                }

            }

        }


    }
}

$agni_builder_blocks_css = new AgniBuilderBlocksCss();