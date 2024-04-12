<?php 
add_filter( 'agni_header_css', 'cartify_header_css' );

add_filter( 'agni_header_css_logo', 'cartify_header_css_logo', 10, 3 );
add_filter( 'agni_header_css_menu', 'cartify_header_css_menu', 10, 3 );
add_filter( 'agni_header_css_category_menu', 'cartify_header_css_category_menu', 10, 3 );
add_filter( 'agni_header_css_search', 'cartify_header_css_search', 10, 3 );
add_filter( 'agni_header_css_additional_info', 'cartify_header_css_additional_info', 10, 3 );
add_filter( 'agni_header_css_myaccount', 'cartify_header_css_myaccount', 10, 3 );
add_filter( 'agni_header_css_language', 'cartify_header_css_language', 10, 3 );
add_filter( 'agni_header_css_currency', 'cartify_header_css_currency', 10, 3 );
add_filter( 'agni_header_css_cart', 'cartify_header_css_cart', 10, 3 );
add_filter( 'agni_header_css_wishlist', 'cartify_header_css_icon', 10, 3 );
add_filter( 'agni_header_css_compare', 'cartify_header_css_icon', 10, 3 );
add_filter( 'agni_header_css_social', 'cartify_header_css_social', 10, 3 );
add_filter( 'agni_header_css_button', 'cartify_header_css_button', 10, 3 );
add_filter( 'agni_header_css_content_block', 'cartify_header_css_content_block', 10, 3 );


add_filter( 'agni_header_css_spacing_processor', 'cartify_header_css_spacing_processor', 10, 3 );

