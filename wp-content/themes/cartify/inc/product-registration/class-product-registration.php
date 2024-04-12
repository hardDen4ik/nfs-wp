<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Agni_Product_Registration{

    private $defaults;

    private $domain_name = '';

    private $item_code = '';

    private $purchase_code = '';

    private $envato_token = '';

    private $email = '';

    public function __construct(){

        $this->init();


                add_action( 'admin_menu', array( $this, 'product_registration_page') );
        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

        add_action( 'agni_product_registration_form', array($this, 'registration_form') );

    }

    public function init(){
        $this->defaults = array(
            'position' => 1
        );

        $this->domain_name = $this->get_domain_name();

        $this->item_code = $this->get_item_code();

        $this->purchase_code = $this->get_purchase_code();

        $this->envato_token = $this->get_envato_token();

        $this->email = $this->get_email();
    }

    public function includes(){
        require_once AGNI_TEMPLATE_DIR . '/product-registration/class-product-activation.php';
    }

    public static function get_domain_name(){

                $url = home_url();
        $url = preg_replace( "(^https?://)", "", $url );

        return $url;
    }

    public static function get_envato_token(){

                return cartify_get_theme_option( 'agni_product_registration_envato_token', '' );
    }

    public static function get_staging(){

                return cartify_get_theme_option( 'agni_product_registration_staging', '' );
    }

    public static function get_item_code(){

                return 'nsc8rc78xkkutjsb'; // ct8x664f6tz9n4jz
    }


    public static function get_item_id(){

                return '35299456';
    }

    public static function get_purchase_code(){

        $item_id = SELF::get_item_id();

                return get_option( 'envato_purchase_code_' . $item_id );
    }

    public static function get_email(){

        return cartify_get_theme_option( 'agni_product_registration_email', '' );
    }


    public function product_registration_page(){

        // add_menu_page( esc_html__( 'Header Builder', 'cartify' ), esc_html__( 'Agni Header', 'cartify' ), 'edit_theme_options', 'agni_header_builder', array( $this, 'header_builder_contents' ), $this->header_builder_menu_icon, $this->header_builder_menu_position );
        add_submenu_page( 'cartify', esc_html__( 'Product Registration', 'cartify' ), esc_html__( 'Product Registration', 'cartify' ), 'edit_theme_options', 'agni_product_registration', array( $this, 'contents' ), $this->defaults['position'] );
    }

    public function contents(){

        // wp_enqueue_style( 'agni-header-builder-react-style');
        // wp_enqueue_script( 'agni-header-builder-react-script');


        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Agni Product Registration', 'cartify' ); ?></h1>
            <?php do_action( 'agni_product_registration_form' ); ?>

        </div>
        <?php

    }

    public function registration_form(){

        $args = array(
            // 'item_code' => $this->item_code,
            'domain' => $this->domain_name,
            'purchase_code' => $this->purchase_code,
            // 'envato_token' => $this->envato_token,
            'email' => $this->email,
            'fetch' => true
        );

                $buyer_info = Agni_Product_Activation::get_remote_buyer_info( $args );

                // echo "Buyer Info:"; print_r( $buyer_info );


        if( isset($buyer_info->success) ){
            $get_buyer_info = $buyer_info->success;
        }

        $is_not_registered = !isset($buyer_info->success); //(is_wp_error($buyer_info) || !isset( $buyer_info ) || empty( $buyer_info ));

        $staging = get_option( 'agni_product_registration_staging', '' );

        ?>
        <div class="agni-product-registration">
            <div class="agni-product-registration-dashboard">
                <div class="agni-product-registration-dashboard__container">
                    <?php if( $is_not_registered ) { ?>
                        <h1 class="agni-product-registration__heading"><?php echo esc_html__( 'Register Your Product', 'cartify' ); ?></h1>
                        <p class="agni-product-registration__description"><?php echo esc_html__( 'This registration process will make you eligible to get automatic updates, install premium plugins and import demo content.', 'cartify' ); ?></p>
                    <?php } 
                    else{ ?>
                        <h1 class="agni-product-registration__heading"><?php echo esc_html__( 'Product Registered', 'cartify' ); ?><span class="dashicons dashicons-saved"></span></h1>
                        <p class="agni-product-registration__description"><?php echo esc_html__( 'Congratulations! Now you’re eligible to automatic updates, install premium plugins and import demo contents.', 'cartify' ); ?></p>
                    <?php } ?>
                    <form class="agni-product-registration-form"  method="post">
                        <p>
                            <label for="product-registration-envato-purchase-code"><?php echo esc_html__( 'Envato Purchase Code', 'cartify' ); echo '<span class="required">*</span>'; ?></label>
                            <input type="text" id="product-registration-envato-purchase-code" name="purchase_code" class="input code" placeholder="XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX" autocomplete="off" required value="<?php echo esc_attr( !empty( $this->purchase_code ) ? substr_replace(  $this->purchase_code, str_repeat('X', 12), 24 ) : '' ) ?>"></input>
                        </p>
                        <?php /* ?>
                        <p>
                            <label for="product-registration-envato-token"><?php echo esc_html__( 'Envato Personal Token', 'cartify' ); ?></label>
                            <input type="text" id="product-registration-envato-token" name="envato_token" class="input code" placeholder="XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" autocomplete="off" value="<?php echo esc_html( $this->envato_token ) ?>"></input>
                        </p>
                        <?php */ ?>
                        <p>
                            <label for="product-registration-email"><?php echo esc_html__( 'Enter your address (Optional)', 'cartify' ); ?></label>
                            <input type="email" id="product-registration-email" name="email" placeholder="yourmail@mail.com" value="<?php echo esc_html( $this->email ) ?>"></input>
                            <span><?php echo esc_html__( 'We send you promotions & discounts (Not more than once or twice a month). Also you\'ll receive an update notifications with changelog & tutorials.', 'cartify' ); ?></span>
                        </p>
                        <p>
                            <label for="product-registration-staging">
                                <input type="checkbox" id="product-registration-staging" name="staging" value="1" <?php echo checked( $staging, '1' ) ?>><?php echo esc_html__( 'This is staging/local site', 'cartify' ); ?></input>
                            </label>
                        </p>
                        <p>
                            <small><?php echo esc_html__( 'Purchase code, Site addresses will be encrypted & stored on our server for verification.', 'cartify' ); ?></small>
                        </p>
                        <p>

                                                        <?php 
                            if( $is_not_registered ) { ?>
                                <input type="hidden" name="register" value="1" />
                            <?php } 
                            else if( isset($get_buyer_info->username) && empty($get_buyer_info->username) ){ ?>
                                <input type="hidden" name="register" value="1" />
                            <?php }
                            else{ ?>
                                <input type="hidden" name="deregister" value="1" />
                            <?php } ?>
                            <button type="submit" id="product-registration-submit" name="product-registration-submit"><?php 
                            if( $is_not_registered ) {
                                echo esc_html__( 'Register this site', 'cartify' ); 
                            } 
                            else if ( isset($get_buyer_info->username) && empty($get_buyer_info->username) ){
                                echo esc_html__( 'Register this site with token', 'cartify' );
                            }
                            else{ 
                                echo esc_html__( 'Deregister this site', 'cartify' ); 
                            } ?></button>
                        </p>
                    </form>
                </div>
                <hr/>
                <?php  
                if( $is_not_registered ) { ?>
                    <div class="agni-product-registration-instruction">
                        <div class="agni-product-registration-instruction__container">
                            <h2><?php echo esc_html__( 'Instruction', 'cartify' ); ?></h2>
                            <ol>
                                <li>Download License certificate & purchase code by <a href="<?php echo esc_url( 'themeforest.net/downloads'); ?>" target="_blank">clicking this link</a></li>
                                <li>Copy the purchase code into the box above.</li>
                                <li>Click the "Register" button.</li>
                            </ol>
                            <small><sup>*</sup>Single purchase (license) is only valid for One Domain. Purchase again <a href="#">here</a> for adding more domains.</small>
                        </div>
                    </div>
                <?php } 
                else{ 
                    $support_period_renewal = false;

                    if( isset( $get_buyer_info->sold_at ) ){
                        if( !empty( $get_buyer_info->sold_at ) ){
                            $sold_at_date = new DateTime( $get_buyer_info->sold_at ); 
                            $sold_at = $sold_at_date->format( 'd M Y' );
                        }
                        else{
                            $sold_at = 'N/A';
                        }
                    }

                    if( isset( $get_buyer_info->supported_until ) ){
                        if( !empty( $get_buyer_info->supported_until ) ){
                            $supported_until_date = new DateTime( $get_buyer_info->supported_until ); 
                            $supported_until = $supported_until_date->format( 'd M Y' );

                            $now = new DateTime();

                            if($supported_until_date < $now) {
                                $support_period = 'Expired'; 
                                $support_period_renewal = true;
                            }
                            else{
                                $support_period = 'Active'; 
                            } 
                        }
                        else{
                            $supported_until = 'N/A';
                            $support_period = 'N/A';
                        }
                    }

                    if( isset($get_buyer_info->username) ){
                        if( !empty( $get_buyer_info->username ) ){
                            $username = $get_buyer_info->username;
                        }
                        else{
                            $username = 'Localhost User';
                        }
                    }

                    ?>
                    <div class="agni-product-registration-details">
                        <div class="agni-product-registration-details__container">
                            <h2><?php echo esc_html__( 'License Details', 'cartify' ); ?></h2>
                            <table>
                                <tr>
                                    <td>Recently Purchased on</td>
                                    <td><?php echo esc_html( $sold_at ); ?></td>
                                </tr>
                                <tr>
                                    <td>Support Period</td>
                                    <td><?php 
                                        echo esc_html( $support_period ); 

                                        if( $support_period_renewal ){
                                            ?>
                                            <span><a href="<?php echo esc_url( '//themeforest.net/downloads' ); ?>"><?php echo esc_html__('Renew', 'cartify'); ?></a></span>
                                            <?php
                                        }
                                    ?></td>
                                </tr>
                                <tr>
                                    <td>Support Valid upto</td>
                                    <td><?php echo esc_html( $supported_until ); ?></td>
                                </tr>
                                <tr>
                                    <td>Envato Username</td>
                                    <td><?php echo esc_html( $username ); ?></td>
                                </tr>
                            </table>
                            <small><sup>*</sup>Single purchase (license) is only valid for One Domain. Purchase again <a href="#">here</a> for adding more domains.</small>
                        </div>
                    </div>
                <?php } ?>

                            </div>
        </div>

        <?php

            }

    public function admin_enqueue_scripts(){

        wp_register_script( 'agni-cartify-product-registration-admin', AGNI_FRAMEWORK_JS_URL . '/product-registration/product-registration.js' );
        wp_localize_script( 'agni-cartify-product-registration-admin', 'agni_product_registration', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('agni_product_registration_nonce'),
            'domain_name' => $this->domain_name,
            // 'item_code' => $this->item_code
        ) );
        wp_enqueue_script( 'agni-cartify-product-registration-admin' );

            }

}

$agni_product_registration = new Agni_Product_Registration();
