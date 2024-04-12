<?php

add_action( 'woocommerce_after_single_product_summary', 'cartify_recently_viewed_products' );
add_action( 'template_redirect', 'cartify_recently_viewed_products_set_cookie' );

// add_filter( 'agni_recently_viewed_products_args', 'cartify_recently_viewed_products_args', 10, 1 );

if( !function_exists('cartify_woocommerce_recently_viewed_products_title') ){
    function cartify_woocommerce_recently_viewed_products_title(){
        ?>
        <h2><?php echo esc_html__( 'Recently viewed items', 'cartify' ) ?></h2>
        <?php
    }
}

if( !function_exists('cartify_recently_viewed_products_id') ){
    function cartify_recently_viewed_products_id( $args = array( 'number_of_products' => 12 ) ){
        $viewed_products = '';

        $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
        $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

        $sliced_viewed_products = array_slice( $viewed_products, 0, $args['number_of_products'] );

        return $sliced_viewed_products;
    }
}


if( !function_exists('cartify_recently_viewed_products') ){
    /**
     * function to display recently viewed products
     *
     * @return void
     */
    function cartify_recently_viewed_products( $args = array( 'no_title' => false, 'count' => '10' ) ){

        extract( $args );

        $recent_args = array();

        $recent_args = apply_filters( 'agni_recently_viewed_products_args', array( 'posts_per_page' => $count, 'columns' => '10' ) );


        $recent_products_contents_classes = array(
            'agni-recently-viewed-products__contents',
            'columns-' . $recent_args['columns'],
            'products'
        );

        ?>
        <div class="agni-recently-viewed-products">
            <?php if( $no_title == false ){ 
                echo wp_kses( apply_filters( 'agni_woocommerce_recently_viewed_products_title', cartify_woocommerce_recently_viewed_products_title() ), 'title' ); 
            } ?>
            <ul class="<?php echo esc_attr( cartify_prepare_classes( $recent_products_contents_classes ) ); ?>">
                <?php

                // Get recently viewed product cookies data
                // $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
                // $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
                $viewed_products = cartify_recently_viewed_products_id();

                                if ( empty( $viewed_products ) ) {
                    return;
                }

                $args = array(
                    'posts_per_page' => $recent_args['posts_per_page'],
                    'no_found_rows'  => 1, 
                    'post_status'    => 'publish', 
                    'post_type'      => 'product', 
                    'post__in'       => $viewed_products, 
                    // 'order'        => 'DESC'
                );

                $product_recent_query = new WP_Query($args);

                if( $product_recent_query->have_posts() ){

                    while( $product_recent_query->have_posts() ){ $product_recent_query->the_post(); 
                        global $product;
                        $thumbnail_id = $product->get_image_id();
                        ?>
                        <li <?php wc_product_class( '', $product ); ?>>
                            <?php
                            woocommerce_template_loop_product_link_open();
                            ?>
                            <div class="woocommerce-loop-product__thumbnail">
                                <?php
                                    echo apply_filters( 'agni_woocommerce_recently_viewed_products_image_thumbnail_html', wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail'), $thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                                ?>
                            </div>
                            <?php

                            woocommerce_template_loop_product_title();

                            woocommerce_template_loop_product_link_close(); 
                            ?>
                        </li>

                                                <?php
                    }
                } 
                wp_reset_postdata();
                ?>
            </ul>
        </div>
        <?php
    }
}

if( !function_exists( 'cartify_recently_viewed_products_args' ) ){
    function cartify_recently_viewed_products_args( $args ){

        $args['posts_per_page'] = 10; // 4 related products
        $args['columns'] = 10; // arranged in 2 columns

        return $args;
    }
}

if( !function_exists('cartify_recently_viewed_products_set_cookie') ){
    /**
     * setup cookie variable inside single product page.
     *
     * @return void
     */
    function cartify_recently_viewed_products_set_cookie(){
        if ( ! is_singular( 'product' ) ) {
            return;
        }
        global $post;

        $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();

        if ( ! in_array( $post->ID, $viewed_products ) ) {
            $viewed_products[] = $post->ID;

        }

        wc_setcookie('woocommerce_recently_viewed', implode( '|', $viewed_products ), time() + 60 * 60 * 24 * 30);
    }
}

?>