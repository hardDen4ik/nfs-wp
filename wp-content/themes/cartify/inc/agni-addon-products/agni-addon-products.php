<?php

require_once AGNI_TEMPLATE_DIR . '/agni-addon-products/admin-agni-addon-products.php';

function cartify_addon_products_display_single_product(){
    if( !is_product() ){
        return;
    }

        global $post;

    $products_list = get_post_meta( $post->ID, 'agni_product_data_addon_products', true );
    if( empty( $products_list ) ){
        return;
    }

    wp_enqueue_script('cartify-addon-products');
    ?>

    <div class="agni-addon-products">
        <?php echo apply_filters( 'agni_woocommerce_addon_products_title', cartify_woocommerce_addon_product_title() ) ?>
        <div class="agni-addon-products__container">
        <?php do_action( 'agni_addon_products_contents', $products_list ); ?>
        </div>

            </div>

        <?php
}

function cartify_woocommerce_addon_product_title(){
    ?>
    <h2><?php echo esc_html__( 'Frequently Bought Together', 'cartify' ) ?></h2>
    <?php
}


function cartify_addon_products_prepare_contents($products_list){
    global $post;

    $current_product_id = $post->ID;
    $variation_id = '';
    $variation_price = 0;
    $total_price = 0;

    $exclude_current_product = apply_filters( 'agni_woocommerce_addon_products_exclude_current_product', false );

    if( isset( $_POST['current_product_id'] ) ){
        $current_product_id = $_POST['current_product_id'];
    }
    if( isset( $_POST['product_ids'] ) ){
        $products_list = explode(',', $_POST['product_ids']);
    }

    if( isset($_POST['variation_id']) ){
        $variation_id = $_POST['variation_id'];
    }

    if( isset( $_POST['revised_product_ids'] ) ){
        $revised_products_list = explode(',', $_POST['revised_product_ids']);
    }


    $current_product = wc_get_product( $current_product_id );

    $products_list_current = isset($revised_products_list)?$revised_products_list:$products_list;
    if( !$exclude_current_product ){
    	array_unshift($products_list_current, $current_product_id);
	}
    $products_list_current = array_unique( $products_list_current );

    $default_products_list = $products_list;
	if( !$exclude_current_product ){
    	array_unshift($default_products_list, $current_product_id);
	}

        // print_r($products_list);
    // print_r($products_list_current);
    // echo $variation_id;

    ?>

        <ul class="agni-addon-products__contents">
        <?php 
            foreach( $products_list_current as $product_id ){
                if( $product_id != $current_product_id ){

                    $product = wc_get_product( $product_id );
                    $thumbnail_id = $product->get_image_id();
                    $size = 'woocommerce_thumbnail';
                    ?>
                    <li <?php wc_product_class( '', $product ); ?>>
                        <div class="woocommerce-loop-product__thumbnail">
                            <?php echo apply_filters( 'agni_woocommerce_addon_products_image_thumbnail_html', wp_get_attachment_image($thumbnail_id, $size), $thumbnail_id ); ?>
                        </div>
                        <h2 class="woocommerce-loop-product__title"><?php echo esc_html( $product->get_title() ); ?></h2>
                        <div class="price">
                            <?php echo wp_kses( $product->get_price_html(), array(
                                'del' => array(
                                    'aria-hidden' => array(),
                                    'class' => array(),
                                ),
                                'ins' => array(
                                    'class' => array(),
                                ),
                                'span' => array(
                                    'class' => array(),
                                ), 
                                'bdi' => array()
                            )); ?>
                        </div>
                    </li>
                    <?php

                }
            }

        ?>
    </ul>

        <div class="agni-addon-products__choices">
        <ul class="agni-addon-products__list-items">
        <?php  
        foreach($default_products_list as $product_id){

            $product = wc_get_product( $product_id );

            $checked = !in_array( $product_id, $products_list_current ) ? '' : 'checked';


            $products_item_title_classes = array(
                'agni-addon-products__item-title',
                ( $product_id == $current_product_id ) ? 'current' : '',
            );

            ?>
            <li>
                <label for="agni-addon-products-<?php echo esc_attr( $product_id ); ?>">
                    <input 
                        <?php echo esc_attr( $checked ); ?> 
                        <?php disabled( $product_id == $current_product_id, true ); ?> 
                        type="checkbox" 
                        id="agni-addon-products-<?php echo esc_attr( $product_id ); ?>" 
                        data-product-id="<?php echo esc_attr( $product_id ); ?>" 
                        data-variation-id="<?php echo isset($variation_id)?$variation_id:''; ?>" 
                        data-product-ids="<?php echo esc_attr( implode(',', $products_list_current) ); ?>" 
                        data-product-price="<?php echo esc_attr( wc_get_price_to_display( $product ) ); ?>"
                    >
                    <span class="<?php echo esc_attr( cartify_prepare_classes( $products_item_title_classes ) ); ?>">
                        <span><?php 
                            if( $product_id == $current_product_id ){
                                echo esc_html__( 'This item: ', 'cartify' );
                            } 
                        ?></span>
                        <span><?php echo esc_html( $product->get_title() ); ?></span>
                    </span>
                    <?php 
                        if( $product_id == $current_product_id && $current_product->is_type( 'variable' ) ){ 
                            if( $product->is_type( 'variable' ) ){
                                if( !empty( $variation_id ) ){
                                    array_unshift( $products_list, $variation_id );

                                    $available_variations = $product->get_available_variations();
                                    foreach( $available_variations as $variation ){
                                        if( $variation_id == $variation['variation_id']){
                                            $variation_price = $variation['display_price'];
                                            ?>
                                            <span><?php echo wp_kses( wc_price( $variation['display_price'] ), array( 'span' => array( 'class' => array() ) ) ); ?></span>
                                            <?php
                                        }
                                    }

                                }
                                else{
                                    echo esc_html__('Sorry no variation chosen.', 'cartify' );
                                }
                            }
                            else{
                                array_unshift( $products_list, $product_id );
                            }
                        }
                        else{
                            echo wp_kses( wc_price( wc_get_price_to_display( $product ) ), array(
                                'del' => array(
                                    'aria-hidden' => array(),
                                    'class' => array(),
                                ),
                                'ins' => array(
                                    'class' => array(),
                                ),
                                'span' => array(
                                    'class' => array(),
                                ), 
                                'bdi' => array()
                            ) );
                        }

                                            ?>
                </label>
            </li>
            <?php

        }
        ?>
        </ul>
    </div>

    <div class="agni-addon-products__cart">
        <?php

                    foreach( $products_list_current as $product_id ){
                $product = wc_get_product( $product_id );
                if( !$product->is_type('variable') || $product_id != $current_product_id){
                    $total_price += wc_get_price_to_display( $product );
                }
            }

            if($current_product->is_type( 'variable' )){
                if( $variation_price != 0 ){
                    $total_price += $variation_price;
                }
            }

            $products_add_to_cart = $products_list_current;
            if( $variation_id != '' ){
                foreach( $products_list_current as $key => $product_id ){
                    if( $product_id == $current_product_id ){
                        $products_add_to_cart[$key] = $variation_id;
                    }
                }
            }

        ?>
        <div class="agni-addon-products__total"><span class="agni-addon-products__total-text"><?php echo esc_html__( 'Total:', 'cartify' ); ?></span><span class="agni-addon-products__total-price"><?php echo wp_kses( wc_price( $total_price ), array(
            'del' => array(
                'aria-hidden' => array(),
                'class' => array(),
            ),
            'ins' => array(
                'class' => array(),
            ),
            'span' => array(
                'class' => array(),
            ), 
            'bdi' => array()
        ) ); 
        ?></span><span class="agni-addon-products__qty"><?php echo esc_html( count(array_unique($products_add_to_cart)) ); ?><?php echo esc_html__( ' items', 'cartify' ); ?></span></div>


                        <?php
        if( $current_product->is_type( 'variable' ) && $variation_id == '' ){
            ?>
            <span class="agni-addon-products__variation-error"><?php echo esc_html__( 'Choose the variation first!', 'cartify' ); ?></span>
            <?php
        }
        else{
            ?>
            <button class="agni-addon-products__button--add-all-to-cart" data-product-ids=<?php echo esc_attr( implode( ',', array_unique($products_add_to_cart)) ); ?>><?php echo esc_html__('Add all to cart', 'cartify' ); ?></button>
            <?php
        }
        ?>
    </div>
    <?php

    // if(!is_admin() || wp_doing_ajax()){
    //     die();
    // }
}

