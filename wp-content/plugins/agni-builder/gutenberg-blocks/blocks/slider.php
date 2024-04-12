<?php

function dynamic_block_agni_slider( $attributes, $content = '' ){

    extract( $attributes );
    

    $columnWidthClass = ($width === 'wide' || $width === 'full' || $width === 'container') ? $width : '';

    $slider_classes = array(
        'agni-block-slider',
        !empty( $columnWidthClass ) ? 'has-columns-' . $columnWidthClass : '',
        $customClassName
    );

    if( !isset( $id ) || empty( $id ) ){
        // $id = '0';
        $sliders = (array)get_option('agni_slider_builder_sliders');
        $id = $sliders[0]['id'];
    }


	// wp_register_style( 'cartify-slider-custom-' . $id, false );
    // wp_enqueue_style( 'cartify-slider-custom-' . $id );

    ob_start();
    
    ?>
    <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $slider_classes ) ); ?>">
        <?php do_action( 'agni_slider', $id ); ?>
    </div>
    <?php 

    return ob_get_clean();
}