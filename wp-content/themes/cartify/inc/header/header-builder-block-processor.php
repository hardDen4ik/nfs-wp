<?php
// actions
add_action( 'agni_before_footer', 'cartify_header_myaccount_login_form', 10, 1 );
add_action( 'agni_before_footer', 'cartify_header_sidecart', 20, 1 );

// filters
add_filter( 'agni_header_social_icons', 'cartify_header_social_icons' , 10, 1 );
add_filter( 'agni_header_myaccount', 'cartify_header_myaccount' , 10, 1 );
add_filter( 'agni_header_categories_dropdown', 'cartify_header_categories_dropdown' , 10, 1 );
add_filter( 'agni_header_cart', 'cartify_header_cart' , 10, 1 );
add_filter( 'agni_header_wishlist', 'cartify_header_wishlist' , 10, 1 );
add_filter( 'agni_header_logo', 'cartify_header_logo' , 10, 1 );
add_filter( 'agni_header_menu', 'cartify_header_menu' , 10, 1 );
add_filter( 'agni_header_additional_menu', 'cartify_header_additional_menu' , 10, 1 );
add_filter( 'agni_header_search', 'cartify_header_search' , 10, 1 );
add_filter( 'agni_header_additional_info', 'cartify_header_additional_info' , 10, 1 );
add_filter( 'agni_header_language', 'cartify_header_language' , 10, 1 );
add_filter( 'agni_header_currency', 'cartify_header_currency' , 10, 1 );
add_filter( 'agni_header_compare', 'cartify_header_compare' , 10, 1 );
add_filter( 'agni_header_button', 'cartify_header_button', 10, 1 );
add_filter( 'agni_header_content_block', 'cartify_header_content_block', 10, 1 );

// ajax filter 
add_filter('agni_ajax_header_cart_get_count', 'cartify_ajax_header_cart_get_count');
add_filter('agni_ajax_header_cart_get_amount', 'cartify_ajax_header_cart_get_amount');

if( !function_exists('cartify_header_social_icons') ){
    function cartify_header_social_icons($block_options){

        $social_icons_repeatable = isset( $block_options['social-icon-general-icons'] )?$block_options['social-icon-general-icons']: array( array( 'icon' => 'lni lni-facebook', 'link' => 'facebook.com', 'text' => 'Facebook' ), array( 'icon' => 'lni lni-twitter', 'link' => 'twitter.com' ) );
        $social_icons_link_target = isset( $block_options['social-icon-general-link-target'] )?$block_options['social-icon-general-link-target']: '_self';


        $social_icons_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";


        $social_icons_classes = array(
            'site-header-social-icons',
            $social_icons_class_name
        );

        ?>

        <?php if( !empty($social_icons_repeatable) ){ ?>
            <div class="<?php echo esc_attr( cartify_prepare_classes( $social_icons_classes ) ); ?>">
                <?php foreach ($social_icons_repeatable as $key => $social_icon) {
                    $social_icon_icon = isset($social_icon['icon'])?$social_icon['icon']:'';
                    $social_icon_text = isset($social_icon['text'])?$social_icon['text']:'';
                    $social_icon_link = isset($social_icon['link'])?$social_icon['link']:'';

                    ?>
                    <div class="site-header-social-icon">
                        <a href="<?php echo esc_url( $social_icon_link ); ?>" target="<?php echo esc_attr( $social_icons_link_target ); ?>" class="site-header-social-icon__link">
                            <i class="<?php echo esc_attr( $social_icon_icon ); ?>"></i>
                            <span class="site-header-social-icon__text"><?php echo esc_html( $social_icon_text ); ?></span>
                        </a>
                    </div>
                    <?php
                } ?>
            </div>
        <?php
        }
    }
}

