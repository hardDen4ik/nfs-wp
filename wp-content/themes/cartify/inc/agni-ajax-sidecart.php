<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !function_exists( 'cartify_ajax_sidecart' ) ){
    function cartify_ajax_sidecart(){
        if( is_cart() || is_checkout() ){
            return;
        }

                wp_enqueue_script('cartify-ajax-sidecart');

        ?>
        <div class="agni-sidecart woocommerce">
            <div class="agni-sidecart__container">
                <div class="agni-sidecart__overlay"></div>
                <div class="agni-sidecart__contents">
                    <div class="agni-sidecart__header">
                        <?php echo apply_filters( 'agni_woocommerce_recently_viewed_products_title', cartify_woocommerce_sidecart_title() ) ?>
                        <span class="agni-sidecart__close"><i class="lni lni-close"></i></span>
                    </div>
                    <div class="agni-sidecart__body">
                        <?php do_action( 'agni_ajax_sidecart_contents' ); ?>

                        <?php // cartify_woocommerce_cart_coupon(); ?>
                    </div>
                    <div class="agni-sidecart__footer">
                        <?php do_action( 'agni_ajax_sidecart_footer' ); ?>
                    </div>
                    <div class="agni-sidecart__loader"><?php echo apply_filters('agni_woocommerce_sidecart_loading_text', esc_html__( 'Updating Cart!', 'cartify' )); ?></div>
                </div>
            </div>
        </div>
        <?php
    }
}


function cartify_woocommerce_sidecart_title(){
    ?>
    <h3><?php echo esc_html__( 'Your Cart', 'cartify' ) ?></h3>
    <?php
}


/**
 * Get Side Cart Content
 *
 * @since     1.0.0
 */

function cartify_ajax_sidecart_contents(){

	// ob_start();
	?>

    <?php 
    if( !WC()->cart->is_empty() ){

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );                          

            $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

            ?>

            <div class="agni-sidecart__product" data-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                <div class="agni-sidecart__image">
                    <a href="<?php echo esc_url( $product_permalink ) ?>">
                        <?php echo wp_kses( apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key ), array( 'img' => array(
                            'src' => array(),
                            'srcset' => array(),
                            'width' => array(),
                            'height' => array(),
                            'sizes' => array(),
                            'class' => array(),
                            'alt' => array(),
                            'loading' => array()
                        ) ) ); ?>
                    </a>
                </div>
                <div class="agni-sidecart__details">
                    <div class="agni-sidecart__inner">
                        <a class="agni-sidecart__product-link" href="<?php echo esc_url( $product_permalink ) ?>">
                            <?php echo wp_kses( apply_filters( 'agni_woocommerce_sidecart_item_name', sprintf( '<h6>%s</h6>', $_product->get_title() ), $cart_item, $cart_item_key ), 'title' ); ?>
                            <div class="price">
                                <span><?php echo wp_kses( $product_price, array( 'span' => array( 'class' => array() ) ) ); ?></span>
                            </div>
                            <div class="product-variations"><?php 

                                                            echo wp_kses( wc_get_formatted_cart_item_data( $cart_item ), array(
                                    'dl' => array( 'class' => array() ),
                                    'dt' => array( 'class' => array() ),
                                    'dd' => array( 'class' => array() ),
                                    'p' => array() 
                                ) ); 
                            ?></div>
                        </a>
                        <a href="#" class="agni-sidecart__remove"><?php echo esc_html_x( 'Remove', 'Side cart remove', 'cartify' ); ?></a>
                    </div>

                                        <?php
                    if ( $_product->is_sold_individually() ) {
                        $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                    } else {
                        $product_quantity = woocommerce_quantity_input(
                            array(
                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                'input_value'  => $cart_item['quantity'],
                                'max_value'    => $_product->get_max_purchase_quantity(),
                                'min_value'    => '0',
                                'product_name' => $_product->get_name(),
                            ),
                            $_product,
                            false
                        );
                    }

                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                    ?>
                </div>

                            </div>
        <?php } ?>

    <?php } 
    }
    else{
        echo esc_html__( 'Cart is Empty', 'cartify');
    }
    ?>
	<?php
	// return ob_get_clean();
}

/**
 * Cart Total
 */
