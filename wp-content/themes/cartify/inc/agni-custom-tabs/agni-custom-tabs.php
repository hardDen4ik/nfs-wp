<?php  

require_once AGNI_TEMPLATE_DIR . '/agni-custom-tabs/admin-agni-custom-tabs.php';

function cartify_woocommerce_template_custom_tabs($tabs){
    global $product;

    $specification_title = get_post_meta( $product->get_id(), 'agni_product_data_tab_specification_title', true );
    $shipping_info_title = get_post_meta( $product->get_id(), 'agni_product_data_tab_shipping_info_title', true );

    ?>

        <?php 

        if( $specification_title != '' ){
            // Adds the new tab
            $tabs['spec'] = array(
                'title'     => esc_html($specification_title),
                'priority'  => 10,
                'callback'  => 'cartify_woocommerce_spec_tab_contents'
            );
        }

        if( $shipping_info_title != '' ){
            $tabs['shipping'] = array(
                'title'     => esc_html($shipping_info_title),
                'priority'  => 40,
                'callback'  => 'cartify_woocommerce_shipping_tab_contents'
            );
        }
    ?>

    <?php
    return $tabs;
}

function cartify_woocommerce_spec_tab_contents(){
    global $product;

        $specification_title = get_post_meta( $product->get_id(), 'agni_product_data_tab_specification_title', true );
    $specification_contents = get_post_meta( $product->get_id(), 'agni_product_data_tab_specification_table_data', true );

    $display_specification_title = apply_filters( 'agni_product_tabs_display_specification_title' , false );

    ?>
    <div class="agni-product-tab-specification">
        <?php
		if ( $display_specification_title ) {
			?>
            <h2><?php echo esc_html($specification_title); ?></h2>
        <?php } ?>
        <table class="agni-product-tab-specification__table">
            <tbody>
            <?php 
            if($specification_contents){
                foreach ($specification_contents as $specification_data) {
                    ?>
                    <tr>
                        <th><?php echo esc_html($specification_data['key']); ?></th>
                        <td><?php echo esc_html($specification_data['value']); ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>

        <?php
}

function cartify_woocommerce_shipping_tab_contents(){
    global $product;

        $shipping_info_title = get_post_meta( $product->get_id(), 'agni_product_data_tab_shipping_info_title', true );
    $shipping_info_contents = get_post_meta( $product->get_id(), 'agni_product_data_tab_shipping_info_desc', true );

    $display_shipping_info_title = apply_filters( 'agni_product_tabs_display_shipping_info_title' , false );

    ?>
    <div class="agni-product-tab-shipping">
        <?php
		if ( $display_shipping_info_title ) {
			?>
            <h2><?php echo esc_html($shipping_info_title); ?></h2>
        <?php } ?>
        <div class="agni-product-tab-shipping__contents">
            <?php echo wp_kses_post($shipping_info_contents); ?>
        </div>
    </div>

    <?php
}


function cartify_woocommerce_template_single_features(){
    global $product;

    $additional_info_features = get_post_meta($product->get_id(), 'agni_product_data_features', true);

    if( empty($additional_info_features) ){
        return;
    }
    ?>
    <div class="agni-product-features">
        <ul class="agni-product-features-list">
        <?php foreach ( $additional_info_features as $feature ) {
            ?>
            <li class="agni-product-features-list-item">
                <?php if( !empty( $feature['icon'] ) ){ ?>
                    <span class="agni-product-features-list-item__icon"><?php echo wp_kses( cartify_prepare_icon( $feature['icon'] ), array_merge_recursive( wp_kses_allowed_html( 'img' ), array( 'i' => array( 'class' => array() ) ) ) ); ?></span>
                <?php } ?>
                <span class="agni-product-features-list-item__text"><?php echo wp_kses( $feature['text'], array( 'br' => array() ) ); ?></span>
            </li>
            <?php
        } ?>
        </ul>
    </div>
    <?php
}


function cartify_woocommerce_template_single_offers(){

    global $product;

    $additional_info_offer_title = get_post_meta( $product->get_id(), 'agni_product_data_offer_title', true );
    $additional_info_offer_texts = get_post_meta( $product->get_id(), 'agni_product_data_offer_text', true );

    $additional_info_offer_texts = !empty( $additional_info_offer_texts ) ?  array_filter( $additional_info_offer_texts ) : $additional_info_offer_texts;

    if( empty( $additional_info_offer_texts ) ){
        return;
    }
    ?>
    <div class="agni-product-offers">
        <h6 class="agni-product-offers-title"><?php echo esc_html($additional_info_offer_title); ?></h6>
        <ul class="agni-product-offers-list">
            <?php foreach ($additional_info_offer_texts as $offer_text) { ?>
                <li class="agni-product-offers-list-item">
                    <span class="agni-product-offers-list-item__icon"><i class="lni lni-tag"></i></span>
                    <span class="agni-product-offers-list-item__text"><?php echo esc_html($offer_text); ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>
    <?php
}

function cartify_woocommerce_template_single_shipping_info(){

    global $product;

    $additional_info_shipping_info_title = get_post_meta( $product->get_id(), 'agni_product_data_shipping_info_title', true );
    $additional_info_shipping_info_desc = get_post_meta( $product->get_id(), 'agni_product_data_shipping_info_desc', true );
    $additional_info_shipping_info_link_text = get_post_meta( $product->get_id(), 'agni_product_data_shipping_info_link_text', true );

    if( empty( $additional_info_shipping_info_title ) && empty($additional_info_shipping_info_desc) && empty($additional_info_shipping_info_link_text) ){
        return;
    }


    if( !empty($additional_info_shipping_info_link_text) ){
        wp_enqueue_script('cartify-custom-tabs');
    }

            ?>
    <div class="agni-product-shipping-info">
        <h6 class="agni-product-shipping-info-title"><?php echo esc_html($additional_info_shipping_info_title); ?></h6>
        <div class="agni-product-shipping-info-description"><?php echo esc_html($additional_info_shipping_info_desc); ?></div>
        <a class="agni-product-shipping-info-link"><?php echo esc_html($additional_info_shipping_info_link_text); ?></a>
    </div>
    <?php
}

function cartify_woocommerce_product_tab_shipping_info_popup(){

    $product_id = get_the_id();

    $additional_info_shipping_info_link_text = get_post_meta( $product_id, 'agni_product_data_shipping_info_link_text', true );
    $additional_info_shipping_info_popup = get_post_meta( $product_id, 'agni_product_data_shipping_info_popup', true );

    if( empty($additional_info_shipping_info_link_text) ){
        return;
    }


    ?>
    <div class="agni-product-shipping-info-popup">
        <div class="agni-product-shipping-info-popup__container">
            <div class="agni-product-shipping-info-popup__overlay"></div>
            <div class="agni-product-shipping-info-popup__contents">
                <?php echo wp_kses_post($additional_info_shipping_info_popup); ?>
                <span class="agni-product-shipping-info-popup__close"><i class="lni lni-close"></i></span>
            </div>
        </div>
    </div>
    <?php

}

function cartify_custom_tabs_scripts(){

    // Registering JS for compare
    wp_register_script('cartify-custom-tabs', AGNI_FRAMEWORK_JS_URL . '/agni-custom-tabs/agni-custom-tabs.js', array('jquery'), wp_get_theme()->get('Version'), true);
}


add_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_template_single_features', 15 );
add_action( 'agni_woocommerce_single_product_additional_info', 'cartify_woocommerce_template_single_offers' );
add_action( 'agni_woocommerce_single_product_additional_info', 'cartify_woocommerce_template_single_shipping_info', 15 );
add_action( 'agni_before_footer', 'cartify_woocommerce_product_tab_shipping_info_popup', 10, 1 );

add_action( 'wp_enqueue_scripts', 'cartify_custom_tabs_scripts' );

add_filter( 'woocommerce_product_tabs', 'cartify_woocommerce_template_custom_tabs' );