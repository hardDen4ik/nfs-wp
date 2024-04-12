<?php

add_action( 'woocommerce_init', 'cartify_woocommerce_variation_swatches_init' );
add_action( 'woocommerce_product_option_terms', 'cartify_woocommerce_variation_swatches_options_terms', 10, 3 );
add_action( 'admin_enqueue_scripts', 'cartify_admin_variation_swatches' );

add_filter( 'product_attributes_type_selector', 'cartify_woocommerce_variation_swatches_display_type' );


function cartify_woocommerce_variation_swatches_rest_meta_preparation(  $data, $term, $context  ){
    $agni_variation_swatch_field = get_term_meta( $term->term_id, 'agni_variation_swatch_field', true );

    if( $agni_variation_swatch_field ) {
        $data->data['agni_variation_swatch_field'] = $agni_variation_swatch_field;
    }

    return $data;
}


function cartify_woocommerce_variation_swatch_form_field_text($value = ''){
    ?>
    <input type="text" name="agni_variation_swatch_fields[label]" id="tag-label" class="agni-tag-label" value="<?php echo esc_attr($value); ?>">
    <?php
}

function cartify_woocommerce_variation_swatch_form_field_color($value = ''){
    ?>
    <input type="text" name="agni_variation_swatch_fields[color]" id="tag-color" class="agni-tag-color" value="<?php echo esc_attr($value); ?>">
    <?php  
}

function cartify_woocommerce_variation_swatch_form_field_image($value = false){

        ?>
    <div class="agni-tag-image-placeholder"><?php echo wp_get_attachment_image($value, 'thumbnail'); ?></div>
    <input type="hidden" name="agni_variation_swatch_fields[image]" value="<?php echo esc_attr($value); ?>">
    <button id="tag-image" class="agni-tag-image button"><?php echo esc_html__( 'Insert Image', 'cartify' ); ?></button>
    <?php 
}


// A callback function to add a custom field to our "presenters" taxonomy
function cartify_woocommerce_variation_swatches_edit_form_fields($tag) {

    $attribute_taxonomies = wc_get_attribute_taxonomies();
    $taxonomy_terms = array();

    if ( $attribute_taxonomies ) {
        foreach ($attribute_taxonomies as $tax) {
            if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                if( 'pa_'.$tax->attribute_name === $tag->taxonomy ){

                                        switch( $tax->attribute_type ){
                        case 'color':
                            $value = get_term_meta($tag->term_id, 'agni_variation_swatch_field', true);
                            ?>
                            <tr class="form-field form-required term-color-wrap">
                                <th scope="row"><label for="tag-color"><?php echo esc_html__( 'Choose color', 'cartify' ); ?></label></th>
                                <td><?php echo cartify_woocommerce_variation_swatch_form_field_color($value); ?></td>
                            </tr>
                            <?php
                            break;
                        case 'image':
                            $value = get_term_meta($tag->term_id, 'agni_variation_swatch_field', true);
                            ?>
                             <tr class="form-field form-required term-image-wrap">
                                <th scope="row"><label for="tag-image"><?php echo esc_html__( 'Choose Image', 'cartify' ); ?></label></th>
                                <td><?php echo cartify_woocommerce_variation_swatch_form_field_image($value); ?></td>
                            </tr>
                            <?php
                            break;
                        case 'label':
                            $value = get_term_meta($tag->term_id, 'agni_variation_swatch_field', true);
                            ?>
                            <tr class="form-field form-required term-label-wrap">
                                <th scope="row"><label for="tag-label"><?php echo esc_html__( 'Enter Label', 'cartify' ); ?></label></th>
                                <td><?php echo cartify_woocommerce_variation_swatch_form_field_text($value); ?></td>
                            </tr>
                            <?php
                            break;
                        default:
                            break;
                    }
                }

            }
        }
    }

     }

// A callback function to add a custom field to our "presenters" taxonomy
function cartify_woocommerce_variation_swatches_add_form_fields($tag) {

    $attribute_taxonomies = wc_get_attribute_taxonomies();
    $taxonomy_terms = array();

    if ( $attribute_taxonomies ) {
        foreach ($attribute_taxonomies as $tax) {
            if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                if( 'pa_'.$tax->attribute_name === $tag ){

                                        switch( $tax->attribute_type ){
                        case 'color':
                            ?>
                            <div class="form-field term-color-wrap">
                                <label for="tag-color"><?php echo esc_html__( 'Choose color', 'cartify' ); ?></label>
                                <?php echo cartify_woocommerce_variation_swatch_form_field_color(); ?>
                            </div>
                            <?php
                            break;
                        case 'image':
                            ?>
                             <div class="form-field term-image-wrap">
                                <label for="tag-image"><?php echo esc_html__( 'Choose Image', 'cartify' ); ?></label>
                                <?php echo cartify_woocommerce_variation_swatch_form_field_image(); ?>
                            </div>
                            <?php
                            break;
                        case 'label':
                            ?>
                            <div class="form-field form-required term-label-wrap">
                                <label for="tag-label"><?php echo esc_html__( 'Enter Label', 'cartify' ); ?></label>
                                <?php echo cartify_woocommerce_variation_swatch_form_field_text(); ?>
                            </div>
                            <?php
                            break;
                        default:
                            break;
                    }
                }

            }
        }
    }

 }

 // A callback function to save our extra taxonomy field(s)