if( !function_exists('cartify_header_myaccount') ){
    function cartify_header_myaccount($block_options){
        if( !class_exists('WooCommerce') ){
            return;
        }

        $my_account_general_icon_choice = isset($block_options['my-account-general-icon-choice'])?$block_options['my-account-general-icon-choice']:'';
        $my_account_general_icon_predefined = isset($block_options['my-account-general-icon-predefined'])?$block_options['my-account-general-icon-predefined']:'myaccount';
        $my_account_general_icon_custom = isset($block_options['my-account-general-icon-custom'])?$block_options['my-account-general-icon-custom']:'';
        $my_account_general_display_style = isset($block_options['my-account-general-display-style'])?$block_options['my-account-general-display-style']:'1';
        $my_account_general_text = isset($block_options['my-account-general-text'])?$block_options['my-account-general-text']:'';
        $my_account_general_text_2 = isset($block_options['my-account-general-text-2'])?$block_options['my-account-general-text-2']:'';
        $my_account_general_alignment = isset($block_options['my-account-general-alignment'])?$block_options['my-account-general-alignment']:'it';
        $my_account_general_show_login_form = isset($block_options['my-account-general-show-login-form'])?$block_options['my-account-general-show-login-form']:'on';
        $my_account_general_menu_choice = isset($block_options['my-account-general-menu-choice'])?$block_options['my-account-general-menu-choice']:'primary-menu';

        $my_account_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";

        if( !cartify_get_theme_option( 'agni_header_block_field_show_login_form', '' ) ){
            set_theme_mod( 'agni_header_block_field_show_login_form', $my_account_general_show_login_form == 'on' ? '1' : '' );
        }


        $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );

        if( $my_account_general_icon_choice == 'custom' ){
            $icon = '<img src="' . esc_url( $my_account_general_icon_custom ) . '" />';
        }
        else{
            $icon = cartify_get_icon_svg( 'common', $my_account_general_icon_predefined);
        }

        if ( is_user_logged_in() && $my_account_general_display_style == '2' ) {
            $current_user = wp_get_current_user();
            $my_account_general_text = !empty( $my_account_general_text )?$my_account_general_text: esc_html__('Howdy,', 'cartify');
            $my_account_general_text_2 = $current_user->display_name;
        }

        $social_login_fb_show = cartify_get_theme_option( 'shop_settings_social_login_fb_show', '1' );
        $social_login_google_show = cartify_get_theme_option( 'shop_settings_social_login_google_show', '1' );


        $my_account_classes = array(
            'site-header-icon-myaccount',
            'site-header-icon',
            'style-' . $my_account_general_display_style,
            $my_account_general_alignment,
            is_user_logged_in() ? 'logged-in': 'logged-out',
            ($my_account_general_show_login_form == 'off') ? 'no-login-form' : '',
            $my_account_class_name
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $my_account_classes ) ); ?>">
            <a href="<?php echo esc_url( get_permalink( $myaccount_page_id ) ); ?>" class="site-header-icon-myaccount__link">
                <div class="site-header-icon-myaccount__container">
                    <span class="site-header-icon-container">
                        <?php if ( is_user_logged_in() && $my_account_general_display_style == '2') { 
                            $current_user = wp_get_current_user();
                            echo get_avatar( $current_user->ID, 32 );
                        }
                        else {
                            echo apply_filters('agni_header_icons_myaccount_icon', $icon );  
                        } ?>
                    </span>
                </div>
                <?php if( !empty( $my_account_general_text ) || !empty( $my_account_general_text_2 )){ ?>
                    <div class="site-header-icon-myaccount__details">
                        <div class="site-header-icon-myaccount__text">
                            <span class="site-header-icon-myaccount__label"><?php echo esc_html( $my_account_general_text ); ?></span>
                            <?php if( $my_account_general_display_style == '2' && !empty( $my_account_general_text_2 ) ){ ?>
                                <span class="site-header-icon-myaccount__username"><?php echo esc_html( $my_account_general_text_2 ); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </a>
            <?php if( is_user_logged_in() ){ ?>
                <div class="site-header-icon-myaccount__contents">
                    <div class="site-header-icon-myaccount__menu">
                        <?php if ( !empty($my_account_general_menu_choice) ) { 
                            $args = array(
                                'menu' => $my_account_general_menu_choice,
                                'menu_class' => 'site-header-myaccount-menu',
                                'menu_id' => 'site-header-myaccount-menu',
                                'container' => false,
                            );
                            echo wp_nav_menu( $args ); 
                        } 

                                                // if ( is_front_page() || is_home() ) {
                        //     $url = get_home_url();
                        // } else {
                        //     $url = get_permalink();
                        // }

                        global $wp;
                        $url = home_url( $wp->request );

                        ?>
                        <div class="site-header-icon-myaccount__logout-btn"><a href="<?php echo wp_logout_url( $url ); ?>"><?php echo esc_html__( 'Log Out', 'cartify' ); ?></a></div>
                    </div>
                </div>
            <?php } ?>
            <?php //if( $my_account_general_show_login_form == 'on'  && !is_user_logged_in() && $myaccount_page_id != get_the_id() ){ 
                /* ?>
                <div class="site-header-icon-myaccount__contents">
                    <div class="site-header-icon-myaccount-panel">
                        <div class="site-header-icon-myaccount-panel__overlay"></div>
                        <div class="site-header-icon-myaccount-panel__container">
                            <?php //if(  ){ ?>
                            <div class="site-header-icon-myaccount-panel__title">
                                <h3><?php echo esc_html( 'Login/Register', 'cartify' ); ?></h3>
                                <span class="site-header-icon-myaccount-panel__close"><i class="lni lni-close"></i></span>
                            </div>
                            <?php //} ?>
                            <div class="site-header-icon-myaccount-panel__contents">
                                <div class="site-header-icon-myaccount__register">
                                    <span><?php echo esc_html('Don\'t have an account', 'cartify');?></span>
                                    <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="site-header-icon-myaccount__register-btn btn btn-bold btn-block btn-lg btn-alt"><?php echo esc_html__( 'Register', 'cartify' ); ?></a>
                                </div>
                                <div class="site-header-icon-myaccount__login--form">
                                    <?php // do_action( 'agni_header_woocommerce_login_form' ); ?>
                                </div>
                                <div class="site-header-icon-myaccount__login--social">
                                    <?php if( $social_login_fb_show ){?>
                                        <button id="login-btn-facbook" class="site-header-icon-myaccount__login-btn--facbook btn btn-block btn-bold btn-lg"><?php echo esc_html__( 'Continue with Facebook', 'cartify'); ?></button>
                                    <?php } ?>
                                    <?php if( $social_login_google_show ){ ?>
                                        <button id="login-btn-google" class="site-header-icon-myaccount__login-btn--google btn btn-block btn-bold btn-lg"><?php echo esc_html__( 'Continue with Google', 'cartify'); ?></button>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="site-header-icon-myaccount__loader"><?php echo esc_html__( 'Loading', 'cartify' ); ?></div>
                        </div>

                                            </div>
                </div>
            <?php */ 
            //} ?>
        </div>
        <?php
    }
}

