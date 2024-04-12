<?php 

/*
import products after product categories, attributes, tags, brands
import posts after categories, tags
import comments after products, posts

import menu at the end.

media attachments are pending
slider revolution is pending


*/

add_filter( 'agni_content_posts', 'agni_import_export_content_posts', 10 , 2 );
add_filter( 'agni_content_categories', 'agni_import_export_content_categories', 10, 2 );
add_filter( 'agni_content_tags', 'agni_import_export_content_tags', 10, 2 );
add_filter( 'agni_content_comments', 'agni_import_export_content_comments', 10, 2 );
add_filter( 'agni_content_pages', 'agni_import_export_content_pages', 10, 2 );
add_filter( 'agni_content_attachments', 'agni_import_export_content_attachments', 10, 2 );
add_filter( 'agni_content_products_attributes', 'agni_import_export_content_products_attributes', 10, 2 );
add_filter( 'agni_content_products', 'agni_import_export_content_products', 10, 2 );
add_filter( 'agni_content_products_categories', 'agni_import_export_content_products_categories', 10, 2 );
add_filter( 'agni_content_products_tags', 'agni_import_export_content_products_tags', 10, 2 );
add_filter( 'agni_content_products_brand', 'agni_import_export_content_products_brand', 10, 2 );
add_filter( 'agni_content_products_reviews', 'agni_import_export_content_products_reviews', 10, 2 );
add_filter( 'agni_content_blocks', 'agni_import_export_content_blocks', 10, 2 );
add_filter( 'agni_content_agni_block', 'agni_import_export_content_agni_block', 10, 2 );
add_filter( 'agni_content_block_categories', 'agni_import_export_content_block_categories', 10, 2 );
add_filter( 'agni_content_portfolio', 'agni_import_export_content_portfolio', 10, 2 );
add_filter( 'agni_content_portfolio_categories', 'agni_import_export_content_portfolio_categories', 10, 2 );
add_filter( 'agni_content_agni_wc_wishlist', 'agni_import_export_content_agni_wc_wishlist', 10, 2 );
add_filter( 'agni_content_headers', 'agni_import_export_content_headers', 10, 2 );
add_filter( 'agni_content_sliders', 'agni_import_export_content_sliders', 10, 2 );
add_filter( 'agni_content_product_layouts', 'agni_import_export_content_product_layouts', 10, 2 );
add_filter( 'agni_content_fonts', 'agni_import_export_content_fonts', 10, 2 );
add_filter( 'agni_content_widgets', 'agni_import_export_content_widgets', 10, 2 );
add_filter( 'agni_content_menus', 'agni_import_export_content_menus', 10, 2 );
add_filter( 'agni_content_theme_options', 'agni_import_export_content_theme_options', 10, 2 );
add_filter( 'agni_content_set_menu_locations', 'agni_import_export_content_set_menu_locations', 10, 2 );
add_filter( 'agni_content_set_homepage', 'agni_import_export_content_set_homepage', 10, 2 );

add_filter( 'agni_content_insert_post', 'agni_import_export_content_insert_post', 10, 1 );
add_filter( 'agni_content_insert_term', 'agni_import_export_content_insert_term', 10, 2 );
add_filter( 'agni_content_insert_product', 'agni_import_export_content_insert_product', 10, 3 );


function agni_import_export_content_posts( $posts, $options ){
    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    foreach ($posts as $key => $post) {

        $existing_post = get_page_by_title( $post['title']['raw'], OBJECT, $post['type'] );
        
        if( is_null( $existing_post ) ){
            $new_post_id = apply_filters( 'agni_content_insert_post', $post );

            $new_category = array();
            $new_tag = array();
            foreach ($post['categories'] as $key => $value) {
                $new_category[$key] = $new_demo_content_options['categories'][$value];
            }
            foreach ($post['tags'] as $key => $value) {
                $new_tag[$key] = $new_demo_content_options['tags'][$value];
            }

            wp_set_post_terms( $new_post_id, $new_category, 'category' );
            wp_set_post_terms( $new_post_id, $new_tag, 'post_tag' );

            update_post_meta( $new_post_id, 'agni_page_header_choice', $new_demo_content_options['headers'][$post['meta']['agni_page_header_choice']] );
            update_post_meta( $new_post_id, 'agni_slider_id', $new_demo_content_options['sliders'][$post['meta']['agni_slider_id']] ); 
            update_post_meta( $new_post_id, 'agni_footer_block_id', $new_demo_content_options['agni_block'][$post['meta']['agni_footer_block_id']] ); 

            if( $post['featured_media'] !== 0 ){
                update_post_meta( $new_post_id, '_thumbnail_id', $new_demo_content_options['media'][$post['featured_media']] );
            }
        }
        else{
            $new_post_id = $existing_post->ID;
        }


        $prepare_options[$post['id']] = $new_post_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'posts' );

    return agni_prepare_return_success_array( 'Posts added' );
}

function agni_import_export_content_categories( $categories, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $new_category = array();
    foreach ($categories as $key => $category) {

        $term_exists = term_exists( $category['slug'], 'category' );

        if( !$term_exists ){
            // $category['parent'] = 0;
            $new_category[$category['id']] = apply_filters( 'agni_content_insert_term', $category, 'category' );

            $header_id = $category['meta']['agni_term_header_id'];
            $slider_id = $category['meta']['agni_slider_id'];
            $footer_block_id = $category['meta']['agni_term_footer_block_id'];

            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_term_header_id', $new_demo_content_options['headers'][$header_id]  );
            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_slider_id', $new_demo_content_options['sliders'][$slider_id]  );
            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_term_footer_block_id', $new_demo_content_options['agni_block'][$footer_block_id]  );
        }
        else{
            $new_category[$category['id']] = $term_exists;

        }

        $new_term_id = $new_category[$category['id']]['term_id'];

        $prepare_options[$category['id']] = $new_term_id;
    }

    foreach ($categories as $key => $category) {    
        if( $category['parent'] != 0 ){
            $new_parent_term_id = $prepare_options[$category['parent']];
            wp_update_term( $new_category[$category['id']]['term_id'], 'category', array(
                'parent' => $new_parent_term_id
            ) );
        }
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'categories' );
    
    return agni_prepare_return_success_array( 'Categories added' );
}

function agni_import_export_content_tags( $tags, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $new_tag = array();
    foreach ($tags as $key => $tag) {

        $term_exists = term_exists( $tag['slug'], 'post_tag' );

        if( !$term_exists ){
            $new_tag[$tag['id']] = apply_filters( 'agni_content_insert_term', $tag, 'post_tag' );

            
            $header_id = $tag['meta']['agni_term_header_id'];
            $slider_id = $tag['meta']['agni_slider_id'];
            $footer_block_id = $tag['meta']['agni_term_footer_block_id'];

            update_term_meta( $new_tag[$tag['id']]['term_id'], 'agni_term_header_id', $new_demo_content_options['headers'][$header_id]  );
            update_term_meta( $new_tag[$tag['id']]['term_id'], 'agni_slider_id', $new_demo_content_options['sliders'][$slider_id]  );
            update_term_meta( $new_tag[$tag['id']]['term_id'], 'agni_term_footer_block_id', $new_demo_content_options['agni_block'][$footer_block_id]  );
        }
        else{
            $new_tag[$tag['id']] = $term_exists;

        }

        $new_term_id = $new_tag[$tag['id']]['term_id'];

        $prepare_options[$tag['id']] = $new_term_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'tags' );

    return agni_prepare_return_success_array( 'Tags added' );
}

