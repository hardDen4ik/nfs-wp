<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'cartify' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'cartify' );


$qty_choice = cartify_get_theme_option( 'shop_settings_general_qty_style',  '' );

$quantity_style = apply_filters( 'agni_woocommerce_qty_choice', $qty_choice );

?>
<div class="quantity">
	<?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
	<span><?php echo esc_html__( 'Qty', 'cartify' ); ?></span>
	<div class="agni-product-qty-container">
	<?php 
		if( $quantity_style == 'dropdown' && !$is_readonly ){ 
			$dropdown_limit = apply_filters('agni_woocommerce_qty_dropdown_max_limit', 10);
			$max_value = !empty($max_value) ? $max_value : $dropdown_limit;
			?>
			<select
				id="<?php echo esc_attr( $input_id ); ?>"
				class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
				name="<?php echo esc_attr( $input_name ); ?>"
				title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'cartify' ); ?>"
				>
				<?php for ($value = $min_value; $value <= $max_value; $value++) { ?>
					<option <?php selected( $value, $input_value ); ?>><?php echo esc_html( $value ); ?></option>
				<?php } ?>
			</select>
		<?php }
		else{ ?>
			<input
				type="<?php echo esc_attr( $type ); ?>"
				<?php echo $readonly ? 'readonly="readonly"' : ''; ?>
				id="<?php echo esc_attr( $input_id ); ?>"
				class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
				min="<?php echo esc_attr( $min_value ); ?>"
				max="<?php echo esc_attr( 0 < $max_value ? $max_value : '999' ); ?>"
				name="<?php echo esc_attr( $input_name ); ?>"
				value="<?php echo esc_attr( $input_value ); ?>"
				aria-label="<?php esc_attr_e( 'Product quantity', 'woocommerce' ); ?>"
				<?php if ( ! $readonly ): ?>
					step="<?php echo esc_attr( $step ); ?>"
					placeholder="<?php echo esc_attr( $placeholder ); ?>"
					inputmode="<?php echo esc_attr( $inputmode ); ?>"
					autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
				<?php endif; ?>
			/>
			<span class="qty-minus"><i class="lni lni-minus"></i></span>
			<span class="qty-plus"><i class="lni lni-plus"></i></span>
	<?php } ?>
	</div>
	<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
</div>