<?php 

require_once AGNI_TEMPLATE_DIR . '/agni-ajax-variation-swatches/admin-agni-ajax-variation-swatches.php';

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'cartify_woocommerce_variation_swatches' , 10, 2 );

function cartify_woocommerce_variation_swatches( $html, $args ){
    if( !class_exists( 'Agni_Cartify' ) ){
        return $html;
    }

    $html_select = $html;
    $swatches = '';
    $values = array();
    ?>
    <?php

    $product               = $args['product'];
    $attribute             = $args['attribute'];

        $terms = wc_get_product_terms(
        $product->get_id(),
        $attribute,
        array(
            'fields' => 'all',
        )
    );

    // foreach( $terms as $term ){

            //     $values[] = get_term_meta($term->term_id, 'agni_variation_swatch_field', true);

            // }

    // Get selected value.
    // if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
    // 	$selected_key     = 'attribute_' . sanitize_title( $args['attribute'] );
    // 	$args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
    // }

    $options               = $args['options'];
    $product               = $args['product'];
    $attribute             = $args['attribute'];
    $name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
    $id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
    $class                 = $args['class'];
    $show_option_none      = false; //(bool) $args['show_option_none'];
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__( 'Choose an option', 'cartify' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
        $attributes = $product->get_variation_attributes();
        $options    = $attributes[ $attribute ];
    }

    //$html .= '<span value="">' . esc_html( $show_option_none_text ) . '</span>';

    if ( ! empty( $options ) ) {
        if ( $product && taxonomy_exists( $attribute ) ) {
            // Get terms if this is a taxonomy - ordered. We need the names too.
            $terms = wc_get_product_terms(
                $product->get_id(),
                $attribute,
                array(
                    'fields' => 'all',
                )
            );


            $attribute_taxonomies = wc_get_attribute_taxonomies();

            $attribute_display_types = array();

            if ( $attribute_taxonomies ) {
                foreach ($attribute_taxonomies as $tax) {
                    $attribute_display_types['pa_' .$tax->attribute_name] = $tax->attribute_type;
                }
            }

            if($attribute_display_types[$attribute] !== 'select' ){
                $swatches = '<div class="agni-swatches agni-swatches-' . esc_attr( sanitize_title( $attribute ) ) . ' ' . esc_attr( $class ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
            }

                        foreach ( $terms as $term ) {
                if ( in_array( $term->slug, $options, true ) ) {
                    $value = get_term_meta($term->term_id, 'agni_variation_swatch_field', true);

                    $selected = ( isset($args['selected']) && $term->slug == $args['selected']) ?'selected':'';

                    if ( $attribute_taxonomies ) {
                        foreach ($attribute_taxonomies as $tax) {
                            if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                                if( 'pa_'.$tax->attribute_name === $attribute ){ 
                                    ?>
                                    <?php switch( $tax->attribute_type ){
                                        case 'color':

                                            $styles ="
                                                .agni-swatch-color--{$term->slug}{
                                                    background: {$value}; 
                                                    border-color: {$value};
                                                }
                                            ";

			                                wp_enqueue_style( 'cartify-custom-style' );
			                                wp_add_inline_style( 'cartify-custom-style', $styles );

                                            $swatches .= '<span class="agni-swatch agni-swatch-color agni-swatch-color--'.esc_attr($term->slug).' '. esc_attr($selected) .'" data-value="' . esc_attr( $term->slug ) . '">' . esc_html( apply_filters( 'agni_woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) ) . '</span>';
                                            break;
                                        case 'image':
                                            $value = !empty($value)?wp_get_attachment_image($value, 'thumbnail'):'';
                                            $swatches .= '<span class="agni-swatch agni-swatch-image agni-swatch-image--'.esc_attr($term->slug).' '. esc_attr($selected) .'" data-value="' . esc_attr( $term->slug ) . '"><span>' . wp_kses( apply_filters( 'agni_woocommerce_variation_option_name', $value, $term, $attribute, $product ), 'img' ) . '</span></span>';
                                            break;
                                        case 'label':
                                            $value = empty($value)?$term->name:$value;
                                            $swatches .= '<span class="agni-swatch agni-swatch-label agni-swatch-label--'.esc_attr($term->slug).' '. esc_attr($selected) .'" data-value="' . esc_attr( $term->slug ) . '">' . esc_html( apply_filters( 'agni_woocommerce_variation_option_name', $value, $term, $attribute, $product ) ) . '</span>';
                                            break;
                                    }
                                }
                            }
                        }
                    }

                                    }
            }

                        if($attribute_display_types[$attribute] !== 'select' ){
                $swatches .= '</div>';
            }

        } 
    }


    return $swatches . $html_select;
}


function cartify_variation_swatches(){
    ?>

    <?php


    // Registering JS for Variation swatches
    wp_enqueue_script('cartify-variation-swatches', AGNI_FRAMEWORK_JS_URL . '/agni-ajax-variation-swatches/agni-ajax-variation-swatches.js', array('jquery'), wp_get_theme()->get('Version'), true);
}

add_action( 'wp_enqueue_scripts', 'cartify_variation_swatches' );