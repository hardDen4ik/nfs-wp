<?php

function cartify_template_product_layout_block_stock($block){

    $block_settings = $block['settings'];

    $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        isset($block_settings['className']) ? $block_settings['className'] : ''
    );


    // if( isset( $block_settings['availability-bar-show'] ) ){
    //     foreach ($block_settings['availability-bar-show'] as $device => $value) {
    //         if( $value ){
    //             $block_classes[] = "has-availability-bar-{$device}";
    //         }
    //     }
    // }


    ?>
    <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php 
        cartify_woocommerce_template_single_stock_html(); 
    ?></div>
    <?php
}