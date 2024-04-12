<?php

function cartify_template_product_layout_block_related($block){

    $block_settings = $block['settings'];

        $qty_show = '';

    $posts_per_page = isset( $block_settings['posts_per_page'] ) ? $block_settings['posts_per_page'] : '5';
	$columns = isset( $block_settings['columns'] ) ? $block_settings['columns'] : '5';
	$display_style = isset( $block_settings['display-style'] ) ? $block_settings['display-style'] : '1';
	$inline_mobile = isset( $block_settings['inline-on-mobile'] ) ? $block_settings['inline-on-mobile'] : true;

        if( isset($block_settings['qty-show']) ){
	    $qty_show = cartify_prepare_responsive_values( $block_settings['qty-show'] ); 
    }
	$qty_choice = isset( $block_settings['qty-choice'] ) ? $block_settings['qty-choice'] : '2';

    $block_args = array();

    $block_args['posts_per_page'] = $posts_per_page;
    // $block_args['columns'] = $columns;

    add_filter( 'agni_products_archives_display_style', function() use($display_style){
        return $display_style;
    }, 99, 1 );

    add_filter( 'agni_products_archives_qty_show', function() use($qty_show){
        return $qty_show;
    }, 99, 1 );

    add_filter( 'agni_products_archives_qty_choice', function() use($qty_choice){
        return $qty_choice;
    }, 99, 1 );

    add_filter( 'woocommerce_output_related_products_args', function($args) use ($block_args){
        $args['posts_per_page'] = $block_args['posts_per_page']; // 4 related products
        // $args['columns'] = $block_args['columns']; // arranged in 2 columns

        return $args;
    }, 99, 1 );

    $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        !empty($inline_mobile) ? 'has-inline-products' : '',
        isset($block_settings['className']) ? $block_settings['className'] : ''
    );

    ?>
    <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php 
        woocommerce_output_related_products(); 
    ?></div>
    <?php

        add_filter( 'agni_products_archives_display_style', function(){return '1'; }, 99 );


}
