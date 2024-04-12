<?php

include AGNI_TEMPLATE_DIR . '/woocommerce/class-relevanssi-compatibility.php';


// enable gutenberg for woocommerce
function cartify_enable_block_editor_product( $can_edit, $post_type ) {
 if ( $post_type == 'product' ) {
        $can_edit = true;
    }
    return $can_edit;
}

// enable taxonomy fields for woocommerce with gutenberg on
function cartify_enable_taxonomy_rest( $args ) {
    $args['show_in_rest'] = true;
    return $args;
}



function cartify_woocommerce_setup() {

	// Removing Wocommerce CSS
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

	// Disabling Wizard redirect
	add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );

}

/**
 * Register & Enqueue WooCommerce Scripts & Styles
 *
 * @return void
 */
function cartify_woocommerce_scripts(){

	// Enqueue CSS
	//wp_enqueue_style( 'cartify-woocommerce-style', AGNI_FRAMEWORK_CSS_URL .'/woocommerce-style.css', array(), wp_get_theme()->get('Version')  );


	$fb_app_id = esc_attr( cartify_get_theme_option('api_settings_facebook_app_id', '') );
	$google_client_id = esc_attr( cartify_get_theme_option('api_settings_google_client_id', '') );

	if( !empty( $google_client_id ) ){
		wp_register_script( 'cartify_google_api', '//accounts.google.com/gsi/client', array(), '' );
	}

	$woocommerce_pagination_base = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );

	// Enqueue JS
	wp_enqueue_script('cartify-woocommerce', AGNI_FRAMEWORK_JS_URL . '/woocommerce/woocommerce.js', array('jquery'), wp_get_theme()->get('Version'), true);
	wp_localize_script('cartify-woocommerce', 'cartify_woocommerce', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
        'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
		'security' => wp_create_nonce('agni_woocommerce_nonce'),
		'woocommerce_pagination_base' => $woocommerce_pagination_base,
		'breakpoints' => array(
			'desktop' => '1440',
			'laptop' => '1024',
			'tab' => '667',
			'mobile' => ''
		),
		'fb_app_id' => $fb_app_id,
		'google_client_id' => $google_client_id
		//'action' => 'agni_processing_ajax_search',
	));

    }

// function cartify_woocommerce_admin_scripts($hook_suffix){
// 	global $post;

// 	if(!in_array($hook_suffix, array('post.php', 'post-new.php') )){
// 		if ( 'product' !== $post->post_type ) {
// 			return;
// 		}
// 	}

// 	// Enqueue JS
// 	if( !did_action( 'wp_enqueue_media' ) ) {
//         wp_enqueue_media();
// 	}

// 	wp_enqueue_script('cartify-woocommerce-admin', AGNI_FRAMEWORK_JS_URL . '/woocommerce/woocommerce-admin.js', array('jquery', 'media-upload'), wp_get_theme()->get('Version'), true);
// 	// wp_localize_script('cartify-woocommerce', 'cartify_woocommerce', array(
// 	// 	'ajaxurl' => admin_url('admin-ajax.php'),
//     //     'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
// 	// 	'security' => wp_create_nonce('agni_woocommerce_nonce'),
// 	// 	//'action' => 'agni_processing_ajax_search',
// 	// ));

    // }
// add_action( 'admin_enqueue_scripts', 'cartify_woocommerce_admin_scripts' );

function cartify_woocommerce_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'WooCommerce Sidebar', 'cartify'),
		'id'            => 'cartify-sidebar-2',
		'description' => esc_html__('Add widgets here.', 'cartify'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'WooCommerce Single Sidebar', 'cartify'),
		'id'            => 'cartify-sidebar-3',
		'description' => esc_html__('Add widgets here.', 'cartify'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'WooCommerce Topbar', 'cartify'),
		'id'            => 'cartify-sidebar-4',
		'description' => esc_html__('Add widgets here.', 'cartify'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
	) );
}



function cartify_woocommerce_custom_image_sizes(){
	if( !class_exists( 'WooCommerce' ) ){
		return false;
	}

	$custom_woocommerce_image_sizes = cartify_get_theme_option( 'advanced_settings_generate_custom_thumbnails', '' ); //theme option to get the value.

	if( $custom_woocommerce_image_sizes ){

		$base_thumbnail_width = get_option('woocommerce_thumbnail_image_width'); //432;
		$base_single_width = get_option('woocommerce_single_image_width'); //700;

		add_image_size( 'cartify_thumbnail_1/1', $base_thumbnail_width, $base_thumbnail_width, true );


				add_image_size( 'cartify_thumbnail_3/2', $base_thumbnail_width, $base_thumbnail_width * 2/3, true );
		add_image_size( 'cartify_thumbnail_4/3', $base_thumbnail_width, $base_thumbnail_width * 3/4, true );
		add_image_size( 'cartify_thumbnail_5/4', $base_thumbnail_width, $base_thumbnail_width * 4/5, true );
		add_image_size( 'cartify_thumbnail_6/5', $base_thumbnail_width, $base_thumbnail_width * 5/6, true );

		add_image_size( 'cartify_thumbnail_2/3', $base_thumbnail_width, $base_thumbnail_width * 3/2, true );
		add_image_size( 'cartify_thumbnail_3/4', $base_thumbnail_width, $base_thumbnail_width * 4/3, true );
		add_image_size( 'cartify_thumbnail_4/5', $base_thumbnail_width, $base_thumbnail_width * 5/4, true );
		add_image_size( 'cartify_thumbnail_5/6', $base_thumbnail_width, $base_thumbnail_width * 6/5, true );

		add_image_size( 'cartify_single_1/1', $base_single_width, $base_single_width, true );

		add_image_size( 'cartify_single_3/2', $base_single_width, $base_single_width * 2/3, true );
		add_image_size( 'cartify_single_4/3', $base_single_width, $base_single_width * 3/4, true );
		add_image_size( 'cartify_single_5/4', $base_single_width, $base_single_width * 4/5, true );
		add_image_size( 'cartify_single_6/5', $base_single_width, $base_single_width * 5/6, true );

		add_image_size( 'cartify_single_2/3', $base_single_width, $base_single_width * 3/2, true );
		add_image_size( 'cartify_single_3/4', $base_single_width, $base_single_width * 4/3, true );
		add_image_size( 'cartify_single_4/5', $base_single_width, $base_single_width * 5/4, true );
		add_image_size( 'cartify_single_5/6', $base_single_width, $base_single_width * 6/5, true );

	}

}

function cartify_regenerate_custom_image_sizes( $unfiltered_sizes ){


	$custom_woocommerce_image_sizes = 1; // theme option to get the value.

	if( !$custom_woocommerce_image_sizes ){
		return $unfiltered_sizes;
	}

	$unfiltered_sizes[] = 'cartify_thumbnail_1/1';
	$unfiltered_sizes[] = 'cartify_thumbnail_3/2';
	$unfiltered_sizes[] = 'cartify_thumbnail_4/3';
	$unfiltered_sizes[] = 'cartify_thumbnail_5/4';
	$unfiltered_sizes[] = 'cartify_thumbnail_6/5';
	$unfiltered_sizes[] = 'cartify_thumbnail_2/3';
	$unfiltered_sizes[] = 'cartify_thumbnail_3/4';
	$unfiltered_sizes[] = 'cartify_thumbnail_4/5';
	$unfiltered_sizes[] = 'cartify_thumbnail_5/6';

	return $unfiltered_sizes;
}

function cartify_woocommerce_theme_options_processing(){
	// $single_page_sidebar_default = '';
	// $single_page_sidebar = cartify_get_theme_option( 'shop_settings_single_sidebar', $single_page_sidebar_default );

	// $shop_page_sidebar = cartify_get_theme_option( 'shop_settings_general_sidebar', '' );


}
function cartify_woocommerce_layout_setup(){

	if( !is_product() ){
		return;
	}

	$product_id = get_the_id();

	$layout_id = esc_attr( get_post_meta( $product_id, 'agni_product_layout_choice', true ) ); //'1'; // get value from metabox or option panel

		do_action( 'agni_single_product_layout', $layout_id );
}


function cartify_woocommerce_single_open_tag( $product_single_options ){

	$single_page_sidebar_default = '';
	$single_page_sidebar = cartify_get_theme_option( 'shop_settings_single_sidebar', $single_page_sidebar_default );

	if( is_single() ){
		$shop_single_classes = apply_filters( 'agni_single_product_layout_container_classes', array('shop-single-page-container', $single_page_sidebar) );
		?>
		<div class="<?php echo esc_attr( cartify_prepare_classes( $shop_single_classes ) ); ?>">
		<?php
	}
	else{
		$shop_fullwidth = cartify_get_theme_option( 'shop_settings_general_fullwidth', '' );

		$shop_classes = apply_filters( 'agni_shop_container_classes', array(
			'shop-page-container', 
			!empty( $shop_fullwidth ) ? 'has-fullwidth' : '',
		));
		?>
		<div class="<?php echo esc_attr( cartify_prepare_classes( $shop_classes ) ); ?>">
		<?php
	}
	?>
	<?php
}

function cartify_woocommerce_single_close_tag(){
	?>
	</div>
	<?php
}

/*
function cartify_woocommerce_single_content_open_tag(){
	?>
	<div class="shop-single-page-content">
	<?php
}

function cartify_woocommerce_single_content_close_tag(){
	?>
	</div>
	<?php
}

function cartify_woocommerce_single_image_open_tag(){
	?>
	<div class="single-product-images">
	<?php
}
function cartify_woocommerce_single_image_close_tag(){
	?>
	</div>
	<?php
} */

function cartify_template_loop_thumbnail_open_tag(){
	?>
	<div class="product-thumbnail"><?php
}

function cartify_template_loop_thumbnail_close_tag(){
	?></div>
	<?php
}


function cartify_template_loop_cart_open_tag(){
	?>
	<div class="agni-add-to-cart"><?php
}

function cartify_template_loop_cart_close_tag(){
	?></div>
	<?php
}



// if (!function_exists('cartify_registered_menus_list')) {
//     /**
//      * Getting Registered Menus
//      */
//     function cartify_registered_menus_list($empty)
//     {
//         $menu_list = '';
//         if ($empty == true) {
//             $menu_list = array("" => "Inherit");
//         }
//         $menus = get_terms('nav_menu', array('hide_empty' => true));
//         foreach ($menus as $menu) {
//             $menu_list[$menu->slug] = $menu->name;
//         }
//         return $menu_list;
//     }
// }


function cartify_woocommerce_get_sidebar(){

	if( !is_product() ) {

		$shop_page_sidebar = cartify_get_theme_option( 'shop_settings_general_sidebar', '' );
		$shop_page_topbar = cartify_get_theme_option( 'shop_settings_general_topbar', '1' );

		if( $shop_page_sidebar || $shop_page_topbar ){
			$args = array();

			$filter_toggle = cartify_get_theme_option( 'shop_settings_filter_toggle', '' );
			$filter_content_choice = cartify_get_theme_option( 'shop_settings_filter_toggle_content', '' );

			if( $filter_toggle && $filter_content_choice === 'shop-sidebar' ){
				$args['additional_classes'] = 'agni-filter-toggle-content'; 
			}

			// if( $shop_page_sidebar != '' ){
				$args['has_sidebar'] = $shop_page_sidebar; 
			// }

			// if( $shop_page_topbar != '' ){
				$args['has_topbar'] = $shop_page_topbar; 
			// }

			get_sidebar( 'shop', $args );

			// get_sidebar( 'shop' );
		}

	}
}

function cartify_woocommerce_single_get_sidebar(){

	$single_page_sidebar = '1';//cartify_get_theme_option( 'shop_settings_single_sidebar', '' );

	if( is_product() && $single_page_sidebar ){
		get_sidebar( 'shop-single' );
	}
}

