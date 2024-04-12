<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="review_order_table" class="shop_table woocommerce-checkout-review-order-table">

	<div class="woocommerce-checkout-products">
		<h5><?php echo esc_html__( 'Item details', 'cartify' ); ?></h5>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>

								<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="product-thumbnail">
						<?php echo apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key ); ?>
					</div>	
					<div class="product-details">
						<h6 class="product-name"><?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h6>

													<span class="product-quantity"><span><?php echo esc_html__( 'Qty:', 'cartify' ); ?></span><?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', $cart_item['quantity'], $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div class="product-variations"><?php if ( $_product->is_type('variable') || $_product->is_type('variation') ) {
							echo wc_get_formatted_variation( $_product ); 
						} 
						echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					</div>

										<span class="product-total">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					</div>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</div>
	<div class="woocommerce-checkout-totals">

		<span class="cart-subtotal">
			<span><?php echo esc_html_x( 'Subtotal', 'Checkout subtotal', 'cartify' ); ?></span>
			<span><?php wc_cart_totals_subtotal_html(); ?></span>
		</span>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<span><?php echo apply_filters( 'agni_woocommerce_totals_coupon_label_text', '<i class="lni lni-tag"></i>' ); //wc_cart_totals_coupon_label( $coupon ); ?></span>
				<span><?php echo esc_html($coupon->get_code()); ?></span>
				<span><a href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ); ?>" class="woocommerce-remove-coupon" data-coupon="<?php echo esc_attr( $coupon->get_code() ) ?>"><?php echo esc_html_x( 'Remove', 'Checkout coupon remove', 'cartify' ); ?></a></span>
				<span data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php cartify_wc_cart_totals_coupon_html( $coupon ); //wc_cart_totals_coupon_html( $coupon ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<span class="fee">
				<span><?php echo esc_html( $fee->name ); ?></span>
				<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</span>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited ?>
					<span class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<span><?php echo esc_html( $tax->label ); ?></span>
						<span><?php echo wp_kses( $tax->formatted_amount, array( 'span' => array() ) ); ?></span>
					</span>
				<?php endforeach; ?>
			<?php else : ?>
				<span class="tax-total">
					<span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
					<span><?php wc_cart_totals_taxes_total_html(); ?></span>
				</span>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<span class="order-total">
			<span><?php echo esc_html_x( 'Total', 'Checkout total', 'cartify' ); ?></span>
			<span><?php wc_cart_totals_order_total_html(); ?></span>
		</span>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</div>
</div>
