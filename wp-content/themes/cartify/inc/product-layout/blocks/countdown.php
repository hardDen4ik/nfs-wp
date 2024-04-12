<?php

function cartify_template_product_layout_block_countdown($block){

    $block_settings = $block['settings'];

        $display_style = isset( $block_settings['display-style'] ) ? $block_settings['display-style'] : '1';

        add_filter( 'cartify_woocommerce_single_product_sale_countdown_display_style', function($args) use($display_style){
        return $display_style;
    }, 10, 1 );


    $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        'style-' .$display_style,
        isset($block_settings['className']) ? $block_settings['className'] : ''
    );

    ?>
    <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php 
        cartify_woocommerce_single_product_sale_countdown(); 
    ?></div>
    <?php
}