function agni_import_export_content_comments( $comments, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );
    
    foreach ($comments as $key => $comment) {

        $post_id = $new_demo_content_options['posts'][$comment['post']];

        $new_comment_id = wp_insert_comment(array(
            'comment_post_ID' => $post_id,
            'comment_parent' => $comment['parent'],
            'comment_author' => $comment['author_name'],
            'comment_author_url' => $comment['author_url'],
            'comment_author_email' => $comment['author_email'],
            'comment_date' => $comment['date'],
            'comment_date_gmt' => $comment['date_gmt'],
            "comment_content" => $comment['content']['raw'],
            'comment_approved' => $comment['status'] == 'approved' ? 1 : 0,
            'comment_type' => $comment['type'],
            'comment_meta' => $comment['meta']
        ));

        $prepare_options[$comment['id']] = $new_comment_id;
    }


    foreach ($comments as $key => $comment) {    
        if( $comment['parent'] != 0 ){
            $new_parent_comment_id = $prepare_options[$comment['parent']];
            wp_update_comment(array(
                'comment_ID' => $prepare_options[$comment['id']],
                'comment_parent' => $new_parent_comment_id,
            ));
        }
    }


    agni_prepare_importer_exporter_options( $prepare_options, 'comments' );

    return agni_prepare_return_success_array( 'Comments added' );
}

function agni_import_export_content_pages( $posts, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    foreach ($posts as $key => $post) {

        $existing_post = get_page_by_title( $post['title']['raw'], OBJECT, $post['type'] );
        
        if( is_null( $existing_post ) ){
            $new_post_id = apply_filters( 'agni_content_insert_post', $post );


            update_post_meta( $new_post_id, 'agni_page_header_choice', $new_demo_content_options['headers'][$post['meta']['agni_page_header_choice']] );
            update_post_meta( $new_post_id, 'agni_slider_id', $new_demo_content_options['sliders'][$post['meta']['agni_slider_id']] ); 
            update_post_meta( $new_post_id, 'agni_footer_block_id', $new_demo_content_options['agni_block'][$post['meta']['agni_footer_block_id']] ); 

            if( $post['featured_media'] !== 0 ){
                update_post_meta( $new_post_id, '_thumbnail_id', $new_demo_content_options['media'][$post['featured_media']] );
            }
        }
        else{
            $new_post_id = $existing_post->ID;
        }


        $prepare_options[$post['id']] = $new_post_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'pages' );


    return agni_prepare_return_success_array( 'Pages added' );
}

function agni_import_export_content_attachments( $attachments, $options ){
    // print_r( $attachments );
    $prepare_options = array();

    // $attachments = array_slice( $attachments, 0, 60 );

    // add_action('init', 'agni_import_export_remove_extra_image_sizes', 10);

    // add_filter( 'intermediate_image_sizes_advanced', 'agni_importer_exporter_disable_upload_sizes', 10, 2 );

    foreach ($attachments as $key => $args) {

        $new_demo_content_options_media = array();
        $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

        if( isset( $new_demo_content_options['media'] ) ){
            $new_demo_content_options_media = $new_demo_content_options['media'];
        }

        // print_r( $new_demo_content_options_media );
        // echo array_key_exists( $args['id'], $new_demo_content_options_media );
        // echo $args['id'];

        // if( !array_key_exists( $args['id'], $new_demo_content_options_media ) ){

            // print_r( $args );

            $url = $args['source_url']; 

            $date = wp_date( 'Y/m', strtotime($args['date']) );

            $upload_dir = wp_upload_dir();

            		
		   	global $wp_filesystem;
            // Initialize the WP filesystem, no more using 'file-put-contents' function
            if (empty($wp_filesystem)) {
                require_once (ABSPATH . '/wp-admin/includes/file.php');
                WP_Filesystem();
            }


            if ( !$wp_filesystem->exists( $upload_dir['basedir'] . '/' . $date . '/' . basename($url) ) ) {

                // echo 'url' . $url;

                if( !class_exists( 'WP_Http' ) ){
                    include_once( ABSPATH . WPINC . '/class-http.php' );
                }

                $http = new WP_Http();
                $response = $http->request( $url );

                // $args = array(
                //     'timeout'     => 5,
                //     'redirection' => 5,
                //     'blocking'    => true,
                //     'httpversion' => '1.0',
                //     'sslverify'   => true, // make it true for live
                // );

                // Make an API request.
                // $response = wp_remote_request( esc_url_raw( $url ), $args );
                // echo esc_url_raw( $url );
                // print_r( $response['body'] );

                if( is_wp_error( $response ) ){
                    return array( 
                        'success' => false,
                        'data' => $response->get_error_message()
                    );

                    exit;
                }



                $upload = wp_upload_bits( basename($url), null, $response['body'], $date );

                // print_r( $upload );

                if( !empty( $upload['error'] ) ) {
                    return array( 
                        'success' => false,
                        'data' => $upload['error']
                    );
                }
                
                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();

                $file = $upload['file'];

                $media = array(
                    'import_id'         => $args['id'],
                    'post_date'         => $args['date'],
                    'post_date_gmt'     => $args['date_gmt'],
                    'post_modified'     => $args['modified'],
                    'post_modified_gmt' => $args['modified_gmt'],
                    'guid'              => $args['guid']['raw'],
                    'post_title'        => $args['title']['raw'],
                    'post_mime_type'    => $args['mime_type'],
                    'post_type'         => $args['type'],
                    'post_status'       => $args['status'], 
                    'post_content'      => $args['description']['raw'],
                    'post_excerpt'      => $args['caption']['raw'],
                    'comment_status'    => $args['comment_status'],
                    'ping_status'       => $args['ping_status'],
                    'meta_input'        => $args['meta'],
            
                );
            


                $existing_attachment = get_page_by_title( $args['title']['raw'], OBJECT, $args['type'] );

                if( is_null( $existing_attachment ) ){
                    $attach_id = wp_insert_attachment( $media, $file, $args['post'], 0, true );

                    // require_once( ABSPATH . 'wp-admin/includes/image.php' );

                    // $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

                    // wp_update_attachment_metadata( $attach_id,  $attach_data );

                    update_post_meta($attach_id, '_wp_attachment_image_alt', $args['alt_text']);

                }
                else{
                    $attach_id = $existing_attachment->ID;
                }

                $new_demo_content_options_media[$args['id']] = $attach_id;
            }
        // }

        agni_prepare_importer_exporter_options( $new_demo_content_options_media, 'media' );

        // $prepare_options[$args['id']] = $attach_id;

    }

    // remove_action('init', 'agni_import_export_remove_extra_image_sizes');

    // remove_filter( 'intermediate_image_sizes_advanced', 'agni_importer_exporter_disable_upload_sizes', 10, 2 );

    // agni_prepare_importer_exporter_options( $prepare_options, 'media' );

    return agni_prepare_return_success_array( 'I am parsing Attachments' );
}