function cartify_save_taxonomy_custom_fields( $term_id ) {

    if( !isset( $_POST['agni_variation_swatch_fields'] ) ){
        return;
    }

    if( !empty($_POST['agni_variation_swatch_fields']['color']) ){
        $field_value = $_POST['agni_variation_swatch_fields']['color'];
    }
    else if( !empty($_POST['agni_variation_swatch_fields']['image']) ){
        $field_value = $_POST['agni_variation_swatch_fields']['image'];
    }
    else if( !empty($_POST['agni_variation_swatch_fields']['label']) ){
        $field_value = $_POST['agni_variation_swatch_fields']['label'];
    }

    // update_option( 'agni_variation_swatch_field_'.$term_id, $field_value );
    update_term_meta( $term_id, 'agni_variation_swatch_field', $field_value );

}

function cartify_woocommerce_variation_swatches_init(){

    $attribute_taxonomies = wc_get_attribute_taxonomies();
    //print_r($attribute_taxonomies);
    if ( $attribute_taxonomies ) {
        foreach ($attribute_taxonomies as $tax) {

            // register_term_meta( 'pa_'.$tax->attribute_name, 'agni_variation_swatch_field', array(
            //     'type' => 'string',
            //     'single' => true,
            //     'default' => '',
            //     'show_in_rest' => true,
            // ) );

            add_filter( 'woocommerce_rest_prepare_pa_' . $tax->attribute_name, 'cartify_woocommerce_variation_swatches_rest_meta_preparation', 10, 3 );

            //if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
    //echo $tax->attribute_name;
            add_action( 'pa_'.$tax->attribute_name.'_edit_form_fields', 'cartify_woocommerce_variation_swatches_edit_form_fields', 10, 2 );
            add_action( 'pa_'.$tax->attribute_name.'_add_form_fields', 'cartify_woocommerce_variation_swatches_add_form_fields', 10, 2 );

                        add_action( 'edited_pa_'.$tax->attribute_name, 'cartify_save_taxonomy_custom_fields', 10, 2 );
            add_action( 'create_pa_'.$tax->attribute_name, 'cartify_save_taxonomy_custom_fields', 10, 2 );
            //}
        }
    }
}


function cartify_woocommerce_variation_swatches_display_type($array){
    $array['color'] = esc_html__( 'Color', 'cartify' );
    $array['image'] = esc_html__( 'Image', 'cartify' );
    $array['label'] = esc_html__( 'Label', 'cartify' );

    return $array;
}


function cartify_woocommerce_variation_swatches_options_terms( $attribute_taxonomy, $i, $attribute ){

        $available_attribute_type = array('color', 'image', 'label');

    if ( in_array($attribute_taxonomy->attribute_type, $available_attribute_type) ) {
        ?>
        <select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'cartify' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
            <?php
            $args      = array(
                'orderby'    => ! empty( $attribute_taxonomy->attribute_orderby ) ? $attribute_taxonomy->attribute_orderby : 'name',
                'hide_empty' => 0,
            );
            $all_terms = get_terms( $attribute->get_taxonomy(), apply_filters( 'woocommerce_product_attribute_terms', $args ) );
            if ( $all_terms ) {
                foreach ( $all_terms as $term ) {
                    $options = $attribute->get_options();
                    $options = ! empty( $options ) ? $options : array();
                    echo '<option value="' . esc_attr( $term->term_id ) . '"' . wc_selected( $term->term_id, $options ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
                }
            }
            ?>
        </select>
        <button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'cartify' ); ?></button>
        <button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'cartify' ); ?></button>
        <button class="button fr plus add_new_attribute"><?php esc_html_e( 'Add new', 'cartify' ); ?></button>
        <?php
    }
}

function cartify_admin_variation_swatches(){
    ?>

    <?php

    wp_enqueue_style( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');

    wp_enqueue_media();

    // Registering JS for Variation swatches
    wp_enqueue_script('cartify-admin-variation-swatches', AGNI_FRAMEWORK_JS_URL . '/agni-ajax-variation-swatches/admin-agni-ajax-variation-swatches.js', array('jquery'), wp_get_theme()->get('Version'), true);
}