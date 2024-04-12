<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Agni Framework
 */

?><!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
	<meta charset="<?php echo esc_html(get_bloginfo('charset')); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<?php do_action('agni_body_open_tag'); ?>
<?php do_action('agni_page_open_tag'); ?>
<?php 

$post_id = get_the_ID();

if( class_exists('WooCommerce') && is_shop() ){
	$post_id = wc_get_page_id('shop');
}

$header_source = esc_attr( get_post_meta($post_id, 'agni_page_header_source', true) );
$header_choice = esc_attr( get_post_meta($post_id, 'agni_page_header_choice', true) );

if( get_query_var( 'term' ) || get_query_var('cat') || get_query_var('tag') ){
	$term = get_queried_object();

	if( isset( $term->term_id ) ){
		$header_source = '1';
		$header_choice = esc_attr( get_term_meta($term->term_id, 'agni_term_header_id', true) );
	}
}


do_action( 'agni_header', $header_source, $header_choice ); ?>

<?php do_action('agni_content_open_tag'); ?>
