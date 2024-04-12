<?php

function cartify_template_product_layout_block_recently_viewed($block){

    $block_settings = $block['settings'];

    $posts_per_page = isset( $block_settings['posts_per_page'] ) ? $block_settings['posts_per_page'] : '10';
	$columns = isset( $block_settings['columns'] ) ? $block_settings['columns'] : '10';
	$inline_mobile = isset( $block_settings['inline-on-mobile'] ) ? $block_settings['inline-on-mobile'] : true;

    $block_args = array();

    $block_args['posts_per_page'] = $posts_per_page;
    // $block_args['columns'] = $columns;


    add_filter( 'agni_recently_viewed_products_args', function ($args) use ($block_args){
        $args['posts_per_page'] = $block_args['posts_per_page']; // 4 related products
        // $args['columns'] = $block_args['columns']; // arranged in 2 columns

        return $args;
    }, 10, 1 );

    $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        !empty($inline_mobile) ? 'has-inline-products' : '',
        isset($block_settings['className']) ? $block_settings['className'] : ''
    );

    ?>
    <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php 
        cartify_recently_viewed_products(); 
    ?></div>
    <?php
}