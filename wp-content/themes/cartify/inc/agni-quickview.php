<?php

function cartify_quickview_button(){
    global $product;

    $product_quickview = cartify_get_theme_option( 'shop_settings_general_product_quickview', '1' );

    if( $product_quickview != '1' ){
        if( is_shop() || is_product_category() || is_product_tag() || is_product() || is_tax( 'product_brand' )){
            return;
        }
    }


    wp_enqueue_script('cartify-quickview');

	//wp_enqueue_script('cartify-ajax-sidecart');
    //wp_enqueue_script( 'wc-add-to-cart-variation' );
	//wp_enqueue_script('cartify-ajax-sidecart');

    ?>
    <div class="agni-quickview"><?php
        ?><div class="agni-quickview__button"><?php
            ?><a href="#" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo apply_filters( 'agni_woocommerce_quickview_icon', cartify_get_icon_svg('common', 'quickview') ) ?><span><?php echo esc_html__( 'Quickview', 'cartify' ); ?></span></a><?php
        ?></div><?php
    ?></div><?php
}


function cartify_quickview_contents(){
    if( !isset($_REQUEST['product_id']) ){
        return;
    }

    global $product;
    $product_id = $_REQUEST['product_id'];
    $product = wc_get_product($product_id);

    $product_price = $product->get_price_html();
    $product_short_description = $product->get_short_description();
    $post_thumbnail_id = $product->get_image_id();
    $attachment_ids = $product->get_gallery_image_ids();

        remove_action( 'woocommerce_after_add_to_cart_button', 'cartify_woocommerce_single_additional_button_wishlist', 15 );
    remove_action( 'woocommerce_after_add_to_cart_button', 'cartify_woocommerce_single_additional_button_compare', 20 );

    ?>
    <div class="agni-quickview-contents">
        <div class="agni-quickview-contents__gallery">
            <?php

                           // echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $post_thumbnail_id, true ), $post_thumbnail_id );
            echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wp_get_attachment_image( $post_thumbnail_id, 'woocommerce_single' ) );

            if ( $attachment_ids && $post_thumbnail_id ) {
                foreach ( $attachment_ids as $attachment_id ) {
                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wp_get_attachment_image( $attachment_id, 'woocommerce_single' ) );
                }
            }

            ?>
        </div>

                <div class="agni-quickview-contents__details">
            <?php echo apply_filters( 'agni_quickview_product_title', sprintf( '<h3 class="agni-quickview-contents__title">%s</h3>', wp_kses( $product->get_name(), 'title' ) )  ); ?>
            <?php if($product_price){
                ?>
                <span class="price"><?php echo wp_kses( $product_price, 'price' ); ?></span>
                <?php
            } ?>
            <?php if( $product->get_short_description() ){ ?>
				<div class="agni-quickview-contents__short-description">
					<?php echo wp_kses_post( $product->get_short_description() ); ?>
				</div>
			<?php } ?>
            <?php do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' ); ?>

			<div class="agni-quickview-contents__single-link">
                <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo esc_html__( 'More Details', 'cartify' ); ?></a>
			</div>
        </div>
    </div>

        <?php
    die();
}

function cartify_quickview_additional_variation_images(){
    if( !isset($_REQUEST['product_id']) ){
        return;
    }

    $product_id = $_REQUEST['product_id'];
    $variation_id = $_REQUEST['variation_id'];

    $product = wc_get_product( $product_id );
    //$variation = new WC_Product_Variation($variation_id);
    $available_variations = $product->get_available_variations();
    $variation_image_id = '';
    ?>
    <?php


                // if ( $available_variation_images_ids ) {
    //     foreach ( $available_variation_images_ids as $available_variation_images_id ) {
    //         echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $available_variation_images_id, true ), $available_variation_images_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
    //     }
    // }

    //$available_variation_images_ids = array();
    foreach( $available_variations as $variation ){
        if( $variation_id == $variation['variation_id']){
            $variation_image_id = $variation['image_id'];

            echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wp_get_attachment_image( $variation_image_id, 'woocommerce_single' ) );

            foreach($variation['agni_variation_images'] as $additional_variation_image_id){
                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wp_get_attachment_image( $additional_variation_image_id, 'woocommerce_single' ) );
            }
        }
    }

        ?>

    <?php
    die();
}


function cartify_quickview_additional_variation_images_reset(){
    $product_id = $_POST['product_id'];
    $product = wc_get_product( $product_id );

    $post_thumbnail_id = $product->get_image_id();
    $attachment_ids = $product->get_gallery_image_ids();

    ?>
    <?php

                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wp_get_attachment_image( $post_thumbnail_id, 'woocommerce_single' ) );

        if ( $attachment_ids && $post_thumbnail_id ) {
            foreach ( $attachment_ids as $attachment_id ) {
                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wp_get_attachment_image( $attachment_id, 'woocommerce_single' ) );
            }
        }

            ?>
    <?php

    die();
}


function cartify_quickview_scripts(){

    // Registering JS for compare
    wp_register_script('cartify-quickview', AGNI_FRAMEWORK_JS_URL . '/agni-quickview/agni-quickview.js', array('jquery'), wp_get_theme()->get('Version'), true);
    wp_localize_script('cartify-quickview', 'cartify_quickview', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
        // 'add_to_compare_text' => 'Compare',
        // 'remove_from_compare_text' => 'Remove Compare',

        // 'security' => wp_create_nonce('agni_ajax_search_nonce'),
        // 'action' => 'agni_processing_ajax_search',
    ));
}


add_action( 'agni_woocommerce_after_shop_loop_item', 'cartify_quickview_button', 15 );

add_action( 'wc_ajax_agni_quickview_contents', 'cartify_quickview_contents' );

add_action( 'wc_ajax_agni_quickview_additional_variation_images', 'cartify_quickview_additional_variation_images' );
add_action( 'wc_ajax_agni_quickview_additional_variation_images_reset', 'cartify_quickview_additional_variation_images_reset' );

add_action( 'wp_enqueue_scripts', 'cartify_quickview_scripts' );