if( !function_exists( 'cartify_ajax_woocommerce_pagination' ) ){
	function cartify_ajax_woocommerce_pagination(){
		if (!check_ajax_referer('agni_woocommerce_nonce', 'security')) {
			return 'Invalid Nonce';
		}

		$options = isset( $_POST['options'] ) ? $_POST['options'] : array();
		$current_page_num = isset($options['current']) ? $options['current'] : '';
		$total_page_num = isset($options['total']) ? $options['total'] : '';

        // wp_reset_postdata();

				if(!function_exists('wc_get_products')) {
			return;
		}


		// $product_cat = $wp_query->get_queried_object();
		// $product_cat_name = $product_cat->name;

		// echo "options";
		// print_r( $options );
		// echo "ends";


			$paged                   = (int)$current_page_num + 1;
		$ordering                = WC()->query->get_catalog_ordering_args();
		// $ordering['orderby']     = array_shift(explode(' ', $ordering['orderby']));
		// $ordering['orderby']     = stristr($ordering['orderby'], 'price') ? 'meta_value_num' : $ordering['orderby'];
		$products_per_page       = apply_filters('agni_woocommerce_pagination_products_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page());

		$args = array(
			// 'meta_key'             => '_price',
			'status'               => 'publish',
			'limit'                => $products_per_page,
			'page'                 => $paged,
			'paginate'             => true,
			'return'               => 'ids',
			'orderby'              => $ordering['orderby'],
			'order'                => $ordering['order'],
		);

				if( !empty( $options['taxonomy'] ) && $options['taxonomy'] == 'product_cat' ){
			$args['category'] = array( $options['taxonomy_slug'] );
		}
		else if( !empty( $options['taxonomy'] ) && $options['taxonomy'] == 'product_tag' ){
			$args['tag'] = array( $options['taxonomy_slug'] );
		}

			$get_products       = wc_get_products($args);

		// print_r( $get_products );

			wc_set_loop_prop('current_page', $paged);
		wc_set_loop_prop('is_paginated', wc_string_to_bool(true));
		// wc_set_loop_prop('page_template', get_page_template_slug());
		wc_set_loop_prop('per_page', $products_per_page);
		wc_set_loop_prop('total', $get_products->total);
		wc_set_loop_prop('total_pages', $get_products->max_num_pages);

		ob_start();

				if($get_products->products) {
			foreach($get_products->products as $product) {

				$post_object = get_post($product);

				setup_postdata($GLOBALS['post'] =& $post_object);

				wc_get_template_part('content', 'product');
			}

			wp_reset_postdata();
		} 

		$products = ob_get_clean();

		wp_send_json_success(array( 
			'current_page_num' => (int)$current_page_num + 1, 
			'products' => $products
		));

		// echo 'I\'m page at ' . $current_page_num . 'total is ' . $total_page_num;

			wp_die();
	}
}

function cartify_woocommerce_get_topbar(){

	$shop_page_topbar = cartify_get_theme_option( 'shop_settings_general_topbar', '1' );
	$filter_toggle = cartify_get_theme_option( 'shop_settings_filter_toggle', '' );
	$filter_content_choice = cartify_get_theme_option( 'shop_settings_filter_toggle_content', '' );

	if( $shop_page_topbar != '' ){

		$sidebar_classes = array(
			'widget-area',
			'topbar',
			($filter_toggle && $filter_content_choice === 'topbar') ? 'agni-filter-toggle-content' : '',
		)

		?>
		<div class="<?php echo esc_attr( cartify_prepare_classes( $sidebar_classes ) ); ?>" role="complementary"><?php 
			dynamic_sidebar( 'cartify-sidebar-4' ); 
			?></div>
		<?php
	}

}

function cartify_woocommerce_breadcrumb(){
	if( !is_product() ){
		return woocommerce_breadcrumb();
	}
}
function cartify_woocommerce_breadcrumb_defaults(){
	return array(
		'delimiter'   => '',
		'wrap_before' => '<nav class="woocommerce-breadcrumb">',
		'wrap_after'  => '</nav>',
		'before'      => '<span>',
		'after'       => '</span>',
		'home'        => esc_html_x( 'Home', 'breadcrumb', 'cartify' ),
	);
}

function cartify_woocommerce_sidecart_shipping_text( $text ){

	if( !(is_cart() || is_checkout()) ){
		return esc_html__( 'Shipping options will be updated during checkout.', 'cartify' );
	}

	return $text;
}

function cartify_woocommerce_single_product_featured(){
	global $product;

	if( !$product->is_featured() ){
		return;
	}
	?>
	<div class="agni-product-featured-label"><span><?php echo esc_html__( "cartify's choice", 'cartify' ); ?></span></div>
	<?php
}

if( !function_exists('cartify_template_loop_product_category_description') ){
	function cartify_template_loop_product_category_description( $category ){

		if( empty( $category->description ) ){
			return;
		}
		?>

		<div class="woocommerce-loop-category__description"><?php echo esc_html( $category->description ); ?></div>

				<?php
	}
}

function cartify_woocommerce_template_loop_product_title() {
	echo '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">';
	echo woocommerce_template_loop_product_link_open();
	echo get_the_title();
	echo woocommerce_template_loop_product_link_close();
	echo '</h2>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}


if( !function_exists('cartify_woocommerce_products_loop_category_title') ){
	function cartify_woocommerce_products_loop_category_title(){

		$product_category = cartify_get_theme_option( 'shop_settings_general_product_category', '1' );
		if( $product_category != '1' ){
			if( is_shop() || is_product_category() || is_product_tag() || is_product() || is_tax( 'product_brand' )){
				return;
			}
		}

		?>
		<div class="woocommerce-loop-product__category">
			<?php echo wc_get_product_category_list( get_the_id() ); ?>
		</div>
		<?php

	}
}

if( !function_exists( 'cartify_woocommerce_product_hover_placeholder' ) ){
	function cartify_woocommerce_product_hover_placeholder(){
		?>
		<div class="product-bg-on-hover"></div>
		<?php
	}
}

if( !function_exists( 'cartify_woocommerce_label_outofstock' ) ){
	function cartify_woocommerce_label_outofstock(){
		global $product;

		if( $product->is_in_stock() ){
			return;
		}

		$availability = $product->get_availability();

				?>
		<span class="agni-product-outofstock"><?php echo esc_html( $availability['availability'] ); ?></span>
		<?php 
	}
}

if( !function_exists( 'cartify_woocommerce_label_hot' ) ){
	function cartify_woocommerce_label_hot( $conditional = false ){
		global $product;
		$products_list_hot = cartify_get_theme_option( 'shop_settings_label_hot', '' );

		if( empty( $products_list_hot ) || !in_array( $product->get_id(), $products_list_hot ) ){
			return;
		}

		if( $conditional === true ){
			return true;
		}

		?>
		<span class="agni-product-hot-label"><?php echo esc_html__( 'Hot!', 'cartify' ); ?></span>
		<?php
	}
}

if( !function_exists( 'cartify_woocommerce_label_new' ) ){
	function cartify_woocommerce_label_new( $conditional = false ){
		global $product;
		$products_list_new = cartify_get_theme_option( 'shop_settings_label_new', '' );

		if( empty( $products_list_new ) || !in_array( $product->get_id(), $products_list_new ) ){
			return;
		}

		if( $conditional === true ){
			return true;
		}

				?>
		<span class="agni-product-new-label"><?php echo esc_html__( 'New!', 'cartify' ); ?></span>
		<?php
	}
}



if( !function_exists( 'cartify_woocommerce_template_single_brand' ) ){
	function cartify_woocommerce_template_single_brand(){
		if( !class_exists( 'Agni_Cartify' ) ){
			return;
		}

		$taxonomy_slug = apply_filters('cartify_woocommerce_template_single_brand_taxonomy_slug', 'product_brand');

		$brands = get_the_terms( get_the_ID(), $taxonomy_slug ); 
		if( !$brands ){
			return;
		}

		foreach( $brands as $brand ){

			$agni_product_brand_icon_id = get_term_meta($brand->term_id, 'agni_product_brand_icon_id', true);

			?>
			<span class="agni-product-brand">
				<span class="agni-product-brand__by-text">
					<?php echo esc_html__( 'by', 'cartify' ); ?>
				</span>
				<a class="agni-product-brand__brand-name" href="<?php echo esc_url(get_term_link( $brand->slug, $taxonomy_slug )); ?>">
					<?php echo esc_html($brand->name); ?>
				</a>
				<span class="agni-product-brand__brand-logo">
					<?php echo wp_kses( wp_get_attachment_image( $agni_product_brand_icon_id, 'full' ), 'img' ); ?>
				</span>
			</span> 
			<?php
		}
	}
}


if( !function_exists('cartify_woocommerce_single_additional_info') ){
	function cartify_woocommerce_single_additional_info(){
		?>
		<div class="agni-product-additional-info"><?php 
			do_action( 'agni_woocommerce_single_product_additional_info' );
		?></div>
		<?php
	}
}

if( !function_exists('cartify_woocommerce_single_compare_button') ){
	function cartify_woocommerce_single_compare_button(){
		global $post;

		$products_list = get_post_meta( $post->ID, 'agni_product_data_compare', true );
		if( empty( $products_list ) ){
			return;
		}

		?>
		<div class="agni-single-compare-button">
			<a href="#agni-compare"><?php echo esc_html__( 'Compare with similar items', 'cartify' ); ?></a>
		</div>

				<?php
	}
}

if( !function_exists( 'cartify_woocommerce_after_single_product_title' ) ){

	// do_action( 'agni_woocommerce_after_single_product_title' );
	function cartify_woocommerce_after_single_product_title(){
		?>
		<div class="agni-single-product-after-title">
			<?php do_action( 'agni_woocommerce_after_single_product_title' ); ?>
		</div>
		<?php
	}
}

function cartify_woocommerce_single_additional_button_buynow(){
	global $product;

	if( !$product->is_type( 'external' ) ){
		?>
		<button class="single_buynow_button"><?php echo esc_html__( 'Buy now', 'cartify' ); ?></button>
		<?php
	} ?>
	<?php
}
function cartify_woocommerce_single_additional_button_wishlist(){
	global $product;
 ?>
	<?php echo esc_html__( 'Add to Wishlist', 'cartify' );  ?>
	<?php
}
function cartify_woocommerce_single_additional_button_compare(){
	global $product;
 ?>
	<?php echo esc_html__( 'Add to Compare', 'cartify' );  ?>
	<?php
}

if( !function_exists('cartify_woocommerce_recommended_products') ){
	function cartify_woocommerce_recommended_products(){
		?>
	Recommended Products
		<?php 
	}
}

if( !function_exists('cartify_woocommerce_bestselling_products') ){
	function cartify_woocommerce_bestselling_products(){
		?>
	Best Selling Products
		<?php 
	}
}

// Ensure cart contents update when products are added to the cart via AJAX.
function cartify_woocommerce_ajax_cart_fragment( $fragments ) {

		ob_start();
	?>		

	<?php echo esc_html( apply_filters('agni_ajax_header_cart_get_count', 'cartify_ajax_header_cart_get_count') ); ?>

	<?php
	$fragments['span.site-header-icon-cart__count'] = ob_get_contents(); 
	ob_end_clean();

	ob_start();
	?>		

	<?php echo wp_kses( apply_filters('agni_ajax_header_cart_get_amount', 'cartify_ajax_header_cart_get_amount'), array(
		'span' => array( 'class' => array() ),
		'bdi' => array()
	) ); ?>

	<?php
	$fragments['span.site-header-icon-cart__amount'] = ob_get_contents(); 
	ob_end_clean();

	return $fragments;
}


/**
 * Use single add to cart button for variable products.
 */
if( !function_exists( 'cartify_template_loop_add_to_cart' ) ){
	function cartify_template_loop_add_to_cart( $product = 0 ) {
		if( !$product ){
			global $product;
		}

				$variable_add_cart = cartify_get_theme_option( 'shop_settings_general_variable_add_to_cart', '1' );

		if ( ! $product->is_type( 'variable' ) || $variable_add_cart != '1' ) {
			woocommerce_template_loop_add_to_cart();
			return;
		}

		wp_enqueue_script( 'wc-add-to-cart-variation' );

		// add_action( 'woocommerce_single_variation', 'iconic_loop_variation_add_to_cart_button', 20 );
		?>

		<?php
		//$product->get_available_variations();
		$attributes = $product->get_variation_attributes();
		$available_variations = $product->get_available_variations();
		// echo '<pre>'; 
		// print_r($available_variations);
		// echo '</pre>';
		$attribute_keys  = array_keys( $attributes );
		$variations_json = wp_json_encode( $available_variations );
		$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );


		?>
		<form class="variations_form cart agni-products-variation-swatches" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo esc_attr( $variations_attr ); // WPCS: XSS ok. ?>">
			<table class="variations">
				<tbody>
					<?php foreach ( $attributes as $attribute_name => $options ) { 
						$random_number = rand(10000,99999);

												?>
						<tr>
							<td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) . '-' . $random_number ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
							<td class="value">
								<?php
									wc_dropdown_variation_attribute_options( array(
										'options'   => $options,
										'attribute' => $attribute_name,
										'product'   => $product,
										'id' => $attribute_name . '-' . $random_number,
									) );
									echo end( $attribute_keys ) === $attribute_name ? wp_kses( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'cartify' ) . '</a>' ), array( 'a' => array( 'class' => array(), 'href' => array() ) ) ) : '';
								?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="woocommerce-variation single_variation"></div>
			<div class="agni-products-variation-swatches__add-to-cart">
				<?php cartify_product_qty_update_html(); ?>
				<button type="submit" class="single_add_to_cart_button button disabled wc-variation-selection-needed"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
				<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
				<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
				<input type="hidden" name="variation_id" class="variation_id" value="0" />
			</div>
		</form>

	<?php
	}
}