if( !function_exists( 'cartify_header_css' ) ){
    function cartify_header_css( $header ){
        $styles = '';

        // print_r( $header );

        $header_devices = array(
            'mobile' => isset( $header['content']['mobile'] ) ? $header['content']['mobile'] : array(),
            'tab' => isset( $header['content']['tab'] ) ? $header['content']['tab'] : array(),
            'laptop' => isset( $header['content']['laptop'] ) ? $header['content']['laptop'] : array(),
            'desktop' => isset( $header['content']['desktop'] ) ? $header['content']['desktop'] : array(),
        );

        foreach ($header_devices as $deviceKey => $device) {
            if( $deviceKey == 'desktop' ){
                $break_point = '1440px';
            }
            else if( $deviceKey == 'laptop' ){
                $break_point = '1024px';
            }
            else if( $deviceKey == 'tab' ){
                $break_point = '667px';
            }

            if( $deviceKey !== 'mobile' ){
                $styles .= '@media (min-width: ' . $break_point . '){';
            }

            $height = 0;
            // echo $deviceKey;

            foreach ($device as $rowKey => $row) {
                $row_settings = $row['settings'];

                $row_height_default = ($row['rowName'] == 'main') ? '80px' : '40px';

                $row_height = isset($row_settings['height']) ? $row_settings['height'] : $row_height_default;
                $row_flex_ratio = isset($row_settings['flex-ratio']) ? $row_settings['flex-ratio'] : '';
                $row_bg_image = isset($row_settings['bg-image']) ? $row_settings['bg-image'] : '';
                $row_bg_color = isset($row_settings['bg-color']) ? $row_settings['bg-color'] : '';
                $row_border_color = isset($row_settings['border-color']) ? $row_settings['border-color'] : '';


                $row_classname = isset($row_settings['className']) ? ".site-header-{$deviceKey} .{$row_settings['className']}" : '';

                $row_non_empty = false;
                foreach ($row['content'] as $colKey => $col) {
                    if( !empty($col['content']) ){
                       $row_non_empty = true;
                    }
                }
                if( ($row['rowName'] != 'sticky') && $row_non_empty ){
                    $height += (int)$row_height;
                }

                                // $styles .= "
                // {$row_classname} .site-header-{$row['rowName']}__contents--center {
                //     -webkit-box-flex: {$row_flex_ratio};
                //     -ms-flex: {$row_flex_ratio};
                //     flex: {$row_flex_ratio};
                // }
                // {$row_classname} {
                //     height: {$row_height};
                //     background-image: url('{$row_bg_image}');
                //     background-color: {$row_bg_color};
                //     border-color: {$row_border_color};
                // }
                // ";

                $styles .= "{$row_classname} .site-header-{$row['rowName']}__contents--center {";
                    $styles .= agni_header_prepare_css_styles(array(
                        '-webkit-box-flex' => $row_flex_ratio,
                        '-ms-flex' => $row_flex_ratio,
                        'flex' => $row_flex_ratio,
                    ));
                $styles .= "}";

                $styles .= "{$row_classname} {";
                    $styles .= agni_header_prepare_css_styles(array(
                        'height' => $row_height,
                        'background-image' => !empty($row_bg_image) ? 'url(' . $row_bg_image . ')' : '',
                        'background-color' => $row_bg_color,
                        'border-color' => $row_border_color,
                    ));
                $styles .= "}";

                                $styles .= apply_filters( 'agni_header_css_spacing_processor', $row['settings'], ".site-header-{$deviceKey}", '' );

                foreach ($row['content'] as $colKey => $col) {
                    // print_r($col);
                    $col_settings = isset( $col['settings'] ) ? $col['settings'] : '';

                    $col_classname = isset( $col_settings['className'] ) ? '.' . $col_settings['className'] : '';


                                                            foreach ($col['content'] as $blockKey => $block) {
                        // print_r($block);
                        $block_settings = isset($block['settings']) ? $block['settings'] : '';

                        $styles .= apply_filters( 'agni_header_css_spacing_processor', $block_settings, $row_classname, $col_classname );

                        switch( $block['id'] ){
                            case 'logo':
                                $styles .= apply_filters( 'agni_header_css_logo', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'menu-1':
                            case 'menu-2':
                                $styles .= apply_filters( 'agni_header_css_menu', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'menu-3':
                                $styles .= apply_filters( 'agni_header_css_category_menu', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'search':
                                $styles .= apply_filters( 'agni_header_css_search', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'info':
                                $styles .= apply_filters( 'agni_header_css_additional_info', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'language':
                                $styles .= apply_filters( 'agni_header_css_language', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'currency':
                                $styles .= apply_filters( 'agni_header_css_currency', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'my-account':
                                $styles .= apply_filters( 'agni_header_css_myaccount', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'cart':
                                $styles .= apply_filters( 'agni_header_css_cart', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'wishlist':
                                $styles .= apply_filters( 'agni_header_css_wishlist', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'compare':
                                $styles .= apply_filters( 'agni_header_css_compare', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'social':
                                $styles .= apply_filters( 'agni_header_css_social', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'button':
                                $styles .= apply_filters( 'agni_header_css_button', $block_settings, $row_classname, $col_classname );
                                break;
                            case 'content-block':
                                $styles .= apply_filters( 'agni_header_css_content_block', $block_settings, $row_classname, $col_classname );
                                break;
                            default:
                        }
                    }
                }
            }
            $styles .= "
                .site-header-{$deviceKey} .spacer-{$deviceKey}{
                    height: {$height}px;
                }
            ";


            if( $deviceKey !== 'mobile' ){
                $styles .= '}';
            }
        }


        return $styles;
    }
}

if( !function_exists( 'cartify_header_css_logo' ) ){
    function cartify_header_css_logo( $block_settings, $row_classname, $col_classname ){
        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $height = isset( $block_settings['css-height'] ) ? $block_settings['css-height'] : '';

        // $styles = "
        //     {$row_classname} {$col_classname} {$block_classname} a{
        //         height: {$height}
        //     }
        // ";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} a{";
            $styles .= agni_header_prepare_css_styles(array(
                'height' => $height,
            ));
        $styles .= "}";


        return $styles;
    }
}

if( !function_exists( 'cartify_header_css_menu' ) ){

    function cartify_header_css_menu( $block_settings, $row_classname, $col_classname ){

        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $max_width = isset( $block_settings['general-max-width'] ) ? $block_settings['general-max-width'] : '';
        $overflow_wrap = isset( $block_settings['general-overflow-wrap'] ) ? $block_settings['general-overflow-wrap'] : '';

        $font_family = isset( $block_settings['typo-font-choice'] ) ? $block_settings['typo-font-choice']['css-font-family'] : '';
        $font_weight = isset( $block_settings['typo-font-choice'] ) ? $block_settings['typo-font-choice']['css-font-weight'] : '';
        $font_size = isset( $block_settings['typo-font-size'] ) ? $block_settings['typo-font-size'] : '';
        $letter_spacing = isset( $block_settings['typo-letter-spacing'] ) ? $block_settings['typo-letter-spacing'] : '';
        $text_transform = isset( $block_settings['typo-text-transform'] ) ? $block_settings['typo-text-transform'] : '';

        $gutter = isset( $block_settings['typo-gutter'] ) ? $block_settings['typo-gutter'] : '';
        $color = isset( $block_settings['typo-menu-color'] ) ? $block_settings['typo-menu-color'] : '';
        $submenu_bg_color = isset( $block_settings['typo-submenu-background-color'] ) ? $block_settings['typo-submenu-background-color'] : '';
        $submenu_color = isset( $block_settings['typo-submenu-color'] ) ? $block_settings['typo-submenu-color'] : '';

        $wrap = $overflow_wrap == 'on' ? 'wrap' : '';

        // if( !empty( $gutter ) ){
        //     $styles .= "
        //         {$row_classname} {$col_classname} {$block_classname}{
        //             --cartify_header_menu_gap: {$gutter};
        //         }
        //     ";
        // }

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname}{
        //         --cartify_header_menu_max_width: {$max_width}; 
        //         --cartify_header_menu_overflow_wrap: {$wrap}; 
        //     }
        // ";

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} >ul >li >a{
        //         color: {$color}
        //     }

        //     {$row_classname} {$col_classname} {$block_classname} >ul >li ul,
        //     {$row_classname} {$col_classname} {$block_classname} >ul >li ul:before{
        //         background-color: {$submenu_bg_color}
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} >ul >li ul a{
        //         color: {$submenu_color}
        //     }
        // ";

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} a,
        //     {$row_classname} {$col_classname} {$block_classname} li >ul a{
        //         font-family: {$font_family};
        //         font-weight: {$font_weight};
        //         font-size: {$font_size}; 
        //         letter-spacing: {$letter_spacing};
        //         text-transform: {$text_transform};
        //     }
        // ";



        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= agni_header_prepare_css_styles(array(
                '--cartify_header_menu_gap' => $gutter,
                '--cartify_header_menu_max_width' => $max_width,
                '--cartify_header_menu_overflow_wrap' => $wrap,
            ));
        $styles .= "}";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-menu-contents >ul >li >a{";
            $styles .= agni_header_prepare_css_styles(array(
                'color' => $color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-menu-contents >ul >li ul,
        {$row_classname} {$col_classname} {$block_classname} .site-header-menu-contents >ul >li ul:before{";
            $styles .= agni_header_prepare_css_styles(array(
                'background-color' => $submenu_bg_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-menu-contents >ul >li ul a{";
            $styles .= agni_header_prepare_css_styles(array(
                'color' => $submenu_color,
            ));
        $styles .= "}";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} a,
        {$row_classname} {$col_classname} {$block_classname} li >ul a{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $font_family,
                'font-weight' => $font_weight,
                'font-size' => $font_size,
                'letter-spacing' => $letter_spacing,
                'text-transform' => $text_transform,
            ));
        $styles .= "}";


        return $styles;
    }
}

if( !function_exists( 'cartify_header_css_category_menu' ) ){
    function cartify_header_css_category_menu( $block_settings, $row_classname, $col_classname ){

        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $category_text_1_font_family = isset($block_settings['menu-3-typo-font-choice-1']) ? $block_settings['menu-3-typo-font-choice-1']['css-font-family'] : '';
        $category_text_1_font_weight = isset($block_settings['menu-3-typo-font-choice-1']) ? $block_settings['menu-3-typo-font-choice-1']['css-font-weight'] : '';
        $category_text_1_font_size = isset($block_settings['menu-3-typo-font-size-1']) ? $block_settings['menu-3-typo-font-size-1'] : '';
        $category_text_1_letter_spacing = isset($block_settings['menu-3-typo-letter-spacing-1']) ? $block_settings['menu-3-typo-letter-spacing-1'] : '';

                $category_text_2_font_family = isset($block_settings['menu-3-typo-font-choice-2']) ? $block_settings['menu-3-typo-font-choice-2']['css-font-family'] : '';
        $category_text_2_font_weight = isset($block_settings['menu-3-typo-font-choice-2']) ? $block_settings['menu-3-typo-font-choice-2']['css-font-weight'] : '';
        $category_text_2_font_size = isset($block_settings['menu-3-typo-font-size-2']) ? $block_settings['menu-3-typo-font-size-2'] : '';
        $category_text_2_letter_spacing = isset($block_settings['menu-3-typo-letter-spacing-2']) ? $block_settings['menu-3-typo-letter-spacing-2'] : '';

                $category_menu_title_font_family = isset($block_settings['menu-3-typo-font-choice-menu-title']) ? $block_settings['menu-3-typo-font-choice-menu-title']['css-font-family'] : '';
        $category_menu_title_font_weight = isset($block_settings['menu-3-typo-font-choice-menu-title']) ? $block_settings['menu-3-typo-font-choice-menu-title']['css-font-weight'] : '';
        $category_menu_title_font_size = isset($block_settings['menu-3-typo-font-size-menu-title']) ? $block_settings['menu-3-typo-font-size-menu-title'] : '';
        $category_menu_title_letter_spacing = isset($block_settings['menu-3-typo-letter-spacing-menu-title']) ? $block_settings['menu-3-typo-letter-spacing-menu-title'] : '';

        $category_menu_button_font_family = isset($block_settings['menu-3-typo-font-choice-menu-button']) ? $block_settings['menu-3-typo-font-choice-menu-button']['css-font-family'] : '';
        $category_menu_button_font_weight = isset($block_settings['menu-3-typo-font-choice-menu-button']) ? $block_settings['menu-3-typo-font-choice-menu-button']['css-font-weight'] : '';
        $category_menu_button_font_size = isset($block_settings['menu-3-typo-font-size-menu-button']) ? $block_settings['menu-3-typo-font-size-menu-button'] : '';
        $category_menu_button_letter_spacing = isset($block_settings['menu-3-typo-letter-spacing-menu-button']) ? $block_settings['menu-3-typo-letter-spacing-menu-button'] : '';

        $category_typo_color = isset( $block_settings['menu-3-typo-color'] ) ? $block_settings['menu-3-typo-color'] : '';

        $category_menu_repeatable = isset( $block_settings['menu-3-general-menu-choice'] ) ? $block_settings['menu-3-general-menu-choice'] : '';

        // print_r( $block_settings, $row_classname, $col_classname );

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-category-dropdown__text{
        //         font-family: {$category_text_1_font_family};
        //         font-weight: {$category_text_1_font_weight};
        //         font-size: {$category_text_1_font_size}; 
        //         letter-spacing: {$category_text_1_letter_spacing};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-category-dropdown__text-2{
        //         font-family: {$category_text_2_font_family};
        //         font-weight: {$category_text_2_font_weight};
        //         font-size: {$category_text_2_font_size}; 
        //         letter-spacing: {$category_text_2_letter_spacing};
        //     }

        //     {$row_classname} {$col_classname} {$block_classname} .category-dropdown-menu-nav-menu__title{
        //         font-family: {$category_menu_title_font_family};
        //         font-weight: {$category_menu_title_font_weight};
        //         font-size: {$category_menu_title_font_size}; 
        //         letter-spacing: {$category_menu_title_letter_spacing};
        //     }

        //     {$row_classname} {$col_classname} {$block_classname} .category-dropdown-menu-nav-menu__btn{
        //         font-family: {$category_menu_button_font_family};
        //         font-weight: {$category_menu_button_font_weight};
        //         font-size: {$category_menu_button_font_size}; 
        //         letter-spacing: {$category_menu_button_letter_spacing};
        //     }

        //     {$row_classname} {$col_classname} {$block_classname} .site-header-category-dropdown__text,
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-category-dropdown__text-2{
        //         color: {$category_typo_color};
        //     }

                    //     {$row_classname} {$col_classname} {$block_classname} .burg-icon, 
        //     {$row_classname} {$col_classname} {$block_classname} .burg-icon:before, 
        //     {$row_classname} {$col_classname} {$block_classname} .burg-icon:after{
        //         background-color: {$category_typo_color};
        //     }
        // ";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-category-dropdown__text{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $category_text_1_font_family,
                'font-weight' => $category_text_1_font_weight,
                'font-size' => $category_text_1_font_size,
                'letter-spacing' => $category_text_1_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-category-dropdown__text-2{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $category_text_2_font_family,
                'font-weight' => $category_text_2_font_weight,
                'font-size' => $category_text_2_font_size,
                'letter-spacing' => $category_text_2_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .category-dropdown-menu-nav-menu__title{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $category_menu_title_font_family,
                'font-weight' => $category_menu_title_font_weight,
                'font-size' => $category_menu_title_font_size,
                'letter-spacing' => $category_menu_title_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .category-dropdown-menu-nav-menu__btn{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $category_menu_button_font_family,
                'font-weight' => $category_menu_button_font_weight,
                'font-size' => $category_menu_button_font_size,
                'letter-spacing' => $category_menu_button_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-category-dropdown__text,
        {$row_classname} {$col_classname} {$block_classname} .site-header-category-dropdown__text-2{";
            $styles .= agni_header_prepare_css_styles(array(
                'color' => $category_typo_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .burg-icon,
        {$row_classname} {$col_classname} {$block_classname} .burg-icon:before,
        {$row_classname} {$col_classname} {$block_classname} .burg-icon:after {";
            $styles .= agni_header_prepare_css_styles(array(
                'background-color' => $category_typo_color,
            ));
        $styles .= "}";





        if( !empty( $category_menu_repeatable ) ){
            foreach ($category_menu_repeatable as $key => $menu) {

                $font_family = isset( $menu['menu-font-choice']['css-font-family'] ) ? $menu['menu-font-choice']['css-font-family'] : '';
                $font_weight = isset( $menu['menu-font-choice']['css-font-weight'] ) ? $menu['menu-font-choice']['css-font-weight'] : '';
                $font_size = isset( $menu['menu-font-size'] ) ? $menu['menu-font-size'] : '';
                $letter_spacing = isset( $menu['menu-letter-spacing'] ) ? $menu['menu-letter-spacing'] : '';
                $text_transform = isset( $menu['menu-text-transform'] ) ? $menu['menu-text-transform'] : '';
                $color = isset( $menu['menu-color'] ) ? $menu['menu-color'] : '';
                $submenu_bg_color = isset( $menu['submenu-background-color'] ) ? $menu['submenu-background-color'] : '';
                $submenu_color = isset( $menu['submenu-color'] ) ? $menu['submenu-color'] : '';

                $gap = isset( $menu['menu-vertical-gap'] ) ? $menu['menu-vertical-gap'] : '';

                $padding = ((int)$gap / 2);

                // $styles .= "
                // {$row_classname} {$col_classname} .category-menu-{$key} a{
                //     font-family: {$font_family};
                //     font-weight: {$font_weight};
                //     font-size: {$font_size}; 
                //     letter-spacing: {$letter_spacing};
                //     color: {$color};
                //     text-transform: {$text_transform};
                // }
                // {$row_classname} {$col_classname} .category-menu-{$key} .category-dropdown-menu-nav-menu__contents > ul > li{
                //     padding-top: {$padding}px;
                //     padding-bottom: {$padding}px;
                // }
                // {$row_classname} {$col_classname} .category-menu-{$key} .category-dropdown-menu-nav-menu__contents >ul >li ul{
                //     background-color: {$submenu_bg_color}
                // }
                // {$row_classname} {$col_classname} .category-menu-{$key} .category-dropdown-menu-nav-menu__contents >ul >li ul a{
                //     color: {$submenu_color}
                // }
                // ";



                $styles .= "{$row_classname} {$col_classname} {$block_classname} .category-menu-{$key} a{";
                    $styles .= agni_header_prepare_css_styles(array(
                        'font-family' => $font_family,
                        'font-weight' => $font_weight,
                        'font-size' => $font_size,
                        'letter-spacing' => $letter_spacing,
                        'color' => $color,
                        'text-transform' => $text_transform,
                    ));
                $styles .= "}";


                $styles .= "{$row_classname} {$col_classname} {$block_classname} .category-menu-{$key} .category-dropdown-menu-nav-menu__contents > ul > li{";
                    $styles .= agni_header_prepare_css_styles(array(
                        'padding-top' => !empty( $padding ) ? $padding . 'px' : '',
                        'padding-bottom' => !empty( $padding ) ? $padding . 'px' : '',
                    ));
                $styles .= "}";


                $styles .= "{$row_classname} {$col_classname} {$block_classname} .category-menu-{$key} .category-dropdown-menu-nav-menu__contents >ul >li ul{";
                    $styles .= agni_header_prepare_css_styles(array(
                        'background-color' => $submenu_bg_color,
                    ));
                $styles .= "}";


                $styles .= "{$row_classname} {$col_classname} {$block_classname} .category-menu-{$key} .category-dropdown-menu-nav-menu__contents >ul >li ul a{";
                    $styles .= agni_header_prepare_css_styles(array(
                        'color' => $submenu_color,
                    ));
                $styles .= "}";



            }
        }


                // $styles .= cartify_header_css_process_typo_menu(); //apply_filter( '',  ); 

        return $styles;
    }
}


if( !function_exists( 'cartify_header_css_search' ) ){
    function cartify_header_css_search( $block_settings, $row_classname, $col_classname ){
        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';


		        $search_general_display_style = isset( $block_settings['search-general-display-style'] ) ? $block_settings['search-general-display-style']: '1';
        $search_general_icon_custom = isset( $block_settings['search-general-icon-custom'] ) ? $block_settings['search-general-icon-custom']: '';

        $icon_size = isset($block_settings['general-icon-size']) ? $block_settings['general-icon-size'] : '';
        $search_form_width = isset( $block_settings['search-form-width'] ) ? $block_settings['search-form-width'] : '';
        $search_form_height = isset( $block_settings['search-form-height'] ) ? $block_settings['search-form-height'] : '';
        $font_family = isset( $block_settings['typo-font-choice'] ) ? $block_settings['typo-font-choice']['css-font-family'] : '';
        $font_weight = isset( $block_settings['typo-font-choice'] ) ? $block_settings['typo-font-choice']['css-font-weight'] : '';
        $font_size = isset( $block_settings['typo-font-size'] ) ? $block_settings['typo-font-size'] : '';
        $letter_spacing = isset( $block_settings['typo-letter-spacing'] ) ? $block_settings['typo-letter-spacing'] : '';

        $color = isset( $block_settings['typo-color'] ) ? $block_settings['typo-color'] : '';
        $form_bg_color = isset( $block_settings['typo-form-bg-color'] ) ? $block_settings['typo-form-bg-color'] : '';
        $form_border_color = isset( $block_settings['typo-form-border-color'] ) ? $block_settings['typo-form-border-color'] : '';

        if( !empty( $search_form_width ) ){
            $styles .= "
            {$row_classname} {$col_classname} {$block_classname} .agni-ajax-search-form{
                min-width: {$search_form_width};
            }
            ";
        }

        if( !empty( $search_form_height ) ){
            $styles .= "
            {$row_classname} {$col_classname} {$block_classname} .agni-ajax-search-form{
                height: {$search_form_height};
            }
            ";
        }

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{
        //         width: {$icon_size};
        //         height: {$icon_size};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname}{
        //         font-family: {$font_family};
        //         font-weight: {$font_weight};
        //         letter-spacing: {$letter_spacing};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} .agni-ajax-search-form{
        //         background-color: {$form_bg_color};
        //         border-color: {$form_border_color};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-search__text,
        //     {$row_classname} {$col_classname} {$block_classname} .agni-ajax-search-form input[type='text'],
        //     {$row_classname} {$col_classname} {$block_classname} .agni-ajax-search-form select{
        //         font-size: {$font_size}; 
        //     }
        // ";



        if( $search_general_display_style == '1' && !empty( $search_general_icon_custom ) ){
            $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-ajax-search-form button[type='submit']{
                background-image: url('" . $search_general_icon_custom . "');
            }";
        }



                $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{";
            $styles .= agni_header_prepare_css_styles(array(
                'width' => $icon_size,
                'height' => $icon_size,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $font_family,
                'font-weight' => $font_weight,
                'letter-spacing' => $letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .agni-ajax-search-form{";
            $styles .= agni_header_prepare_css_styles(array(
                'background-color' => $form_bg_color,
                'border-color' => $form_border_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-icon-search__text,
        {$row_classname} {$col_classname} {$block_classname} .agni-ajax-search-form input[type='text'],
        {$row_classname} {$col_classname} {$block_classname} .agni-ajax-search-form select{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-size' => $font_size,
            ));
        $styles .= "}";

        return $styles;
    }
}


if( !function_exists( 'cartify_header_css_additional_info' ) ){
    function cartify_header_css_additional_info( $block_settings, $row_classname, $col_classname ){
        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';


                $additional_info_align = isset($block_settings['typo-text-align']) ? $block_settings['typo-text-align'] : '';
        $additional_info_text_1_font_family = isset($block_settings['typo-font-choice-1']) ? $block_settings['typo-font-choice-1']['css-font-family'] : '';
        $additional_info_text_1_font_weight = isset($block_settings['typo-font-choice-1']) ? $block_settings['typo-font-choice-1']['css-font-weight'] : '';
        $additional_info_text_1_font_size = isset($block_settings['typo-font-size-1']) ? $block_settings['typo-font-size-1'] : '';
        $additional_info_text_1_letter_spacing = isset($block_settings['typo-letter-spacing-1']) ? $block_settings['typo-letter-spacing-1'] : '';
        $additional_info_text_1_line_height = isset($block_settings['typo-line-height-1']) ? $block_settings['typo-line-height-1'] : '';

                $additional_info_text_2_font_family = isset($block_settings['typo-font-choice-2']) ? $block_settings['typo-font-choice-2']['css-font-family'] : '';
        $additional_info_text_2_font_weight = isset($block_settings['typo-font-choice-2']) ? $block_settings['typo-font-choice-2']['css-font-weight'] : '';
        $additional_info_text_2_font_size = isset($block_settings['typo-font-size-2']) ? $block_settings['typo-font-size-2'] : '';
        $additional_info_text_2_letter_spacing = isset($block_settings['typo-letter-spacing-2']) ? $block_settings['typo-letter-spacing-2'] : '';
        $additional_info_text_2_line_height = isset($block_settings['typo-line-height-2']) ? $block_settings['typo-line-height-2'] : '';
        $additional_info_color = isset( $block_settings['typo-color'] ) ? $block_settings['typo-color'] : '';

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} {
        //         text-align: {$additional_info_align};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-additional-info__name{
        //         font-family: {$additional_info_text_1_font_family};
        //         font-weight: {$additional_info_text_1_font_weight};
        //         font-size: {$additional_info_text_1_font_size};
        //         letter-spacing: {$additional_info_text_1_letter_spacing};
        //     }

        //     {$row_classname} {$col_classname} {$block_classname} .site-header-additional-info__value{
        //         font-family: {$additional_info_text_2_font_family};
        //         font-weight: {$additional_info_text_2_font_weight};
        //         font-size: {$additional_info_text_2_font_size};
        //         letter-spacing: {$additional_info_text_2_letter_spacing};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname}{
        //         color: {$additional_info_color};
        //     }
        // ";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-additional-info__name{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $additional_info_text_1_font_family,
                'font-weight' => $additional_info_text_1_font_weight,
                'font-size' => $additional_info_text_1_font_size,
                'letter-spacing' => $additional_info_text_1_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-additional-info__value{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $additional_info_text_2_font_family,
                'font-weight' => $additional_info_text_2_font_weight,
                'font-size' => $additional_info_text_2_font_size,
                'letter-spacing' => $additional_info_text_2_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname}{";
            $styles .= agni_header_prepare_css_styles(array(
                'text-align' => $additional_info_align,
                'color' => $additional_info_color,
            ));
        $styles .= "}";

        return $styles;
    }
}



if( !function_exists( 'cartify_header_css_myaccount' ) ){
    function cartify_header_css_myaccount( $block_settings, $row_classname, $col_classname ){
        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $icon_size = isset($block_settings['general-icon-size']) ? $block_settings['general-icon-size'] : '';
        $my_account_text_1_font_family = isset($block_settings['typo-font-choice-1']) ? $block_settings['typo-font-choice-1']['css-font-family'] : '';
        $my_account_text_1_font_weight = isset($block_settings['typo-font-choice-1']) ? $block_settings['typo-font-choice-1']['css-font-weight'] : '';
        $my_account_text_1_font_size = isset($block_settings['typo-font-size-1']) ? $block_settings['typo-font-size-1'] : '';
        $my_account_text_1_letter_spacing = isset($block_settings['typo-letter-spacing-1']) ? $block_settings['typo-letter-spacing-1'] : '';

                $my_account_text_2_font_family = isset($block_settings['typo-font-choice-2']) ? $block_settings['typo-font-choice-2']['css-font-family'] : '';
        $my_account_text_2_font_weight = isset($block_settings['typo-font-choice-2']) ? $block_settings['typo-font-choice-2']['css-font-weight'] : '';
        $my_account_text_2_font_size = isset($block_settings['typo-font-size-2']) ? $block_settings['typo-font-size-2'] : '';
        $my_account_text_2_letter_spacing = isset($block_settings['typo-letter-spacing-2']) ? $block_settings['typo-letter-spacing-2'] : '';

                $my_account_color = isset( $block_settings['typo-color'] ) ? $block_settings['typo-color'] : '';
        $my_account_submenu_color = isset( $block_settings['typo-submenu-color'] ) ? $block_settings['typo-submenu-color'] : '';
        $my_account_submenu_bg_color = isset( $block_settings['typo-submenu-background-color'] ) ? $block_settings['typo-submenu-background-color'] : '';

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{
        //         width: {$icon_size};
        //         height: {$icon_size};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname}.site-header-icon .site-header-icon-myaccount__label{
        //         font-family: {$my_account_text_1_font_family};
        //         font-weight: {$my_account_text_1_font_weight};
        //         font-size: {$my_account_text_1_font_size};
        //         letter-spacing: {$my_account_text_1_letter_spacing};
        //     }

        //     {$row_classname} {$col_classname} {$block_classname}.site-header-icon .site-header-icon-myaccount__username{
        //         font-family: {$my_account_text_2_font_family};
        //         font-weight: {$my_account_text_2_font_weight};
        //         font-size: {$my_account_text_2_font_size};
        //         letter-spacing: {$my_account_text_2_letter_spacing};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-myaccount__label,
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-myaccount__username{
        //         color: {$my_account_color};
        //     }

        //     {$row_classname} {$col_classname} {$block_classname}.logged-in .site-header-icon-myaccount__contents{
        //         background-color: {$my_account_submenu_bg_color};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname}.logged-in .site-header-icon-myaccount__contents a{
        //         color: {$my_account_submenu_color};
        //     }

        //     {$row_classname} {$col_classname} {$block_classname} svg{
        //         fill: {$my_account_color}
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{
        //         color: {$my_account_color}
        //     }
        // ";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{";
            $styles .= agni_header_prepare_css_styles(array(
                'width' => $icon_size,
                'height' => $icon_size,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname}.site-header-icon .site-header-icon-myaccount__label{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $my_account_text_1_font_family,
                'font-weight' => $my_account_text_1_font_weight,
                'font-size' => $my_account_text_1_font_size,
                'letter-spacing' => $my_account_text_1_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname}.site-header-icon .site-header-icon-myaccount__username{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $my_account_text_2_font_family,
                'font-weight' => $my_account_text_2_font_weight,
                'font-size' => $my_account_text_2_font_size,
                'letter-spacing' => $my_account_text_2_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-icon-myaccount__label,
        {$row_classname} {$col_classname} {$block_classname} .site-header-icon-myaccount__username{";
            $styles .= agni_header_prepare_css_styles(array(
                'color' => $my_account_color
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname}.logged-in .site-header-icon-myaccount__contents{";
            $styles .= agni_header_prepare_css_styles(array(
                'background-color' => $my_account_submenu_bg_color
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname}.logged-in .site-header-icon-myaccount__contents a{";
            $styles .= agni_header_prepare_css_styles(array(
                'color' => $my_account_submenu_color
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} svg{";
            $styles .= agni_header_prepare_css_styles(array(
                'fill' => $my_account_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{";
            $styles .= agni_header_prepare_css_styles(array(
                'color' => $my_account_color,
            ));
        $styles .= "}";

        return $styles;
    }
}
if( !function_exists( 'cartify_header_css_language' ) ){
    function cartify_header_css_language( $block_settings, $row_classname, $col_classname ){
        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $language_font_family = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-family'] : '';
        $language_font_weight = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-weight'] : '';
        $language_font_size = isset($block_settings['typo-font-size']) ? $block_settings['typo-font-size'] : '';
        $language_letter_spacing = isset($block_settings['typo-letter-spacing']) ? $block_settings['typo-letter-spacing'] : '';
        $language_color = isset($block_settings['typo-color']) ? $block_settings['typo-color'] : '';

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} span,
        //     {$row_classname} {$col_classname} {$block_classname} a{
        //         font-family: {$language_font_family};
        //         font-weight: {$language_font_weight};
        //         font-size: {$language_font_size};
        //         letter-spacing: {$language_letter_spacing};
        //         color: {$language_color}
        //     }
        // ";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} span,
        {$row_classname} {$col_classname} {$block_classname} a{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $language_font_family,
                'font-weight' => $language_font_weight,
                'font-size' => $language_font_size,
                'letter-spacing' => $language_letter_spacing,
                'color' => $language_color,
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_header_css_currency' ) ){
    function cartify_header_css_currency( $block_settings, $row_classname, $col_classname ){
        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $currency_font_family = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-family'] : '';
        $currency_font_weight = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-weight'] : '';
        $currency_font_size = isset($block_settings['typo-font-size']) ? $block_settings['typo-font-size'] : '';
        $currency_letter_spacing = isset($block_settings['typo-letter-spacing']) ? $block_settings['typo-letter-spacing'] : '';
        $currency_color = isset($block_settings['typo-color']) ? $block_settings['typo-color'] : '';

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} span,
        //     {$row_classname} {$col_classname} {$block_classname} a{
        //         font-family: {$currency_font_family};
        //         font-weight: {$currency_font_weight};
        //         font-size: {$currency_font_size};
        //         letter-spacing: {$currency_letter_spacing};
        //         color: {$currency_color}
        //     }
        // ";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} span,
        {$row_classname} {$col_classname} {$block_classname} a{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $currency_font_family,
                'font-weight' => $currency_font_weight,
                'font-size' => $currency_font_size,
                'letter-spacing' => $currency_letter_spacing,
                'color' => $currency_color,
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_header_css_cart' ) ){
    function cartify_header_css_cart( $block_settings, $row_classname, $col_classname ){
        $styles = '';

                $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $icon_size = isset( $block_settings['general-icon-size'] ) ? $block_settings['general-icon-size'] : '';
        $cart_text_1_font_family = isset($block_settings['typo-font-choice-1']) ? $block_settings['typo-font-choice-1']['css-font-family'] : '';
        $cart_text_1_font_weight = isset($block_settings['typo-font-choice-1']) ? $block_settings['typo-font-choice-1']['css-font-weight'] : '';
        $cart_text_1_font_size = isset($block_settings['typo-font-size-1']) ? $block_settings['typo-font-size-1'] : '';
        $cart_text_1_letter_spacing = isset($block_settings['typo-letter-spacing-1']) ? $block_settings['typo-letter-spacing-1'] : '';

                $cart_text_2_font_family = isset($block_settings['typo-font-choice-2']) ? $block_settings['typo-font-choice-2']['css-font-family'] : '';
        $cart_text_2_font_weight = isset($block_settings['typo-font-choice-2']) ? $block_settings['typo-font-choice-2']['css-font-weight'] : '';
        $cart_text_2_font_size = isset($block_settings['typo-font-size-2']) ? $block_settings['typo-font-size-2'] : '';
        $cart_text_2_letter_spacing = isset($block_settings['typo-letter-spacing-2']) ? $block_settings['typo-letter-spacing-2'] : '';
        $cart_color = isset( $block_settings['typo-color'] ) ? $block_settings['typo-color'] : '';

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{
        //         width: {$icon_size};
        //         height: {$icon_size};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-cart__text{
        //         font-family: {$cart_text_1_font_family};
        //         font-weight: {$cart_text_1_font_weight};
        //         font-size: {$cart_text_1_font_size};
        //         letter-spacing: {$cart_text_1_letter_spacing};
        //     }

        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-cart__amount{
        //         font-family: {$cart_text_2_font_family};
        //         font-weight: {$cart_text_2_font_weight};
        //         font-size: {$cart_text_2_font_size};
        //         letter-spacing: {$cart_text_2_letter_spacing};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} > a{
        //         color: {$cart_color};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} svg{
        //         fill: {$cart_color};
        //     }
        // ";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{";
            $styles .= agni_header_prepare_css_styles(array(
                'width' => $icon_size,
                'height' => $icon_size,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-icon-cart__text{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $cart_text_1_font_family,
                'font-weight' => $cart_text_1_font_weight,
                'font-size' => $cart_text_1_font_size,
                'letter-spacing' => $cart_text_1_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-icon-cart__amount{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $cart_text_2_font_family,
                'font-weight' => $cart_text_2_font_weight,
                'font-size' => $cart_text_2_font_size,
                'letter-spacing' => $cart_text_2_letter_spacing,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} >a{";
            $styles .= agni_header_prepare_css_styles(array(
                'color' => $cart_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} svg{";
            $styles .= agni_header_prepare_css_styles(array(
                'fill' => $cart_color,
            ));
        $styles .= "}";

        return $styles;
    }
}
// if( !function_exists( 'cartify_header_css_wishlist' ) ){
//     function cartify_header_css_wishlist( $block_settings, $row_classname, $col_classname ){
//         $styles = '';

//         $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';


//         return $styles;
//     }
// }
if( !function_exists( 'cartify_header_css_icon' ) ){
    function cartify_header_css_icon( $block_settings, $row_classname, $col_classname ){
        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $icon_size = isset($block_settings['general-icon-size']) ? $block_settings['general-icon-size'] : '';
        $compare_font_family = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-family'] : '';
        $compare_font_weight = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-weight'] : '';
        $compare_font_size = isset($block_settings['typo-font-size']) ? $block_settings['typo-font-size'] : '';
        $compare_letter_spacing = isset($block_settings['typo-letter-spacing']) ? $block_settings['typo-letter-spacing'] : '';
        $compare_color = isset($block_settings['typo-color']) ? $block_settings['typo-color'] : '';

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{
        //         width: {$icon_size};
        //         height: {$icon_size};
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} > a{
        //         font-family: {$compare_font_family};
        //         font-weight: {$compare_font_weight};
        //         font-size: {$compare_font_size};
        //         letter-spacing: {$compare_letter_spacing};
        //         color: {$compare_color}
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} svg{
        //         fill: {$compare_color}
        //     }
        // ";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} .site-header-icon-container{";
            $styles .= agni_header_prepare_css_styles(array(
                'width' => $icon_size,
                'height' => $icon_size,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} >a{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $compare_font_family,
                'font-weight' => $compare_font_weight,
                'font-size' => $compare_font_size,
                'letter-spacing' => $compare_letter_spacing,
                'color' => $compare_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} svg{";
            $styles .= agni_header_prepare_css_styles(array(
                'fill' => $compare_color,
            ));
        $styles .= "}";

        return $styles;
    }
}
if( !function_exists( 'cartify_header_css_social' ) ){
    function cartify_header_css_social( $block_settings, $row_classname, $col_classname ){
        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $social_font_family = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-family'] : '';
        $social_font_weight = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-weight'] : '';
        $social_font_size = isset($block_settings['typo-font-size']) ? $block_settings['typo-font-size'] : '';
        $social_letter_spacing = isset($block_settings['typo-letter-spacing']) ? $block_settings['typo-letter-spacing'] : '';
        $social_color = isset($block_settings['typo-color']) ? $block_settings['typo-color'] : '';

        // $styles .= "
        //     {$row_classname} {$col_classname} {$block_classname} a{
        //         font-family: {$social_font_family};
        //         font-weight: {$social_font_weight};
        //         font-size: {$social_font_size};
        //         letter-spacing: {$social_letter_spacing};
        //         color: {$social_color}
        //     }
        //     {$row_classname} {$col_classname} {$block_classname} i{
        //         color: {$social_color}
        //     }
        // ";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} a{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $social_font_family,
                'font-weight' => $social_font_weight,
                'font-size' => $social_font_size,
                'letter-spacing' => $social_letter_spacing,
                'color' => $social_color,
            ));
        $styles .= "}";

        $styles .= "{$row_classname} {$col_classname} {$block_classname} i{";
            $styles .= agni_header_prepare_css_styles(array(
                'color' => $social_color,
            ));
        $styles .= "}";

        return $styles;
    }
}

if( !function_exists( 'cartify_header_css_button' ) ){
    function cartify_header_css_button( $block_settings, $row_classname, $col_classname ){
        $styles = '';

                $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';    

                $border_radius = isset($block_settings['border-radius']) ? $block_settings['border-radius'] : '';

        $color = isset($block_settings['color']) ? $block_settings['color'] : '';
        $border_color = isset($block_settings['border-color']) ? $block_settings['border-color'] : '';
        $background_color = isset($block_settings['background-color']) ? $block_settings['background-color'] : '';
        $hover_color = isset($block_settings['hover-color']) ? $block_settings['hover-color'] : '';
        $hover_border_color = isset($block_settings['hover-border-color']) ? $block_settings['hover-border-color'] : '';
        $hover_background_color = isset($block_settings['hover-background-color']) ? $block_settings['hover-background-color'] : '';

        $font_family = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-family'] : '';
        $font_weight = isset($block_settings['typo-font-choice']) ? $block_settings['typo-font-choice']['css-font-weight'] : '';
        $font_size = isset($block_settings['typo-font-size']) ? $block_settings['typo-font-size'] : '';
        $letter_spacing = isset($block_settings['typo-letter-spacing']) ? $block_settings['typo-letter-spacing'] : '';
        $text_transform = isset($block_settings['typo-text-transform']) ? $block_settings['typo-text-transform'] : '';

        $styles .= "
            {$row_classname} {$col_classname} {$block_classname} a{
                border-radius: {$border_radius};
                color: {$color};
                background-color: {$background_color};
                border-color: {$border_color};
            }
            {$row_classname} {$col_classname} {$block_classname} a:hover {
                color: {$hover_color};
                background-color: {$hover_background_color};
                border-color: {$hover_border_color};
            }
        ";


        $styles .= "{$row_classname} {$col_classname} {$block_classname} a{";
            $styles .= agni_header_prepare_css_styles(array(
                'font-family' => $font_family,
                'font-weight' => $font_weight,
                'font-size' => $font_size,
                'letter-spacing' => $letter_spacing,
                'text-transform' => $text_transform,
            ));
        $styles .= "}";

        return $styles;
    }
}


if( !function_exists( 'cartify_header_css_content_block' ) ){
    function cartify_header_css_content_block( $block_settings, $row_classname, $col_classname ){
        $styles = '';

        $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

        $width = isset($block_settings['content-block-width']) ? $block_settings['content-block-width'] : '';


               $styles .= "
            {$row_classname} {$col_classname} {$block_classname} {
                width: {$width};
            }
        ";

        return $styles;
    }
}


function cartify_header_css_spacing_processor( $block_settings, $row_classname, $col_classname ){
    $styles = "";

    $block_classname = isset( $block_settings['className'] ) ? '.' . $block_settings['className'] : '';

    $padding = isset($block_settings['styling-padding']) ? $block_settings['styling-padding'] : '';
    if( !empty( $padding ) ){
        $styles .= "
            {$row_classname} {$col_classname} {$block_classname} {
                padding: {$padding}
            }
        ";
    }
    return $styles;
}


function agni_header_prepare_css_styles($css_array){
    $css_values_array = [];
    foreach ($css_array as $key => $value) {
        // if(!empty($value)){
        //     $css_values_array[] = "{$key}:{$value};";
        // }
        if($value !== '' && $value !== 'px' && $value !== '%' && $value !== 'em' && $value !== 'rem' && $value !== 'vw' && $value !== 'vh'){
            $css_values_array[] = "{$key}:{$value};";
        }
    }
    return implode(' ', array_filter($css_values_array));
}

?>