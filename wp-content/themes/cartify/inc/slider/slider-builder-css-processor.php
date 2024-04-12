<?php
add_filter( 'agni_slider_css', 'cartify_slider_css' );

add_filter( 'agni_slider_css_block_processor', 'cartify_slider_css_block_processor' );

add_filter( 'agni_slider_css_text', 'cartify_slider_css_text' );
add_filter( 'agni_slider_css_buttons', 'cartify_slider_css_buttons' );
add_filter( 'agni_slider_css_group', 'cartify_slider_css_group' );
add_filter( 'agni_slider_css_contentblock', 'cartify_slider_css_contentblock' );
add_filter( 'agni_slider_css_separator', 'cartify_slider_css_separator' );

add_filter( 'agni_slider_slide_css_parser', 'cartify_slider_slide_css_parser' );
add_filter( 'agni_slider_slider_css_parser', 'cartify_slider_slider_css_parser' );

add_filter( 'agni_slider_css_parser', 'cartify_slider_css_parser', 10, 1 );
add_filter( 'agni_slider_css_array_parser', 'cartify_slider_css_array_parser', 10, 2 );

if( !function_exists('cartify_slider_css') ){
    function cartify_slider_css( $slider ){

        $styles = '';

        $slider_settings = $slider['settings'];

        if( isset( $slider_settings['width-choice'] ) && !empty( $slider_settings['width-choice'] ) ){
            $slider_settings['css-max-width'] = '';
        }

        $slider_settings['className'] = '.' . $slider_settings['className'];

        $styles .= apply_filters( 'agni_slider_slider_css_parser', $slider_settings );

        if( isset( $slider['content'] ) && !empty( $slider['content'] ) ){
            foreach( $slider['content'] as $slide_key => $slide ){
                $slide['settings']['className'] = $slider_settings['className'] . ' .' . $slide['settings']['className'];
                $styles .= apply_filters( 'agni_slider_slide_css_parser', $slide['settings'] );

                foreach ($slide['content'] as $block_key => $block) {
                    // print_r($block);
                    if(isset($slide['settings']['content-animation-type']) && $slide['settings']['content-animation-type'] == 'sequential'){
                        $block['settings']['key'] = $block_key + 1;
                    }
                    $block['settings']['className'] = $slide['settings']['className'] . ' .' . $block['settings']['className'];
                    // print_r($block['settings']);

                    $styles .= apply_filters( 'agni_slider_css_parser', $block['settings'] );
                    $styles .= apply_filters( 'agni_slider_css_block_processor', $block );


                                    }
            }
        }

                return $styles;

    }

}

function cartify_slider_css_block_processor( $block ){

    $styles = '';

    switch( $block['id'] ){
        case 'text':
            $styles .= apply_filters( 'agni_slider_css_text', $block['settings'] );
            break;
        case 'buttons':
            $styles .= apply_filters( 'agni_slider_css_buttons', $block['settings'] );
            break;
        case 'content-block':
            $styles .= apply_filters( 'agni_slider_css_contentblock', $block['settings'] );
            break;
        case 'separator':
            $styles .= apply_filters( 'agni_slider_css_separator', $block['settings'] );
            break;
        case 'group':
            $styles .= apply_filters( 'agni_slider_css_group', $block['settings'] );

            foreach ($block['content'] as $key => $groupBlock) {
                $groupBlock['settings']['className'] = $block['settings']['className'] . ' .' . $groupBlock['settings']['className'];

                                $styles .= apply_filters( 'agni_slider_css_parser', $groupBlock['settings'] );
                $styles .= apply_filters( 'agni_slider_css_block_processor', $groupBlock );
            }
            break;
    }

    return $styles;
}

function cartify_slider_css_text( $block_settings ){

    $tag = isset( $block_settings['tag'] ) ? $block_settings['tag'] : '';
    $block_classname = isset( $block_settings['className'] ) ? $block_settings['className'] : '';


    // print_r( $block_settings );

    $styles = '';

    // $styles .= "
    // .agni-slider{$block_classname}{
    //     font-size: var(--cartify_font_size_small_1);
    // }
    // ";

    $new_block_settings = array();

    $css_text_pattern = 'cssText';
    foreach ($block_settings as $field_key => $field_value) {
        if(strpos($field_key, $css_text_pattern) !== false){
            $field_key = str_replace($css_text_pattern, 'css', $field_key);
            $new_block_settings[' ' . $tag][$field_key] = $field_value;
        }

    }


    foreach ($new_block_settings as $sub_classname => $field) {
        $field['className'] = isset( $block_classname ) ? $block_classname . $sub_classname : '';
        $styles .= apply_filters( 'agni_slider_css_parser', $field );
    }


    return $styles;
}

