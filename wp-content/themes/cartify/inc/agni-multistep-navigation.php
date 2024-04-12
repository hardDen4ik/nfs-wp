<?php 

add_action( 'woocommerce_before_checkout_form', 'cartify_woocommerce_checkout_multistep_tabs' );
add_action( 'woocommerce_after_checkout_form', 'cartify_woocommerce_checkout_multistep_navigation' );

add_filter( 'body_class', 'cartify_woocommerce_additional_classes_checkout_page' );


add_action( 'wc_ajax_agni_multistep_address_validation', 'cartify_multistep_address_validation' );
add_action( 'wc_ajax_agni_apply_coupon', 'cartify_apply_coupon' );

function cartify_woocommerce_additional_classes_checkout_page( $classes ){

	$multistep_checkout = cartify_get_theme_option( 'shop_settings_multistep_checkout', '1' );

	if( !$multistep_checkout ){
		return $classes;
	}

	if( is_checkout() ){
		$classes[] = 'has-multistep-navigation';

			}

		return array_unique( $classes );
}


function cartify_woocommerce_checkout_multistep_tabs(){

	$multistep_checkout = cartify_get_theme_option( 'shop_settings_multistep_checkout', '1' );

	if( !$multistep_checkout ){
		return;
	}


	$step_1 = true;
	$step_2 = false;

	if( is_user_logged_in() || ( 'no' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' ) && 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) ){
		$step_1 = false;
		$step_2 = true;
	}

	?>
	<div class="agni-woocommerce-multistep-checkout">
		<div class="agni-woocommerce-multistep-checkout__container">
			<ul class="agni-woocommerce-multistep-checkout__tabs">
				<?php 
				$i = 1;
				?>
				<li class="<?php echo esc_attr( $step_1 ) ? 'active' : 'disabled' ?>">
					<a href="#account_info"><span><?php echo esc_html( $i ) . '. '; $i++; ?></span><?php echo esc_html__( 'Account information', 'cartify' );?></a>
				</li>
				<li class="<?php echo esc_attr( $step_2 ) ? 'active' : '' ?>">
					<a href="#customer_details"><span><?php echo esc_html( $i ) . '. '; $i++; ?></span><?php echo esc_html__( 'Billing & shipping address', 'cartify' ); ?></a>
				</li>
				<li>
					<a href="#review_order_table"><span><?php echo esc_html( $i ) . '. '; $i++; ?></span><?php echo esc_html__( 'Review order', 'cartify' ); ?></a>
				</li>
				<li>
					<a href="#payment"><span><?php echo esc_html( $i ) . '. '; ?></span><?php echo esc_html__( 'Payment', 'cartify' ); ?></a>
				</li>
			</ul>
		</div>
	</div>
	<?php
}


function cartify_woocommerce_checkout_multistep_navigation(){

	$multistep_checkout = cartify_get_theme_option( 'shop_settings_multistep_checkout', '1' );

	if( !$multistep_checkout ){
		return;
	}
	// if(/* is not Multistep naviagation */){

	// }
	$go_back = false;

		if( !is_user_logged_in() && !( 'no' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' ) && 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) )){
		$go_back = true;
	}

	?>
	<div class="agni-woocommerce-multistep-checkout-navigation">

		<?php if( !is_user_logged_in() ){ ?> 
			<div id="account_info_navigation" class="woocommerce-checkout-account-info-navigation">
				<a href="#customer_details" class="btn next"><?php echo esc_html__( 'Skip login', 'cartify' ); ?><i class="lni lni-chevron-right"></i></a>
			</div>
		<?php } ?>
		<div id="customer_details_navigation" class="woocommerce-checkout-customer-details-navigation">
			<?php if( $go_back ){ ?> 
				<a href="#account_info" class="prev"><i class="lni lni-chevron-left"></i><?php echo esc_html__( 'Go back', 'cartify' ); ?></a>
			<?php } ?>
			<a href="#review_order_table" class="btn next"><?php echo esc_html__( 'Continue', 'cartify' ); ?><i class="lni lni-chevron-right"></i></a>
		</div>
		<div id="review_order_table_navigation" class="woocommerce-checkout-review-order-navigation">
			<a href="#customer_details" class="prev"><i class="lni lni-chevron-left"></i><?php echo esc_html__( 'Go back', 'cartify' ); ?></a>
			<a href="#payment" class="btn next"><?php echo esc_html__( 'Continue', 'cartify' ); ?><i class="lni lni-chevron-right"></i></a>
		</div>
		<div id="payment_navigation" class="woocommerce-checkout-payment-navigation">
			<a href="#review_order_table" class="prev"><i class="lni lni-chevron-left"></i><?php echo esc_html__( 'Go back', 'cartify' ); ?></a>
		</div>
	</div>
	<?php
}


function cartify_multistep_address_validation() {

    // print_r( $_REQUEST );

    global $woocommerce;
    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

    // print_r($chosen_methods);

    // billing_country
    // billing_postcode
    // billing_email
    // billing_phone

    // shipping_country
    // shipping_postcode

    $validation = new WC_Validation();

    $is_billing_valid = $validation->is_postcode( $_REQUEST['billing_postcode'], $_REQUEST['billing_country'] );
    // $is_email_valid = $validation->is_email( $_REQUEST['billing_email'] );
	// $is_phone_valid = $validation->is_phone( $_REQUEST['billing_phone'] );

	    $results = array(
        // 'email' => $is_email_valid,
		// 'phone' => $is_phone_valid,
		'shipping_options' => $chosen_methods,
        'valid_billing_address' => $is_billing_valid,
	);

		if( isset( $_REQUEST['billing_email'] ) ){
		$results['email'] = $validation->is_email( $_REQUEST['billing_email'] );
	}

	if( isset( $_REQUEST['billing_phone'] ) ){
		$results['phone'] = $validation->is_phone( $_REQUEST['billing_phone'] );
	}

	if( $chosen_methods !== null && $chosen_methods[0] !== false ){
		$is_shipping_valid = $validation->is_postcode( $_REQUEST['shipping_postcode'], $_REQUEST['shipping_country'] );

		$results['valid_shipping_address'] = $is_shipping_valid;
	}

		// WC()->session->set( 'chosen_shipping_methods', null );

    wp_send_json_success( $results );

    die();
}


/**
 * AJAX apply coupon on checkout page.
 */
function cartify_apply_coupon() {

	check_ajax_referer( 'apply-coupon', 'security' );

	if ( ! empty( $_POST['coupon_code'] ) ) {
		WC()->cart->add_discount( wc_format_coupon_code( wp_unslash( $_POST['coupon_code'] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	} else {
		wc_add_notice( WC_Coupon::get_generic_coupon_error( WC_Coupon::E_WC_COUPON_PLEASE_ENTER ), 'error' );
	}

	wc_print_notices();
	wp_die();
}

/**
 * AJAX remove coupon on cart and checkout page.
 */
function cartify_remove_coupon() {
	check_ajax_referer( 'remove-coupon', 'security' );

	$coupon = isset( $_POST['coupon'] ) ? wc_format_coupon_code( wp_unslash( $_POST['coupon'] ) ) : false; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( empty( $coupon ) ) {
		wc_add_notice( esc_html__( 'Sorry there was a problem removing this coupon.', 'cartify' ), 'error' );
	} else {
		WC()->cart->remove_coupon( $coupon );
		wc_add_notice( esc_html__( 'Coupon has been removed.', 'cartify' ) );
	}

	wc_print_notices();
	wp_die();
}

?>