function agni_import_export_content_products_attributes( $attributes, $options ){

    $prepare_options = array();

    foreach ($attributes as $key => $attribute) {

        $attribute_id = wc_create_attribute(
            array(
                'name'         => $attribute['name'],
                'slug'         => $attribute['slug'],
                'type'         => $attribute['type'],
                'order_by'     => $attribute['order_by'],
                'has_archives' => $attribute['has_archives'],
            )
        );

        $prepare_options['attributes'][$attribute['id']] = $attribute_id;

        $register_taxonomy = register_taxonomy( $attribute['slug'], array( 'product' ), array() );

        foreach ($attribute['terms'] as $key => $term) {
            if( !is_wp_error($attribute_id) ){

                $term_exists = term_exists( $term['slug'], $attribute['slug'] );

                if( !$term_exists ){

                    $new_term[$term['id']] = wp_insert_term( $term['name'], $attribute['slug'], array(
                        'description' => $term['description'],
                        'slug'        => $term['slug'],
                        'menu_order'  => $term['menu_order'],
                        'count'       => $term['count'],
                        'agni_variation_swatch_field' =>  $term['agni_variation_swatch_field'],
                    ) );

                    update_term_meta( $new_term[$term['id']]['term_id'], 'agni_variation_swatch_field', $term['agni_variation_swatch_field'] );
                }
                else{
                    $new_term[$term['id']] = $term_exists;
                }

                $new_term_id = $new_term[$term['id']]['term_id'];

                $prepare_options['terms'][$term['id']] = $new_term_id;
            }
            // else{
            //     echo $attribute_id->get_error_message();
            // }
        }
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'products_attributes' );

    return agni_prepare_return_success_array( 'Products attributes added' );
}
function agni_import_export_content_products( $products, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );
    
    foreach ($products as $key => $product) {
            
        $existing_post = get_page_by_title( $product['name'], OBJECT, 'product' );

        if( is_null( $existing_post ) ){

            $new_product_id = apply_filters( 'agni_content_insert_product', $product, 'product' );
            
            if( !empty( $product['categories'] ) ){
                $product_categories = array();
                foreach ($product['categories'] as $key => $category) {
                    $product_categories[] = (int)$new_demo_content_options['products_categories'][$category['id']];
                }

                wp_set_post_terms( $new_product_id, $product_categories, 'product_cat' );
            }
            if( !empty( $product['tags'] ) ){
                $product_tags = array();
                foreach ($product['tags'] as $key => $tag) {
                    $product_tags[] = (int)$new_demo_content_options['products_tags'][$tag['id']];
                }

                wp_set_post_terms( $new_product_id, $product_tags, 'product_tag' );
            }
            if( !empty( $product['brands'] ) ){
                $product_brands = array();
                foreach ($product['brands'] as $key => $brand) {
                    $product_brands[] = (int)$new_demo_content_options['products_brand'][$brand['id']];
                }

                wp_set_post_terms( $new_product_id, $product_brands, 'product_brand' );
            }


            wp_set_post_terms( $new_product_id, $product['type'], 'product_type' );

            $meta_datas = array();

            foreach ($product['meta_data'] as $key => $meta) {
                $meta_datas[$meta["id"]] = array( 'key' => $meta["key"], 'value' => $meta["value"] );
            }

            foreach ($meta_datas as $key => $meta) {
                switch( $meta['key'] ){
                    case 'agni_page_header_choice':
                        update_post_meta( $new_product_id, 'agni_page_header_choice', $new_demo_content_options['headers'][$meta['value']] );
                        break;
                    case 'agni_slider_id':
                        update_post_meta( $new_product_id, 'agni_slider_id', $new_demo_content_options['sliders'][$meta['value']] ); 
                        break;
                    case 'agni_footer_block_id':
                        update_post_meta( $new_product_id, 'agni_footer_block_id', $new_demo_content_options['agni_block'][$meta['value']] ); 
                        break;
                    case 'agni_product_layout_choice':
                        update_post_meta( $new_product_id, 'agni_product_layout_choice', $new_demo_content_options['product_layouts'][$meta['value']] ); 
                        break;
                    default:
                        update_post_meta( $new_product_id, $meta['key'], $meta['value'] ); 

                }
            }

            $featured_thumbnail_id = '';
            $gallery_ids = array();

            if( !empty($product['images']) ){
                foreach ($product['images'] as $key => $image) {
                    if( $key == 0 ){
                        $featured_thumbnail_id = $new_demo_content_options['media'][$image['id']];
                    }
                    else{
                        $gallery_ids[] = $new_demo_content_options['media'][$image['id']];
                    }
                }
            }

            if( !empty( $featured_thumbnail_id ) ){
                update_post_meta( $new_product_id, '_thumbnail_id', $featured_thumbnail_id );
            }

            if( !empty( $gallery_ids ) ){
                update_post_meta( $new_product_id, '_product_image_gallery', implode(',', $gallery_ids) );
            }
           

            if( isset($product['type']) && $product['type'] === 'variable' ){
                $set_product = new WC_Product_Variable($new_product_id);
            } elseif( isset($product['type']) && $product['type'] === 'grouped' ){
                $set_product = new WC_Product_Grouped($new_product_id);
            } elseif( isset($product['type']) && $product['type'] === 'external' ){
                $set_product = new WC_Product_External($new_product_id);
            } else {
                $set_product = new WC_Product_Simple($new_product_id); // "simple" By default
            } 

            if( isset($product['downloadable']) && $product['downloadable'] ) {
                $set_product->set_downloads( isset($product['downloads']) ? $product['downloads'] : array() );
            }

            // $set_product->set_featured( true );
            
            $set_product->save();

            if( $product['type'] === 'variable' && isset($product['variations_products']) ){
                foreach ($product['variations_products'] as $key => $variation) {

                    $variation_id = apply_filters( 'agni_content_insert_product', $variation, 'product_variation', $new_product_id );

                    $prepare_options[$variation['id']] = $variation_id;


                    update_post_meta( $variation_id, '_variation_description', $variation['description'] );


                    $variation_gallery_ids = array();

                    if( !empty($variation['image']) ){
                        update_post_meta( $variation_id, '_thumbnail_id', $new_demo_content_options['media'][$variation['image']['id']] );
                    }

                    if( !empty( $variation['meta_data'] ) ){
                        foreach ($variation['meta_data'] as $key => $meta) {
                            if( $meta['key'] == 'agni_product_variation_images'){
                                foreach ($meta['value'] as $key => $image_id) {
                                    $variation_gallery_ids[] = $new_demo_content_options['media'][$image_id];
                                }
                            }
                        }
                    }

                    if( !empty( $variation_gallery_ids ) ){
                        update_post_meta( $variation_id, 'agni_product_variation_images', implode(',', $variation_gallery_ids) );
                    }

                    $set_variation = new WC_Product_Variation($variation_id);

                    if( isset($variation['downloadable']) && $variation['downloadable'] ) {
                        $set_variation->set_downloads( isset($variation['downloads']) ? $variation['downloads'] : array() );
                    }

                    $set_variation->save();
                }
            }
        }
        else{
            $new_product_id = $existing_post->ID;
            
        }
        
        $prepare_options[$product['id']] = $new_product_id;

    }


    agni_prepare_importer_exporter_options( $prepare_options, 'products' );

    return agni_prepare_return_success_array( 'Products added need revision for compare, addons products' );
}
function agni_import_export_content_products_categories( $categories, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $new_category = array();
    foreach ($categories as $key => $category) {

        $term_exists = term_exists( $category['slug'], 'product_cat' );

        if( !$term_exists ){
            // $category['parent'] = 0;

            $new_category[$category['id']] = apply_filters( 'agni_content_insert_term', $category, 'product_cat' );
            
            $banner_image_id = $category['meta']['agni_product_cat_banner_image_id'];
            $content_block = $category['meta']['agni_product_cat_content_block'];
            $header_id = $category['meta']['agni_term_header_id'];
            $slider_id = $category['meta']['agni_slider_id'];
            $footer_block_id = $category['meta']['agni_term_footer_block_id'];

            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_product_cat_banner_image_id', $new_demo_content_options['media'][$banner_image_id]  );
            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_product_cat_content_block', $new_demo_content_options['agni_block'][$content_block]  );
            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_term_header_id', $new_demo_content_options['headers'][$header_id]  );
            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_slider_id', $new_demo_content_options['sliders'][$slider_id]  );
            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_term_footer_block_id', $new_demo_content_options['agni_block'][$footer_block_id]  );
        
            if( !is_null($category['image']) ){
                update_term_meta( $new_category[$category['id']]['term_id'], 'thumbnail_id', $new_demo_content_options['media'][$category['image']['id']] );
            }
        }
        else{
            $new_category[$category['id']] = $term_exists;

        }
        $new_term_id = $new_category[$category['id']]['term_id'];

        $prepare_options[$category['id']] = $new_term_id;
    }

    foreach ($categories as $key => $category) {    
        if( $category['parent'] != 0 ){
            $new_parent_term_id = $prepare_options[$category['parent']];
            wp_update_term( $new_category[$category['id']]['term_id'], 'product_cat', array(
                'parent' => $new_parent_term_id
            ) );
        }
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'products_categories' );


    return agni_prepare_return_success_array( 'Product categories added' );
}
function agni_import_export_content_products_tags( $tags, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $new_tag = array();
    foreach ($tags as $key => $tag) {

        $term_exists = term_exists( $tag['slug'], 'product_tag' );

        if( !$term_exists ){
            $new_tag[$tag['id']] = apply_filters( 'agni_content_insert_term', $tag, 'product_tag' );

            
            $banner_image_id = $tag['meta']['agni_product_tag_banner_image_id'];
            $content_block = $tag['meta']['agni_product_tag_content_block'];
            $header_id = $tag['meta']['agni_term_header_id'];
            $slider_id = $tag['meta']['agni_slider_id'];
            $footer_block_id = $tag['meta']['agni_term_footer_block_id'];

            update_term_meta( $new_tag[$tag['id']]['term_id'], 'agni_product_tag_banner_image_id', $new_demo_content_options['media'][$banner_image_id]  );
            update_term_meta( $new_tag[$tag['id']]['term_id'], 'agni_product_tag_content_block', $new_demo_content_options['agni_block'][$content_block]  );
            update_term_meta( $new_tag[$tag['id']]['term_id'], 'agni_term_header_id', $new_demo_content_options['headers'][$header_id]  );
            update_term_meta( $new_tag[$tag['id']]['term_id'], 'agni_slider_id', $new_demo_content_options['sliders'][$slider_id]  );
            update_term_meta( $new_tag[$tag['id']]['term_id'], 'agni_term_footer_block_id', $new_demo_content_options['agni_block'][$footer_block_id]  );
        }
        else{
            $new_tag[$tag['id']] = $term_exists;

        }

        $new_term_id = $new_tag[$tag['id']]['term_id'];

        $prepare_options[$tag['id']] = $new_term_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'products_tags' );

    return agni_prepare_return_success_array( 'Products tags added' );
}


