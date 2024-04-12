<?php  

add_filter( 'agni_products_categories_tab_placeholder', 'dynamic_blocks_agni_products_categories_tab_placeholder', 10, 1 );


if( !function_exists( 'dynamic_blocks_agni_products_categories_tab' ) ){
    function dynamic_blocks_agni_products_categories_tab( $attributes, $content = '' ){

        if( is_admin() ){
            return null;
        }

        wp_enqueue_style( 'lineicons' );
        wp_enqueue_style( 'font-awesome' );

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
            if( !$categoriesSynced ){
                $attributes['count'] = $countOnGrid;
            }
            else{
                $attributes['count'] = $total_products['mobile'];
            }
        }


        $args = array();

        $allowed_attributes_variables = array( "columns", "rows", "count", "countOnGrid", "categoriesSynced", "paginationStyle", "hide_empty", "category_thumbnail", "category_title", "category_count", "category_button", "category_button_text", "category_children", "imgSize", "carousel" );
        $not_allowed_tab_variables = array( "title" );

        $args_attributes = array_intersect_key($attributes, array_flip($allowed_attributes_variables));
        $args_tab = array_diff_key($tabs[$activeTab], array_flip($not_allowed_tab_variables));

        $args = array_merge( $args_attributes, $args_tab );


        $per_page = $carousel_class = $carousel_args = '';
    
        $product_categories_count_query = AgniBuilderHelper::building_products_categories_query( wp_parse_args( $args, array( 'fields' => 'count' ) ) );
        $product_categories_count = $product_categories_count_query->terms;

        $product_categories_query = AgniBuilderHelper::building_products_categories_query( $args );
        $product_categories = $product_categories_query->terms; 

        $total_products_to_display = $count;

        if( !empty($product_categories_count) && $product_categories_count < $count ){
            $total_products_to_display = $product_categories_count;
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

        if( (isset($categoriesSynced) && !$categoriesSynced) ){
            $dataAspectRatio = 'data-aspect-ratio='.$aspectRatio;
        }

        $block_classes = array(
            'agni-block-products-categories-tab',
            (!$carousel && $inlineCategoriesMobile && $categoriesSynced) ? 'has-scroll-navigation-mobile' : '',
            $customClassName
        );

        $categories_classes = array(
            'products', 
            // 'columns-' . $columns, 
            'has-display-style-' . $display_style,
            (isset($categoriesSynced) && !$categoriesSynced) ? 'has-grid-layout' : '',
            (!$carousel && $inlineCategoriesMobile && $categoriesSynced) ? 'has-inline-categories' : '',
            'grid'
        );

        $categories_header_classes = array(
            'agni-block-products-categories-tab-header',
            'has-header-style-' . $header_style,
            'has-header-align-' . $header_align,
            !empty( $headerFilled ) ? 'has-header-background' : '',
            !empty( $headerOutlined ) ? 'has-header-border' : '',

        );


        $categories_bg_classes = array(
            "agni-block-products-categories-tab__background",
            !empty( $fullWidthBG ) ? 'has-background-full': '',
            !empty( $backgroundOverlayBackward ) ? 'send-overlay-to-back': '',
        );

        $category_desc = '';

        if( !$category_thumbnail ){
            remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
        }
        if( $category_icon ){
            add_action( 'woocommerce_before_subcategory_title', 'cartify_template_loop_product_category_tab_icon', 10 );

            // if( $category_button_text ){
            //     add_filter( 'cartify_template_loop_product_category_tab_icon_content', function($category) use( $category_button_text ){
            //         return $category_button_text;
            //     });
            // }
        }

        if( !$category_title ){
            remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title' );
        }

        if( $category_desc ){
            add_action( 'woocommerce_after_subcategory_title', 'cartify_template_loop_product_category_description' );
        }


        if( $category_button ){
            add_action( 'woocommerce_after_subcategory_title', 'cartify_template_loop_product_category_tab_button', 20 );
            
            if( $category_button_text ){
                add_filter( 'cartify_template_loop_product_category_tab_button_text', function($category) use( $category_button_text ){
                    return $category_button_text;
                });
            }
        }


        // add_filter( 'subcategory_archive_thumbnail_size', function($size) use($imgSize){
        //     return 'cartify_thumbnail_' . $imgSize;
        // } );

        ob_start(); ?>

        <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $block_classes ) );?>">

        <?php if((isset($backgroundUrl) && $backgroundUrl) || (isset($backgroundColor) && $backgroundColor) || (isset($backgroundGradient) && $backgroundGradient)) { ?>
                <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $categories_bg_classes ) ); ?>">
                    <?php if(isset($backgroundUrl) && $backgroundType === 'video'){ ?>
                        <video
                            class="agni-block-products-categories-tab__background--video"
                            src=<?php echo esc_url( $backgroundUrl ); ?>
                            poster=<?php echo isset($backgroundFallbackUrl) ? esc_url( $backgroundFallbackUrl ) : ''; ?>
                            playsinline
                            <?php echo esc_attr( $videoAutoplay ? 'autoplay' : '' ); ?>
                            <?php echo esc_attr( $videoMuted ? 'muted' : '' ); ?>
                            <?php echo esc_attr( $videoLoop ? 'loop' : '' ); ?>
                        ></video>
                    <?php } ?>
                    <?php if( isset($backgroundUrl) && $backgroundType === 'image' ){ ?>
                        <div class="agni-block-products-categories-tab__background--image"></div>
                    <?php } ?>
                    <?php if( isset($backgroundGradient) && $backgroundGradient ){ ?>
                        <div class="agni-block-products-categories-tab__background--gradient"></div>
                    <?php } ?>
                    <?php if( isset($backgroundColor) && $backgroundColor ){ ?>
                        <div class="agni-block-products-categories-tab__background--color"></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php // if( !empty($headingText) || !empty($buttonText) || ){ ?>
                <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $categories_header_classes ) ); ?>"><?php
                    if( !empty($headingText) ){ ?>
                        <?php echo wp_kses( '<h'.$headingLevel.' class="agni-block-products-categories-tab-heading">'.$headingText.'</h'.$headingLevel.'>', 'title' ); ?>
                    <?php } ?>

                    <ul class="agni-block-products-categories-tab__tabs">
                        <?php 
                        foreach ($tabs as $key => $tab) {
                            extract($tab);
                            $data_args = array();
                            
                            $data_args_attributes = array_intersect_key($attributes, array_flip($allowed_attributes_variables));
                            $data_args_tab = array_diff_key($tab, array_flip($not_allowed_tab_variables));

                            $data_args = array_merge( $data_args_attributes, $data_args_tab );

                            $productsTabTabClassNames = array(
                                'agni-block-products-categories-tab__tab',
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
                        AgniBuilderDynamicBlocks::agni_products_categories_pagination( $attributes, $total_products_to_display ); 
                    } 
                    
                    if( !empty($buttonText) ){ 
                        ?><a class="agni-block-products-btn" href="<?php echo esc_url( isset($buttonUrl) ? $buttonUrl : '#' ); ?>" target="<?php echo esc_attr( $buttonTarget ); ?>" rel="<?php echo esc_attr( $rel ); ?>"><?php echo esc_html( $buttonText ); ?></a><?php 
                    } 
                ?></div>
            <?php // } ?>
            <?php
                ?>
                <div class="agni-block-products-categories-tab-contents">
                <?php
                if ( $carousel ){
                    wp_enqueue_script( 'slick' );
                    wp_enqueue_style( 'slick' );

                    $categories_classes[] = 'agni-products-carousel';
                    
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
                    <ul class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $categories_classes ) ); ?>" data-slick="<?php echo esc_attr( $carousel_args ); ?>" data-slick-slides-to-show="<?php echo esc_attr( wp_json_encode($columns) ); ?>" data-slick-slides-per-row="<?php echo esc_attr( wp_json_encode($rows) ); ?>" <?php echo $dataAspectRatio; ?>>
                    <?php
                }
                else{
                    ?>
                    <ul class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $categories_classes ) ); ?>" <?php echo $dataAspectRatio; ?>>
                    <?php 
                }

                if( !empty( $product_categories ) ){
                    // print_r( $widthRatio );
                    $i = 0;
                    foreach ( $product_categories as $category ) { 
                        $value = '';
                        if( !empty( $widthRatio )  ){
                            foreach ($widthRatio as $key => $ratio) {
                                // print_r($ratio);
                                if(isset($ratio['value']) && $ratio['index'] == $i){
                                    // print_r( implode( 'x', explode( 'x',$ratio['value'] ) ) );
                                    $value = implode( '/', explode( 'x',$ratio['value'] ) );
                                }
                            }
                        }

                        // echo "value:" . $value;
                        // echo $value !== '1/1';
                        if( !empty($value) && $value !== '1/1' ){
                            add_filter( 'subcategory_archive_thumbnail_size', function() use($imgMaxWidth){
                                return !empty( $imgMaxWidth ) ? array( $imgMaxWidth, null, 0 ) : 'full';
                                // return 'full';
                            } );
                        }
                        else if( isset( $imgSize ) && !empty($imgSize) ){
                            add_filter( 'subcategory_archive_thumbnail_size', function() use($imgSize){
                                return 'cartify_thumbnail_' . $imgSize;
                            } );
                            
                        }

                        $sub_categories = get_categories( array( 
                            'taxonomy' => 'product_cat',
                            'parent' => $category->term_id,
                            'hierarchical' => 1,
                            'show_option_none' => '',
                            'hide_empty' => 0,
                        ) );
                        
                        if( !empty($sub_categories) && $category_children ){
                            add_action( 'woocommerce_after_subcategory_title', 'cartify_template_loop_product_category_tab_children', 15 );

                            add_filter( 'cartify_template_loop_product_category_tab_sub_categories', function($category) use($sub_categories){
                                
                                ob_start();

                                ?>
                                <ul>
                                <?php 
                                foreach ($sub_categories as $key => $sub_cat) {
                                    $sub_cat_link = get_term_link( $sub_cat->slug, $sub_cat->taxonomy );
                                    $sub_cat_name = $sub_cat->name;

                                    ?>
                                    <li><a href="<?php echo esc_url( $sub_cat_link ); ?>"><?php echo wp_kses( $sub_cat_name, 'title' ); ?></a></li>
                                    <?php        
                                }
                                ?>
                                </ul>
                                <?php

                                return ob_get_clean();
                                
                            } );
                        }

                        wc_get_template( 'content-product-cat.php', array(
                            'category' => $category
                        ) );

                        if( isset( $imgSize ) && !empty($imgSize) ){
                            add_filter( 'subcategory_archive_thumbnail_size', function(){
                                return 'woocommerce_thumbnail';
                            } );
                        }

                    
                        if( !empty($sub_categories) && $category_children ){
                            // add_filter( 'cartify_template_loop_product_category_tab_sub_categories', function($category){ return ''; }, 15 );
                            remove_action( 'woocommerce_after_subcategory', 'cartify_template_loop_product_category_tab_children', 15 );
                        }
                        
                        $i++;
                    } ?>
                    </ul>

                <?php
                
                }
                else {
                    if( isset($showPlaceholder) && $showPlaceholder == '1' ){
                        echo apply_filters( 'agni_products_categories_tab_placeholder', $count );
                    }
                }
                ?>
                </div>
                <div class="agni-block-products-categories-tab-nav hide">
                    <span class="agni-block-products-categories-tab-nav-left nav-left"><i class="lni lni-chevron-left"></i></span>
                    <span class="agni-block-products-categories-tab-nav-right nav-right"><i class="lni lni-chevron-right"></i></span>
                </div>

                <?php if( !$carousel && $paginationStyle == '2' ){ 
                    AgniBuilderDynamicBlocks::agni_products_categories_pagination( $attributes, $total_products_to_display ); 
                } ?>
            <?php 
            ?>
        </div>
        <?php

        // add_filter( 'subcategory_archive_thumbnail_size', function(){
        //     return 'woocommerce_thumbnail';
        // } );
        
        if( !$category_thumbnail ){
            add_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
        }
        if( $category_icon ){
            remove_action( 'woocommerce_before_subcategory_title', 'cartify_template_loop_product_category_tab_icon', 10 );
        }

        if( !$category_title ){
            add_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
        }
        if( $category_desc ){
            remove_action( 'woocommerce_after_subcategory_title', 'cartify_template_loop_product_category_description' );
        }

        if( $category_children ){
            remove_action( 'woocommerce_after_subcategory_title', 'cartify_template_loop_product_category_tab_children', 15 );
        }

        if( $category_button ){
            remove_action( 'woocommerce_after_subcategory_title', 'cartify_template_loop_product_category_tab_button', 20 );
        }

        return ob_get_clean();
    }
}