if( !function_exists('cartify_header_categories_dropdown') ){
    function cartify_header_categories_dropdown($block_options) {

        $category_dropdown_choice = isset($block_options['menu-3-general-dropdown-style'])?$block_options['menu-3-general-dropdown-style']:'1';
        $category_display_choice = isset($block_options['menu-3-general-display-style'])?$block_options['menu-3-general-display-style']:'1';
        $category_dropdown_active = isset( $block_options['menu-3-general-dropdown-active'] ) ? $block_options['menu-3-general-dropdown-active'] : '';
        $category_display_text = isset($block_options['menu-3-general-display-text'])?$block_options['menu-3-general-display-text']:'Shop by';
        $category_display_text_2 = isset($block_options['menu-3-general-display-text-2'])?$block_options['menu-3-general-display-text-2']:'Categories';
        $category_icon_choice = isset($block_options['menu-3-general-icon-choice'])?$block_options['menu-3-general-icon-choice']:'';
        $category_icon_predefined = isset($block_options['menu-3-general-icon-predefined'])?$block_options['menu-3-general-icon-predefined']:'';
        $category_icon_custom = isset($block_options['menu-3-general-icon-custom'])?$block_options['menu-3-general-icon-custom']:'';
        $category_submenu_arrows = isset($block_options['menu-3-general-submenu-arrows'])?$block_options['menu-3-general-submenu-arrows']:'on';
        $category_highlight_menu = isset($block_options['menu-3-general-highlight-menu'])?$block_options['menu-3-general-highlight-menu']:'on';
        $category_user_info = isset($block_options['menu-3-general-user-info'])?$block_options['menu-3-general-user-info']:'';
        $category_menu_choice_repeatable = isset($block_options['menu-3-general-menu-choice'])?$block_options['menu-3-general-menu-choice']:array( array( 'menu_choice' => 'primary' ), array( 'menu_choice' => 'secondary' ), array() );
        $menu_click_choice = isset( $block_options['mobile-menu-click-choice'] ) ? $block_options['mobile-menu-click-choice'] : '';

        $category_menu_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";



        if( $category_icon_choice == 'custom' ){
            $icon = '<img src="' . esc_url( $category_icon_custom ) . '" />';
        }
        else{
            $icon = cartify_get_icon_svg( 'common', $category_icon_predefined );
        }


        // $category_menus 
        $category_menus = '';

        ob_start();
        foreach ($category_menu_choice_repeatable as $key => $category_menu_options) {
            # code...
            $category_menu_choice = isset($category_menu_options['menu_choice'])?$category_menu_options['menu_choice']:'';
            $category_menu_title = isset($category_menu_options['menu_title'])?$category_menu_options['menu_title']:'';
            $category_menu_btn_text = isset($category_menu_options['menu_btn_text'])?$category_menu_options['menu_btn_text']:'';
            $category_menu_btn_link = isset($category_menu_options['menu_btn_link'])?$category_menu_options['menu_btn_link']:'#';

            $category_nav_menu_classes = array(
                'category-dropdown-menu-nav-menu',
                'category-menu-' . $key,
                ( $category_highlight_menu == 'on' ) ? 'highlight-current-menu' : '',
                ( $category_submenu_arrows == 'on' ) ? 'has-arrow' : '',
                ( $menu_click_choice == 'dropdown' ) ? 'has-dropdown-on-click' : '',
            )

            ?>
            <div class="<?php echo esc_attr( cartify_prepare_classes( $category_nav_menu_classes ) ); ?>">
                <?php if( !empty( $category_menu_title ) || !empty( $category_menu_btn_text ) ){ ?>
                    <div class="category-dropdown-menu-nav-menu__header">
                        <?php if( !empty( $category_menu_title ) ){ ?>
                            <h6 class="category-dropdown-menu-nav-menu__title"><?php echo esc_html( $category_menu_title ); ?></h6>
                        <?php } ?>
                        <?php if( !empty( $category_menu_btn_text ) ){ ?>
                            <a href="<?php echo esc_url( $category_menu_btn_link ); ?>" class="category-dropdown-menu-nav-menu__btn"><?php echo esc_html( $category_menu_btn_text ); ?></a>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="category-dropdown-menu-nav-menu__contents">
                    <?php // if( !empty( $category_menu_choice ) ){
                        apply_filters( 'agni_header_menu_nav_cateogory_dropdown', cartify_header_menu_nav( $category_menu_choice, 'category' ) );
                    /* }
                    else{ ?>
                        <a href="#"><?php echo esc_html__( 'Setup menu', 'cartify' ); ?></a>
                    <?php } */ ?>
                </div>
            </div>
            <?php

        }

        $category_menus = ob_get_clean();
        // echo "menu active";
        // print_r( $category_dropdown_active );
        $category_classes = array(
            'site-header-category-dropdown',
            'toggle-style-' . $category_display_choice,
            $category_menu_class_name
        );

        $category_menu_classes = array(
            'category-dropdown-menu',
            'dropdown-style-' . $category_dropdown_choice,
            ( $category_dropdown_active == 'on' ) ? 'active' : '',
        )

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $category_classes ) ); ?>">
            <div class="site-header-category-dropdown__toggle">
                <?php echo apply_filters('agni_header_icons_category_icon', $icon ); ?>
                <?php switch ($category_display_choice) {
                    case '1': ?>
                        <div class="site-header-category-dropdown__details">
                            <span class="site-header-category-dropdown__text"><?php echo esc_html($category_display_text); ?></span>
                            <span class="site-header-category-dropdown__text-2"><?php echo esc_html($category_display_text_2); ?></span>
                        </div>
                    <?php break;
                    case '2': ?>
                        <div class="site-header-category-dropdown__text"><?php echo esc_html($category_display_text); ?><i class="lni lni-chevron-down"></i></div>
                    <?php break; 
                } ?>
            </div>
            <div class="<?php echo esc_attr( cartify_prepare_classes( $category_menu_classes ) ); ?>">  
                <div class="category-dropdown-menu__overlay"></div>
                <div class="category-dropdown-menu__container">
                    <?php if( class_exists('WooCommerce') ){
                        if( $category_user_info == 'on' ){ 
                        $current_user = wp_get_current_user();
                        ?>
                        <div class="category-dropdown-menu__user-info">
                            <?php if( is_user_logged_in() ){ 
                                echo wp_kses( get_avatar( $current_user->user_login, '32' ), 'img' ); 
                            } ?>
                            <span>
                                <?php echo esc_html__( 'Howdy, ', 'cartify' ); ?>
                                <?php if( is_user_logged_in() ){ 
                                    echo esc_html( $current_user->display_name );
                                }
                                else{
                                    echo esc_html__( 'Guest', 'cartify' );
                                } ?>
                            </span>
                            <?php if( !is_user_logged_in() ){ ?>
                                <a href="<?php echo esc_url( wc_get_page_permalink('myaccount') ); ?>"><?php echo esc_html__( 'Sign in', 'cartify' ); ?></a>
                            <?php } ?>
                        </div>
                        <?php }
                    } ?>
                    <div class="category-dropdown-menu__nav-menus">
                        <?php echo wp_kses_post( $category_menus ); ?>
                    </div>
                    <?php if( $category_dropdown_active !== 'on' ){ ?>
                        <span class="category-dropdown-menu__close"><i class="lni lni-close"></i></span>
                    <?php } ?>

                </div>
            </div>
        </div>
        <?php
    }
}

