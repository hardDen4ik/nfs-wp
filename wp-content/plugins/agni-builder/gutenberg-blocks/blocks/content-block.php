<?php 

function dynamic_block_agni_content_block( $attributes, $content = '' ){

    extract($attributes);

    // echo 'block id' . $id;

    $content_block_classes = array(
        'agni-block-content-block',
        $customClassName
    );

    ob_start();
    ?>
    <div class="<?php echo esc_attr( AgniBuilderHelper::prepare_classes( $content_block_classes ) ); ?>">
        <?php echo apply_filters( 'agni_content_block', $id ); ?>
    </div>
    <?php
    return ob_get_clean();

}