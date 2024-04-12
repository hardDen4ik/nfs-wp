<?php 

add_action( 'init', 'agni_register_custom_taxonomy_brand' );
add_action( 'init', 'agni_register_custom_posttype_wishlist' );
add_action( 'init', 'agni_register_custom_posttype_portfolio' );
add_action( 'init', 'agni_register_custom_posttype_blocks' );


/**
 * Custom taxonomy for products
 */

function agni_register_custom_taxonomy_brand(){

    if( !class_exists( 'WooCommerce' ) ){
        return;
    }

    $labels = array(
        'name'                       => esc_html_x( 'Brand', 'taxonomy general name', 'agni-cartify' ),
        'singular_name'              => esc_html_x( 'Brand', 'taxonomy singular name', 'agni-cartify' ),
        'search_items'               => esc_html__( 'Search Brand', 'agni-cartify' ),
        'popular_items'              => esc_html__( 'Popular Brands', 'agni-cartify' ),
        'all_items'                  => esc_html__( 'All Brands', 'agni-cartify' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => esc_html__( 'Edit Brand', 'agni-cartify' ),
        'update_item'                => esc_html__( 'Update Brand', 'agni-cartify' ),
        'add_new_item'               => esc_html__( 'Add New Brand', 'agni-cartify' ),
        'new_item_name'              => esc_html__( 'New Brand Name', 'agni-cartify' ),
        'separate_items_with_commas' => esc_html__( 'Separate brands with commas', 'agni-cartify' ),
        'add_or_remove_items'        => esc_html__( 'Add or remove brands', 'agni-cartify' ),
        'choose_from_most_used'      => esc_html__( 'Choose from the most used brands', 'agni-cartify' ),
        'not_found'                  => esc_html__( 'No brands found.', 'agni-cartify' ),
        'menu_name'                  => esc_html__( 'Brands', 'agni-cartify' ),
    );
 
    $args = array(
        'hierarchical'          => false, // true
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        //'show_in_quick_edit'         => false,
        //'meta_box_cb'           => 'post_categories_meta_box',
        //'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'product_brand' ),
    );


    register_taxonomy( 'product_brand',  array( 'product' ),  $args );
    

}

function agni_register_custom_posttype_wishlist(){
    if( !class_exists( 'WooCommerce' ) ){
        return;
    }

    $labels = array(
        'name' 				=> esc_html_x( 'Wishlist', 'post type general name', 'agni-cartify' ),
        'singular_name'		=> esc_html_x( 'Wishlist', 'post type singular name', 'agni-cartify' ),
        'add_new' 			=> esc_html__( 'Add New Wishlist', 'agni-cartify' ),
        'add_new_item' 		=> esc_html__( 'Add New Wishlist Item', 'agni-cartify' ),
        'all_items' 		=> esc_html__( 'All Wishlist Items', 'agni-cartify' ),
        'edit_item' 		=> esc_html__( 'Edit Wishlist Item', 'agni-cartify' ),
        'new_item' 			=> esc_html__( 'New Wishlist Item', 'agni-cartify' ),
        'view_item' 		=> esc_html__( 'View Wishlist Items', 'agni-cartify' ),
        'search_items' 		=> esc_html__( 'Search Wishlist Items', 'agni-cartify' ),
        'not_found' 		=> esc_html__( 'No Wishlist found', 'agni-cartify' ),
        'not_found_in_trash'=> esc_html__( 'No Wishlist found in trash', 'agni-cartify' ),
        'parent_item'       => null,
        'parent_item_colon' => null,
        'menu_name'			=> esc_html__( 'Wishlist', 'agni-cartify' )
    );
    
    $post_type_args = array(
        'labels' 			=> $labels,
        'public' 			=> false,
        'show_ui' 			=> true,
        'show_in_nav_menus'	=> false,
        'publicly_queryable'=> true,
        'query_var'			=> true,
        'capability_type' 	=> 'post',
        'hierarchical' 		=> false,
        'supports' 			=> array('title', 'author'),
        'menu_position' 	=> 56, // Where it is in the menu. Change to 6 and it's below posts. 11 and it's below media, etc.
        'menu_icon' 		=> 'dashicons-admin-post',
        'can_export'        => true,
        'show_in_rest' 		=> true,
    );
    register_post_type('agni_wc_wishlist',$post_type_args);

}



/**
 * Block Custom Post Type
 */ 
function agni_register_custom_posttype_blocks() {
    $labels = array(
        'name'              => esc_html_x( 'Blocks', 'post type general name', 'agni-cartify' ),
        'singular_name'     => esc_html_x( 'Block', 'post type singular name', 'agni-cartify' ),
        'add_new'           => esc_html__( 'Add New Block', 'agni-cartify' ),
        'add_new_item'      => esc_html__( 'Add New Block', 'agni-cartify' ),
        'all_items'         => esc_html__( 'All Blocks', 'agni-cartify' ),
        'edit_item'         => esc_html__( 'Edit Block', 'agni-cartify' ),
        'new_item'          => esc_html__( 'New Block', 'agni-cartify' ),
        'view_item'         => esc_html__( 'View Block', 'agni-cartify' ),
        'search_items'      => esc_html__( 'Search Blocks', 'agni-cartify' ),
        'not_found'         => esc_html__( 'No blocks found', 'agni-cartify' ),
        'not_found_in_trash'=> esc_html__( 'No blocks found in trash', 'agni-cartify' ),
        'parent_item_colon' => esc_html__( 'Parent Blocks:', 'agni-cartify' ),
        'menu_name'         => esc_html__( 'Blocks', 'agni-cartify' )
    );

    
    // $taxonomies = array( 'category', 'post_tag');
    
    $post_type_args = array(
        'labels'            => $labels,
        'singular_label'    => esc_html__( 'Block', 'agni-cartify' ),
        'public'            => true,
        'show_ui'           => true,
        'show_in_nav_menus' => false,
        'publicly_queryable'=> true,
        'query_var'         => true,
        'capability_type'   => 'page',
        'has_archive'       => false,
        'hierarchical'      => false,
        'exclude_from_search' => true,
        'rewrite'           => array('slug' => 'agni_block', 'with_front' => false  ),
        'supports'          => array('title', 'editor', 'custom-fields'),
        'menu_position'     => 6, // Where it is in the menu. Change to 6 and it's below posts. 11 and it's below media, etc.
        'menu_icon'         => 'dashicons-layout',
        // 'taxonomies'        => $taxonomies,
        // 'can_export'         => true,
        'show_in_rest' 		=> true,
    );
        
    register_post_type( 'agni_block', $post_type_args );

    $tax_args = array(
        'hierarchical' => true, 
        'label' => esc_html__( 'Block Categories', 'agni-cartify' ), 
        'singular_label' => esc_html__( 'Block Category', 'agni-cartify' ), 
        'show_ui' => true, 
        'show_admin_column' => true, 
        'query_var' => true, 
        'rewrite' => true,
        'show_in_rest' => true,
    );

    register_taxonomy( 'block_category', array('agni_block'), $tax_args );
}

/**
 * Portfolio Custom Post Type
 */ 
function agni_register_custom_posttype_portfolio() {
    
    $labels = array(
        'name'              => esc_html_x( 'Portfolio', 'post type general name', 'agni-cartify' ),
        'singular_name'     => esc_html_x( 'Portfolio Item', 'post type singular name', 'agni-cartify' ),
        'add_new'           => esc_html__( 'Add New Portfolio', 'agni-cartify' ),
        'add_new_item'      => esc_html__( 'Add New Portfolio Item', 'agni-cartify' ),
        'all_items'         => esc_html__( 'All Portfolio Items', 'agni-cartify' ),
        'edit_item'         => esc_html__( 'Edit Item', 'agni-cartify' ),
        'new_item'          => esc_html__( 'New Portfolio Item', 'agni-cartify' ),
        'view_item'         => esc_html__( 'View Portfolio Items', 'agni-cartify' ),
        'search_items'      => esc_html__( 'Search Portfolio', 'agni-cartify' ),
        'not_found'         => esc_html__( 'No portfolio items found', 'agni-cartify' ),
        'not_found_in_trash'=> esc_html__( 'No portfolio items found in trash', 'agni-cartify' ),
        'parent_item_colon' => esc_html__( 'Parent Portfolio Item:', 'agni-cartify' ),
        'menu_name'         => esc_html__( 'Portfolio', 'agni-cartify' )
    );

    
    // $taxonomies = array( 'category', 'post_tag');
    
    $post_type_args = array(
        'labels'              => $labels,
        'singular_label'    => esc_html__( 'Portfolio Item', 'agni-cartify' ),
        'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite' 			=> array( 'slug' => 'portfolio', 'with_front' => false ),
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-portfolio',
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        // 'can_export'          => true,
        'show_in_rest' 		  => true,
    );

        
    register_post_type( 'portfolio', $post_type_args );

    $tax_args = array(
        'hierarchical' => true, 
        'label' => esc_html__( 'Portfolio Categories', 'agni-cartify' ), 
        'singular_label' => esc_html__( 'Portfolio Category', 'agni-cartify' ), 
        'show_ui' => true, 
        'show_admin_column' => true, 
        'query_var' => true, 
        'rewrite' => true,
        'show_in_rest' => true,
    );

    register_taxonomy( 'portfolio_category', array( 'portfolio' ), $tax_args );
    
}
