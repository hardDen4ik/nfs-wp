<?php

function cartify_template_product_layout_block_add_to_cart($block){

    $block_settings = $block['settings'];

    $display_style = isset( $block_settings['display-style'] ) ? $block_settings['display-style'] : '1';
    $qty_choice = isset( $block_settings['quantity-choice'] ) ? $block_settings['quantity-choice'] : '';
    $sticky_add_to_cart = isset( $block_settings['sticky-add-to-cart'] ) ? $block_settings['sticky-add-to-cart'] : '';

        $variations_display_style = isset( $block_settings['variations-display-style'] ) ? $block_settings['variations-display-style'] : '1';
    $quantity_label_inline = isset( $block_settings['quantity-label-inline'] ) ? $block_settings['quantity-label-inline'] : '';
    $bn_show = isset( $block_settings['bn-show'] ) ? $block_settings['bn-show'] : true;

    if( $bn_show ){
        add_action( 'woocommerce_after_add_to_cart_button', 'cartify_woocommerce_single_additional_button_buynow', 10 );
    }

    if( !empty( $qty_choice ) ){
        add_filter( 'agni_woocommerce_qty_choice', function() use($qty_choice){
            return $qty_choice;
        } );
    }

    $redirect_after_add_to_cart = get_option( 'woocommerce_cart_redirect_after_add' );

    $block_classes = array(
        "agni-product-layout-block",
        "agni-product-layout-block-" . $block['slug'],
        'style-' . $display_style,
        'has-variation-style-' . $variations_display_style,
        !empty( $quantity_label_inline ) ? 'has-qty-inline' : '',
        ($redirect_after_add_to_cart == 'yes') ? 'has-cart-redirect' : '',
        isset( $block_settings['className'] ) ? $block_settings['className'] : ''

            );

    ?>
    <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>"><?php 
        woocommerce_template_single_add_to_cart(); 

        if( !empty( $sticky_add_to_cart ) ){
            do_action( 'agni_woocommerce_single_product_add_to_cart_sticky' );
        }
    ?></div>
    <?php
}


// Agni Single Add to cart Sticky
function cartify_template_product_layout_block_add_to_cart_sticky(){

		$product_id = get_the_id();

	$product = wc_get_product( $product_id );
	?>

	<div class="agni-add-to-cart-sticky">
		<div class="agni-add-to-cart-sticky-container container">
			<div class="agni-add-to-cart-sticky-content page-scroll">
				<div class="agni-add-to-cart-sticky-thumbnail">
					<?php echo get_the_post_thumbnail( $product_id, 'thumb' ); ?>
				</div>
				<div class="agni-add-to-cart-sticky-details">
					<h4 itemprop="name" class="product_title"><?php echo wp_kses_post( $product->get_name() ); ?></h4>
				</div>
				<?php if( $product->is_in_stock() ){
                    if( $product->get_type() == 'grouped' ){?>
                        <a class="add-to-cart-sticky-btn btn-product-<?php echo esc_attr( $product->get_type() ); ?>" href="#<?php echo 'product-'.$product_id; ?>"><?php echo esc_html__( 'Config Product', 'cartify' ); ?></a>
                    <?php }
                    else{
                        woocommerce_template_single_add_to_cart(); 
                    }
                    // if( $product->get_type() == 'simple' || $product->get_type() == 'external' ){
                        // do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' ); 
                    /* }
                    else{ ?>
                        <a class="add-to-cart-sticky-btn btn-product-<?php echo esc_attr( $product->get_type() ); ?>" href="#<?php echo 'product-'.$product_id; ?>"><?php echo esc_html__( 'Config Product', 'cartify' ); ?></a>
                    <?php } */
                }
                else{ ?>
                    <span class="agni-add-to-cart-sticky-out-of-stock out-of-stock-label"><?php echo esc_html__( 'Out of stock', 'cartify' ); ?></span>
                <?php } ?>
			</div>
		</div>
	</div>
	<?php 
}

add_action( 'agni_woocommerce_single_product_add_to_cart_sticky', 'cartify_template_product_layout_block_add_to_cart_sticky', 10, 1 );