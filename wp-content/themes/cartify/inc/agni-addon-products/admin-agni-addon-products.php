<?php

/**
 * Add a custom product tab.
 */
function cartify_product_data_tabs_addon_products( $tabs) {

    global $post;

    $product = wc_get_product( $post->ID );

    if( !$product->is_type( array( 'simple', 'variable' ) ) ){
        return $tabs;
    }

    $tabs['agni_custom_variations'] = array(
		'label'		=> esc_html__( 'Cartify Addon Products', 'cartify' ),
		'target'	=> 'agni-addon-products',
		'class'		=> array( 'agni_woocommerce_custom_variations_tab' ),
	);

	return $tabs;

}

function cartify_product_data_addon_products_custom_field(){

	global $post;

        $product_list = array();
    $products_id = get_post_meta( $post->ID, 'agni_product_data_addon_products', true );
    $current_product = wc_get_product( $post->ID );

    if( $products_id ){
        foreach( $products_id as $product_id ){
            $product_list[$product_id] = get_the_title( $product_id );
        }
    }

	// Note the 'id' attribute needs to match the 'target' parameter set above
	?><div id='agni-addon-products' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'>
            <?php

            // product type : simple
            // product id : except current id

            if( function_exists('woocommerce_wp_select_multiple') ){
                woocommerce_wp_select_multiple(
                    array(
                        'id'      => 'agni_product_data_addon_products',
                        'name'      => 'agni_product_data_addon_products[]',
                        'label'   => esc_html__( 'Products to addon products', 'cartify' ),
                        'options' => $product_list,
                        'custom_attributes'	=> array(
                            'multiple'	=> 'multiple',
                            'data-ignore-id' => $current_product->get_id()
                        ),
                    )
                );
            }

            ?>

                    </div>
    </div>
    <?php

}

function cartify_product_data_addon_products_save_custom_field( $post_id ) {

	    $product_data_addon_products = isset( $_POST['agni_product_data_addon_products'] )? array_map( 'intval', (array) $_POST['agni_product_data_addon_products'] ) : array();
    update_post_meta( $post_id, 'agni_product_data_addon_products', $product_data_addon_products );

    return $post_id;

	}

function cartify_addon_products_search_results(){

    $product_list = array();
    $args = array( 
        's'=> $_GET['q'], // the search query
        'post_type'             => 'product',
		'post_status' => 'publish', // if you don't want drafts to be returned
        'ignore_sticky_posts' => 1,
        'post__not_in' => array( $_GET['ignore_id'] ),
        'posts_per_page' => 10, // how much to show at once
    );

     $products_addon_products_query = new WP_Query( $args );
	if( $products_addon_products_query->have_posts() ) {
        while( $products_addon_products_query->have_posts() ) { $products_addon_products_query->the_post();
            $get_product_type = wc_get_product( $products_addon_products_query->post->ID );
            if( $get_product_type->get_type() == 'simple' ){
                $product_list[] = array( $products_addon_products_query->post->ID, $products_addon_products_query->post->post_title ); // array( Post ID, Post Title )
            }
        }
    }

        wp_reset_postdata();
    echo json_encode( $product_list );


        	die();
}

function cartify_admin_addon_products_scripts(){

    // Registering JS for Addon Products
    wp_enqueue_script('cartify-admin-addon-products', AGNI_FRAMEWORK_JS_URL . '/agni-addon-products/admin-agni-addon-products.js', array('jquery', 'select2'), wp_get_theme()->get('Version'), true);
}



add_action( 'woocommerce_product_data_tabs', 'cartify_product_data_tabs_addon_products', 11 );
add_filter( 'woocommerce_product_data_panels', 'cartify_product_data_addon_products_custom_field' );
add_action( 'woocommerce_process_product_meta', 'cartify_product_data_addon_products_save_custom_field', 10, 1 );

add_action( 'wp_ajax_agni_addon_products_search_results', 'cartify_addon_products_search_results' ); 

add_action( 'admin_enqueue_scripts', 'cartify_admin_addon_products_scripts' );