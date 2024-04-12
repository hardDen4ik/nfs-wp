<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if ( !class_exists( 'Plugin_Upgrader' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}


class Cartify_Plugin_Upgrader_Skin extends Plugin_Upgrader_Skin {
    public function feedback($string, ...$args){
    }
    public function header() {
    }

     public function footer() {
    }

    public function set_result($result) {
        $this->result = false;
    }
}

class Agni_Plugins_Installer{

    private $defaults;

    public function __construct(){

        $this->init();

        add_action( 'admin_menu', array( $this, 'header_builder_menu_page') );

        // add_action( 'plugins_loaded', array( $this, 'install' ) );

        add_action( 'wp_ajax_activation', array( $this, 'activation' ), 10, 1 );
        add_action( 'wp_ajax_nopriv_activation', array( $this, 'activation' ), 10, 1 );

    }

    public function init(){
        $this->defaults = array(
            'position' => 2
        );
    }


    public static function pluginsList(){

                $available_plugins = array(
            array(
                'name'                  => 'Agni Cartify', 
                'slug'                  => 'agni-cartify', 
                'source'                => 'bundled', 
                'required'              => true,
                'version'               => '1.0.0', 
                'force_activation'      => false,
                'force_deactivation'    => false,
                'external_url'          => '',
                'thumbnail_url'         => '',
                'premium'               => true,
                'author'                => 'AgniHD',
                'author_uri'            => esc_url( 'agnidesigns.com' )
            ),
            array(
                'name'                  => 'Agni Builder', 
                'slug'                  => 'agni-builder', 
                'source'                => 'bundled', 
                'required'              => true,
                'version'               => '1.0.0', 
                'force_activation'      => false,
                'force_deactivation'    => false,
                'external_url'          => '',
                'thumbnail_url'         => '',
                'premium'               => true,
                'author'                => 'AgniHD',
                'author_uri'            => esc_url( 'agnidesigns.com' )
            ), 
            array(
                'name'                  => 'Agni Importer Exporter', 
                'slug'                  => 'agni-importer-exporter', 
                'source'                => 'bundled', 
                'required'              => false,
                'version'               => '1.0.2', 
                'force_activation'      => false,
                'force_deactivation'    => false,
                'external_url'          => '',
                'thumbnail_url'         => '',
                'premium'               => true,
                'author'                => 'AgniHD',
                'author_uri'            => esc_url( 'agnidesigns.com' )
            ), 
            // array(
            //     'name'                  => 'Revolution Slider', 
            //     'slug'                  => 'revslider', 
            //     'source'                => 'bundled', 
            //     'required'              => false,
            //     'version'               => '6.0.6', 
            //     'force_activation'      => false,
            //     'force_deactivation'    => false,
            //     'external_url'          => esc_url( 'revolution.themepunch.com/' ),
            //     'author'                => 'themepunch',
            //     'author_uri'            => esc_url( 'revolution.themepunch.com/' ),
            //     'premium'               => true
            // ),
            array(
                'name'                  => 'WooCommerce',
                'slug'                  => 'woocommerce',
                'source'                => '',
                'required'              => false,
                'version'               => '6.4.1',
                'force_activation'      => false,
                'force_deactivation'    => false,
                'installation_path'     => 'woocommerce/woocommerce.php',
                'external_url'          => esc_url('wordpress.org/plugins/woocommerce/'),
            ),
            array(
                'name'                  => 'Kirki Customizer Framework',
                'slug'                  => 'kirki',
                'source'                => '',
                'required'              => true,
                'version'               => '3.0.44',
                'force_activation'      => false,
                'force_deactivation'    => false,
                'installation_path'     => 'kirki/kirki.php',
                'external_url'          => esc_url( 'wordpress.org/plugins/kirki/' ),
            ),
            array(
                'name'                  => 'MC4WP: Mailchimp for WordPress',
                'slug'                  => 'mailchimp-for-wp',
                'source'                => '',
                'required'              => false,
                'version'               => '4.8.7',
                'force_activation'      => false,
                'force_deactivation'    => false,
                'installation_path'     => 'mailchimp-for-wp/mailchimp-for-wp.php',
                'external_url'          => esc_url( 'wordpress.org/plugins/mailchimp-for-wp/' ),
            )
        );

        return $available_plugins;
    }

    public function header_builder_menu_page(){

        add_submenu_page( 'cartify', esc_html__( 'Install Plugins', 'cartify' ), esc_html__( 'Install Plugins', 'cartify' ), 'edit_theme_options', 'agni_install_plugins', array( $this, 'plugins_installer_contents' ), $this->defaults['position'] );
    }

    public function plugins_installer_contents(){

        if( !current_user_can( 'install_plugins' ) ){
            return false;
        }

        // wp_enqueue_style( 'agni-header-builder-react-style');
        // wp_enqueue_script( 'agni-header-builder-react-script');

        $purchase_code = Agni_Product_Registration::get_purchase_code();

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Agni Plugins Manager', 'cartify' ); ?></h1>
            <div id="agni-plugins-manager" class="agni-plugins-manager">

                <div class="agni-plugins">
                    <?php 

                    foreach ($this->pluginsList() as $key => $plugin) {

                        $latest_version = '';

                        $plugin_info = $this->getPluginInfo( $plugin );

                        $url = add_query_arg( array(
                            'plugin' => $plugin['slug'],
                        ));

                        if( isset( $plugin['premium'] ) && !isset( $plugin_info['active'] ) && !isset( $plugin_info['deactive'] ) ){
                            $url = add_query_arg( array(
                                'premium' => $plugin['premium'],
                                'token' => $this->getToken()
                            ), $url);
                        }

                        if( !isset( $plugin['premium'] ) || !$plugin['premium']  ){

                                                        $args = array(
                                'slug' => $plugin['slug'],
                                'fields' => array(
                                    'version' => true,
                                    // 'icons' => true
                                )
                            );

                                            if ( ! function_exists( 'plugins_api' ) ) {
                                require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
                            }

                                            $repo_plugin_info = plugins_api( 'plugin_information', $args );

                                            $latest_version = $repo_plugin_info->version;
                        }
                        else{

                            $version_request_url = 'https://api.agnihd.com/agni-purchase-verifier/agni-purchase-verifier.php?get_plugin_version=' . $plugin['slug'];


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

                            // $latest_version = '3.0.0';
                        }

                                                ?>
                        <div class="agni-plugin <?php echo ( isset( $plugin['premium'] ) && $plugin['premium'] && empty($purchase_code)) ? 'disabled' : ''; ?>">
                            <div class="agni-plugin__left">
                                <?php if( isset( $plugin_info['thumb'] ) ){ ?>
                                    <img src="<?php echo esc_url( $plugin_info['thumb'] ); ?>"/>
                                <?php } ?>
                                <div class="agni-plugin__meta">
                                    <?php if( isset( $plugin['required'] ) && $plugin['required'] ){ ?>
                                        <span class="required"><?php echo esc_html__( 'Required', 'cartify' ); ?></span>
                                    <?php }
                                    if( isset( $plugin['premium'] ) && $plugin['premium'] ){ ?>
                                        <span class="premium"><?php echo esc_html__( 'Premium', 'cartify' ); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="agni-plugin__right">
                                <h3><?php if( isset( $plugin['external_url'] ) ){ ?>
                                    <a href="<?php echo esc_url( $plugin['external_url'] ) ?>"><?php echo esc_html( isset($plugin_info['name']) ? $plugin_info['name'] : $plugin['name'] ); ?></a>
                                <?php } 
                                else{ ?>
                                    <span><?php echo esc_html( $plugin_info['name'] ); ?></span>
                                <?php } ?></h3>
                                <p><span><?php echo esc_html__( 'By ', 'cartify' ); ?></span><span><?php if( isset( $plugin_info['author'] ) && !empty( $plugin_info['author'] ) ){
                                    echo wp_kses( $plugin_info['author'], array( 'a' => array( 'href' => array() ) ) );
                                } ?></span></p>
                                <p><span><?php echo esc_html__( 'v', 'cartify' ); ?></span><span><?php echo esc_html( $plugin_info['version'] ); ?><?php 
                                    if( version_compare($latest_version, $plugin_info['version'], '>') ){
                                        echo sprintf( esc_html__( ' (Update available - %s)', 'cartify' ), $latest_version ); 
                                    }
                                ?></span></p>
                                <div class="agni-plugin-actions">
                                    <?php if( isset( $plugin_info['active'] ) && $plugin_info['active'] ){ 
                                        $url = esc_url( add_query_arg( array(
                                            'deactivate' => true
                                        ), $url ) ); ?>
                                        <a class="agni-plugin-action deactivate" href="<?php echo esc_url( $url ); ?>">
                                            <span><?php echo esc_html__( 'Deactivate', 'cartify' ); ?></span>
                                        </a>
                                    <?php }
                                    else if( isset( $plugin_info['installed'] ) && $plugin_info['installed'] ){ 
                                        $url = esc_url( add_query_arg( array(
                                            'activate' => true
                                        ), $url ) ); ?>
                                        <a class="agni-plugin-action activate" href="<?php echo esc_url( $url ); ?>">
                                            <span><?php echo esc_html__( 'Activate', 'cartify' ); ?></span>
                                        </a>
                                    <?php }
                                    else{ 
                                        $url = esc_url( add_query_arg( array(
                                            'install' => true
                                        ), $url ) ); ?>
                                        <a class="agni-plugin-action install" href="<?php echo esc_url( $url ); ?>">
                                            <span><?php echo esc_html__( 'Install', 'cartify' ); ?></span>
                                        </a>
                                    <?php } ?>
                                    <?php if( ( version_compare($latest_version, $plugin_info['version'], '>') ) ){
                                        if( ( isset( $plugin_info['active'] ) && $plugin_info['active'] ) || ( isset( $plugin_info['installed'] ) && $plugin_info['installed'] ) ){

                                                                                        $update_url = add_query_arg( array(
                                                'plugin' => $plugin['slug'],
                                                'update' => true
                                            ));

                                            if( isset( $plugin['premium'] ) && $plugin['premium'] ){
                                                $update_url = add_query_arg( array(
                                                    'premium' => $plugin['premium'],
                                                    'token' => $this->getToken(),
                                                ), $update_url);
                                            }

                                            ?>
                                            <a class="agni-plugin-action update" href="<?php echo esc_url( $update_url ); ?>" title="Download & Install Update">
                                                <span class="tooltip"><?php echo esc_html__( 'Download & Install Update', 'cartify' ); ?></span>
                                                <span><?php echo esc_html__( 'Update', 'cartify' ); ?></span>
                                            </a>
                                        <?php } 
                                    }?>
                                </div>
                                <?php

                                                                ?>
                            </div>
                        </div>
                        <?php
                    }

                                        ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function activation(){

        if (!check_ajax_referer('agni_product_registration_nonce', 'security')) {
            return 'Invalid Nonce';
        }

        if( !current_user_can( 'activate_plugins' ) ){
            return false;
        }

        if( isset( $_POST['plugin'] ) ){
            $pluginSlug = $_POST['plugin'];

            $installed_plugins = get_plugins();

            $plugin_info = array_filter( $installed_plugins, function($existing_plugin) use($pluginSlug){
                if( $existing_plugin['TextDomain'] === $pluginSlug ){
                    return true;
                }
            } );

            foreach ($plugin_info as $installedPluginPath => $value) {
                if( $_POST['activate'] ){
                    // echo 'DIsplaying activate';
                    $result = $this->activatePlugin( $installedPluginPath );
                }
                else if( $_POST['deactivate'] ){
                    // echo 'DIsplaying deactivate' . $installedPluginPath;
                    $result = $this->deactivatePlugin( $installedPluginPath );
                }
                else if( $_POST['update'] ){
                    if( isset($_POST['premium']) && $_POST['premium'] ){
                        $result = $this->installPremiumPlugin( $_POST['plugin'], $_POST['token'], true );
                    }
                    else{
                        $result = $this->installFreePlugin( $_POST['plugin'], true );
                    }
                }

                                if ( is_wp_error( $result ) ) {
                    echo esc_html( $result->get_error_message() );
                }
            }


            if( isset( $_POST['install'] ) && $_POST['install'] ){
                if( isset($_POST['premium']) && $_POST['premium'] ){
                    $result = $this->installPremiumPlugin( $_POST['plugin'], $_POST['token'] );
                }
                else{
                    $result = $this->installFreePlugin( $_POST['plugin'] );
                }

            }
        }

        return $result;

        die();
    }

    public function getPluginInfo( $plugin ){
        $result = array();

        $installed_plugins = get_plugins();

        $plugin_info = array_filter( $installed_plugins, function($existing_plugin) use($plugin){
            if( $existing_plugin['TextDomain'] === $plugin['slug'] ){
                return true;
            }
        } );

        foreach($plugin_info as $key => $existing_plugin) {
            $result['name'] = $existing_plugin['Name'];
            $result['description'] = $existing_plugin['Description'];
            $result['version'] = $existing_plugin['Version'];
            $result['author'] = $existing_plugin['AuthorName'];
            // $result['author_uri'] = $existing_plugin['AuthorURI'];
            $result['installed'] = true;
            $result['active'] = in_array( $key, (array) get_option( 'active_plugins', array() ), true );

            if( !empty( $existing_plugin['AuthorURI'] ) ){
                $result['author'] = '<a href="' .$existing_plugin['AuthorURI'] . '">'.$existing_plugin['AuthorName'].'</a>';
            }
        }


                if( $plugin['source'] === 'bundled' && !empty($plugin['source']) ){
            if( empty( $plugin_info ) ){
                // $result['error'] = 'Need to add details';
                $result['name'] = $plugin['name'];
                $result['version'] = $plugin['version'];
                $result['author'] = $plugin['author'];
                // $result['installed'] = false;

                if( !empty( $plugin['author_uri'] ) ){
                    $result['author'] = '<a href="' .$plugin['author_uri'] . '">'.$plugin['author'].'</a>';
                }
            }
        }
        // else if( !empty($plugin['source']) ){
        //     if( empty( $plugin_info ) ){
        //         $result['error'] = 'Need to add details';
        //     }
        // }
        else{
            $args = array(
                'slug' => $plugin['slug'],
                'fields' => array(
                    'version' => true,
                    'icons' => true
                )
            );

            if ( ! function_exists( 'plugins_api' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
            }

            $repo_plugin_info = plugins_api( 'plugin_information', $args );

            if( !is_wp_error( $repo_plugin_info ) ){
                if( empty( $plugin_info ) ){
                    $result['name'] = $repo_plugin_info->name;
                    $result['version'] = $repo_plugin_info->version;
                    $result['author'] = $repo_plugin_info->author;
                    // $result['author_uri'] = $repo_plugin_info->author_profile;
                }
                $result['thumb'] = $repo_plugin_info->icons['2x'];

                            }
            else{
                $result['error'] = $repo_plugin_info->get_error_message();
            }
        }

                // print_r( $result );

        return $result;

    }

    public function activateInstalledPlugins( ){

        if( !current_user_can( 'activate_plugins' ) ){
            return false;
        }

        if( isset( $_GET['plugin'] ) ){
            $pluginSlug = $_GET['plugin'];

            $installed_plugins = get_plugins();

            $plugin_info = array_filter( $installed_plugins, function($existing_plugin) use($pluginSlug){
                if( $existing_plugin['TextDomain'] === $pluginSlug ){
                    return true;
                }
            } );

            foreach ($plugin_info as $installedPluginPath => $value) {
                // return $installedPluginPath;
                // activate_plugin($installedPluginPath);
                $result = activate_plugin( $installedPluginPath, self_admin_url( 'admin.php?page=agni_install_plugins' ) );
                if ( is_wp_error( $result ) ) {
                    echo esc_html( $result->get_error_message() );
                }


                // wp_redirect( self_admin_url( "admin.php?page=agni_install_plugins&activate=true" ) );
                // exit;
            }
        }
    }



    public static function getToken(){

        $url = 'https://api.agnihd.com/agni-purchase-verifier/agni-purchase-verifier.php?get_token=1';

        // $url = add_query_arg(array(
        //     'domain' => Agni_Product_Registration::get_domain_name(),
        //     'purchase_code' => Agni_Product_Registration::get_purchase_code()
        // ), $url);

        $args = array(
            'headers'     => array(),
            'timeout'     => 60,
            'redirection' => 5,
            'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => true, // make it true for live
        );

        // Make an API request.
        $response = wp_remote_get( esc_url_raw( $url ), $args );

        // Check the response code.
        $response_code    = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        $debugging_information['response_code']   = $response_code;
        $debugging_information['response_cf_ray'] = wp_remote_retrieve_header( $response, 'cf-ray' );
        $debugging_information['response_server'] = wp_remote_retrieve_header( $response, 'server' );

                if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines
            $body    = $response['body']; // use the content

            // return 'https://api.agnihd.com/agni-purchase-verifier/api.php?file=envato-market.zip&token=' . json_decode( $body );
            return json_decode( $body );
        }

        return $response;
        // $response = wp_remote_get( 'https://api.agnihd.com/agni-purchase-verifier/api.php' );
    }

    public function activatePlugin( $pluginSlug ){
        $result = activate_plugin( $pluginSlug );

        if( is_wp_error($result) ){
            echo wp_send_json( array( 'error' => esc_html__( 'Failed to activate plugin', 'cartify' ) ) );
        }
        else{
            echo wp_send_json( array( 'success' => esc_html__( 'Plugin activated successfully', 'cartify' ) ) );
        }
    }


    public function deactivatePlugin( $pluginSlug ){
        $result = deactivate_plugins( $pluginSlug );

                if( is_wp_error($result) ){
            echo wp_send_json( array( 'error' => esc_html__( 'Failed to deactivate plugin', 'cartify' ) ) );
        }
        else{
            echo wp_send_json( array( 'success' => esc_html__( 'Plugin deactivated successfully', 'cartify' ) ) );
        }
    }

    public function installFreePlugin($pluginSlug, $update = false){

        require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

               $source = '';
        $installation_paths = array();
        foreach ($this->pluginsList() as $key => $plugin) {
            if( $plugin['slug'] === $pluginSlug ){
                // $source = $plugin['source'];
                if( empty( $plugin['source'] ) ){
                    $args = array(
                        'slug' => $plugin['slug'],
                        'fields' => array(
                            'version' => true,
                            'icons' => true
                        )
                    );

                                $repo_plugin_info = plugins_api( 'plugin_information', $args );                    
                    $source = $repo_plugin_info->download_link;

                                        if( isset( $plugin['installation_path'] ) ){
                        $installation_paths[$plugin['slug']] = $plugin['installation_path'];
                    }
                }
                else{
                    $source = $plugin['source'];
                }
            }
            // print_r( $plugin );
        }

        $skin = new Cartify_Plugin_Upgrader_Skin(); 
        $upgrader = new Plugin_Upgrader( $skin );

        if( $update ){
            $install = $upgrader->upgrade( $installation_paths[$pluginSlug] );
        }
        else{
            $install = $upgrader->install( $source );
        }


                if( $install ){
            if( $update ){
                echo wp_send_json( array( 'success' => esc_html__( 'Plugin updated successfully', 'cartify' ), 'contents'=> $install ) );
            }
            else{
                echo wp_send_json( array( 'success' => esc_html__( 'Plugin installed successfully', 'cartify' ) ) );
            }
        }
        else {
            if( $update ){
                echo wp_send_json( array( 'error' => esc_html__( 'Failed to update plugin', 'cartify' ), 'contents'=> $install ) );
            }
            else{
                echo wp_send_json( array( 'error' => esc_html__( 'Failed to install plugin', 'cartify' ) ) );
            }
        }
    }

    public function installPremiumPlugin( $pluginSlug, $token, $update = false ){


                // $fileSystemDirect = new WP_Filesystem_Direct(false);
        // $fileSystemDirect->rmdir($dir, true);

        $body = array(
            'file' => $pluginSlug . '.zip',
            // 'item_code' => Agni_Product_Registration::get_item_code(),
            'purchase_code' => Agni_Product_Registration::get_purchase_code(),
            'domain' => Agni_Product_Registration::get_domain_name()
        );

        // print_r( $body );
        $url = 'https://api.agnihd.com/agni-purchase-verifier/agni-purchase-verifier.php?install_plugins=1' ;


        $args = array(
            'body'  => json_encode( $body ),
            'headers'     => array(
                'Content-Type' => 'application/json',
                // 'Content-Type' => 'application/octet-stream',
                'Authorization' => 'Bearer ' . $token
            ),
            'timeout'     => 120,
            'redirection' => 5,
            'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => true, // make it true for live
        );

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
            echo wp_send_json_error( $response->errors );
        }

        if ( is_array( $response ) && !is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines
            $body    = $response['body']; // use the content

            // print_r( $body ); 
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

                if( !empty( $pluginSlug ) ){
                    $pluginAdded = $wp_filesystem->put_contents( WP_PLUGIN_DIR . "/{$pluginSlug}.zip", $body );
                }

                if(!$pluginAdded ) {
                    echo wp_send_json( array( 'error' => esc_html__( 'Failed to install plugin', 'cartify' ) ) );
                }
                else{
                    $fileDeleted = true;

                    if( $update ){
                        $fileDeleted = $wp_filesystem->delete( WP_PLUGIN_DIR . "/{$pluginSlug}", true );
                    }

                    if( $fileDeleted ){
                        $unzip_file = unzip_file( WP_PLUGIN_DIR . "/{$pluginSlug}.zip", WP_PLUGIN_DIR );

                        if( !is_wp_error( $unzip_file ) ){
                            wp_delete_file( WP_PLUGIN_DIR . "/{$pluginSlug}.zip" );

                            if( $update ){
                                echo wp_send_json( array( 'success' => esc_html__( 'Plugin updated successfully', 'cartify' ) ) );
                            }
                            else{
                                echo wp_send_json( array( 'success' => esc_html__( 'Plugin installed successfully', 'cartify' ) ) );
                            }
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



}

$agni_plugins_installer = new Agni_Plugins_Installer();

