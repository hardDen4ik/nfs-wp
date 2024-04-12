<?php
/**
 * Single Product Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

 ?>

<div class="woocommerce-product-rating">
	<?php

	if ( $rating_count > 0 ) {
		echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. 
	} 
	else{
		$html = '';
		$rating = 0;
		$count = 0;

		/* translators: %s: rating */
		$label = sprintf( esc_html__( 'Rated %s out of 5', 'cartify' ), $rating );
		$html  = '<div class="star-rating" role="img" aria-label="' . esc_attr( $label ) . '">' . wc_get_star_rating_html( $rating, $count ) . '</div>';

			echo apply_filters( 'agni_woocommerce_product_get_rating_html', $html, $rating, $count );
	}

		?>
	<?php if ( comments_open() ) : ?>
		<?php //phpcs:disable ?>
		<a href="#reviews" class="woocommerce-review-link" rel="nofollow"><?php 
		if ( $rating_count > 0 ) {	
			printf( _n( '%s review', '%s reviews', $review_count, 'cartify' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); 
		}
		else{
			echo esc_html__( 'Be the first to review', 'cartify' );
		}
		?></a>
		<?php // phpcs:enable ?>
	<?php endif ?>
</div>
