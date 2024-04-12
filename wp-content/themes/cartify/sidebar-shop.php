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

$additional_classes = isset( $args['additional_classes'] ) ? $args['additional_classes'] : '';
$has_topbar = isset( $args['has_topbar'] ) ? $args['has_topbar'] : '';
$has_sidebar = isset( $args['has_sidebar'] ) ? $args['has_sidebar'] : '';


$sidebar_classes = array(
	'widget-area',
	'shop-sidebar',
	!empty( $has_topbar ) ? 'has-topbar' : '',
	!empty( $has_sidebar ) ? 'has-sidebar' : '',
	$additional_classes,
)

?>

<div id="secondary" class="<?php echo esc_attr( cartify_prepare_classes( $sidebar_classes ) ); ?>" role="complementary">
	<div class="shop-sidebar-overlay"></div>
	<div class="shop-sidebar-container">
		<div class="shop-sidebar-content">
			<?php if( !empty( $has_topbar ) ){ ?>
				<div class="shop-sidebar-content__topbar">
					<?php dynamic_sidebar( 'cartify-sidebar-4' ); ?>
				</div>
			<?php } ?>
			<?php if( !empty( $has_sidebar ) ){ ?>
				<div class="shop-sidebar-content__sidebar">
					<?php dynamic_sidebar( 'cartify-sidebar-2' ); ?>
				</div>
			<?php } ?>
			<?php if( !empty( $has_topbar ) && !empty( $has_sidebar ) ){ ?>
				<button class="shop-sidebar-content__toggle btn btn-primary btn-alt btn-sm btn-block">
					<span class="shop-sidebar-btn-more-text"><?php echo esc_html__( 'More filters', 'cartify' ); ?></span>
					<span class="shop-sidebar-btn-less-text"><?php echo esc_html__( 'Less filters', 'cartify' ); ?></span>
				</button>
			<?php } ?>
		</div>
		<div class="shop-sidebar-footer">
			<a href="#" class="shop-sidebar-btn-close btn btn-alt btn-primary btn-block"><?php echo esc_html_x( 'Close', 'Sidebar Close', 'cartify' ); ?></a>
			<a href="#" class="shop-sidebar-btn-apply btn btn-primary btn-block"><?php echo esc_html__( 'Apply filter', 'cartify' ); ?></a>
		</div>
	</div>
</div>