function cartify_ajax_sidecart_footer(){

    if( !WC()->cart->is_empty() ){
        ?>
        <div class="agni-sidecart__coupon">
            <div class="agni-sidecart__coupon-text"><span><?php echo esc_html__( 'Have a coupon?', 'cartify' ); ?></span></div>
            <div class="agni-sidecart__coupon-contents">
                <?php cartify_woocommerce_cart_coupon(); ?>
            </div>
        </div>

        <?php
    }
    // ob_start();

        if( !WC()->cart->is_empty() ){

      // GLOBAL $woocommerce;
    // if ( ! defined('WOOCOMMERCE_CART') ) {
    //   define( 'WOOCOMMERCE_CART', true );
    // }

    WC()->cart->calculate_shipping();
    ?>

    <div class="agni-sidecart__subtotal cart_totals">
        <div class="shop_table shop_table_responsive ">

            <div class="cart-subtotal">
                <span><?php echo esc_html_x( 'Subtotal', 'Side cart subtotal', 'cartify' ); ?></span>
                <span data-title="<?php esc_attr_e( 'Subtotal', 'cartify' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></span>
            </div>

            <?php  foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                <div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                    <span><?php echo apply_filters( 'agni_woocommerce_totals_coupon_label_text', '<i class="lni lni-tag"></i>' ); //wc_cart_totals_coupon_label( $coupon ); ?></span>
                    <span><?php echo esc_html($coupon->get_code()); ?></span>
                    <span><a href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ); ?>" class="woocommerce-remove-coupon" data-coupon="<?php echo esc_attr( $coupon->get_code() ) ?>"><?php echo esc_html_x( 'Remove', 'Side cart coupon remove', 'cartify' ); ?></a></span>
                    <span data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php cartify_wc_cart_totals_coupon_html( $coupon ); //wc_cart_totals_coupon_html( $coupon ); ?></span>
                </div>
            <?php endforeach; ?>

                        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

                <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

                <?php wc_cart_totals_shipping_html(); ?>                

                <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

            <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

                <div class="shipping">
                    <span><?php esc_html_e( 'Shipping', 'cartify' ); ?></span>
                    <span data-title="<?php esc_attr_e( 'Shipping', 'cartify' ); ?>"><?php woocommerce_shipping_calculator(); ?></span>
                </div>

            <?php endif; ?>

            <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                <div class="fee">
                    <span><?php echo esc_html( $fee->name ); ?></span>
                    <span data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></span>
                </div>
            <?php endforeach; ?>

            <?php
            if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
                $taxable_address = WC()->customer->get_taxable_address();
                $estimated_text  = '';

                if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                    /* translators: %s location. */
                    $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'cartify' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
                }

                if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                    foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
                        ?>
                        <div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                            <span><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                            <span data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses( $tax->formatted_amount, 'post' ); ?></span>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="tax-total">
                        <span><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <span data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></span>
                    </div>
                    <?php
                }
            }
            ?>

            <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

            <div class="order-total">
                <span><?php echo esc_html_x( 'Total', 'Side cart total', 'cartify' ); ?></span>
                <span data-title="<?php esc_attr_e( 'Total', 'cartify' ); ?>"><?php wc_cart_totals_order_total_html(); ?></span>
            </div>

            <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

        </div>
    </div>
    <div class="agni-sidecart__buttons">
        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="agni-sidecart__button--checkout button btn btn-bold btn-block btn-lg"><?php echo apply_filters( 'agni_woocommerce_sidecart_checkout_btn_text', esc_html__( 'Checkout', 'cartify' ) ); ?></a>
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="agni-sidecart__button--cart button"><?php echo apply_filters( 'agni_woocommerce_sidecart_cart_btn_text', esc_html__( 'Go to Shopping Cart', 'cartify' ) ); ?></a>
    </div>
    <?php
        }

        // return ob_get_clean();
}

/**
 * Cart Footer
 */