if( !function_exists('cartify_header_cart') ){
    function cartify_header_cart($block_options){

        if( !class_exists('WooCommerce') ){
            return;
        }

        $cart_general_icon_choice = isset($block_options['cart-general-icon-choice'])?$block_options['cart-general-icon-choice']:'';
        $cart_general_icon_predefined = isset($block_options['cart-general-icon-predefined'])?$block_options['cart-general-icon-predefined']:'cart';
        $cart_general_icon_custom = isset($block_options['cart-general-icon-custom'])?$block_options['cart-general-icon-custom']:'';
        $cart_general_display_style = isset($block_options['cart-general-display-style'])?$block_options['cart-general-display-style']:'1';
        $cart_general_text = isset($block_options['cart-general-text'])?$block_options['cart-general-text']:'';
        $cart_general_show_count = isset($block_options['cart-general-show-count'])?$block_options['cart-general-show-count']:'on';
        $cart_general_dropdown_style = isset($block_options['cart-general-dropdown-style'])?$block_options['cart-general-dropdown-style']:'1';

                $cart_general_show_amount = isset($block_options['cart-general-show-amount'])?$block_options['cart-general-show-amount']:'off';


                $cart_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";


        if( !cartify_get_theme_option( 'agni_header_block_field_show_sidecart', '' ) ){
            set_theme_mod( 'agni_header_block_field_show_sidecart', $cart_general_dropdown_style != '' ? '1' : '' );
        }

        if( $cart_general_icon_choice == 'custom' ){
            $icon = '<img src="' . esc_url( $cart_general_icon_custom ) . '" />';
        }
        else{
            $icon = cartify_get_icon_svg( 'common', $cart_general_icon_predefined);
        }

        $cart_classes = array(
            'site-header-icon-cart',
            'site-header-icon',
            'style-' . $cart_general_display_style,
            ($cart_general_dropdown_style == '') ? 'no-sidecart' : '',
            $cart_class_name
        );

        ?>

        <div class="<?php echo esc_attr( cartify_prepare_classes( $cart_classes ) ); ?>">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="site-header-icons-cart__link">
                <div class="site-header-icon-cart__details">
                    <?php if( !empty( $cart_general_text ) ){ ?>
                        <span class="site-header-icon-cart__text"><?php echo esc_html( $cart_general_text ); ?></span>
                    <?php } ?>
                    <?php if( $cart_general_show_amount == 'on' ){ ?>
                        <?php echo apply_filters('agni_ajax_header_cart_get_amount', 'cartify_ajax_header_cart_get_amount'); ?>
                    <?php } ?>
                </div>
                <div class="site-header-icon-cart__container">
                    <?php if( $cart_general_show_count == 'on'){ ?>
                        <?php echo apply_filters('agni_ajax_header_cart_get_count', 'cartify_ajax_header_cart_get_count'); ?>
                    <?php } ?>
                    <span class="site-header-icon-container"><?php echo apply_filters('agni_header_icons_cart_icon', $icon) ?></span>
                </div>
            </a>

            <?php // do_action( 'agni_ajax_sidecart' ); ?>
        </div>
        <?php 
    }
}

if( !function_exists('cartify_header_wishlist') ){
    function cartify_header_wishlist($block_options){

        if( !class_exists('WooCommerce') ){
            return;
        }

        $wishlist_general_icon_choice = isset($block_options['wishlist-general-icon-choice'])?$block_options['wishlist-general-icon-choice']:'';
        $wishlist_general_icon_predefined = isset($block_options['wishlist-general-icon-predefined'])?$block_options['wishlist-general-icon-predefined']:'wishlist';
        $wishlist_general_icon_custom = isset($block_options['wishlist-general-icon-custom'])?$block_options['wishlist-general-icon-custom']:'';
        $wishlist_general_text = isset( $block_options['wishlist-general-text'] ) ? $block_options['wishlist-general-text'] : '';


        $wishlist_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";

        if( $wishlist_general_icon_choice == 'custom' ){
            $icon = '<img src="' . esc_url( $wishlist_general_icon_custom ) . '" />';
        }
        else{
            $icon = cartify_get_icon_svg( 'common', $wishlist_general_icon_predefined );
        }

        $wishlist_classes = array(
            'site-header-icon-wishlist',
            'site-header-icon',
            $wishlist_class_name
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $wishlist_classes ) ); ?>">
            <a href="<?php echo esc_url( wc_get_account_endpoint_url('wishlist') ); ?>" class="site-header-icon-wishlist__link">
                <div class="site-header-icon-wishlist__details"><?php 
                if( !empty( $wishlist_general_text ) ){ ?>
                    <span class="site-header-icon-wishlist__text"><?php echo esc_html($wishlist_general_text); ?></span>
                <?php } 
                ?></div>
                <span class="site-header-icon-container"><?php echo apply_filters('agni_header_icons_wishlist_icon', $icon) ?></span>
            </a>
        </div>
        <?php
    }
}

