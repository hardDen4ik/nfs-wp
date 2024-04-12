<?php

function cartify_template_product_layout_block_images($block){

    $block_settings = $block['settings'];

    $hoverzoom_array = isset( $block_settings['easyzoom'] ) ? $block_settings['easyzoom'] : array();
    $lightbox_array = isset( $block_settings['lightbox'] ) ? $block_settings['lightbox'] : array();

    $hoverzoom = cartify_prepare_responsive_values($hoverzoom_array, true);
    $lightbox = cartify_prepare_responsive_values($lightbox_array, true);

    $display_style = isset( $block_settings['display-style'] ) ? $block_settings['display-style'] : '1';
    $carousel_options = isset( $block_settings['carousel-options'] ) ? $block_settings['carousel-options'] : '';
    $images_size = isset( $block_settings['images-size'] ) ? $block_settings['images-size'] : '';
    $thumbnails_mobile  = isset( $block_settings['thumbnails-on-mobile'] ) ? $block_settings['thumbnails-on-mobile'] : true;
    $thumbnails_placement  = isset( $block_settings['thumbnails-placement'] ) ? $block_settings['thumbnails-placement'] : '';

        if(!(count(array_unique($hoverzoom)) === 1 && !current($hoverzoom))) {
        wp_enqueue_script('easyzoom');
    }

    if(!(count(array_unique($lightbox)) === 1 && !current($lightbox))) {
        wp_enqueue_style('cartify-photoswipe-style');
        wp_enqueue_script('cartify-photoswipe-script');
    }

        if( !empty($images_size) ){
        add_filter( 'woocommerce_gallery_image_size', function() use($images_size){
            return "cartify_single_{$images_size}";
        });
    }

    $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        'product-style-' . $display_style,
        ( $display_style == '2' ) ? 'has-vertical-thumbnails' : '',
        ( $display_style == '1' || $display_style == '2' ) ? 'has-slick' : '',
        ( $display_style == '1' || $display_style == '2' || $display_style == '3' ) ? 'has-thumbnails' : '',
        !$thumbnails_mobile ? 'no-thumbnails-mobile': '',
        !empty($thumbnails_placement) ? 'has-thumbnails-' . $thumbnails_placement : '',
        isset($block_settings['className']) ? $block_settings['className'] : ''
    );

    if( !empty($hoverzoom) ){
        foreach ($hoverzoom as $key => $value) {
            if( $value ){
                $block_classes[] = 'has-hoverzoom-' . $key;
            }
        }
    }

    if( !empty($lightbox) ){
        foreach ($lightbox as $key => $value) {
            if( $value ){
                $block_classes[] = 'has-lightbox-' . $key;
            }
        }
    }

    if( $display_style == '1' || $display_style == '2' ){
        wp_enqueue_style('slick');
        wp_enqueue_script('slick');
        ?><div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>" data-slick="<?php echo esc_attr( cartify_prepare_slick_options( $carousel_options ) ); ?>"><?php
    }
    else{
        ?><div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php
    }
    ?><?php 
        woocommerce_show_product_images();  
    ?></div>
    <?php
}