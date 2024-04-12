<?php 

add_filter( 'agni_product_layout_css', 'cartify_product_layout_css' );
add_filter( 'agni_product_layout_css_block_processor', 'cartify_product_layout_css_block_processor', 10 ,3 );

add_filter( 'agni_product_layout_css_parser', 'cartify_product_layout_css_parser', 10, 3 );
add_filter( 'agni_product_layout_css_array_parser', 'cartify_product_layout_css_array_parser', 10, 2 );

add_filter( 'agni_product_layout_css_templates', 'cartify_product_layout_css_templates', 10, 3 );
add_filter( 'agni_product_layout_css_placement', 'cartify_product_layout_css_placement', 10, 3 );

add_filter( 'agni_product_layout_css_columns', 'cartify_product_layout_css_columns', 10, 3 );
add_filter( 'agni_product_layout_css_column', 'cartify_product_layout_css_column', 10, 3 );
add_filter( 'agni_product_layout_css_divider', 'cartify_product_layout_css_divider', 10, 3 );
add_filter( 'agni_product_layout_css_separator', 'cartify_product_layout_css_separator', 10, 3 );
add_filter( 'agni_product_layout_css_spacer', 'cartify_product_layout_css_spacer', 10, 3 );

add_filter( 'agni_product_layout_css_breadcrumbs', 'cartify_product_layout_css_breadcrumbs', 10, 3 );
add_filter( 'agni_product_layout_css_sale', 'cartify_product_layout_css_sale', 10, 3 );
add_filter( 'agni_product_layout_css_new', 'cartify_product_layout_css_new', 10, 3 );
add_filter( 'agni_product_layout_css_hot', 'cartify_product_layout_css_hot', 10, 3 );
add_filter( 'agni_product_layout_css_images', 'cartify_product_layout_css_images', 10, 3 );
add_filter( 'agni_product_layout_css_title', 'cartify_product_layout_css_title', 10, 3 );
add_filter( 'agni_product_layout_css_video', 'cartify_product_layout_css_video', 10, 3 );
add_filter( 'agni_product_layout_css_360', 'cartify_product_layout_css_360', 10, 3 );
add_filter( 'agni_product_layout_css_brand', 'cartify_product_layout_css_brand', 10, 3 );
add_filter( 'agni_product_layout_css_rating', 'cartify_product_layout_css_rating', 10, 3 );
add_filter( 'agni_product_layout_css_price', 'cartify_product_layout_css_price', 10, 3 );
add_filter( 'agni_product_layout_css_countdown', 'cartify_product_layout_css_countdown', 10, 3 );
add_filter( 'agni_product_layout_css_featured', 'cartify_product_layout_css_featured', 10, 3 );
add_filter( 'agni_product_layout_css_features', 'cartify_product_layout_css_features', 10, 3 );
add_filter( 'agni_product_layout_css_short_description', 'cartify_product_layout_css_short_description', 10, 3 );
add_filter( 'agni_product_layout_css_metadata', 'cartify_product_layout_css_metadata', 10, 3 );
add_filter( 'agni_product_layout_css_stock', 'cartify_product_layout_css_stock', 10, 3 );
add_filter( 'agni_product_layout_css_add_to_cart', 'cartify_product_layout_css_add_to_cart', 10, 3 );
add_filter( 'agni_product_layout_css_offers', 'cartify_product_layout_css_offers', 10, 3 );
add_filter( 'agni_product_layout_css_shipping_info', 'cartify_product_layout_css_shipping_info', 10, 3 );
add_filter( 'agni_product_layout_css_add_to_wishlist', 'cartify_product_layout_css_add_to_wishlist', 10, 3 );
add_filter( 'agni_product_layout_css_add_to_compare', 'cartify_product_layout_css_add_to_compare', 10, 3 );
add_filter( 'agni_product_layout_css_compare', 'cartify_product_layout_css_compare', 10, 3 );
add_filter( 'agni_product_layout_css_compare_content', 'cartify_product_layout_css_compare_content', 10, 3 );
add_filter( 'agni_product_layout_css_recently_viewed', 'cartify_product_layout_css_recently_viewed', 10, 3 );
add_filter( 'agni_product_layout_css_upsell', 'cartify_product_layout_css_upsell', 10, 3 );
add_filter( 'agni_product_layout_css_related', 'cartify_product_layout_css_related', 10, 3 );
add_filter( 'agni_product_layout_css_tabs', 'cartify_product_layout_css_tabs', 10, 3 );
add_filter( 'agni_product_layout_css_fbt', 'cartify_product_layout_css_fbt', 10, 3 );



if( !function_exists( 'cartify_product_layout_css' ) ){
    function cartify_product_layout_css( $layout ){

        $styles = '';

        // print_r( $layout );
        // print_r($layout['settings']);
        $layout_settings = $layout['settings'];
        $layout_settings['className'] = '.' . $layout_settings['className'];

        $styles .= apply_filters( 'agni_product_layout_css_block_processor', $layout );
        $styles .= apply_filters( 'agni_product_layout_css_templates', $layout_settings );

        foreach( $layout['content'] as $col_key => $col ){
            $col_classname = '';
            $col['settings']['className'] = $col['settings']['className'] ? '.' . $col['settings']['className'] : '.' . $col['slug'];
            $col_classname = $col['settings']['className'];

            $styles .= apply_filters( 'agni_product_layout_css_block_processor', $col );

                        if( isset( $col['content'] ) && !empty( $col['content'] ) ){
                foreach ($col['content'] as $block_key => $block) {
                    // print_r($block);
                    $block_classname = isset( $block['settings']['className'] ) ? '.'.$block['settings']['className'] : '';
                    if( !empty($col_classname) ){
                        $block_classname = $col_classname . ' ' . $block_classname;
                    }
                    $block['settings']['className'] = $block_classname;

                    $styles .= apply_filters( 'agni_product_layout_css_block_processor', $block );
                }
            }

                    }

                return $styles;


    }
}

if( !function_exists( 'cartify_product_layout_css_block_processor' ) ){
    function cartify_product_layout_css_block_processor( $block, $row_classname = '', $col_classname = '' ){
        $styles = '';
        $block_settings = isset($block['settings']) ? $block['settings'] : '';

                // title, spacer, price
        $block_settings['className'] = isset( $block_settings['className'] ) ? '' . $block_settings['className'] : '';

        $styles .= apply_filters( 'agni_product_layout_css_parser', $block_settings, $row_classname, $col_classname );

                if( !empty( $block['slug'] ) ){
            switch( $block['slug'] ){
                case 'before_single_product':
                case 'before_single_product_summary':
                case 'single_product_summary':
                case 'after_single_product_summary':
                    $styles .= apply_filters( 'agni_product_layout_css_placement', $block_settings );
                break;
                case 'divider':
                    $styles .= apply_filters( 'agni_product_layout_css_divider', $block_settings, $row_classname, $col_classname );
                break;
                case 'separator':
                    $styles .= apply_filters( 'agni_product_layout_css_separator', $block_settings, $row_classname, $col_classname );
                break;
                case 'spacer': 
                    $styles .= apply_filters( 'agni_product_layout_css_spacer', $block_settings, $row_classname, $col_classname );
                    break;
                case 'columns': 
                    // $block_settings = isset( $block['settings'] ) ? $block['settings'] : '';
                    // $block_settings['className'] = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

                    $styles .= apply_filters( 'agni_product_layout_css_columns', $block_settings );

                    foreach ($block['content'] as $inner_row_key => $inner_col) {
                        if( isset($inner_col['slug']) && $inner_col['slug'] == 'column' ){
                            $inner_col_settings = isset( $inner_col['settings'] ) ? $inner_col['settings'] : '';

                                                        $inner_col_settings['className'] = isset( $inner_col_settings['className'] ) ? $block_settings['className'] . '>.' . $inner_col_settings['className'] : '';
                            // print_r($inner_col);

                                                    $styles .= apply_filters( 'agni_product_layout_css_parser', $inner_col_settings, '' );
                            $styles .= apply_filters( 'agni_product_layout_css_column', $inner_col_settings, '');

                            foreach ($inner_col['content'] as $inner_block_key => $inner_block) {
                                $inner_block_settings = isset( $inner_block['settings'] ) ? $inner_block['settings'] : '';

                                $inner_block['settings']['className'] = $inner_col_settings['className'] . '>.' . $inner_block_settings['className'];

                                                                $styles .= apply_filters( 'agni_product_layout_css_block_processor', $inner_block, '', '' );
                            }
                        }
                    }
                break;
                case 'breadcrumbs':
                    $styles .= apply_filters( 'agni_product_layout_css_breadcrumbs', $block_settings, $row_classname, $col_classname );
                    break;
                case 'sale':
                    $styles .= apply_filters( 'agni_product_layout_css_sale', $block_settings, $row_classname, $col_classname );
                    break;
                case 'new':
                    $styles .= apply_filters( 'agni_product_layout_css_new', $block_settings, $row_classname, $col_classname );
                    break;
                case 'hot':
                    $styles .= apply_filters( 'agni_product_layout_css_hot', $block_settings, $row_classname, $col_classname );
                    break;
                case 'images':
                    $styles .= apply_filters( 'agni_product_layout_css_images', $block_settings, $row_classname, $col_classname );
                    break;
                case 'title':
                    $styles .= apply_filters( 'agni_product_layout_css_title', $block_settings, $row_classname, $col_classname );
                    break;
                case 'short_description':
                    $styles .= apply_filters( 'agni_product_layout_css_short_description', $block_settings, $row_classname, $col_classname );
                    break;
                case 'brand':
                    $styles .= apply_filters( 'agni_product_layout_css_brand', $block_settings, $row_classname, $col_classname );
                break;
                case 'countdown':
                    $styles .= apply_filters( 'agni_product_layout_css_countdown', $block_settings, $row_classname, $col_classname );
                break;
                case 'product_360':
                    $styles .= apply_filters( 'agni_product_layout_css_360', $block_settings, $row_classname, $col_classname );
                break;
                case 'product_video':
                    $styles .= apply_filters( 'agni_product_layout_css_video', $block_settings, $row_classname, $col_classname );
                break;
                case 'rating':
                    $styles .= apply_filters( 'agni_product_layout_css_rating', $block_settings, $row_classname, $col_classname );
                break;
                case 'price':
                    $styles .= apply_filters( 'agni_product_layout_css_price', $block_settings, $row_classname, $col_classname );
                break;
                case 'featured_label':
                    $styles .= apply_filters( 'agni_product_layout_css_featured', $block_settings, $row_classname, $col_classname );
                break;
                case 'features':
                    $styles .= apply_filters( 'agni_product_layout_css_features', $block_settings, $row_classname, $col_classname );
                break;
                case 'add_to_cart':
                    $styles .= apply_filters( 'agni_product_layout_css_add_to_cart', $block_settings, $row_classname, $col_classname );
                break;
                case 'offers':
                    $styles .= apply_filters( 'agni_product_layout_css_offers', $block_settings, $row_classname, $col_classname );
                break;
                case 'shipping_info':
                    $styles .= apply_filters( 'agni_product_layout_css_shipping_info', $block_settings, $row_classname, $col_classname );
                break;
                case 'add_to_wishlist':
                    $styles .= apply_filters( 'agni_product_layout_css_add_to_wishlist', $block_settings, $row_classname, $col_classname );
                break;
                case 'add_to_compare':
                    $styles .= apply_filters( 'agni_product_layout_css_add_to_compare', $block_settings, $row_classname, $col_classname );
                break;
                case 'compare':
                    $styles .= apply_filters( 'agni_product_layout_css_compare', $block_settings, $row_classname, $col_classname );
                break;
                case 'compare_content':
                    $styles .= apply_filters( 'agni_product_layout_css_compare_content', $block_settings, $row_classname, $col_classname );
                break;
                case 'metadata':
                    $styles .= apply_filters( 'agni_product_layout_css_metadata', $block_settings, $row_classname, $col_classname );
                    break;
                case 'stock':
                    $styles .= apply_filters( 'agni_product_layout_css_stock', $block_settings, $row_classname, $col_classname );
                    break;
                case 'recently_viewed':
                    $styles .= apply_filters( 'agni_product_layout_css_recently_viewed', $block_settings, $row_classname, $col_classname );
                break;
                case 'related':
                    $styles .= apply_filters( 'agni_product_layout_css_related', $block_settings, $row_classname, $col_classname );
                break;
                case 'upsell':
                    $styles .= apply_filters( 'agni_product_layout_css_upsell', $block_settings, $row_classname, $col_classname );
                break;
                case 'tabs':
                    $styles .= apply_filters( 'agni_product_layout_css_tabs', $block_settings, $row_classname, $col_classname );
                break;
                case 'fbt':
                    $styles .= apply_filters( 'agni_product_layout_css_fbt', $block_settings, $row_classname, $col_classname );
                break;
            }
        }

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_templates' ) ){
    function cartify_product_layout_css_templates( $block_settings ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $stretch = isset( $block_settings['stretch'] ) ? $block_settings['stretch'] : '';
        $width = isset( $block_settings['width'] ) ? $block_settings['width'] : '';
        $bg_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';

        $styles = '';

        if( $stretch == 'custom' ){
            $styles .= "{$block_classname} {";
                $styles .= cartify_prepare_css_styles(array(
                    '--agni_product_layout_template_width_mobile' => !empty( $width['mobile'] ) ? $width['mobile'] : '',
                    '--agni_product_layout_template_width_tab' => !empty( $width['tab'] ) ? $width['tab'] : '',
                    '--agni_product_layout_template_width_laptop' => !empty( $width['laptop'] ) ? $width['laptop'] : '',
                    '--agni_product_layout_template_width_desktop' => !empty( $width['desktop'] ) ? $width['desktop'] : '',
                ));

            $styles .= "}";
        }

        $styles .= "{$block_classname} {";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_template_background_color' => $bg_color,
            ));
        $styles .= "}";

        return $styles;

    }
}

if( !function_exists( 'cartify_product_layout_css_placement' ) ){
    function cartify_product_layout_css_placement( $block_settings ){

        $block_classname = (isset( $block_settings['className']) && !empty( $block_settings['className'] ) ) ? $block_settings['className'] : $block_settings['slug'];

                $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $width = isset( $block_settings['width'] ) ? $block_settings['width'] : '';
        $order = isset( $block_settings['order'] ) ? $block_settings['order'] : '';
        $vertical_align = isset( $block_settings['vertical-align'] ) ? $block_settings['vertical-align'] : '';

                $styles = '';

        $styles .= "{$block_classname} {";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_placement_width_mobile' => !empty( $width['mobile'] ) ? $width['mobile'] : '',
                '--agni_product_layout_placement_width_tab' => !empty( $width['tab'] ) ? $width['tab'] : '',
                '--agni_product_layout_placement_width_laptop' => !empty( $width['laptop'] ) ? $width['laptop'] : '',
                '--agni_product_layout_placement_width_desktop' => !empty( $width['desktop'] ) ? $width['desktop'] : '',

                '--agni_product_layout_placement_vertical_align' => $vertical_align,
                '--agni_product_layout_placement_z_index' => $order,
                '--agni_product_layout_placement_border_width' => $border_width,
                '--agni_product_layout_placement_border_color' => $border_color,

                'border-radius' => $border_radius,
            ));
        $styles .= "}";


                $styles .= "{$block_classname}-background {";
            $styles .= cartify_prepare_css_styles(array(
                'background-color' => $background_color,
            ));
        $styles .= "}";


        return $styles;
    }
}