function cartify_ajax_get_cart_item_key(){
	$product_id = $_POST['product_id'];

		foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
		if( $product_id == $cart_item['product_id'] ){
			wp_send_json( array( "key" => $cart_item_key, "qty" => $cart_item['quantity'] ) );

		}
	}

	wp_die();
}

function cartify_template_loop_qty_update(){
	global $product;

		if( !$product->is_type( 'simple' ) ){
		return;
	}

	cartify_product_qty_update_html();

	}

function cartify_product_qty_update_html(){
	global $product;

	// $archives_show_added_cart_qty = cartify_get_theme_option( 'shop_settings_general_show_qty', '1' ); //'1';

	$qty = apply_filters( 'agni_products_archives_qty_show', cartify_get_theme_option( 'shop_settings_general_show_qty', '' ) ); //'1';

		if( $product->is_in_stock() && $qty == '1' ){

		$product_id = $product->get_id();

		$cart_key = '';
		$cart_qty = 1;

				?>
		<div class="agni-update-cart" data-quantity="<?php echo esc_attr( $cart_qty ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-item-key="<?php  echo esc_attr( $cart_key ); ?>"><?php 
			woocommerce_quantity_input( array(
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
				'disabled'	  => true
			));

		?></div>
		<?php
	}
}


function cartify_woocommerce_ajax_template_loop_product_link_open($product_id) {
	global $product;
	$product = wc_get_product($product_id);

	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink($product_id), $product );

	echo '<a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
}

/**
 * Use single add to cart button for variable products.
 */
