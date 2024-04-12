<?php

if( !function_exists('dynamic_blocks_agni_recently_viewed_products') ){
    function dynamic_blocks_agni_recently_viewed_products( $attributes, $content = '' ){

    extract( $attributes );


    $args = array(
        'no_title' => true, 
        'count' => $count
    );


    $header_classes = array(
        'agni-block-recently-viewed-products-header',
        'has-header-align-' . $align
    );

    $block_classes = array(
        "agni-block-recently-viewed-products",
        $customClassName
    );

        ob_start();


        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $block_classes ) ); ?>">
            <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $header_classes ) ); ?>"><?php 
                if( !empty($headingText) ){ 
                    echo wp_kses( '<h'.$headingLevel.' class="agni-block-recently-viewed-products-heading">'.$headingText.'</h'.$headingLevel.'>', 'title' );  
                } 
                
            ?></div>
            <?php cartify_recently_viewed_products( $args ); ?>
        </div>
        <?php

        return ob_get_clean();
    }
}
