<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action('agni_header_ajax_search', 'cartify_ajax_search');
add_action('wp_enqueue_scripts', 'cartify_ajax_search_scripts');

add_action('wp_ajax_agni_processing_ajax_search', 'cartify_processing_ajax_search');
add_action('wp_ajax_nopriv_agni_processing_ajax_search', 'cartify_processing_ajax_search');


if (!function_exists('cartify_ajax_search')) {
    /**
     * displaying search form function
     *
     * @return void
     */
    function cartify_ajax_search( $options ) {

        $search_general_category_dropdown = isset($options['search-general-category-dropdown']) ? $options['search-general-category-dropdown']: "on";
        $search_general_display_style = isset( $options['search-general-display-style'] ) ? $options['search-general-display-style']: "1";
        $search_general_icon_choice = isset( $options['search-general-icon-choice'] ) ? $options['search-general-icon-choice']: "";
        $search_general_icon_custom = isset( $options['search-general-icon-custom'] ) ? $options['search-general-icon-custom']: "";
        $search_general_icon_predefined = isset( $options['search-general-icon-predefined'] ) ? $options['search-general-icon-predefined']: "search";
        $search_general_icon_text = isset( $options['search-general-icon-text'] ) ? $options['search-general-icon-text']: "on";
        $search_general_result_count = isset( $options['search-general-result-count'] ) ? $options['search-general-result-count']: "10";

        wp_enqueue_script('cartify-ajax-search');

        if( $search_general_icon_choice == 'custom' ){
            $icon = '<img src="' . esc_url( $search_general_icon_custom ) . '" />';
        }
        else{
            $icon = cartify_get_icon_svg( 'common', $search_general_icon_predefined);
        }

                ?>
        <div class="agni-ajax-search">
            <?php if( $search_general_display_style == '2' ){ ?>
                <div class="site-header-icon-search site-header-icon">
                    <?php if( !empty( $search_general_icon_text ) ){ ?>
                    <span class="site-header-icon-search__details">
                        <span class="site-header-icon-search__text"><?php echo esc_html( $search_general_icon_text ); ?></span>
                    </span>
                    <?php } ?>  
                    <span class="site-header-icon-search__container">
                        <span class="site-header-icon-container"><?php echo wp_kses( apply_filters('agni_header_icons_search_icon', $icon), 'svg' ); ?></span>
                    </span>
                </div>
            <?php } ?>
            <div class="site-header-search-form-container">
                <form role="search" method="get" class="woocommerce-product-search search-form agni-ajax-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="text" class="agni-ajax-search-form__text" name="s" autocomplete="off" placeholder="<?php echo esc_attr__('Search Products..', 'cartify') ?>" />
                    <?php if( $search_general_category_dropdown != 'off' ){
                        $random_number = rand(10000,99999);

                        $args = array(
                            'hide_if_empty' => true,
                            'show_option_all' => esc_html__( 'All Categories', 'cartify' ),
                            'taxonomy' => 'product_cat',
                            'name' => 'product_cat',
                            'value_field' => 'slug',
                            'class' => 'agni-ajax-search-form__category',
                            'id' => 'agni-ajax-search-category-' . $random_number
                        );
                        wp_dropdown_categories($args); 
                    } ?>
                    <button type="submit" class="agni-ajax-search-form__submit"></button>
                    <input type="hidden" name="post_type" value="product" />

                    <div class="agni-ajax-search__loader"><i class="lni lni-reload"></i><?php //echo esc_html__( 'Loading', 'cartify' ); ?></div>
                </form>
                <div class="agni-ajax-search-results">
                    <div class="agni-ajax-search-results__container"></div>
                </div>
            </div>
        </div>

<?php
    }
}