function cartify_ajax_template_loop_add_to_cart() {

	if (!check_ajax_referer('agni_woocommerce_nonce', 'security')) {
		return 'Invalid Nonce';
	}

	if( !isset($_POST['variation_id']) || !isset($_POST['product_id']) ){
		return;
	}

	$variation_id = $_POST['variation_id'];
	$product_id = $_POST['product_id'];

	global $product;
	$product = wc_get_product( $product_id );
    //$variation = new WC_Product_Variation($variation_id);
    $available_variations = $product->get_available_variations();
    $variation_image_id = '';
	//print_r($available_variations);
    ?>
    <?php

	// $variation_image_id
	// $available_variation_images_ids

	$product_thumbnail_style = apply_filters( 'agni_product_archive_thumbnail_style', cartify_get_theme_option( 'shop_settings_general_thumbnail_choice', 1 ) );

		$product_thumbnail_ids = array();

    $available_variation_images_ids = array();
    foreach( $available_variations as $variation ){

		        if( $variation_id == $variation['variation_id']){
			$product_thumbnail_ids[] = $variation['image_id'];


						if( $product_thumbnail_style == 2 ){
				$attachment_ids = $product->get_gallery_image_ids();
				if( !empty($variation['agni_variation_images']) ){
					$product_thumbnail_ids[] = $variation['agni_variation_images'][0];
				}
			}
			else if( $product_thumbnail_style == 3 ){

						wp_enqueue_style('slick');
				wp_enqueue_script('slick');

								foreach($variation['agni_variation_images'] as $additional_variation_image_id){
					$product_thumbnail_ids[] = $additional_variation_image_id;
				}
			}


                    }
	}

		// print_r($product_thumbnail_ids);
	?>
	<div class="woocommerce-loop-product__thumbnail style-<?php echo esc_attr($product_thumbnail_style); ?>">
		<?php
		foreach( $product_thumbnail_ids as $thumbnail_id ){
			echo cartify_woocommerce_ajax_template_loop_product_link_open( $product_id );
			echo apply_filters( 'agni_woocommerce_archives_product_image_thumbnail_html', wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail'), $thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
			echo woocommerce_template_loop_product_link_close();
		} ?>
	</div>


	<?php

die();

}

function cartify_ajax_template_loop_add_to_cart_reset(){
	if (!check_ajax_referer('agni_woocommerce_nonce', 'security')) {
		return 'Invalid Nonce';
	}

	if( !isset($_POST['product_id']) ){
		return;
	}

	$product_id = $_POST['product_id'];	
	$product = wc_get_product( $product_id );

	//$post_thumbnail_id = $product->get_image_id();
	$attachment_ids = $product->get_gallery_image_ids();

	$product_thumbnail_style = apply_filters( 'agni_product_archive_thumbnail_style', cartify_get_theme_option( 'shop_settings_general_thumbnail_choice', 1 ) );

	$product_thumbnail_ids = array();

	if( $product ){
		$product_thumbnail_ids[] = $product->get_image_id();

				}

	if( $product_thumbnail_style == 2 ){
		$attachment_ids = $product->get_gallery_image_ids();
		if( !empty($attachment_ids) ){
			$product_thumbnail_ids[] = $attachment_ids[0];
		}
	}
	else if( $product_thumbnail_style == 3 ){

		wp_enqueue_style('slick');
		wp_enqueue_script('slick');

				$attachment_ids = $product->get_gallery_image_ids();
		if( $attachment_ids && $product->get_image_id() ){
			foreach( $attachment_ids as $attachment_id ){
				$product_thumbnail_ids[] = $attachment_id;
			}
		}
	}

	?>
	<div class="woocommerce-loop-product__thumbnail style-<?php echo esc_attr($product_thumbnail_style); ?>">
		<?php
		foreach( $product_thumbnail_ids as $thumbnail_id ){
			echo cartify_woocommerce_ajax_template_loop_product_link_open( $product_id );
			echo apply_filters( 'agni_woocommerce_archives_product_image_thumbnail_html', wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail'), $thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
			echo woocommerce_template_loop_product_link_close();
		} ?>
	</div>


		<?php
	die();

}


// function cartify_products_view_( $cols ) {
// 	$cols = $_GET['count'];

// 	return $cols;
// }

// if( !function_exists('cartify_products_view_processing') ){
// 	function cartify_products_view_processing(){
// 		if( !isset($_GET['view']) ){
// 			return;
// 		}

// 	}
// }


if( !function_exists( 'cartify_woocommerce_column_switcher' ) ){
	function cartify_woocommerce_column_switcher(){

		if( !is_shop() && !is_product_category() && !is_product_tag() && !is_tax( 'product_brand' ) ){
			return;
		}

		$view_query_string_list = 'list';
		$view_query_string_grid = 'grid';

		if( isset($_GET['view'])){
			$view = $_GET['view'];
		}

		?>
		<div class="agni-view-switcher">
			<span class="agni-view-switcher__button--list<?php echo esc_attr( (isset($view) && $view == $view_query_string_list )?' active':'' ); ?>"><a href="<?php echo esc_url( add_query_arg( 'view', $view_query_string_list ) ); ?>" class="agni-view-switcher__link"><?php echo esc_html__('List', 'cartify'); ?></a></span>
			<span class="agni-view-switcher__button--grid<?php echo esc_attr( (isset($view) && $view == $view_query_string_grid )?' active':'' ); ?>"><a href="<?php echo esc_url( add_query_arg( 'view', $view_query_string_grid ) ); ?>" class="agni-view-switcher__link"><?php echo esc_html__('Grid', 'cartify'); ?></a></span>
		</div>
		<?php

	}
}



function cartify_products_count_per_page( $cols ) {
	$cols = $_GET['count'];

	return $cols;
}

if( !function_exists('cartify_products_count_processing') ){
	function cartify_products_count_processing(){
		if( !isset($_GET['count']) ){
			return;
		}

				add_filter( 'loop_shop_per_page', 'cartify_products_count_per_page', 20 );
	}
}

if( !function_exists( 'cartify_woocommerce_filter_toggle' ) ){
	function cartify_woocommerce_filter_toggle(){

		if( !is_shop() && !is_product_category() && !is_product_tag() && !is_tax( 'product_brand' ) ){
			return;
		}

		// $filter_toggle = cartify_get_theme_option( 'shop_settings_filter_toggle', '' );

		// if( $filter_toggle == '' ){
		// 	return;
		// }

		$sidebar = cartify_get_theme_option( 'shop_settings_general_sidebar', '' );
		$topbar = cartify_get_theme_option( 'shop_settings_general_topbar', '' );
		$filter_text = cartify_get_theme_option( 'shop_settings_filter_toggle_text', esc_html__( 'Filters', 'cartify' ) );
		$filter_toggle = cartify_get_theme_option( 'shop_settings_filter_toggle', '' );
		$filter_content_choice = cartify_get_theme_option( 'shop_settings_filter_toggle_content', 'topbar' );

		if( $topbar == '' && $sidebar == '' ){
			return;
		}

		$filter_classes = array(
			'agni-filter-toggle',
			$filter_toggle ? 'show-on-desktop' : ''
		);

		?>
		<div class="<?php echo esc_attr( cartify_prepare_classes( $filter_classes ) ); ?>">
		<?php if( $filter_toggle == '' ){ ?>
			<a href="#"><?php echo esc_html( $filter_text ); ?></a>
		<?php }
		else{ ?>
			<a href="#" data-content-class="<?php echo esc_attr( $filter_content_choice ); ?>"><?php echo esc_html( $filter_text ); ?></a>
		<?php } ?>
		</div>
		<?php
	}
}

if( !function_exists('cartify_woocommerce_prducts_count_switcher') ){
	function cartify_woocommerce_prducts_count_switcher(){
		if( !is_shop() && !is_product_category() && !is_product_tag() && !is_tax( 'product_brand' ) ){
			return;
		}

		if( isset( $_GET['count'] ) ){
			$count = $_GET['count'];
		}

		global $wp_query;
		$total_products = $wp_query->found_posts;


		$count_1x = wc_get_loop_prop( 'columns' ) * get_option('woocommerce_catalog_rows', 4);
		$count_2x = 2 * $count_1x;
		$count_3x = 3 * $count_1x;

				$count_query_string_array = [$count_1x, $count_2x, $count_3x];
		$count_show_all = 1;


		if( $total_products <= $count_1x ){
			return;
		}


		?>
		<div class="agni-count-switcher">
			<?php foreach( $count_query_string_array as $count_query_string ){ ?>
				<span class="agni-count-switcher__button<?php echo esc_attr( (isset($count) && $count == $count_query_string )?' active':'' ); ?>"><a href="<?php echo esc_url( add_query_arg( 'count', $count_query_string ) ); ?>" data-count="<?php echo esc_attr($count_query_string); ?>"><?php echo esc_html( $count_query_string ); ?></a></span>

			<?php } ?>

			<?php if( $count_show_all ){ ?>
				<span class="agni-count-switcher__button all"><a href="<?php echo esc_url( add_query_arg( 'count', '-1' ) ); ?>"  data-count="-1"><?php echo esc_html__( 'All', 'cartify' ); ?></a></span>
			<?php } ?>
		</div>
		<?php
	}
}

if( !function_exists('cartify_woocommerce_category_bar') ){

		/**
	 * displaying category bar at the top of products
	 */
	function cartify_woocommerce_category_bar(){

		if( !is_shop() && !is_product_category() ){
			return;
		}

				$categories_bar = cartify_get_theme_option( 'shop_settings_general_categories_bar_show', '1' );

		if( empty( $categories_bar ) ){
			return;
		}


		$taxonomy     = 'product_cat';
		$orderby      = 'name';  
		$show_count   = 0;      // 1 for yes, 0 for no
		$pad_counts   = 0;      // 1 for yes, 0 for no
		$hierarchical = 1;      // 1 for yes, 0 for no  
		$empty        = 0;

		$has_carousel = true;

		$args = array(
			'taxonomy'     => $taxonomy,
			'orderby'      => $orderby,
			'show_count'   => $show_count,
			'pad_counts'   => $pad_counts,
			'hierarchical' => $hierarchical,
			'hide_empty'   => $empty
		);


		if( is_product_category() ){
			$current_category = get_queried_object();
			$current_category_id = $current_category->term_id;

			$args['child_of'] = 0;
			$args['parent'] = $current_category_id;

		}

		$categories = get_categories( $args );

		$category_bar_list_classes = array(
			'agni-shop-categories-bar__list',
			// ( !empty( $has_carousel ) && $has_carousel ) ? 'has-categories-bar-carousel' : '',
			( !empty( $has_carousel ) && $has_carousel ) ? 'has-categories-bar-scroll' : '',
		);

		// if( $has_carousel ){
		// 	wp_enqueue_style('slick');
		// 	wp_enqueue_script('slick');
		// }

		if(!empty($categories)){
			?>
			<div class="agni-shop-categories-bar has-scroll-navigation">
				<div class="agni-shop-categories-bar__title"><?php echo esc_html__( 'Shop by Categories', 'cartify' ); ?></div>
				<div class="agni-shop-categories-bar__contents">
					<div class="agni-shop-categories-bar__container">
						<ul class="<?php echo esc_attr( cartify_prepare_classes( $category_bar_list_classes ) ); ?>">
							<?php
							foreach ($categories as $cat) {
								if($cat->category_parent == 0 || is_product_category()) {
									$category_id = $cat->term_id;       
									?>
									<li class="agni-shop-categories-bar__item"><a href="<?php echo esc_url(get_term_link($cat->slug, 'product_cat')); ?>"><?php echo esc_html($cat->name); ?></a></li>
									<?php 

																}       
							}
							?>
						</ul>
					</div>
					<div class="agni-shop-categories-bar-nav hide">
						<span class='agni-shop-categories-bar-nav-left nav-left'><i class="lni lni-chevron-left"></i></span>
						<span class='agni-shop-categories-bar-nav-right nav-right'><i class="lni lni-chevron-right"></i></span>
					</div>
				</div>
			</div>
			<?php
		}

		/* ?>
		<div class="agni-shop-categories-bar">
			<div class="agni-shop-categories-bar__title"><?php echo esc_html__( 'Shop by Categories', 'cartify' ); ?></div>
			<?php

			$taxonomy     = 'product_cat';
			$orderby      = 'name';  
			$show_count   = 0;      // 1 for yes, 0 for no
			$pad_counts   = 0;      // 1 for yes, 0 for no
			$hierarchical = 1;      // 1 for yes, 0 for no  
			$empty        = 0;

			if( is_product_category() ){
				$current_category = get_queried_object();
				$current_category_id = $current_category->term_id;

				$args = array(
					'taxonomy'     => $taxonomy,
					'child_of'     => 0,
					'parent'       => $current_category_id,
					'orderby'      => $orderby,
					'show_count'   => $show_count,
					'pad_counts'   => $pad_counts,
					'hierarchical' => $hierarchical,
					'hide_empty'   => $empty
				);
				$sub_categories = get_categories( $args );

				if(!empty( $sub_categories )) {
					?>
					<ul class="agni-shop-categories-bar__list">
						<?php
						foreach($sub_categories as $sub_category) {
						?>
							<li class="agni-shop-categories-bar__item"><a href="<?php echo esc_url(get_term_link($sub_category->slug, 'product_cat')); ?>"><?php echo esc_html($sub_category->name); ?></a></li>
						<?php
						}   
						?>
					</ul>
					<?php
				}
			}
			else{

								$args = array(
					'taxonomy'     => $taxonomy,
					'orderby'      => $orderby,
					'show_count'   => $show_count,
					'pad_counts'   => $pad_counts,
					'hierarchical' => $hierarchical,
					'hide_empty'   => $empty
				);
				$all_categories = get_categories( $args );

				if(!empty($all_categories)){
					?>
					<ul class="agni-shop-categories-bar__list">
						<?php
						foreach ($all_categories as $cat) {
							if($cat->category_parent == 0) {
								$category_id = $cat->term_id;       
								?>
								<li class="agni-shop-categories-bar__item"><a href="<?php echo esc_url(get_term_link($cat->slug, 'product_cat')); ?>"><?php echo esc_html($cat->name); ?></a></li>
								<?php 

														}       
						}
						?>
					</ul>
					<?php
				}
			}

						?>
		</div>
		<?php */
	}
}

if( !function_exists( 'cartify_woocommerce_category_content_block' ) ){
	function cartify_woocommerce_category_content_block(){

		if( !class_exists('WooCommerce') ){
			return;
		}

		if( !(is_product_category() || is_product_tag() || is_tax('product_brand')) ){
			return;
		}

		$term = get_queried_object();
		$term_id = $term->term_id;

		$content_block_id = esc_attr( get_term_meta($term_id, 'agni_product_cat_content_block', true) );

		if( !empty( $content_block_id ) ){
			?>
			<div class="agni-product-category-block">
				<?php
				echo apply_filters( 'agni_content_block', $content_block_id );
				?>
			</div>
			<?php
		}

			}
}

if( !function_exists('cartify_woocommerce_shop_slider') ){
	function cartify_woocommerce_shop_slider(){
		if( !class_exists('WooCommerce') ){
			return;
		}

				if( is_product_category() || is_product_tag() || is_tax('product_brand') ){
			return;
		}

				$page_id = get_the_ID();

		if( is_shop() ){
			$page_id = wc_get_page_id('shop');
		}

				// $function_name = 'get_post_meta';

		// if( is_product_category() || is_product_tag() || is_tax('product_brand') ){
		// 	$term = get_queried_object();

		// 	$function_name = 'get_term_meta';
		// 	$page_id = $term->term_id;
		// }

		// $page_title_hide = esc_attr( get_post_meta($page_id, 'agni_page_title_hide', true) );
		// $page_title_align = esc_attr( get_post_meta($page_id, 'agni_page_title_align', true) );
		// $page_bg_color = esc_attr( get_post_meta($page_id, 'agni_page_bg_color', true) );
		// $page_bg_gradient = esc_attr( get_post_meta($page_id, 'agni_page_bg_gradient', true) );
		// $page_margin_remove = esc_attr( get_post_meta($page_id, 'agni_page_margin_remove', true) );

		// $page_footer_choice = esc_attr( get_post_meta($page_id, 'agni_footer_block_id', true) );
		// $page_sidebar_choice = esc_attr( get_post_meta($page_id, 'agni_page_sidebar_choice', true) );

		$slider_id = esc_attr( get_post_meta($page_id, 'agni_slider_id', true) );

		?>
		<?php if( $slider_id !== '' ){ ?>
			<?php do_action( 'agni_slider', $slider_id ); ?>
		<?php } ?>
		<?php

	}
}

if( !function_exists( 'cartify_woocommerce_products_header_classes' ) ){
	function cartify_woocommerce_products_header_classes( $classes ){

		if( !class_exists('WooCommerce') ){
			return $classes;
		}

		if( !(is_product_category() || is_product_tag() || is_tax('product_brand')) ){
			return $classes;
		}

		$term = get_queried_object();
		$term_id = $term->term_id;

		if( is_product_category() ){
			$banner_image_id = esc_attr( (int)get_term_meta($term_id, 'agni_product_cat_banner_image_id', true) );
			$banner_content_bg = get_term_meta($term_id, 'agni_product_cat_banner_content_bg', true);
		}
		else if( is_product_tag() ){
			$banner_image_id = esc_attr( (int)get_term_meta($term_id, 'agni_product_tag_banner_image_id', true) );
			$banner_content_bg = get_term_meta($term_id, 'agni_product_tag_banner_content_bg', true);
		}
		else if( is_tax('product_brand') ){
			$banner_image_id = esc_attr( (int)get_term_meta($term_id, 'agni_product_brand_banner_image_id', true) );
			$banner_content_bg = get_term_meta($term_id, 'agni_product_brand_banner_content_bg', true);
		}

				if( !empty( $banner_image_id ) ){
			$classes[] = 'has-banner-image';


			$styles = '';

			$styles .= '.has-banner-image{
				--cartify_products_header_banner_image: url("' . esc_url( wp_get_attachment_image_url( $banner_image_id, 'full' ) ) . '");
				--cartify_products_header_banner_content_bg_color: ' . $banner_content_bg . ';
			}';

			wp_enqueue_style( 'cartify-custom-style' );
			wp_add_inline_style( 'cartify-custom-style', $styles );

		}

		return $classes;
	}
}

if( !function_exists('cartify_woocommerce_shop_page_title') ){
	function cartify_woocommerce_shop_page_title($show){
		if( !class_exists('WooCommerce') || !is_shop() ){
			return $show;
		}

				$page_id = wc_get_page_id('shop');

		$page_title_hide = esc_attr( get_post_meta($page_id, 'agni_page_title_hide', true) );

		if( $page_title_hide == 'on' ){
			$show = false;
		}

		return $show;
	}
}

if( !function_exists('cartify_woocommerce_get_star_rating_html') ){
	function cartify_woocommerce_get_star_rating_html( $html, $rating, $count ){

		$rating_class = str_replace( '.', '-', $rating );

		$styles ="
			.star-rating__star-{$rating_class}{
				--woocommerce-rating-width: calc(({$rating} / 5) * 100%);
			}
			.star-rating__star-{$rating_class}:after{
				width: var(--woocommerce-rating-width);
			}
		";

		wp_enqueue_style( 'cartify-custom-style' );
		wp_add_inline_style( 'cartify-custom-style', $styles );

		$html = '';
		$html = '<span class="star-rating__star star-rating__star-'. esc_attr( $rating_class ) .'"></span>';

				if ( 0 < $count ) {

			$html .= '<span class="star-rating__text"><span>' . esc_html( number_format((float)$rating, 2, '.', '') ) . '</span>/<span>5.00</span></span>';

					$html .= '<span class="star-rating__count">'; 
			/* translators: 1: rating 2: rating count */
			$html .= sprintf( _n( '%s customer rating', '%s customer ratings', $count, 'cartify' ), '<span class="rating">' . esc_html( $count ) . '</span>' );
			$html .= '</span>';
		} 

		return $html;
	}

}

if( !function_exists('cartify_woocommerce_single_reviews_histogram') ){
	/**
	 * displaying reviews histogram
	 *
	 * @return void
	 */
	function cartify_woocommerce_single_reviews_histogram(){
		global $product;


				$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();


		if( !$rating_count > 0 ){
			return;
		}


		?>

		<div class="reviews-container">
			<div class="reviews-rating-avg"><?php echo esc_html($average); ?></div>
			<div class="reviews-stars-container">
				<span class="reviews-stars-avg"><?php echo wc_get_rating_html( $average ) // WordPress.XSS.EscapeOutput.OutputNotEscaped. ?></span>
				<span class="reviews-stars-text"><?php printf( _n( '%s review', '%s reviews', $review_count, 'cartify' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?></span>
			</div>
		</div>
		<div class="ratings-histogram-container">
			<?php
				for ($i=1; $i <= 5; $i++) { 
					$percentage = $product->get_rating_count($i) * 100 / $rating_count;


					$styles ="
						.rating-container:nth-child({$i}) .rating-percentage-bar{
							width: {$percentage}%;
						}
					";

					wp_enqueue_style( 'cartify-custom-style' );
					wp_add_inline_style( 'cartify-custom-style', $styles );

					?>
					<div class="rating-container">
						<span class="rating-text"><?php printf( _n( '%s star', '%s stars', $i, 'cartify' ), '<span class="count">' . esc_html( $i ) . '</span>' ); ?></span>
						<span class="rating-percentage"><span class="rating-percentage-bar"><?php echo esc_html($percentage); ?></span></span>
						<span class="rating-count"><?php echo esc_html($product->get_rating_count($i)); ?></span>
					</div>
					<?php
				}
			?>
		</div>
		<a class="comments-link-button" href="#comments"><?php echo esc_html__( 'See all reviews', 'cartify' ); ?></a>
		<?php

	}
}


if( !function_exists('cartify_woocommerce_pagination') ){
	function cartify_woocommerce_pagination(){

		$total   = wc_get_loop_prop( 'total_pages' );
		$current = wc_get_loop_prop( 'current_page' );

		$term = get_queried_object();

		$shop_pagination_style = cartify_get_theme_option( 'shop_settings_general_pagination', '1' );


		$pagination_classes = cartify_prepare_classes(array(
			'agni-woocommerce-pagination',
			'has-display-style-' . $shop_pagination_style
		));

		$options = array(
			'current' => $current,
			'total' => $total
		);

		if( !empty( $term->taxonomy ) ){
			$options['taxonomy'] = $term->taxonomy;
			$options['taxonomy_slug'] = $term->slug;
			$options['taxonomy_id'] = $term->term_id;
		}



		$options['category'] = cartify_get_theme_option( 'shop_settings_general_product_category', '1' );
		$options['quickview'] = cartify_get_theme_option( 'shop_settings_general_product_quickview', '1' );
		$options['compare'] = cartify_get_theme_option( 'shop_settings_general_product_compare', '1' );
		$options['stock'] = cartify_get_theme_option( 'shop_settings_general_product_stock', '1' );
		$options['countdown'] = cartify_get_theme_option( 'shop_settings_general_product_countdown', '1' );


		?>
		<div class="<?php echo esc_attr( $pagination_classes ); ?>">
			<?php if( $shop_pagination_style == '2' ){ ?>
				<a class="agni-woocommerce-pagination-infinite" href="#" data-current-page-num="<?php echo esc_attr($current); ?>" data-total-page-num="<?php echo esc_attr($total); ?>" data-infinite-options="<?php echo esc_attr( json_encode($options) ); ?>">
					<span><?php echo esc_html__( 'Load More', 'cartify' ) ?></span>
					<span><?php echo esc_html__( 'Loading', 'cartify' ) ?></span>
				</a>

						<?php }
			else { ?>
				<?php if( get_previous_posts_link() ){ ?>
					<span class="agni-woocommerce-pagination__prev"><a href="<?php echo esc_url( get_previous_posts_page_link() ); ?>"><span><i class="lni lni-chevron-left"></i></span><span><?php echo esc_html__( 'Previous', 'cartify' ); ?></span></a></span>
				<?php } ?>

				<span class="agni-woocommerce-pagination__contents">
					<span class="agni-woocommerce-pagination__current"><input class="agni-woocommerce-pagination__input" type="number" min="1" max="<?php echo esc_attr($total); ?>" value="<?php echo esc_attr($current); ?>"></span>
					<span class="agni-woocommerce-pagination__count-text"><?php echo sprintf( esc_html__( 'of %s Pages', 'cartify' ), esc_html($total) ); ?></span>
				</span>

								<?php if( get_next_posts_link() ){ ?>
					<span class="agni-woocommerce-pagination__next"><a href="<?php echo esc_url( get_next_posts_page_link() ); ?>"><span><i class="lni lni-chevron-right"></i></span><span><?php echo esc_html__( 'Next', 'cartify' ); ?></span></a></span>
				<?php } ?>
			<?php } ?>
		</div>
		<?php
	}
}


if( !function_exists('cartify_woocommerce_block_short_description') ){
	function cartify_woocommerce_block_short_description(){
		global $product;

		?><div class="woocommerce-loop-product__description"><?php 
			echo wp_kses_post( $product->get_short_description() ); 
		?></div><?php 
	}
}

if( !function_exists('cartify_woocommerce_short_description') ){
	function cartify_woocommerce_short_description(){
		global $product;

		$show_description = cartify_get_theme_option( 'shop_settings_general_show_desc', '' );

		if( !$show_description ){
			return;
		}

		?><div class="woocommerce-loop-product__description"><?php 
			echo wp_kses_post( $product->get_short_description() ); 
		?></div><?php 
	}
}

if( !function_exists('cartify_woocommerce_instock_indicator') ){
	function cartify_woocommerce_instock_indicator(){
		global $product;

				$product_stock = cartify_get_theme_option( 'shop_settings_general_product_stock', '1' );
		if( $product_stock != '1' ){
			if( is_shop() || is_product_category() || is_product_tag() || is_product() || is_tax( 'product_brand' ) ){
				return;
			}
		}

		$instock = get_post_meta( $product->get_id(), '_stock', true );
		$total_sales = get_post_meta( $product->get_id(), 'total_sales', true );

		if( !$instock ){
			return;
		}


		$total_stock = $total_sales + $instock;
		$instock_percentage = round($total_sales*100/$total_stock, 2);
		// $total_available = $instock - $total_sales;

		$styles ="
			.post-{$product->get_id()} .agni-stock-indicator__progressbar >span{
				width: {$instock_percentage}%;
			}
		";

		wp_enqueue_style( 'cartify-custom-style' );
		wp_add_inline_style( 'cartify-custom-style', $styles );

		?>
		<div class="agni-stock-indicator">
			<div class="agni-stock-indicator__progressbar"><span></span></div>
			<div class="agni-stock-indicator__text"><span><?php echo esc_html__( 'Sold - ', 'cartify' ); ?></span><span><?php echo esc_html( $total_sales ); ?>/<?php echo esc_html( $total_stock ); ?></span></div>
		</div>
		<?php 
	}
}


function cartify_woocommerce_template_single_stock_html(){

	global $product;

	?>
	<?php 

	$availability = $product->get_availability();

		if( $product->is_in_stock() ){
		if( $product->managing_stock() ){
			echo wc_get_stock_html( $product );
		}
		else{
			?>
			<div class="stock"><?php echo wp_kses_post( $availability['availability'] ); ?></div>
			<?php
		}
	}
	else{
		echo wc_get_stock_html( $product );
	}

		echo cartify_woocommerce_instock_indicator();
	?>
	<?php
}

if( !function_exists( 'cartify_woocommerce_custom_stock_text' ) ){
	function cartify_woocommerce_custom_stock_text( $availability, $_product ) {

				// Change In Stock Text
		if ( $_product->is_in_stock() ) {
			if( !$_product->managing_stock() ){
				$availability['availability'] = esc_html__('In stock', 'cartify');
			}
			else{
				$availability['availability'] =  sprintf( esc_html__('%s In stock', 'cartify'), $_product->get_stock_quantity());
			}
		}

			return $availability;
	}
}

// Sale Flash filter
if( !function_exists('cartify_woocommerce_custom_sale_flash') ){
	function cartify_woocommerce_custom_sale_flash(){

		global $product;

		// $sale_flash_choice_default = '1';
		// $product_thumbnail_style = cartify_get_theme_option( 'shop_settings_general_thumbnail_choice', $product_thumbnail_style_default );

		$sale_flash_choice = apply_filters( 'agni_product_single_sales_flash_choice', '1' );
		$sale_flash_text_prefix = '-';
		$sale_flash_text_suffix = ' off.';

		$product_regular_price = $product->get_regular_price();
		$product_sale_price = $product->get_sale_price();

		$sale_flash = esc_html__( 'Sale!', 'cartify' );

		if( $sale_flash_choice == '2' && $product->is_type( 'simple' ) ){
			$sale_flash = $sale_flash_text_prefix . (round((($product_regular_price - $product_sale_price)*100)/$product_regular_price)).'%' . $sale_flash_text_suffix;
		}
		else if( $sale_flash_choice == '3' && $product->is_type( 'simple' ) ){
			$sale_flash = $sale_flash_text_prefix . get_woocommerce_currency_symbol().round($product_regular_price - $product_sale_price) . $sale_flash_text_suffix;
		}

		return '<span class="onsale">' . esc_html( $sale_flash ) . '</span>';
	}
}



if( !function_exists('cartify_woocommerce_sale_countdown') ){
	function cartify_woocommerce_sale_countdown(){
		global $product;

		$product_countdown = cartify_get_theme_option( 'shop_settings_general_product_countdown', '1' );
		if( $product_countdown != '1' ){
			if( is_shop() || is_product_category() || is_product_tag() || is_tax( 'product_brand' ) ){
				return;
			}
		}

		if( $product->is_type( 'variable' ) || $product->is_type( 'grouped' ) ){
			return;
		}

		echo cartify_woocommerce_countdown_timer( $product->get_id(), $style = '2' );

				?>
		<?php 
	}
}

if( !function_exists('cartify_woocommerce_countdown_timer') ){
	function cartify_woocommerce_countdown_timer($product_id, $style = '1'){

				$style = $style;
		$sales_price_to = get_post_meta($product_id, '_sale_price_dates_to', true);
		$sales_price_from = get_post_meta($product_id, '_sale_price_dates_from', true);

		if( $sales_price_from > current_time( 'timestamp' ) || $sales_price_to <= current_time( 'timestamp' ) ){
			return;
		}
		?><div class="agni-sale-countdown style-<?php echo esc_attr($style); ?>" data-countdown-startdate="<?php echo esc_attr( $sales_price_from ); ?>" data-countdown-enddate="<?php echo esc_attr( $sales_price_to ); ?>">
			<div class="agni-sale-countdown-container">
				<div class="agni-sale-countdown-holder">
					<div class="agni-sale-countdown-holder--days">
						<span class="days"></span>
						<div class="agni-sale-countdown-holder__label"><?php echo esc_html__('Days', 'cartify'); ?></div>
					</div>
					<div class="agni-sale-countdown-holder--hours">
						<span class="hours"></span>
						<div class="agni-sale-countdown-holder__label"><?php echo esc_html__('Hrs', 'cartify'); ?></div>
					</div>
					<div class="agni-sale-countdown-holder--minutes">
						<span class="minutes"></span>
						<div class="agni-sale-countdown-holder__label"><?php echo esc_html__('Mins', 'cartify'); ?></div>
					</div>
					<div class="agni-sale-countdown-holder--seconds">
						<span class="seconds"></span>
						<div class="agni-sale-countdown-holder__label"><?php echo esc_html__('Secs', 'cartify'); ?></div>
					</div>
				</div>
			</div>
		</div><?php
	}
}

if( !function_exists( 'cartify_woocommerce_single_product_sale_countdown' ) ){
	function cartify_woocommerce_single_product_sale_countdown(){
		global $product;

		if( !is_single() || $product->is_type( 'variable' ) || $product->is_type( 'grouped' ) ){
			return;
		}

		$countdown_style = apply_filters( 'cartify_woocommerce_single_product_sale_countdown_display_style', '1' );

		cartify_woocommerce_countdown_timer( $product->get_id(), $countdown_style );


			}
}

function cartify_ajax_woocommerce_sale_countdown(){
	if( !isset($_POST['variation_id']) ){
		return;
	}

	cartify_woocommerce_countdown_timer($_POST['variation_id']);

		?>
	<?php 
	die();
}


if( !function_exists('cartify_woocommerce_single_product_video') ){
	function cartify_woocommerce_single_product_video(){

		global $product;

		$embed_video_url = get_post_meta( $product->get_id(), 'agni_product_data_video_embed_url', true );

		if( empty($embed_video_url) ){
			return;
		}

		wp_enqueue_style('cartify-photoswipe-style');
		wp_enqueue_script('cartify-photoswipe-script');

		?>
		<div class="agni-product-video">
			<a href="#" class="agni-product-video__button" data-modal="<?php echo esc_html( $embed_video_url ); ?>"><?php echo apply_filters('agni_woocommerce_product_video_icon', cartify_get_icon_svg('product', 'play')); ?><span><?php echo esc_html__( 'Play video', 'cartify' ); ?></span></a>
		</div>
		<?php
	}
}

if( !function_exists('cartify_woocommerce_single_product_360_image') ){
	function cartify_woocommerce_single_product_360_image(){

		global $product;

		$threesixty_images = get_post_meta( $product->get_id(), 'agni_product_data_threesixty_images', true );

				if( empty($threesixty_images) ){
			return;
		}
		// print_r( $threesixty_images );
		wp_enqueue_script('threesixty');

		//$threesixty_images = json_decode( $threesixty_images_json[0], true );
		$threesixty_images_url = array();

				foreach( $threesixty_images as $value ){
			$threesixty_images_url[] = $value['url'];
			$threesixty_images_id[] = $value['id'];
		}
		$threesixty_images_src = array_values($threesixty_images_url);
		$threesixty_image_metadata = wp_get_attachment_metadata($threesixty_images_id[0]);

		$threesixty_data_options = array(
			"count" => count( $threesixty_images_src ),
			"extension" => pathinfo($threesixty_images_src[0], PATHINFO_EXTENSION),
			// "width" => $threesixty_image_metadata['width'],
			// "height" => $threesixty_image_metadata['height'],
			"src" => $threesixty_images_src,
		);

		if( isset( $threesixty_image_metadata['width'] ) && !empty( $threesixty_image_metadata['width'] ) ){
			$threesixty_data_options['width'] = $threesixty_image_metadata['width'];
		}

		if( isset( $threesixty_image_metadata['height'] ) && !empty( $threesixty_image_metadata['height'] ) ){
			$threesixty_data_options['height'] = $threesixty_image_metadata['height'];
		}

				?>
		<div class="agni-threesixty">
			<a href="#" class="agni-threesixty__button"><?php echo apply_filters('agni_woocommerce_threesixty_icon', cartify_get_icon_svg('product', '360degree')); ?><span><?php echo wp_kses( __( '360<sup>o</sup> image', 'cartify' ), array( 'sup' => array() ) ); ?></span></a>
			<div class="agni-threesixty__container">
				<div class="agni-threesixty__overlay"></div>
				<div class="agni-threesixty__contents">
					<?php //do_action( 'agni_woocommerce_threesixty_images' ); ?>
					<div id="threesixty" class="threesixty threesixty-container" data-360="<?php echo esc_attr( json_encode( $threesixty_data_options ) ); ?>">
						<ol class="threesixty_images"></ol>
						<div class="agni-threesixty__nav">
							<span class="agni-threesixty__nav--prev"><?php echo esc_html__( 'Previous', 'cartify' ); ?></span>
							<span class="agni-threesixty__nav--play"><?php echo esc_html__( 'Play', 'cartify' ); ?></span>
							<span class="agni-threesixty__nav--stop hide"><?php echo esc_html__( 'Stop', 'cartify' ); ?></span>
							<span class="agni-threesixty__nav--next"><?php echo esc_html__( 'Next', 'cartify' ); ?></span>
						</div>
					</div>
					<div class="agni-threesixty__loader">
						<span>0%</span>
					</div>
					<span class="agni-threesixty__close"><i class="lni lni-close"></i><span><?php echo esc_html_x( 'Close', '360 close', 'cartify' ); ?></span></span>
				</div>
			</div>
		</div>
		<?php
	}
}

if( !function_exists( 'cartify_woocommerce_additional_classes_cart_page' ) ){
	function cartify_woocommerce_additional_classes_cart_page( $classes ){

		if( !class_exists( 'WooCommerce' ) || !is_cart()){
			return $classes;
		}

		if( WC()->cart->get_cart_contents_count() == 0 ){
			$classes[] = 'woocommerce-cart-empty';
		}

		?>
		<?php

		return array_unique( $classes );
	}
}

if( !function_exists( 'cartify_woocommerce_empty_cart_suggestion' ) ){
	function cartify_woocommerce_empty_cart_suggestion(){

		if( !class_exists( 'WooCommerce' ) || !is_cart()){
			return;
		}

		$block_id = cartify_get_theme_option( 'shop_settings_cart_block_choice', '' );

		if( empty( $block_id ) ){
			return;
		}

		?>
		<div class="cart-empty-contents"><?php 
			echo apply_filters( 'agni_content_block', $block_id );
		?></div>
		<?php
	}
}


if( !function_exists('cartify_woocommerce_cart_coupon') ){
	function cartify_woocommerce_cart_coupon(){
		?>
		<?php if ( wc_coupons_enabled() ) { ?>
			<form class="woocommerce-coupon-form" action="<?php echo esc_url( wc_get_cart_url() ) ?>" method="post">
				<div class="coupon">
					<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'cartify' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php echo esc_attr_x( 'Coupon code', 'Cart coupon code', 'cartify' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'cartify' ); ?>"><?php echo apply_filters( 'agni_woocommerce_coupon_submit_text', '<i class="lni lni-chevron-right"></i>' ); // esc_attr_e( '&#xEA4C;', 'cartify' ); ?></button>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>
			</form>
		<?php } ?>
		<?php
	}
}

if( !function_exists('cartify_wc_cart_totals_coupon_html') ){
	function cartify_wc_cart_totals_coupon_html( $coupon ) {
		if ( is_string( $coupon ) ) {
			$coupon = new WC_Coupon( $coupon );
		}

		$discount_amount_html = '';

		$amount               = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
		$discount_amount_html = wc_price( $amount );

		if ( $coupon->get_free_shipping() && empty( $amount ) ) {
			$discount_amount_html = esc_html__( 'Free shipping coupon', 'cartify' );
		}

		$discount_amount_html = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_amount_html, $coupon );
		$coupon_html          = '<span>' . $discount_amount_html . '</span>';

		echo wp_kses( apply_filters( 'woocommerce_cart_totals_coupon_html', $coupon_html, $coupon, $discount_amount_html ), array_replace_recursive( wp_kses_allowed_html( 'post' ), array( 'a' => array( 'data-coupon' => true ) ) ) ); // phpcs:ignore PHPCompatibility.PHP.NewFunctions.array_replace_recursiveFound
	}
}

function cartify_woocommerce_checkout_multistep_login_form(){
	if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
		return;
	}

	cartify_header_woocommerce_login_form();

		/*?>
	<div class="woocommerce-checkout-account-info__login-form">
		<h5><?php esc_html_e( 'Login', 'cartify' ); ?></h5>
		<?php
		woocommerce_login_form(
			array(
				'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Register or Billing section.', 'cartify' ),
				'redirect' => wc_get_page_permalink( 'checkout' ),
				//'hidden'   => true,
			)
		);
		?>
	</div>
	<?php */
}
/*
function cartify_woocommerce_checkout_coupon(){
	if ( !wc_coupons_enabled() ) {
		return;
	}

		$multistep_checkout = cartify_get_theme_option( 'shop_settings_multistep_checkout', '1' );

	if( !$multistep_checkout ){
		return;
	}

	 	?>
	<form class="checkout_coupon woocommerce-form-coupon" method="post">
		<div class="coupon">
			<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'cartify' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'cartify' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'cartify' ); ?>"><?php echo apply_filters( 'agni_woocommerce_coupon_submit_text', '<i class="lni lni-chevron-right"></i>' ); // esc_attr_e( '&#xEA4C;', 'cartify' ); ?></button>
			<?php do_action( 'woocommerce_cart_coupon' ); ?>
		</div>
	</form>
	<?php 
}*/

function cartify_woocommerce_checkout_coupon(){
	if ( !wc_coupons_enabled() ) {
		return;
	}

		// $multistep_checkout = cartify_get_theme_option( 'shop_settings_multistep_checkout', '1' );

	// if( $multistep_checkout ){
	// 	return;
	// }

	 	?>
	<div class="agni_checkout_coupon coupon">
		<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'cartify' ); ?></label>
		<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php echo esc_attr_x( 'Coupon code', 'Checkout coupon code', 'cartify' ); ?>" />
		<a class="coupon_submit" href="#"><?php echo apply_filters( 'agni_woocommerce_coupon_submit_text', '<i class="lni lni-chevron-right"></i>' ); // esc_attr_e( '&#xEA4C;', 'cartify' ); ?></a>
		<?php //do_action( 'woocommerce_cart_coupon' ); ?>
	</div>
	<?php 
}