if( !function_exists('cartify_header_logo') ){
    function cartify_header_logo($block_options){


        $site_logo_default = '';
        $theme_options_site_logo_url = cartify_get_theme_option('header_settings_general_logo_1_url', $site_logo_default);


        $site_logo_url = '';

                if( isset( $block_options['logo-general-url'] ) ){
            $site_logo_url = $block_options['logo-general-url'];
        }
        else{
            $site_logo_url = $theme_options_site_logo_url;
        }

        $site_logo_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";

        $site_logo_classes = array(
            'site-header-logo',
            $site_logo_class_name
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $site_logo_classes ) ); ?>">
            <div class="site-title">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title-logo">
                    <?php
                    if ( !empty($site_logo_url) ) {
                        $logo = wp_kses( sprintf( '<img width="83" height="32" src="%s" />', esc_url( $site_logo_url ) ), array( 'img' => array( 'src' => array(), 'width' => array(), 'height' => array() ) ) );
                    } else {
                        $logo = cartify_get_icon_svg('site', 'logo');
                    } 

                    echo apply_filters('agni_header_logo_icon', $logo);
                    ?>
                </a>

                            </div>
        </div>
        <?php
    }
}

if( !function_exists('cartify_header_menu') ){
    function cartify_header_menu($block_options){

        $menu_active = isset( $block_options['menu-1-general-menu-active'] ) ? $block_options['menu-1-general-menu-active'] : "off";
        $menu_arrow = isset( $block_options['menu-1-general-menu-arrow'] ) ? $block_options['menu-1-general-menu-arrow'] : "on";
        $menu_button = isset( $block_options['menu-1-general-menu-button'] ) ? $block_options['menu-1-general-menu-button'] : "off";
        $menu_click_choice = isset( $block_options['menu-1-mobile-menu-click-choice'] ) ? $block_options['menu-1-mobile-menu-click-choice'] : '';
        $overflow_wrap = isset( $block_options['general-overflow-wrap'] ) ? $block_options['general-overflow-wrap'] : 'on';

        $menu_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";

        $menu_classes = array(
            'site-header-menu',
            ( $menu_active == 'on' ) ? 'highlight-current-menu' : '',
            ( $menu_button == 'on' ) ? 'has-menu-button' : '',
            ( $menu_arrow == 'on' ) ? 'has-arrow' : '',
            ( $menu_click_choice == 'dropdown' ) ? 'has-dropdown-on-click' : '',
            !empty( $menu_class_name ) ? esc_attr( $menu_class_name ) : ''
        );


        if( $overflow_wrap == 'off' ){
            $menu_classes[] = 'has-scroll-navigation'; 
        }

                ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $menu_classes ) ); ?>">
            <div class="site-header-menu-contents">
                <?php apply_filters('agni_header_menu_nav_primary', cartify_header_menu_nav('', 'primary')); ?>
            </div>
            <?php if( $overflow_wrap == 'off' ){  ?>
                <div class="site-header-menu-nav hide">
                    <span class='site-header-menu-nav-left nav-left'><i class="lni lni-chevron-left"></i></span>
                    <span class='site-header-menu-nav-right nav-right'><i class="lni lni-chevron-right"></i></span>
                </div>
            <?php }  ?>
        </div>
        <?php
    }
}

if( !function_exists('cartify_header_additional_menu') ){
    function cartify_header_additional_menu($block_options){

                $additional_menu_choice = isset( $block_options['menu-2-general-choice'] ) ? $block_options['menu-2-general-choice'] : "";
        $additional_menu_active = isset( $block_options['menu-2-general-menu-active'] ) ? $block_options['menu-2-general-menu-active'] : "off";
        $additional_menu_arrow = isset( $block_options['menu-2-general-menu-arrow'] ) ? $block_options['menu-2-general-menu-arrow'] : "on";
        $additional_menu_submenu_hide = isset( $block_options['general-submenu-hide'] ) ? $block_options['general-submenu-hide'] : "";
        $overflow_wrap = isset( $block_options['general-overflow-wrap'] ) ? $block_options['general-overflow-wrap'] : 'on';
        $menu_click_choice = isset( $block_options['mobile-menu-click-choice'] ) ? $block_options['mobile-menu-click-choice'] : '';

        $additional_menu_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";

        $depth = '';

        if( $additional_menu_submenu_hide == 'on' ){
            $depth = 1;
        }

        $additional_menu_classes = array(
            'site-header-menu',
            ( $additional_menu_active == 'on' ) ? 'highlight-current-menu' : '',
            ( $additional_menu_arrow == 'on' && empty( $depth ) ) ? 'has-arrow' : '',
            ( $menu_click_choice == 'dropdown' ) ? 'has-dropdown-on-click' : '',
            !empty( $additional_menu_class_name ) ? esc_attr( $additional_menu_class_name ) : ''
        );


        if( $overflow_wrap == 'off' ){
            $additional_menu_classes[] = 'has-scroll-navigation'; 
        }


                        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $additional_menu_classes ) ); ?>">
            <div class="site-header-menu-contents">
                <?php apply_filters('agni_header_menu_nav_additional', cartify_header_menu_nav( $additional_menu_choice, 'secondary', $depth )); ?>
            </div>
            <?php if( $overflow_wrap == 'off' ){  ?>
                <div class="site-header-menu-nav hide">
                    <span class='site-header-menu-nav-left nav-left'><i class="lni lni-chevron-left"></i></span>
                    <span class='site-header-menu-nav-right nav-right'><i class="lni lni-chevron-right"></i></span>
                </div>
            <?php }  ?>
        </div>
        <?php
    }
}