if( !function_exists('cartify_ajax_sidecart_footer') ){
    function cartify_ajax_sidecart_footer(){
        ob_start();

                if( !WC()->cart->is_empty() ){
            ?>
            <div class="agni-sidecart__subtotal">
                <span><?php echo apply_filters('agni_woocommerce_sidecart_loading_text', esc_html__( 'Subtotal:', 'cartify' ) ); ?></span>
                <?php wc_cart_totals_subtotal_html(); ?>
            </div>
            <div class="agni-sidecart__buttons">
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="agni-sidecart__button--checkout button btn btn-bold btn-block btn-lg"><?php echo apply_filters( 'agni_woocommerce_sidecart_checkout_btn_text', esc_html__( 'Checkout', 'cartify' ) ); ?></a>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="agni-sidecart__button--cart button"><?php echo apply_filters( 'agni_woocommerce_sidecart_cart_btn_text', esc_html__( 'Go to Shopping Cart', 'cartify' ) ); ?></a>
            </div>


                                                        <?php
        }

        return ob_get_clean();
    }
}


if( !function_exists( 'cartify_ajax_cart_item_keys' ) ){
    function cartify_ajax_cart_item_keys(){
        if( !WC()->cart->is_empty() ){
            $cart_item_keys = [];
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $cart_item_keys[$cart_item['product_id']] = $cart_item_key;
            }

            // print_r( $cart_item_keys );
        }

    }

}


/**
 * Set fragments
 *
 */

function cartify_ajax_fragments($fragments){

    ob_start();

    do_action( 'agni_ajax_sidecart_contents' );

    $cart_content =  ob_get_clean();

        ob_start();

    do_action( 'agni_ajax_sidecart_footer' );

    $cart_footer = ob_get_clean();

        ob_start();

        do_action( 'agni_ajax_cart_item_keys' );

    $cart_item_keys = ob_get_clean();

	//Cart content
	$fragments['div.agni-sidecart__body'] = '<div class="agni-sidecart__body">'.$cart_content.'</div>';
    $fragments['div.agni-sidecart__footer'] = '<div class="agni-sidecart__footer">'.$cart_footer.'</div>';


        $fragments['cart_item_keys'] = $cart_item_keys;

	return $fragments;
}


function cartify_ajax_add_to_cart(){

    	// if(!isset($_POST['action']) || $_POST['action'] != 'agni_ajax_add_to_cart' || !isset($_POST['add-to-cart'])){
	// 	die();
	// }

		// get woocommerce error notice
	$error = wc_get_notices( 'error' );
	$html = '';

	if( $error ){
        // wp_send_json($error);

        		// print notice
		ob_start();
		foreach( $error as $value ) {
			// wc_print_notice( $value['notice'], 'error' );
            $data[] = $value['notice'];
		}

		// $data =  array(
		// 	'error' => ob_get_clean()
		// );

        // $data = ob_get_clean();

		wc_clear_notices(); // clear other notice
		wp_send_json($data);
	}

		else{
		// trigger action for added to cart in ajax
		do_action( 'woocommerce_ajax_added_to_cart', intval( $_POST['add-to-cart'] ) );
		wc_clear_notices(); // clear other notice
        WC_AJAX::get_refreshed_fragments();	
	}

    wp_die();

}

function cartify_ajax_update_cart(){

	//Form Input Values
	$cart_key = sanitize_text_field($_POST['cart_key']);
    $new_qty = sanitize_text_field($_POST['new_qty']);

    	//If empty return error
	if(!$cart_key){
		wp_send_json(array('error' => esc_html__( 'Something went wrong', 'cartify' )));
	}

        if( $new_qty == 0 ){
        $cart_success = WC()->cart->remove_cart_item($cart_key);
    }
    else{
        $cart_success = WC()->cart->set_quantity($cart_key, $new_qty);
    }

		if($cart_success){
        WC_AJAX::get_refreshed_fragments();
	}
	else{
		if(wc_notice_count('error') > 0){
    		echo wc_print_notices();
		}
	}
    wp_die();
}

/**
 * AJAX apply coupon on checkout page.
 */
