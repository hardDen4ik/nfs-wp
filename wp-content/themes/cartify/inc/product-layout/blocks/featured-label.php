<?php

function cartify_template_product_layout_block_featured_label($block){

    $block_settings = $block['settings'];

    $display_style = isset( $block_settings['display-style'] ) ? $block_settings['display-style'] : '1';
    $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';

     $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        'display-style-' . $display_style,
        (!empty( $border_width ) && $border_width != '0' ) ? 'has-border' : '',
        isset($block_settings['className']) ? $block_settings['className'] : ''
    );

    ?>
    <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php 
        cartify_woocommerce_single_product_featured(); 
    ?></div>
    <?php
}