function cartify_woocommerce_checkout_registration_form(){

	if( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' ) ){
		return;
	}

	?>
		<h4><?php esc_html_e( 'Register', 'cartify' ); ?></h4>

		<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_username"><?php esc_html_e( 'Username', 'cartify' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

			<?php endif; ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_email"><?php esc_html_e( 'Email address', 'cartify' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_password"><?php esc_html_e( 'Password', 'cartify' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
				</p>

			<?php else : ?>

				<p><?php esc_html_e( 'A password will be sent to your email address.', 'cartify' ); ?></p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<p class="woocommerce-FormRow form-row">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" class="woocommerce-Button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'cartify' ); ?>"><?php esc_html_e( 'Register', 'cartify' ); ?></button>
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>
	<?php
}

function cartify_woocommerce_checkout_account_information(){
	if( is_user_logged_in() ){
		return;
	}

	$social_login_fb_show = cartify_get_theme_option( 'shop_settings_social_login_fb_show', '1' );
	$social_login_google_show = cartify_get_theme_option( 'shop_settings_social_login_google_show', '1' );

		$multistep_checkout = cartify_get_theme_option( 'shop_settings_multistep_checkout', '1' );



	$hide_register = ('yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' ) && 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' )) && $multistep_checkout;


	?>
	<div id="account_info" class="woocommerce-checkout-account-info <?php echo !esc_attr($multistep_checkout) ? 'hide' : ''; ?>">
	<?php if( !$multistep_checkout ){
		?>
		<div class="woocommerce-checkout-account-info__overlay"></div>
		<?php
	} ?>
		<?php if( $multistep_checkout ){ ?>
			<?php if( 'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' ) && 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ){ ?> 
				<div class="woocommerce-checkout-account-info__toggle">
					<div class="woocommerce-checkout-account-info__toggle-login hide">
						<p><?php echo esc_html__( 'Already have an account', 'cartify' );  ?></p>	
						<a href="#account_info_login" class="btn btn-alt"><?php echo esc_html__( 'Login', 'cartify' );  ?></a>
					</div>
					<div class="woocommerce-checkout-account-info__toggle-register">
						<p><?php echo esc_html__( 'Don\'t have an account', 'cartify' );  ?></p>	
						<a href="#account_info_register" class="btn btn-alt"><?php echo esc_html__( 'Register', 'cartify' );  ?></a>
					</div>
				</div>
			<?php } ?>
		<?php } ?>

		<div id="account_info_login" class="woocommerce-checkout-account-info__login">
			<?php if( !$multistep_checkout ){
				?>
				<div class="woocommerce-checkout-account-info__close"><i class="lni lni-close"></i></div>
				<?php
			} ?>
			<?php cartify_woocommerce_checkout_multistep_login_form(); ?>	

			<?php if('yes' === get_option( 'woocommerce_enable_checkout_login_reminder' )){ ?>
				<div class="woocommerce-checkout-account-info__login-social">
					<?php if( $social_login_fb_show ){ ?>
						<button id="login-btn-facbook" class="woocommerce-checkout-account-info__login-btn--facbook btn btn-block btn-bold btn-lg"><?php echo esc_html__( 'Continue with Facebook', 'cartify'); ?></button>
					<?php } ?>
					<?php if( $social_login_google_show ){ ?>
						<button id="login-btn-google" class="woocommerce-checkout-account-info__login-btn--google btn btn-block btn-bold btn-lg"><?php echo esc_html__( 'Continue with Google', 'cartify'); ?></button>
					<?php } ?>
				</div>
			<?php } ?>
		</div>

		<?php if( $multistep_checkout ){ ?>
		<div id="account_info_register" class="woocommerce-checkout-account-info__register <?php echo esc_attr( $hide_register ) ? 'hide' : ''; ?>">
			<?php cartify_woocommerce_checkout_registration_form(); ?>
		</div>
		<?php } ?>

	</div>

		<?php
}