function cartify_ajax_apply_coupon() {

    if ( empty( $_POST['coupon_code'] ) ) {
        return wp_send_json_error(WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ));
    }

    WC()->cart->add_discount( wc_format_coupon_code( wp_unslash( $_POST['coupon_code'] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        // wp_send_json_success( wc_print_notices() );

        $error = wc_get_notices( 'error' );
    $success = wc_get_notices( 'success' );
    if( $error ){
        // print notice
        wc_clear_notices(); // clear other notice
        // ob_start();
        foreach( $error as $value ) {
            wp_send_json_error($value['notice']);
        }
    }
    else{
        wc_clear_notices(); // clear other notice
        foreach( $success as $value ) {
            wp_send_json_success($value['notice']);
        }
    }

        wc_print_notices();
    wp_die();
}

/**
 * AJAX remove coupon on cart and checkout page.
 */
function cartify_ajax_remove_coupon() {

    $coupon = isset( $_POST['coupon'] ) ? wc_format_coupon_code( wp_unslash( $_POST['coupon'] ) ) : false; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

    if ( empty( $coupon ) ) {
        wp_send_json_error( esc_html__( 'Sorry there was a problem removing this coupon.', 'cartify' ) );
    } else {
        WC()->cart->remove_coupon( $coupon );
        wp_send_json_success( esc_html__( 'Coupon has been removed.', 'cartify' ) );
    }

    // wc_print_notices();
    wp_die();
}


function cartify_ajax_update_shipping_method(){

        if( isset( $_POST['shipping_method'] ) ){

                $shipping_method = sanitize_text_field( $_POST['shipping_method'] );

                WC()->session->set('chosen_shipping_methods', array( $shipping_method ) );

        WC()->cart->calculate_totals();

        wc_clear_notices(); // clear other notice
        WC_AJAX::get_refreshed_fragments();

    }

    wp_die();
}

/**
 * AJAX receive updated cart_totals div.
 */
function cartify_ajax_get_cart_totals() {
    // wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );
    WC()->cart->calculate_totals();

        wc_clear_notices(); // clear other notice
    WC_AJAX::get_refreshed_fragments();

    wp_die();
}


/**
 * function to display the scripts & styles.
 *
 * @return void
 */
function cartify_ajax_sidecart_scripts() {

	    $ajax_sidecart = 1;

    	//Check if item added to cart
	if(isset($_POST['add-to-cart'])){
		$added_to_cart = 1;
	}


    wp_register_script('cartify-ajax-sidecart', AGNI_FRAMEWORK_JS_URL . '/agni-ajax-sidecart/agni-ajax-sidecart.js', array('jquery'), wp_get_theme()->get('Version'), true);
    wp_localize_script('cartify-ajax-sidecart', 'cartify_ajax_sidecart', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        // 'security' => wp_create_nonce('agni_ajax_sidecart_nonce'),
        //'action' => 'agni_ajax_add_to_cart',
        'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
        //'added_to_cart' => true, //$added_to_cart,
        'ajax_sidecart' => $ajax_sidecart,
        'checkout_url'  => esc_url( wc_get_checkout_url() )
    ));
}



// if(!is_admin() || wp_doing_ajax()){
    add_action('agni_ajax_sidecart', 'cartify_ajax_sidecart');
    add_action('agni_ajax_sidecart_contents', 'cartify_ajax_sidecart_contents');
    add_action('agni_ajax_sidecart_footer', 'cartify_ajax_sidecart_footer');
    add_action('agni_ajax_cart_item_keys', 'cartify_ajax_cart_item_keys');

	add_action('wc_ajax_agni_ajax_add_to_cart', 'cartify_ajax_add_to_cart');
    add_action('wc_ajax_agni_ajax_update_cart', 'cartify_ajax_update_cart');
    add_action('wc_ajax_agni_ajax_apply_coupon', 'cartify_ajax_apply_coupon');
    add_action('wc_ajax_agni_ajax_remove_coupon', 'cartify_ajax_remove_coupon');
    add_action('wc_ajax_agni_ajax_get_cart_totals', 'cartify_ajax_get_cart_totals');
    add_action('wc_ajax_agni_ajax_update_shipping_method', 'cartify_ajax_update_shipping_method');

	// add_action('wc_ajax_nopriv_agni_ajax_add_to_cart', 'cartify_ajax_add_to_cart');
    // add_action('wc_ajax_nopriv_agni_ajax_update_cart', 'cartify_ajax_update_cart');

    	add_filter('woocommerce_add_to_cart_fragments', 'cartify_ajax_fragments', 10, 1);
    add_action('wp_enqueue_scripts', 'cartify_ajax_sidecart_scripts');
// }
