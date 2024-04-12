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

?>

<?php do_action('agni_content_close_tag'); ?>
<?php 



$post_id = get_the_ID();

if( class_exists('WooCommerce') && is_shop() ){
    $post_id = wc_get_page_id('shop');
}

$footer_choice = '2';
$footer_block_choice = esc_attr( get_post_meta($post_id, 'agni_footer_block_id', true) );

if( get_query_var( 'term' ) || get_query_var('cat') || get_query_var('tag') ){
    $term = get_queried_object();

    if( isset( $term->term_id ) ){
        $footer_block_choice = esc_attr( get_term_meta($term->term_id, 'agni_term_footer_block_id', true) );
    }
}

if( empty( $footer_block_choice ) ){

    $footer_choice = esc_attr( cartify_get_theme_option( 'footer_settings_footer_source', '1' ) );
    $footer_block_choice = esc_attr( cartify_get_theme_option( 'footer_settings_content_block_choice', '' ) );
}

do_action( 'agni_footer', $footer_choice, $footer_block_choice ); 

?>
<?php do_action('agni_page_close_tag'); ?>
<?php do_action('agni_body_close_tag');?>

</html>
