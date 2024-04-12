<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( !function_exists('cartify_login') ){
    function cartify_login(){

        // First check the nonce, if it fails the function will break
        if (!check_ajax_referer('agni_ajax_login_nonce', 'security')) {
            return 'Invalid Nonce';
        }

        if( isset($_REQUEST['username']) && isset($_REQUEST['password']) ){

                        $creds = array(
                'user_login'    => $_REQUEST['username'],
                'user_password' => $_REQUEST['password'],
                'remember'      => isset($_REQUEST['remember'])? true : false
            );

            $user = wp_signon( $creds, is_ssl() );

            // print_r($user);

            if ( is_wp_error( $user ) ) {
                wp_send_json_error( $user->get_error_message() );
            }
            else{
                wp_send_json_success();
            }

        ?>


        <?php
        }
        die();
    }
}


if( !function_exists('cartify_header_woocommerce_login_form') ){
    function cartify_header_woocommerce_login_form(){

        wp_enqueue_script('cartify-ajax-login');

        ?>
        <h4><?php esc_html_e( 'Customer Login', 'cartify' ); ?></h4>
        <p><?php esc_html_e( 'If you have an account, sign in with your email address.', 'cartify' ); ?></p>

        <div class="agni-ajax-login-notice"></div>
        <form class="woocommerce-form woocommerce-form-login login agni-ajax-login" method="post">

            <?php // do_action( 'woocommerce_login_form_start' ); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="username"><?php esc_html_e( 'Username or email address', 'cartify' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password"><?php esc_html_e( 'Password', 'cartify' ); ?>&nbsp;<span class="required">*</span></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
            </p>
            <p class="woocommerce-LostPassword lost_password">
                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'cartify' ); ?></a>
            </p>

            <?php // do_action( 'woocommerce_login_form' ); ?>

            <p class="form-row">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'cartify' ); ?></span>
                </label>
                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'cartify' ); ?>"><?php esc_html_e( 'Log in', 'cartify' ); ?></button>
            </p>

            <?php // do_action( 'woocommerce_login_form_end' ); ?>

        </form>
        <?php
    }
}

function cartify_ajax_login_scripts(){
    wp_register_script('cartify-ajax-login', AGNI_FRAMEWORK_JS_URL . '/agni-ajax-login/agni-ajax-login.js', array('jquery'), wp_get_theme()->get('Version'), true );
    wp_localize_script('cartify-ajax-login', 'cartify_ajax_login', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('agni_ajax_login_nonce'),
        'action' => 'agni_login',
        'ajaxurl_wc' => WC_AJAX::get_endpoint( "%%endpoint%%" ),
    ));
}

if(!is_admin() || wp_doing_ajax()){
    // myaccount action
    add_action('agni_header_woocommerce_login_form', 'cartify_header_woocommerce_login_form');
    add_action('wp_ajax_agni_login', 'cartify_login');
    add_action('wp_ajax_nopriv_agni_login', 'cartify_login');
    add_action('wp_enqueue_scripts', 'cartify_ajax_login_scripts');
}

?>