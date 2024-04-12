<?php

add_action( 'agni_before_footer', 'cartify_category_dropdown_menu', 30, 1 );

add_action( 'agni_after_footer', 'cartify_footer_dock' );
add_action( 'agni_after_footer', 'cartify_cookie_policy_notice' );

function cartify_category_dropdown_menu(){
    $category_menu_choice = '';
    $category_user_info = 'on';
    ?>
    <div class="agni-category-dropdown-panel">
        <div class="category-dropdown-menu dropdown-style-1">  
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
                    <div class="category-dropdown-menu-nav-menu has-arrow">
                        <div class="category-dropdown-menu-nav-menu__header">
                            <h6 class="category-dropdown-menu-nav-menu__title"><?php echo esc_html__( 'Departments', 'cartify' ) ?></h6>
                        </div>
                        <div class="category-dropdown-menu-nav-menu__contents">
                            <?php apply_filters( 'agni_header_menu_nav_cateogory_dropdown', cartify_header_menu_nav( $category_menu_choice, 'category' ) ); ?>
                        </div>
                    </div>
                </div>
                <span class="category-dropdown-menu__close"><i class="lni lni-close"></i></span>
            </div>
        </div>
    </div>
    <?php
}

function cartify_footer_dock(){

    if( !class_exists( 'WooCommerce' ) ){
        return;
    }

    $dock_mobile = cartify_get_theme_option( 'dock_settings_general_show', '1' );

    if( !$dock_mobile ){
        return;
    }

    $dock_myaccount_show = cartify_get_theme_option( 'dock_settings_general_myaccount_show', '1' );
    $dock_cart_show = cartify_get_theme_option( 'dock_settings_general_cart_show', '1' );
    $dock_category_dropdown_show = cartify_get_theme_option( 'dock_settings_general_category_dropdown_show', '1' );
    $dock_shop_show = cartify_get_theme_option( 'dock_settings_general_shop_show', '1' );

    // if( $dock_myaccount_show ){

    // }
    // if( $dock_cart_show ){

            // }
    // if( $dock_category_dropdown_show ){

            // }
    // if( $dock_shop_show ){

            // }

    if( $dock_myaccount_show ){
        if( !cartify_get_theme_option( 'agni_header_block_field_show_login_form', '' ) ){
            set_theme_mod( 'agni_header_block_field_show_login_form', '1' );
        }
    }

    if( $dock_cart_show ){
        if( !cartify_get_theme_option( 'agni_header_block_field_show_sidecart', '' ) ){
            set_theme_mod( 'agni_header_block_field_show_sidecart', '1' );
        }
    }


    $dock_exception_pages = apply_filters( 'agni_dock_exception_list', array(
        'hide_on_products' => true,
        'hide_on_cart' => true,
        'hide_on_checkout' => true,
        'hide_on_admin' => true
    ) );

    if( $dock_exception_pages['hide_on_cart'] && is_cart() ){
        return;
    }

    if( $dock_exception_pages['hide_on_checkout'] && is_checkout() ){
        return;
    }

    if( $dock_exception_pages['hide_on_products'] && is_product() ){
        return;
    }

    if( $dock_exception_pages['hide_on_admin'] && is_account_page() ){
        return;
    }

    // $header_login_form = cartify_get_theme_option( 'agni_header_block_field_show_login_form', '' );
    // $header_sidecart = cartify_get_theme_option( 'agni_header_block_field_show_sidecart', '' );

    ?>
    <div class="agni-shop-dock">
        <div class="agni-shop-dock__container">
            <ul>
                <?php do_action( 'agni_dock_before_contents' ); ?>

                <?php if( $dock_shop_show ){ 

                    $dock_shop_custom_link = cartify_get_theme_option( 'dock_settings_general_shop_link', '' );

                    $dock_shop_icon = wp_kses( cartify_get_icon_svg( 'common', 'store' ), 'svg' );
                    $dock_shop_link = !empty( $dock_shop_custom_link ) ? $dock_shop_custom_link : get_the_permalink( wc_get_page_id('shop') );


                                        $dock_shop_link_classes = array(
                        'agni-shop-dock-shop__link',
                        !empty($dock_shop_custom_link) ? 'has-custom-link' : ''
                    )

                                        ?>
                    <li class="agni-shop-dock-shop">
                        <a class="<?php echo esc_attr( cartify_prepare_classes( $dock_shop_link_classes ) ) ?>" href="<?php echo esc_url( $dock_shop_link ) ?>">
                            <?php echo apply_filters( 'agni_dock_icon_shop', $dock_shop_icon ); ?>
                            <span><?php echo esc_html__( 'Shop', 'cartify' ); ?></span>
                        </a>
                    </li>
                <?php } ?>
                <?php if( $dock_category_dropdown_show ){ 

                    $dock_categories_custom_link = cartify_get_theme_option( 'dock_settings_general_category_dropdown_link', '' );

                                        $dock_categories_icon = wp_kses( cartify_get_icon_svg( 'common', 'hamburger' ), 'svg' );
                    // $dock_categories_link = '';

                                        $dock_categories_link_classes = array(
                        'agni-shop-dock-categories__link',
                        !empty($dock_shop_custom_link) ? 'has-custom-link' : ''
                    )
                    ?>
                    <li class="agni-shop-dock-categories">
                        <a class="<?php echo esc_attr( cartify_prepare_classes( $dock_categories_link_classes ) ) ?>" href="<?php echo !empty( $dock_categories_custom_link ) ? esc_url( $dock_categories_custom_link ) : '#'; ?>">
                            <?php echo apply_filters( 'agni_dock_icon_categories', $dock_shop_icon ); ?>
                            <span><?php echo esc_html__( 'Categories', 'cartify' ); ?></span>
                        </a>
                    </li>
                <?php } ?>
                <?php if( $dock_cart_show ){ 

                    $dock_cart_custom_link = cartify_get_theme_option( 'dock_settings_general_cart_link', '' );

                                        $dock_cart_icon = wp_kses( cartify_get_icon_svg( 'common', 'cart' ), 'svg' );
                    $dock_cart_link = !empty( $dock_cart_custom_link ) ? $dock_cart_custom_link : get_the_permalink( get_option( 'woocommerce_cart_page_id' ) );

                    $dock_cart_link_classes = array(
                        'agni-shop-dock-cart__link',
                        !empty($dock_shop_custom_link) ? 'has-custom-link' : ''
                    )
                    ?>
                    <li class="agni-shop-dock-cart">
                        <a class="<?php echo esc_attr( cartify_prepare_classes( $dock_cart_link_classes ) ) ?>" href="<?php echo esc_url( $dock_cart_link ) ?>">
                            <?php echo apply_filters( 'agni_dock_icon_cart', $dock_cart_icon ); ?>
                            <span><?php echo esc_html__( 'Cart', 'cartify' ); ?></span>
                        </a>
                    </li>
                <?php } ?>
                <?php if( $dock_myaccount_show ){ 

                    $dock_myaccount_custom_link = cartify_get_theme_option( 'dock_settings_general_myaccount_link', '' );

                                        $dock_myaccount_icon = wp_kses( cartify_get_icon_svg( 'common', 'myaccount' ), 'svg' );
                    $dock_myaccount_link = !empty( $dock_myaccount_custom_link ) ? $dock_myaccount_custom_link :  get_the_permalink( get_option( 'woocommerce_myaccount_page_id' ) );


                    $dock_myaccount_classes = array( 
                        'agni-shop-dock-myaccount',
                        is_user_logged_in() ? ' logged-in' : '',
                        !empty($dock_myaccount_custom_link) ? 'has-custom-link' : ''
                    );


                    ?>
                    <li class="<?php echo esc_attr( cartify_prepare_classes( $dock_myaccount_classes ) ); ?>">
                        <a class="agni-shop-dock-myaccount__link" href="<?php echo esc_url( $dock_myaccount_link ) ?>">
                            <?php echo apply_filters( 'agni_dock_icon_myaccount', $dock_myaccount_icon ); ?>
                            <span><?php echo esc_html__( 'My Account', 'cartify' ); ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php
}


function cartify_cookie_policy_notice(){

    $cookie_notice = esc_attr( cartify_get_theme_option( 'cookie_settings_notice_show', '' ) );
    $cookie_notice_expires = esc_attr( cartify_get_theme_option( 'cookie_settings_notice_expire_period', '' ) );

    if( empty($cookie_notice) ){
        return;
    }

    wp_enqueue_script('js-cookie');

    ?>
    <div class="agni-cookie-consent">
        <div class="agni-cookie-consent__container">
            <div class="agni-cookie-consent__icon">
                <?php echo cartify_get_icon_svg( 'site', 'cookie' ) ?>
            </div>
            <h5 class="agni-cookie-consent__title">
                <?php echo esc_html__( 'Cookies', 'cartify' ); ?>
            </h5>
            <div class="agni-cookie-consent__content">
                <?php echo esc_html__( 'We use cookies to give you the best possible experience. By continuing to visit our website, you agree to the use of cookies as described in our', 'cartify' ); ?>
                <?php the_privacy_policy_link(); ?>
            </div>
            <button class="agni-cookie-consent__accept cookie-accept" data-expires="<?php echo esc_attr( $cookie_notice_expires ); ?>"><?php echo esc_html__( 'Accept Cookies', 'cartify' ); ?></button>
            <span class="agni-cookie-consent__close"><i class="lni lni-close"></i></span>
        </div>
        <div class="overlay"></div>
    </div>
    <?php

        ?>    
    <?php
}