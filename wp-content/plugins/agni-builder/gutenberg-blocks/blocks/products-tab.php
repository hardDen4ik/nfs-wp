<?php 

add_filter( 'agni_products_tab_placeholder', 'dynamic_blocks_agni_products_tab_placeholder', 10, 1 );

if( !function_exists( 'dynamic_blocks_agni_products_tab' ) ){
    function dynamic_blocks_agni_products_tab( $attributes, $content = '' ){

        if( is_admin() ){
            return null;
        }

        wp_enqueue_style( 'lineicons' );
        wp_enqueue_style( 'font-awesome' );
        
        $product_thumbnail_style = '';

        if( function_exists( 'cartify_get_theme_option' ) ){
            $product_thumbnail_style = cartify_get_theme_option( 'shop_settings_general_thumbnail_choice', '1' );
        }

        if( $product_thumbnail_style == '3' ){
            wp_enqueue_style('slick');
            wp_enqueue_script('slick');
        }

        wp_enqueue_script( 'wc-add-to-cart-variation' );
        

        extract( $attributes );

        $total_products = array();

        foreach ($columns as $colDevice => $column) {
            foreach ($rows as $rowDevice => $row) {
                if( $colDevice == $rowDevice ){
                    $total_products[$rowDevice] = $column * $row;
                }
            }
        }
        
        if ( !$carousel ){
            if( !$productsSynced ){
                $attributes['count'] = $countOnGrid;
            }
            else{
                $attributes['count'] = ($count < $total_products['mobile']) ? $count : $total_products['mobile'];
            }
        }
        // $args = AgniBuilderHelper::building_products_query( $attributes );
        // $product_query = AgniBuilderHelper::building_products_query( $attributes ); //new WP_Query( $args );

        // $total_products_to_display = $count;
        
        // if( $product_query->found_posts < $count || $count == '-1' ){
        //     $total_products_to_display = $product_query->found_posts;
        // }


        $args = array();

        $allowed_attributes_variables = array( "columns", "rows", "count", "countOnGrid", "imgDisplayStyle", "productsSynced", "paginationStyle", "product_title", "product_desc", "product_category", "product_price", "product_rating", "product_add_to_cart", "product_add_to_compare", "product_quickview", "product_stock", "product_countdown", "product_qty", "carousel" );
        $not_allowed_tab_variables = array( "title", "icon", "svgIcon", "svgIconHTML" );

        $args_attributes = array_intersect_key($attributes, array_flip($allowed_attributes_variables));
        $args_tab = array_diff_key($tabs[$activeTab], array_flip($not_allowed_tab_variables));

        $args = array_merge( $args_attributes, $args_tab );

        $product_query = AgniBuilderHelper::building_products_query( $args );

        $total_products_to_display = $count;
        
        if( $product_query->found_posts < $count || $count == '-1' ){
            $total_products_to_display = $product_query->found_posts;
        }

        $aspectRatio = $dataAspectRatio = '';
        if( !isset($imgSize) || empty($imgSize) ){
            if( get_option('woocommerce_thumbnail_cropping') == 'custom' ){
                $aspectRatioWidth = get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' );
                $aspectRatioHeight = get_option( 'woocommerce_thumbnail_cropping_custom_height', '4' );
                $aspectRatio = $aspectRatioWidth .'/'. $aspectRatioHeight;
            }
        }
        else{
            $aspectRatio = $imgSize;
        }

        if( empty($aspectRatio) ){
            $aspectRatio = '1/1';
        }

        if( (isset($productsSynced) && !$productsSynced) ){
            $dataAspectRatio = 'data-aspect-ratio='.$aspectRatio;
        }

                   
        $styles = $carousel_args = '';

        $block_classes = array(
            'agni-block-products-tab',
            (!$carousel && $inlineProductsMobile && $productsSynced) ? 'has-scroll-navigation-mobile' : '',
            $customClassName
        );
        
        $products_classes = array(
            'products', 
            // 'columns-' . $columns, 
            'has-display-style-' . $display_style,
            ( $product_qty == '1' ) ? 'has-qty-' . $product_qty_choice : '',
            (isset($productsSynced) && !$productsSynced) ? 'has-grid-layout' : '',
            (!$carousel && $inlineProductsMobile && $productsSynced) ? 'has-inline-products' : '',
            'grid'
        );

        $products_header_classes = array(
            'agni-block-products-tab-header',
            'has-header-style-' . $header_style,
            'has-header-align-' . $header_align,
            !empty( $headerFilled ) ? 'has-header-background' : '',
            !empty( $headerOutlined ) ? 'has-header-border' : '',

        );

        // print_r( $attributes );

        $products_bg_classes = array(
            "agni-block-products-tab__background",
            !empty( $fullWidthBG ) ? 'has-background-full': '',
            !empty( $backgroundOverlayBackward ) ? 'send-overlay-to-back': '',
        );

        ob_start(); 

        remove_action( 'woocommerce_after_shop_loop_item_title', 'cartify_woocommerce_short_description', 9 );

        AgniBuilderDynamicBlocks::agni_product_display_options( $attributes );
        
        // print_r( $attributes );
        ?>
        <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $block_classes ) ); ?>">

            <?php if((isset($backgroundUrl) && $backgroundUrl) || (isset($backgroundColor) && $backgroundColor) || (isset($backgroundGradient) && $backgroundGradient)) { ?>
                <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $products_bg_classes ) ); ?>">
                    <?php if($backgroundUrl && $backgroundType === 'video'){ ?>
                        <video
                            class="agni-block-products-tab__background--video"
                            src="<?php echo esc_url( $backgroundUrl ); ?>"
                            poster="<?php echo isset($backgroundFallbackUrl) ? esc_url( $backgroundFallbackUrl ) : ''; ?>"
                            playsinline
                            <?php echo esc_attr( $videoAutoplay ? 'autoplay' : '' ); ?>
                            <?php echo esc_attr( $videoMuted ? 'muted' : '' ); ?>
                            <?php echo esc_attr( $videoLoop ? 'loop' : '' ); ?>
                        ></video>
                    <?php } ?>
                    <?php if( $backgroundUrl && $backgroundType === 'image' ){ ?>
                        <div class="agni-block-products-tab__background--image"></div>
                    <?php } ?>
                    <?php if( isset($backgroundGradient) && $backgroundGradient ){ ?>
                        <div class="agni-block-products-tab__background--gradient"></div>
                    <?php } ?>
                    <?php if( isset($backgroundColor) && $backgroundColor ){ ?>
                        <div class="agni-block-products-tab__background--color"></div>
                    <?php } ?>
                </div>
            <?php } ?>


            <?php // if( !empty($headingText) || !empty($buttonText) || !empty($pagination_classes) ){ ?>
                <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $products_header_classes ) ); ?>"><?php 
                    if( !empty($headingText) ){ 
                        if( !empty($headingUrl) ){ 
                              $headingText = '<a href="'.esc_url($headingUrl).'">'.wp_kses($headingText, 'title').'</a>';
                        } 
                        
                        echo wp_kses( '<h'.$headingLevel.' class="agni-block-products-tab-heading">'.$headingText.'</h'.$headingLevel.'>', 'title' );  
                    
                    } 
                    ?>
                    <ul class="agni-block-products-tab__tabs">
                        <?php 
                        foreach ($tabs as $key => $tab) {
                            extract($tab);
                            $data_args = array();
                            
                            $data_args_attributes = array_intersect_key($attributes, array_flip($allowed_attributes_variables));
                            $data_args_tab = array_diff_key($tab, array_flip($not_allowed_tab_variables));

                            $data_args = array_merge( $data_args_attributes, $data_args_tab );

                            $productsTabTabClassNames = array(
                                'agni-block-products-tab__tab',
                                ($key == $activeTab) ? 'active' : ''
                            )
                            ?>
                            <li class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $productsTabTabClassNames ) ) ?>" data-args="<?php echo esc_attr( json_encode( $data_args ) ); ?>">  
                                <?php if( $showIcon ){ ?>
                                    <span class="agni-block-products-tab__tab-icon">
                                        <?php if( !empty($icon) ) { ?>
                                            <i class="<?php echo esc_attr( $icon ) ?>"></i>
                                        <?php }
                                        if( !empty($svgIcon) ) { 
                                            if( !empty($svgIconHTML) ){
                                                ?>
                                                <span class="agni-block-products-tab-svg__file"><?php echo wp_kses( $svgIconHTML, 'svg' ); ?></span>
                                            <?php } 
                                            else {
                                                ?>
                                                <span class="agni-block-products-tab-svg__code"><?php echo cartify_get_icon_svg('common', $svgIcon ); ?></span>
                                            <?php }

                                        } ?>
                                    </span>
                                <?php } ?>
                                <span class="agni-block-products-tab__tab-title"><?php echo esc_html( $title ); ?></span>
                            </li>
                            <?php

                        }
                        ?>
                    </ul>
                    <?php
                    
                    if( !$carousel && $paginationStyle == '1' ){ 
                        AgniBuilderDynamicBlocks::agni_products_pagination( $attributes, $total_products_to_display ); 
                    } 
                     
                    if( !empty($buttonText) ){ 
                        ?><a class="agni-block-products-tab-btn" href="<?php echo esc_url( isset($buttonUrl) ? $buttonUrl : '' ); ?>" target="<?php echo esc_attr( $buttonTarget ); ?>" rel="<?php echo esc_attr( $rel ); ?>"><?php echo esc_html( $buttonText ); ?></a><?php 
                        } 
                ?></div>
            <?php // } ?>
            
                <div class="agni-block-products-tab-contents">
                <?php

                    if ( $carousel ){
                        wp_enqueue_script( 'slick' );
                        wp_enqueue_style( 'slick' );

                        $products_classes[] = 'agni-products-carousel';
                        
                        if( isset( $carouselOptions ) && $carouselOptions !== 'basic' ){
                            $carousel_args = AgniBuilderHelper::prepare_slick_options( $carouselOptions );
                        }
                        else{
                            $carousel_slides_to_show = $carousel_rows = array();

                            $carousel_args = array(
                                // 'slidesToShow' => $columns,
                                'autoplay' => $carouselAutoplay,
                                'infinite' => $carouselInfinite,
                                'arrows' => $carouselArrows,
                                'dots' => $carouselDots
                            );
                            
                            $carousel_args = wp_json_encode($carousel_args);
                        }
                        // print_r( $carousel_args );
                        
                        ?>
                        <ul class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $products_classes ) ); ?>" data-slick="<?php echo esc_attr( $carousel_args ); ?>" data-slick-slides-to-show="<?php echo esc_attr( wp_json_encode($columns) ); ?>" data-slick-slides-per-row="<?php echo esc_attr( wp_json_encode($rows) ); ?>" <?php echo $dataAspectRatio; ?>>
                        <?php
                    }
                    else{
                        ?>
                        <ul class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $products_classes ) ); ?>" <?php echo esc_attr($dataAspectRatio); ?>>
                        <?php
                    }


                    if( $product_query->have_posts() ){

                        if( !empty( $imgDisplayStyle ) ){
                            add_filter( 'agni_product_archive_thumbnail_style', function() use($imgDisplayStyle){
                                return $imgDisplayStyle;
                            } );
                        }
                        

                        $i = 0;
                        while( $product_query->have_posts() ){
                            $product_query->the_post();
                            
                            $value = '';
                            if( !empty( $widthRatio )  ){
                                foreach ($widthRatio as $key => $ratio) {
                                    // print_r($ratio);
                                    if(isset($ratio['value']) && $ratio['index'] == $i){
                                        // $value = $ratio['value'];
                                        $value = implode( '/', explode( 'x',$ratio['value'] ) );
                                    }
                                }
                            }
                            if( !empty($value) && $value !== '1/1' ){
                                add_filter( 'single_product_archive_thumbnail_size', function() use($imgMaxWidth){
                                    return !empty( $imgMaxWidth ) ? array( $imgMaxWidth, null, 0 ) : 'full';
                                    // return 'full';
                                } );
                            }
                            else if( isset( $imgSize ) && !empty($imgSize) ){
                                add_filter( 'single_product_archive_thumbnail_size', function() use($imgSize){
                                    return 'cartify_thumbnail_' . $imgSize;
                                } );
                                
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
                    else{
                        if( isset($showPlaceholder) && $showPlaceholder == '1' ){
                            echo apply_filters( 'agni_products_tab_placeholder', $count );
                        }
                    }

                    ?>
                    </ul>

                </div>
                <div class="agni-block-products-tab-nav hide">
                    <span class="agni-block-products-tab-nav-left nav-left"><i class="lni lni-chevron-left"></i></span>
                    <span class="agni-block-products-tab-nav-right nav-right"><i class="lni lni-chevron-right"></i></span>
                </div>

                <?php if( !$carousel && $paginationStyle == '2' ){ 
                    AgniBuilderDynamicBlocks::agni_products_pagination( $attributes, $total_products_to_display ); 
                } ?>

                <?php
            ?>
        </div><?php 


        add_action( 'woocommerce_after_shop_loop_item_title', 'cartify_woocommerce_short_description', 9 );
        
        // add back all actions again
        AgniBuilderDynamicBlocks::agni_product_display_options( $attributes, $reverse = true );

        return ob_get_clean();
    }
}


if( !function_exists( 'dynamic_blocks_agni_products_tab_placeholder' ) ){
    function dynamic_blocks_agni_products_tab_placeholder( $count ){

        ob_start();

        for($i = 0; $i < $count; $i++){
            ?>
            <li class="product product-type-simple">
                <div class="product-bg-on-hover"></div>
                <div class="product-thumbnail">
                    <div class="woocommerce-loop-product__thumbnail"><?php echo wp_kses( wc_placeholder_img(), 'img' ); ?></div>

                    <div class="agni-add-to-cart">
                        <a href="#" class="button product_type_simple add_to_cart_button ajax_add_to_cart" rel="nofollow"><?php echo esc_html__( 'Add to cart', 'cartify' ); ?></a>
                    </div>
                </div>
                <div class="product-details">
                    <div class="woocommerce-loop-product__category">
                        <a href="#" rel="tag"><?php echo esc_html__( 'Sample category', 'cartify' ); ?></a>		
                    </div>
                    <h2 class="woocommerce-loop-product__title"><a href="#" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><?php echo esc_html__( 'This is Sample product title', 'cartify' ); ?></a></h2>
                    <span class="price"><span class="woocommerce-Price-amount amount"><bdi>$19.99</bdi></span></span>
                </div>
                <div class="product-buttons">
                    <div class="agni-add-to-cart">
                        <a href="#" class="button product_type_simple add_to_cart_button ajax_add_to_cart" rel="nofollow"><?php echo esc_html__( 'Add to cart', 'cartify' ); ?></a>
                    </div>
                </div>	
            </li>
            <?php
        }

        return ob_get_clean();
    }
}