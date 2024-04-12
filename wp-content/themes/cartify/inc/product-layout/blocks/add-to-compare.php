<?php

function cartify_template_product_layout_block_add_to_compare($block){

    $block_settings = $block['settings'];

    $display_style = isset( $block_settings['display-style'] ) ? $block_settings['display-style'] : '1';

    $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        'style-' . $display_style,
        isset($block_settings['className']) ? $block_settings['className'] : ''
    );

    ?>
    <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php 
        cartify_compare_add_to_compare_button(); 
    ?></div>
    <?php
}