function cartify_template_loop_product_category_tab_button( $category ){
    ?>
    <a href="<?php echo esc_url( get_term_link( $category->slug, $category->taxonomy ) ) ?>" class="woocommerce-loop-category__button"><?php echo esc_html( apply_filters( 'cartify_template_loop_product_category_tab_button_text', $category ) ); ?></a>
    <?php
}

function cartify_template_loop_product_category_tab_children( $category ){
    ?> 
    <div class="woocommerce-loop-category__sub-categories"><?php echo apply_filters( 'cartify_template_loop_product_category_tab_sub_categories', $category ) ?></div>
    <?php 
}

function cartify_template_loop_product_category_tab_icon( $category ){
    if( !function_exists( 'cartify_prepare_icon' ) ){
        return;
    }
    
    // print_r( $category->term_id );
    $term_id = $category->term_id;
    $cat_icon = get_term_meta( $term_id, 'agni_product_cat_icon_id', true );

    if( !empty( $cat_icon ) ){
        ?>
        <span class="woocommerce-loop-category__icon"><?php echo cartify_prepare_icon( $cat_icon ); ?><?php // echo esc_html( apply_filters( 'cartify_template_loop_product_category_tab_icon_content', $category ) ); ?></span>
        <?php
    }
}



if( !function_exists( 'dynamic_blocks_agni_products_categories_tab_placeholder' ) ){
    function dynamic_blocks_agni_products_categories_tab_placeholder( $count ){

        ob_start(); 

        for($i = 0; $i < $count; $i++){
            ?>
            <li class="product-category product">
                <?php echo wp_kses( wc_placeholder_img(), 'img' ); ?>
                <div class="category-details">
                    <h2 class="woocommerce-loop-category__title"><a href="#" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><?php echo esc_html__( 'This is Sample category title', 'cartify' ); ?></a></h2>
                </div>
            </li>
            <?php
        }
        ?>
        <?php
        return ob_get_clean();
    }
}