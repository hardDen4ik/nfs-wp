<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$view = apply_filters( 'agni_products_archives_view_choice', cartify_get_theme_option( 'shop_settings_general_view_choice', 'grid' ) ); // 'grid'; // get theme option to choose default view
$display_style = apply_filters( 'agni_products_archives_display_style', cartify_get_theme_option( 'shop_settings_general_display_style', '1a' ) ); // '1';
$qty = apply_filters( 'agni_products_archives_qty_show', cartify_get_theme_option( 'shop_settings_general_show_qty', '' ) ); //'1';
$qty_style = apply_filters( 'agni_products_archives_qty_choice', cartify_get_theme_option( 'shop_settings_general_show_qty_choice', '2' ) ); //'2'; 


if(isset($_GET['view'])){
	$view = sanitize_text_field( $_GET['view'] );

	if( $_GET['view'] == 'list' ){
		$display_style = '';
	}
}

$classes = array(
	"shop-page-products",
	"products",
	"columns-" . wc_get_loop_prop( 'columns' ),
	( !empty( $display_style ) ) ? "has-display-style-" . $display_style : '',
	( $qty == '1' && !empty($qty_style) ) ? 'has-qty-' . $qty_style : '',
	$view,
);


?>
<ul class="<?php echo esc_attr( cartify_prepare_classes( $classes ) ); ?>">