function agni_import_export_content_products_brand( $brands, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $new_brand = array();
    foreach ($brands as $key => $brand) {

        $term_exists = term_exists( $brand['slug'], 'product_brand' );

        if( !$term_exists ){
            $new_brand[$brand['id']] = apply_filters( 'agni_content_insert_term', $brand, 'product_brand' );

            
            $icon_id = $brand['meta']['agni_product_brand_icon_id'];
            $banner_image_id = $brand['meta']['agni_product_brand_banner_image_id'];
            $content_block = $brand['meta']['agni_product_brand_content_block'];
            $header_id = $brand['meta']['agni_term_header_id'];
            $slider_id = $brand['meta']['agni_slider_id'];
            $footer_block_id = $brand['meta']['agni_term_footer_block_id'];

            update_term_meta( $new_brand[$brand['id']]['term_id'], 'agni_product_brand_icon_id', $new_demo_content_options['media'][$icon_id]  );
            update_term_meta( $new_brand[$brand['id']]['term_id'], 'agni_product_brand_banner_image_id', $new_demo_content_options['media'][$banner_image_id]  );
            update_term_meta( $new_brand[$brand['id']]['term_id'], 'agni_product_brand_content_block', $new_demo_content_options['agni_block'][$content_block]  );
            update_term_meta( $new_brand[$brand['id']]['term_id'], 'agni_term_header_id', $new_demo_content_options['headers'][$header_id]  );
            update_term_meta( $new_brand[$brand['id']]['term_id'], 'agni_slider_id', $new_demo_content_options['sliders'][$slider_id]  );
            update_term_meta( $new_brand[$brand['id']]['term_id'], 'agni_term_footer_block_id', $new_demo_content_options['agni_block'][$footer_block_id]  );
        }
        else{
            $new_brand[$brand['id']] = $term_exists;

        }

        $new_term_id = $new_brand[$brand['id']]['term_id'];

        $prepare_options[$brand['id']] = $new_term_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'products_brand' );


    return agni_prepare_return_success_array( 'Products brands added' );
}
function agni_import_export_content_products_reviews( $reviews, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );
   
    foreach ($reviews as $key => $review) {
        
        $product_id = $new_demo_content_options['products'][$review['product_id']];

        $review_id = wp_insert_comment(array(
            'comment_post_ID' => $product_id,
            'comment_author' => $review['reviewer'],
            'comment_author_email' => $review['reviewer_email'],
            'comment_date' => $review['date_created'],
            'comment_date_gmt' => $review['date_created_gmt'],
            'comment_content' => $review['review'],
            'comment_approved' => $review['status'] == 'approved' ? 1 : 0,
            'comment_type' => 'review',
        ));
      
        update_comment_meta($review_id, 'rating', $review['rating']);
        update_comment_meta($review_id, 'verified', $review['verified']);
      
        $prepare_options[$review['product_id']] = $review_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'products_reviews' );

    return agni_prepare_return_success_array( 'Products reviews added' );
}


function agni_import_export_content_blocks( $blocks, $options ){
    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    foreach ($blocks as $key => $block) {

        $existing_post = get_page_by_title( $block['title']['raw'], OBJECT, $block['type'] );
        
        if( is_null( $existing_post ) ){
            $new_post_id = apply_filters( 'agni_content_insert_post', $block );

        }
        else{
            $new_post_id = $existing_post->ID;
        }


        $prepare_options[$block['id']] = $new_post_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'blocks' );

    return agni_prepare_return_success_array( 'WP blocks added' );
}


function agni_import_export_content_agni_block( $blocks, $options ){
    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    foreach ($blocks as $key => $block) {

        $existing_post = get_page_by_title( $block['title']['raw'], OBJECT, $block['type'] );
        
        if( is_null( $existing_post ) ){
            $new_post_id = apply_filters( 'agni_content_insert_post', $block );

            $new_block_category = array();
            foreach ($block['block_category'] as $key => $value) {
                $new_block_category[$key] = $new_demo_content_options['block_categories'][$value];
            }

            wp_set_post_terms( $new_post_id, $new_block_category, 'block_category' );
        }
        else{
            $new_post_id = $existing_post->ID;
        }


        $prepare_options[$block['id']] = $new_post_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'agni_block' );

    return agni_prepare_return_success_array( 'Agni blocks added' );
}


