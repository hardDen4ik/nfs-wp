<?php 


add_action( 'rest_api_init', 'agni_cartify_register_rest_api', 10 );

add_filter( 'rest_prepare_agni_wc_wishlist', 'agni_cartify_wishlist_rest_meta_preparation', 10, 3 );
add_filter( 'rest_prepare_post', 'agni_cartify_post_rest_categories_preparation', 10, 3 );
add_filter( 'woocommerce_rest_prepare_product_object', 'agni_cartify_woocommerce_product_rest_meta_preparation', 10, 3 );
add_filter( 'woocommerce_rest_prepare_product_cat', 'agni_cartify_woocommerce_product_cat_rest_meta_preparation', 10, 3 );
add_filter( 'woocommerce_rest_prepare_product_tag', 'agni_cartify_woocommerce_product_tag_rest_meta_preparation', 10, 3 );
add_filter( 'rest_product_collection_params', 'agni_cartify_collection_limits', 10, 1 );
add_filter( 'rest_attachment_collection_params', 'agni_cartify_collection_limits', 10, 1 );
add_filter( 'rest_page_collection_params', 'agni_cartify_collection_limits', 10, 1 );


function agni_cartify_post_rest_categories_preparation( $data, $post, $context ){

    $terms = array();
    foreach ($data as $value) {
        if( isset( $value['categories'] ) ){
            foreach ($value['categories'] as $key => $category) {
                $term = get_term_by( 'ID', $category, 'category' );

                $terms[] = array(
                    'term_id' => $term->term_id,
                    'name' => $term->name,
                    'slug' => $term->slug,
                );
            }
        }
        
    };

    $data->data['categories_detailed'] = $terms;

    return $data;
}

// Add Rest field for wishlist post type
function agni_cartify_wishlist_rest_meta_preparation( $data, $post, $context ) {

    $agni_wishlist_product_ids = get_post_meta( $post->ID, 'agni_wishlist_product_ids', true );

    if( isset( $agni_wishlist_product_ids ) ) {
        $data->data['meta']['agni_wishlist_product_ids'] = $agni_wishlist_product_ids;
    }

    return $data;
}

function agni_cartify_woocommerce_product_rest_meta_preparation( $data, $product, $context ){
    // $attributes = array();
    // // print_r( $data );
    // foreach ($data as $value) {
        
    //     if( !empty( $value['attributes'] ) ){
    //         foreach ($value['attributes'] as $key => $attribute) {
    //             $attributes[$key] = $attribute;
    //             $taxonomy_name = wc_attribute_taxonomy_name_by_id( $attribute['id'] );
    //             if( !empty( $taxonomy_name ) ){
    //                 $attributes[$key]['taxonomy_name'] = $taxonomy_name;
    //             }
    //         }
            
    //     };
    // }

    // $data->data['attributes'] = $attributes;

    $terms = get_the_terms( $product->get_id(), 'product_brand' );
    $brands = array();

    if( $terms ){
        foreach ($terms as $key => $term) {
            $brands[] =  array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }
        
    }

    $data->data['brands'] = $brands;

    // print_r( $data );

    return $data;
}