if( !function_exists( 'cartify_product_layout_css_columns' ) ){
    function cartify_product_layout_css_columns( $block_settings ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

                $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $gap = isset($block_settings['gap']) ? $block_settings['gap'] : '';
        $vertical_align = isset($block_settings['vertical-align']) ? $block_settings['vertical-align'] : '';

                $styles = '';

        $styles .= "{$block_classname} {";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_columns_gap_desktop' => !empty( $gap['desktop'] ) ? $gap['desktop'] : '',
                '--agni_product_layout_columns_gap_laptop' => !empty( $gap['laptop'] ) ? $gap['laptop'] : '',
                '--agni_product_layout_columns_gap_tab' => !empty( $gap['tab'] ) ? $gap['tab'] : '',
                '--agni_product_layout_columns_gap_mobile' => !empty( $gap['mobile'] ) ? $gap['mobile'] : '',

                '--agni_product_layout_columns_border_width_desktop' => !empty( $border_width['desktop'] ) ? $border_width['desktop'] : '',
                '--agni_product_layout_columns_border_width_laptop' => !empty( $border_width['laptop'] ) ? $border_width['laptop'] : '',
                '--agni_product_layout_columns_border_width_tab' => !empty( $border_width['tab'] ) ? $border_width['tab'] : '',
                '--agni_product_layout_columns_border_width_mobile' => !empty( $border_width['mobile'] ) ? $border_width['mobile'] : '',

                '--agni_product_layout_columns_vertical_alignment' => $vertical_align,
                '--agni_product_layout_columns_border_color' => $border_color,

                'background-color' => $background_color,
                'border-radius' => $border_radius,
            ));
        $styles .= "}";

        return $styles;
    }
}
if( !function_exists( 'cartify_product_layout_css_column' ) ){
    function cartify_product_layout_css_column( $block_settings, $row_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

                $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $width = isset($block_settings['width']) ? $block_settings['width'] : '';
        $gap = isset($block_settings['gap']) ? $block_settings['gap'] : '';
        $align = isset($block_settings['align']) ? $block_settings['align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$block_classname} {";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_column_width_desktop' => !empty( $width['desktop'] ) ? $width['desktop'] : '',
                '--agni_product_layout_column_width_laptop' => !empty( $width['laptop'] ) ? $width['laptop'] : '',
                '--agni_product_layout_column_width_tab' => !empty( $width['tab'] ) ? $width['tab'] : '',
                '--agni_product_layout_column_width_mobile' => !empty( $width['mobile'] ) ? $width['mobile'] : '',

                '--agni_product_layout_column_border_width_desktop' => !empty( $border_width['desktop'] ) ? $border_width['desktop'] : '',
                '--agni_product_layout_column_border_width_laptop' => !empty( $border_width['laptop'] ) ? $border_width['laptop'] : '',
                '--agni_product_layout_column_border_width_tab' => !empty( $border_width['tab'] ) ? $border_width['tab'] : '',
                '--agni_product_layout_column_border_width_mobile' => !empty( $border_width['mobile'] ) ? $border_width['mobile'] : '',

                '--agni_product_layout_column_alignment' => $align,
                '--agni_product_layout_column_gap' => $gap,
                '--agni_product_layout_column_border_color' => $border_color,

                'background-color' => $background_color,
                'border-radius' => $border_radius,
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_separator' ) ){
    function cartify_product_layout_css_separator( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $width = isset( $block_settings['width'] ) ? $block_settings['width'] : '';
        $height = isset( $block_settings['height'] ) ? $block_settings['height'] : '';
        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $align = isset( $block_settings['align'] ) ? $block_settings['align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname} {";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_separator_width_desktop' => !empty( $width['desktop'] ) ? $width['desktop'] : '',
                '--agni_product_layout_separator_width_laptop' => !empty( $width['laptop'] ) ? $width['laptop'] : '',
                '--agni_product_layout_separator_width_tab' => !empty( $width['tab'] ) ? $width['tab'] : '',
                '--agni_product_layout_separator_width_mobile' => !empty( $width['mobile'] ) ? $width['mobile'] : '',

                '--agni_product_layout_separator_alignment' => $align,
                '--agni_product_layout_separator_color' => $color,
                '--agni_product_layout_separator_height' => $height,
            ));
        $styles .= "}";

        return $styles;
    }
}

// if( !function_exists( 'cartify_product_layout_css_divider' ) ){
//     function cartify_product_layout_css_divider( $block_settings, $row_classname = '', $col_classname = '' ){
//         $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

//         $width = isset( $block_settings['css-width'] ) ? $block_settings['css-width'] : '';
//         $color = isset( $block_settings['css-color'] ) ? $block_settings['css-color'] : '';
//         $align = isset( $block_settings['align'] ) ? $block_settings['align'] : '';

//         if( $align == 'center' ){
//             $alignment = 'margin-right: auto;
//             margin-left: auto;';

//         }
//         else if( $align == 'right' ){
//             $alignment = 'margin-left: auto;';

//         }
//         else{
//             $alignment = 'margin-right: auto;';
//         }

//         $styles = "
//             {$row_classname} {$col_classname} {$block_classname}{
//                 width: {$width};
//                 border-color: {$color};
//             }
//         ";

//         $styles = "
//             {$row_classname} {$col_classname} {$block_classname}{
//                 $alignment
//             }
//         ";

//         return $styles;
//     }
// }


if( !function_exists( 'cartify_product_layout_css_spacer' ) ){
    function cartify_product_layout_css_spacer( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $height = isset( $block_settings['height'] ) ? $block_settings['height'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname} {";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_spacer_height_desktop' => !empty( $height['desktop'] ) ? $height['desktop'] : '',
                '--agni_product_layout_spacer_height_laptop' => !empty( $height['laptop'] ) ? $height['laptop'] : '',
                '--agni_product_layout_spacer_height_tab' => !empty( $height['tab'] ) ? $height['tab'] : '',
                '--agni_product_layout_spacer_height_mobile' => !empty( $height['mobile'] ) ? $height['mobile'] : '',
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_breadcrumbs' ) ){
    function cartify_product_layout_css_breadcrumbs( $block_settings, $row_classname = '', $col_classname = '' ){

        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $link_color = isset( $block_settings['link-color'] ) ? $block_settings['link-color'] : '';

        $separator = isset( $block_settings['separator'] ) ? $block_settings['separator'] : '';

        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        // $text_transform = isset( $block_settings['text-transform'] ) ? $block_settings['text-transform'] : '';
        // $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_breadcrumbs_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_breadcrumbs_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_breadcrumbs_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_breadcrumbs_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                '--cartify_breadcrumb_delimiter' => $separator,

                                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,
            ));
            // $styles .= cartify_prepare_css_styles(array(
            //     'text-transform' => $text_transform,
            //     'text-align' => $text_align,
            // ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} a{";
            $styles .= cartify_prepare_css_styles(array(
                'color' => $link_color,
            ));
        $styles .= "}";


        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_title' ) ){
    function cartify_product_layout_css_title( $block_settings, $row_classname = '', $col_classname = '' ){

                $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $line_height = isset( $block_settings['line-height'] ) ? $block_settings['line-height'] : '';
        $text_transform = isset( $block_settings['text-transform'] ) ? $block_settings['text-transform'] : '';
        $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_title_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_title_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_title_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_title_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',
            ));
            $styles .= cartify_prepare_css_styles(array(
                'text-transform' => $text_transform,
                'text-align' => $text_align,
            ));
        $styles .= "}";

                $styles .= "{$row_classname} {$col_classname} {$block_classname} .product_title{";
            $styles .= cartify_prepare_css_styles(array(
                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'line-height' => $line_height,
                'letter-spacing' => $letter_spacing,
            ));
        $styles .= "}";


        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_short_description' ) ){
    function cartify_product_layout_css_short_description( $block_settings, $row_classname = '', $col_classname = '' ){

        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $line_height = isset( $block_settings['line-height'] ) ? $block_settings['line-height'] : '';
        $text_transform = isset( $block_settings['text-transform'] ) ? $block_settings['text-transform'] : '';
        $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                //cartify_font_size_small_3
                '--agni_product_layout_description_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_description_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_description_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_description_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'line-height' => $line_height,
                'letter-spacing' => $letter_spacing,
            ));
            $styles .= cartify_prepare_css_styles(array(
                'text-transform' => $text_transform,
                'text-align' => $text_align,
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_sale' ) ){
    function cartify_product_layout_css_sale( $block_settings, $row_classname = '', $col_classname = '' ){

        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


                $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $content_padding = isset( $block_settings['content-padding'] ) ? $block_settings['content-padding'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        // $text_transform = isset( $block_settings['text-transform'] ) ? $block_settings['text-transform'] : '';
        // $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                //cartify_font_size_small_3
                '--agni_product_layout_sale_text_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_sale_text_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_sale_text_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_sale_text_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,
            ));
            // $styles .= cartify_prepare_css_styles(array(
            //     'text-transform' => $text_transform,
            //     'text-align' => $text_align,
            // ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .onsale{";
            $styles .= cartify_prepare_css_styles(array(
                //cartify_font_size_small_3
                '--agni_product_layout_sale_text_padding_desktop' => !empty( $content_padding['desktop'] ) ? $content_padding['desktop'] : '',
                '--agni_product_layout_sale_text_padding_laptop' => !empty( $content_padding['laptop'] ) ? $content_padding['laptop'] : '',
                '--agni_product_layout_sale_text_padding_tab' => !empty( $content_padding['tab'] ) ? $content_padding['tab'] : '',
                '--agni_product_layout_sale_text_padding_mobile' => !empty( $content_padding['mobile'] ) ? $content_padding['mobile'] : '',

            ));
            $styles .= cartify_prepare_css_styles(array(
                'background-color' => $background_color,
                'border-width' => $border_width,
                'border-color' => $border_color,
                'border-radius' => $border_radius,
            ));
        $styles .= "}";

        return $styles;
    }
}


if( !function_exists( 'cartify_product_layout_css_new' ) ){
    function cartify_product_layout_css_new( $block_settings, $row_classname = '', $col_classname = '' ){

        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


                $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $content_padding = isset( $block_settings['content-padding'] ) ? $block_settings['content-padding'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        // $text_transform = isset( $block_settings['text-transform'] ) ? $block_settings['text-transform'] : '';
        // $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                //cartify_font_size_small_3
                '--agni_product_layout_new_text_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_new_text_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_new_text_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_new_text_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,
            ));
            // $styles .= cartify_prepare_css_styles(array(
            //     'text-transform' => $text_transform,
            //     'text-align' => $text_align,
            // ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-new-label{";
            $styles .= cartify_prepare_css_styles(array(
                //cartify_font_size_small_3
                '--agni_product_layout_new_text_padding_desktop' => !empty( $content_padding['desktop'] ) ? $content_padding['desktop'] : '',
                '--agni_product_layout_new_text_padding_laptop' => !empty( $content_padding['laptop'] ) ? $content_padding['laptop'] : '',
                '--agni_product_layout_new_text_padding_tab' => !empty( $content_padding['tab'] ) ? $content_padding['tab'] : '',
                '--agni_product_layout_new_text_padding_mobile' => !empty( $content_padding['mobile'] ) ? $content_padding['mobile'] : '',

            ));
            $styles .= cartify_prepare_css_styles(array(
                'background-color' => $background_color,
                'border-width' => $border_width,
                'border-color' => $border_color,
                'border-radius' => $border_radius,
            ));
        $styles .= "}";

        return $styles;
    }
}


if( !function_exists( 'cartify_product_layout_css_hot' ) ){
    function cartify_product_layout_css_hot( $block_settings, $row_classname = '', $col_classname = '' ){

        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


                $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $content_padding = isset( $block_settings['content-padding'] ) ? $block_settings['content-padding'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        // $text_transform = isset( $block_settings['text-transform'] ) ? $block_settings['text-transform'] : '';
        // $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                //cartify_font_size_small_3
                '--agni_product_layout_hot_text_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_hot_text_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_hot_text_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_hot_text_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,
            ));
            // $styles .= cartify_prepare_css_styles(array(
            //     'text-transform' => $text_transform,
            //     'text-align' => $text_align,
            // ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-hot-label{";
            $styles .= cartify_prepare_css_styles(array(
                //cartify_font_size_small_3
                '--agni_product_layout_hot_text_padding_desktop' => !empty( $content_padding['desktop'] ) ? $content_padding['desktop'] : '',
                '--agni_product_layout_hot_text_padding_laptop' => !empty( $content_padding['laptop'] ) ? $content_padding['laptop'] : '',
                '--agni_product_layout_hot_text_padding_tab' => !empty( $content_padding['tab'] ) ? $content_padding['tab'] : '',
                '--agni_product_layout_hot_text_padding_mobile' => !empty( $content_padding['mobile'] ) ? $content_padding['mobile'] : '',

            ));
            $styles .= cartify_prepare_css_styles(array(
                'background-color' => $background_color,
                'border-width' => $border_width,
                'border-color' => $border_color,
                'border-radius' => $border_radius,
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_images' ) ){
    function cartify_product_layout_css_images( $block_settings, $row_classname = '', $col_classname = '' ){

                $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $images_size = isset($block_settings['images-size']) ? $block_settings['images-size'] : '';
        $images_width = isset($block_settings['images-width']) ? $block_settings['images-width'] : '';
        $images_height = isset($block_settings['images-height']) ? $block_settings['images-height'] : '';
        $images_gap = isset($block_settings['images-gap']) ? $block_settings['images-gap'] : '';
        $thumbnails_width = isset($block_settings['thumbnails-width']) ? $block_settings['thumbnails-width'] : '';
        $thumbnails_height = isset($block_settings['thumbnails-height']) ? $block_settings['thumbnails-height'] : '';
        $thumbnails_gap = isset($block_settings['thumbnails-gap']) ? $block_settings['thumbnails-gap'] : '';

        $styles = '';


        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles( array(
                '--agni_product_layout_images_width' => $images_width,
                '--agni_product_layout_images_height' => $images_height,

            ));

            // $styles .= cartify_prepare_css_styles( array(
            //     'text-align' => $text_align
            // ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .woocommerce-product-gallery{";
            $styles .= cartify_prepare_css_styles( array(
                '--agni_product_layout_images_nav_image_width_desktop' => !empty( $thumbnails_width['desktop'] ) ? $thumbnails_width['desktop'] : '',
                '--agni_product_layout_images_nav_image_width_laptop' => !empty( $thumbnails_width['laptop'] ) ? $thumbnails_width['laptop'] : '',
                '--agni_product_layout_images_nav_image_width_tab' => !empty( $thumbnails_width['tab'] ) ? $thumbnails_width['tab'] : '',
                '--agni_product_layout_images_nav_image_width_mobile' => !empty( $thumbnails_width['mobile'] ) ? $thumbnails_width['mobile'] : '',

                '--agni_product_layout_images_nav_image_height_desktop' => !empty( $thumbnails_height['desktop'] ) ? $thumbnails_height['desktop'] : '',
                '--agni_product_layout_images_nav_image_height_laptop' => !empty( $thumbnails_height['laptop'] ) ? $thumbnails_height['laptop'] : '',
                '--agni_product_layout_images_nav_image_height_tab' => !empty( $thumbnails_height['tab'] ) ? $thumbnails_height['tab'] : '',
                '--agni_product_layout_images_nav_image_height_mobile' => !empty( $thumbnails_height['mobile'] ) ? $thumbnails_height['mobile'] : '',

                '--agni_product_layout_images_gap_desktop' => !empty( $images_gap['desktop'] ) ? $images_gap['desktop'] : '',
                '--agni_product_layout_images_gap_laptop' => !empty( $images_gap['laptop'] ) ? $images_gap['laptop'] : '',
                '--agni_product_layout_images_gap_tab' => !empty( $images_gap['tab'] ) ? $images_gap['tab'] : '',
                '--agni_product_layout_images_gap_mobile' => !empty( $images_gap['mobile'] ) ? $images_gap['mobile'] : '',

                '--cartify_woocommerce_product_gallery_nav_gap' => $thumbnails_gap,

            ));

            // $styles .= cartify_prepare_css_styles( array(
            //     'text-align' => $text_align
            // ));
        $styles .= "}";

        return $styles;
    }
}


if( !function_exists( 'cartify_product_layout_css_video' ) ){
    function cartify_product_layout_css_video( $block_settings, $row_classname = '', $col_classname = '' ){

                $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $gap = isset( $block_settings['gap'] ) ? $block_settings['gap'] : '';
        $icon_size = isset( $block_settings['icon-size'] ) ? $block_settings['icon-size'] : '';
        $icon_color = isset( $block_settings['icon-color'] ) ? $block_settings['icon-color'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';

        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';

        $styles = '';


        if( isset( $block_settings['icon-show'] ) ){
            $icon_show = cartify_prepare_responsive_values($block_settings['icon-show'], true);
            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_layout_video_icon_visibility_desktop' => empty( $icon_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_video_icon_visibility_laptop' => empty( $icon_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_video_icon_visibility_tab' => empty( $icon_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_video_icon_visibility_mobile' => empty( $icon_show['mobile'] ) ? 'none' : '',

                ));
            $styles .= "}";
        }

        if( isset( $block_settings['text-show'] ) ){
            $text_show = cartify_prepare_responsive_values($block_settings['text-show'], true); 

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_layout_video_text_visibility_desktop' => empty( $text_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_video_text_visibility_laptop' => empty( $text_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_video_text_visibility_tab' => empty( $text_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_video_text_visibility_mobile' => empty( $text_show['mobile'] ) ? 'none' : '',

                ));
            $styles .= "}";
        }

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-video__button{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_video_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_video_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_video_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_video_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                                '--agni_product_layout_video_icon_size_desktop' => !empty( $icon_size['desktop'] ) ? $icon_size['desktop'] : '',
                '--agni_product_layout_video_icon_size_laptop' => !empty( $icon_size['laptop'] ) ? $icon_size['laptop'] : '',
                '--agni_product_layout_video_icon_size_tab' => !empty( $icon_size['tab'] ) ? $icon_size['tab'] : '',
                '--agni_product_layout_video_icon_size_mobile' => !empty( $icon_size['mobile'] ) ? $icon_size['mobile'] : '',

                '--cartify_product_product_media_gap' => $gap,
                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,

                'background-color' => $background_color,
                'border-color' => $border_color,
                'border-width' => $border_width,
                'border-radius' => $border_radius,
            ));

        $styles .= "}";

        return $styles;
    }
}