function agni_import_export_content_block_categories( $categories, $options ){

    // print_r( $categories );

    $prepare_options = array();

    $new_category = array();
    foreach ($categories as $key => $category) {

        $term_exists = term_exists( $category['slug'], 'block_category' );

        if( !$term_exists ){
            // $category['parent'] = 0;
            $new_category[$category['id']] = apply_filters( 'agni_content_insert_term', $category, 'block_category' );
        }
        else{
            $new_category[$category['id']] = $term_exists;

        }
        $new_term_id = $new_category[$category['id']]['term_id'];

        $prepare_options[$category['id']] = $new_term_id;
    }

    foreach ($categories as $key => $category) {    
        if( $category['parent'] != 0 ){
            $new_parent_term_id = $prepare_options[$category['parent']];
            wp_update_term( $new_category[$category['id']]['term_id'], 'block_category', array(
                'parent' => $new_parent_term_id
            ) );
        }
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'block_categories' );

    return agni_prepare_return_success_array( 'Block categories added' );
}
function agni_import_export_content_portfolio( $posts, $options ){
    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    foreach ($posts as $key => $post) {

        $existing_post = get_page_by_title( $post['title']['raw'], OBJECT, $post['type'] );
        
        if( is_null( $existing_post ) ){
            $new_post_id = apply_filters( 'agni_content_insert_post', $post );

            $new_category = array();
            $new_tag = array();
            foreach ($post['portfolio_category'] as $key => $value) {
                $new_category[$key] = $new_demo_content_options['portfolio_categories'][$value];
            }

            wp_set_post_terms( $new_post_id, $new_category, 'portfolio_category' );

            update_post_meta( $new_post_id, 'agni_page_header_choice', $new_demo_content_options['headers'][$post['meta']['agni_page_header_choice']] );
            update_post_meta( $new_post_id, 'agni_slider_id', $new_demo_content_options['sliders'][$post['meta']['agni_slider_id']] ); 
            update_post_meta( $new_post_id, 'agni_footer_block_id', $new_demo_content_options['agni_block'][$post['meta']['agni_footer_block_id']] ); 

            if( $post['featured_media'] !== 0 ){
                update_post_meta( $new_post_id, '_thumbnail_id', $new_demo_content_options['media'][$post['featured_media']] );
            }
        }
        else{
            $new_post_id = $existing_post->ID;
        }


        $prepare_options[$post['id']] = $new_post_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'portfolio' );

    return agni_prepare_return_success_array( 'Portfolio added' );
}
function agni_import_export_content_portfolio_categories( $categories, $options ){


    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $new_category = array();
    foreach ($categories as $key => $category) {

        $term_exists = term_exists( $category['slug'], 'portfolio_category' );

        if( !$term_exists ){
            // $category['parent'] = 0;
            $new_category[$category['id']] = apply_filters( 'agni_content_insert_term', $category, 'portfolio_category' );

            $header_id = $category['meta']['agni_term_header_id'];
            $slider_id = $category['meta']['agni_slider_id'];
            $footer_block_id = $category['meta']['agni_term_footer_block_id'];

            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_term_header_id', $new_demo_content_options['headers'][$header_id]  );
            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_slider_id', $new_demo_content_options['sliders'][$slider_id]  );
            update_term_meta( $new_category[$category['id']]['term_id'], 'agni_term_footer_block_id', $new_demo_content_options['agni_block'][$footer_block_id]  );
        }
        else{
            $new_category[$category['id']] = $term_exists;

        }
        
        $new_term_id = $new_category[$category['id']]['term_id'];

        $prepare_options[$category['id']] = $new_term_id;
    }

    foreach ($categories as $key => $category) {    
        if( $category['parent'] != 0 ){
            $new_parent_term_id = $prepare_options[$category['parent']];
            wp_update_term( $new_category[$category['id']]['term_id'], 'portfolio_category', array(
                'parent' => $new_parent_term_id
            ) );
        }
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'portfolio_categories' );


    return agni_prepare_return_success_array( 'Portfolio categories added' );
}
function agni_import_export_content_agni_wc_wishlist( $posts, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    foreach ($posts as $key => $post) {

        $existing_post = get_page_by_title( $post['title']['raw'], OBJECT, $post['type'] );
        
        if( is_null( $existing_post ) ){
            $new_post_id = apply_filters( 'agni_content_insert_post', $post );

            $wishlist_ids = explode('|', $post['meta']['agni_wishlist_product_ids']);
            
            if( !empty( $wishlist_ids ) ){
                $new_wishlist_ids = array();

                foreach ($wishlist_ids as $key => $product_ids) {
                    $wishlist_product_ids = explode( ':', $product_ids );

                    $new_product_ids = array();

                    foreach ($wishlist_product_ids as $key => $product_id) {
                        $new_product_ids[] = $new_demo_content_options['products'][$product_id];
                    }

                    $new_wishlist_ids[] = implode( ':', $new_product_ids );
                }
                
                update_post_meta( $new_post_id, 'agni_wishlist_product_ids', implode('|', $new_wishlist_ids) );
            }

        }
        else{
            $new_post_id = $existing_post->ID;
        }


        $prepare_options[$post['id']] = $new_post_id;
    }

    agni_prepare_importer_exporter_options( $prepare_options, 'agni_wc_wishlist' );

    return agni_prepare_return_success_array( 'Wishlists added' );
}
function agni_import_export_content_headers( $headers, $options ){

    $prepare_options = array();
    $existing_headers = array();

    $existing_headers = get_option( 'agni_header_builder_headers_list' );

    $existing_ids = array_column($existing_headers, 'id');
    $existing_titles = array_column($existing_headers, 'title');

    $headers = array_filter( $headers, function( $header ){ return $header['id'] !== 0; } );

    foreach ($headers as $key => $header) {

        if( !in_array( $header['title'], $existing_titles ) ){
            $new_header_id = '';
            if( in_array( $header['id'], $existing_ids ) ){
                $new_header_id = agni_import_export_get_new_id( $existing_ids );
            }
            else{
                $new_header_id = $header['id'];
            }

            $prepare_options[$header['id']] = $new_header_id;
        }
        else{
            unset($headers[$key]);
        }

    }

    $headers = array_merge( $existing_headers, $headers );

    update_option( 'agni_header_builder_headers_list', $headers );

    agni_prepare_importer_exporter_options( $prepare_options, 'headers' );

    return agni_prepare_return_success_array( 'Headers added, revision needed on "content block" field after products added' );
}
function agni_import_export_content_sliders( $sliders, $options ){

    $prepare_options = array();

    $existing_sliders = array();


    $existing_sliders = get_option( 'agni_slider_builder_sliders' );


    foreach ($sliders as $key => $slider) {
        if( !empty( $existing_sliders ) ){
            $existing_ids = array_column($existing_sliders, 'id');
            $existing_titles = array_column($existing_sliders, 'title');
            
            if( !in_array( $slider['title'], $existing_titles ) ){
                $new_slider_id = '';
                if( in_array( $slider['id'], $existing_ids ) ){
                    $new_slider_id = agni_import_export_get_new_id( $existing_ids );
                }
                else{
                    $new_slider_id = $slider['id'];
                }
    
                $prepare_options[$slider['id']] = $new_slider_id;
            }
            else{
                unset($sliders[$key]);
            }
        }
        else{
            $prepare_options[$slider['id']] = $slider['id'];
        }
    }

    if( !empty( $existing_sliders ) ){
        $sliders = array_merge( $existing_sliders, $sliders );
    }

    update_option( 'agni_slider_builder_sliders', $sliders );

    agni_prepare_importer_exporter_options( $prepare_options, 'sliders' );

    return agni_prepare_return_success_array( 'Sliders added, revision needed on "content block" field after products added' );
}
function agni_import_export_content_product_layouts( $layouts, $options ){

    $prepare_options = array();
    $existing_layouts = array();

    $existing_layouts = get_option( 'agni_product_builder_layouts_list' );

    $existing_ids = array_column($existing_layouts, 'id');
    $existing_titles = array_column($existing_layouts, 'title');

    $layouts = array_filter( $layouts, function( $layout ){ return $layout['id'] !== 0; } );

    foreach ($layouts as $key => $layout) {
            
        if( !in_array( $layout['title'], $existing_titles ) ){
            $new_layout_id = '';
            if( in_array( $layout['id'], $existing_ids ) ){
                $new_layout_id = agni_import_export_get_new_id( $existing_ids );
            }
            else{
                $new_layout_id = $layout['id'];
            }

            $prepare_options[$layout['id']] = $new_layout_id;
        }
        else{
            unset($layouts[$key]);
        }
       
    }

    $layouts = array_merge( $existing_layouts, $layouts );

    update_option( 'agni_product_builder_layouts_list', $layouts );

    agni_prepare_importer_exporter_options( $prepare_options, 'product_layouts' );
    
    return agni_prepare_return_success_array( $layouts );
    
    // return agni_prepare_return_success_array( 'Product layouts added, revision needed on "example", "content block" field after products added' );
}
function agni_import_export_content_fonts( $fonts, $options ){
    
    $custom_fonts = $fonts[0]['custom_fonts'];

    edit_fonts_list_custom_fonts( $custom_fonts );

    update_option( 'agni_font_manager_list',  $fonts );

    return agni_prepare_return_success_array( 'Fonts added' );
}
function agni_import_export_content_widgets( $widgets_data, $options ){

    global $wp_registered_sidebars;

    $widgets_data = $widgets_data[0];

    $sidebar_widgets = $widgets_data['sidebars_widgets'];
    $widgets_data = $widgets_data['widgets'];

    unset( $sidebar_widgets['wp_inactive_widgets'] );

    update_option( 'sidebars_widgets', $sidebar_widgets );
    
    foreach ($sidebar_widgets as $sidebar_name => $sidebar_widget_array) {
        // if( !isset( $wp_registered_sidebars[$sidebar_name] ) ){
        //     return;
        // }

        // echo "i reached here!";

        foreach ($sidebar_widget_array as $key => $widget_instance_id ) {

            $pattern = '/-(\d*)/';
            if( preg_match($pattern, $widget_instance_id, $matches) ){
                $widget_id = $matches[1];

                $id_base = str_replace( $matches[0], '', $widget_instance_id );

                $widgets_id_base = get_option( 'widget_' . $id_base );
                $widgets_id_base[$widget_id] = $widgets_data[$widget_instance_id];

                update_option( 'widget_' . $id_base, $widgets_id_base );

            }

        }
    }

    return agni_prepare_return_success_array( 'Widgets added' );
}



