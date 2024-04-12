<?php

require_once AGNI_TEMPLATE_DIR . '/agni-compare/admin-agni-compare.php';

function cartify_compare_get_compare_button_url( $added = 0 ){
    if( $added === 1 ){
        $url = add_query_arg( 'remove_item', get_the_id() );
    }
    else{
        $url = add_query_arg( 'add_to_compare', get_the_id() );
    }

    return apply_filters( 'agni_woocommerce_product_compare_button_url', $url );
}

function cartify_compare_add_to_compare_button(){
    global $product;


    $product_compare = cartify_get_theme_option( 'shop_settings_general_product_compare', '1' );
    if( $product_compare != '1' ){
        if( is_shop() || is_product_category() || is_product_tag() || is_product() || is_tax( 'product_brand' ) ){
            return;
        }
    }


    $compare_page_choice = cartify_get_theme_option('shop_settings_compare_page_choice', '');

    if( get_the_id() == $compare_page_choice ){
        return;
    }


    wp_enqueue_script('cartify-compare');

    $products_to_compare = cartify_compare_get_cookie_selected_ids();

    ?>
    <div class="agni-add-to-compare"><?php 

                $compare_page_disable_remove_after_added = '1';

        $added_to_compare_classes = array(
            'added-to-compare',
            ($compare_page_disable_remove_after_added == '1') ? 'disabled' : '',
            ( !empty($products_to_compare) && in_array( $product->get_id(), $products_to_compare ) ) ? '' : 'hide',
        );
        $add_to_compare_classes = array(
            'add-to-compare',
            ( !empty($products_to_compare) && in_array( $product->get_id(), $products_to_compare ) ) ? 'hide' : '',
        )

        ?><div class="agni-add-to-compare__button">
            <a href="<?php echo esc_url( get_permalink( $compare_page_choice ) ); ?>" class="<?php echo esc_attr( cartify_prepare_classes( $added_to_compare_classes ) ); ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo apply_filters( 'agni_woocommerce_compare_check_icon', cartify_get_icon_svg('common', 'check') ); ?><span><?php echo esc_html__( 'Go to compare', 'cartify' ); ?></span></a><a href="<?php echo esc_url( cartify_compare_get_compare_button_url() ); ?>" class="<?php echo esc_attr( cartify_prepare_classes( $add_to_compare_classes ) ); ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo apply_filters( 'agni_woocommerce_compare_icon', cartify_get_icon_svg('common', 'compare') ); ?><span><?php echo esc_html__( 'Add to compare', 'cartify' ); ?></span></a>
        </div><?php
    ?></div><?php
}


function cartify_compare_get_cookie_selected_ids(){
    if( !isset($_COOKIE['agni_woocommerce_compare']) ){
        return;
    }

    $products_to_compare = ! empty( $_COOKIE['agni_woocommerce_compare'] ) ? (array) explode( '|', sanitize_text_field( $_COOKIE['agni_woocommerce_compare'] ) ) : array();

    return $products_to_compare;
}

function cartify_compare_set_cookie(){

    if( !isset($_POST['product_id']) ){
        return;
    }
    //echo 'product found to add cookie';
    $product_id = sanitize_text_field( $_POST['product_id'] );
    $products_to_compare = ! empty( $_COOKIE['agni_woocommerce_compare'] ) ? (array) explode( '|', sanitize_text_field( $_COOKIE['agni_woocommerce_compare'] ) ) : array();

    if ( ! in_array( $product_id, $products_to_compare ) ) {
        $products_to_compare[] = $product_id;

    }

    wc_setcookie('agni_woocommerce_compare', implode( '|', $products_to_compare ), time() + 60 * 60 * 24 * 30);
    return 'product found to add cookie';

    die();
}

function cartify_compare_update_cookie(){
    if( !isset($_COOKIE['agni_woocommerce_compare']) || empty( $_COOKIE['agni_woocommerce_compare'] ) ){
        return;
    }

    $product_id = sanitize_text_field( $_POST['product_id'] );
    $products_to_compare = cartify_compare_get_cookie_selected_ids();

    //$updated_products_to_compare = str_replace( $product_id, '', $products_to_compare );
    //print_r( $products_to_compare );
    if (($product_id_key = array_search($product_id, $products_to_compare)) !== false) {
        unset($products_to_compare[$product_id_key]);
    }
    //print_r( $products_to_compare );
    // print_r( $updated_products_to_compare );
    //return $updated_products_to_compare;
    //wc_setcookie('agni_woocommerce_compare', '', time() + 60 * 60 * 24 * 30);

    wc_setcookie('agni_woocommerce_compare', implode( '|', $products_to_compare ), time() + 60 * 60 * 24 * 30);

    die();
}