function cartify_slider_css_buttons( $settings ){
    $styles = '';

    $classname = isset($settings['className'])?$settings['className']:'';

        $repeatable_pattern = 'repeatable';

    foreach ($settings as $field_key => $field_value) {

                if( strpos($field_key, $repeatable_pattern) !== false ){
            foreach ($field_value as $index => $button) {
                $button['className'] = $classname . ' .btn-' . $index;

                if( $button['btn_size'] == 'link' ){
                    unset( $button['css-border-radius'] );
                    unset( $button['css-background-color'] );
                    unset( $button['css-border-color'] );
                    unset( $button['hoverCss-color'] );
                    unset( $button['hoverCss-background-color'] );
                    unset( $button['hoverCss-border-color'] );
                }
                $styles .= apply_filters( 'agni_slider_css_parser', $button );
            }
        }

    }

    return $styles;
}

function cartify_slider_css_group( $settings ){
    $styles = '';

    $classname = isset($settings['className'])?$settings['className']:'';

    $align = isset( $settings['align'] ) ? $settings['align'] : '';


    $styles .= "
    {$classname} {
        --agni_slider_group_align:{$align};
    }";

    // if( !empty( $gutter ) ){
    //     $styles .= "
    //     {$classname} {
    //         --agni_slider_slide_gap:{$gutter};
    //     }
    //     ";
    // }

    // $styles .= apply_filters( 'agni_slider_css_parser', $settings );


    return $styles;
}

function cartify_slider_css_contentblock( $settings ){
    $styles = '';

    $block_id = isset($settings['block'])?$settings['block']:'';
    $classname = isset($settings['className'])?$settings['className']:'';



    $block = get_post( $block_id );

    if( !empty($block->post_content) ){
        $blocks = parse_blocks( $block->post_content );

        $styles .= apply_filters( 'agni_gutenberg_blocks_css', $blocks );
    }

    return $styles;

}

function cartify_slider_css_separator( $settings ){
    $styles = '';

    $classname = isset($settings['className'])?$settings['className']:'';

    $width = isset( $settings['width'] ) ? $settings['width'] : '';
    $height = isset( $settings['height'] ) ? $settings['height'] : '';
    $color = isset( $settings['color'] ) ? $settings['color'] : '';
    $align = isset( $settings['align'] ) ? $settings['align'] : '';


    $styles .= "{$classname} {";
        $styles .= cartify_prepare_css_styles(array(
            '--agni_slider_separator_width_desktop' => isset($width['desktop']) ? $width['desktop'] : '',
            '--agni_slider_separator_width_laptop' => isset($width['laptop']) ? $width['laptop'] : '',
            '--agni_slider_separator_width_tab' => isset($width['tab']) ? $width['tab'] : '',
            '--agni_slider_separator_width_mobile' => isset($width['mobile']) ? $width['mobile'] : '',

                '--agni_slider_separator_height' => $height,
            '--agni_slider_separator_color' => $color,
            '--agni_slider_separator_align' => $align,
        ));
    $styles .= "}";

    return $styles;
}

function cartify_slider_slider_css_parser( $settings ){
    $styles = '';

    $offset_top = isset( $settings['offset-top'] ) ? $settings['offset-top'] : '';
    $gutter = isset( $settings['slides-gutter'] ) ? $settings['slides-gutter'] : '';
    $classname = isset($settings['className'])? $settings['className'] : '';


    if( !empty( $offset_top ) ){
        $styles .= "
        {$classname} {
            --agni_slider_offset_top_desktop:{$offset_top['desktop']};
            --agni_slider_offset_top_laptop:{$offset_top['laptop']};
            --agni_slider_offset_top_tab:{$offset_top['tab']};
            --agni_slider_offset_top_mobile:{$offset_top['mobile']};
        }";
    }

    if( !empty( $gutter ) ){
        $styles .= "
        {$classname} {
            --agni_slider_slide_gap:{$gutter};
        }
        ";
    }

    $styles .= apply_filters( 'agni_slider_css_parser', $settings );

    return $styles;
}