function agni_import_export_content_menus( $menus, $options ){

    $prepare_options = array();

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $menu_terms = $menus[0]['menus'];
    $menu_data = $menus[0]['menu_items'];

    $menu_term_slugs = array();

    foreach ($menu_terms as $key => $menu_term) {

        $menu_term_args = array(
            'description' => $menu_term['description'],
            'menu-name'   => $menu_term['name'],
            'parent'      => $menu_term['parent'],
        );

        $menu_exists = wp_get_nav_menu_object( $menu_term['name'] );
        
        if( $menu_exists ){
            $menu_id = $menu_exists->term_id;
        }
        else{
            $menu_id = wp_update_nav_menu_object( 0, $menu_term_args );
        }

        $menu_term_slugs[$menu_id] = $menu_term['slug'];

        $prepare_options[$menu_term['term_id']] = $menu_id;
    }


    foreach ($menu_term_slugs as $menu_id => $menu_term_slug) {
        // echo "menu id:" . $menu_id;

        $menu_items = $menu_data[$menu_term_slug];
        
        $mapped_menu_items = array();
        $new_menu_items = array();

        foreach ($menu_items as $key => $menu_item) {
            $new_menu_item = 0;

            if( $menu_item['object'] == 'post' ){
                $menu_item['object_id'] = $new_demo_content_options['posts'][$menu_item['object_id']];
            }
            else if( $menu_item['object'] == 'page' ){
                $menu_item['object_id'] = $new_demo_content_options['pages'][$menu_item['object_id']];
            }
            else if( $menu_item['object'] == 'product' ){
                $menu_item['object_id'] = $new_demo_content_options['products'][$menu_item['object_id']];
            }

            $new_menu_item = agni_import_export_insert_menu_item( $menu_id, $menu_item );
            // echo "new menu item" . $new_menu_item . "<br/>"; 

            update_post_meta( $new_menu_item, 'agni_menu_item_label', $menu_item['agni_menu_item_label'] );
            update_post_meta( $new_menu_item, 'agni_menu_item_show_menu_on', $menu_item['agni_menu_item_show_menu_on'] );
            update_post_meta( $new_menu_item, 'agni_menu_item_hide_menu_text', $menu_item['agni_menu_item_hide_menu_text'] );
            update_post_meta( $new_menu_item, 'agni_menu_item_block_choice', $menu_item['agni_menu_item_block_choice'] );
            update_post_meta( $new_menu_item, 'agni_menu_item_width', $menu_item['agni_menu_item_width'] );
            update_post_meta( $new_menu_item, 'agni_menu_item_height', $menu_item['agni_menu_item_height'] );
            update_post_meta( $new_menu_item, 'agni_menu_item_fullwidth', $menu_item['agni_menu_item_fullwidth'] );
            update_post_meta( $new_menu_item, 'agni_menu_item_icon', $menu_item['agni_menu_item_icon'] );

            $mapped_menu_items[$menu_item['ID']] = $new_menu_item;
            $mapped_menu_parent_items[$menu_item['ID']] = $menu_item['menu_item_parent'];

        }

        // print_r( $mapped_menu_items );
        // print_r( $mapped_menu_parent_items );

        foreach ($menu_items as $key => $menu_item) {

            if( $menu_item['menu_item_parent'] != 0 ){

                $new_menu_item_id = $mapped_menu_items[$menu_item['ID']];
                $new_menu_item_parent = $mapped_menu_items[$mapped_menu_parent_items[$menu_item['ID']]];
                
                update_post_meta( $new_menu_item_id, '_menu_item_menu_item_parent', (string) ( (int) $new_menu_item_parent ) );
            }
        }

        // $prepare_options[$menu['term_id']] = array(
        //     'id' => $menu_id,
        //     'slug' => $menu_term_slug,
        //     'content' => $mapped_menu_items
        // );

    }

    agni_prepare_importer_exporter_options( $prepare_options, 'menus' );
    
    return agni_prepare_return_success_array( 'Menus added' );
}