function cartify_compare_clear_cookie(){
    if( !isset($_COOKIE['agni_woocommerce_compare']) ){
        return;
    }

    wc_setcookie('agni_woocommerce_compare', '', time() + 60 * 60 * 24 * 30);

    die();
}


function cartify_compare_display_products(){
    ?>
    <?php

            // Get recently viewed product cookies data
        $products_to_compare = ! empty( $_COOKIE['agni_woocommerce_compare'] ) ? (array) explode( '|', sanitize_text_field( $_COOKIE['agni_woocommerce_compare'] ) ) : array();
        $products_to_compare = array_reverse( array_filter( array_map( 'absint', $products_to_compare ) ) );

        ?>

        <div id="agni-compare" class="agni-compare">
            <div class="agni-compare-header">
                <h2 class="agni-compare-title"><?php echo sprintf( esc_html__( 'Compare Products (%s)', 'cartify' ), esc_html( count( $products_to_compare ) ) ); ?></h2>
                <div class="agni-compare-controls">
                    <button class="agni-compare-clear"><?php echo esc_html__( 'Clear all', 'cartify' ); ?></button>
                </div>
            </div>
            <?php 
            if ( !empty( $products_to_compare ) ) {
                cartify_compare_prepare_compare_table($products_to_compare);
            } 
            else{ ?>
                <div class="agni-compare-empty">
                    <p><?php echo esc_html__( 'It looks like nothing to compare at this page. Check the link below.', 'cartify' ); ?></p>
                    <a class="btn btn-alt" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php echo esc_html__( 'Add Products to Compare', 'cartify' ); ?></a>
                </div>
                <?php
            } ?>
        </div>

    <?php
}

function cartify_woocommerce_single_product_compare_title(){
    ?>
    <h2 class="agni-compare-similar-title"><?php echo esc_html__( 'Similar products to compare', 'cartify' ) ?></h2>
    <?php
}

function cartify_compare_display_single_product(){
    global $product;

    $products_list = (array)get_post_meta( $product->get_id(), 'agni_product_data_compare', true );
    if( empty( implode('', $products_list) ) ){
        return;
    }

    array_unshift($products_list, $product->get_id());
    ?>
    <div id="agni-compare" class="agni-compare">
        <?php
        echo apply_filters('agni_woocommerce_single_product_compare_title', cartify_woocommerce_single_product_compare_title());
        // send all product ids through below function
        cartify_compare_prepare_compare_table($products_list, 1);

                ?>
    </div>
    <?php
}