function cartify_woocommerce_checkout_login_link(){

	if( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ){
		return;
	}

		$multistep_checkout = cartify_get_theme_option( 'shop_settings_multistep_checkout', '1' );

	if( $multistep_checkout ){
		return;
	}

		?>
		<div class="woocommerce-checkout-account-info-toggle">
			<div class="woocommerce-info">
				<span><?php echo esc_html__( 'Returning customer?', 'cartify' ); ?></span>
				<a href="#" class="woocommerce-checkout-login-toggle-link"><?php echo esc_html__( 'Click here to login', 'cartify' ); ?> </a>
			</div>
		</div>
	<?php 
}


function cartify_woocommerce_checkout_details_open_tag(){ 
	?> <div class="woocommerce-checkout-customer-details"> <?php 
}

function cartify_woocommerce_checkout_details_close_tag(){ 
	?> </div> <?php 
}


function cartify_woocommerce_checkout_review_order_open_tag(){ 
	?> <div class="woocommerce-checkout-review-order-details"> <?php 
}

function cartify_woocommerce_checkout_review_order_close_tag(){ 
	?> </div> <?php 
}


function cartify_woocommerce_login_redirect() {
	if( !isset($_GET['redirect']) ){
		return;
	}

	$url = $_GET['redirect'];

	?>
	<input type="hidden" name="redirect" value="<?php echo esc_url( $url ); ?>">
	<?php 
}


