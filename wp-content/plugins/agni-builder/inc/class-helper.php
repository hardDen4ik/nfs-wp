<?php

class AgniBuilderHelper{

    public static function building_products_query( $attributes ){
        $per_page = '';
        $products_type = '';
        $product_ids = array();
        $categories_name_array = array();
    
        if( isset($attributes['count']) ){
            $per_page = $attributes['count'];
        }
        // else if( isset($attributes['rows']) && $attributes['columns'] ){
        //     $per_page = $attributes['rows']*$attributes['columns'];
        // }

    
        if( isset($attributes['category_ids']) && !empty($attributes['category_ids']) ){
            foreach($attributes['category_ids'] as $cat_id){
                $cat = get_term( $cat_id );

                if( !is_null( $cat ) ){
                    $categories_name_array[]= $cat->slug;
                }
            }
        }
    
        if( isset( $attributes['product_ids'] ) && !empty($attributes['product_ids']) ){
            $product_ids = $attributes['product_ids'];
        }
    
        if( isset( $attributes['products_type'] ) && $attributes['products_type'] ){
            $products_type = $attributes['products_type'];
        }
    
        if( isset( $attributes['order_by'] ) && $attributes['order_by'] ){
            $order_by = $attributes['order_by'];
        }
        
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish', // if you don't want drafts to be returned
            'ignore_sticky_posts' => true,
            'posts_per_page' => $per_page, // how much to show at once
            'suppress_filters' => true
        );


        if( isset($attributes['paged']) ){
            $args['paged'] = $attributes['paged'];
        }
        if( isset($attributes['offset']) ){
            $args['offset'] = $attributes['offset'];
        }
    
        if( !empty($product_ids) ){
            $args['post__in'] = $product_ids;
        }
    
        if( !empty($categories_name_array) ){
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $categories_name_array,
                'include_children' => false,
            );
        }
        
        $args['tax_query'][] = array(
            'taxonomy'         => 'product_visibility',
            'terms'            => array( 'exclude-from-catalog', 'exclude-from-search' ),
            'field'            => 'name',
            'operator'         => 'NOT IN',
            'include_children' => false,
        );
    
        switch( $products_type ){
            case 'on-sale' :
                $product_on_sale_IDs = wc_get_product_ids_on_sale();
                $meta_query   = array();
                $meta_query[] = WC()->query->visibility_meta_query();
                $meta_query[] = WC()->query->stock_status_meta_query();
                $meta_query   = array_filter( $meta_query );
                $args['meta_query'] = $meta_query;
                $args['post__in'] = array_merge( array( 0 ), $product_on_sale_IDs );
                break;
    
            case 'featured' :
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                );
                $args['tax_query']['relation'] = 'AND';
                break;
    
            case 'hot-label':
                $products_list_hot = '';

                if( function_exists( 'cartify_get_theme_option' ) ){
                    $products_list_hot = cartify_get_theme_option( 'shop_settings_label_hot', '' );
                }
                $args['post__in'] = array_merge( array( 0 ), $products_list_hot );
                break;
    
            case 'new-label':
                $products_list_new = '';

                if( function_exists( 'cartify_get_theme_option' ) ){
                    $products_list_new = cartify_get_theme_option( 'shop_settings_label_new', '' );
                }
                $args['post__in'] = array_merge( array( 0 ), $products_list_new );
                break;
    
        }
    
        switch( $order_by ){
            case '1': // Title A - Z
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            case '2': // Title Z - A
                $args['orderby'] = 'title';
                $args['order'] = 'DESC';
                break;
            case '3': // Price High first
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case '4': // Price Low first
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
            case '5': // Newest first
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
            case '6': // Sales High first
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case '7': // Rating High first
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case '8': // custom menu order
                $args['orderby'] = 'menu_order';
                $args['order'] = 'DESC';
                break;
            default: // Newest first
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
    
        }
        
        $query = new WP_Query( $args );
    
        return $query; //$args;
    }

    public static function building_products_categories_query( $attributes ){
        $args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => filter_var($attributes['hide_empty'], FILTER_VALIDATE_BOOLEAN),
            'pad_counts' => true,
            'number' => $attributes['count'],
            'suppress_filters' => true
        );

        if( isset($attributes['category_ids']) && !empty($attributes['category_ids']) ){
            $args['include'] = $attributes['category_ids'];
        }

        if( isset($attributes['fields']) && !empty($attributes['fields']) ){
            $args['fields'] = $attributes['fields'];
        }

        if( isset($attributes['offset']) ){
            $args['offset'] = $attributes['offset'];
        }


        if( isset( $attributes['order_by'] ) && $attributes['order_by'] ){
            $order_by = $attributes['order_by'];
        }

        switch( $order_by ){

            case '1': // Title A to Z
                $args['orderby'] = 'name';
                $args['order'] = 'ASC';
                break;
            case '2': // Title Z to A
                $args['orderby'] = 'name';
                $args['order'] = 'DESC';
                break;
            case '3': // Count
                $args['orderby'] = 'count';
                $args['order'] = 'DESC';
                break;
            default:
                $args['order'] = 'ASC';
        }

        return new WP_Term_Query($args);
        // return get_terms( $args ); 

    }

    public static function prepare_classes( $classes ) {
        return trim( preg_replace('!\s+!', ' ', join(' ', $classes) ) );
    }

    public static function prepare_slick_options( $options ){
        $options = preg_replace('/[\s+\']/', '', $options);
        $options = preg_replace( '/(\w+):/', '"$1":', $options );
    
        return '{' . $options . '}';
    }

    public static function prepare_camel_to_dash( $class ) {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $class));
    }

    public static function cartify_prepare_icon( $icon = '' ){
        $icon_html = '';
        
        if( preg_match('/feather:/', $icon, $matches) ) {
            $icon_name = str_replace( $matches[0], '', $icon );
            $icon_html = '<img width="20" height="20" src="' . esc_url( AGNI_FRAMEWORK_ICONS_URL . '/feather/' . esc_attr( $icon_name ) . '.svg' ) . '"/>';
            
        }
        else if( preg_match('/ionicons:/', $icon, $matches) ){
            $icon_name = str_replace( $matches[0], '', $icon );
            $icon_html = '<img width="20" height="20" src="' . esc_url( AGNI_FRAMEWORK_ICONS_URL . '/ionicons-outlined/' . esc_attr( $icon_name ) . '.svg' ) . '"/>';
        }
        else if( preg_match('/miscellaneous:/', $icon, $matches) ){
            $icon_name = str_replace( $matches[0], '', $icon );
            $icon_html = '<img width="20" height="20" src="' . esc_url( AGNI_FRAMEWORK_ICONS_URL . '/miscellaneous/' . esc_attr( $icon_name ) . '.svg' ) . '"/>';
        }
        else if( preg_match('/ fa-/', $icon, $matches) ){
            $icon_html = '<i class="' . esc_attr( $icon ) . '"></i>';
        }
        else if( preg_match('/lni /', $icon, $matches) ){
            $icon_html = '<i class="' . esc_attr( $icon ) . '"></i>';
        }
    
        return $icon_html;
        // return $icon_src
    }
}

$AgniBuilderHelper = new AgniBuilderHelper();