// Add Rest field for product category
function agni_cartify_woocommerce_product_cat_rest_meta_preparation( $data, $term, $context ) {

    $term_data = get_term_by( 'ID', $term->parent, 'product_cat' );

    // print_r( $term_data );
    if( $term_data->slug != null ){
        $data->data['parent_slug'] = $term_data->slug;
    }
    else{
        $data->data['parent_slug'] = "";
    }

    $agni_product_cat_icon_id = get_term_meta($term->term_id, 'agni_product_cat_icon_id', true);
    $agni_product_cat_banner_image_id = get_term_meta($term->term_id, 'agni_product_cat_banner_image_id', true);
    $agni_product_cat_banner_content_bg = get_term_meta($term->term_id, 'agni_product_cat_banner_content_bg', true);
    $agni_product_cat_content_block = get_term_meta($term->term_id, 'agni_product_cat_content_block', true);
    $agni_term_header_id = get_term_meta($term->term_id, 'agni_term_header_id', true);
    $agni_term_slider_id = get_term_meta($term->term_id, 'agni_slider_id', true);
    $agni_term_footer_block_id = get_term_meta($term->term_id, 'agni_term_footer_block_id', true);

    if( isset( $agni_product_cat_icon_id ) ){
        $data->data['meta']['agni_product_cat_icon_id'] = $agni_product_cat_icon_id;
    }
    if( isset( $agni_product_cat_banner_image_id ) ){
        $data->data['meta']['agni_product_cat_banner_image_id'] = $agni_product_cat_banner_image_id;
    }
    if( isset( $agni_product_cat_banner_content_bg ) ){
        $data->data['meta']['agni_product_cat_banner_content_bg'] = $agni_product_cat_banner_content_bg;
    }
    if( isset( $agni_product_cat_content_block ) ){
        $data->data['meta']['agni_product_cat_content_block'] = $agni_product_cat_content_block;
    }
    if( isset( $agni_term_header_id ) ){
        $data->data['meta']['agni_term_header_id'] = $agni_term_header_id;
    }
    if( isset( $agni_term_slider_id ) ){
        $data->data['meta']['agni_slider_id'] = $agni_term_slider_id;
    }
    if( isset( $agni_term_footer_block_id ) ){
        $data->data['meta']['agni_term_footer_block_id'] = $agni_term_footer_block_id;
    }

    return $data;
}
// Add Rest field for product tag
function agni_cartify_woocommerce_product_tag_rest_meta_preparation( $data, $term, $context ) {

    $agni_product_tag_banner_image_id = get_term_meta($term->term_id, 'agni_product_tag_banner_image_id', true);
    $agni_product_tag_banner_content_bg = get_term_meta($term->term_id, 'agni_product_tag_banner_content_bg', true);
    $agni_product_tag_content_block = get_term_meta($term->term_id, 'agni_product_tag_content_block', true);
    $agni_term_header_id = get_term_meta($term->term_id, 'agni_term_header_id', true);
    $agni_term_slider_id = get_term_meta($term->term_id, 'agni_slider_id', true);
    $agni_term_footer_block_id = get_term_meta($term->term_id, 'agni_term_footer_block_id', true);

    if( isset( $agni_product_tag_banner_image_id ) ){
        $data->data['meta']['agni_product_tag_banner_image_id'] = $agni_product_tag_banner_image_id;
    }
    if( isset( $agni_product_tag_banner_content_bg ) ){
        $data->data['meta']['agni_product_tag_banner_content_bg'] = $agni_product_tag_banner_content_bg;
    }
    if( isset( $agni_product_tag_content_block ) ){
        $data->data['meta']['agni_product_tag_content_block'] = $agni_product_tag_content_block;
    }
    if( isset( $agni_term_header_id ) ){
        $data->data['meta']['agni_term_header_id'] = $agni_term_header_id;
    }
    if( isset( $agni_term_slider_id ) ){
        $data->data['meta']['agni_slider_id'] = $agni_term_slider_id;
    }
    if( isset( $agni_term_footer_block_id ) ){
        $data->data['meta']['agni_term_footer_block_id'] = $agni_term_footer_block_id;
    }

    return $data;
}


function agni_cartify_collection_limits( $query_params ){
    $query_params['per_page']['maximum'] = 2000;

    return $query_params;
}

function agni_cartify_register_rest_api(){
    // menu
    // kirki options


    $current_user_can = current_user_can( 'edit_theme_options' );

    register_rest_route( 'agni-cartify/v1', '/menus', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'agni_cartify_get_menus',
        'permission_callback' => function() use($current_user_can){
            return $current_user_can;
        },
    ) );
    register_rest_route( 'agni-cartify/v1', '/theme_options', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'agni_cartify_get_theme_options',
        'permission_callback' => function() use($current_user_can){
            return $current_user_can;
        },
    ) );
    register_rest_route( 'agni-cartify/v1', '/widgets', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'agni_cartify_get_widgets',
        'permission_callback' => function() use($current_user_can){
            return $current_user_can;
        },
    ) );

    register_rest_route( 'agni-cartify/v1', '/menu_locations', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'agni_cartify_get_menu_locations',
        'permission_callback' => function() use($current_user_can){
            return $current_user_can;
        },
    ) );

    register_rest_route( 'agni-cartify/v1', '/homepage', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'agni_cartify_get_homepage',
        'permission_callback' => function() use($current_user_can){
            return $current_user_can;
        },
    ) );

    

    
}

