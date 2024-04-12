<?php 

add_filter( 'agni_importer_exporter_parser', 'agni_importer_content_parser', 10, 3 );


function agni_importer_content_parser( $content, $contentChoice, $options ){

    $result = '';

    // print_r( $content );
    switch ($contentChoice) {
        case 'posts':
            $result = apply_filters( 'agni_content_posts', $content, $options );
            break;
        case 'categories':
            $result = apply_filters( 'agni_content_categories', $content, $options );
            break;
        case 'tags':
            $result = apply_filters( 'agni_content_tags', $content, $options );
            break;
        case 'comments':
            $result = apply_filters( 'agni_content_comments', $content, $options );
            break;
        case 'pages':
            $result = apply_filters( 'agni_content_pages', $content, $options );
            break;
        case 'media':
            $result = apply_filters( 'agni_content_attachments', $content, $options );
            break;
        case 'products':
            $result = apply_filters( 'agni_content_products', $content, $options );
            break;
        case 'products_categories':
            $result = apply_filters( 'agni_content_products_categories', $content, $options );
            break;
        case 'products_tags':
            $result = apply_filters( 'agni_content_products_tags', $content, $options );
            break;
        case 'products_brand':
            $result = apply_filters( 'agni_content_products_brand', $content, $options );
            break;
        case 'products_reviews':
            $result = apply_filters( 'agni_content_products_reviews', $content, $options );
            break;
        case 'products_attributes':
            $result = apply_filters( 'agni_content_products_attributes', $content, $options );
            break;
        case 'blocks':
            $result = apply_filters( 'agni_content_blocks', $content, $options );
            break;
        case 'agni_block':
            $result = apply_filters( 'agni_content_agni_block', $content, $options );
            break;
        case 'block_categories':
            $result = apply_filters( 'agni_content_block_categories', $content, $options );
            break;
        case 'portfolio':
            $result = apply_filters( 'agni_content_portfolio', $content, $options );
            break;
        case 'portfolio_categories':
            $result = apply_filters( 'agni_content_portfolio_categories', $content, $options );
            break;
        case 'agni_wc_wishlist':
            $result = apply_filters( 'agni_content_agni_wc_wishlist', $content, $options );
            break;
        case 'headers':
            $result = apply_filters( 'agni_content_headers', $content, $options );
            break;
        case 'sliders':
            $result = apply_filters( 'agni_content_sliders', $content, $options );
            break;
        case 'product_layouts':
            $result = apply_filters( 'agni_content_product_layouts', $content, $options );
            break;
        case 'fonts':
            $result = apply_filters( 'agni_content_fonts', $content, $options );
            break;
        case 'widgets':
            $result = apply_filters( 'agni_content_widgets', $content, $options );
            break;
        case 'menus':
            $result = apply_filters( 'agni_content_menus', $content, $options );
            break;
        case 'theme_options':
            $result = apply_filters( 'agni_content_theme_options', $content, $options );
            break;
        case 'set_menu_locations':
            $result = apply_filters( 'agni_content_set_menu_locations', $content, $options );
            break;
        case 'set_homepage':
            $result = apply_filters( 'agni_content_set_homepage', $content, $options );
            break;
        
        default:
            break;
    }

    return $result;

}
