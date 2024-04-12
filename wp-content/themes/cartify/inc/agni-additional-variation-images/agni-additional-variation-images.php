<?php

add_action( 'wp_enqueue_scripts', 'cartify_additional_variation_images_script' );
add_action( 'admin_enqueue_scripts', 'cartify_additional_variation_images_admin_script' );
// add_action( 'admin_footer', 'cartify_admin_template_scripts' );
add_action( 'woocommerce_product_after_variable_attributes', 'cartify_add_additional_variation_images_uploader', 10, 3 );
add_action( 'woocommerce_save_product_variation', 'cartify_save_additional_variation_images', 10, 2 );
add_filter( 'woocommerce_available_variation', 'cartify_update_available_variations', 99, 1);

add_action( 'wc_ajax_agni_additional_variation_images', 'cartify_additional_variation_images');
add_action( 'wc_ajax_agni_additional_variation_images_reset', 'cartify_additional_variation_images_reset');


// function cartify_admin_template_scripts(){
//     require_once AGNI_TEMPLATE_DIR . '/agni-additional-variation-images/templates/template-admin-images.php';
// }

function cartify_add_additional_variation_images_uploader( $loop, $variation_data, $variation ) {
	$meta_key = 'agni_product_variation_images';
	$variation_id = absint($variation->ID);
	$variation_image_ids = get_post_meta($variation_id, $meta_key, true);

	?>

	   	<div class="agni-additional-variation-images">
		<div class="agni-additional-variation-images__holder"> 
			<?php 
			foreach($variation_image_ids as $id){
                $image_attributes = wp_get_attachment_image_src($id, 'thumb');
                ?>
                <div id="agni-variation-image-<?php echo esc_attr( $id ); ?>" class="agni-additional-variation-images__image">
                    <input type="hidden" name="agni_product_variation_images[<?php echo esc_attr( $variation_id ); ?>][]" value="<?php echo esc_attr($id); ?>" />
                    <?php echo wp_kses(wp_get_attachment_image($id, 'thumb'), array( 'img' => array( 'class' => array(), 'width' => array(), 'height' => array(), 'src' => array(), 'srcset' => array(), 'sizes' => array(), 'alt' => array() ) ) ); ?>
                    <a class="agni-additional-variation-images__remove" data-remove-id="agni-variation-image-<?php echo esc_attr( $id ); ?>"></a>
                </div>
			<?php }
			?>
		</div>
		<a href="#" class="agni_variation-images__button button" data-variation-id="<?php echo esc_attr($variation_id); ?>"><?php echo esc_html__( 'Add More Images', 'cartify' ); ?></a>
	</div>
	<?php
}


 function cartify_save_additional_variation_images( $variation_id, $i ) {
	$meta_key = 'agni_product_variation_images';
    if(!isset($_POST['agni_product_variation_images']) || !isset($_POST['agni_product_variation_images'][$variation_id])){
        delete_post_meta($variation_id, $meta_key);
    }

    if (isset($_POST['agni_product_variation_images'][$variation_id])) {
        update_post_meta($variation_id, $meta_key, $_POST['agni_product_variation_images'][$variation_id]);
    }

}


 function cartify_update_available_variations( $variations ) {

	    $meta_key = 'agni_product_variation_images';
    $variation_images = (array)get_post_meta( $variations[ 'variation_id' ], $meta_key, true );
    $variations['agni_variation_images'] = array();
    foreach($variation_images as $i => $variation_image){
        $variations['agni_variation_images'][$i] = $variation_image;
    }
    return $variations;
} 

function cartify_additional_variation_images(){
    $product_id = $_POST['product_id'];
    $variation_id = $_POST['variation_id'];

    $product = wc_get_product( $product_id );
    //$variation = new WC_Product_Variation($variation_id);
    $available_variations = $product->get_available_variations();
    $variation_image_id = '';
    ?>
    <?php
    $available_variation_images_ids = array();
    foreach( $available_variations as $variation ){
        if( $variation_id == $variation['variation_id']){
            $variation_image_id = $variation['image_id'];

                        $variation['agni_variation_images'] = array_filter( $variation['agni_variation_images'] );

                        foreach($variation['agni_variation_images'] as $additional_variation_image_id){
                $available_variation_images_ids[] = $additional_variation_image_id;
            }
        }
    }

    $args = array( 
        'product' => $product, 
        'variation_image_id' => $variation_image_id, 
    );
    if( !empty( $available_variation_images_ids ) ){
        $args['variation_additional_image_ids'] = $available_variation_images_ids;
    }

        wc_get_template( 'single-product/product-image.php', $args );

        ?>
    <?php
        /*
    ?>
    <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
        <figure class="woocommerce-product-gallery__wrapper">
        <?php 

            foreach( $available_variations as $variation ){
                if( $variation_id == $variation['variation_id']){

                                        if( $variation['image_id'] ){
                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $variation['image_id'], true ), $variation['image_id'] ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                    }
                    foreach($variation['agni_variation_images'] as $variation_image_id){
                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $variation_image_id, true ), $variation_image_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                    }
                }
            }

                    ?>
        </figure>
    </div>
    <?php */

    die();
}

function cartify_additional_variation_images_reset(){
    $product_id = $_POST['product_id'];
    $product = wc_get_product( $product_id );

    ?>
        <?php 
            $args = array( 
                'product' => $product
            );
            wc_get_template( 'single-product/product-image.php', $args );

                    ?>
    <?php

    die();
}

function cartify_additional_variation_images_admin_script($hook_suffix){
    global $post;

    //if ( 'product' !== $post->post_type ) {
	    if(!in_array($hook_suffix, array('post.php', 'post-new.php') )){
			return;
		}
	//}

	// Enqueue JS
	if( !did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
	}

    	wp_enqueue_script('cartify-additional-variation-images-admin', AGNI_FRAMEWORK_JS_URL . '/agni-additional-variation-images/admin-agni-additional-variation-images.js', array('jquery'), wp_get_theme()->get('Version'), true);
}


function cartify_additional_variation_images_script(){

	wp_enqueue_script('cartify-additional-variation-images', AGNI_FRAMEWORK_JS_URL . '/agni-additional-variation-images/agni-additional-variation-images.js', array('jquery'), wp_get_theme()->get('Version'), true);
    wp_localize_script('cartify-additional-variation-images', 'cartify_additional_variation_images', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('agni_ajax_additional_variation_images_nonce'),
        'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
    ));
}