if( !function_exists('cartify_header_additional_info') ){
    function cartify_header_additional_info($block_options){
        // $block_options['additional-info-general-info-repeatable'];
        // info_name,
        // info_value
        $additional_info_repeatable = isset( $block_options['additional-info-general-info'] ) ? $block_options['additional-info-general-info'] : array(
            array(
                'info_name' =>  esc_html__( 'Support:', 'cartify' ),
                'info_value' => esc_html__( '+0 123 456 789', 'cartify' )
            ),
            array(
                'info_name' =>  esc_html__( 'Email:', 'cartify' ),
                'info_value' => esc_html__( 'yourmail@mail.ccom', 'cartify' )
            )
        );

        $additional_info_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";

                $additional_info_classes = array(
            'site-header-additional-info',
            $additional_info_class_name
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $additional_info_classes ) ); ?>">
            <?php if( !empty( $additional_info_repeatable ) ){
                foreach ($additional_info_repeatable as $key => $additional_info) {
                    $additional_info = (object) $additional_info;
                    ?>
                    <div class="site-header-additional-info__line info-line-<?php echo esc_attr( $key ); ?>">
                        <span class="site-header-additional-info__name"><?php echo esc_html( $additional_info->info_name ); ?></span>
                        <span class="site-header-additional-info__value"><?php echo esc_html( $additional_info->info_value ); ?></span>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
}

if( !function_exists('cartify_header_search') ){
    function cartify_header_search($block_options){
        // if( !class_exists('WooCommerce') ){
        //     return;
        // }

        $search_general_display_style = isset($block_options['search-general-display-style']) ? $block_options['search-general-display-style'] : '1';
        $search_form_grow = isset($block_options['search-form-flex-grow']) ? $block_options['search-form-flex-grow'] : 'on';

        $search_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";

        $search_classes = array(
            'site-header-search',
            'style-' . $search_general_display_style,
            (  $search_form_grow == 'on' ) ? 'has-flex-grow' : '',
            $search_class_name
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $search_classes ) ); ?>">
            <?php 
            if( class_exists('WooCommerce') ){
                do_action('agni_header_ajax_search', $block_options); 
            }
            else{ ?>
                <div class="site-header-search-form-container">
                    <form role="search" method="get" class="search-form agni-ajax-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="text" class="agni-ajax-search-form__text" name="s" autocomplete="off" placeholder="<?php echo esc_attr__('Search..', 'cartify') ?>" />
                        <button type="submit" class="agni-ajax-search-form__submit"></button>
                    </form>
                </div>
                <?php
            }?>

        </div>
        <?php
    }
}

if( !function_exists('cartify_header_language') ){
    function cartify_header_language($block_options){
        $language_general_display_style = isset( $block_options['language-general-display-style'] ) ? $block_options['language-general-display-style'] : '1';
        $language_general_menu_choice = isset( $block_options['language-general-menu-choice'] ) ? $block_options['language-general-menu-choice'] : '';
        $language_general_menu_custom_label = isset( $block_options['language-general-menu-custom-label'] ) ? $block_options['language-general-menu-custom-label'] : esc_html( 'Language', 'cartify' );
        $language_general_menu_custom = isset( $block_options['language-general-menu-custom'] ) ? $block_options['language-general-menu-custom'] : '';

        $language_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";

        if( !function_exists('icl_object_id') && $language_general_menu_choice == '' ){
            return;
        }

                // if( $wpml_diplay_options == '2' ){
        //     $label = $l['native_name'];
        // }
        // else if( $wpml_diplay_options == '3' ){
        //     $label = $l['language_code'];
        // }
        // else if( $wpml_diplay_options == '4' ){
        //     $label = '<img src="'.$l['country_flag_url'].'" alt="'.$l['translated_name'].'" />';
        // }
        // else{
        //     $label = $l['translated_name'];
        // }

        $lang_array = array();
        $label = $language_general_menu_custom_label;

        if( $language_general_menu_choice == '' ){
            $languages = icl_get_languages('skip_missing=0');

            if(1 < count($languages)){
                foreach ($languages as $l){
                    if($l['active']){
                        $label = $l['translated_name'];
                    }
                    else{
                        $lang_array['default'][] = $l;
                    }
                }
            }
        }


                $language_menu_classes = array(
            'site-header-language-menu',
            'style-' . $language_general_display_style,
            $language_class_name
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $language_menu_classes ) ); ?>">
            <?php if( $language_general_display_style == '2' ){ ?>
                <span class="site-header-language-menu__label"><?php echo esc_html($label); ?>
                </span>
            <?php } ?>
            <?php if( $language_general_menu_choice == '' ){ ?>
                <ul class="site-header-menu-additional site-header-menu-wpml-menu">
                    <?php foreach ($lang_array['default'] as $l) { ?>
                        <li><a href="<?php echo esc_url( $l['url'] ); ?>">
                            <?php echo esc_html( $l['translated_name'] ); ?>
                        </a></li>
                    <?php } ?>
                </ul>
            <?php }
            else{ ?>
                <?php apply_filters('agni_header_menu_nav_language', cartify_header_menu_nav( $language_general_menu_custom ) ); ?>
            <?php } ?>

                    </div>
        <?php
    }
}