function cartify_woocommerce_checkout_login_form(){
	?>
	<?php 
	if( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) || 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ){ ?> 
		<div class="woocommerce-myaccount-account-info-toggle">
			<div class="woocommerce-checkout-account-info-toggle-login hide">
				<p><?php echo esc_html__( 'Already have an account', 'cartify' );  ?></p>	
				<a href="#" class="btn btn-alt"><?php echo esc_html__( 'Login', 'cartify' );  ?></a>
			</div>
			<div class="woocommerce-checkout-account-info-toggle-register">
				<p><?php echo esc_html__( 'Don\'t have an account', 'cartify' );  ?></p>	
				<a href="#" class="btn btn-alt"><?php echo esc_html__( 'Register', 'cartify' );  ?></a>
			</div>
		</div>
	<?php }
	?>
	<?php
}

function cartify_woocommerce_myaccount_social_login(){

	$social_login_fb_show = cartify_get_theme_option( 'shop_settings_social_login_fb_show', '1' );
	$social_login_google_show = cartify_get_theme_option( 'shop_settings_social_login_google_show', '1' );


	if( $social_login_google_show == '1' && !is_user_logged_in() ){
		wp_enqueue_script( 'cartify_google_api' );
	}

	if('yes' === get_option( 'woocommerce_enable_checkout_login_reminder' )){ ?>
		<div class="woocommerce-myaccount-account-info__login-social">
			<?php if( $social_login_fb_show ){ ?>
				<button id="login-btn-facbook" class="woocommerce-myaccount-account-info__login-btn--facbook btn btn-block btn-bold btn-lg"><?php echo esc_html__( 'Continue with Facebook', 'cartify'); ?></button>
			<?php } ?>
			<?php if( $social_login_google_show ){ ?>
				<button id="login-btn-google" class="woocommerce-myaccount-account-info__login-btn--google btn btn-block btn-bold btn-lg"><?php echo esc_html__( 'Continue with Google', 'cartify'); ?></button>
			<?php } ?>
		</div>
	<?php }
}


if( !function_exists( 'cartify_social_login' ) ){

	function cartify_social_login(){


		$social_user_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$social_user_name = isset($_REQUEST['name'])?$_REQUEST['name']:'';
		$social_user_email = isset($_REQUEST['email'])?$_REQUEST['email']:'';
		$social_user_picture = isset($_REQUEST['picture'])?$_REQUEST['picture']:'';

				if ( !empty( $social_user_id ) && !empty( $social_user_email ) ) {

				if( !email_exists( $social_user_email ) ) {

				$args = array(
					'user_login'  =>  $social_user_email,
					'user_pass'   =>  wp_generate_password(),
					'user_email' => $social_user_email,
					'first_name' => $social_user_name,
					'role'  => 'customer'
				);
				wp_insert_user( $args );

			} 

			$wp_user = get_user_by( 'email', $social_user_email );
			// print_r($wp_user);
			$wp_user_id = $wp_user->ID;

			wp_set_auth_cookie( $wp_user_id, true );

			wp_send_json($wp_user);


		}

		?>
		<?php
		die();
	}
}




function cartify_woocommerce_checkout_payment_title(){

	$multistep_checkout = cartify_get_theme_option( 'shop_settings_multistep_checkout', '1' );

	if( $multistep_checkout ){
		return;
	}

	?>
	<h3 class="woocommerce-checkout-payment-heading"><?php echo esc_html__( 'Payment details', 'cartify' ); ?></h3>
	<?php
}


function cartify_woocommerce_checkout_billing_shipping_title(){
	?>
	<h3><?php echo esc_html__( 'Billing & shipping address', 'cartify' ); ?></h3>
	<?php
}

function cartify_woocommerce_myaccount_dashboard(){

	$endpoints = array(
		'orders'          => get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' ),
		'downloads'       => get_option( 'woocommerce_myaccount_downloads_endpoint', 'downloads' ),
		'edit-address'    => get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ),
		'wishlist'		  => get_option( 'woocommerce_myaccount_wishlist_endpoint', 'wishlist' ),
		'edit-account'    => get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' )
	);

		?>
	<ul class="agni-woocommerce-account-dashboard-control">
		<?php if( !empty($endpoints['wishlist']) ){ ?>
			<li><a href="<?php echo esc_url( wc_get_endpoint_url( 'wishlist' ) ) ?>"><?php echo esc_html__( 'Wishlists', 'cartify' ); ?></a></li>
		<?php } ?>
		<?php if( !empty($endpoints['orders']) ){ ?>
			<li><a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ) ?>"><?php echo esc_html__( 'Orders', 'cartify' ); ?></a></li>
		<?php } ?>
		<?php if( !empty($endpoints['downloads']) ){ ?>
			<li><a href="<?php echo esc_url( wc_get_endpoint_url( 'downloads' ) ) ?>"><?php echo esc_html__( 'Downloads', 'cartify' ); ?></a></li>
		<?php } ?>
		<?php if( !empty($endpoints['edit-address']) ){ ?>
			<li><a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ) ?>"><?php echo esc_html__( 'Addresses', 'cartify' ); ?></a></li>
		<?php } ?>
		<?php if( !empty($endpoints['edit-account']) ){ ?>
			<li><a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ) ?>"><?php echo esc_html__( 'Account details', 'cartify' ); ?></a></li>
		<?php } ?>
	</ul>
	<?php
}



// function cartify_woocommerce_template_loop_product_thumbnail(){

// }

/**
 * overriding the archives product thumbnail
 *
 * @param string $size
 * @param integer $deprecated1
 * @param integer $deprecated2
 * @return void
 */
function woocommerce_get_product_thumbnail( $size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0 ) {
	global $product;

		$product_thumbnail_style = apply_filters( 'agni_product_archive_thumbnail_style', cartify_get_theme_option( 'shop_settings_general_thumbnail_choice', 1 ) );

	$product_thumbnail_ids = array();
	$image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

	if( $product && $product->get_image_id() ){
		$product_thumbnail_ids[] = $product->get_image_id();

				}

	// echo "thumbnail style" . $product_thumbnail_style;

	if( $product_thumbnail_style == 2 ){
		$attachment_ids = $product->get_gallery_image_ids();
		if( !empty($attachment_ids) ){
			$product_thumbnail_ids[] = $attachment_ids[0];
		}
	}
	else if( $product_thumbnail_style == 3 ){

		wp_enqueue_style('slick');
		wp_enqueue_script('slick');

				$attachment_ids = $product->get_gallery_image_ids();
		if( $attachment_ids && $product->get_image_id() ){
			foreach( $attachment_ids as $attachment_id ){
				$product_thumbnail_ids[] = $attachment_id;
			}
		}
	}
	if( empty( $product_thumbnail_ids ) ){
		$product_thumbnail_ids[] = get_option( 'woocommerce_placeholder_image', '' );
	}
	// get_option( 'woocommerce_placeholder_image', 0 );

	if( !empty( $product_thumbnail_ids ) ){
	?>
	<div class="woocommerce-loop-product__thumbnail style-<?php echo esc_attr($product_thumbnail_style); ?>">
		<?php
		foreach( $product_thumbnail_ids as $thumbnail_id ){
			echo woocommerce_template_loop_product_link_open();

					echo apply_filters( 'agni_woocommerce_archives_product_image_thumbnail_html', wp_get_attachment_image($thumbnail_id, $image_size), $thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

			echo woocommerce_template_loop_product_link_close();
		} ?>

	</div>

		<?php
	}
	//return $product ? $product->get_image( $image_size ) : '';
}

// add_filter( 'subcategory_archive_thumbnail_size', function(){
// 	return 'cartify_thumbnail_3/2'; } );
// add_filter( 'subcategory_archive_thumbnail_size', function(){
// 	return array( '265', '230' ); } );
// add_filter( 'woocommerce_get_image_size_thumbnail', function( $size ) {
//                 print_r( $size );
//                 return array(
//                 'width' => 260,
//                 'crop' => 0,
//                 );
//                 } );


function woocommerce_wp_select_multiple( $field ) {
    global $thepostid, $post, $woocommerce;

    $thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
    $field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
    $field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
    $field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['value']         = isset( $field['value'] ) ? $field['value'] : ( get_post_meta( $thepostid, $field['id'], true ) ? get_post_meta( $thepostid, $field['id'], true ) : array() );
	$field['custom_attributes'] = isset( $field['custom_attributes'] ) ? $field['custom_attributes'] : ''; 

	$attributes = '';
	$attributes_array = $field['custom_attributes'];

	foreach( $attributes_array as $key => $value ){
		$attributes .= $key . '="' . $value . '" ';
	}


    echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" class="' . esc_attr( $field['class'] ) . '" '.$attributes.'>';
    foreach ( $field['options'] as $key => $value ) {

        echo '<option value="' . esc_attr( $key ) . '" ' . ( is_array( $field['value'] ) && in_array( $key, $field['value'] ) ? 'selected="selected"' : '' ) . '>' . esc_html( $value ) . '</option>';

    }

    echo '</select> ';

    if ( ! empty( $field['description'] ) ) {

        if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
            echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
        } else {
            echo '<span class="description">' . esc_html( $field['description'] ) . '</span>';
        }

    }
    echo '</p>';
}


