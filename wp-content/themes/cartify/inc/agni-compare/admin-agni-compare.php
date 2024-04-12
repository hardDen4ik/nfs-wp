<?php

/**
 * Add a custom product tab.
 */
function cartify_product_data_tabs_compare( $tabs) {

	$tabs['agni_compare'] = array(
		'label'		=> esc_html__( 'Cartify Compare', 'cartify' ),
		'target'	=> 'agni-compare-options',
		'class'		=> array( 'agni_woocommerce_compare_tab' ),
    );


    	return $tabs;

}


function cartify_product_data_compare_custom_field(){

	global $post;

        $product_list = array();
    $products_id = get_post_meta( $post->ID, 'agni_product_data_compare', true );

    if( $products_id ){
        foreach( $products_id as $product_id ){
            $product_list[$product_id] = get_the_title( $product_id );
        }
    }

	// Note the 'id' attribute needs to match the 'target' parameter set above
	?><div id='agni-compare-options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'>
            <?php

            if( function_exists('woocommerce_wp_select_multiple') ){
                woocommerce_wp_select_multiple(
                    array(
                        'id'      => 'agni_product_data_compare',
                        'name'      => 'agni_product_data_compare[]',
                        'label'   => esc_html__( 'Products to compare', 'cartify' ),
                        'options' => $product_list,
                        'custom_attributes'	=> array(
                            'multiple'	=> 'multiple',
                        ),
                    )
                );
            }

            ?>

                    </div>
    </div>
    <?php

}

function cartify_product_data_compare_save_custom_field( $post_id ) {

	    $product_data_compare = isset( $_POST['agni_product_data_compare'] )? array_map( 'intval', (array) $_POST['agni_product_data_compare'] ) : array();
    update_post_meta( $post_id, 'agni_product_data_compare', $product_data_compare );

    return $post_id;

	}

function cartify_compare_products_search_results(){

    $product_list = array();
    $args = array( 
        's'=> $_GET['q'], // the search query
        'post_type'             => 'product',
		'post_status' => 'publish', // if you don't want drafts to be returned
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 10 // how much to show at once
	);

 	$products_compare_query = new WP_Query( $args );
	if( $products_compare_query->have_posts() ) {
		while( $products_compare_query->have_posts() ) { $products_compare_query->the_post();
			$product_list[] = array( $products_compare_query->post->ID, $products_compare_query->post->post_title ); // array( Post ID, Post Title )
        }
    }

        wp_reset_postdata();
	echo json_encode( $product_list );
	die();
}



function cartify_admin_compare_scripts(){

    // Registering JS for compare
    wp_enqueue_script('cartify-admin-compare', AGNI_FRAMEWORK_JS_URL . '/agni-compare/admin-agni-compare.js', array('jquery', 'select2'), wp_get_theme()->get('Version'), true);
}



add_filter( 'woocommerce_product_data_tabs', 'cartify_product_data_tabs_compare' );
add_filter( 'woocommerce_product_data_panels', 'cartify_product_data_compare_custom_field' );
add_action( 'woocommerce_process_product_meta', 'cartify_product_data_compare_save_custom_field', 10, 1 );

add_action( 'wp_ajax_agni_compare_products_search_results', 'cartify_compare_products_search_results' ); 

add_action( 'admin_enqueue_scripts', 'cartify_admin_compare_scripts' );