if( !function_exists('cartify_header_currency') ){
    function cartify_header_currency($block_options){

                $currency_general_display_style = isset( $block_options['currency-general-display-style'] ) ? $block_options['currency-general-display-style'] : '1';
        $currency_general_menu_choice = isset( $block_options['currency-general-menu-choice'] ) ? $block_options['currency-general-menu-choice'] : '';
        $currency_general_menu_custom_label = isset( $block_options['currency-general-menu-custom-label'] ) ? $block_options['currency-general-menu-custom-label'] : esc_html( 'currency', 'cartify' );
        $currency_general_menu_custom = isset( $block_options['currency-general-menu-custom'] ) ? $block_options['currency-general-menu-custom'] : '';

        $currency_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";

        // if( !function_exists('icl_object_id') && $currency_general_menu_choice == '' ){
        //     return;
        // }

                // if( $wpml_diplay_options == '2' ){
        //     $label = $l['native_name'];
        // }
        // else if( $wpml_diplay_options == '3' ){
        //     $label = $l['currency_code'];
        // }
        // else if( $wpml_diplay_options == '4' ){
        //     $label = '<img src="'.$l['country_flag_url'].'" alt="'.$l['translated_name'].'" />';
        // }
        // else{
        //     $label = $l['translated_name'];
        // }

        // $lang_array = array();
        $label = $currency_general_menu_custom_label;



                $currency_menu_classes = array(
            'site-header-currency-menu',
            'style-' . $currency_general_display_style,
            $currency_class_name
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $currency_menu_classes ) ); ?>">
            <?php if( $currency_general_display_style == '2' ){ ?>
                <span class="site-header-currency-menu__label"><?php echo esc_html($label); ?>
                </span>
            <?php } ?>
            <?php if( $currency_general_menu_choice == '' ){ ?>
				<?php global $WOOCS;
					$currencies    = $WOOCS->get_currencies();
					$currency_list = array();
					foreach ( $currencies as $key => $currency ) {
						if ( $WOOCS->current_currency == $key ) {
							array_unshift( $currency_list, sprintf( '<li><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current" data-currency="%s">%s</a></li>', esc_attr( $currency['name'] ), esc_html( $currency['name'] ) ) );
						} else {
							$currency_list[] = sprintf( '<li><a href="#" class="woocs_flag_view_item" data-currency="%s">%s</a></li>', esc_attr( $currency['name'] ), esc_html( $currency['name'] ) );
						}
					} ?>
				<ul class="site-header-menu-additional site-header-menu-woocs-menu">
					<?php echo implode( "\n\t", $currency_list ); ?>
				</ul>
            <?php }
			else{ ?>
                <?php apply_filters('agni_header_menu_nav_currency', cartify_header_menu_nav( $currency_general_menu_custom ) ); ?>

							<?php } ?>

                    </div>
        <?php
    }
}


if( !function_exists('cartify_header_compare') ){
    function cartify_header_compare($block_options){
        if( !class_exists('WooCommerce') ){
            return;
        }

        $compare_page_choice_default = '';
        $compare_page_choice = cartify_get_theme_option('shop_settings_compare_page_choice', $compare_page_choice_default);

        $compare_general_icon_choice = isset($block_options['compare-general-icon-choice'])?$block_options['compare-general-icon-choice']:'';
        $compare_general_icon_predefined = isset($block_options['compare-general-icon-predefined'])?$block_options['compare-general-icon-predefined']:'compare';
        $compare_general_icon_custom = isset($block_options['compare-general-icon-custom'])?$block_options['compare-general-icon-custom']:'';
        $compare_general_text = isset( $block_options['compare-general-text'] ) ? $block_options['compare-general-text'] : '';

        $compare_class_name = isset( $block_options['className'] ) ? $block_options['className'] : "";


        if( $compare_general_icon_choice == 'custom' ){
            $icon = '<img src="' . esc_url( $compare_general_icon_custom ) . '" />';
        }
        else{
            $icon = cartify_get_icon_svg( 'common', $compare_general_icon_predefined);
        }

        $compare_classes = array(
            'site-header-icon-compare',
            'site-header-icon',
            $compare_class_name
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $compare_classes ) ); ?>">
            <a href="<?php echo esc_url( get_the_permalink( $compare_page_choice ) ); ?>" class="site-header-icon-compare__link">
            <?php if( !empty( $compare_general_text ) ){ ?>
                <div class="site-header-icon-compare__details">
                    <span><?php echo esc_html($compare_general_text); ?></span>
                </div>
            <?php } ?>
                <div class="site-header-icon-compare__container">
                    <span class="site-header-icon-container"><?php echo apply_filters('agni_header_icons_compare_icon', $icon) ?></span>
                </div>
            </a>
        </div>
        <?php
    }
}

if( !function_exists( 'cartify_header_button' ) ){
    function cartify_header_button( $block_options ){

        $btn_text = isset($block_options['btn-text']) ? $block_options['btn-text'] : '';
        $btn_link = isset($block_options['btn-link']) ? $block_options['btn-link']: '#';
        $btn_size = isset($block_options['btn-size']) ? $block_options['btn-size'] : '';
        $btn_target = isset( $block_options['btn-target'] ) ? $block_options['btn-target'] : '_self';

        $className = isset( $block_options['className'] ) ? $block_options['className'] : "";


                $classes = array(
            'site-header-button',
            $className
        );

        $btn_classes = array(
            'btn',
            !empty( $btn_size ) ? 'btn-' . $btn_size : '',
        )

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $classes ) ); ?>">
            <a class="<?php echo esc_attr( cartify_prepare_classes( $btn_classes ) ); ?>" href="<?php echo esc_url( $btn_link ); ?>" target="<?php echo esc_attr( $btn_target ); ?>"><?php echo esc_html( $btn_text ); ?></a>
        </div>
        <?php
    }
}

