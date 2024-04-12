<?php  

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class AgniProductRestApi{

    public function __construct(){
        add_action( 'rest_api_init', array($this, 'register_api' ), 10 );

    }

    public function register_api(){

        if( !class_exists('WooCommerce') ){
            return;
        }

        $current_user_can = current_user_can( 'edit_posts' );

        register_rest_route( 'agni-product-builder/v1', '/layouts', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_layouts_list'),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );
        register_rest_route( 'agni-product-builder/v1', '/layouts/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_layout'),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );

         register_rest_route( 'agni-product-builder/v1', '/layouts/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'update_layout' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
         ) );

         register_rest_route( 'agni-product-builder/v1', '/layouts/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => array( $this, 'delete_layout' ),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
         ) );


        register_rest_route( 'agni-product-builder/v1', '/product', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_product'),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );
        

        register_rest_route( 'agni-product-builder/v1', '/products', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_products_list'),
            'permission_callback' => function() use($current_user_can){
                return $current_user_can;
            },
        ) );

    }
    public function get_layouts_list( WP_REST_Request $request ){
        $agni_product_builder_layout_custom = get_option( 'agni_product_builder_layouts_list' );

        return $agni_product_builder_layout_custom;
    }
    public function get_layout( WP_REST_Request $request ) {
        $id = $request['id'];
        $results = get_option( 'agni_product_builder_layouts_list' );
        return $results[$id];
    }

    public function update_layout( WP_REST_Request $request ) { 

        $url_param = $request->get_url_params();
        $layout_id = $url_param['id'];
        $layout = $request->get_param( $url_param['id'] );

        $results = (array)get_option( 'agni_product_builder_layouts_list' );

        $layout_key = array_search($layout_id, array_column($results, 'id'));
        // echo "default is true:" . ($layout['default'] == true);
        if( false !== $layout_key ){
            $results[$layout_key] = $layout;
        }
        else{
            $results[] = $layout;
        }


        if( $layout['default'] == true ){
            foreach ($results as $key => $existingLayout) {
                if( $layout_key !== $key ){
                    unset($results[$key]['default']);
                    $results[$key]['default'] = false;
                }
            }
        }

        // print_r($results);
        $results = update_option( 'agni_product_builder_layouts_list', $results );
        // return $results;
        // return $layout;
        return 'Success!';
    }

    public function delete_layout( WP_REST_Request $request ) { 
        $url_param = $request->get_url_params();
        $layout_id = $url_param['id'];

        $results = (array)get_option( 'agni_product_builder_layouts_list' );

        $layout_key = array_search($layout_id, array_column($results, 'id'));

        if( false !== $layout_key ){
            
            array_splice($results, $layout_key, 1);
        
            // print_r($results);
            $results = update_option( 'agni_product_builder_layouts_list', $results );

            return wp_send_json_success( 'Deleted!' );
        }
        else{
            return wp_send_json_error( 'Header Not found!' );
        }
    }

    public function get_product( WP_REST_Request $request ){

        // echo " ";




        $params = $request->get_params();

        $newData = [];

        if( isset($params['id']) && !empty($params['id'])){            
            $product = wc_get_product($params['id']);
        }
        else{
            $products = wc_get_products( array( 'status' => 'publish', 'limit' => -1, 'order' => 'ASC' ) );
            $product = $products[0];
        }

        $product_id = $product->get_id();

        $data = $product->get_data();

        // print_r( $data['meta_data'] );

        // print_r( $product->get_meta('agni_product_data_offer_text') );

        // print_r($data);
        $newData = $data;

        unset( $newData['meta_data'] );

        $upsells = $recently_viewed = $categories = $tags = $image = $gallery = $brands = $compares = $addons = [];
        

        $attribute_taxonomies = wc_get_attribute_taxonomies();

        $attributes_type_array = [];
        foreach ($attribute_taxonomies as $key => $tax) {
            
            $attributes_type_array['pa_' . $tax->attribute_name] = array(
                'name' => $tax->attribute_label,
                'display_type' => $tax->attribute_type
            );
        }

        $product_attributes = $product->get_attributes();

        foreach ($product_attributes as $key => $attribute) {
            $attribute_data = $attribute->get_data();


            $variation_attributes_options = [];
            foreach($attribute_data['options'] as $option){
                $term = get_term_by( 'id', $option, $attribute_data['name'] );
                $value = get_term_meta( $term->term_id, 'agni_variation_swatch_field', true );

                $variation_attributes_options[] = array(
                    'name' => $term->name,
                    'value' => $value
                ); 
            }
            $variation_attributes[] = array(
                'name' => $attributes_type_array[$attribute_data['name']]['name'],
                'tax_name' => $attribute_data['name'],
                'display_type' => $attributes_type_array[$attribute_data['name']]['display_type'],
                'options' =>$variation_attributes_options
            );
        }

        $newData['attributes'] = $variation_attributes;

        $related_ids = wc_get_related_products($product_id, 10, $product->get_upsell_ids());

        foreach ($related_ids as $id) {
            $product_data = wc_get_product( $id )->get_data();

            $product_data_categories = [];
            foreach ($product_data['category_ids'] as $cat_id) {
                $term = get_term_by( 'id', $cat_id, 'product_cat' );
    
                $product_data_categories[] = array(
                    'name' => $term->name
                );
            }

            $related[] = array(
                'id' => wc_get_product( $id )->get_id(),
                'name' => $product_data['name'],
                'price' => $product_data['price'],
                'regular_price' => $product_data['regular_price'],
                'sale_price' => $product_data['sale_price'],
                'image' => wp_get_attachment_url($product_data['image_id']),
                'type' => wc_get_product( $id )->get_type(),
                'short_description' => $product_data['short_description'],
                'categories' => $product_data_categories,

            );
        }

        foreach ($data['upsell_ids'] as $id) {
            $product_data = wc_get_product( $id )->get_data();
            $product_data_categories = [];
            foreach ($product_data['category_ids'] as $cat_id) {
                $term = get_term_by( 'id', $cat_id, 'product_cat' );
    
                $product_data_categories[] = array(
                    'name' => $term->name
                );
            }
            $upsells[] = array(
                'name' => $product_data['name'],
                'price' => $product_data['price'],
                'regular_price' => $product_data['regular_price'],
                'sale_price' => $product_data['sale_price'],
                'image' => wp_get_attachment_url($product_data['image_id']),
                'type' => wc_get_product( $id )->get_type(),
                'short_description' => $product_data['short_description'],
                'categories' => $product_data_categories,
            );
        }


        foreach ($data['category_ids'] as $id) {
            $term = get_term_by( 'id', $id, 'product_cat' );

            $categories[] = array(
                'name' => $term->name
            );
        }

        foreach ($data['tag_ids'] as $id) {
            $term = get_term_by( 'id', $id, 'product_tag' );

            $tags[] = array(
                'name' => $term->name
            );
        }

        $taxonomy_slug = apply_filters('cartify_woocommerce_template_single_brand_taxonomy_slug', 'product_brand');

        $brand_terms = get_the_terms( $product_id, $taxonomy_slug );
        
        if( $brand_terms ){
            foreach ($brand_terms as $key => $term) {
                $brands[] =  array(
                    'name' => $term->name,
                    'logo' => wp_get_attachment_url(get_term_meta($term->term_id, 'agni_product_brand_icon_id', true)),
                );
            }
        }
        
        $images[] = wp_get_attachment_url($data['image_id']);

        foreach ($data['gallery_image_ids'] as $image_id) {
            $images[] = wp_get_attachment_url($image_id);
        }
        
        $offers = array(
            'title' => $product->get_meta('agni_product_data_offer_title'),
            'value' => $product->get_meta('agni_product_data_offer_text'),
        );

        $shipping = array(
            'title' => $product->get_meta('agni_product_data_shipping_info_title'),
            'value' => $product->get_meta('agni_product_data_shipping_info_desc'),
            'link' => $product->get_meta('agni_product_data_shipping_info_link_text'),
        );

        $shipping_tab = array(
            'title' => $product->get_meta('agni_product_data_tab_shipping_info_title'),
            'value' => $product->get_meta('agni_product_data_tab_shipping_info_desc'),
        );

        $specifications = array(
            'title' => $product->get_meta('agni_product_data_tab_specification_title'),
            'value' => $product->get_meta('agni_product_data_tab_specification_table_data')
        );

        $compare_products = $product->get_meta('agni_product_data_compare');

        foreach ($compare_products as $key => $id) {
            $product_data = wc_get_product( $id )->get_data();
            $product_data_categories = [];
            foreach ($product_data['category_ids'] as $cat_id) {
                $term = get_term_by( 'id', $cat_id, 'product_cat' );
    
                $product_data_categories[] = array(
                    'name' => $term->name
                );
            }
            $compare_attributes = [];

            foreach ($attribute_taxonomies as $key => $tax) {
                
                // if( $tax->attribute_name )

                $product_terms = get_the_terms( $id, 'pa_'.$tax->attribute_name );
                if( $product_terms ){
                    $compare_attributes_options = [];
                    foreach( $product_terms as $product_term ){
                        // $compare_attributes['pa_'.$tax->attribute_name][] = array(
                        //     'name' => $product_term->name,
                        // );
                        $compare_attributes_options[] = array(
                            'name' => $product_term->name,
                        );
                    }

                    $compare_attributes[] = array(
                        'tax_name' => 'pa_'.$tax->attribute_name,
                        'name' => $tax->attribute_label,
                        'options' => $compare_attributes_options,
                    );
                }
            }

            $compare_specifications = array(
                'title' => wc_get_product( $id )->get_meta('agni_product_data_tab_specification_title'),
                'value' => wc_get_product( $id )->get_meta('agni_product_data_tab_specification_table_data')
            );

            $compares[] = array(
                'id' => $id,
                'name' => $product_data['name'],
                'price' => $product_data['price'],
                'regular_price' => $product_data['regular_price'],
                'sale_price' => $product_data['sale_price'],
                'image' => wp_get_attachment_url($product_data['image_id']),
                'type' => wc_get_product( $id )->get_type(),
                'short_description' => $product_data['short_description'],
                'categories' => $product_data_categories,
                'rating_counts' => $product_data['rating_counts'],
                'specifications' => $compare_specifications,
                'sku' => wc_get_product( $id )->get_sku(),
                'attributes' => $compare_attributes
            );
        }

        $addon_products = $product->get_meta('agni_product_data_addon_products');

        foreach ($addon_products as $key => $id) {
            $product_data = wc_get_product( $id )->get_data();
            $product_data_categories = [];
            foreach ($product_data['category_ids'] as $cat_id) {
                $term = get_term_by( 'id', $cat_id, 'product_cat' );
    
                $product_data_categories[] = array(
                    'name' => $term->name
                );
            }
            $addons[] = array(
                'name' => $product_data['name'],
                'price' => $product_data['price'],
                'regular_price' => $product_data['regular_price'],
                'sale_price' => $product_data['sale_price'],
                'image' => wp_get_attachment_url($product_data['image_id']),
                'type' => wc_get_product( $id )->get_type(),
                'short_description' => $product_data['short_description'],
                'categories' => $product_data_categories,
            );
        }

        $recently_viewed_products = cartify_recently_viewed_products_id();

        
        foreach ($recently_viewed_products as $key => $id) {
            $recently_product = wc_get_product( $id );
            if( !empty( $recently_product ) ){
                $product_data = wc_get_product( $id )->get_data();
                
                $recently_viewed[] = array(
                    'name' => $product_data['name'],
                    'image' => wp_get_attachment_url($product_data['image_id'])
                );
            }
        }
        
        $products_list_new = $products_list_hot = '';
        
        if( function_exists( 'cartify_get_theme_option' ) ){
            $products_list_new = cartify_get_theme_option( 'shop_settings_label_new', '' );
            $products_list_hot = cartify_get_theme_option( 'shop_settings_label_hot', '' );
        }

        if( !empty( $products_list_new ) && in_array( $product_id, $products_list_new ) ){
            $newData['new'] = true;
        }

        if( !empty( $products_list_hot ) && in_array( $product_id, $products_list_hot ) ){
            $newData['hot'] = true;
        }
        
        $newData['type'] = $product->get_type();
        $newData['recently_viewed'] = $recently_viewed;
        $newData['related'] = $related;
        $newData['upsells'] = $upsells;
        $newData['cross_sells'] = $cross_sells;
        $newData['categories'] = $categories;
        $newData['tags'] = $tags;
        $newData['image'] = wp_get_attachment_url($data['image_id']);
        $newData['images'] = $images;
        $newData['features'] = $product->get_meta('agni_product_data_features');
        $newData['offers'] = $offers;
        $newData['brands'] = $brands;
        $newData['specifications'] = $specifications;
        $newData['shipping'] = $shipping;
        $newData['shipping_tab'] = $shipping_tab;
        $newData['product_video'] = $product->get_meta('agni_product_data_video_embed_url');
        $newData['product_360'] = $product->get_meta('agni_product_data_threesixty_images');
        $newData['compares'] = $compares;
        $newData['addons'] = $addons;


        return $newData;

    }

    public function get_products_list( WP_REST_Request $request ){

        return wc_get_products();
    }
}

$AgniProductRestApi = new AgniProductRestApi();

?>