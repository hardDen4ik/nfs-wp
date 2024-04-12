<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Agni Framework
 */

if ( ! is_active_sidebar( 'cartify-sidebar-2' ) ) {
	return;
}
?>

<div class="widget-area sidebar" role="complementary">
	<?php dynamic_sidebar( 'cartify-sidebar-3' ); ?>
</div>