if( !function_exists( 'cartify_header_content_block' ) ){
    function cartify_header_content_block( $block_options ){

        $block_id = isset($block_options['content-block-choice']) ? $block_options['content-block-choice'] : '';

        $className = isset( $block_options['className'] ) ? $block_options['className'] : "";


                $classes = array(
            'site-header-content-block',
            $className
        );

        ?>
        <div class="<?php echo esc_attr( cartify_prepare_classes( $classes ) ); ?>">
            <?php echo apply_filters( 'agni_content_block', $block_id ); ?>
        </div>
        <?php
    }
}

// helper functions
if( !function_exists('cartify_header_menu_nav') ){
    function cartify_header_menu_nav($menu_choice = '', $fallback = '', $depth = 0) {

        // $menu_selector = empty($position) ? $menu_choice : $position;

        // $header_menu_location = $position;
        // $header_menu_class = 'site-header-menu-' . $menu_selector;
        // $header_menu_id = 'site-header-menu-' . $menu_selector;

        // $menu_list = cartify_registered_menus_list(true);

        $header_menu_classes = array(
            $fallback !== 'primary' ? 'site-header-menu-additional' : '', 
            empty( $menu_choice ) && $fallback == 'primary' ? 'site-header-menu-primary' : '', 
            !empty( $menu_choice ) ? ' site-header-menu-' . $menu_choice : ''
        );


                $args = array(
            'menu_class'        => cartify_prepare_classes( $header_menu_classes ), 
            // 'menu_id'           => $header_menu_id, 
            'container'         => false, 
            'fallback_cb'       => 'cartify_header_menu_nav_fallback',
            // 'walker' => new Agni_Walker_Nav_Menu()
        );

        if( $depth ){
            $args['depth'] = $depth;
        }


        if( !empty( $menu_choice ) ){
            $args['menu'] = $menu_choice;
        }
        else{
            if( !empty( $fallback ) ){
                $args['theme_location'] = $fallback;
            }
            else{
                $args['theme_location'] = 'primary';
            }
        }

                wp_nav_menu( $args ); 
    }
}

if( !function_exists('cartify_header_menu_nav_fallback') ){
    function cartify_header_menu_nav_fallback(){
        if( is_user_logged_in() ){
            ?>
            <a href="<?php echo admin_url('nav-menus.php'); ?>" class="menu-fallback-link"><?php echo esc_html__( 'Setup Menu', 'cartify' ); ?></a>
            <?php
        }
    }
}

if( !function_exists('cartify_header_myaccount_login_form') ){
    function cartify_header_myaccount_login_form(){

        $header_login_form = cartify_get_theme_option( 'agni_header_block_field_show_login_form', '' );

        $social_login_fb_show = cartify_get_theme_option( 'shop_settings_social_login_fb_show', '1' );
        $social_login_google_show = cartify_get_theme_option( 'shop_settings_social_login_google_show', '1' );


        if( $social_login_google_show == '1' && !is_user_logged_in() ){
            wp_enqueue_script( 'cartify_google_api' );
        }



        $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
        $checkout_page_id = get_option( 'woocommerce_checkout_page_id' );

        if( $header_login_form && !is_user_logged_in() && $myaccount_page_id != get_the_id() && $checkout_page_id != get_the_id() ){
            ?>
            <div class="site-header-login-panel">
                <div class="site-header-login-panel__overlay"></div>
                <div class="site-header-login-panel__container">
                    <?php //if(  ){ ?>
                    <div class="site-header-login-panel__title">
                        <h3><?php echo esc_html( 'Войти/Регистрация', 'cartify' ); ?></h3>
                        <span class="site-header-login-panel__close"><i class="lni lni-close"></i></span>
                    </div>
                    <?php //} ?>
                    <div class="site-header-login-panel__contents">
                        <div class="site-header-login-panel__register">
                            <span><?php echo esc_html('У меня нет аккаунта', 'cartify');?></span>
                            <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="site-header-login-panel__register-btn btn btn-bold btn-block btn-lg btn-alt"><?php echo esc_html__( 'Register', 'cartify' ); ?></a>
                        </div>
                        <div class="site-header-login-panel__login--form">
                            <?php do_action( 'agni_header_woocommerce_login_form' ); ?>
                        </div>
                        <div class="site-header-login-panel__login--social">
                            <?php if( $social_login_fb_show ){?>
                                <button id="login-btn-facbook" class="site-header-login-panel__login-btn--facbook btn btn-block btn-bold btn-lg"><?php echo esc_html__( 'Continue with Facebook', 'cartify'); ?></button>
                            <?php } ?>
                            <?php if( $social_login_google_show ){ ?>
                                <button id="login-btn-google" class="site-header-login-panel__login-btn--google btn btn-block btn-bold btn-lg"><?php echo esc_html__( 'Continue with Google', 'cartify'); ?></button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="site-header-login-panel__loader"><?php echo esc_html__( 'Loading', 'cartify' ); ?></div>
                </div>

                            </div>
            <?php 
        }

    }
}


function cartify_header_sidecart(){
    $header_sidecart = cartify_get_theme_option( 'agni_header_block_field_show_sidecart', '' );

    if( $header_sidecart ){
    ?>
        <?php do_action( 'agni_ajax_sidecart' ) ?>
    <?php  
    }
}


function cartify_ajax_header_cart_get_count(){
    ?>
    <span class="site-header-icon-cart__count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
}

function cartify_ajax_header_cart_get_amount(){
    ?>
    <span class="site-header-icon-cart__amount"><?php echo wc_cart_totals_subtotal_html(); ?></span>
    <?php
}


?>