if( !function_exists( 'cartify_product_layout_css_360' ) ){
    function cartify_product_layout_css_360( $block_settings, $row_classname = '', $col_classname = '' ){

                $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $gap = isset( $block_settings['gap'] ) ? $block_settings['gap'] : '';
        $icon_size = isset( $block_settings['icon-size'] ) ? $block_settings['icon-size'] : '';
        $icon_color = isset( $block_settings['icon-color'] ) ? $block_settings['icon-color'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';

        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';

        $styles = '';




        if( isset( $block_settings['icon-show'] ) ){
            $icon_show = cartify_prepare_responsive_values($block_settings['icon-show'], true);
            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_layout_360_icon_visibility_desktop' => empty( $icon_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_360_icon_visibility_laptop' => empty( $icon_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_360_icon_visibility_tab' => empty( $icon_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_360_icon_visibility_mobile' => empty( $icon_show['mobile'] ) ? 'none' : '',

                ));
            $styles .= "}";
        }

        if( isset( $block_settings['text-show'] ) ){
            $text_show = cartify_prepare_responsive_values($block_settings['text-show'], true); 

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_layout_360_text_visibility_desktop' => empty( $text_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_360_text_visibility_laptop' => empty( $text_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_360_text_visibility_tab' => empty( $text_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_360_text_visibility_mobile' => empty( $text_show['mobile'] ) ? 'none' : '',

                ));
            $styles .= "}";
        }

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-threesixty__button{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_360_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_360_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_360_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_360_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                                '--agni_product_layout_360_icon_size_desktop' => !empty( $icon_size['desktop'] ) ? $icon_size['desktop'] : '',
                '--agni_product_layout_360_icon_size_laptop' => !empty( $icon_size['laptop'] ) ? $icon_size['laptop'] : '',
                '--agni_product_layout_360_icon_size_tab' => !empty( $icon_size['tab'] ) ? $icon_size['tab'] : '',
                '--agni_product_layout_360_icon_size_mobile' => !empty( $icon_size['mobile'] ) ? $icon_size['mobile'] : '',

                '--cartify_product_product_media_gap' => $gap,
                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,

                'background-color' => $background_color,
                'border-color' => $border_color,
                'border-width' => $border_width,
                'border-radius' => $border_radius,
            ));

        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_brand' ) ){
    function cartify_product_layout_css_brand( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        $by_text_color = isset( $block_settings['by-text-color'] ) ? $block_settings['by-text-color'] : '';
        $by_text_font_family = isset( $block_settings['by-text-font-choice']['font-family'] ) ? $block_settings['by-text-font-choice']['font-family'] : '';
        $by_text_font_weight = isset( $block_settings['by-text-font-choice']['font-weight'] ) ? $block_settings['by-text-font-choice']['font-weight'] : '';
        $by_text_font_size = isset( $block_settings['by-text-font-size'] ) ? $block_settings['by-text-font-size'] : '';
        $by_text_letter_spacing = isset( $block_settings['by-text-letter-spacing'] ) ? $block_settings['by-text-letter-spacing'] : '';
        $by_text_text_transform = isset( $block_settings['by-text-text-transform'] ) ? $block_settings['by-text-text-transform'] : '';

        $brand_color = isset( $block_settings['brand-color'] ) ? $block_settings['brand-color'] : '';
        $brand_font_family = isset( $block_settings['brand-font-choice']['font-family'] ) ? $block_settings['brand-font-choice']['font-family'] : '';
        $brand_font_weight = isset( $block_settings['brand-font-choice']['font-weight'] ) ? $block_settings['brand-font-choice']['font-weight'] : '';
        $brand_font_size = isset( $block_settings['brand-font-size'] ) ? $block_settings['brand-font-size'] : '';
        $brand_letter_spacing = isset( $block_settings['brand-letter-spacing'] ) ? $block_settings['brand-letter-spacing'] : '';
        $brand_text_transform = isset( $block_settings['brand-text-transform'] ) ? $block_settings['brand-text-transform'] : '';

        $brand_logo_background_color = isset( $block_settings['brand-logo-bg-color'] ) ? $block_settings['brand-logo-bg-color'] : '';
        $brand_logo_border_width = isset( $block_settings['brand-logo-border-width'] ) ? $block_settings['brand-logo-border-width'] : '';
        $brand_logo_border_color = isset( $block_settings['brand-logo-border-color'] ) ? $block_settings['brand-logo-border-color'] : '';
        $brand_logo_border_radius = isset( $block_settings['brand-logo-border-radius'] ) ? $block_settings['brand-logo-border-radius'] : '';

        $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $styles = '';

        if( isset( $block_settings['by-text-show'] ) ){
            $brand_logo_show = cartify_prepare_responsive_values($block_settings['brand-logo-show'], false);

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_brand_by_text_visibility_desktop' => empty( $by_text_show['desktop'] ) ? 'none' : '',
                    '--agni_product_brand_by_text_visibility_laptop' => empty( $by_text_show['laptop'] ) ? 'none' : '',
                    '--agni_product_brand_by_text_visibility_tab' => empty( $by_text_show['tab'] ) ? 'none' : '',
                    '--agni_product_brand_by_text_visibility_mobile' => empty( $by_text_show['mobile'] ) ? 'none' : '',


                ));
            $styles .= "}";
        }

        if( isset( $block_settings['by-text-show'] ) ){
            $by_text_show = cartify_prepare_responsive_values($block_settings['by-text-show'], true);

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_brand_brand_name_visibility_desktop' => empty( $brand_show['desktop'] ) ? 'none' : '',
                    '--agni_product_brand_brand_name_visibility_laptop' => empty( $brand_show['laptop'] ) ? 'none' : '',
                    '--agni_product_brand_brand_name_visibility_tab' => empty( $brand_show['tab'] ) ? 'none' : '',
                    '--agni_product_brand_brand_name_visibility_mobile' => empty( $brand_show['mobile'] ) ? 'none' : '',


                ));
            $styles .= "}";
        }
        if( isset( $block_settings['brand-show'] ) ){
            $brand_show = cartify_prepare_responsive_values($block_settings['brand-show'], true);

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_brand_brand_logo_visibility_desktop' => !empty( $brand_logo_show['desktop'] ) ? 'unset' : '',
                    '--agni_product_brand_brand_logo_visibility_laptop' => !empty( $brand_logo_show['laptop'] ) ? 'unset' : '',
                    '--agni_product_brand_brand_logo_visibility_tab' => !empty( $brand_logo_show['tab'] ) ? 'unset' : '',
                    '--agni_product_brand_brand_logo_visibility_mobile' => !empty( $brand_logo_show['mobile'] ) ? 'unset' : '',

                ));
            $styles .= "}";
        }

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-brand__by-text{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $by_text_font_family,
                'font-weight' =>  $by_text_font_weight,
                'font-variation-settings' =>  !empty($by_text_font_weight) ? '"wght"' . $by_text_font_weight : '',
                'letter-spacing' => $by_text_letter_spacing,
                'color' => $by_text_color,
                'text-transform' => $by_text_text_transform,

                '--agni_product_brand_by_text_font_size_desktop' => !empty( $by_text_font_size['desktop'] ) ? $by_text_font_size['desktop'] : '',
                '--agni_product_brand_by_text_font_size_laptop' => !empty( $by_text_font_size['laptop'] ) ? $by_text_font_size['laptop'] : '',
                '--agni_product_brand_by_text_font_size_tab' => !empty( $by_text_font_size['tab'] ) ? $by_text_font_size['tab'] : '',
                '--agni_product_brand_by_text_font_size_mobile' => !empty( $by_text_font_size['mobile'] ) ? $by_text_font_size['mobile'] : '',
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-brand__brand-name{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $brand_font_family,
                'font-weight' =>  $brand_font_weight,
                'font-variation-settings' =>  !empty($brand_font_weight) ? '"wght"' . $brand_font_weight : '',
                'letter-spacing' => $brand_letter_spacing,
                'color' => $brand_color,
                'text-transform' => $brand_text_transform,

                '--agni_product_brand_brand_name_font_size_desktop' => !empty( $brand_font_size['desktop'] ) ? $brand_font_size['desktop'] : '',
                '--agni_product_brand_brand_name_font_size_laptop' => !empty( $brand_font_size['laptop'] ) ? $brand_font_size['laptop'] : '',
                '--agni_product_brand_brand_name_font_size_tab' => !empty( $brand_font_size['tab'] ) ? $brand_font_size['tab'] : '',
                '--agni_product_brand_brand_name_font_size_mobile' => !empty( $brand_font_size['mobile'] ) ? $brand_font_size['mobile'] : '',
            ));
        $styles .= "}";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-brand__brand-logo{";
            $styles .= cartify_prepare_css_styles(array(
                '--cartify_background_color_lite' => $brand_logo_background_color,

                'border-width' => $brand_logo_border_width,
                'border-color' => $brand_logo_border_color,
                'border-radius' => $brand_logo_border_radius,

                            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_rating' ) ){
    function cartify_product_layout_css_rating( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        // print_r( $star_show );

        $star_size = isset( $block_settings['star-size'] ) ? $block_settings['star-size'] : '';
        $star_color = isset( $block_settings['star-color'] ) ? $block_settings['star-color'] : '';
        $star_base_color = isset( $block_settings['star-base-color'] ) ? $block_settings['star-base-color'] : '';


        $avg_bg_color = isset( $block_settings['avg-bg-color'] ) ? $block_settings['avg-bg-color'] : '';
        $avg_border_width = isset( $block_settings['avg-border-width'] ) ? $block_settings['avg-border-width'] : '';
        $avg_border_color = isset( $block_settings['avg-border-color'] ) ? $block_settings['avg-border-color'] : '';
        $avg_border_radius = isset( $block_settings['avg-border-radius'] ) ? $block_settings['avg-border-radius'] : '';
        $avg_color = isset( $block_settings['avg-color'] ) ? $block_settings['avg-color'] : '';
        $avg_font_family = isset( $block_settings['avg-font-choice']['font-family'] ) ? $block_settings['avg-font-choice']['font-family'] : '';
        $avg_font_weight = isset( $block_settings['avg-font-choice']['font-weight'] ) ? $block_settings['avg-font-choice']['font-weight'] : '';
        $avg_font_size = isset( $block_settings['avg-font-size'] ) ? $block_settings['avg-font-size'] : '';
        $avg_letter_spacing = isset( $block_settings['avg-letter-spacing'] ) ? $block_settings['avg-letter-spacing'] : '';

        $review_color = isset( $block_settings['review-color'] ) ? $block_settings['review-color'] : '';
        $review_font_family = isset( $block_settings['review-font-choice']['font-family'] ) ? $block_settings['review-font-choice']['font-family'] : '';
        $review_font_weight = isset( $block_settings['review-font-choice']['font-weight'] ) ? $block_settings['review-font-choice']['font-weight'] : '';
        $review_font_size = isset( $block_settings['review-font-size'] ) ? $block_settings['review-font-size'] : '';
        $review_letter_spacing = isset( $block_settings['review-letter-spacing'] ) ? $block_settings['review-letter-spacing'] : '';
        $review_text_transform = isset( $block_settings['review-text-transform'] ) ? $block_settings['review-text-transform'] : '';


        $rating_color = isset( $block_settings['rating-color'] ) ? $block_settings['rating-color'] : '';
        $rating_font_family = isset( $block_settings['rating-font-choice']['font-family'] ) ? $block_settings['rating-font-choice']['font-family'] : '';
        $rating_font_weight = isset( $block_settings['rating-font-choice']['font-weight'] ) ? $block_settings['rating-font-choice']['font-weight'] : '';
        $rating_font_size = isset( $block_settings['rating-font-size'] ) ? $block_settings['rating-font-size'] : '';
        $rating_letter_spacing = isset( $block_settings['rating-letter-spacing'] ) ? $block_settings['rating-letter-spacing'] : '';
        $rating_text_transform = isset( $block_settings['rating-text-transform'] ) ? $block_settings['rating-text-transform'] : '';

        $styles = '';

        if( isset($block_settings['star-show']) ){
            $star_show = cartify_prepare_responsive_values($block_settings['star-show'], true);

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_rating_star_visibility_desktop' => empty( $star_show['desktop'] ) ? 'none' : '',
                    '--agni_product_rating_star_visibility_laptop' => empty( $star_show['laptop'] ) ? 'none' : '',
                    '--agni_product_rating_star_visibility_tab' => empty( $star_show['tab'] ) ? 'none' : '',
                    '--agni_product_rating_star_visibility_mobile' => empty( $star_show['mobile'] ) ? 'none' : '',
                ));

            $styles .= "}";
        }


        if( isset($block_settings['avg-show']) ){
            $avg_show = cartify_prepare_responsive_values($block_settings['avg-show'], true);

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_rating_text_visibility_desktop' => empty( $avg_show['desktop'] ) ? 'none' : '',
                    '--agni_product_rating_text_visibility_laptop' => empty( $avg_show['laptop'] ) ? 'none' : '',
                    '--agni_product_rating_text_visibility_tab' => empty( $avg_show['tab'] ) ? 'none' : '',
                    '--agni_product_rating_text_visibility_mobile' => empty( $avg_show['mobile'] ) ? 'none' : '',
                ));

            $styles .= "}";
        }


        if( isset($block_settings['rating-show']) ){
            $rating_show = cartify_prepare_responsive_values($block_settings['rating-show']);

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_rating_count_visibility_desktop' => !empty( $rating_show['desktop'] ) ? 'unset' : '',
                    '--agni_product_rating_count_visibility_laptop' => !empty( $rating_show['laptop'] ) ? 'unset' : '',
                    '--agni_product_rating_count_visibility_tab' => !empty( $rating_show['tab'] ) ? 'unset' : '',
                    '--agni_product_rating_count_visibility_mobile' => !empty( $rating_show['mobile'] ) ? 'unset' : '',

                ));

            $styles .= "}";
        }

        if( isset($block_settings['review-show']) ){
            $review_show = cartify_prepare_responsive_values($block_settings['review-show'], true);

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_rating_review_visibility_desktop' => empty( $review_show['desktop'] ) ? 'none' : '',
                    '--agni_product_rating_review_visibility_laptop' => empty( $review_show['laptop'] ) ? 'none' : '',
                    '--agni_product_rating_review_visibility_tab' => empty( $review_show['tab'] ) ? 'none' : '',
                    '--agni_product_rating_review_visibility_mobile' => empty( $review_show['mobile'] ) ? 'none' : '',

                ));

            $styles .= "}";
        }



        $styles .= "{$row_classname} {$col_classname} {$block_classname} .star-rating__star{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_rating_star_size_desktop' => !empty( $star_size['desktop'] ) ? $star_size['desktop'] : '',
                '--agni_product_rating_star_size_laptop' => !empty( $star_size['laptop'] ) ? $star_size['laptop'] : '',
                '--agni_product_rating_star_size_tab' => !empty( $star_size['tab'] ) ? $star_size['tab'] : '',
                '--agni_product_rating_star_size_mobile' => !empty( $star_size['mobile'] ) ? $star_size['mobile'] : '',

                '--agni_product_rating_star_base_color' => $star_base_color,
                '--agni_product_rating_star_color' => $star_color,
                'color' => $star_color
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .star-rating__text{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_rating_text_font_size_desktop' => !empty( $avg_font_size['desktop'] ) ? $avg_font_size['desktop'] : '',
                '--agni_product_rating_text_font_size_laptop' => !empty( $avg_font_size['laptop'] ) ? $avg_font_size['laptop'] : '',
                '--agni_product_rating_text_font_size_tab' => !empty( $avg_font_size['tab'] ) ? $avg_font_size['tab'] : '',
                '--agni_product_rating_text_font_size_mobile' => !empty( $avg_font_size['mobile'] ) ? $avg_font_size['mobile'] : '',

                'font-family' => $avg_font_family,
                'font-weight' =>  $avg_font_weight,
                'font-variation-settings' =>  !empty($avg_font_weight) ? '"wght"' . $avg_font_weight : '',
                'letter-spacing' => $avg_letter_spacing,
                'color' => $avg_color,

                'border-width' => $avg_border_width,
                'border-color' => $avg_border_color,
                'border-radius' => $avg_border_radius,
                'background-color' => $avg_bg_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .star-rating__count{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_rating_count_font_size_desktop' => !empty( $rating_font_size['desktop'] ) ? $rating_font_size['desktop'] : '',
                '--agni_product_rating_count_font_size_laptop' => !empty( $rating_font_size['laptop'] ) ? $rating_font_size['laptop'] : '',
                '--agni_product_rating_count_font_size_tab' => !empty( $rating_font_size['tab'] ) ? $rating_font_size['tab'] : '',
                '--agni_product_rating_count_font_size_mobile' => !empty( $rating_font_size['mobile'] ) ? $rating_font_size['mobile'] : '',

                'font-family' => $rating_font_family,
                'font-weight' =>  $rating_font_weight,
                'font-variation-settings' =>  !empty($rating_font_weight) ? '"wght"' . $rating_font_weight : '',
                'letter-spacing' => $rating_letter_spacing,

                'color' => $rating_color,
                'text-transform' => $rating_text_transform
            ));

        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .woocommerce-review-link{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_rating_review_font_size_desktop' => !empty( $review_font_size['desktop'] ) ? $review_font_size['desktop'] : '',
                '--agni_product_rating_review_font_size_laptop' => !empty( $review_font_size['laptop'] ) ? $review_font_size['laptop'] : '',
                '--agni_product_rating_review_font_size_tab' => !empty( $review_font_size['tab'] ) ? $review_font_size['tab'] : '',
                '--agni_product_rating_review_font_size_mobile' => !empty( $review_font_size['mobile'] ) ? $review_font_size['mobile'] : '',

                'font-family' => $review_font_family,
                'font-weight' =>  $review_font_weight,
                'font-variation-settings' =>  !empty($review_font_weight) ? '"wght"' . $review_font_weight : '',
                'letter-spacing' => $review_letter_spacing,

                'color' => $review_color,
                'text-transform' => $review_text_transform
            ));
        $styles .= "}";


        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_price' ) ){
    function cartify_product_layout_css_price( $block_settings, $row_classname = '', $col_classname = '' ){

        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $text_transform = isset( $block_settings['text-transform'] ) ? $block_settings['text-transform'] : '';

        $old_color = isset( $block_settings['old-color'] ) ? $block_settings['old-color'] : '';
        $old_font_size = isset( $block_settings['old-font-size'] ) ? $block_settings['old-font-size'] : '';


        $styles = '';

                $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles( array(

                '--agni_product_price_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_price_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_price_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_price_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,

                'text-transform' => $text_transform
            ));
        $styles .= "}";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} .price del{";
            $styles .= cartify_prepare_css_styles( array(

                '--agni_product_price_old_font_size_desktop' => !empty( $old_font_size['desktop'] ) ? $old_font_size['desktop'] : '',
                '--agni_product_price_old_font_size_laptop' => !empty( $old_font_size['laptop'] ) ? $old_font_size['laptop'] : '',
                '--agni_product_price_old_font_size_tab' => !empty( $old_font_size['tab'] ) ? $old_font_size['tab'] : '',
                '--agni_product_price_old_font_size_mobile' => !empty( $old_font_size['mobile'] ) ? $old_font_size['mobile'] : '',

                'color' => $old_color

                            ));

        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_featured' ) ){
    function cartify_product_layout_css_featured( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_featured_label_background_color' => $background_color,
                '--agni_product_featured_label_border_color' => $border_color,
                '--agni_product_featured_label_border_width' => $border_width,

                '--agni_product_featured_label_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_featured_label_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_featured_label_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_featured_label_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                // 'line-height' => $line_height,
                'letter-spacing' => $letter_spacing,
            ));
            // $styles .= cartify_prepare_css_styles(array(
            //     'text-transform' => $text_transform,
            //     'text-align' => $text_align,
            // ));
        $styles .= "}";
        $styles .= "{$row_classname} {$col_classname} {$block_classname} span{";
            $styles .= cartify_prepare_css_styles(array(
                'background-color' => $background_color
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_countdown' ) ){
    function cartify_product_layout_css_countdown( $block_settings, $row_classname = '', $col_classname = '' ){

        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';

        $label_color = isset( $block_settings['label-color'] ) ? $block_settings['label-color'] : '';
        $label_font_family = isset( $block_settings['label-font-choice']['font-family'] ) ? $block_settings['label-font-choice']['font-family'] : '';
        $label_font_weight = isset( $block_settings['label-font-choice']['font-weight'] ) ? $block_settings['label-font-choice']['font-weight'] : '';
        $label_font_size = isset( $block_settings['label-font-size'] ) ? $block_settings['label-font-size'] : '';
        $label_letter_spacing = isset( $block_settings['label-letter-spacing'] ) ? $block_settings['label-letter-spacing'] : '';
        $label_text_transform = isset( $block_settings['label-text-transform'] ) ? $block_settings['label-text-transform'] : '';

        $styles = '';

        if( isset($block_settings['label-show']) ){
            $label_show = cartify_prepare_responsive_values($block_settings['label-show'], true);

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_countdown_visibility_desktop' => empty( $label_show['desktop'] ) ? 'none' : '',
                    '--agni_product_countdown_visibility_laptop' => empty( $label_show['laptop'] ) ? 'none' : '',
                    '--agni_product_countdown_visibility_tab' => empty( $label_show['tab'] ) ? 'none' : '',
                    '--agni_product_countdown_visibility_mobile' => empty( $label_show['mobile'] ) ? 'none' : '',
                ));

            $styles .= "}";
        }

        $styles .= "{$row_classname} {$col_classname} {$block_classname} span{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_countdown_background_color' => $background_color,
                '--agni_product_countdown_border_color' => $border_color,
                '--agni_product_countdown_border_width' => $border_width,

                '--agni_product_countdown_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_countdown_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_countdown_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_countdown_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                // 'line-height' => $line_height,
                'letter-spacing' => $letter_spacing,
            ));
            // $styles .= cartify_prepare_css_styles(array(
            //     'background-color' => $background_color,
            //     'border-width' => $border_width,
            //     'border-color' => $border_color,
            // ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-sale-countdown-holder__label{";
            $styles .= cartify_prepare_css_styles(array(

                '--agni_product_countdown_label_font_size_desktop' => !empty( $label_font_size['desktop'] ) ? $label_font_size['desktop'] : '',
                '--agni_product_countdown_label_font_size_laptop' => !empty( $label_font_size['laptop'] ) ? $label_font_size['laptop'] : '',
                '--agni_product_countdown_label_font_size_tab' => !empty( $label_font_size['tab'] ) ? $label_font_size['tab'] : '',
                '--agni_product_countdown_label_font_size_mobile' => !empty( $label_font_size['mobile'] ) ? $label_font_size['mobile'] : '',

                                'color' => $label_color,
                'font-family' => $label_font_family,
                'font-weight' =>  $label_font_weight,
                'font-variation-settings' =>  !empty($label_font_weight) ? '"wght"' . $label_font_weight : '',
                // 'line-height' => $line_height,
                'letter-spacing' => $label_letter_spacing,

                'text-transform' => $label_text_transform
            ));

        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_features' ) ){
    function cartify_product_layout_css_features( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $background_color = isset( $block_settings['feature-bg-color'] ) ? $block_settings['feature-bg-color'] : '';
        $border_width = isset( $block_settings['feature-border-width'] ) ? $block_settings['feature-border-width'] : '';
        $border_color = isset( $block_settings['feature-border-color'] ) ? $block_settings['feature-border-color'] : '';
        $border_radius = isset( $block_settings['feature-border-radius'] ) ? $block_settings['feature-border-radius'] : '';
        $gap = isset( $block_settings['feature-gap'] ) ? $block_settings['feature-gap'] : '';
        $feature_width = isset( $block_settings['feature-width'] ) ? $block_settings['feature-width'] : '';
        $feature_padding = isset( $block_settings['feature-padding'] ) ? $block_settings['feature-padding'] : '';

        $icon_size = isset( $block_settings['icon-size'] ) ? $block_settings['icon-size'] : '';
        $icon_color = isset( $block_settings['icon-color'] ) ? $block_settings['icon-color'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $line_height = isset( $block_settings['line-height'] ) ? $block_settings['line-height'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-features-list{";
            $styles .= cartify_prepare_css_styles(array(
                '--cartify_product_features_alignment' => $text_align,
                '--cartify_product_features_column_width_desktop' => !empty( $feature_width['desktop'] ) ? $feature_width['desktop'] : '',
                '--cartify_product_features_column_width_laptop' => !empty( $feature_width['laptop'] ) ? $feature_width['laptop'] : '',
                '--cartify_product_features_column_width_tab' => !empty( $feature_width['tab'] ) ? $feature_width['tab'] : '',
                '--cartify_product_features_column_width_mobile' => !empty( $feature_width['mobile'] ) ? $feature_width['mobile'] : '',
                '--cartify_product_features_icon_size' => $icon_size,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-features-list-item{";
            $styles .= cartify_prepare_css_styles(array(
                'cartify_product_features_item_gap' => $gap,
                'cartify_product_features_item_padding_desktop' => !empty( $feature_padding['desktop'] ) ? $feature_padding['desktop'] : '',
                'cartify_product_features_item_padding_laptop' => !empty( $feature_padding['laptop'] ) ? $feature_padding['laptop'] : '',
                'cartify_product_features_item_padding_tab' => !empty( $feature_padding['tab'] ) ? $feature_padding['tab'] : '',
                'cartify_product_features_item_padding_mobile' => !empty( $feature_padding['mobile'] ) ? $feature_padding['mobile'] : '',
            ));
            $styles .= cartify_prepare_css_styles(array(
                'background-color' => $background_color,
                'border-width' => $border_width,
                'border-color' => $border_color,
                'border-radius' => $border_radius,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-features-list-item__icon{";
            $styles .= cartify_prepare_css_styles(array(
                'color' => $icon_color,
            ));

                $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-features-list-item__text{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_features_text_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_features_text_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_features_text_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_features_text_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'line-height' => $line_height,
                'letter-spacing' => $letter_spacing,
            ));

                $styles .= "}";

                return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_add_to_cart' ) ){
    function cartify_product_layout_css_add_to_cart( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        $quantity_label_show = isset( $block_settings['quantity-label-show'] ) ? $block_settings['quantity-label-show'] : true;
        $variation_value_show = isset( $block_settings['variations-value-show'] ) ? $block_settings['variations-value-show'] : true;
        $stock_show = isset( $block_settings['stock-show'] ) ? $block_settings['stock-show'] : false;

        $variations_label_color = isset( $block_settings['variations-label-color'] ) ? $block_settings['variations-label-color'] : '';
        $variations_label_font_family = isset( $block_settings['variations-label-font-choice']['font-family'] ) ? $block_settings['variations-label-font-choice']['font-family'] : '';
        $variations_label_font_weight = isset( $block_settings['variations-label-font-choice']['font-weight'] ) ? $block_settings['variations-label-font-choice']['font-weight'] : '';
        $variations_label_font_size = isset( $block_settings['variations-label-font-size'] ) ? $block_settings['variations-label-font-size'] : '';
        $variations_label_letter_spacing = isset( $block_settings['variations-label-letter-spacing'] ) ? $block_settings['variations-label-letter-spacing'] : '';
        $variations_label_text_transform = isset( $block_settings['variations-label-text-transform'] ) ? $block_settings['variations-label-text-transform'] : '';

        $variations_value_color = isset( $block_settings['variations-value-color'] ) ? $block_settings['variations-value-color'] : '';
        $variations_value_font_family = isset( $block_settings['variations-value-font-choice']['font-family'] ) ? $block_settings['variations-value-font-choice']['font-family'] : '';
        $variations_value_font_weight = isset( $block_settings['variations-value-font-choice']['font-weight'] ) ? $block_settings['variations-value-font-choice']['font-weight'] : '';
        $variations_value_font_size = isset( $block_settings['variations-value-font-size'] ) ? $block_settings['variations-value-font-size'] : '';
        $variations_value_letter_spacing = isset( $block_settings['variations-value-letter-spacing'] ) ? $block_settings['variations-value-letter-spacing'] : '';
        $variations_value_text_transform = isset( $block_settings['variations-value-text-transform'] ) ? $block_settings['variations-value-text-transform'] : '';


                $variations_price_color = isset( $block_settings['variations-price-color'] ) ? $block_settings['variations-price-color'] : '';
        $variations_price_font_family = isset( $block_settings['variations-price-font-choice']['font-family'] ) ? $block_settings['variations-price-font-choice']['font-family'] : '';
        $variations_price_font_weight = isset( $block_settings['variations-price-font-choice']['font-weight'] ) ? $block_settings['variations-price-font-choice']['font-weight'] : '';
        $variations_price_font_size = isset( $block_settings['variations-price-font-size'] ) ? $block_settings['variations-price-font-size'] : '';
        $variations_price_letter_spacing = isset( $block_settings['variations-price-letter-spacing'] ) ? $block_settings['variations-price-letter-spacing'] : '';
        $variations_price_text_transform = isset( $block_settings['variations-price-text-transform'] ) ? $block_settings['variations-price-text-transform'] : '';
        $variations_price_old_color = isset( $block_settings['variations-price-old-color'] ) ? $block_settings['variations-price-old-color'] : '';
        $variations_price_old_font_size = isset( $block_settings['variations-price-old-font-size'] ) ? $block_settings['variations-price-old-font-size'] : '';

        $atc_font_family = isset( $block_settings['atc-font-choice']['font-family'] ) ? $block_settings['atc-font-choice']['font-family'] : '';
        $atc_font_weight = isset( $block_settings['atc-font-choice']['font-weight'] ) ? $block_settings['atc-font-choice']['font-weight'] : '';
        $atc_font_size = isset( $block_settings['atc-font-size'] ) ? $block_settings['atc-font-size'] : '';
        $atc_letter_spacing = isset( $block_settings['atc-letter-spacing'] ) ? $block_settings['atc-letter-spacing'] : '';
        $atc_text_transform = isset( $block_settings['atc-text-transform'] ) ? $block_settings['atc-text-transform'] : '';

        $atc_btn_color = isset( $block_settings['atc-btn-color'] ) ? $block_settings['atc-btn-color'] : '';
        $atc_btn_bg_color = isset( $block_settings['atc-btn-bg-color'] ) ? $block_settings['atc-btn-bg-color'] : '';
        $atc_btn_border_color = isset( $block_settings['atc-btn-border-color'] ) ? $block_settings['atc-btn-border-color'] : '';
        $atc_btn_hover_color = isset( $block_settings['atc-btn-hover-color'] ) ? $block_settings['atc-btn-hover-color'] : '';
        $atc_btn_hover_bg_color = isset( $block_settings['atc-btn-hover-bg-color'] ) ? $block_settings['atc-btn-hover-bg-color'] : '';
        $atc_btn_hover_border_color = isset( $block_settings['atc-btn-hover-border-color'] ) ? $block_settings['atc-btn-hover-border-color'] : '';
        $atc_btn_border_radius = isset( $block_settings['atc-btn-border-radius'] ) ? $block_settings['atc-btn-border-radius'] : '';
        $atc_btn_padding = isset( $block_settings['atc-btn-padding'] ) ? $block_settings['atc-btn-padding'] : '';


        $bn_font_family = isset( $block_settings['bn-font-choice']['font-family'] ) ? $block_settings['bn-font-choice']['font-family'] : '';
        $bn_font_weight = isset( $block_settings['bn-font-choice']['font-weight'] ) ? $block_settings['bn-font-choice']['font-weight'] : '';
        $bn_font_size = isset( $block_settings['bn-font-size'] ) ? $block_settings['bn-font-size'] : '';
        $bn_letter_spacing = isset( $block_settings['bn-letter-spacing'] ) ? $block_settings['bn-letter-spacing'] : '';
        $bn_text_transform = isset( $block_settings['bn-text-transform'] ) ? $block_settings['bn-text-transform'] : '';

        $bn_btn_color = isset( $block_settings['bn-btn-color'] ) ? $block_settings['bn-btn-color'] : '';
        $bn_btn_bg_color = isset( $block_settings['bn-btn-bg-color'] ) ? $block_settings['bn-btn-bg-color'] : '';
        $bn_btn_border_color = isset( $block_settings['bn-btn-border-color'] ) ? $block_settings['bn-btn-border-color'] : '';
        $bn_btn_hover_color = isset( $block_settings['bn-btn-hover-color'] ) ? $block_settings['bn-btn-hover-color'] : '';
        $bn_btn_hover_bg_color = isset( $block_settings['bn-btn-hover-bg-color'] ) ? $block_settings['bn-btn-hover-bg-color'] : '';
        $bn_btn_hover_border_color = isset( $block_settings['bn-btn-hover-border-color'] ) ? $block_settings['bn-btn-hover-border-color'] : '';
        $bn_btn_border_radius = isset( $block_settings['bn-btn-border-radius'] ) ? $block_settings['bn-btn-border-radius'] : '';
        $bn_btn_padding = isset( $block_settings['bn-btn-padding'] ) ? $block_settings['bn-btn-padding'] : '';
        //variations-label-font-choice

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles( array(
                '--agni_product_layout_add_to_cart_stock_visibility' => $stock_show ? 'unset' : '',
                '--agni_product_layout_add_to_cart_variation_value_visibility' => !$variation_value_show ? 'none' : '',
                '--agni_product_layout_add_to_cart_qty_label_visibility' => !$quantity_label_show ? 'none' : '',
            ));

        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .variations_form label{";
            $styles .= cartify_prepare_css_styles( array(
                'color' => $variations_label_color,
                'font-family' => $variations_label_font_family,
                'font-weight' =>  $variations_label_font_weight,
                'font-variation-settings' =>  !empty($variations_label_font_weight) ? '"wght"' . $variations_label_font_weight : '',
                'letter-spacing' => $variations_label_letter_spacing,
                'font-size' => $variations_label_font_size,
                'text-transform' => $variations_label_text_transform,
            ));

        $styles .= "}";

                if( $variation_value_show ){
            $styles .= "{$row_classname} {$col_classname} {$block_classname} .variations_form .attribute-value{";
                $styles .= cartify_prepare_css_styles( array(
                    'color' => $variations_value_color,
                    'font-family' => $variations_value_font_family,
                    'font-weight' =>  $variations_value_font_weight,
                'font-variation-settings' =>  !empty($variations_value_font_weight) ? '"wght"' . $variations_value_font_weight : '',
                    'letter-spacing' => $variations_value_letter_spacing,
                    'font-size' => $variations_value_font_size,
                    'text-transform' => $variations_value_text_transform,
                ));

            $styles .= "}";
        }

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .variations_form .woocommerce-variation-price .price{";
            $styles .= cartify_prepare_css_styles(array(

                '--agni_product_layout_add_to_cart_variation_price_font_size_mobile' => !empty( $variations_price_font_size['mobile'] ) ? $variations_price_font_size['mobile'] : '',
                '--agni_product_layout_add_to_cart_variation_price_font_size_tab' => !empty( $variations_price_font_size['tab'] ) ? $variations_price_font_size['tab'] : '',
                '--agni_product_layout_add_to_cart_variation_price_font_size_laptop' => !empty( $variations_price_font_size['laptop'] ) ? $variations_price_font_size['laptop'] : '',
                '--agni_product_layout_add_to_cart_variation_price_font_size_desktop' => !empty( $variations_price_font_size['desktop'] ) ? $variations_price_font_size['desktop'] : '',

                '--agni_product_layout_add_to_cart_variation_price_old_font_size_mobile' => !empty( $variations_price_old_font_size['mobile'] ) ? $variations_price_old_font_size['mobile'] : '',
                '--agni_product_layout_add_to_cart_variation_price_old_font_size_tab' => !empty( $variations_price_old_font_size['tab'] ) ? $variations_price_old_font_size['tab'] : '',
                '--agni_product_layout_add_to_cart_variation_price_old_font_size_laptop' => !empty( $variations_price_old_font_size['laptop'] ) ? $variations_price_old_font_size['laptop'] : '',
                '--agni_product_layout_add_to_cart_variation_price_old_font_size_desktop' => !empty( $variations_price_old_font_size['desktop'] ) ? $variations_price_old_font_size['desktop'] : '',
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .single_add_to_cart_button{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $atc_font_family,
                'font-weight' =>  $atc_font_weight,
                'font-variation-settings' =>  !empty($atc_font_weight) ? '"wght"' . $atc_font_weight : '',
                'letter-spacing' => $atc_letter_spacing,

                '--agni_product_layout_add_to_cart_atc_font_size_mobile' => !empty( $atc_font_size['mobile'] ) ? $atc_font_size['mobile'] : '',
                '--agni_product_layout_add_to_cart_atc_font_size_tab' => !empty( $atc_font_size['tab'] ) ? $atc_font_size['tab'] : '',
                '--agni_product_layout_add_to_cart_atc_font_size_laptop' => !empty( $atc_font_size['laptop'] ) ? $atc_font_size['laptop'] : '',
                '--agni_product_layout_add_to_cart_atc_font_size_desktop' => !empty( $atc_font_size['desktop'] ) ? $atc_font_size['desktop'] : '',

                '--agni_product_layout_add_to_cart_atc_padding_mobile' => !empty( $atc_btn_padding['mobile'] ) ? $atc_btn_padding['mobile'] : '',
                '--agni_product_layout_add_to_cart_atc_padding_tab' => !empty( $atc_btn_padding['tab'] ) ? $atc_btn_padding['tab'] : '',
                '--agni_product_layout_add_to_cart_atc_padding_laptop' => !empty( $atc_btn_padding['laptop'] ) ? $atc_btn_padding['laptop'] : '',
                '--agni_product_layout_add_to_cart_atc_padding_desktop' => !empty( $atc_btn_padding['desktop'] ) ? $atc_btn_padding['desktop'] : '',

                '--agni_product_layout_add_to_cart_atc_color' => $atc_btn_color,
                '--agni_product_layout_add_to_cart_atc_bg_color' => $atc_btn_bg_color,
                '--agni_product_layout_add_to_cart_atc_border_color' => $atc_btn_border_color,

                '--agni_product_layout_add_to_cart_atc_hover_color' => $atc_btn_hover_color,
                '--agni_product_layout_add_to_cart_atc_hover_bg_color' => $atc_btn_hover_bg_color,
                '--agni_product_layout_add_to_cart_atc_hover_border_color' => $atc_btn_hover_border_color,

                'text-transform' => $atc_text_transform,
                'border-radius' => $atc_btn_border_radius,

            ));

        $styles .= "}";


                $styles .= "{$row_classname} {$col_classname} {$block_classname} .single_buynow_button{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $bn_font_family,
                'font-weight' =>  $bn_font_weight,
                'font-variation-settings' =>  !empty($bn_font_weight) ? '"wght"' . $bn_font_weight : '',
                'letter-spacing' => $bn_letter_spacing,

                '--agni_product_layout_add_to_cart_bn_font_size_mobile' => !empty( $bn_font_size['mobile'] ) ? $bn_font_size['mobile'] : '',
                '--agni_product_layout_add_to_cart_bn_font_size_tab' => !empty( $bn_font_size['tab'] ) ? $bn_font_size['tab'] : '',
                '--agni_product_layout_add_to_cart_bn_font_size_laptop' => !empty( $bn_font_size['laptop'] ) ? $bn_font_size['laptop'] : '',
                '--agni_product_layout_add_to_cart_bn_font_size_desktop' => !empty( $bn_font_size['desktop'] ) ? $bn_font_size['desktop'] : '',

                '--agni_product_layout_add_to_cart_bn_padding_mobile' => !empty( $bn_btn_padding['mobile'] ) ? $bn_btn_padding['mobile'] : '',
                '--agni_product_layout_add_to_cart_bn_padding_tab' => !empty( $bn_btn_padding['tab'] ) ? $bn_btn_padding['tab'] : '',
                '--agni_product_layout_add_to_cart_bn_padding_laptop' => !empty( $bn_btn_padding['laptop'] ) ? $bn_btn_padding['laptop'] : '',
                '--agni_product_layout_add_to_cart_bn_padding_desktop' => !empty( $bn_btn_padding['desktop'] ) ? $bn_btn_padding['desktop'] : '',

                '--agni_product_layout_add_to_cart_bn_color' => $bn_btn_color,
                '--agni_product_layout_add_to_cart_bn_bg_color' => $bn_btn_bg_color,
                '--agni_product_layout_add_to_cart_bn_border_color' => $bn_btn_border_color,

                '--agni_product_layout_add_to_cart_bn_hover_color' => $bn_btn_hover_color,
                '--agni_product_layout_add_to_cart_bn_hover_bg_color' => $bn_btn_hover_bg_color,
                '--agni_product_layout_add_to_cart_bn_hover_border_color' => $bn_btn_hover_border_color,

                'text-transform' => $bn_text_transform,
                'border-radius' => $bn_btn_border_radius,

            ));

            $styles .= cartify_prepare_css_styles(array(
            ));
        $styles .= "}";


        return $styles;
    }
}


if( !function_exists( 'cartify_product_layout_css_offers' ) ){
    function cartify_product_layout_css_offers( $block_settings, $row_classname = '', $col_classname = '' ){

                $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';

        $icon_color = isset( $block_settings['icon-color'] ) ? $block_settings['icon-color'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $line_height = isset( $block_settings['line-height'] ) ? $block_settings['line-height'] : '';

        $heading_color = isset( $block_settings['heading-color'] ) ? $block_settings['heading-color'] : '';
        $heading_font_family = isset( $block_settings['heading-font-choice']['font-family'] ) ? $block_settings['heading-font-choice']['font-family'] : '';
        $heading_font_weight = isset( $block_settings['heading-font-choice']['font-weight'] ) ? $block_settings['heading-font-choice']['font-weight'] : '';
        $heading_font_size = isset( $block_settings['heading-font-size'] ) ? $block_settings['heading-font-size'] : '';
        $heading_letter_spacing = isset( $block_settings['heading-letter-spacing'] ) ? $block_settings['heading-letter-spacing'] : '';
        $heading_line_height = isset( $block_settings['heading-line-height'] ) ? $block_settings['heading-line-height'] : '';
        $heading_text_transform = isset( $block_settings['heading-text-transform'] ) ? $block_settings['heading-text-transform'] : '';

        $styles = '';

        if( isset( $block_settings['heading-show'] ) ){
            $heading_show = cartify_prepare_responsive_values($block_settings['heading-show'], true);

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles(array(

                    '--agni_product_layout_offers_heading_visibility_desktop' => empty( $heading_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_offers_heading_visibility_laptop' => empty( $heading_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_offers_heading_visibility_tab' => empty( $heading_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_offers_heading_visibility_mobile' => empty( $heading_show['mobile'] ) ? 'none' : '',

                ));
            $styles .= "}";
        }

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_offers_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_offers_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_offers_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_offers_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'line-height' => $line_height,
                'letter-spacing' => $letter_spacing,

                'background-color' => $background_color,
                'border-width' => $border_width,
                'border-color' => $border_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} i{";
            $styles .= cartify_prepare_css_styles(array(
                'color' => $icon_color,
            ));

        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-offers-title{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_offers_title_font_size_desktop' => !empty( $heading_font_size['desktop'] ) ? $heading_font_size['desktop'] : '',
                '--agni_product_layout_offers_title_font_size_laptop' => !empty( $heading_font_size['laptop'] ) ? $heading_font_size['laptop'] : '',
                '--agni_product_layout_offers_title_font_size_tab' => !empty( $heading_font_size['tab'] ) ? $heading_font_size['tab'] : '',
                '--agni_product_layout_offers_title_font_size_mobile' => !empty( $heading_font_size['mobile'] ) ? $heading_font_size['mobile'] : '',

                                'color' => $heading_color,
                'font-family' => $heading_font_family,
                'font-weight' =>  $heading_font_weight,
                'font-variation-settings' =>  !empty($heading_font_weight) ? '"wght"' . $heading_font_weight : '',
                'line-height' => $heading_line_height,
                'letter-spacing' => $heading_letter_spacing,

                'text-transform' => $heading_text_transform
            ));

        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_shipping_info' ) ){
    function cartify_product_layout_css_shipping_info( $block_settings, $row_classname = '', $col_classname = '' ){

                $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $line_height = isset( $block_settings['line-height'] ) ? $block_settings['line-height'] : '';

        $heading_color = isset( $block_settings['heading-color'] ) ? $block_settings['heading-color'] : '';
        $heading_font_family = isset( $block_settings['heading-font-choice']['font-family'] ) ? $block_settings['heading-font-choice']['font-family'] : '';
        $heading_font_weight = isset( $block_settings['heading-font-choice']['font-weight'] ) ? $block_settings['heading-font-choice']['font-weight'] : '';
        $heading_font_size = isset( $block_settings['heading-font-size'] ) ? $block_settings['heading-font-size'] : '';
        $heading_letter_spacing = isset( $block_settings['heading-letter-spacing'] ) ? $block_settings['heading-letter-spacing'] : '';
        $heading_line_height = isset( $block_settings['heading-line-height'] ) ? $block_settings['heading-line-height'] : '';
        $heading_text_transform = isset( $block_settings['heading-text-transform'] ) ? $block_settings['heading-text-transform'] : '';

        $link_color = isset( $block_settings['link-color'] ) ? $block_settings['link-color'] : '';
        $link_hover_color = isset( $block_settings['link-hover-color'] ) ? $block_settings['link-hover-color'] : '';
        $link_background_color = isset( $block_settings['link-bg-color'] ) ? $block_settings['link-bg-color'] : '';
        $link_border_color = isset( $block_settings['link-border-color'] ) ? $block_settings['link-border-color'] : '';
        $link_hover_background_color = isset( $block_settings['link-hover-bg-color'] ) ? $block_settings['link-hover-bg-color'] : '';
        $link_hover_border_color = isset( $block_settings['link-hover-border-color'] ) ? $block_settings['link-hover-border-color'] : '';

        $link_font_family = isset( $block_settings['link-font-choice']['font-family'] ) ? $block_settings['link-font-choice']['font-family'] : '';
        $link_font_weight = isset( $block_settings['link-font-choice']['font-weight'] ) ? $block_settings['link-font-choice']['font-weight'] : '';
        $link_font_size = isset( $block_settings['link-font-size'] ) ? $block_settings['link-font-size'] : '';
        $link_letter_spacing = isset( $block_settings['link-letter-spacing'] ) ? $block_settings['link-letter-spacing'] : '';
        $link_line_height = isset( $block_settings['link-line-height'] ) ? $block_settings['link-line-height'] : '';
        $link_text_transform = isset( $block_settings['link-text-transform'] ) ? $block_settings['link-text-transform'] : '';
        $link_padding = isset( $block_settings['link-padding'] ) ? $block_settings['link-padding'] : '';

        $styles = '';

        if( isset( $block_settings['heading-show'] ) ){
            $heading_show = cartify_prepare_responsive_values($block_settings['heading-show'], true);

                        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles(array(
                    '--agni_product_layout_shipping_heading_visibility_desktop' => empty( $heading_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_shipping_heading_visibility_laptop' => empty( $heading_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_shipping_heading_visibility_tab' => empty( $heading_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_shipping_heading_visibility_mobile' => empty( $heading_show['mobile'] ) ? 'none' : '',

                                    ));
            $styles .= "}";
        }

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_shipping_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_shipping_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_shipping_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_shipping_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                                'color' => $color,
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'line-height' => $line_height,
                'letter-spacing' => $letter_spacing,

                'background-color' => $background_color,
                'border-width' => $border_width,
                'border-color' => $border_color,

                            ));
        $styles .= "}";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-shipping-info-link{";
            $styles .= cartify_prepare_css_styles(array(

                '--agni_product_layout_shipping_link_font_size_desktop' => !empty( $link_font_size['desktop'] ) ? $link_font_size['desktop'] : '',
                '--agni_product_layout_shipping_link_font_size_laptop' => !empty( $link_font_size['laptop'] ) ? $link_font_size['laptop'] : '',
                '--agni_product_layout_shipping_link_font_size_tab' => !empty( $link_font_size['tab'] ) ? $link_font_size['tab'] : '',
                '--agni_product_layout_shipping_link_font_size_mobile' => !empty( $link_font_size['mobile'] ) ? $link_font_size['mobile'] : '',

                '--agni_product_layout_shipping_link_padding_desktop' => !empty( $link_padding['desktop'] ) ? $link_padding['desktop'] : '',
                '--agni_product_layout_shipping_link_padding_laptop' => !empty( $link_padding['laptop'] ) ? $link_padding['laptop'] : '',
                '--agni_product_layout_shipping_link_padding_tab' => !empty( $link_padding['tab'] ) ? $link_padding['tab'] : '',
                '--agni_product_layout_shipping_link_padding_mobile' => !empty( $link_padding['mobile'] ) ? $link_padding['mobile'] : '',

                '--agni_product_layout_shipping_link_color' => $link_color,
                '--agni_product_layout_shipping_link_bg_color' => $link_background_color,
                '--agni_product_layout_shipping_link_border_color' => $link_border_color,

                '--agni_product_layout_shipping_link_hover_color' => $link_hover_color,
                '--agni_product_layout_shipping_link_hover_bg_color' => $link_hover_background_color,
                '--agni_product_layout_shipping_link_hover_border_color' => $link_hover_border_color,

                                'font-family' => $link_font_family,
                'font-weight' =>  $link_font_weight,
                'font-variation-settings' =>  !empty($link_font_weight) ? '"wght"' . $link_font_weight : '',
                'line-height' => $link_line_height,
                'letter-spacing' => $link_letter_spacing,

                'text-transform' => $link_text_transform

                            ));

        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-product-shipping-info-title{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_shipping_heading_font_size_desktop' => !empty( $heading_font_size['desktop'] ) ? $heading_font_size['desktop'] : '',
                '--agni_product_layout_shipping_heading_font_size_laptop' => !empty( $heading_font_size['laptop'] ) ? $heading_font_size['laptop'] : '',
                '--agni_product_layout_shipping_heading_font_size_tab' => !empty( $heading_font_size['tab'] ) ? $heading_font_size['tab'] : '',
                '--agni_product_layout_shipping_heading_font_size_mobile' => !empty( $heading_font_size['mobile'] ) ? $heading_font_size['mobile'] : '',

                                'color' => $heading_color,
                'font-family' => $heading_font_family,
                'font-weight' =>  $heading_font_weight,
                'font-variation-settings' =>  !empty($heading_font_weight) ? '"wght"' . $heading_font_weight : '',
                'line-height' => $heading_line_height,
                'letter-spacing' => $heading_letter_spacing,

                'text-transform' => $heading_text_transform
            ));

        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_add_to_wishlist' ) ){
    function cartify_product_layout_css_add_to_wishlist( $block_settings, $row_classname = '', $col_classname = '' ){

        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        $btn_padding = isset( $block_settings['btn-padding'] ) ? $block_settings['btn-padding'] : '';
        // $btn_fullwidth = isset( $block_settings['btn-fullwidth'] ) ? $block_settings['btn-fullwidth'] : '';
        $btn_background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $btn_border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $btn_border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $btn_border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';
        $btn_hover_background_color = isset( $block_settings['hover-bg-color'] ) ? $block_settings['hover-bg-color'] : '';

                $btn_hover_border_color = isset( $block_settings['hover-border-color'] ) ? $block_settings['hover-border-color'] : '';

        $gap = isset( $block_settings['gap'] ) ? $block_settings['gap'] : '';
        $icon_size = isset( $block_settings['icon-size'] ) ? $block_settings['icon-size'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $hover_color = isset( $block_settings['hover-color'] ) ? $block_settings['hover-color'] : '';

        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $align = isset( $block_settings['align'] ) ? $block_settings['align'] : '';

        $styles = '';


        if( isset( $block_settings['icon-show'] ) ){
            $icon_show = cartify_prepare_responsive_values($block_settings['icon-show'], true);
            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_layout_add_to_wishlist_icon_visibility_desktop' => empty( $icon_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_wishlist_icon_visibility_laptop' => empty( $icon_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_wishlist_icon_visibility_tab' => empty( $icon_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_wishlist_icon_visibility_mobile' => empty( $icon_show['mobile'] ) ? 'none' : '',
                ));
            $styles .= "}";
        }

        if( isset( $block_settings['text-show'] ) ){
            $text_show = cartify_prepare_responsive_values($block_settings['text-show'], true); 

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_layout_add_to_wishlist_text_visibility_desktop' => empty( $text_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_wishlist_text_visibility_laptop' => empty( $text_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_wishlist_text_visibility_tab' => empty( $text_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_wishlist_text_visibility_mobile' => empty( $text_show['mobile'] ) ? 'none' : '',
                ));
            $styles .= "}";
        }

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(

                                '--agni_product_layout_add_to_wishlist_btn_padding_desktop' => !empty( $btn_padding['desktop'] ) ? $btn_padding['desktop'] : '',
                '--agni_product_layout_add_to_wishlist_btn_padding_laptop' => !empty( $btn_padding['laptop'] ) ? $btn_padding['laptop'] : '',
                '--agni_product_layout_add_to_wishlist_btn_padding_tab' => !empty( $btn_padding['tab'] ) ? $btn_padding['tab'] : '',
                '--agni_product_layout_add_to_wishlist_btn_padding_mobile' => !empty( $btn_padding['mobile'] ) ? $btn_padding['mobile'] : '',

                '--agni_product_layout_add_to_wishlist_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_add_to_wishlist_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_add_to_wishlist_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_add_to_wishlist_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                                '--agni_product_layout_add_to_wishlist_icon_size_desktop' => !empty( $icon_size['desktop'] ) ? $icon_size['desktop'] : '',
                '--agni_product_layout_add_to_wishlist_icon_size_laptop' => !empty( $icon_size['laptop'] ) ? $icon_size['laptop'] : '',
                '--agni_product_layout_add_to_wishlist_icon_size_tab' => !empty( $icon_size['tab'] ) ? $icon_size['tab'] : '',
                '--agni_product_layout_add_to_wishlist_icon_size_mobile' => !empty( $icon_size['mobile'] ) ? $icon_size['mobile'] : '',

                '--agni_product_layout_add_to_wishlist_align' => $align,
                '--agni_product_layout_add_to_wishlist_color' => $color,
                '--agni_product_layout_add_to_wishlist_bg_color' => $btn_background_color,
                '--agni_product_layout_add_to_wishlist_border_color' => $btn_border_color,

                '--agni_product_layout_add_to_wishlist_hover_color' => $hover_color,
                '--agni_product_layout_add_to_wishlist_hover_bg_color' => $btn_hover_background_color,
                '--agni_product_layout_add_to_wishlist_hover_border_color' => $btn_hover_border_color,

                '--agni_product_layout_add_to_wishlist_gap' => $gap,

                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,
            ));

        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-add-to-wishlist__button a{";
            $styles .= cartify_prepare_css_styles(array(
                'border-width' => $btn_border_width,
                'border-radius' => $btn_border_radius,
            ));
        $styles .= "}";

                return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_add_to_compare' ) ){
    function cartify_product_layout_css_add_to_compare( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        $btn_padding = isset( $block_settings['btn-padding'] ) ? $block_settings['btn-padding'] : '';
        // $btn_fullwidth = isset( $block_settings['btn-fullwidth'] ) ? $block_settings['btn-fullwidth'] : '';
        $btn_background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $btn_border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $btn_border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $btn_border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';
        $btn_hover_background_color = isset( $block_settings['hover-bg-color'] ) ? $block_settings['hover-bg-color'] : '';

                $btn_hover_border_color = isset( $block_settings['hover-border-color'] ) ? $block_settings['hover-border-color'] : '';

        $gap = isset( $block_settings['gap'] ) ? $block_settings['gap'] : '';
        $icon_size = isset( $block_settings['icon-size'] ) ? $block_settings['icon-size'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $hover_color = isset( $block_settings['hover-color'] ) ? $block_settings['hover-color'] : '';

        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $align = isset( $block_settings['align'] ) ? $block_settings['align'] : '';

        $styles = '';


        if( isset( $block_settings['icon-show'] ) ){
            $icon_show = cartify_prepare_responsive_values($block_settings['icon-show'], true);
            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_layout_add_to_compare_icon_visibility_desktop' => empty( $icon_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_compare_icon_visibility_laptop' => empty( $icon_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_compare_icon_visibility_tab' => empty( $icon_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_compare_icon_visibility_mobile' => empty( $icon_show['mobile'] ) ? 'none' : '',
                ));
            $styles .= "}";
        }

        if( isset( $block_settings['text-show'] ) ){
            $text_show = cartify_prepare_responsive_values($block_settings['text-show'], true); 

            $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
                $styles .= cartify_prepare_css_styles( array(
                    '--agni_product_layout_add_to_compare_text_visibility_desktop' => empty( $text_show['desktop'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_compare_text_visibility_laptop' => empty( $text_show['laptop'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_compare_text_visibility_tab' => empty( $text_show['tab'] ) ? 'none' : '',
                    '--agni_product_layout_add_to_compare_text_visibility_mobile' => empty( $text_show['mobile'] ) ? 'none' : '',
                ));
            $styles .= "}";
        }

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(

                                '--agni_product_layout_add_to_compare_btn_padding_desktop' => !empty( $btn_padding['desktop'] ) ? $btn_padding['desktop'] : '',
                '--agni_product_layout_add_to_compare_btn_padding_laptop' => !empty( $btn_padding['laptop'] ) ? $btn_padding['laptop'] : '',
                '--agni_product_layout_add_to_compare_btn_padding_tab' => !empty( $btn_padding['tab'] ) ? $btn_padding['tab'] : '',
                '--agni_product_layout_add_to_compare_btn_padding_mobile' => !empty( $btn_padding['mobile'] ) ? $btn_padding['mobile'] : '',

                '--agni_product_layout_add_to_compare_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_add_to_compare_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_add_to_compare_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_add_to_compare_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                                '--agni_product_layout_add_to_compare_icon_size_desktop' => !empty( $icon_size['desktop'] ) ? $icon_size['desktop'] : '',
                '--agni_product_layout_add_to_compare_icon_size_laptop' => !empty( $icon_size['laptop'] ) ? $icon_size['laptop'] : '',
                '--agni_product_layout_add_to_compare_icon_size_tab' => !empty( $icon_size['tab'] ) ? $icon_size['tab'] : '',
                '--agni_product_layout_add_to_compare_icon_size_mobile' => !empty( $icon_size['mobile'] ) ? $icon_size['mobile'] : '',

                '--agni_product_layout_add_to_compare_align' => $align,
                '--agni_product_layout_add_to_compare_color' => $color,
                '--agni_product_layout_add_to_compare_bg_color' => $btn_background_color,
                '--agni_product_layout_add_to_compare_border_color' => $btn_border_color,

                '--agni_product_layout_add_to_compare_hover_color' => $hover_color,
                '--agni_product_layout_add_to_compare_hover_bg_color' => $btn_hover_background_color,
                '--agni_product_layout_add_to_compare_hover_border_color' => $btn_hover_border_color,

                '--agni_product_layout_add_to_compare_gap' => $gap,

                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,
            ));

        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-add-to-compare__button a{";
            $styles .= cartify_prepare_css_styles(array(
                'border-width' => $btn_border_width,
                'border-radius' => $btn_border_radius,
            ));
        $styles .= "}";

                return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_compare' ) ){
    function cartify_product_layout_css_compare( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        $btn_padding = isset( $block_settings['btn-padding'] ) ? $block_settings['btn-padding'] : '';
        // $btn_fullwidth = isset( $block_settings['btn-fullwidth'] ) ? $block_settings['btn-fullwidth'] : '';
        $btn_background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $btn_border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $btn_border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $btn_border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';
        $btn_hover_background_color = isset( $block_settings['hover-bg-color'] ) ? $block_settings['hover-bg-color'] : '';

                $btn_hover_border_color = isset( $block_settings['hover-border-color'] ) ? $block_settings['hover-border-color'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $hover_color = isset( $block_settings['hover-color'] ) ? $block_settings['hover-color'] : '';

        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(

                                '--agni_product_layout_compare_btn_padding_desktop' => !empty( $btn_padding['desktop'] ) ? $btn_padding['desktop'] : '',
                '--agni_product_layout_compare_btn_padding_laptop' => !empty( $btn_padding['laptop'] ) ? $btn_padding['laptop'] : '',
                '--agni_product_layout_compare_btn_padding_tab' => !empty( $btn_padding['tab'] ) ? $btn_padding['tab'] : '',
                '--agni_product_layout_compare_btn_padding_mobile' => !empty( $btn_padding['mobile'] ) ? $btn_padding['mobile'] : '',

                '--agni_product_layout_compare_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_compare_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_compare_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_compare_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',

                '--agni_product_layout_compare_color' => $color,
                '--agni_product_layout_compare_bg_color' => $btn_background_color,
                '--agni_product_layout_compare_border_color' => $btn_border_color,

                '--agni_product_layout_compare_hover_color' => $hover_color,
                '--agni_product_layout_compare_hover_bg_color' => $btn_hover_background_color,
                '--agni_product_layout_compare_hover_border_color' => $btn_hover_border_color,

                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,
            ));

        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-single-compare-button a{";
            $styles .= cartify_prepare_css_styles(array(
                'border-width' => $btn_border_width,
                'border-radius' => $btn_border_radius,
            ));
        $styles .= "}";

                return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_metadata' ) ){
    function cartify_product_layout_css_metadata( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $label_color = isset( $block_settings['label-color'] ) ? $block_settings['label-color'] : '';
        $label_font_family = isset( $block_settings['label-font-choice']['font-family'] ) ? $block_settings['label-font-choice']['font-family'] : '';
        $label_font_weight = isset( $block_settings['label-font-choice']['font-weight'] ) ? $block_settings['label-font-choice']['font-weight'] : '';
        $label_font_size = isset( $block_settings['label-font-size'] ) ? $block_settings['label-font-size'] : '';
        $label_letter_spacing = isset( $block_settings['label-letter-spacing'] ) ? $block_settings['label-letter-spacing'] : '';
        $label_text_transform = isset( $block_settings['label-text-transform'] ) ? $block_settings['label-text-transform'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $text_transform = isset( $block_settings['text-transform'] ) ? $block_settings['text-transform'] : '';

        $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                'background-color' => $background_color,
                'border-color' => $border_color,
                'border-width' => $border_width,
                'border-radius' => $border_radius,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .product_meta >span{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $label_font_family,
                'font-weight' =>  $label_font_weight,
                'font-variation-settings' =>  !empty($label_font_weight) ? '"wght"' . $label_font_weight : '',
                'letter-spacing' => $label_letter_spacing,
                'color' => $label_color,
                'text-transform' => $label_text_transform,

                '--agni_product_layout_meta_label_font_size_desktop' => !empty( $label_font_size['desktop'] ) ? $label_font_size['desktop'] : '',
                '--agni_product_layout_meta_label_font_size_laptop' => !empty( $label_font_size['laptop'] ) ? $label_font_size['laptop'] : '',
                '--agni_product_layout_meta_label_font_size_tab' => !empty( $label_font_size['tab'] ) ? $label_font_size['tab'] : '',
                '--agni_product_layout_meta_label_font_size_mobile' => !empty( $label_font_size['mobile'] ) ? $label_font_size['mobile'] : '',
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .product_meta >span a, 
        {$row_classname} {$col_classname} {$block_classname} .product_meta >span span{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,
                'color' => $color,
                'text-transform' => $text_transform,

                '--agni_product_layout_meta_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_meta_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_meta_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_meta_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',
            ));
        $styles .= "}";


        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_stock' ) ){
    function cartify_product_layout_css_stock( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


        $availability_bar_show = isset( $block_settings['availability-bar-show'] ) ? $block_settings['availability-bar-show'] : '';

        $availability_bar_color = isset( $block_settings['availability-bar-color'] ) ? $block_settings['availability-bar-color'] : '';
        $availability_bar_base_color = isset( $block_settings['availability-bar-base-color'] ) ? $block_settings['availability-bar-base-color'] : '';
        $availability_bar_font_family = isset( $block_settings['availability-bar-font-choice']['font-family'] ) ? $block_settings['availability-bar-font-choice']['font-family'] : '';
        $availability_bar_font_weight = isset( $block_settings['availability-bar-font-choice']['font-weight'] ) ? $block_settings['availability-bar-font-choice']['font-weight'] : '';
        $availability_bar_font_size = isset( $block_settings['availability-bar-font-size'] ) ? $block_settings['availability-bar-font-size'] : '';
        $availability_bar_letter_spacing = isset( $block_settings['availability-bar-letter-spacing'] ) ? $block_settings['availability-bar-letter-spacing'] : '';
        $availability_bar_text_transform = isset( $block_settings['availability-bar-text-transform'] ) ? $block_settings['availability-bar-text-transform'] : '';
        $availability_bar_text_color = isset( $block_settings['availability-bar-text-color'] ) ? $block_settings['availability-bar-text-color'] : '';
        $availability_bar_height = isset( $block_settings['availability-bar-height'] ) ? $block_settings['availability-bar-height'] : '';

        $color = isset( $block_settings['color'] ) ? $block_settings['color'] : '';
        $font_family = isset( $block_settings['font-choice']['font-family'] ) ? $block_settings['font-choice']['font-family'] : '';
        $font_weight = isset( $block_settings['font-choice']['font-weight'] ) ? $block_settings['font-choice']['font-weight'] : '';
        $font_size = isset( $block_settings['font-size'] ) ? $block_settings['font-size'] : '';
        $letter_spacing = isset( $block_settings['letter-spacing'] ) ? $block_settings['letter-spacing'] : '';
        $text_transform = isset( $block_settings['text-transform'] ) ? $block_settings['text-transform'] : '';


        $text_background_color = isset( $block_settings['text-bg-color'] ) ? $block_settings['text-bg-color'] : '';
        $text_border_width = isset( $block_settings['text-border-width'] ) ? $block_settings['text-border-width'] : '';
        $text_border_color = isset( $block_settings['text-border-color'] ) ? $block_settings['text-border-color'] : '';
        $text_border_radius = isset( $block_settings['text-border-radius'] ) ? $block_settings['text-border-radius'] : '';

        $background_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $text_align = isset( $block_settings['text-align'] ) ? $block_settings['text-align'] : '';

        $styles = '';


        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles( array(
                '--agni_product_layout_stock_availability_bar_visibility_desktop' => !empty( $availability_bar_show['desktop'] ) ? 'unset' : '',
                '--agni_product_layout_stock_availability_bar_visibility_laptop' => !empty( $availability_bar_show['laptop'] ) ? 'unset' : '',
                '--agni_product_layout_stock_availability_bar_visibility_tab' => !empty( $availability_bar_show['tab'] )  ? 'unset' : '',
                '--agni_product_layout_stock_availability_bar_visibility_mobile' => !empty( $availability_bar_show['mobile'] ) ? 'unset' : '',

                '--agni_product_layout_stock_text_visibility_desktop' => !empty( $availability_bar_show['desktop'] ) ? 'none' : '',
                '--agni_product_layout_stock_text_visibility_laptop' => !empty( $availability_bar_show['laptop'] ) ? 'none' : '',
                '--agni_product_layout_stock_text_visibility_tab' => !empty( $availability_bar_show['tab'] )  ? 'none' : '',
                '--agni_product_layout_stock_text_visibility_mobile' => !empty( $availability_bar_show['mobile'] ) ? 'none' : '',

                'background-color' => $background_color,
                'border-color' => $border_color,
                'border-width' => $border_width,
                'border-radius' => $border_radius,

            ));

            // $styles .= cartify_prepare_css_styles( array(
            //     'text-align' => $text_align
            // ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-stock-indicator{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $availability_bar_font_family,
                'font-weight' =>  $availability_bar_font_weight,
                'font-variation-settings' =>  !empty($availability_bar_font_weight) ? '"wght"' . $availability_bar_font_weight : '',
                'letter-spacing' => $availability_bar_letter_spacing,
                'color' => $availability_bar_text_color,
                'text-transform' => $availability_bar_text_transform,

                '--cartify_product_stock_progressbar_height' => $availability_bar_height,

                '--agni_product_layout_stock_availability_bar_font_size_desktop' => !empty( $availability_bar_font_size['desktop'] ) ? $availability_bar_font_size['desktop'] : '',
                '--agni_product_layout_stock_availability_bar_font_size_laptop' => !empty( $availability_bar_font_size['laptop'] ) ? $availability_bar_font_size['laptop'] : '',
                '--agni_product_layout_stock_availability_bar_font_size_tab' => !empty( $availability_bar_font_size['tab'] ) ? $availability_bar_font_size['tab'] : '',
                '--agni_product_layout_stock_availability_bar_font_size_mobile' => !empty( $availability_bar_font_size['mobile'] ) ? $availability_bar_font_size['mobile'] : '',

                '--agni_product_layout_stock_availability_bar_base_color' => $availability_bar_base_color,
                '--agni_product_layout_stock_availability_bar_color' => $availability_bar_color,

                            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .stock{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $font_family,
                'font-weight' =>  $font_weight,
                'font-variation-settings' =>  !empty($font_weight) ? '"wght"' . $font_weight : '',
                'letter-spacing' => $letter_spacing,
                'color' => $color,
                'text-transform' => $text_transform,

                'background-color' => $text_background_color,
                'border-width' => $text_border_width,
                'border-color' => $text_border_color,
                'border-radius' => $text_border_radius,

                '--agni_product_layout_stock_text_border_padding' => !empty( $text_border_width ) ? '0px 8px' : '',

                '--agni_product_layout_stock_font_size_desktop' => !empty( $font_size['desktop'] ) ? $font_size['desktop'] : '',
                '--agni_product_layout_stock_font_size_laptop' => !empty( $font_size['laptop'] ) ? $font_size['laptop'] : '',
                '--agni_product_layout_stock_font_size_tab' => !empty( $font_size['tab'] ) ? $font_size['tab'] : '',
                '--agni_product_layout_stock_font_size_mobile' => !empty( $font_size['mobile'] ) ? $font_size['mobile'] : '',
            ));
        $styles .= "}";


        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_recently_viewed' ) ){
    function cartify_product_layout_css_recently_viewed( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $bg_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';

        $columns = isset( $block_settings['columns'] ) ? $block_settings['columns'] : '';

        $heading_color = isset( $block_settings['heading-color'] ) ? $block_settings['heading-color'] : '';
        $heading_font_family = isset( $block_settings['heading-font-choice']['font-family'] ) ? $block_settings['heading-font-choice']['font-family'] : '';
        $heading_font_weight = isset( $block_settings['heading-font-choice']['font-weight'] ) ? $block_settings['heading-font-choice']['font-weight'] : '';
        $heading_font_size = isset( $block_settings['heading-font-size'] ) ? $block_settings['heading-font-size'] : '';
        $heading_letter_spacing = isset( $block_settings['heading-letter-spacing'] ) ? $block_settings['heading-letter-spacing'] : '';
        $heading_line_height = isset( $block_settings['heading-line-height'] ) ? $block_settings['heading-line-height'] : '';
        $heading_text_transform = isset( $block_settings['heading-text-transform'] ) ? $block_settings['heading-text-transform'] : '';
        $heading_text_align = isset( $block_settings['heading-text-align'] ) ? $block_settings['heading-text-align'] : '';

        $styles = '';

                $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_recently_viewed_columns_count_mobile' => !empty( $columns['mobile'] ) ? $columns['mobile'] : '',
                '--agni_product_layout_recently_viewed_columns_count_tab' => !empty( $columns['tab'] ) ? $columns['tab'] : '',
                '--agni_product_layout_recently_viewed_columns_count_laptop' => !empty( $columns['laptop'] ) ? $columns['laptop'] : '',
                '--agni_product_layout_recently_viewed_columns_count_desktop' => !empty( $columns['desktop'] ) ? $columns['desktop'] : '',

                'background-color' => $bg_color,
                'border-width' => $border_width,
                'border-color' => $border_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-recently-viewed-products >h2{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $heading_font_family,
                'font-weight' =>  $heading_font_weight,
                'font-variation-settings' =>  !empty($heading_font_weight) ? '"wght"' . $heading_font_weight : '',
                'letter-spacing' => $heading_letter_spacing,
                'line-height' => $heading_line_height,
                'color' => $heading_color,

                '--agni_product_layout_recently_viewed_heading_font_size_desktop' => !empty( $heading_font_size['desktop'] ) ? $heading_font_size['desktop'] : '',
                '--agni_product_layout_recently_viewed_heading_font_size_laptop' => !empty( $heading_font_size['laptop'] ) ? $heading_font_size['laptop'] : '',
                '--agni_product_layout_recently_viewed_heading_font_size_tab' => !empty( $heading_font_size['tab'] ) ? $heading_font_size['tab'] : '',
                '--agni_product_layout_recently_viewed_heading_font_size_mobile' => !empty( $heading_font_size['mobile'] ) ? $heading_font_size['mobile'] : '',

                'text-transform' => $heading_text_transform,
                'text-align' => $heading_text_align,
            ));
        $styles .= "}";

        return $styles;
    }
}


if( !function_exists( 'cartify_product_layout_css_upsell' ) ){
    function cartify_product_layout_css_upsell( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $bg_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';

        $columns = isset( $block_settings['columns'] ) ? $block_settings['columns'] : '';

        $heading_color = isset( $block_settings['heading-color'] ) ? $block_settings['heading-color'] : '';
        $heading_font_family = isset( $block_settings['heading-font-choice']['font-family'] ) ? $block_settings['heading-font-choice']['font-family'] : '';
        $heading_font_weight = isset( $block_settings['heading-font-choice']['font-weight'] ) ? $block_settings['heading-font-choice']['font-weight'] : '';
        $heading_font_size = isset( $block_settings['heading-font-size'] ) ? $block_settings['heading-font-size'] : '';
        $heading_letter_spacing = isset( $block_settings['heading-letter-spacing'] ) ? $block_settings['heading-letter-spacing'] : '';
        $heading_line_height = isset( $block_settings['heading-line-height'] ) ? $block_settings['heading-line-height'] : '';
        $heading_text_transform = isset( $block_settings['heading-text-transform'] ) ? $block_settings['heading-text-transform'] : '';
        $heading_text_align = isset( $block_settings['heading-text-align'] ) ? $block_settings['heading-text-align'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_upsell_columns_count_mobile' => !empty( $columns['mobile'] ) ? $columns['mobile'] : '',
                '--agni_product_layout_upsell_columns_count_tab' => !empty( $columns['tab'] ) ? $columns['tab'] : '',
                '--agni_product_layout_upsell_columns_count_laptop' => !empty( $columns['laptop'] ) ? $columns['laptop'] : '',
                '--agni_product_layout_upsell_columns_count_desktop' => !empty( $columns['desktop'] ) ? $columns['desktop'] : '',

                'background-color' => $bg_color,
                'border-width' => $border_width,
                'border-color' => $border_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .upsells >h2{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $heading_font_family,
                'font-weight' =>  $heading_font_weight,
                'font-variation-settings' =>  !empty($heading_font_weight) ? '"wght"' . $heading_font_weight : '',
                'letter-spacing' => $heading_letter_spacing,
                'line-height' => $heading_line_height,
                'color' => $heading_color,

                '--agni_product_layout_upsell_heading_font_size_desktop' => !empty( $heading_font_size['desktop'] ) ? $heading_font_size['desktop'] : '',
                '--agni_product_layout_upsell_heading_font_size_laptop' => !empty( $heading_font_size['laptop'] ) ? $heading_font_size['laptop'] : '',
                '--agni_product_layout_upsell_heading_font_size_tab' => !empty( $heading_font_size['tab'] ) ? $heading_font_size['tab'] : '',
                '--agni_product_layout_upsell_heading_font_size_mobile' => !empty( $heading_font_size['mobile'] ) ? $heading_font_size['mobile'] : '',

                'text-transform' => $heading_text_transform,
                'text-align' => $heading_text_align,
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_related' ) ){
    function cartify_product_layout_css_related( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $bg_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';

        $columns = isset( $block_settings['columns'] ) ? $block_settings['columns'] : '';

        $heading_color = isset( $block_settings['heading-color'] ) ? $block_settings['heading-color'] : '';
        $heading_font_family = isset( $block_settings['heading-font-choice']['font-family'] ) ? $block_settings['heading-font-choice']['font-family'] : '';
        $heading_font_weight = isset( $block_settings['heading-font-choice']['font-weight'] ) ? $block_settings['heading-font-choice']['font-weight'] : '';
        $heading_font_size = isset( $block_settings['heading-font-size'] ) ? $block_settings['heading-font-size'] : '';
        $heading_letter_spacing = isset( $block_settings['heading-letter-spacing'] ) ? $block_settings['heading-letter-spacing'] : '';
        $heading_line_height = isset( $block_settings['heading-line-height'] ) ? $block_settings['heading-line-height'] : '';
        $heading_text_transform = isset( $block_settings['heading-text-transform'] ) ? $block_settings['heading-text-transform'] : '';
        $heading_text_align = isset( $block_settings['heading-text-align'] ) ? $block_settings['heading-text-align'] : '';

        $styles = '';


                $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_related_columns_count_mobile' => !empty( $columns['mobile'] ) ? $columns['mobile'] : '',
                '--agni_product_layout_related_columns_count_tab' => !empty( $columns['tab'] ) ? $columns['tab'] : '',
                '--agni_product_layout_related_columns_count_laptop' => !empty( $columns['laptop'] ) ? $columns['laptop'] : '',
                '--agni_product_layout_related_columns_count_desktop' => !empty( $columns['desktop'] ) ? $columns['desktop'] : '',

                'background-color' => $bg_color,
                'border-width' => $border_width,
                'border-color' => $border_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .related >h2{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $heading_font_family,
                'font-weight' =>  $heading_font_weight,
                'font-variation-settings' =>  !empty($heading_font_weight) ? '"wght"' . $heading_font_weight : '',
                'letter-spacing' => $heading_letter_spacing,
                'line-height' => $heading_line_height,
                'color' => $heading_color,

                '--agni_product_layout_related_heading_font_size_desktop' => !empty( $heading_font_size['desktop'] ) ? $heading_font_size['desktop'] : '',
                '--agni_product_layout_related_heading_font_size_laptop' => !empty( $heading_font_size['laptop'] ) ? $heading_font_size['laptop'] : '',
                '--agni_product_layout_related_heading_font_size_tab' => !empty( $heading_font_size['tab'] ) ? $heading_font_size['tab'] : '',
                '--agni_product_layout_related_heading_font_size_mobile' => !empty( $heading_font_size['mobile'] ) ? $heading_font_size['mobile'] : '',

                'text-transform' => $heading_text_transform,
                'text-align' => $heading_text_align,
            ));
        $styles .= "}";

        return $styles;
    }
}


if( !function_exists( 'cartify_product_layout_css_tabs' ) ){
    function cartify_product_layout_css_tabs( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $bg_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';

        $tabs_font_family = isset( $block_settings['tabs-font-choice']['font-family'] ) ? $block_settings['tabs-font-choice']['font-family'] : '';
        $tabs_font_weight = isset( $block_settings['tabs-font-choice']['font-weight'] ) ? $block_settings['tabs-font-choice']['font-weight'] : '';
        $tabs_font_size = isset( $block_settings['tabs-font-size'] ) ? $block_settings['tabs-font-size'] : '';
        $tabs_letter_spacing = isset( $block_settings['tabs-letter-spacing'] ) ? $block_settings['tabs-letter-spacing'] : '';
        $tabs_text_transform = isset( $block_settings['tabs-text-transform'] ) ? $block_settings['tabs-text-transform'] : '';
        $tabs_text_align = isset( $block_settings['tabs-text-align'] ) ? $block_settings['tabs-text-align'] : '';

        $tabs_color = isset( $block_settings['tabs-color'] ) ? $block_settings['tabs-color'] : '';
        $tabs_border_color = isset( $block_settings['tabs-border-color'] ) ? $block_settings['tabs-border-color'] : '';
        $tabs_active_color = isset( $block_settings['tabs-active-color'] ) ? $block_settings['tabs-active-color'] : '';
        $tabs_active_border_color = isset( $block_settings['tabs-active-border-color'] ) ? $block_settings['tabs-active-border-color'] : '';
        $tabs_border_width = isset( $block_settings['tabs-border-width'] ) ? $block_settings['tabs-border-width'] : '';
        $tabs_bg_color = isset( $block_settings['tabs-bg-color'] ) ? $block_settings['tabs-bg-color'] : '';

        $styles = '';

                $styles .= "{$row_classname} {$col_classname} {$block_classname} {";
            $styles .= cartify_prepare_css_styles(array(
                'background-color' => $bg_color,
                'border-width' => $border_width,
                'border-color' => $border_color,

                '--agni_product_layout_tabs_color' => $tabs_color,
                '--agni_product_layout_tabs_active_color' => $tabs_active_color,
                '--agni_product_layout_tabs_border_color' => $tabs_border_color,
                '--agni_product_layout_tabs_active_border_color' => $tabs_active_border_color,
                '--agni_product_layout_tabs_border_width' => $tabs_border_width,
                '--agni_product_layout_tabs_bg_color' => $tabs_bg_color,
                '--agni_product_layout_tabs_padding' => !empty( $tabs_bg_color ) ? '15px 30px' : '',
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .woocommerce-tabs >ul li a{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $tabs_font_family,
                'font-size' => $tabs_font_size,
                'font-weight' =>  $tabs_font_weight,
                'font-variation-settings' =>  !empty($tabs_font_weight) ? '"wght"' . $tabs_font_weight : '',
                'letter-spacing' => $tabs_letter_spacing,

                'text-transform' => $tabs_text_transform,
                'text-align' => $tabs_text_align,

            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_fbt' ) ){
    function cartify_product_layout_css_fbt( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $bg_color = isset( $block_settings['bg-color'] ) ? $block_settings['bg-color'] : '';
        $border_width = isset( $block_settings['border-width'] ) ? $block_settings['border-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';
        $border_radius = isset( $block_settings['border-radius'] ) ? $block_settings['border-radius'] : '';

        $image_width = isset( $block_settings['image-width'] ) ? $block_settings['image-width'] : '';

        $heading_color = isset( $block_settings['heading-color'] ) ? $block_settings['heading-color'] : '';
        $heading_font_family = isset( $block_settings['heading-font-choice']['font-family'] ) ? $block_settings['heading-font-choice']['font-family'] : '';
        $heading_font_weight = isset( $block_settings['heading-font-choice']['font-weight'] ) ? $block_settings['heading-font-choice']['font-weight'] : '';
        $heading_font_size = isset( $block_settings['heading-font-size'] ) ? $block_settings['heading-font-size'] : '';
        $heading_letter_spacing = isset( $block_settings['heading-letter-spacing'] ) ? $block_settings['heading-letter-spacing'] : '';
        $heading_line_height = isset( $block_settings['heading-line-height'] ) ? $block_settings['heading-line-height'] : '';
        $heading_text_transform = isset( $block_settings['heading-text-transform'] ) ? $block_settings['heading-text-transform'] : '';
        $heading_text_align = isset( $block_settings['heading-text-align'] ) ? $block_settings['heading-text-align'] : '';


                $product_name_color = isset( $block_settings['product-name-color'] ) ? $block_settings['product-name-color'] : '';
        $product_name_font_family = isset( $block_settings['product-name-font-choice']['font-family'] ) ? $block_settings['product-name-font-choice']['font-family'] : '';
        $product_name_font_weight = isset( $block_settings['product-name-font-choice']['font-weight'] ) ? $block_settings['product-name-font-choice']['font-weight'] : '';
        $product_name_font_size = isset( $block_settings['product-name-font-size'] ) ? $block_settings['product-name-font-size'] : '';
        $product_name_letter_spacing = isset( $block_settings['product-name-letter-spacing'] ) ? $block_settings['product-name-letter-spacing'] : '';
        $product_name_text_transform = isset( $block_settings['product-name-text-transform'] ) ? $block_settings['product-name-text-transform'] : '';
        $product_name_text_align = isset( $block_settings['product-name-text-align'] ) ? $block_settings['product-name-text-align'] : '';


        $price_color = isset( $block_settings['price-color'] ) ? $block_settings['price-color'] : '';
        $price_font_family = isset( $block_settings['price-font-choice']['font-family'] ) ? $block_settings['price-font-choice']['font-family'] : '';
        $price_font_weight = isset( $block_settings['price-font-choice']['font-weight'] ) ? $block_settings['price-font-choice']['font-weight'] : '';
        $price_font_size = isset( $block_settings['price-font-size'] ) ? $block_settings['price-font-size'] : '';
        $price_letter_spacing = isset( $block_settings['price-letter-spacing'] ) ? $block_settings['price-letter-spacing'] : '';
        $price_text_transform = isset( $block_settings['price-text-transform'] ) ? $block_settings['price-text-transform'] : '';
        $price_text_align = isset( $block_settings['price-text-align'] ) ? $block_settings['price-text-align'] : '';


        $btn_font_family = isset( $block_settings['btn-font-choice']['font-family'] ) ? $block_settings['btn-font-choice']['font-family'] : '';
        $btn_font_weight = isset( $block_settings['btn-font-choice']['font-weight'] ) ? $block_settings['btn-font-choice']['font-weight'] : '';
        $btn_font_size = isset( $block_settings['btn-font-size'] ) ? $block_settings['btn-font-size'] : '';
        $btn_letter_spacing = isset( $block_settings['btn-letter-spacing'] ) ? $block_settings['btn-letter-spacing'] : '';
        $btn_text_transform = isset( $block_settings['btn-text-transform'] ) ? $block_settings['btn-text-transform'] : '';

        $btn_padding = isset( $block_settings['btn-padding'] ) ? $block_settings['btn-padding'] : '';
        $btn_bg_color = isset( $block_settings['btn-bg-color'] ) ? $block_settings['btn-bg-color'] : '';
        $btn_border_width = isset( $block_settings['btn-border-width'] ) ? $block_settings['btn-border-width'] : '';
        $btn_border_color = isset( $block_settings['btn-border-color'] ) ? $block_settings['btn-border-color'] : '';
        $btn_border_radius = isset( $block_settings['btn-border-radius'] ) ? $block_settings['btn-border-radius'] : '';
        $btn_hover_bg_color = isset( $block_settings['btn-hover-bg-color'] ) ? $block_settings['btn-hover-bg-color'] : '';

                $btn_hover_border_color = isset( $block_settings['btn-hover-border-color'] ) ? $block_settings['btn-hover-border-color'] : '';

        $btn_color = isset( $block_settings['btn-color'] ) ? $block_settings['btn-color'] : '';
        $btn_hover_color = isset( $block_settings['btn-hover-color'] ) ? $block_settings['btn-hover-color'] : '';

        $styles = '';

        $styles .= "{$row_classname} {$col_classname} {$block_classname} {";
            $styles .= cartify_prepare_css_styles(array(
                '--agni_product_layout_fbt_thumbmail_width_desktop' => !empty( $image_width['desktop'] ) ? $image_width['desktop'] : '',
                '--agni_product_layout_fbt_thumbmail_width_laptop' => !empty( $image_width['laptop'] ) ? $image_width['laptop'] : '',
                '--agni_product_layout_fbt_thumbmail_width_tab' => !empty( $image_width['tab'] ) ? $image_width['tab'] : '',
                '--agni_product_layout_fbt_thumbmail_width_mobile' => !empty( $image_width['mobile'] ) ? $image_width['mobile'] : '',

                '--agni_product_layout_fbt_border_width' => $border_width,
                '--agni_product_layout_fbt_border_color' => $border_color,

                                'background-color' => $bg_color,
                'border-radius' => $border_radius,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} h2{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $heading_font_family,
                'font-weight' =>  $heading_font_weight,
                'font-variation-settings' =>  !empty($heading_font_weight) ? '"wght"' . $heading_font_weight : '',
                'letter-spacing' => $heading_letter_spacing,
                'line-height' => $heading_line_height,
                'color' => $heading_color,

                'text-transform' => $heading_text_transform,
                'text-align' => $heading_text_align,

                '--agni_product_layout_fbt_heading_font_size_desktop' => !empty( $heading_font_size['desktop'] ) ? $heading_font_size['desktop'] : '',
                '--agni_product_layout_fbt_heading_font_size_laptop' => !empty( $heading_font_size['laptop'] ) ? $heading_font_size['laptop'] : '',
                '--agni_product_layout_fbt_heading_font_size_tab' => !empty( $heading_font_size['tab'] ) ? $heading_font_size['tab'] : '',
                '--agni_product_layout_fbt_heading_font_size_mobile' => !empty( $heading_font_size['mobile'] ) ? $heading_font_size['mobile'] : '',
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-addon-products__choices{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $product_name_font_family,
                'font-weight' =>  $product_name_font_weight,
                'font-variation-settings' =>  !empty($product_name_font_weight) ? '"wght"' . $product_name_font_weight : '',
                'font-size' => $product_name_font_size,
                'letter-spacing' => $product_name_letter_spacing,
                'color' => $product_name_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-addon-products__total-price{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $price_font_family,
                'font-weight' =>  $price_font_weight,
                'font-variation-settings' =>  !empty($price_font_weight) ? '"wght"' . $price_font_weight : '',
                'font-size' => $price_font_size,
                'letter-spacing' => $price_letter_spacing,
                'color' => $price_color,
            ));
        $styles .= "}";

                $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-addon-products__button--add-all-to-cart{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $btn_font_family,
                'font-weight' =>  $btn_font_weight,
                'font-variation-settings' =>  !empty($btn_font_weight) ? '"wght"' . $btn_font_weight : '',
                'letter-spacing' => $btn_letter_spacing,

                'text-transform' => $btn_text_transform,

                '--cartify_button_border_width' => $btn_border_width,
                '--cartify_button_border_radius' => $btn_border_radius,

                                '--agni_product_layout_fbt_btn_color' => $btn_color,
                '--agni_product_layout_fbt_btn_bg_color' => $btn_bg_color,
                '--agni_product_layout_fbt_btn_border_color' => $btn_border_color,
                '--agni_product_layout_fbt_btn_hover_color' => $btn_hover_color,
                '--agni_product_layout_fbt_btn_hover_bg_color' => $btn_hover_bg_color,
                '--agni_product_layout_fbt_btn_hover_border_color' => $btn_hover_border_color,

                '--agni_product_layout_fbt_btn_font_size_desktop' => !empty( $btn_font_size['desktop'] ) ? $btn_font_size['desktop'] : '',
                '--agni_product_layout_fbt_btn_font_size_laptop' => !empty( $btn_font_size['laptop'] ) ? $btn_font_size['laptop'] : '',
                '--agni_product_layout_fbt_btn_font_size_tab' => !empty( $btn_font_size['tab'] ) ? $btn_font_size['tab'] : '',
                '--agni_product_layout_fbt_btn_font_size_mobile' => !empty( $btn_font_size['mobile'] ) ? $btn_font_size['mobile'] : '',

                '--agni_product_layout_fbt_btn_padding_desktop' => !empty( $btn_padding['desktop'] ) ? $btn_padding['desktop'] : '',
                '--agni_product_layout_fbt_btn_padding_laptop' => !empty( $btn_padding['laptop'] ) ? $btn_padding['laptop'] : '',
                '--agni_product_layout_fbt_btn_padding_tab' => !empty( $btn_padding['tab'] ) ? $btn_padding['tab'] : '',
                '--agni_product_layout_fbt_btn_padding_mobile' => !empty( $btn_padding['mobile'] ) ? $btn_padding['mobile'] : '',
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_product_layout_css_compare_content' ) ){
    function cartify_product_layout_css_compare_content( $block_settings, $row_classname = '', $col_classname = '' ){
        $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';

        $heading_color = isset( $block_settings['heading-color'] ) ? $block_settings['heading-color'] : '';
        $heading_font_family = isset( $block_settings['heading-font-choice']['font-family'] ) ? $block_settings['heading-font-choice']['font-family'] : '';
        $heading_font_weight = isset( $block_settings['heading-font-choice']['font-weight'] ) ? $block_settings['heading-font-choice']['font-weight'] : '';
        $heading_font_size = isset( $block_settings['heading-font-size'] ) ? $block_settings['heading-font-size'] : '';
        $heading_letter_spacing = isset( $block_settings['heading-letter-spacing'] ) ? $block_settings['heading-letter-spacing'] : '';
        $heading_line_height = isset( $block_settings['heading-line-height'] ) ? $block_settings['heading-line-height'] : '';
        $heading_text_transform = isset( $block_settings['heading-text-transform'] ) ? $block_settings['heading-text-transform'] : '';
        $heading_text_align = isset( $block_settings['heading-text-align'] ) ? $block_settings['heading-text-align'] : '';

        $label_color = isset( $block_settings['label-color'] ) ? $block_settings['label-color'] : '';
        $label_font_family = isset( $block_settings['label-font-choice']['font-family'] ) ? $block_settings['label-font-choice']['font-family'] : '';
        $label_font_weight = isset( $block_settings['label-font-choice']['font-weight'] ) ? $block_settings['label-font-choice']['font-weight'] : '';
        $label_font_size = isset( $block_settings['label-font-size'] ) ? $block_settings['label-font-size'] : '';
        $label_letter_spacing = isset( $block_settings['label-letter-spacing'] ) ? $block_settings['label-letter-spacing'] : '';
        $label_line_height = isset( $block_settings['label-line-height'] ) ? $block_settings['label-line-height'] : '';
        $label_text_transform = isset( $block_settings['label-text-transform'] ) ? $block_settings['label-text-transform'] : '';

        $value_color = isset( $block_settings['value-color'] ) ? $block_settings['value-color'] : '';
        $value_font_family = isset( $block_settings['value-font-choice']['font-family'] ) ? $block_settings['value-font-choice']['font-family'] : '';
        $value_font_weight = isset( $block_settings['value-font-choice']['font-weight'] ) ? $block_settings['value-font-choice']['font-weight'] : '';
        $value_font_size = isset( $block_settings['value-font-size'] ) ? $block_settings['value-font-size'] : '';
        $value_letter_spacing = isset( $block_settings['value-letter-spacing'] ) ? $block_settings['value-letter-spacing'] : '';
        $value_line_height = isset( $block_settings['value-line-height'] ) ? $block_settings['value-line-height'] : '';
        $value_text_transform = isset( $block_settings['value-text-transform'] ) ? $block_settings['value-text-transform'] : '';

        $label_bg_color = isset( $block_settings['label-bg-color'] ) ? $block_settings['label-bg-color'] : '';
        $base_bg_color = isset( $block_settings['base-bg-color'] ) ? $block_settings['base-bg-color'] : '';

        $border_top_width = isset( $block_settings['border-top-width'] ) ? $block_settings['border-top-width'] : '';
        $border_color = isset( $block_settings['border-color'] ) ? $block_settings['border-color'] : '';

        $styles = '';

                $styles .= "{$row_classname} {$col_classname} {$block_classname} {";
            $styles .= cartify_prepare_css_styles(array(
                '--cartify_compare_similiar_products_row_border_width' => $border_top_width,
                '--cartify_compare_similiar_products_row_border_color' => $border_color,
                '--cartify_compare_similiar_products_column_head_bg_color' => $label_bg_color,
                '--cartify_compare_similiar_products_column_base_bg_color' => $base_bg_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-compare >h2{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $heading_font_family,
                'font-weight' =>  $heading_font_weight,
                'font-variation-settings' =>  !empty($heading_font_weight) ? '"wght"' . $heading_font_weight : '',
                'letter-spacing' => $heading_letter_spacing,
                'line-height' => $heading_line_height,
                'color' => $heading_color,

                'text-transform' => $heading_text_transform,
                'text-align' => $heading_text_align,

                '--agni_product_layout_compare_similar_heading_font_size_desktop' => !empty( $heading_font_size['desktop'] ) ? $heading_font_size['desktop'] : '',
                '--agni_product_layout_compare_similar_heading_font_size_laptop' => !empty( $heading_font_size['laptop'] ) ? $heading_font_size['laptop'] : '',
                '--agni_product_layout_compare_similar_heading_font_size_tab' => !empty( $heading_font_size['tab'] ) ? $heading_font_size['tab'] : '',
                '--agni_product_layout_compare_similar_heading_font_size_mobile' => !empty( $heading_font_size['mobile'] ) ? $heading_font_size['mobile'] : '',
            ));
        $styles .= "}";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} table{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $value_font_family,
                'font-weight' =>  $value_font_weight,
                'font-variation-settings' =>  !empty($value_font_weight) ? '"wght"' . $value_font_weight : '',
                'font-size' => $value_font_size,
                'letter-spacing' => $value_letter_spacing,
                'line-height' => $value_line_height,
                'color' => $value_color,

            ));
        $styles .= "}";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} table th{";
            $styles .= cartify_prepare_css_styles(array(
                'font-family' => $label_font_family,
                'font-weight' =>  $label_font_weight,
                'font-variation-settings' =>  !empty($label_font_weight) ? '"wght"' . $label_font_weight : '',
                'font-size' => $label_font_size,
                'letter-spacing' => $label_letter_spacing,
                'line-height' => $label_line_height,
                'color' => $label_color,

            ));
        $styles .= "}";



                return $styles;
    }
}


if( !function_exists( 'cartify_product_layout_css_parser' ) ){
    function cartify_product_layout_css_parser( $settings, $row_classname = '', $col_classname = '' ){

        $styles = '';
        $field_keys = array();

                $css_pattern = 'css-';
        $hover_css_pattern = 'hoverCss-';
        $array_pattern = 'cssArray-';
        $repeatable_pattern = 'repeatable';


        $block_classname = isset( $settings['className'] ) ? $settings['className'] : '';
        $classname = $block_classname; //$row_classname .' '. $col_classname .' '. $block_classname;

        foreach ($settings as $field_key => $field_value) {
            if (strpos($field_key, $css_pattern) !== false || strpos($field_key, $hover_css_pattern) !== false) {

                if(strpos($field_key, $css_pattern) !== false){
                    $field_key = str_replace($css_pattern, '', $field_key);
                    $event = 'default';
                }
                else{
                    $field_key = str_replace($hover_css_pattern, '', $field_key);
                    $event = 'hover';
                }

                if( is_array( $field_value ) ){
                    $field_keys[$event]['mobile'][$field_key] = isset( $field_value['mobile'] ) ? $field_value['mobile'] : '';
                    $field_keys[$event]['tab'][$field_key] = isset( $field_value['tab'] ) ? $field_value['tab'] : '';
                    $field_keys[$event]['laptop'][$field_key] = isset( $field_value['laptop'] ) ? $field_value['laptop'] : '';
                    $field_keys[$event]['desktop'][$field_key] = isset( $field_value['desktop'] ) ? $field_value['desktop'] : '';
                }
                else{
                    $field_keys[$event]['common'][$field_key] = $field_value;
                }
            }

            if( strpos($field_key, $array_pattern) !== false ){
                // print_r($field_value);
                $font_values = $field_value;
                $font_values['className'] = $block_classname;

                $styles .= apply_filters( 'agni_product_layout_css_parser', $font_values, $row_classname, $col_classname );
            }
        }


        $styles .= apply_filters( 'agni_product_layout_css_array_parser', $field_keys, $classname );

        return $styles;

    }
}

if( !function_exists( 'cartify_product_layout_css_array_parser' ) ){
    function cartify_product_layout_css_array_parser( $fields, $classname ){
        $styles = '';

        foreach ($fields as $event_key => $event) {
            if( $event_key == 'hover' ){
                $classname = $classname . ':hover';
            }
            foreach ($event as $device => $css) {
                if( $device == 'desktop' ){
                    $break_point = '1440px';
                }
                else if( $device == 'laptop' ){
                    $break_point = '1024px';
                }
                else if( $device == 'tab' ){
                    $break_point = '667px';
                }
                // else if( $device == 'mobile' ){
                //     $break_point = '';
                // }

                                if( $device !== 'common' && $device !== 'mobile' ){
                    $styles .= '@media (min-width: ' . $break_point . '){';
                }
                $styles .= $classname . '{';
                foreach ($css as $key => $value) {
                    if( !empty($value) ){
                        $styles .= $key . ': ' . $value . '; ';
                    }
                }
                $styles .= '}';
                if( $device !== 'common' && $device !== 'mobile' ){
                    $styles .= '}';
                }
            }
        }

        return $styles;
    }
}


// function cartify_prepare_css_styles($css_array){
//     $css_values_array = [];
//     foreach ($css_array as $key => $value) {
//         if(!empty($value)){
//             $css_values_array[] = "{$key}:{$value};";
//         }
//     }
//     return implode(' ', array_filter($css_values_array));
// }

// function cartify_prepare_responsive_values($value_array, $default = false){
//     $new_value_array = [];
//     $default_array = [];

//     $default_array = $default;

//     if( !is_array( $default ) ){
//         $default_array = array(
//             'desktop' => $default,
//             'laptop' => $default,
//             'tab' => $default,
//             'mobile' => $default,
//         );
//     }

//     $new_value_array = array(
//         'desktop' => isset( $value_array['desktop'] ) ? $value_array['desktop'] : $default_array['desktop'],
//         'laptop' => isset( $value_array['laptop'] ) ? $value_array['laptop'] : $default_array['laptop'],
//         'tab' => isset( $value_array['tab'] ) ? $value_array['tab'] : $default_array['tab'],
//         'mobile' => isset( $value_array['mobile'] ) ? $value_array['mobile'] : $default_array['mobile'],
//     );

//     // echo "value array \n\n";
//     // print_r( $new_value_array );
//     // echo "value default \n\n";
//     // print_r( $default_array );
//     // echo "value ends \n\n";

//     return $new_value_array;
// }