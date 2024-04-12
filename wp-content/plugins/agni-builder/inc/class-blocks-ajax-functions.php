<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if( !class_exists('AgniBuilderAjaxFunctions') ){
    class AgniBuilderAjaxFunctions{
        public function __construct(){

            add_action( 'wc_ajax_agni_builder_products_tab_contents', array( $this, 'products_tab_contents' ) );
            add_action( 'wc_ajax_agni_builder_products_categories_tab_contents', array( $this, 'products_categories_tab_contents' ) );

            add_action( 'wc_ajax_agni_builder_ajax_get_products', array( $this, 'get_products' ) );
            add_action( 'wc_ajax_agni_builder_ajax_get_products_categories', array( $this, 'get_products_categories' ) );
            // add_action( 'wp_ajax_no_priv_agni_builder_ajax_get_products_categories', array( $this, 'get_products_categories' ) );

            add_action( 'wc_ajax_agni_builder_hotspot', array( $this, 'hotspot' ) );


        }



        public function products_tab_contents(){


            // print_r($data);
            $attributes = $_POST;
            extract( $attributes );

            $carousel_args = '';
            
            $product_query = AgniBuilderHelper::building_products_query( $attributes ); //new WP_Query( $args );

            // print_r( $attributes );
            $total_products_to_display = $totalProducts;
            
            if( $product_query->found_posts < $totalProducts || $totalProducts == '-1' ){
                $total_products_to_display = $product_query->found_posts;
            }

            ob_start();

            AgniBuilderDynamicBlocks::agni_products_pagination( $attributes, $total_products_to_display ); 

            $ajax_pagination = ob_get_clean();

            ob_start();
            ?>  

            <?php
            
                if( !empty( $imgDisplayStyle ) ){
                    add_filter( 'agni_product_archive_thumbnail_style', function() use($imgDisplayStyle){
                        return $imgDisplayStyle;
                    } );
                }
            
                while( $product_query->have_posts() ){
                    
                    $product_query->the_post();
                    
                    do_action( 'woocommerce_shop_loop' );
                    
                    wc_get_template_part( 'content', 'product' );
                }

                if( !empty( $imgDisplayStyle ) ){
                    add_filter( 'agni_product_archive_thumbnail_style', function(){
                        if( function_exists( 'cartify_get_theme_option' ) ){
                            return cartify_get_theme_option( 'shop_settings_general_thumbnail_choice', 1 );
                        }
                        else{
                            return 1;
                        }
                    } );
                }
                

            ?>

            <?php 
            $ajax_content = ob_get_clean();    

            wp_send_json_success(array(
                'content' => $ajax_content,
                'pagination' => $ajax_pagination
            ));

            die();

        }

        public function products_categories_tab_contents(){

            $attributes = $_POST;
            extract( $attributes );

            $carousel_class = $carousel_args = '';
            ?>

            <?php
            // $product_categories_count = wp_count_terms( array(
            //     'taxonomy' => 'product_cat',
            //     'hide_empty' => filter_var( $hide_empty, FILTER_VALIDATE_BOOLEAN ),
            //     'pad_counts' => true,
            //     'include' => empty( $category_ids ) ? 'all' : $category_ids,
            //     'suppress_filters' => true
            // ) );

            $product_categories_count = AgniBuilderHelper::building_products_categories_query( wp_parse_args($attributes, array( 'fields' => 'count', 'count' => '99' )) );

            $product_categories_query = AgniBuilderHelper::building_products_categories_query( $attributes );
            $product_categories = $product_categories_query->terms; //get_terms( $args );
            
            $total_products_to_display = $totalProducts;
            // echo $total_products_to_display;
            
            if( $product_categories_count < $totalProducts || $totalProducts == '-1' ){
                $total_products_to_display = $product_categories_count;
            }

            // print_r( $attributes );
            // echo $total_products_to_display;

            ob_start();

            AgniBuilderDynamicBlocks::agni_products_categories_pagination( $attributes, $total_products_to_display ); 

            $ajax_pagination = ob_get_clean();

            ob_start();

            if( $product_categories ){
                ?>
                <?php 
                $i = 0;
                foreach ( $product_categories as $category ) { 

                    if( isset( $imgSize ) && !empty($imgSize) ){
                        $value = '1x1';
                        if( !empty( $widthRatio )  ){
                            foreach ($widthRatio as $key => $ratio) {
                                if($ratio['index'] == $i){
                                    $value = $ratio['value'];
                                }
                            }
                        }
                        if( $value !== '1x1' ){
                            add_filter( 'subcategory_archive_thumbnail_size', function(){
                                return 'full';
                            } );
                        }
                        else{
                            add_filter( 'subcategory_archive_thumbnail_size', function() use($imgSize){
                                return 'cartify_thumbnail_' . $imgSize;
                            } );
                        }
                    }
                
                    // wc_get_template_part( 'content', 'product_cat' );
                    // print_r( $category );
                    wc_get_template( 'content-product_cat.php', array(
                        'category' => $category
                    ) );
                
                    if( isset( $imgSize ) && !empty($imgSize) ){
                        add_filter( 'subcategory_archive_thumbnail_size', function(){
                            return 'woocommerce_thumbnail';
                        } );
                    }

                    $i++;

                } ?>
                <?php 
            }
            ?>

            <?php 
            $ajax_content = ob_get_clean();    

            wp_send_json_success(array(
                'content' => $ajax_content,
                'pagination' => $ajax_pagination
            ));

            die();
        }

        public function get_products(){

            $attributes = $_POST;

            extract($attributes);
            
            $product_query = AgniBuilderHelper::building_products_query( $attributes ); //new WP_Query( $args );
            // print_r( $product_query );

            if( $product_query->have_posts() ){
                
                if( !empty( $imgDisplayStyle ) ){
                    add_filter( 'agni_product_archive_thumbnail_style', function() use($imgDisplayStyle){
                        return $imgDisplayStyle;
                    } );
                }
                
                $i = 0;
                while( $product_query->have_posts() ){
                    $product_query->the_post();

                    if( isset( $imgSize ) && !empty($imgSize) ){
                        $value = '1x1';
                        if( !empty( $widthRatio )  ){
                            foreach ($widthRatio as $key => $ratio) {
                                if($ratio['index'] == $i){
                                    $value = $ratio['value'];
                                }
                            }
                        }
                        if( $value !== '1x1' ){
                            add_filter( 'single_product_archive_thumbnail_size', function(){
                                return 'full';
                            } );
                        }
                        else{
                            add_filter( 'single_product_archive_thumbnail_size', function() use($imgSize){
                                return 'cartify_thumbnail_' . $imgSize;
                            } );
                        }
                    }
                    
                    do_action( 'woocommerce_shop_loop' );
                    
                    wc_get_template_part( 'content', 'product' );

                    if( isset( $imgSize ) && !empty($imgSize) ){
                        add_filter( 'single_product_archive_thumbnail_size', function(){
                            return 'woocommerce_thumbnail';
                        } );
                    }

                    $i++;
                }

                if( !empty( $imgDisplayStyle ) ){
                    add_filter( 'agni_product_archive_thumbnail_style', function(){
                        if( function_exists( 'cartify_get_theme_option' ) ){
                            return cartify_get_theme_option( 'shop_settings_general_thumbnail_choice', 1 );
                        }
                        else{
                            return 1;
                        }
                    } );
                }

            }
            ?>
            <?php 

            die();
        }

        public function get_products_categories(){

            $attributes = $_POST;
            extract( $attributes );

            $carousel_class = $carousel_args = '';
            ?>

            <?php

            $product_categories_query = AgniBuilderHelper::building_products_categories_query( $attributes );
            $product_categories = $product_categories_query->terms; //get_terms( $args );


        if( $category_desc ){
            add_action( 'woocommerce_after_subcategory_title', 'cartify_template_loop_product_category_description' );
        }


        // if( $category_button ){
        //     add_action( 'woocommerce_after_subcategory_title', 'cartify_template_loop_product_category_button', 20 );
            
        //     if( $category_button_text ){
        //         add_filter( 'cartify_template_loop_product_category_button_text', function($category) use( $category_button_text ){
        //             return $category_button_text;
        //         });
        //     }
        // }
            
            if( $product_categories ){
                ?>
                
                <?php 
                $i = 0;
                foreach ( $product_categories as $category ) { 

                    if( isset( $imgSize ) && !empty($imgSize) ){
                        $value = '1x1';
                        if( !empty( $widthRatio )  ){
                            foreach ($widthRatio as $key => $ratio) {
                                if($ratio['index'] == $i){
                                    $value = $ratio['value'];
                                }
                            }
                        }
                        if( $value !== '1x1' ){
                            add_filter( 'subcategory_archive_thumbnail_size', function(){
                                return 'full';
                            } );
                        }
                        else{
                            add_filter( 'subcategory_archive_thumbnail_size', function() use($imgSize){
                                return 'cartify_thumbnail_' . $imgSize;
                            } );
                        }
                    }
                
                    // wc_get_template_part( 'content', 'product_cat' );
                    // print_r( $category );
                    wc_get_template( 'content-product-cat.php', array(
                        'category' => $category
                    ) );
                
                    if( isset( $imgSize ) && !empty($imgSize) ){
                        add_filter( 'subcategory_archive_thumbnail_size', function(){
                            return 'woocommerce_thumbnail';
                        } );
                    }

                    $i++;

                } ?>
                <?php 
            }
            ?>

            <?php 

            die();

        }

        public function hotspot(){

            $product_ids = isset( $_POST['ids'] ) ? $_POST['ids'] : '';

            $product_ids = explode(",", $product_ids);

            foreach ($product_ids as $product_id) {

                $product = wc_get_product( $product_id );

                $product_add_to_cart_url = get_permalink( $product_id );

                if( $product->is_type( 'simple' ) ){
                    $product_add_to_cart_url = add_query_arg( 'add-to-cart', $product_id, '' );
                }

                ?>
                <div class="agni-block-hotspot-product__content">
                    <div class="agni-block-hotspot-product__image">
                        <?php echo wp_kses( wp_get_attachment_image( $product->get_image_id(), 'woocommerce_thumbnail' ), 'img' ); ?>
                    </div>
                    <div class="agni-block-hotspot-product__details">
                        <h2 class="woocommerce-loop-product__title"><?php echo wp_kses( $product->get_name(), 'title' ); ?></h2>
                        <span class="price"><?php echo wp_kses( $product->get_price_html(), 'price' ); ?></span>
                        <a href="<?php echo esc_url( $product_add_to_cart_url ); ?>" class="button add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product_id ); ?>"><?php echo esc_html( $product->add_to_cart_text() ); ?></a>
                    </div>
                </div>
                <?php 
            }

            die();
        }

    }
}
$agni_builder_ajax_functions = new AgniBuilderAjaxFunctions();