function woocommerce_product_archive_description() {
	// Don't display the description on search results page.
	if ( is_search() ) {
		return;
	}

	if ( is_post_type_archive( 'product' ) && in_array( absint( get_query_var( 'paged' ) ), array( 0, 1 ), true ) ) {
		$shop_page = get_post( wc_get_page_id( 'shop' ) );
		if ( $shop_page ) {
			$shop_blocks_parsed = parse_blocks( $shop_page->post_content );
			$description = '';
			foreach($shop_blocks_parsed as $block){
				$description .= render_block($block);
			}

						if ( $description ) {
				echo '<div class="page-description">' . $description . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
}

/**
 * WooCommerce by default hides out of stock products AFTER the query.
 * This leaves gaps: If you have 16 products per page, but 1 of those products are out of stock, you only see 15.
 * This solution excludes out of stock products during the query, so you'll get the expected results even if some of the products are out of stock.
 *
 * @param $query
 *
 * @return WP_Query $query
 */
function cartify_woocommerce_search_remove_out_of_stock( $query ) {
	if ( is_admin() ) return $query;

		$hide_outofstock = cartify_get_theme_option( 'shop_settings_search_hide_outofstock', '' );

		if( $hide_outofstock && is_search() ){

			$meta_query = array();

				// If a meta_query already exists
		if ( !empty($query->get('meta_query')) ) {
			$meta_query = array(
				'relation' => 'AND',
				$query->get('meta_query')
			);
		}

				// Exclude out of stock products
		$meta_query[] = array(
			'key'       => '_stock_status',
			'value'     => 'outofstock',
			'compare'   => 'NOT IN'
		);

				$query->set('meta_query', $meta_query);
	}

		return $query;
}


// function bbloomer_change_cross_sells_columns( $columns ) {
// 	// echo $columns;
// 	return $columns;
// }

 // function bbloomer_change_cross_sells_product_no( $columns ) {
// 	echo $columns;
// 	return $columns;
// }


add_action( 'after_setup_theme', 'cartify_woocommerce_custom_image_sizes' );
add_action( 'woocommerce_init', 'cartify_woocommerce_theme_options_processing' );
add_action( 'woocommerce_init', 'cartify_woocommerce_setup' );
add_action( 'woocommerce_init', 'cartify_products_count_processing' );

add_action( 'wp_enqueue_scripts', 'cartify_woocommerce_scripts' );
add_action( 'widgets_init', 'cartify_woocommerce_widgets_init' );

add_action( 'woocommerce_shop_loop_item_title', 'cartify_woocommerce_products_loop_category_title', 9 );
add_filter( 'woocommerce_add_to_cart_fragments', 'cartify_woocommerce_ajax_cart_fragment' );


add_action( 'woocommerce_before_main_content', 'cartify_woocommerce_layout_setup', 1 );
add_action( 'woocommerce_before_main_content', 'cartify_woocommerce_category_bar', 8 );
add_action( 'agni_woocommerce_before_main_content', 'cartify_woocommerce_shop_slider', 9 );
add_action( 'woocommerce_before_shop_loop', 'cartify_woocommerce_category_content_block' );
// add_action( 'woocommerce_before_shop_loop', 'cartify_woocommerce_get_topbar', 99 );

add_action( 'agni_woocommerce_control_bar', 'cartify_woocommerce_filter_toggle', 20 );
add_action( 'agni_woocommerce_control_bar', 'cartify_woocommerce_prducts_count_switcher', 25 );
add_action( 'agni_woocommerce_control_bar', 'cartify_woocommerce_column_switcher', 40 );
add_action( 'agni_woocommerce_after_control_bar', 'cartify_woocommerce_get_topbar' );
add_action( 'woocommerce_after_shop_loop_item_title', 'cartify_woocommerce_short_description', 9 );
// add_action( 'woocommerce_after_shop_loop_item_title', 'cartify_woocommerce_block_short_description', 9 );
add_action( 'woocommerce_after_shop_loop_item', 'cartify_woocommerce_instock_indicator', 20 );
add_action( 'woocommerce_after_shop_loop_item', 'cartify_woocommerce_sale_countdown', 25 );

add_action( 'agni_woocommerce_after_shop_loop_item', 'cartify_template_loop_cart_open_tag', 9 );
add_action( 'agni_woocommerce_after_shop_loop_item', 'cartify_template_loop_cart_close_tag', 11 );
add_action( 'agni_woocommerce_after_shop_loop_item', 'cartify_template_loop_qty_update', 10 );
// add_action( 'woocommerce_after_subcategory_title', 'cartify_template_loop_product_category_description' );

add_action( 'woocommerce_before_shop_loop_item', 'cartify_woocommerce_product_hover_placeholder', 9 );
add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_woocommerce_label_outofstock', 9 );
add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_woocommerce_label_hot', 9 );
add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_woocommerce_label_new', 9 );

add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_thumbnail_open_tag', 8 );
add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_thumbnail_close_tag', 27 );
add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_cart_open_tag', 15 );
add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_cart_close_tag', 24 );
add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_qty_update', 18 );
add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_template_loop_add_to_cart', 21 );

add_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_link_open', 8 );
add_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_link_close', 12 );

add_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_label_hot', 9 );
add_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_label_new', 9 );


//add_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_rating' );
//add_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_template_single_brand', 9 );
add_action( 'agni_woocommerce_after_single_product_title', 'cartify_woocommerce_template_single_brand' );
add_action( 'agni_woocommerce_after_single_product_title', 'woocommerce_template_single_rating' );
//add_action( 'agni_woocommerce_after_single_product_title', 'cartify_woocommerce_template_single_qa' );
add_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_single_product_video', 25 );
add_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_single_product_360_image', 30 );
//add_action( 'wc_ajax_agni_woocommerce_threesixty_images', 'cartify_woocommerce_threesixty_images' );
//add_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_buttons', 31 );
add_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_product_sale_countdown', 12 );
add_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_product_featured', 4 );
add_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_additional_info', 45 );
add_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_single_compare_button', 55 );
// add_action( 'woocommerce_after_add_to_cart_button', 'cartify_woocommerce_single_additional_button_buynow', 10 );

add_action( 'woocommerce_before_main_content', 'cartify_woocommerce_single_open_tag', 1 );
add_action( 'woocommerce_sidebar', 'cartify_woocommerce_single_close_tag', 15 );

add_action( 'pre_get_posts', 'cartify_woocommerce_search_remove_out_of_stock' );

// add_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_single_content_open_tag', 1 );
// add_action( 'woocommerce_after_single_product_summary', 'cartify_woocommerce_single_content_close_tag', 1 );
// add_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_single_image_open_tag', 5 );
// add_action( 'woocommerce_before_single_product_summary', 'cartify_woocommerce_single_image_close_tag', 50 );

add_action( 'agni_woocommerce_single_reviews_histogram', 'cartify_woocommerce_single_reviews_histogram' );
add_action( 'woocommerce_before_customer_login_form', 'cartify_woocommerce_checkout_login_form', 20 );
add_action( 'woocommerce_login_form', 'cartify_woocommerce_login_redirect' );
add_action( 'woocommerce_login_form_end', 'cartify_woocommerce_myaccount_social_login' );
add_action( 'woocommerce_cart_is_empty', 'cartify_woocommerce_empty_cart_suggestion' );
add_action( 'woocommerce_proceed_to_checkout', 'cartify_woocommerce_cart_coupon');
// add_action( 'agni_ajax_sidecart_footer', 'cartify_woocommerce_cart_coupon', 9 );
add_action( 'woocommerce_review_order_before_payment', 'cartify_woocommerce_checkout_payment_title' );
// add_action( 'woocommerce_checkout_before_customer_details', 'cartify_woocommerce_checkout_billing_shipping_title' );
add_action( 'woocommerce_checkout_before_customer_details', 'cartify_woocommerce_checkout_login_link' );

add_action( 'agni_woocommerce_checkout_before_details', 'cartify_woocommerce_checkout_details_open_tag', 10 );
add_action( 'agni_woocommerce_checkout_after_details', 'cartify_woocommerce_checkout_details_close_tag', 20 );

add_action( 'woocommerce_checkout_before_order_review_heading', 'cartify_woocommerce_checkout_review_order_open_tag', 10 );
add_action( 'woocommerce_checkout_after_order_review', 'cartify_woocommerce_checkout_review_order_close_tag', 20 );

add_action( 'woocommerce_account_dashboard', 'cartify_woocommerce_myaccount_dashboard', 10, 1 );

// add_action( 'woocommerce_before_main_content', 'cartify_woocommerce_recommended_products', 15 );
// add_action( 'woocommerce_before_main_content', 'cartify_woocommerce_bestselling_products', 16 );

// add_action( 'wp_ajax_agni_ajax_products_count_processing', 'cartify_ajax_products_count_processing' );
// add_action( 'wp_ajax_nopriv_agni_ajax_products_count_processing', 'cartify_ajax_products_count_processing' );

add_action( 'wc_ajax_agni_woocommerce_pagination', 'cartify_ajax_woocommerce_pagination' );
add_action( 'wc_ajax_no_priv_agni_woocommerce_pagination', 'cartify_ajax_woocommerce_pagination' );
add_action( 'wc_ajax_agni_template_loop_add_to_cart', 'cartify_ajax_template_loop_add_to_cart', 10, 3 );
add_action( 'wc_ajax_nopriv_agni_template_loop_add_to_cart', 'cartify_ajax_template_loop_add_to_cart', 10, 3 );
add_action( 'wc_ajax_agni_template_loop_add_to_cart_reset', 'cartify_ajax_template_loop_add_to_cart_reset', 10 );
add_action( 'wc_ajax_nopriv_agni_template_loop_add_to_cart_reset', 'cartify_ajax_template_loop_add_to_cart_reset', 10 );
add_action( 'wc_ajax_agni_ajax_get_cart_item_key', 'cartify_ajax_get_cart_item_key' );
add_action( 'wc_ajax_nopriv_agni_ajax_get_cart_item_key', 'cartify_ajax_get_cart_item_key' );
add_action( 'wc_ajax_agni_woocommerce_sale_countdown', 'cartify_ajax_woocommerce_sale_countdown' );
add_action( 'wc_ajax_agni_social_login', 'cartify_social_login' );
add_action( 'wc_ajax_no_priv_agni_social_login', 'cartify_social_login' );


// filter
add_filter( 'body_class', 'cartify_woocommerce_additional_classes_cart_page' );
add_filter( 'use_block_editor_for_post_type', 'cartify_enable_block_editor_product', 10, 2 );
add_filter( 'woocommerce_taxonomy_args_product_cat', 'cartify_enable_taxonomy_rest' );
add_filter( 'woocommerce_taxonomy_args_product_tag', 'cartify_enable_taxonomy_rest' );

add_filter( 'woocommerce_regenerate_images_intermediate_image_sizes', 'cartify_regenerate_custom_image_sizes' );

add_filter( 'woocommerce_products_header_classes', 'cartify_woocommerce_products_header_classes' );
add_filter( 'woocommerce_show_page_title', 'cartify_woocommerce_shop_page_title' );
add_filter( 'woocommerce_get_availability', 'cartify_woocommerce_custom_stock_text', 10, 2);
add_filter( 'woocommerce_sale_flash', 'cartify_woocommerce_custom_sale_flash' );
add_filter( 'woocommerce_get_star_rating_html', 'cartify_woocommerce_get_star_rating_html', 10, 3 );
add_filter( 'woocommerce_breadcrumb_defaults', 'cartify_woocommerce_breadcrumb_defaults' );

add_filter( 'woocommerce_shipping_may_be_available_html', 'cartify_woocommerce_sidecart_shipping_text' );

add_filter( 'woocommerce_is_attribute_in_product_name', '__return_false' );

// add_filter( 'woocommerce_cross_sells_columns', 'bbloomer_change_cross_sells_columns' );
// add_filter( 'woocommerce_cross_sells_total', 'bbloomer_change_cross_sells_product_no' );


 // removing woocommerce link open & close
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

// removing & adding archives title
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'cartify_woocommerce_template_loop_product_title', 10 );

// removing & adding woocommerce pagination
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action( 'woocommerce_after_shop_loop', 'cartify_woocommerce_pagination', 11);

// removing & adding category close link
remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );
add_action( 'woocommerce_before_subcategory_title', 'woocommerce_template_loop_category_link_close', 20 );

// removing & adding sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_action( 'woocommerce_sidebar', 'cartify_woocommerce_get_sidebar', 10 );
add_action( 'woocommerce_sidebar', 'cartify_woocommerce_single_get_sidebar', 10 );

// removing & adding woocommerce ordering
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'agni_woocommerce_control_bar', 'woocommerce_result_count', 20 );

// removing & adding woocommerce result count
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'agni_woocommerce_control_bar', 'woocommerce_catalog_ordering', 30 );

// removing & adding add to cart button on products 
// remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// add_action( 'woocommerce_after_shop_loop_item', 'cartify_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'agni_woocommerce_after_shop_loop_item', 'cartify_template_loop_add_to_cart', 10 );
//add_action( 'agni_woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

// removing & adding breadcrumb on products
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_before_main_content', 'cartify_woocommerce_breadcrumb', 9 );

// removing & adding product gallery
// remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
// add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20, 2 );

// removing & adding notices
remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
add_action( 'agni_woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );

// removing & adding rating, brand, Q&A
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'cartify_woocommerce_after_single_product_title', 9 );


// // removing & adding shop archives thumbnail
// remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
// add_action( 'woocommerce_before_shop_loop_item_title', 'cartify_woocommerce_template_loop_product_thumbnail', 10 );

// removing & adding cross-sell products
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 10 );

// removing & adding checkout page login form
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
add_action( 'woocommerce_before_checkout_form', 'cartify_woocommerce_checkout_account_information', 12 );

// removing & adding checkout page coupon 
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
// add_action( 'woocommerce_review_order_after_order_total', 'cartify_woocommerce_checkout_coupon', 10 );
add_action( 'woocommerce_review_order_after_order_total', 'cartify_woocommerce_checkout_coupon', 10 );

// removing & adding checkout payment form
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'agni_woocommerce_checkout_after_details', 'woocommerce_checkout_payment', 10 );
/* add_action( 'woocommerce_checkout_after_order_review', function(){ 
	?> <div>Hello world! I must be inside form</div> <?php 
}, 10 ); */

// removing & adding checkout page review order
// remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
// add_action( 'woocommerce_checkout_before_order_review', 'woocommerce_order_review', 10 );


// removing woocommerce single tabs title
// add_filter('woocommerce_product_description_heading', '__return_false' );
// add_filter('woocommerce_product_additional_information_heading', '__return_false');



// if( !function_exists('agni_woocommerce_after_shop_loop_item') ){
//     /**
//      * 
//      */
//     function agni_woocommerce_after_shop_loop_item(){
//         woocommerce_template_loop_add_to_cart();
//     }
// }