function agni_import_export_content_theme_options( $theme_options, $options ){


    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    unset( $theme_options[0]['0'] );
    unset( $theme_options[0]['nav_menu_locations'] );
    unset( $theme_options[0]['custom_css_post_id'] );
    unset( $theme_options[0]['sidebars_widgets'] );

    foreach ($theme_options[0] as $setting_key => $setting_value) {
        $new_setting_value = $setting_value;

        switch( $setting_key ){
            case 'footer_settings_content_block_choice':
            case 'shop_settings_cart_block_choice':
            case '404_settings_content_block_choice':
                $new_setting_value = $new_demo_content_options['agni_block'][$setting_value];
                break;
            case 'shop_settings_compare_page_choice':
            case 'portfolio_settings_archive_page_choice':
            case 'shop_settings_compare_page_choice':
                $new_setting_value = $new_demo_content_options['pages'][$setting_value];
                break;
            case 'shop_settings_label_hot':
            case 'shop_settings_label_new':
                foreach ($setting_value as $key => $value) {
                    $new_setting_value[] = $new_demo_content_options['products'][$value];
                }

                break;
            default: 

        }

        set_theme_mod( $setting_key, $new_setting_value );
    }

    return agni_prepare_return_success_array( 'Theme options added' );
}

function agni_import_export_content_set_menu_locations( $nav_menu_locations, $options ){

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $new_nav_menu_locations = array();

    foreach ($nav_menu_locations[0]['nav_menu_locations'] as $slug => $id) {
        $new_nav_menu_locations[$slug] = $new_demo_content_options['menus'][$id];
    }

    set_theme_mod( 'nav_menu_locations', $new_nav_menu_locations );

    return agni_prepare_return_success_array( 'Nav Menus Location assigned' );
}

function agni_import_export_content_set_homepage( $pages, $options ){

    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $page_id = $pages[0]['id'];

    update_option( 'page_on_front', $new_demo_content_options['pages'][$page_id] );
    update_option( 'show_on_front', 'page' );

    return agni_prepare_return_success_array( 'Home Page assigned' );

}



function agni_import_export_content_insert_post($args){
    $new_post = array(
        'import_id'         => $args['id'],
        'post_date'         => $args['date'],
        'post_date_gmt'     => $args['date_gmt'],
        'post_modified'     => $args['modified'],
        'post_modified_gmt' => $args['modified_gmt'],
        'guid'              => $args['guid']['raw'],
        'post_title'        => $args['title']['raw'],
        'post_type'         => $args['type'],
        'post_status'       => $args['status'], 
        // 'post_content'      => $args['content']['raw'],
        // 'post_excerpt'      => $args['excerpt']['raw'],
        'post_parent'       => $args['parent'],
        'comment_status'    => $args['comment_status'],
        'ping_status'       => $args['ping_status'],
        'post_password'     => $args['password'],
        'menu_order'        => $args['menu_order'],
        'page_template'     => $args['template'],
        'meta_input'        => $args['meta']

    );

    if( $args['type'] != 'page' && $args['type'] != 'agni_wc_wishlist' ){
        $new_post['post_category'] = $args['categories'];
        $new_post['tags_input'] = $args['tags'];
    }

    if( !empty( $args['content']['raw'] ) ){
        $new_post['post_content'] = wp_slash( $args['content']['raw'] );
    }
    if( !empty( $args['excerpt']['raw'] ) ){
        $new_post['post_excerpt'] = $args['excerpt']['raw'];
    }

    $existing_post = get_page_by_title( $args['title']['raw'], OBJECT, $args['type'] );
    // print_r( $existing_post );
    if( is_null( $existing_post ) ){
        $new_post_id = wp_insert_post( $new_post );
    }
    else{
        $new_post_id = $existing_post->ID;
    }

    
    if( $new_post_id != 0 && $args['type'] === 'post' ){
        if( isset( $args['format'] ) ){
            set_post_format($new_post_id, $args['format'] );
        }
        if( $args['sticky'] == true ){
            $sticky_posts_list = get_option( 'sticky_posts' );
            array_push( $sticky_posts_list, $new_post_id );
            update_option( 'sticky_posts', $sticky_posts_list );
            
        }
    }

    return $new_post_id;

}


function agni_import_export_content_insert_term($args, $taxonomy){
    
    // $parent = get_term_by( 'slug', $args['parent_slug'], $taxonomy );
    
    $tax_args = array(
        'slug' => $args['slug'],
        'description' => $args['description']
    );

    $new_term_ids = wp_insert_term( $args['name'], $taxonomy, $tax_args );
    
    foreach ($args['meta'] as $key => $value) {
        update_term_meta( $new_term_ids['term_id'], $key, $value, true );
    }

    // print_r( $new_term_ids );

    return $new_term_ids;

}


function agni_import_export_content_insert_product($args, $post_type, $parent = ''){


    $product_args = array(
        'import_id'         => $args['id'],
        'post_date'         => $args['date_created'],
        'post_date_gmt'     => $args['date_created_gmt'],
        'post_modified'     => $args['date_modified'],
        'post_modified_gmt' => $args['date_modified_gmt'],
        'post_title'        => $args['name'],
        'post_type'         => $post_type,
        'post_status'       => $args['status'], 
        'post_parent'       => !empty( $parent ) ? $parent : $args['parent_id'],
        'menu_order'        => $args['menu_order'],

    );

    if( empty( $parent ) ){
        $product_args['post_content'] = wp_slash( $args['description'] );
        $product_args['post_excerpt'] = $args['short_description'];
    }

    $post_id = wp_insert_post( $product_args );


    update_post_meta( $post_id, '_visibility', $args['catalog_visibility'] );
    update_post_meta( $post_id, '_stock_status', $args['stock_status']);
    update_post_meta( $post_id, 'total_sales', $args['total_sales'] );
    update_post_meta( $post_id, '_downloadable', $args['downloadable'] );
    update_post_meta( $post_id, '_download_limit', $args['download_limit'] );
    update_post_meta( $post_id, '_download_expiry', $args['download_expiry'] );
    update_post_meta( $post_id, '_virtual', $args['virtual'] );
    update_post_meta( $post_id, '_regular_price', $args['regular_price'] );
    update_post_meta( $post_id, '_sale_price', $args['sale_price'] );
    update_post_meta( $post_id, '_wc_average_rating', $args['average_rating'] );
    update_post_meta( $post_id, '_wc_rating_count', $args['rating_count'] );
    update_post_meta( $post_id, '_wc_review_count', $args['review_count'] );
    update_post_meta( $post_id, '_purchase_note', $args['purchase_note'] );
    update_post_meta( $post_id, '_featured', $args['featured'] );
    update_post_meta( $post_id, '_weight', $args['weight'] );
    update_post_meta( $post_id, '_length', $args['dimensions']['length'] );
    update_post_meta( $post_id, '_width', $args['dimensions']['width'] );
    update_post_meta( $post_id, '_height', $args['dimensions']['height'] );
    update_post_meta( $post_id, '_sku', $args['sku'] );
    
    if( empty( $parent ) ){
        update_post_meta( $post_id, '_product_attributes', agni_import_product_attributes( $post_id, $args['attributes'] ) );
    }
    else{
    // else if( $args['type'] === 'variable' ){
        agni_import_product_variation_attributes( $post_id, $args['attributes']);
    }

    update_post_meta( $post_id, '_sale_price_dates_from', $args['date_on_sale_from'] );
    update_post_meta( $post_id, '_sale_price_dates_to', $args['date_on_sale_to'] );
    update_post_meta( $post_id, '_price', $args['price'] );
    update_post_meta( $post_id, '_sold_individually', $args['sold_individually'] );
    update_post_meta( $post_id, '_manage_stock', $args['manage_stock'] );
    update_post_meta( $post_id, '_tax_status', $args['tax_status'] );
    update_post_meta( $post_id, '_tax_class', $args['tax_class'] );
    update_post_meta( $post_id, '_upsell_ids', $args['upsell_ids'] );
    update_post_meta( $post_id, '_cross_sell_ids', $args['cross_sell_ids'] );
    // update_post_meta( $post_id, '_related_ids', $args['related_ids'] );
    update_post_meta( $post_id, '_backorders', $args['backorders'] );

    foreach( $args['meta_data'] as $meta ){
        // print_r( $meta );
        update_post_meta( $post_id, $meta['key'], $meta['value'] );
    }
    
    wc_update_product_stock($post_id, $args['stock_quantity'], 'set');

    return $post_id;
}


