<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if( !class_exists('AgniBuilderRestAPI') ){
    class AgniBuilderRestAPI{

        public function __construct(){

            add_action( 'rest_api_init', array($this, 'register_api'), 10 );

        }

        public function register_api(){

            $current_user_can = current_user_can( 'edit_posts' );

            register_rest_route( 'agni-builder/v1', '/blocks', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_options_main'),
                'permission_callback' => '__return_true',
            ) );
            register_rest_route( 'agni-builder/v1', '/blocks/(?P<id>\d+)', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_options'),
                'permission_callback' => '__return_true',
            ) );
    
            register_rest_route( 'agni-builder/v1', '/blocks', array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array( $this, 'add_option_main' ),
                'permission_callback' => '__return_true',
            ) );
            register_rest_route( 'agni-builder/v1', '/blocks/(?P<id>\d+)', array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array( $this, 'add_option' ),
                'permission_callback' => '__return_true',
            ) );

            register_rest_route( 'agni-builder/v1', '/products', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'agni_builder_get_products'),
                'permission_callback' => '__return_true',
                'args' => array(
                    'category_ids' => array(
                        // 'validate_callback' => function($param, $request, $key) {
                        //     return is_numeric( $param );
                        // }
                    ),
                    'product_ids' => array(),
                    'count' => array(),
                    // 'rows' => array(
                    //     // 'validate_callback' => function($param, $request, $key) {
                    //     //     return is_numeric( $param );
                    //     // }
                    // ),
                    // 'columns' => array(
                    //     // 'validate_callback' => function($param, $request, $key) {
                    //     //     return is_numeric( $param );
                    //     // }
                    // ),
                    'products_type' => array(
                        // 'validate_callback' => function($param, $request, $key) {
                        //     return is_numeric( $param );
                        // }
                    ),
                    'order_by' => array(
                        // 'validate_callback' => function($param, $request, $key) {
                        //     return is_numeric( $param );
                        // }
                    ),
                ),
            ) );

            register_rest_route( 'agni-builder/v1', '/sliders', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_sliders_list'),
                'permission_callback' => '__return_true',
            ) );

            register_rest_route( 'agni-builder/v1', '/sliders/(?P<id>\d+)', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_slider'),
                'permission_callback' => '__return_true',
            ) );


            register_rest_route( 'agni-builder/v1', '/posts', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_posts'),
                'permission_callback' => '__return_true',
                'args' => array(
                    'category_ids' => array(),
                    'include' => array(),
                    'count' => array(),
                    'products_type' => array(),
                    'order_by' => array(),
                ),
            ) );


            register_rest_route( 'agni-builder/v1', '/portfolio', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_portfolio'),
                'permission_callback' => '__return_true',
                'args' => array(
                    'category_ids' => array(),
                    'include' => array(),
                    'count' => array(),
                    'products_type' => array(),
                    'order_by' => array(),
                ),
            ) );

            register_rest_route( 'agni-builder/v1', '/menus', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_registered_menus'),
                'permission_callback' => '__return_true',
            ) );

            // register_rest_route( 'agni-builder/v1', '/menus/(?P<slug>[a-z0-9-]+)', array(
            register_rest_route( 'agni-builder/v1', '/menus/(?P<id>\d+)', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_registered_menu_items'),
                'permission_callback' => '__return_true',
            ) );



            // register_rest_route( 'agni-builder/v1', '/blocks/(?P<id>\d+)', array(
            //     'methods' => WP_REST_Server::READABLE,
            //     'callback' => array($this, 'get_options'),
            // ) );
        }

        public function get_registered_menus( WP_REST_Request $request ) {
            $menu_list = array();
            
            $menus = get_terms('nav_menu', array( 'hide_empty' => true ));
            foreach($menus as $menu){ 
                $menu_list[] = array( 'value' =>  $menu->term_id, 'label' => $menu->name );
            //   $menu_list[] = array( 'value' =>  $menu->slug, 'label' => $menu->name, 'id' => $menu->term_id );
            } 
            return $menu_list;
        }

        public function get_registered_menu_items( WP_REST_Request $request ) {

            $menu_choice = $request['id'];
            // echo $menu_choice;
            if( $menu_choice == 0 ){
                $available_menus = get_terms('nav_menu');
                if( !empty( $available_menus ) ){
                    $menu_choice = $available_menus[0]->term_id;
                }
            }

            $menu_items = wp_get_nav_menu_items( $menu_choice );

            // echo json_encode( $menu_items );
            // return false;

            $menu = array();

            foreach ($menu_items as $m) {
                if (empty($m->menu_item_parent)) {
                    $menu[$m->ID] = array();
                    $menu[$m->ID]['ID'] = $m->ID;
                    $menu[$m->ID]['title'] = $m->title;
                    $menu[$m->ID]['url'] = $m->url;
                    $menu[$m->ID]['children'] = $this->get_menu_children($menu_items, $m);
                }
            }

            // print_r( $menu );
            
            // return $menu;
            return new \WP_REST_Response(array_values($menu), 200);
        }

        public function get_menu_children( $menu_array, $menu_item ){
            $children = array();
            if (!empty($menu_array)){
                foreach ($menu_array as $k=>$m) {
                    if ($m->menu_item_parent == $menu_item->ID) {
                        $children[$m->ID] = array();
                        $children[$m->ID]['ID'] = $m->ID;
                        $children[$m->ID]['title'] = $m->title;
                        $children[$m->ID]['url'] = $m->url;
                        unset($menu_array[$k]);
                        $children[$m->ID]['children'] = $this->get_menu_children($menu_array, $m);
                    }
                }
            };

            return $children;
        }


        public function get_options_main( WP_REST_Request $request ){
            // echo "Hello world";
            return "Hello world";
        }

        public function add_option_main( WP_REST_Request $request ){

            // print_r( $request['content'] );
            
            $results = $request['content'];

            // return $results;
            return 'Hello new world';
        }

        public function get_posts( WP_REST_Request $request ){

            $args = array(
                'post_type' => 'post'
            );

            if( isset($request['count']) && !empty($request['count']) ){
                $args['posts_per_page'] = $request['count'];
            }
            
            if( isset($request['include']) && !empty($request['include']) ){
                $args['post__in'] = explode( ',', $request['include']);
            }
            
            if( isset($request['categories']) && !empty($request['categories']) ){
                $args['category__in'] = explode( ',', $request['categories']);
            }

            $posts_query = new WP_Query( $args );
            
            $posts_details = array();

            while( $posts_query->have_posts() ){ $posts_query->the_post();
                
                $categories = get_the_category( get_the_id() );
                $categoriesName = array();
                foreach ($categories as $key => $category) {
                    $categoriesName[] = $category->name;
                }

                $posts_details[] = array(
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'date' => get_the_date(),
                    'categoriesName' => $categoriesName,
                    'author' => array(
                        'name' => get_the_author_meta( 'display_name' ),
                        'avatar' => get_avatar_url( get_post_field( 'post_author', get_the_id() ) ),
                    ),
                    'thumbnail' => get_the_post_thumbnail_url()
                );
                ?>
            <?php
            }

            return $posts_details;
        }

        public function get_portfolio( WP_REST_Request $request ){

            $args = array(
                'post_type' => 'portfolio'
            );

            if( isset($request['count']) && !empty($request['count']) ){
                $args['posts_per_page'] = $request['count'];
            }
            
            if( isset($request['include']) && !empty($request['include']) ){
                $args['post__in'] = explode( ',', $request['include']);
            }
            
            if( isset($request['categories']) && !empty($request['categories']) ){
                $args['category__in'] = explode( ',', $request['category_ids']);
            }

            $posts_query = new WP_Query( $args );
            
            $posts_details = array();

            while( $posts_query->have_posts() ){ $posts_query->the_post();
                
                // $categories = get_the_category( get_the_id() );
                // $categoriesName = array();
                // foreach ($categories as $key => $category) {
                //     $categoriesName[] = $category->name;
                // }

                $categoriesName = wp_get_object_terms( get_the_id(), 'portfolio_category', array( 'fields' => 'names' ) );

                $posts_details[] = array(
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'date' => get_the_date(),
                    'categoriesName' => $categoriesName,
                    'author' => array(
                        'name' => get_the_author_meta( 'display_name' ),
                        'avatar' => get_avatar_url( get_post_field( 'post_author', get_the_id() ) ),
                    ),
                    'thumbnail' => get_the_post_thumbnail_url()
                );
                ?>
            <?php
            }

            return $posts_details;
        }


        public function agni_builder_get_products( WP_REST_Request $request ){

            $attributes = $request;
            
            // $categories_name_array = array();

            if( isset($attributes['product_ids']) && !empty($attributes['product_ids']) ){
                $attributes['product_ids'] = explode(',', $attributes['product_ids']);
            }

            if( isset($attributes['category_ids']) && !empty($attributes['category_ids']) ){
                $attributes['category_ids'] = explode(',', $attributes['category_ids']);
            }


            $products_details = array();
            $product_query = AgniBuilderHelper::building_products_query( $attributes ); //new WP_Query( $args );
            // print_r( $product_query );
            if( $product_query->have_posts() ){
                while( $product_query->have_posts() ){ $product_query->the_post();
                    
                    global $product;
                    // $product = wc_get_product( $product->get_id() );

                    // print_r($product);
                    echo ' ';
                    $category_ids = $product->category_ids;
                    $category_names = array();


                    foreach( $category_ids as $id ){
                        
                        $category_names[] = get_the_category_by_ID( $id );
                    }

                    $child_price = array();
                    if ( $product->get_type() == 'grouped') {
                        $children = $product->get_children();
                        // print_r( $children );
                        foreach ($children as $key => $value) {
                        $child = wc_get_product( $value );
                        $child_price[]= $child->get_price();
                        }
                        // $price = get_woocommerce_currency_symbol( '' ) . ' ' . $price;
                    }
                    // print_r($child_price);

                    $products_details[] = $this->prepare_products_details( array( 'product' => $product, 'category_names' => $category_names ) );

                    
                }
            }
            else{
                $products_details = $this->prepare_products_details_placeholder( $attributes );
            }
            return $products_details;
        }

        public function prepare_products_details( $details_array ){

            extract( $details_array );

            $products_details = array(
                'id' => $product->get_id(),
                'hot' => array(
                    'value' => cartify_woocommerce_label_hot( true ),
                    'text' => esc_html__( 'Hot!', 'agni-builder' ),
                ),
                'new' =>  array(
                    'value' => cartify_woocommerce_label_new( true ),
                    'text' => esc_html__( 'New!', 'agni-builder' ),
                ),
                'image' => array(
                    'id'=> $product->get_image_id(),
                    'src' => wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail'), 
                ),
                'category_names' => $category_names,
                'title' => wp_kses($product->get_title(), 'title'),
                'price' => array(
                    'currency_symbol' => get_woocommerce_currency_symbol(),
                    'decimal_separator' => wc_get_price_decimal_separator(),
                    'thousand_separator' => wc_get_price_thousand_separator(),
                    'decimals' => wc_get_price_decimals(),
                    'price' => $product->get_price(),
                    // 'price_range' => $child_price,
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'sale_from' => $product->get_date_on_sale_from(),
                    'sale_to' => $product->get_date_on_sale_to(),
                    'total_sales' => $product->get_total_sales(),
                ), 
                'button' => array(
                    'text' => $product->add_to_cart_text(),
                )
            );

            return $products_details;
        }

        public function prepare_products_details_placeholder($attributes){
            $count = $attributes['count']; 
            // $placeholder_image = get_option( 'woocommerce_placeholder_image', 0 );
            $products_details = array();
            
            for ($i = 0; $i < $count; $i++) {
            
                $products_details[] = array(
                    'image' => array(
                        'src' => wc_placeholder_img_src(), 
                    ),
                    'category_names' => 'Sample category',
                    'title' => 'This is Sample product title',
                    'price' => array(
                        'currency_symbol' => '$',
                        'decimal_separator' => '.',
                        'decimals' => '2',
                        'price' => '19.99',
                    ), 
                    'button' => array(
                        'text' => 'Add to cart',
                    )
                );
            }

            return $products_details;
        }

        public function get_sliders_list( WP_REST_Request $request ){
            return get_option('agni_slider_builder_sliders');
        }

        public function get_slider( WP_REST_Request $request ){
            
            $attributes = $request;

            // $attributes['id'] = '7';

            ob_start();

            do_action( 'agni_slider', $attributes['id'] );

            return ob_get_clean();
        }

    }

}

$agni_builder_rest_api = new AgniBuilderRestAPI();