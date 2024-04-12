<?php 

add_filter( 'agni_products_brands_placeholder', 'dynamic_blocks_agni_products_brands_placeholder', 10, 1 );


if( !function_exists( 'dynamic_blocks_agni_products_brands' ) ){
    function dynamic_blocks_agni_products_brands( $attributes, $content = '' ){

        extract($attributes);

        $args = array();
        
        switch( $order_by ){
            case '1': 
                $args['orderby'] = 'name';
                $args['order'] = 'ASC';

                break;

            case '2': 
                $args['orderby'] = 'name';
                $args['order'] = 'DESC';

                break;

            default: 
                $args['orderby'] = 'id';
        }

        if( !empty($brand_ids) ){
            $args['include'] = $brand_ids;
        }

        
        $args['taxonomy'] = 'product_brand';
        $args['hide_empty'] = $hide_empty;
        $args['number'] = $count == '-1' ? '' : $count;


        $brands_classes = array(
            'agni-block-products-brands',
            // 'style-' . $displayStyle,
            (isset($gap['desktop']) && $gap['desktop'] == 0 ) ? 'has-no-gutter-desktop' : '',
            (isset($gap['laptop']) && $gap['laptop'] == 0 ) ? 'has-no-gutter-laptop' : '',
            (isset($gap['tab']) && $gap['tab'] == 0 ) ? 'has-no-gutter-tab' : '',
            (isset($gap['mobile']) && $gap['mobile'] == 0 ) ? 'has-no-gutter-mobile' : '',
            ($brandFilled) ? 'has-background' : '',
            ($brandOutlined) ? 'has-border' : '',
            ($align) ? 'has-align-' . $align : '',
            $customClassName
        );

        $brands_list_classes = array(
            'agni-block-products-brands-list'
        );

        ob_start();

        $brands = get_terms( $args );

        if( !empty( $alphabet ) ){
            $brands = array_filter($brands, function($brand) use($alphabet){ 
                return !strcasecmp( $brand->name[0], $alphabet ); 
            });
        }

        
        ?>
        <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $brands_classes ) ); ?>">
            <?php if( $carousel ){ 

                wp_enqueue_script( 'slick' );
                wp_enqueue_style( 'slick' );

                $brands_list_classes[] = 'agni-block-has-carousel';
                
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
                
                ?>
                <ul class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $brands_list_classes ) ); ?>" data-slick="<?php echo esc_attr( $carousel_args ); ?>" data-slick-slides-to-show="<?php echo esc_attr( wp_json_encode($columns) ); ?>">
            <?php } 
            else{ ?>
                <ul class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $brands_list_classes ) ); ?>">
            <?php } ?>

            <?php 
            if( !empty( $brands ) ){
                foreach ($brands as $key => $brand) {

                    // print_r($brand);

                    $attachment_id = get_term_meta( $brand->term_id, 'agni_product_brand_icon_id', true );
                    ?>
                    <li class="agni-block-products-brands-item">
                        <a href="<?php echo esc_url( get_term_link($brand) ); ?>">
                            <?php if( $brand_thumbnail ){ ?>
                                <?php echo wp_kses( wp_get_attachment_image( $attachment_id, 'full' ), array_merge_recursive( wp_kses_allowed_html( 'img' ), wp_kses_allowed_html( 'svg' ) ) ); ?>
                            <?php } ?>
                            <?php if( $brand_name ){ ?>
                                <h6 class="agni-block-products-brands-item__name"><?php echo esc_html( $brand->name ); ?></h6>
                            <?php } ?>
                            <?php if( $brand_description ){ ?>
                                <p class="agni-block-products-brands-item__description"><?php echo esc_html( $brand->description ); ?></p>
                            <?php } ?>
                        </a>
                    </li>
                    
                <?php }
            }
            else{
                if( isset($showPlaceholder) && $showPlaceholder == '1' ){
                    echo apply_filters( 'agni_products_brands_placeholder', $attributes );
                }
            }
            ?>
            </ul>
        </div>

        <?php

        return ob_get_clean();
    }
}



if( !function_exists( 'dynamic_blocks_agni_products_brands_placeholder' ) ){
    function dynamic_blocks_agni_products_brands_placeholder( $attributes ){

        $count = $attributes['count'];


        ob_start(); 

        for($i = 0; $i < $count; $i++){
            ?>
            <li class="agni-block-products-brands-item">
                <a href="#">
                    <?php echo wp_kses( wc_placeholder_img(), 'img' ); ?>
                    <h6 class="agni-block-products-brands-item__name"><?php echo esc_html__( 'Sample brand', 'cartify' ); ?></h6>
                </a>
            </li>
            <?php
        }

        return ob_get_clean();
    }
}