<?php 

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Agni_Cartify_Dashboard {

    public $current_version = '';


    public function __construct(){

        // $this->includes();

        add_action( 'admin_menu', array( $this, 'dashboard_menu_page' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // add_action( 'agni_check_theme_update', array( $this, 'check_for_theme_update' ), 10, 1 );
        add_action( 'wp_ajax_agni_check_theme_update', array( $this, 'check_for_theme_update_ajax' ), 10, 1 );
        add_action( 'wp_ajax_agni_download_theme_update', array( $this, 'download_theme_update_ajax' ), 10, 1 );
        // add_action( 'wp_ajax_no_priv_agni_download_theme_update', array( $this, 'download_theme_update_ajax' ), 10, 1 );

        $this->init();
    }

    public function init(){

        $this->current_version = wp_get_theme()->get('Version');

        $parent = wp_get_theme()->parent();

        if( !empty( $parent ) ){
            $this->current_version = $parent->get('Version');
        }
    }

    // public function includes(){
    //     include_once AGNI_TEMPLATE_DIR . '/admin/system-status.php';
    // }

    public function dashboard_menu_page(){
        //Creating top level menu as staff
        //Use add_submenu_page for adding additional submenu
        add_menu_page( esc_html__( 'Cartify', 'cartify' ), esc_html__( 'Cartify', 'cartify' ), 'edit_theme_options', 'cartify', array( $this, 'dashboard_welcome_page' ), AGNI_FRAMEWORK_IMG_URL . '/icon_logo_dark.svg', 2 );

        add_submenu_page( 'cartify', esc_html__( 'Admin Panel', 'cartify' ), esc_html__( 'Welcome', 'cartify' ), 'edit_theme_options', 'cartify', '', null );

        add_submenu_page( 'cartify', esc_html__( 'Agni Importer & Exporter', 'cartify' ), esc_html__( 'Demo Import/Export', 'cartify' ), 'edit_theme_options', 'agni_importer_exporter', array( $this, 'importer_exporter_mainpage' ), null);

        add_submenu_page( 'cartify', esc_html__( 'Header Builder', 'cartify' ), esc_html__( 'Header Builder', 'cartify' ), 'edit_theme_options', 'agni_header_builder', array( $this, 'header_builder_contents' ), null );

        add_submenu_page( 'cartify', esc_html__( 'Product Layout Builder', 'cartify' ), esc_html__( 'Product Layout Builder', 'cartify' ), 'edit_theme_options', 'agni_product_builder', array( $this, 'product_builder_contents' ), null );

        add_submenu_page( 'cartify', esc_html__( 'Slider Builder', 'cartify' ), esc_html__( 'Slider Builder', 'cartify' ), 'edit_theme_options', 'agni_slider_builder', array( $this, 'slider_builder_contents' ), null );

            add_submenu_page( 'cartify', esc_html__( 'Font Manager', 'cartify' ), esc_html__( 'Font Manager', 'cartify' ), 'edit_theme_options', 'agni_fonts_list', array( $this, 'agni_fonts_callback' ), null );


            }

    public function dashboard_welcome_page(){


        $latest_version = $this->check_for_theme_update();
        $current_version = $this->current_version;


        $is_registered = false;
        $theme_version = $this->current_version;

        $agni_product_registration = new Agni_Product_Registration();

        $args = array(
            // 'item_code' => $agni_product_registration->get_item_code(),
            'domain' => $agni_product_registration->get_domain_name(),
            'purchase_code' => $agni_product_registration->get_purchase_code(),
            // 'envato_token' => $agni_product_registration->get_envato_token(),
            'email' => $agni_product_registration->get_email(),
            'fetch' => true
        );

                $buyer_info = Agni_Product_Activation::get_remote_buyer_info( $args );

        // echo "Buyer Info:";
        // print_r( $buyer_info->success );
        // echo "ends";

        if( isset( $buyer_info->success ) ){
            $is_registered = true;
        }

        $label_class = array(
            'agni-welcome-dashboard-themeinfo__label',
            $is_registered ? 'registered' : 'unregistered'
        );


    ?>
    <div class="agni-welcome">
        <div class="agni-welcome-dashboard">
            <div class="agni-welcome-dashboard__container">
                <div class="agni-welcome-dashboard__left">
                    <div class="agni-welcome-dashboard-themeinfo">
                        <span class="<?php echo esc_attr( cartify_prepare_classes( $label_class ) ); ?>"><?php 
                        if( $is_registered ){
                            echo esc_html__( 'Registered', 'cartify' );
                        }
                        else{
                            echo esc_html__( 'Unregistered', 'cartify' );
                        }
                        ?></span>
                        <img src="<?php echo esc_url( AGNI_FRAMEWORK_IMG_URL . '/logo_text@2x.png' ); ?>" />
                        <span class="agni-welcome-dashboard-themeinfo__version">
                            <?php echo sprintf( 
                                esc_html__( 'version %s%s', 'cartify' ), 
                                $theme_version, 
                                version_compare($latest_version, $current_version, '>') ? esc_html__( ' (Update available)', 'cartify' ) : '' 
                            ); ?>
                        </span>
                        <?php 
                        if( $is_registered ){ ?>
                            <?php if( current_user_can( 'install_themes' ) ){ 
                                if( version_compare($latest_version, $current_version, '>') ){ ?>
                                    <button class="agni-welcome-dashboard-themeinfo__btn download-update"><?php echo sprintf( esc_html__( 'Install new version %s', 'cartify' ), $latest_version ); ?></button>
                                <?php }
                                else { ?>
                                    <button class="agni-welcome-dashboard-themeinfo__btn"><?php echo sprintf( esc_html__( 'No update available', 'cartify' ), $latest_version ); ?></button>
                                <?php } 
                            } ?>
                            <?php /*  ?> <button class="agni-welcome-dashboard-themeinfo__btn check-update"><?php echo esc_html__( 'Check for update', 'cartify' ); ?></button>
                            <?php */ ?>
                        <?php }
                        else{ ?>
                            <a href="<?php echo esc_url( admin_url() ) ?>admin.php?page=agni_product_registration" class="agni-welcome-dashboard-themeinfo__btn unregistered"><?php echo esc_html__( 'Go to Registration', 'cartify' ); ?></a>
                        <?php }
                        ?>
                        <?php if( class_exists( 'Agni_Cartify' ) ){ ?>
                            <button class="agni-welcome-dashboard-themeinfo__btn status" data-system-status="`<?php echo esc_attr( $this->copy_system_status() ); ?>`"><?php echo esc_html__( 'Copy system status', 'cartify' ); ?></button>
                        <?php } ?>
                    </div>
                    <div class="agni-welcome-dashboard__additionalinfo">
                        <a href="<?php echo esc_url( 'youtube.com/playlist?list=PLLS77GIDaV_r1O86Ht0iQvlXPTyVXWAFh' ) ?>"><?php echo esc_html__( 'Video Tutorials', 'cartify' ); ?></a>
                        <a href="<?php echo esc_url( 'demo.agnidesigns.com/doc/cartify' ); ?>"><?php echo esc_html__( 'Documentation', 'cartify' ); ?></a>
                        <a href="#"><?php echo esc_html__( 'Submit a Ticket', 'cartify' ); ?></a>
                    </div>
                </div>
                <div class="agni-welcome-dashboard__right">
                    <div class="agni-welcome-dashboard-steps">
                        <h3 class="agni-welcome-dashboard-steps__title"><?php echo esc_html__( 'Get started', 'cartify' ); ?></h3>
                        <div class="agni-welcome-dashboard-step">
                            <h4 class="agni-welcome-dashboard-step__title"><?php echo esc_html__( '1. Register Your Product', 'cartify' ); ?></h4>
                            <p class="agni-welcome-dashboard-step__description"><?php echo esc_html__( 'Enter your Envato Personal token and click Register that’s it. Now you’ve register this copy of purchase.', 'cartify' ); ?></p>
                        </div>
                        <div class="agni-welcome-dashboard-step">
                            <h4 class="agni-welcome-dashboard-step__title"><?php echo esc_html__( '2. Install Plugins', 'cartify' ); ?></h4>
                            <p class="agni-welcome-dashboard-step__description"><?php echo esc_html__( 'Register the theme to get access to install all premium & core plugins. It’ll let you build site using enhanced Gutenberg editor.', 'cartify' ); ?></p>
                        </div>
                        <div class="agni-welcome-dashboard-step">
                            <h4 class="agni-welcome-dashboard-step__title"><?php echo esc_html__( '3. Import Demo contents', 'cartify' ); ?></h4>
                            <p class="agni-welcome-dashboard-step__description"><?php echo esc_html__( 'Start building your site from pre-designed demo pages by single click. You can even customise what to import.', 'cartify' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <?php do_action( 'agni_welcome_page_status' ); ?>

    <?php
    }

    public function importer_exporter_mainpage(){
        if( !class_exists( 'AgniImporterExporter' ) ){
            $this->require_plugins_notice( 'Agni Importer Exporter' );
        }

                do_action( 'agni_insert_importer_exporter' );
    }


    public function header_builder_contents(){
        if( !class_exists( 'Agni_Header_Builder' ) ){
            $this->require_plugins_notice();
        }

                do_action( 'agni_insert_header_builder' );

    }

    public function product_builder_contents(){
        if( !class_exists( 'AgniProductBuilder' ) ){
            $this->require_plugins_notice();
        }

        do_action( 'agni_insert_product_builder' );

    }

    public function slider_builder_contents(){
        if( !class_exists( 'Agni_Slider_Builder' ) ){
            $this->require_plugins_notice();
        }

                do_action( 'agni_insert_slider_builder' );

    }

    public function agni_fonts_callback(){
        if( !class_exists( 'AgniFontsManager' ) ){
            $this->require_plugins_notice();
        }

                do_action( 'agni_insert_fonts_manager' );

    }

    public function require_plugins_notice($plugin = ''){

        if( empty( $plugin ) ){
            $plugin = 'Agni Cartify';
        }



                ?>
        <div class="agni-required-registration">
            <div class="agni-required-registration__container">
                <div class="agni-required-registration__content">
                    <h1><?php echo sprintf( esc_html__( 'This feature requires "%s" plugin to be installed & activated.', 'cartify' ), esc_html( $plugin ) ); ?></h1>
                    <p><?php echo esc_html__( 'Make sure that you\'ve registered the product license. You can install all required & recommended plugins from "Install Plugins" tab on the left side under "Cartify" menu.', 'cartify' ); ?></p>
                    <div class="agni-required-registration__btns">
                        <a href="<?php echo esc_url( admin_url() ) ?>admin.php?page=agni_install_plugins" class="agni-required-registration__btn install-plugins"><?php echo esc_html__( 'Go to install plugins', 'cartify' ); ?></a>
                        <a href="<?php echo esc_url( admin_url() ) ?>admin.php?page=agni_product_registration" class="agni-required-registration__btn registration"><?php echo esc_html__( 'Register your product', 'cartify' ); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function copy_system_status(){

        $info = apply_filters( 'agni_system_status', '' );

        $output = '';
        // $output = '`';
        $output .= "\n"; 
        foreach ($info as $info_key => $info_type) { 
            $output .= '### ' . wp_strip_all_tags( $info_type['label'] ) . ' ###'; 
            $output .= "\n\n";
            foreach ($info_type['content'] as $key => $value) {
                $output .= wp_strip_all_tags( $value['label'] );
                $output .= ': ';
                $output .= wp_strip_all_tags( $value['value'] );
                $output .= "\n"; 
            }
            $output .= "\n";  
        }
        $output .= "\n"; 
        // $output .= '`';

        return $output;
    }

    public function check_for_theme_update(){
        $latest_version = '';
        $item_id = Agni_Product_Registration::get_item_id();

        $version_request_url = 'https://api.agnihd.com/agni-purchase-verifier/agni-purchase-verifier.php?get_theme_version=' . $item_id;


        $args = array(
            'headers'     => array(),
            'timeout'     => 60,
            'redirection' => 5,
            'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => true, // make it true for live
        );

        // Make an API request.
        $response = wp_remote_get( esc_url_raw( $version_request_url ), $args );


        // Check the response code.
        $response_code    = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        $debugging_information['response_code']   = $response_code;
        $debugging_information['response_cf_ray'] = wp_remote_retrieve_header( $response, 'cf-ray' );
        $debugging_information['response_server'] = wp_remote_retrieve_header( $response, 'server' );

                        if ( !is_wp_error( $response ) ) {
            $latest_version = $response['body'];
        }

        return $latest_version;
    }

    public function check_for_theme_update_ajax(){

        if (!check_ajax_referer('agni_dashboard_nonce', 'security')) {
            return 'Invalid Nonce';
        }

        $latest_version = $this->check_for_theme_update();

        $current_version = $this->current_version;

        $is_new_version = version_compare($latest_version, $current_version, '>');
        // if( version_compare($latest_version, $current_version, '>') ){
        //     echo sprintf( esc_html__( ' (Update available - %s)', 'cartify' ), $latest_version ); 
        // }

        wp_send_json(array(
            'version' => $latest_version,
            'new' => $is_new_version,
            'message' => sprintf( esc_html__( 'Update available - %s', 'cartify' ), $latest_version )

                    ));

        die();
    }

    public function get_theme_update_url(){

        $update_url = add_query_arg(array(
            'token' => Agni_Plugins_Installer::getToken()
        )); 

        return $update_url;
    }

    public function download_theme_update_ajax(){

        if (!check_ajax_referer('agni_dashboard_nonce', 'security')) {
            return 'Invalid Nonce';
        }

        if( !current_user_can( 'install_themes' ) ){
            return false;
        }

        $results = '';

        $latest_version = $this->check_for_theme_update();

        $current_version = $this->current_version;

        $is_new_version = version_compare($latest_version, $current_version, '>');

        if( $is_new_version ){
            $this->installTheme();
        }
        else{
            echo wp_send_json( array( 'error' => esc_html__( 'Already using latest version', 'cartify' ) ) );
        }

        die();


            }

    public function installTheme(){
        $url = '';

            $get_token = Agni_Plugins_Installer::getToken();

        // $fileSystemDirect = new WP_Filesystem_Direct(false);
        // $fileSystemDirect->rmdir($dir, true);

        $body = array(
            // 'file' => $pluginSlug . '.zip',
            // 'item_code' => Agni_Product_Registration::get_item_code(),
            'purchase_code' => Agni_Product_Registration::get_purchase_code(),
            'domain' => Agni_Product_Registration::get_domain_name()
        );
        $url = 'https://api.agnihd.com/agni-purchase-verifier/agni-purchase-verifier.php?update_theme=1' ;

        $args = array(
            'body'  => json_encode( $body ),
            'headers'     => array(
                'Content-Type' => 'application/json',
                // 'Content-Type' => 'application/octet-stream',
                'Authorization' => 'Bearer ' . $get_token
            ),
            'timeout'     => 120,
            'redirection' => 5,
            // 'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => true, // make it true for live
        );

        // print_r( $args );
        // echo 'raw url: ' . esc_url_raw( $url );

                // Make an API request.
        $response = wp_remote_post( esc_url_raw( $url ), $args );

        // Check the response code.
        $response_code    = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        $debugging_information['response_code']   = $response_code;
        $debugging_information['response_cf_ray'] = wp_remote_retrieve_header( $response, 'cf-ray' );
        $debugging_information['response_server'] = wp_remote_retrieve_header( $response, 'server' );
        $source = '';
        // print_r( $response );

        if( is_wp_error( $response ) ){
            // echo wp_send_json_error( $response->get_error_message() );
            echo wp_send_json_error( $response->errors );
        }

        if ( is_array( $response ) && !is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines
            $body    = $response['body']; // use the content
            // print_r( $headers );
            // print_r( $body );

            $theme_slug = $headers['Filename'];
            $decoded_body = json_decode($body);

            if( $decoded_body->error ){
                echo wp_send_json( json_decode($body) );
            }
            else{
                global $wp_filesystem;
                // Initialize the WP filesystem, no more using 'file-put-contents' function
                if (empty($wp_filesystem)) {
                    require_once (ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $temp_zip_name = $this->random_string();
                $pluginAdded = $wp_filesystem->put_contents( get_theme_root() . "/{$temp_zip_name}.zip", $body );

                if(!$pluginAdded ) {
                    echo wp_send_json( array( 'error' => esc_html__( 'Failed to install theme', 'cartify' ) ) );
                }
                else{
                    if( !empty( $theme_slug ) ){
                        $fileDeleted = $wp_filesystem->delete( get_theme_root() . "/{$theme_slug}", true );
                    }

                    if( $fileDeleted ){
                        $unzip_file = unzip_file( get_theme_root() . "/{$temp_zip_name}.zip", get_theme_root() );

                        if( !is_wp_error( $unzip_file ) ){
                            wp_delete_file( get_theme_root() . "/{$temp_zip_name}.zip" );

                            echo wp_send_json( array( 'success' => esc_html__( 'Theme updated successfully', 'cartify' ) ) );

                                                   }
                        else{
                            echo wp_send_json( array( 'error' => esc_html__( 'Failed to extract package', 'cartify' ) ) );

                        }
                    }
                    else{
                        echo wp_send_json( array( 'error' => esc_html__( 'Failed to delete existing version', 'cartify' ) ) );
                    }


                                    }
            }

        }

    }

    public function random_string($length = '16') {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

            return $key;
    }


        public function enqueue_scripts(){
        wp_enqueue_script( 'cartify-admin-dashboard', AGNI_FRAMEWORK_JS_URL . '/admin.js', array( 'jquery' ), wp_get_theme()->get('Version'), true );
        wp_localize_script( 'cartify-admin-dashboard', 'agni_dashboard', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('agni_dashboard_nonce')
        ) );
    }

}

$agni_cartify_dashboard = new Agni_Cartify_Dashboard();



?>