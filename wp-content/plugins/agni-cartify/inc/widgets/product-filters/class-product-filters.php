<?php

if( !class_exists( 'AgniProductFilters' ) ){
    
    class AgniProductFilters {

        /*
        option to display category without checkbox
        attribute filter active bug

        add rating widget
        */

        private $params = ''; // can be removed

        private $products_price_range;

        private $has_price_range = false;

        private $args;

        private $active_filters = array();

        public function __construct(){

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            // add_action( 'init', array( $this, 'includes' ) );

            $this->includes();

            add_action( 'wc_ajax_agni_products_filter', array($this, 'cartify_products_filter') );
            add_action( 'wc_ajax_nopriv_agni_products_filter', array($this, 'cartify_products_filter') );

        }


        public function includes(){

            // widget - filter by category
            require_once 'widget-filter-by-categories.php';

            // widget - filter by attribute
            require_once 'widget-filter-by-attribute.php';

            // widget - filter by brands
            require_once 'widget-filter-by-brands.php';

            // widget - filter by price
            require_once 'widget-filter-by-price.php';

            // widget - filter by rating
            require_once 'widget-filter-by-rating.php';

            // widget - active filters
            require_once 'widget-active-filters.php';


        }



        public function cartify_products_filter(){

            if (!check_ajax_referer('agni_ajax_products_filter_nonce', 'security')) {
                return 'Invalid Nonce';
            }


            // print_r( $_GET['params'] );


            // if( !isset( $_GET['params'] ) ){
            //     return;
            // }

            $current_url = isset( $_GET['current_url'] )?$_GET['current_url']:'';

            // ob_start();
            $this->params = (isset($_GET['params']) && !empty( $_GET['params'] ))?$_GET['params']:'';
            
            // print_r($product);
            // echo "current page" . $_GET['current_page'];
            $current_page = (isset( $_GET['current_page'] ) && !empty($_GET['current_page']) )?$_GET['current_page']:'1';
            $count = wc_get_loop_prop( 'columns' ) * get_option( 'woocommerce_catalog_rows', 4 );
            $current_order = apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

            if( !empty( $this->params ) ){
                foreach( $this->params as $param ){
                    if( $param['param'] == 'count' ){
                        $count = $param['values'][0];
                    }
                }
            }

        
            $this->args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'posts_per_page'        => $count,
                'ignore_sticky_posts'   => 1,
                'hierarchical'          => true, // show all sub categories
                'orderby'               => 'menu_order title',
                'order'                 => 'desc',
                'post__in'              => '',
                'paged'                 => $current_page
                // 'post__in'              => $product_ids,
                // 'suppress_filters'      => true
            );

            $this->args['tax_query'][] = array(
                'taxonomy'  => 'product_visibility',
                'terms'     => array( 'exclude-from-catalog' ),
                'field'     => 'name',
                'operator'  => 'NOT IN',
            );

            
            // echo "taxonomy:";
            // if( get_query_var( 'term' ) ){
            //     $term = get_queried_object();

            //     print_r( $term );

            // }

            // print_r($category_ids);
            if( !empty( $this->params ) ){
                foreach( $this->params as $filter_param ){

                    switch ($filter_param['param']) {
                        case 's':
                            $this->args['s'] = $filter_param['values'][0];
                            break;
                        // case 'product_cat':
                        case 'post_type':
                            $this->args['post_type'] = $filter_param['values'][0];
                            break;
                        case 'product_cat':
                            $this->args['tax_query'][] = array(
                                'taxonomy'  => 'product_cat',
                                'terms' => $filter_param['values'],
                            );
                            break;
                        case 'product_tag':
                            $this->args['tax_query'][] = array(
                                'taxonomy'  => 'product_tag',
                                'terms' => $filter_param['values'],
                            );
                            break;
                        case 'filter_product_cat':
                            $this->get_param_category( $filter_param );
                            break;
                        
                        case 'filter_product_brand':
                            $this->get_param_brand( $filter_param );
                            break;
                    
                        case 'min_price':
                        case 'max_price':
                            $this->get_param_price($filter_param);
                            break;
                        
                        case 'rating':
                            $this->get_param_rating( $filter_param );
                            break;
                        default:
                            $this->get_param_attribute( $filter_param );
                            break;
                    }
                }
    
            }

            $this->get_product_display_order($current_order);

            $this->get_products_query();


            ob_start();
            $this->get_result_count();
            $result_count = ob_get_clean();

            $redirect_url = '';
            if( $result_count == 0 && $current_page != '1'){
                $this->args['paged'] = '1';

                $this->get_products_query();

                ob_start();
                $this->get_result_count();
                $result_count = ob_get_clean();

                $redirect_url = get_permalink( wc_get_page_id( 'shop' ) );
            }


            ob_start();
            $this->get_products();
            $products = ob_get_clean();

            ob_start();
            $this->get_result_count_html();
            $result_count_html = ob_get_clean();

            ob_start();
            $this->get_product_pagination($current_url);
            $pagination = ob_get_clean();

            wp_send_json( array( 
                'content' => $products, 
                'result_count' => $result_count,
                'result_count_html' => $result_count_html,
                'pagination' => $pagination,
                'active_filters' => $this->active_filters,
                'redirect_url' => $redirect_url
            ) );
        
            

            die();
            
        }

        public function prepare_json(){

        }

        public function get_param_category($param){

            $category_ids = $param['values'];

            if( !empty($category_ids) ){
                $categories_name_array = $categories_name_object = array();

                foreach($category_ids as $cat_id){
                    $categories_name_array[]= $cat_id; //get_the_category_by_ID( $cat_id );
                    $categories_name_object[] = array( 
                        'id'  => $cat_id,
                        'name'  => get_the_category_by_ID( $cat_id )
                    );
                }

                $this->args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    // 'field'    => 'slug',
                    'terms'    => $categories_name_array,
                    'include_children' => true, // show all sub categories
                );


                $this->active_filters[] = array(
                    'param' => $param['param'],
                    'values' => $categories_name_object,
                );
            }

            
            // return $this->args;
        }

        public function get_param_brand($param){

            $category_ids = $param['values'];

            if( !empty($category_ids) ){
                $categories_name_array = array();

                foreach($category_ids as $cat_id){
                    // print_r( get_the_category_by_ID( $cat_id ) );
                    $categories_name_array[]= $cat_id; //get_the_category_by_ID( $cat_id );
                    $categories_name_object[] = array( 
                        'id'  => $cat_id,
                        'name'  => get_the_category_by_ID( $cat_id )
                    );
                }

                // print_r( $categories_name_array );
                $this->args['tax_query'][] = array(
                    'taxonomy' => 'product_brand',
                    // 'field'    => 'slug',
                    'terms'    => $categories_name_array,
                    // 'include_children' => true, // show all sub categories
                );

                $this->active_filters[] = array(
                    'param' => $param['param'],
                    'values' => $categories_name_object,
                );
            }
            
            // return $this->args;
        }

        public function get_param_attribute($param){

            if( strpos($param['param'], 'filter') === false ){
                return;
            }

            $product_ids = array();

            $product_ids = $this->get_attribute_product_ids($param);

            // print_r( $this->args );

            $existing_post_ids = $this->args['post__in'];

            if( $existing_post_ids ){
                $this->args['post__in'] = array_intersect( $existing_post_ids, $product_ids );
            }
            else{
                $this->args['post__in'] = $product_ids;
            }

            // if( empty( $this->args['post__in'] ) ){
            //     $this->args['found_posts'] = 0;
            // }
            

            foreach( $param['values'] as $term_slug ){
                $term = get_term_by( 'slug', $term_slug, str_replace( 'filter_', 'pa_', $param['param'] ) );


                $param_values[] = array( 
                    'id'  => $term_slug,
                    'name'  => $term->name
                );
            }

            // $this->active_filters[] = $param;
            $this->active_filters[] = array(
                'param' => $param['param'],
                'values' => $param_values,
            );
        }

        public function get_attribute_product_ids($param){
            // print_r( $params );
            
            $filter_attributes = $product_ids = array();
            $filter_attributes[$param['param']] = $param['values'];

            $products = wc_get_products( array(
                'limit'   => -1,
                'orderby' => 'date',
                'order' => 'DESC',
            ) );

            foreach( $products as $product ){

                $attributes = $product->attributes;

                foreach( $attributes as $tax_id => $attribute ){
                    foreach( $filter_attributes as $filter_tax_id => $filter_attribute ){
                        $new_filter_tax_id = str_replace( 'filter_', 'pa_', $filter_tax_id );
                        if( $attribute->get_name() == $new_filter_tax_id ){

                            foreach( $filter_attribute as $term_name ){
                                $term = get_term_by( 'slug', $term_name, $tax_id );

                                if( in_array( $term->term_id, $attribute->get_options() ) ){
                                    // echo $product->get_title(); //'Exist';

                                    $product_ids[] = $product->get_id();
                                    
                                }
                            }
                            
                        }
                    }
                }

            }
            
            return $product_ids;
        }
        
        public function get_param_price($param){

            $filter_price = implode( '', $param['values'] );

            

            // echo "price params";
            // // $param['param']
            // print_r( $param['values'] );
            // echo "ends";

            // $price_list = $this->get_products_price();

            // $product_ids = array();

            // $existing_products = $this->products_price_range;

            if( $param['param'] == 'min_price'){
                $this->args['meta_query'][] = array(
                    'key' => '_price',
                    'value' => $param['values'][0],
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                );
                
            } 
            if( $param['param'] == 'max_price' ){
                $this->args['meta_query'][] = array(
                    'key' => '_price',
                    'value' => $param['values'][0],
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                );
            }
            $this->args['meta_query']['relation'] = 'AND';


            foreach( $param['values'] as $value ){
                
                $param_values[] = array( 
                    'id'  => $value,
                    'name'  => $value
                );
            }

            // $this->active_filters[] = $param;
            $this->active_filters[] = array(
                'param' => $param['param'],
                'values' => $param_values,
            );
            
            // print_r( $this->products_price_range );
        }

        public function get_products_price(){
            $price_list = array();
            $products = wc_get_products( array(
                'limit'   => -1,
                'orderby' => 'date',
                'order' => 'DESC',
            ) );

            foreach($products as $product){
                $price_list[$product->get_id()] =  $product->get_price();
            }


            return $price_list;
        }

        public function get_products_min_price( $html = true ){
            
            $price = min( $this->get_products_price() );

            if( $html == false ){
                // $price = number_format($price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator());
                $price = number_format((float)$price, 0, '', wc_get_price_thousand_separator());
            }
            else{
                $price = wc_price($price);
            }

            return $price;
        }

        public function get_products_max_price( $html = true ){
            
            $price = max( $this->get_products_price() );

            if( $html == false ){
                // $price = number_format($price, 0, '', wc_get_price_thousand_separator());
                $price = number_format($price, 0, '', '');
            }
            else{
                $price = wc_price($price);
            }

            return $price;
        }

        public function get_param_rating($param){
            $product_ids = array();

            $product_ids = $this->get_rating_product_ids($param);

            $existing_post_ids = $this->args['post__in'];

            if( $existing_post_ids ){
                $this->args['post__in'] = array_intersect( $existing_post_ids, $product_ids );
            }
            else{
                $this->args['post__in'] = $product_ids;
            }


            foreach( $param['values'] as $value ){
                
                $param_values[] = array( 
                    'id'  => $value,
                    'name'  => $value
                );
            }

            $this->active_filters[] = array(
                'param' => $param['param'],
                'values' => $param_values,
            );
        }

        public function get_rating_product_ids($param){
            $product_ids = array();

            $products = wc_get_products( array(
                'limit'   => -1,
                'orderby' => 'date',
                'order' => 'DESC',
            ) );

            foreach( $products as $product ){

                $rating_count = $product->get_rating_count();
                // echo $rating_count;
                if ( $rating_count > 0 ){
                    $average_rating = $product->get_average_rating();

                    foreach ($param['values'] as $key => $value) {
                        $rating = str_replace( '+', '', $value );
                        $rating_limit = $rating + 1;

                        if( $rating == 5 && $rating == $average_rating ){
                            $product_ids[] = $product->get_id();
                        }
                        elseif( $rating == 4 && $rating <= $average_rating && $rating_limit >= $average_rating ){
                            $product_ids[] = $product->get_id();
                        }
                        elseif( $rating <= $average_rating && $rating_limit > $average_rating ){
                            $product_ids[] = $product->get_id();
                        }
                    }

                }

            }
            // print_r( $product_ids );
            return $product_ids;
        }

        public function get_products_query(){

            $product_args = apply_filters( 'cartify_ajax_product_filters_args', $this->args, $this->params );


            // print_r($product_args);
            global $products_filter_query;

            $products_filter_query = new WP_Query( $product_args );
        }


        public function get_products(){
            global $products_filter_query;
            // $products_filter_query = $this->get_products_query();
            // echo "after query";
            // print_r($products_filter_query);

            // echo 'hello' . woocommerce_result_count();

            if( $products_filter_query->have_posts() ){
                while ($products_filter_query->have_posts()) { $products_filter_query->the_post();
                    
                    /**
                     * Hook: woocommerce_shop_loop.
                     */
                    do_action( 'woocommerce_shop_loop' );

                    wc_get_template_part( 'content', 'product' );

                }
            }
            else{
                do_action( 'woocommerce_no_products_found' );
            }

        }

        public function get_result_count(){
            global $products_filter_query;

            echo (int) $products_filter_query->found_posts;

        }

        public function get_result_count_html(){
            global $products_filter_query;

            $args = array(
                'total'    => (int) $products_filter_query->found_posts,
                'per_page' => (int) $products_filter_query->query_vars['posts_per_page'],
                'current'  => (int) $products_filter_query->query_vars['paged'],
            );

            wc_get_template( 'loop/result-count.php', $args );

        }

        public function get_product_display_order($current_order){
            switch ( $current_order ) {
                case 'date' :
                    $orderby = 'date';
                    $order = 'desc';
                    $meta_key = '';
                break;
                case 'price' :
                    $orderby = 'meta_value_num';
                    $order = 'asc';
                    $meta_key = '_price';
                break;
                case 'title' :
                    $orderby = 'meta_value';
                    $order = 'asc';
                    $meta_key = '_woocommerce_product_short_title';
                break;
                default :
                    $orderby = 'menu_order title';
                    $order = 'asc';
                    $meta_key = '';         
                break;
            }

            $this->args['orderby'] = $orderby;
            $this->args['order'] = $order;

            if( $meta_key ){
                $this->args['meta_key'] = $meta_key;

            }
        }

        public function get_product_pagination($current_url){
            global $products_filter_query;
            
            $current = (int) $products_filter_query->query_vars['paged'];
            $total = $products_filter_query->max_num_pages;

            $shop_pagination_style = '1';
            
            if( function_exists( 'cartify_get_theme_option' ) ){
                $shop_pagination_style = cartify_get_theme_option( 'shop_settings_general_pagination', '1' );
            }

            $pagination_classes = cartify_prepare_classes(array(
                'agni-woocommerce-pagination',
                'has-ajax',
                'has-display-style-' . $shop_pagination_style
            ));

            $options = array(
                'current' => $current,
                'total' => $total
            )

            ?>
            <div class="<?php echo esc_attr( $pagination_classes ); ?>">
                <?php if( $shop_pagination_style == '2' ){ ?>
                    <a class="agni-woocommerce-pagination-infinite" href="#" data-infinite-options="<?php echo esc_attr( json_encode($options) ); ?>">
                        <span><?php echo esc_html__( 'Load More', 'agni-cartify' ) ?></span>
                        <span><?php echo esc_html__( 'Loading', 'agni-cartify' ) ?></span>
                    </a>
                <?php }
                else { ?>
                    <?php if( $current > 1 ){ ?>
                        <span class="agni-woocommerce-pagination__prev"><a href="<?php echo esc_url( $this->get_product_prev_pagination_url( $current_url, $current ) ); ?>"><span><i class="lni lni-chevron-left"></i></span><span><?php echo esc_html__( 'Previous', 'agni-cartify' ); ?></span></a></span>
                    <?php } ?>
                    <span class="agni-woocommerce-pagination__contents">
                        <span class="agni-woocommerce-pagination__current"><input class="agni-woocommerce-pagination__input" type="number" min="1" max="<?php echo esc_attr($total); ?>" value="<?php echo esc_attr($current); ?>"></span>
                        <span class="agni-woocommerce-pagination__count-text"><?php echo sprintf( __( 'of %s Pages', 'agni-cartify' ), esc_html($total) ); ?></span>
                    </span>
                    
                    <?php if( $current < $total ){ ?>
                        <span class="agni-woocommerce-pagination__next"><a href="<?php echo esc_url( $this->get_product_next_pagination_url( $current_url, $current ) ); ?>"><span><i class="lni lni-chevron-right"></i></span><span><?php echo esc_html__( 'Next', 'agni-cartify' ); ?></span></a></span>
                    <?php } ?>
                <?php } ?>
            </div>
            <?php
        }

        public function get_product_prev_pagination_url($current_url, $current){

            $current_link = $current_url;
            
            $key = "/\/page\/\d+/";
            if( preg_match( $key, $current_link, $match) == false ){
                $key = "/paged=\d+/";
                if( preg_match( $key, $current_link, $match) ){
                    if( $current == 2 ){
                        $prev_page_link = preg_replace($key, '', $current_link);
                    }
                    else{
                        $prev_page_link = preg_replace($key, 'paged=' . ($current - 1), $current_link);
                    }
                }
            }
            else{
                if( $current == 2 ){
                    $prev_page_link = preg_replace($key, '', $current_link);
                }
                else{
                    $prev_page_link = preg_replace($key, '/page/' . ($current - 1), $current_link);
                }
            }

            return $prev_page_link;
        }

        public function get_product_next_pagination_url($current_url, $current){

            $current_link = $current_url;
            
            $key = "/\/page\/\d+/";
            if( preg_match( $key, $current_link, $match) == false ){
                $key = "/paged=\d+/";
                if( preg_match( $key, $current_link, $match) == false ){
                    $next_page_link = $this->generate_next_pagination_url($current_url, $current);
                }
                else{
                    $next_page_link = preg_replace($key, 'paged=' . ($current + 1), $current_link);
                }
            }
            else{
                $next_page_link = preg_replace($key, '/page/' . ($current + 1), $current_link);
            }

            return $next_page_link;
        }

        public function generate_next_pagination_url($current_url, int $current){

            $current_url_array = explode( '?', $current_url );
            $current_url_array[0] = trailingslashit( $current_url_array[0] ) . 'page/' . ($current + 1) . '/';
            
            $current_link = implode( '?', $current_url_array );
            return $current_link;
            
        }


        public function enqueue_scripts(){

            // wp_enqueue_script( 'wc-add-to-cart-variation' );

            // Register scripts
            wp_register_script( 'agni-product-filters', AGNI_PLUGIN_URL . '/assets/js/product-filters/product-filters.js', array( 'jquery' ), true );
            wp_localize_script( 'agni-product-filters' , 'cartify_ajax_products_filter', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
                'security' => wp_create_nonce('agni_ajax_products_filter_nonce'),
            ));
            wp_enqueue_script( 'agni-product-filters' );
        }
    }

}

$agni_product_filters = new AgniProductFilters();