function cartify_compare_prepare_compare_table_new( $product_ids = array(), $single = 0 ){
    if( empty($product_ids) ){
        return;
    }

    foreach( $product_ids as $key => $product_id ){
        $product = wc_get_product( $product_id );

        if( empty( $product ) ){
            unset( $product_ids[$key] );
        }
    }

    global $product;
    $base_product = $product;
    ?>
    <div class="agni-compare-container">
        <div class="agni-compare-table">
            <?php 
            /* if( !$single ){
                ?>
                <thead>
                    <th></th>
                    <?php 
                    foreach( $product_ids as $product_id ){
                        ?>
                        <td><span class="agni-compare-product-remove" data-remove-id=<?php echo esc_attr($product_id); ?>><i class="lni lni-close"></i><?php echo esc_html_x( 'Remove', 'Similar Compare remove', 'cartify' ); ?></span></td>
                        <?php
                    }
                    ?>
                </thead>
                <?php
            } */



            $specification_contents_array = array();
            $spec_keys = array();
            foreach( $product_ids as $product_id ){
                $specification_contents_array[] = get_post_meta( $product_id, 'agni_product_data_tab_specification_table_data', true );
                if(!empty($specification_contents_array)){
                    foreach( $specification_contents_array as $specification_contents ){
                        if( !empty($specification_contents) ){
                            foreach ($specification_contents as $specification_data) {
                                $spec_keys[] = $specification_data['key'];
                            }
                        }
                    }
                }
            }
            $spec_keys_unique = array_unique($spec_keys);


            ?>
            <div>
                <div class="agni-compare-table-labels">
                    <div></div>
                    <div><?php echo esc_html( 'Rating', 'cartify' ) ?></div>
                    <?php 
                    $attribute_taxonomies = wc_get_attribute_taxonomies();
                    if ( ! empty( $attribute_taxonomies ) ) {
                        foreach ( $attribute_taxonomies as $tax ) {
                            if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {

                                $attributes_name = array();
                                foreach ($product_ids as $key => $product_id) {
                                    $product = wc_get_product( $product_id );

                                    $attributes = $product->get_attributes();
                                    foreach ($attributes as $key => $attr) {
                                        $attributes_name[] = $attr->get_name(); 
                                    }
                                }
                                if( in_array( 'pa_' . $tax->attribute_name, $attributes_name ) ){
                                    ?>
                                    <div><?php echo esc_html( $tax->attribute_label ); ?></div>
                                <?php } 
                            } 
                        } 
                    } ?>
                    <div><?php echo esc_html( 'Description', 'cartify' ) ?></div>
                    <div><?php echo esc_html( 'SKU', 'cartify' ) ?></div>
                    <?php if( !empty( $spec_keys_unique ) ){ ?>
                        <div><?php echo esc_html( 'Specifications', 'cartify' ) ?></div>
                    <?php } ?>
                </div>
                <div>
                    <?php  
                    foreach( $product_ids as $product_id ){
                        $product = wc_get_product( $product_id );

                        // echo $product->get_title();
                        $compare_product_classes = array(
                            'product',
                        );

                        if($single){ 
                            $compare_product_classes[] = ($base_product->get_id() == $product_id) ? 'agni-compare__column--base' : 'agni-compare__column--similar';
                        } ?>
                        <div>
                        <div class="<?php echo esc_attr( cartify_prepare_classes( $compare_product_classes ) ) ?>">  
                        <?php if( !empty( $product ) ){ ?>  
                            <div class="agni-compare__thumbnail"><?php 
                                $product_image_id = wc_get_product( $product_id )->get_image_id();
                                $size = 'woocommerce_thumbnail';
                                echo wp_get_attachment_image( $product_image_id, $size ) 
                            ?></div>
                            <h2 class="agni-compare__title">
                                <?php if($single) {
                                    if($base_product->get_id() == $product_id){ ?>
                                        <span><?php echo esc_html__('You\'re here: ', 'cartify' ); ?></span><span><?php echo esc_html( wc_get_product( $product_id )->get_name() ); ?></span>
                                    <?php } 
                                    else { ?>
                                        <a href="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>"><?php echo esc_html( wc_get_product( $product_id )->get_name() ); ?></a>
                                    <?php }

                                                                        }
                                else{ ?>
                                    <a href="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>"><?php echo esc_html( wc_get_product( $product_id )->get_name() ); ?></a>
                                <?php } ?>

                                                            </h2>
                            <span class="price"><?php echo wp_kses( wc_get_product( $product_id )->get_price_html(), array(
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
                            ) ); ?></span>
                            <?php 
                            echo wc_get_stock_html( $product ); ?>
                            <div class="product-buttons">
                                <?php do_action( 'agni_woocommerce_after_shop_loop_item'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php echo wc_get_rating_html( wc_get_product( $product_id )->get_average_rating() ) ?>
                        <?php 
                            $attribute_taxonomies = wc_get_attribute_taxonomies();
                            if ( ! empty( $attribute_taxonomies ) ) {
                                foreach ( $attribute_taxonomies as $tax ) {
                                    if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {

                                        $attributes_name = array();
                                        foreach ($product_ids as $key => $product_id) {
                                            $product = wc_get_product( $product_id );

                                            $attributes = $product->get_attributes();
                                            foreach ($attributes as $key => $attr) {
                                                $attributes_name[] = $attr->get_name(); 
                                            }
                                        }
                                        if( in_array( 'pa_' . $tax->attribute_name, $attributes_name ) ){
                                            ?>
                                                <?php 


                                                    ?>
                                                    <div><?php
                                                    $product_terms = get_the_terms( $product_id, 'pa_'.$tax->attribute_name );
                                                    if( $product_terms ){
                                                        echo '<ul>';
                                                        foreach( $product_terms as $product_term ){
                                                            echo '<li>' .$product_term->name. '</li>';
                                                        }
                                                        echo '</ul>';
                                                    }
                                                    ?></div>
                                                    <?php
                                                ?>
                                            <?php 
                                        }
                                    }
                                }
                            }
                        ?>
                        <?php echo apply_filters( 'woocommerce_short_description', get_the_excerpt( $product_id ) ); ?>
                        <?php echo esc_html( wc_get_product( $product_id )->get_sku() ) ?>
                        </div> 
                    <?php

                                        }
                    ?>
                </div>
            </div>
            <div>
                <div><?php echo esc_html( 'Specifications', 'cartify' ) ?></div>
                <?php 
                    $specification_contents_array = array();
                    $spec_keys = array();
                    foreach( $product_ids as $product_id ){
                        $specification_contents_array[] = get_post_meta( $product_id, 'agni_product_data_tab_specification_table_data', true );
                        if(!empty($specification_contents_array)){
                            foreach( $specification_contents_array as $specification_contents ){
                                if( !empty($specification_contents) ){
                                    foreach ($specification_contents as $specification_data) {
                                        $spec_keys[] = $specification_data['key'];
                                    }
                                }
                            }
                        }
                    }
                    $spec_keys_unique = array_unique($spec_keys);

                    if( !empty( $spec_keys_unique ) ){

                        ?>
                        <div class="labels">
                            <?php 

                                                foreach( $spec_keys_unique as $spec_title ){
                            ?>
                                <div><?php echo esc_html($spec_title); ?></div>
                            <?php } ?>

                        </div>
                        <div>
                        <?php
                        foreach( $product_ids as $product_id ){
                            $product_specs = get_post_meta( $product_id, 'agni_product_data_tab_specification_table_data', true );
                            ?>

                            <div class="">
                            <?php

                                                foreach( $spec_keys_unique as $spec_title ){
                            ?>
                                <?php 

                                    /* if($single){ ?>
                                        <td class="<?php 
                                            if($base_product->get_id() == $product_id){ 
                                                echo 'agni-compare__column--base';
                                            }
                                            else{
                                                echo 'agni-compare__column--similar'; 
                                            } ?>">
                                    <?php }
                                    else{ ?>
                                        <td>
                                    <?php } */
                                    ?>
                                    <div><?php 
                                    if( !empty($product_specs) ){
                                        foreach( $product_specs as $spec ){
                                            if($spec['key'] == $spec_title){
                                                echo esc_html($spec['value']);
                                            }
                                        }
                                    }
                                    ?></div><?php
                                }

                                                                ?>
                            </div>
                        <?php
                        } ?>
                        </div>
                    <?php
                    }
                ?>
                </div>
            </div>
        </div>
    </div>

    <?php
}

function cartify_compare_prepare_compare_table( $product_ids = array(), $single = 0 ){
    if( empty($product_ids) ){
        return;
    }

    foreach( $product_ids as $key => $product_id ){
        $product = wc_get_product( $product_id );

        if( empty( $product ) ){
            unset( $product_ids[$key] );
        }
    }

    global $product;
    $base_product = $product;
    ?>
    <div class="agni-compare-nav hide">
        <span class='agni-compare-nav-left'><i class="lni lni-chevron-left"></i></span>
        <span class='agni-compare-nav-right'><i class="lni lni-chevron-right"></i></span>
    </div>
    <div class="agni-compare-container">
        <table class="agni-compare__table">
            <?php 
            if( !$single ){
                ?>
                <thead>
                    <th></th>
                    <?php 
                    foreach( $product_ids as $product_id ){
                        ?>
                        <td><span class="agni-compare-product-remove" data-remove-id=<?php echo esc_attr($product_id); ?>><i class="lni lni-close"></i><?php echo esc_html_x( 'Remove', 'Compare remove', 'cartify' ); ?></span></td>
                        <?php
                    }
                    ?>
                </thead>
                <?php
            }
            ?>
            <tbody>
                <tr>
                    <th></th>
                    <?php  
                    foreach( $product_ids as $product_id ){
                        $product = wc_get_product( $product_id );

                        // echo $product->get_title();
                        $compare_product_classes = array(
                            'product',
                        );

                        if($single){ 
                            $compare_product_classes[] = ($base_product->get_id() == $product_id) ? 'agni-compare__column--base' : 'agni-compare__column--similar';
                        } ?>

                                                <td class="<?php echo esc_attr( cartify_prepare_classes( $compare_product_classes ) ) ?>">  
                        <?php if( !empty( $product ) ){ ?>                        
                            <div class="agni-compare__thumbnail"><?php 
                                $product_image_id = wc_get_product( $product_id )->get_image_id();
                                $size = 'woocommerce_thumbnail';
                                echo wp_get_attachment_image( $product_image_id, $size ) 
                            ?></div>
                            <h2 class="agni-compare__title">
                                <?php if($single) {
                                    if($base_product->get_id() == $product_id){ ?>
                                        <span><?php echo esc_html__('You\'re here: ', 'cartify' ); ?></span><span><?php echo esc_html( wc_get_product( $product_id )->get_name() ); ?></span>
                                    <?php } 
                                    else { ?>
                                        <a href="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>"><?php echo esc_html( wc_get_product( $product_id )->get_name() ); ?></a>
                                    <?php }

                                                                        }
                                else{ ?>
                                    <a href="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>"><?php echo esc_html( wc_get_product( $product_id )->get_name() ); ?></a>
                                <?php } ?>

                                                            </h2>
                            <span class="price"><?php echo wp_kses( wc_get_product( $product_id )->get_price_html(), array(
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
                            )); ?></span>
                            <?php 
                            echo wc_get_stock_html( $product ); ?>
                            <div class="product-buttons">
                                <?php do_action( 'agni_woocommerce_after_shop_loop_item'); ?>
                            </div>
                            <?php } ?>
                        </td>
                        <?php

                                            }
                    ?>
                </tr>
                <tr>
                    <th><?php echo esc_html( 'Rating', 'cartify' ) ?></th>
                    <?php  
                        foreach( $product_ids as $product_id ){

                            if($single){ ?>
                                <td class="<?php 
                                    if($base_product->get_id() == $product_id){ 
                                        echo 'agni-compare__column--base';
                                    }
                                    else{
                                        echo 'agni-compare__column--similar'; 
                                    } ?>">
                            <?php }
                            else{ ?>
                                <td>
                            <?php }
                            ?>
                            <?php echo wc_get_rating_html( wc_get_product( $product_id )->get_average_rating() ) ?></td>
                            <?php
                        }
                    ?>
                </tr>
                <?php 
                    $attribute_taxonomies = wc_get_attribute_taxonomies();
                    if ( ! empty( $attribute_taxonomies ) ) {
                        foreach ( $attribute_taxonomies as $tax ) {
                            if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {

                                $attributes_name = array();
                                foreach ($product_ids as $key => $product_id) {
                                    $product = wc_get_product( $product_id );

                                    $attributes = $product->get_attributes();
                                    foreach ($attributes as $key => $attr) {
                                        $attributes_name[] = $attr->get_name(); 
                                    }
                                }
                                if( in_array( 'pa_' . $tax->attribute_name, $attributes_name ) ){
                                    ?>
                                    <tr>
                                        <th><?php echo esc_html( $tax->attribute_label ); ?></th>
                                        <?php 
                                        foreach( $product_ids as $product_id ){

                                            if($single){ ?>
                                                <td class="<?php 
                                                    if($base_product->get_id() == $product_id){ 
                                                        echo 'agni-compare__column--base';
                                                    }
                                                    else{
                                                        echo 'agni-compare__column--similar'; 
                                                    } ?>">
                                            <?php }
                                            else{ ?>
                                                <td>
                                            <?php }

                                            ?>
                                            <?php
                                            $product_terms = get_the_terms( $product_id, 'pa_'.$tax->attribute_name );
                                            if( $product_terms ){
                                                echo '<ul>';
                                                foreach( $product_terms as $product_term ){
                                                    echo '<li>' .$product_term->name. '</li>';
                                                }
                                                echo '</ul>';
                                            }
                                            ?></td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php 
                                }
                            }
                        }
                    }
                ?>
                <tr class="agni-compare__description">
                    <th><?php echo esc_html( 'Description', 'cartify' ) ?></th>
                    <?php  
                        foreach( $product_ids as $product_id ){
                            if($single){ ?>
                                <td class="<?php 
                                    if($base_product->get_id() == $product_id){ 
                                        echo 'agni-compare__column--base';
                                    }
                                    else{
                                        echo 'agni-compare__column--similar'; 
                                    } ?>">
                            <?php }
                            else{ ?>
                                <td>
                            <?php }

                            ?>
                            <?php echo apply_filters( 'woocommerce_short_description', get_the_excerpt( $product_id ) ); ?></td>
                            <?php
                        }
                    ?>
                </tr>
                <tr class="agni-compare__row--last">
                    <th><?php echo esc_html( 'SKU', 'cartify' ) ?></th>
                    <?php  
                        foreach( $product_ids as $product_id ){

                            if($single){ ?>
                                <td class="<?php 
                                    if($base_product->get_id() == $product_id){ 
                                        echo 'agni-compare__column--base';
                                    }
                                    else{
                                        echo 'agni-compare__column--similar'; 
                                    } ?>">
                            <?php }
                            else{ ?>
                                <td>
                            <?php }
                            ?>
                            <?php echo esc_html( wc_get_product( $product_id )->get_sku() ) ?></td>
                            <?php
                        }
                    ?>
                </tr>
                <?php 
                    $specification_contents_array = array();
                    $spec_keys = array();
                    foreach( $product_ids as $product_id ){
                        $specification_contents_array[] = get_post_meta( $product_id, 'agni_product_data_tab_specification_table_data', true );
                        if(!empty($specification_contents_array)){
                            foreach( $specification_contents_array as $specification_contents ){
                                if( !empty($specification_contents) ){
                                    foreach ($specification_contents as $specification_data) {
                                        $spec_keys[] = $specification_data['key'];
                                    }
                                }
                            }
                        }
                    }
                    $spec_keys_unique = array_unique($spec_keys);

                    if( !empty( $spec_keys_unique ) ){

                        ?>
                        <tr class="agni-compare__row--title">
                            <th><?php echo esc_html( 'Specifications', 'cartify' ) ?></th>
                        </tr>
                        <?php

                                            foreach( $spec_keys_unique as $spec_title ){
                            ?>
                            <tr>
                                <th><?php echo esc_html($spec_title); ?></th>
                                <?php 
                                foreach( $product_ids as $product_id ){
                                    $product_specs = get_post_meta( $product_id, 'agni_product_data_tab_specification_table_data', true );

                                    if($single){ ?>
                                        <td class="<?php 
                                            if($base_product->get_id() == $product_id){ 
                                                echo 'agni-compare__column--base';
                                            }
                                            else{
                                                echo 'agni-compare__column--similar'; 
                                            } ?>">
                                    <?php }
                                    else{ ?>
                                        <td>
                                    <?php }
                                    ?>
                                    <?php 
                                    if( !empty($product_specs) ){
                                        foreach( $product_specs as $spec ){
                                            if($spec['key'] == $spec_title){
                                                echo esc_html($spec['value']);
                                            }
                                        }
                                    }
                                    ?></td><?php
                                }

                                                                ?>
                            </tr>
                            <?php
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>

    <?php
}


function cartify_compare_scripts(){

    // Registering JS for compare
    wp_register_script('cartify-compare', AGNI_FRAMEWORK_JS_URL . '/agni-compare/agni-compare.js', array('jquery'), wp_get_theme()->get('Version'), true);
    wp_localize_script('cartify-compare', 'cartify_compare', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
        'add_to_compare_text' => 'Compare',
        'remove_from_compare_text' => 'Remove Compare',

        // 'security' => wp_create_nonce('agni_ajax_search_nonce'),
        // 'action' => 'agni_processing_ajax_search',
    ));
}

add_action( 'agni_woocommerce_after_shop_loop_item', 'cartify_compare_add_to_compare_button', 20 );
add_action( 'woocommerce_single_product_summary', 'cartify_compare_add_to_compare_button', 38 );
add_action( 'agni_woocommerce_compare_page', 'cartify_compare_display_products' );
add_action( 'woocommerce_after_single_product_summary', 'cartify_compare_display_single_product', 20 );

add_action( 'wc_ajax_agni_compare_set_cookie', 'cartify_compare_set_cookie' );
add_action( 'wc_ajax_agni_compare_clear_cookie', 'cartify_compare_clear_cookie' );
add_action( 'wc_ajax_agni_compare_update_cookie', 'cartify_compare_update_cookie' );

add_action( 'wp_enqueue_scripts', 'cartify_compare_scripts' );