function agni_cartify_get_menus(){

    $menus = wp_get_nav_menus();

    if( empty( $menus ) ){
        return;
    }

    $menu_items = $menus_list = array();

    foreach ( $menus as $key => $menu ) {
        $get_menu_items = wp_get_nav_menu_items( $menu );

        $revised_get_menu_items = array();

        foreach ($get_menu_items as $key => $get_menu_item) {
            $revised_get_menu_items[$key] = json_decode(json_encode($get_menu_item), true);

            $revised_get_menu_items[$key]['agni_menu_item_label'] = get_post_meta( $get_menu_item->ID, 'agni_menu_item_label', true );
            $revised_get_menu_items[$key]['agni_menu_item_show_menu_on'] = get_post_meta( $get_menu_item->ID, 'agni_menu_item_show_menu_on', true );
            $revised_get_menu_items[$key]['agni_menu_item_hide_menu_text'] = get_post_meta( $get_menu_item->ID, 'agni_menu_item_hide_menu_text', true );
            $revised_get_menu_items[$key]['agni_menu_item_block_choice'] = get_post_meta( $get_menu_item->ID, 'agni_menu_item_block_choice', true );
            $revised_get_menu_items[$key]['agni_menu_item_width'] = get_post_meta( $get_menu_item->ID, 'agni_menu_item_width', true );
            $revised_get_menu_items[$key]['agni_menu_item_height'] = get_post_meta( $get_menu_item->ID, 'agni_menu_item_height', true );
            $revised_get_menu_items[$key]['agni_menu_item_fullwidth'] = get_post_meta( $get_menu_item->ID, 'agni_menu_item_fullwidth', true );
            $revised_get_menu_items[$key]['agni_menu_item_icon'] = get_post_meta( $get_menu_item->ID, 'agni_menu_item_icon', true );

        }

        $menu_items[$menu->slug] = $revised_get_menu_items;
        
    }

    $menus_list = array(
        array( 
            'menus' => $menus,
            'menu_items' => $menu_items,
            'locations' => get_nav_menu_locations() 
        )
    );


    return $menus_list;
}


function agni_cartify_get_theme_options(){

    // if( !current_user_can( 'edit_theme_options' ) ){
    //     return;
    // }

    $all_theme_options = get_theme_mods();

    unset($all_theme_options['nav_menu_locations']);
    unset($all_theme_options['api_settings_instagram_token']);
    unset($all_theme_options['agni_product_registration_envato_token']);
    unset($all_theme_options['agni_product_registration_email']);
    unset($all_theme_options['api_settings_facebook_app_id']);
    unset($all_theme_options['api_settings_google_client_id']);
    unset($all_theme_options['api_settings_google_map_api']);
    unset($all_theme_options['api_settings_facebook_app_id']);
    
    return array( $all_theme_options );
}

function agni_cartify_get_menu_locations(){

    return array( array( "nav_menu_locations" => get_theme_mod( 'nav_menu_locations' ) ) );
}


function agni_cartify_get_homepage(){
    
    return array( array( "id" => get_option( 'page_on_front' ) ) );
}

function agni_cartify_get_widgets(){

	// global $wp_registered_widget_controls;

    // $widget_controls = $wp_registered_widget_controls;
    global $wp_registered_widget_controls;

    $active_widgets = $widgets = array();

    foreach ($wp_registered_widget_controls as $key => $widget) {
        $active_widgets[$widget['id_base']][] = $widget['id'];
    }

    foreach ($active_widgets as $id_base => $widget_ids) {
        $widgets_data = get_option( 'widget_' . $id_base );

        foreach ($widget_ids as $key => $widget_id) {
            $instance_id = str_replace( $id_base . '-', '', $widget_id );

            if( isset( $widgets_data[$instance_id] ) ){
                $widgets[$widget_id] = $widgets_data[$instance_id];
            }
            
        }
    }


    $result = array(
        array(
            'sidebars_widgets' => get_option( 'sidebars_widgets' ),
            'widgets' => array_filter($widgets)
        )
    );

    return $result;
}