function agni_import_product_attributes( $post_id, $attributes ){

    $data = array();

    foreach ($attributes as $key => $attribute) {

        $attribute_name = strtolower($attribute['name']);

        if( $attribute['id'] != 0  ){
            wp_set_object_terms( $post_id, $attribute['options'], 'pa_'.$attribute_name, true );

            $data['pa_'.$attribute_name] = array(
                'name' => 'pa_'.$attribute_name,
                'options' => $attribute['options'],
                'value' => '',
                'is_visible' => $attribute['visible'],
                'is_variation' => $attribute['variation'],
                'is_taxonomy' => true
            );
        }
        else{
            $data[$attribute_name] = array(
                'name' => $attribute['name'],
                // 'options' => $attribute['options'],
                'value' => wc_implode_text_attributes( $attribute['options'] ),
                'position' => $attribute['position'],
                'is_visible' => $attribute['visible'],
                'is_variation' => $attribute['variation'],
                'is_taxonomy' => false
            );
        }
        
    }
    
    return $data;

}

function agni_import_product_variation_attributes( $post_id, $attributes ){
    
    foreach ($attributes as $key => $attribute) {

        $taxonomy = 'pa_'.strtolower( $attribute['name'] ); // The attribute taxonomy

        $term_slug = get_term_by('name', $attribute['option'], $taxonomy )->slug;

        update_post_meta( $post_id, 'attribute_'.$taxonomy, $term_slug );

    }
}


function edit_fonts_list_custom_fonts( $custom_fonts ) {

    $custom_fonts_families = $custom_fonts['families'];
    $custom_fonts_url = $custom_fonts['fonts_url'];

   
    global $wp_filesystem;
    // Initialize the WP filesystem, no more using 'file-put-contents' function
    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }

    $upload_dir = wp_get_upload_dir();


    foreach ($custom_fonts_families as $key => $family) {
        $font_dir_name = $family['name'];


        foreach ($family['content'] as $key => $family_content) {

            $filename = $family_content['filename'];

            foreach( $family_content['extensions'] as $key => $extension ){
                if( !$wp_filesystem->exists( "{$upload_dir['basedir']}/agni-fonts/{$font_dir_name}/{$filename}{$extension}" ) ){
                    $font = $wp_filesystem->get_contents( "{$custom_fonts_url}/{$font_dir_name}/{$filename}{$extension}" );
                    // $font = $wp_filesystem->get_contents( "http://demo.agnidesigns.com/halena/wp-content/uploads/agni-fonts/Larsseit/Larsseit-Regular.ttf" );

                    if( wp_mkdir_p( $upload_dir['basedir'] . "/agni-fonts/{$font_dir_name}" ) ){
                        $wp_filesystem->put_contents( $upload_dir['basedir']. "/agni-fonts/{$font_dir_name}/{$filename}{$extension}", $font );
                    }
                    else{
                        echo 'permission denied';
                    }
                }

            }
        }

    }
        

}


function agni_import_export_insert_menu_item( $menu_id, $menu_item ){
    $new_menu_item = 0;


    $menu_args = array(
        // 'menu-item-db-id'       => $menu_item['db_id'],
        'menu-item-parent-id'   => $menu_item['menu_item_parent'],
        'menu-item-position'    => $menu_item['menu_order'],
        'menu-item-type'        => $menu_item['type'],
        'menu-item-title'       => $menu_item['title'],
        'menu-item-description' => $menu_item['description'],
        'menu-item-attr-title'  => $menu_item['attr_title'],
        'menu-item-target'      => $menu_item['target'],
        'menu-item-classes'     => implode( " ", $menu_item['classes'] ),
        'menu-item-xfn'         => $menu_item['xfn'],
        'menu-item-status'      => $menu_item['post_status'],
    );

    if( $menu_item['object'] == 'custom' ){
        $menu_args['menu-item-url'] = esc_url( $menu_item['url'] );
    }
    else{
        $menu_args['menu-item-url'] = esc_url( get_permalink( $menu_item['object_id'] ) );
        $menu_args['menu-item-object-id'] = $menu_item['object_id'];
        $menu_args['menu-item-object'] = $menu_item['object'];
    }

    $existing_menu_item_id = agni_get_nav_menu_item( $menu_id, $menu_item['title'] );
    
    if( !$existing_menu_item_id ){
        $new_menu_item = wp_update_nav_menu_item( $menu_id, 0, $menu_args );
    }
    else{
        $new_menu_item = $existing_menu_item_id;
    }

    return $new_menu_item;
}


function agni_get_nav_menu_item( $menu_id, $menu_title ){
    if ( $existing_menu_items = wp_get_nav_menu_items( $menu_id ) ) {
        // print_r( $existing_menu_items );
        foreach ( $existing_menu_items as $existing_menu_item ) {
            if( $existing_menu_item->title === $menu_title ){
                return $existing_menu_item->ID;
            }
           
        }
    }

    return false;
}

function agni_import_export_get_new_id( $existing_header_ids ){

    $missing_header_ids = array();
    for ($i=min( $existing_header_ids ); $i < max( $existing_header_ids ); $i++) { 
        if( !in_array( $i, $existing_header_ids ) ){
            $missing_header_ids[] = $i;
        }
    }

    if( !empty( $missing_header_ids ) ){
        return min( $missing_header_ids );
    }
    else{
        return max( $existing_header_ids ) + 1;
    }
}


function agni_prepare_importer_exporter_options( $new_options, $slug ){


    $new_demo_content_options = get_option( 'agni_importer_exporter_demo_content_mapping' );

    $new_demo_content_options[$slug] = $new_options;

    update_option( 'agni_importer_exporter_demo_content_mapping', $new_demo_content_options );

}

function agni_prepare_return_success_array( $string ){
    return array(
        'success' => true,
        'data' => $string
    );
}


// function agni_import_export_remove_extra_image_sizes() {
//     foreach ( get_intermediate_image_sizes() as $size ) {
//         // if ( !in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
//             remove_image_size( $size );
//         // }
//     }
// }
 



function agni_importer_exporter_disable_upload_sizes( $sizes, $image_meta ) {
 
    // // Get filetype data.
    // $filetype = wp_check_filetype( $image_meta['file'] );
     
    // $exclude_file_types = array(
    //     'image/gif',
    // );
 
    // // Check if file type is on exclude list 
    // if ( in_array( $filetype['type'], $exclude_file_types ) ) {
        $sizes = array();
    // }
 
    // Return sizes you want to create from image (None if image is gif.)
    return $sizes;
}   