if (!function_exists('cartify_processing_ajax_search')) {

    /**
     * processing ajax results for search form
     *
     * @return void
     */
    function cartify_processing_ajax_search(){

        if (!check_ajax_referer('agni_ajax_search_nonce', 'security')) {
            return 'Invalid Nonce';
        }

        // option to enable/disable categories dropdown
        // option to choose posttypes
        // option to show/hide category results.
        // suggested list of keywords at backend.
        // option to show/hide out of stock.

        $keyword = $_POST['s'];
        $product_search_category = $_POST['product_cat'];

        $post_types = 'product';
        $posts_per_page = 5;
        $show_cat = '';
        $show_results_out_of_stock = '';
        $show_results_cat = '';
        $relevanssi_search = 'off'; //off/on


        $category_args = array(
            'posts_per_page'     => $posts_per_page
        );

        $args = array(
            'post_type'             => $post_types,
            'posts_per_page'        => $posts_per_page,
            'post_status'           => 'publish',
            'product_cat'           => esc_attr($product_search_category),
            'ignore_sticky_posts'   => 1,
            's' => esc_attr($keyword),
        );

        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'exclude-from-catalog',
                'operator' => 'NOT IN',
            ),

            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'exclude-from-search',
                'operator' => 'NOT IN',
            ),
        );

        if( empty( $show_results_out_of_stock ) ){
			$args['meta_query'] = array(
				array(
					'key' => '_stock_status',
					'value' => 'instock'
				),
				array(
					'key' => '_backorders',
					'value' => 'no'
				),
			);
		}

        $products_search_query = new WP_Query($args);

        if( $relevanssi_search == 'on' ){
            relevanssi_do_query( $products_search_query );
        }

        $category_results = cartify_processing_ajax_search_categories($products_search_query);

        $term_ids = array_slice( $category_results, 0, $category_args['posts_per_page'], true );

        if ($products_search_query->have_posts()) {
            ?>
            <ul class="agni-ajax-search-result__items">
                <?php while ($products_search_query->have_posts()) { $products_search_query->the_post();
                    global $product; 
                    $product_title = get_the_title();
                    $product_title = str_ireplace( $keyword, '<span class="keyword">'.$keyword.'</span>', $product_title );
                    ?>
                    <li class="agni-ajax-search-result item">
                        <a href="<?php the_permalink(); ?>" class="agni-ajax-search-result__link">
                            <div class="agni-ajax-search-result__thumbnail">
                                <?php 

                                echo wp_kses( $product->get_image(), array( 
                                    'img' => array(
                                        'width' => array(),
                                        'height' => array(),
                                        'src' => array(),
                                        'class' => array(),
                                        'alt' => array(),
                                        'loading' => array()
                                    )
                                ));

                                                                 ?>
                            </div>
                            <?php //the_title('<div class="product-title">', '</div>'); ?>
                            <div class="agni-ajax-search-result__title"><?php echo wp_kses( $product_title, array( 'span' => array( 'class' => array() ) ) ); ?></div>
                            <div class="agni-ajax-search-result__price">
                                <span class="price">
                                    <?php if ($price_html = $product->get_price_html()) { ?>
                                        <?php echo wp_kses( $price_html, array(
                                            'del' => array(
                                                'aria-hidden' => array(),
                                                'class' => array(),
                                            ),
                                            'ins' => array(
                                                'class' => array(),
                                            ),
                                            'span' => array(
                                                'class' => array(),
                                            )
                                        )); ?>
                                    <?php } ?>
                                </span>
                            </div>
                        </a>
                    </li>

                <?php } ?>
                <?php

                if(!empty($term_ids)){
                ?>
                    <?php foreach( $term_ids as $term_id => $count ){
                        $term = get_term( $term_id , 'product_cat' );
                        // $terms_array = array();

                        $term_name = $term->name;

                        // $search_url = add_query_arg(array(
                        //     's' => $keyword,
                        //     'product_cat' => $term->slug,
                        //     'post_type' => $post_types
                        // ), esc_url(home_url('/')) );

                        // $terms_array = ;
                        // $term_name = str_ireplace( $keyword, '<span>'.$keyword.'</span>', $term_name );
                        ?>
                        <li class="agni-ajax-search-result term">
                            <a href="<?php echo esc_url( cartify_ajax_search_url( $keyword, $post_types, $term->slug ) ); ?>" class="agni-ajax-search-result__link">
                                <div class="agni-ajax-search-result__icon"><?php // echo cartify_get_icon_svg( 'common', 'search' ); ?></div>
                                <div class="agni-ajax-search-result__title"><span class="keyword"><?php echo esc_html( $keyword ); ?></span><span class="sep"><?php echo esc_html__( 'in', 'cartify' );  ?></span><span><?php echo esc_html( $term->name ); ?></span></div>
                                <div class="agni-ajax-search-result__categories"><?php echo esc_html__( 'in', 'cartify' );  ?><?php if($term->parent){
                                    cartify_ajax_search_get_category_parents( get_term( $term->parent , 'product_cat' ) ); 
                                } 
                                else { ?>
                                    <span><?php echo esc_html__( 'categories', 'cartify' ); ?></span>
                                <?php } ?></div>
                            </a>
                        </li>
                        <?php
                    } ?>
                <?php
                }

            ?>
            </ul>
            <div class="agni-ajax-search-all-results">
                <a href="<?php echo esc_url( cartify_ajax_search_url( $keyword, $post_types, $product_search_category ) ) ?>" class="agni-ajax-search-all-results__link"><?php echo esc_html__( 'View results', 'cartify' ); ?></a>
            </div>
            <?php


        }

        else{
            ?>
            <div class="agni-ajax-search-no-results">
                <span><?php echo esc_html__( 'No products found.', 'cartify' ); ?></span>
            </div>
            <?php
        }

        wp_reset_postdata();

        die();
    }
}

function cartify_ajax_search_get_category_parents($term, $terms_array = array()){
    if( $term->parent ){
        $parent_term = get_term( $term->parent , 'product_cat' );
        cartify_ajax_search_get_category_parents($parent_term, $terms_array);
        ?>
        <span><?php echo esc_html( $term->name ); ?></span>
        <?php
    }
    else{
        ?>
        <span><?php echo esc_html( $term->name ); ?></span>
        <?php
    }
}

function cartify_ajax_search_url($keyword, $post_types, $product_cat = 0){
    return add_query_arg( array(
        's' => $keyword,
        'product_cat' => $product_cat,
        'post_type' => $post_types
    ), esc_url( home_url('/') ) );
}

function cartify_processing_ajax_search_categories($products_search_query){

    $category_data = array();

    if ($products_search_query->have_posts()) {
        while ($products_search_query->have_posts()) { $products_search_query->the_post();
            $terms = get_the_terms( get_the_id(), 'product_cat' );
            foreach ($terms as $term) {
                if( array_key_exists($term->term_id, $category_data) ){
                    $category_data[$term->term_id] += 1;
                }
                else{
                    $category_data[$term->term_id] = 1;
                }
            }
        }

        wp_reset_postdata();
    }

    return $category_data;

    }


if (!function_exists('cartify_ajax_search_scripts')) {
    /**
     * Function for Enqueueing scripts & styles
     *
     * @return void
     */
    function cartify_ajax_search_scripts()
    {
        // Registering JS
        wp_register_script('cartify-ajax-search', AGNI_FRAMEWORK_JS_URL . '/agni-ajax-search/agni-ajax-search.js', array('jquery'), wp_get_theme()->get('Version'), true);
        wp_localize_script('cartify-ajax-search', 'cartify_ajax_search', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('agni_ajax_search_nonce'),
            'action' => 'agni_processing_ajax_search',
        ));
    }
}
