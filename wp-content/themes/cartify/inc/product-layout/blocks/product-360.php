<?php

function cartify_template_product_layout_block_product_360($block){

    $block_settings = $block['settings'];

    $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        isset($block_settings['className']) ? $block_settings['className'] : ''
    );

    ?>
    <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php 
        cartify_woocommerce_single_product_360_image(); 
    ?></div>
    <?php
}