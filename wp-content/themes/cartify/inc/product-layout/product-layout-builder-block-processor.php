<?php 

// Include Blocks files
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/breadcrumbs.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/images.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/product-video.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/product-360.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/sale.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/hot.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/new.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/featured-label.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/title.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/brand.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/rating.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/price.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/countdown.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/features.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/short-description.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/stock.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/add-to-cart.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/add-to-wishlist.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/add-to-compare.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/offers.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/shipping-info.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/metadata.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/compare.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/compare-content.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/sharing.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/fbt.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/tabs.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/recently-viewed.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/related.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/upsell.php';

require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/spacer.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/separator.php';
require_once AGNI_TEMPLATE_DIR . '/product-layout/blocks/content-block.php';

// Actions
add_action( 'agni_product_layout_block_breadcrumbs', 'cartify_template_product_layout_block_breadcrumbs', 10, 1 );
add_action( 'agni_product_layout_block_images', 'cartify_template_product_layout_block_images', 10, 1 );
add_action( 'agni_product_layout_block_product_video', 'cartify_template_product_layout_block_product_video', 10, 1 );
add_action( 'agni_product_layout_block_product_360', 'cartify_template_product_layout_block_product_360', 10, 1 );
add_action( 'agni_product_layout_block_sale', 'cartify_template_product_layout_block_sale', 10, 1 );
add_action( 'agni_product_layout_block_hot', 'cartify_template_product_layout_block_hot', 10, 1 );
add_action( 'agni_product_layout_block_new', 'cartify_template_product_layout_block_new', 10, 1 );
add_action( 'agni_product_layout_block_featured_label', 'cartify_template_product_layout_block_featured_label', 10, 1 );
add_action( 'agni_product_layout_block_title', 'cartify_template_product_layout_block_title', 10, 1 );
add_action( 'agni_product_layout_block_brand', 'cartify_template_product_layout_block_brand', 10, 1 ); 
add_action( 'agni_product_layout_block_rating', 'cartify_template_product_layout_block_rating', 10, 1 ); 
add_action( 'agni_product_layout_block_price', 'cartify_template_product_layout_block_price', 10, 1 );
add_action( 'agni_product_layout_block_countdown', 'cartify_template_product_layout_block_countdown', 10, 1 );
add_action( 'agni_product_layout_block_features', 'cartify_template_product_layout_block_features', 10, 1 );
add_action( 'agni_product_layout_block_short_description', 'cartify_template_product_layout_block_short_description', 10, 1 );
add_action( 'agni_product_layout_block_stock', 'cartify_template_product_layout_block_stock', 10, 1 ); 
add_action( 'agni_product_layout_block_add_to_cart', 'cartify_template_product_layout_block_add_to_cart', 10, 1 );
add_action( 'agni_product_layout_block_add_to_wishlist', 'cartify_template_product_layout_block_add_to_wishlist', 10, 1 );
add_action( 'agni_product_layout_block_add_to_compare', 'cartify_template_product_layout_block_add_to_compare', 10, 1 );
add_action( 'agni_product_layout_block_offers', 'cartify_template_product_layout_block_offers', 10, 1 ); 
add_action( 'agni_product_layout_block_shipping_info', 'cartify_template_product_layout_block_shipping_info', 10, 1 ); 
add_action( 'agni_product_layout_block_metadata', 'cartify_template_product_layout_block_metadata', 10, 1 );
add_action( 'agni_product_layout_block_compare', 'cartify_template_product_layout_block_compare', 10, 1 );
add_action( 'agni_product_layout_block_compare_content', 'cartify_template_product_layout_block_compare_content', 10, 1 );
add_action( 'agni_product_layout_block_sharing', 'cartify_template_product_layout_block_sharing', 10, 1 );
add_action( 'agni_product_layout_block_fbt', 'cartify_template_product_layout_block_fbt', 10, 1 );
add_action( 'agni_product_layout_block_tabs', 'cartify_template_product_layout_block_tabs', 10, 1 ); 
add_action( 'agni_product_layout_block_recently_viewed', 'cartify_template_product_layout_block_recently_viewed', 10, 1 );
add_action( 'agni_product_layout_block_related', 'cartify_template_product_layout_block_related', 10, 1 );
add_action( 'agni_product_layout_block_upsell', 'cartify_template_product_layout_block_upsell', 10, 1 ); 

add_action( 'agni_product_layout_block_separator', 'cartify_template_product_layout_block_separator', 10, 1 ); 
add_action( 'agni_product_layout_block_spacer', 'cartify_template_product_layout_block_spacer', 10, 1 ); 
add_action( 'agni_product_layout_block_content_block', 'cartify_template_product_layout_block_content_block', 10, 1 );

// Filters
add_filter( 'agni_product_layout_block_processor', 'cartify_product_layout_block_processor' );


function cartify_product_layout_block_processor( $block ){

	$hook = $block['hook'];
	$priority = $block['priority'];


		add_action( "woocommerce_{$hook}", function() use($block){
		do_action( "agni_product_layout_block_{$block['slug']}", $block );
		?><?php
	}, $priority);

	?><?php
}

function cartify_woocommerce_single_divider(){
	?>
	<div class="agni-product-layout-divider"></div>
	<?php
}