function cartify_addon_products_add_all_to_cart(){

    // if (!check_ajax_referer('agni_addon_products_nonce', 'security')) {
    //     return 'Invalid Nonce';
    // }

	if(!isset($_POST['products_to_cart'])){
		die();
	}

		// get woocommerce error notice
	$error = wc_get_notices( 'error' );

	if( $error ){
		// print notice
		ob_start();
		foreach( $error as $value ) {
			wc_print_notice( $value, 'error' );
		}

		$js_data =  array(
			'error' => ob_get_clean()
		);

		wc_clear_notices(); // clear other notice
		wp_send_json($js_data);
	}

		else{
        $cart_array = explode( ',', $_POST['products_to_cart'] );

                foreach($cart_array as $cart_id){

                        WC()->cart->add_to_cart(intval($cart_id), 1);
        }
		wc_clear_notices();
		WC_AJAX::get_refreshed_fragments();	
	}

	die();
}

function cartify_addon_products_scripts(){
    if( !is_product() ){
        return;
    }

    global $post;

    $products_list = get_post_meta( $post->ID, 'agni_product_data_addon_products', true );

    if( empty( $products_list ) ){
        return;
    }

    // Registering JS for Addon Products
    wp_register_script('cartify-addon-products', AGNI_FRAMEWORK_JS_URL . '/agni-addon-products/agni-addon-products.js', array('jquery', 'select2'), wp_get_theme()->get('Version'), true);
    wp_localize_script('cartify-addon-products', 'cartify_addon_products', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
        'security' => wp_create_nonce('agni_addon_products_nonce'),
        'current_product_id' => $post->ID,
        'product_ids' => implode(',', $products_list),
    ));
}

add_action( 'agni_addon_products_contents', 'cartify_addon_products_prepare_contents', 10, 1 );
add_action( 'wc_ajax_agni_addon_products_contents', 'cartify_addon_products_prepare_contents', 10, 1 );
add_action( 'wc_ajax_agni_addon_products_add_all_to_cart', 'cartify_addon_products_add_all_to_cart' );
//add_action( 'wc_ajax_nopriv_agni_addon_products_add_all_to_cart', 'cartify_addon_products_add_all_to_cart' );

add_action( 'woocommerce_after_single_product_summary', 'cartify_addon_products_display_single_product', 9 );

add_action( 'wp_enqueue_scripts', 'cartify_addon_products_scripts' );