if( !function_exists( 'cartify_slider_slide_css_parser' ) ){
    function cartify_slider_slide_css_parser( $settings ){
        $styles = '';

        $background_choice = isset( $settings['background-choice'] ) ? $settings['background-choice'] : 'color';
        $css_background_color = isset( $settings['css-background-color'] ) ? $settings['css-background-color'] : '';
        $css_background_image = isset( $settings['css-background-image'] ) ? $settings['css-background-image']: '';
        $css_background_size = isset( $settings['css-background-size'] ) ? $settings['css-background-size']: '';
        $css_background_position = isset( $settings['css-background-position'] ) ? $settings['css-background-position']: '';
        $css_background_repeat = isset( $settings['css-background-repeat'] ) ? $settings['css-background-repeat']: '';
        $background_overlay = isset( $settings['background-overlay'] ) ? $settings['background-overlay']: '';
        $css_border_radius = isset( $settings['css-border-radius'] ) ? $settings['css-border-radius']: '';
        $css_border_width = isset( $settings['css-border-width'] ) ? $settings['css-border-width']: '';
        $css_border_color = isset( $settings['css-border-color'] ) ? $settings['css-border-color']: '';
        $css_padding = isset( $settings['css-padding'] ) ? $settings['css-padding']: '';

        $classname = isset($settings['className'])? $settings['className']:'';

        if( $background_choice == 'color' ){
            if( !empty( $css_background_color ) ){
                $styles .= '
                .agni-slider'.$classname.' .agni-slide__bg-color{
                    background-color: '.$css_background_color.';
                }
                ';
            }
        }
        else if( $background_choice == 'image' ){
            $styles .= '
            .agni-slider'.$classname.' .agni-slide__bg-image{
                background-image: url("'.$css_background_image.'");
                background-size: '.$css_background_size.';
                background-repeat: '.$css_background_repeat.';
            }
            ';

            if( !empty( $css_background_position ) ){
                $styles .= "
                {$classname}{
                    --agni_slider_slide_bg_image_position_mobile:{$css_background_position['mobile']};
                    --agni_slider_slide_bg_image_position_tab:{$css_background_position['tab']};
                    --agni_slider_slide_bg_image_position_laptop:{$css_background_position['laptop']};
                    --agni_slider_slide_bg_image_position_desktop:{$css_background_position['desktop']};
                }
                ";
            }

        }

        if( !empty( $css_border_radius ) && $css_border_radius != '0px' ){
            $styles .= '
            .agni-slider'.$classname.' .agni-slide__bg{
                border-radius: '.$css_border_radius.';
            }
            ';
        }

        if( !empty( $css_border_width ) && $css_border_width != '0px' ){
            $styles .= '
            .agni-slider'.$classname.' .agni-slide__bg{
                border-width: '.$css_border_width.';
                border-color: '.$css_border_color.';
            }
            ';
        }

        if( !empty( $background_overlay ) ){
            $styles .= '
            .agni-slider'.$classname.' .agni-slide__bg-overlay{
                background-color: '.$background_overlay.';
            }
            ';
        }

                if( !empty( $css_padding ) ){
            $styles .= "
            {$classname}{
                --agni_slider_slide_contents_padding_mobile:{$css_padding['mobile']};
                --agni_slider_slide_contents_padding_tab:{$css_padding['tab']};
                --agni_slider_slide_contents_padding_laptop:{$css_padding['laptop']};
                --agni_slider_slide_contents_padding_desktop:{$css_padding['desktop']};
            }
            ";
        }

        return $styles;
    }
}


if( !function_exists( 'cartify_slider_css_parser' ) ) {
    function cartify_slider_css_parser( $settings ){
        // print_r($settings);
        $styles = '';
        $field_keys = array();

        $animation_delay_duration = 250;

        $css_pattern = 'css-';
        $hover_css_pattern = 'hoverCss-';
        $array_pattern = 'cssArray-';
        $repeatable_pattern = 'repeatable';

        $classname = isset($settings['className'])?$settings['className']:'';
        $animation_delay = isset( $settings['key'] )?$settings['key'] * 250: '';

        if( !empty($animation_delay) ){
            $field_keys['default']['common']['-webkit-animation-delay'] = $animation_delay . 'ms';
            $field_keys['default']['common']['animation-delay'] = $animation_delay . 'ms';
        }

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
                    $field_keys[$event]['mobile'][$field_key] = $field_value['mobile'];
                    $field_keys[$event]['tab'][$field_key] = $field_value['tab'];
                    $field_keys[$event]['laptop'][$field_key] = $field_value['laptop'];
                    $field_keys[$event]['desktop'][$field_key] = $field_value['desktop'];
                }
                else{
                    $field_keys[$event]['common'][$field_key] = $field_value;
                }
            }

            // if( strpos($field_key, $repeatable_pattern) !== false ){
            //     foreach ($field_value as $index => $button) {
            //         $button['className'] = $classname . ' .btn-' . $index;
            //         $styles .= apply_filters( 'agni_slider_css_parser', $button );
            //     }
            // }

            if( strpos($field_key, $array_pattern) !== false ){
                // print_r($field_value);
                $font_values = $field_value;
                $font_values['className'] = $classname;

                $styles .= apply_filters( 'agni_slider_css_parser', $font_values );
            }
        }

        $styles .= apply_filters( 'agni_slider_css_array_parser', $field_keys, '.agni-slider' . $classname );

        return $styles;
    }
}

if( !function_exists( 'cartify_slider_css_array_parser' ) ){
    function cartify_slider_css_array_parser( $fields, $classname ){
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
                    if( $value !== '' && $value !== 'px' && $value !== '%' && $value !== 'em' && $value !== 'rem' && $value !== 'vw' && $value !== 'vh' ){
                        // echo "key: " . $key . "value: " . $value . "\n";
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

?>