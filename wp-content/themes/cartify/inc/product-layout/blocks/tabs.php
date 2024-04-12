<?php

function cartify_template_product_layout_block_tabs($block){

    $block_settings = $block['settings'];
    $display_title = '';
    $has_toggle = false;

    $display_style = isset( $block_settings['display-style'] ) ? $block_settings['display-style'] : '1';
    $tabs_display_style = isset( $block_settings['tabs-display-style'] ) ? $block_settings['tabs-display-style'] : '1';
    $accordion_state = isset( $block_settings['accordion-state'] ) ? $block_settings['accordion-state'] : '1';
    $accordion_mobile = isset( $block_settings['accordion-on-mobile'] ) ? $block_settings['accordion-on-mobile'] : true;


        if( $accordion_state == '2' || $accordion_state == '4' ){
        $has_toggle = true;
    }

    if( isset($block_settings['heading-show']) ){
        $display_title = cartify_prepare_responsive_values( $block_settings['heading-show'] );
    }

        // $sticky = cartify_prepare_responsive_values( $block_settings['sticky'] );

    $block_args['display-style'] = $display_style;

    add_filter( 'agni_product_tabs_display_shipping_info_title', function($args) use($display_title){
        return $display_title == 'on' ? true : false;
    }, 10, 1 );
    add_filter( 'agni_product_tabs_display_specification_title', function($args) use($display_title){
        return $display_title == 'on' ? true : false;
    }, 10, 1 );

    if( $display_title ){
        add_filter('woocommerce_product_description_heading', '__return_false' );
        add_filter('woocommerce_product_additional_information_heading', '__return_false');
    }


    add_filter( 'agni_product_tabs_style', function($args) use($block_args){
        $args['display-style'] = $block_args['display-style'];

        return $args;
    }, 10 , 1 );


    $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        'style-' . $display_style,
        'tabs-style-' . $tabs_display_style,
        ($display_style == '3') ? 'has-accordion-style-' . $accordion_state : '',
        ( $display_style == '3' && $has_toggle ) ? 'has-toggle' : '',
        !empty($accordion_mobile) ? 'has-accordion-mobile' : '',
        // !empty( $sticky ) ? 'sticky' : '',
        isset($block_settings['className']) ? $block_settings['className'] : ''
    );

    ?>
    <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php 
        woocommerce_output_product_data_tabs(); 
    ?></div>
    <?php
}