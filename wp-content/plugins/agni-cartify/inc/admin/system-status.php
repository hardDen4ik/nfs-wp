<?php

add_action( 'agni_welcome_page_status', 'agni_display_system_status' );

add_filter( 'agni_system_status', 'agni_get_system_status' );


function agni_display_system_status(){

    $info = apply_filters( 'agni_system_status', '' );

    ?>

    <div class="agni-system-status">
        <div class="agni-system-status__container">
            <h2><?php echo esc_html__( 'System Status', 'cartify' ); ?></h2>
            <?php foreach ($info as $info_key => $info_type) { ?>
                <div class="agni-system-status__panel <?php echo esc_attr( $info_key ); ?>">
                    <h4><?php echo esc_html( $info_type['label'] ); ?></h4>
                    <table>
                        <tbody>
                        <?php foreach ($info_type['content'] as $key => $value) { ?>
                            <tr>
                                <td><?php echo wp_kses( $value['label'], array( 'a' => array( 'href' => array(), 'target' => array() ) ) ); ?><span>:</span></td>
                                <td><?php echo wp_kses( $value['value'], array( 'mark' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) ); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
}

function agni_get_system_status(){

    global $wpdb; 

    include_once( ABSPATH . 'wp-admin/includes/theme-install.php' );

    $info = array(
        'theme' => array(
            'label' => esc_html__( 'Theme', 'cartify' ),
            'content' => array()
        ),
        'wordpress' => array(
            'label' => esc_html__( 'WordPress Environment', 'cartify' ),
            'content' => array()
        ),
        'server' => array(
            'label' => esc_html__( 'Server Environment', 'cartify' ),
            'content' => array()
        ),
        'active_plugins' => array(
            'label' => esc_html__( 'Active Plugins', 'cartify' ),
            'content' => array()
        ),
    );

    $active_theme         = wp_get_theme();

    $memory = agni_let_to_num( WP_MEMORY_LIMIT );

    if ( function_exists( 'memory_get_usage' ) ) {
        $system_memory = agni_let_to_num( @ini_get( 'memory_limit' ) );
        $memory        = max( $memory, $system_memory );
    }

    if ( $memory < 134217728 ) {
        $memory_value = '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( wp_kses( __( '%s - We recommend setting memory to at least 128MB. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'cartify' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), size_format( $memory ), esc_url( 'codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) ) . '</mark>';
    } else {
        $memory_value = '<mark class="yes">' . size_format( $memory ) . '</mark>';
    }

    $max_upload_size_int = (int) size_format( wp_max_upload_size() );
    $max_upload_size = size_format( wp_max_upload_size() );
    if ( $max_upload_size_int < 32 ) {
        $max_upload_size_value = '<mark class="error">' . sprintf( wp_kses( __( '%s - We recommend at least 32 MB.  <a href="%s" target="_blank">Here</a>', 'cartify' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $max_upload_size ), esc_url( 'premium.wpmudev.org/blog/increase-memory-limit/' ) ) . '</mark>';
    } else {
        $max_upload_size_value = '<mark class="yes">' . esc_html( $max_upload_size ) . '</mark>';
    }

    $post_max_size = size_format( agni_let_to_num( ini_get( 'post_max_size' ) ) );
    if ( $post_max_size < 128 ) {
        $post_max_size_value = '<mark class="error">' . sprintf( wp_kses( __( '%s - We recommend at least 128 MB. See: <a href="%s" target="_blank">Here</a>', 'cartify' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $post_max_size ), esc_url( '<a href="premium.wpmudev.org/blog/increase-memory-limit/' ) ) . '</mark>';
    } else {
        $post_max_size_value = '<mark class="yes">' . esc_html( $post_max_size ) . '</mark>';
    } 

    $max_exec_time = ini_get('max_execution_time');
    if ( $max_exec_time < 300 ) {
        $max_exec_time_value = '<mark class="error">' . sprintf( wp_kses( __( '%s - We recommend at least 300. See: <a href="%s" target="_blank">Here</a>', 'cartify' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $max_exec_time ), esc_url( 'codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded' ) ) . '</mark>';
    } else {
        $max_exec_time_value = '<mark class="yes">' . esc_html( $max_exec_time ) . '</mark>';
    }

    $max_input_var = ini_get( 'max_input_vars' );
    if ( $max_input_var < 2000 ) {
        $max_input_var_value = '<mark class="error">' . sprintf( wp_kses( __( '%s - We recommend at least 2000. See: <a href="%s" target="_blank">Here</a>', 'cartify' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $max_input_var ), esc_url( 'docs.woothemes.com/document/problems-with-large-amounts-of-data-not-saving-variations-rates-etc/#section-2' ) ) . '</mark>';
    } else {
        $max_input_var_value = '<mark class="yes">' . esc_html( $max_input_var ) . '</mark>';
    } 

    if ( function_exists( 'curl_version' ) ) {
        $curl_version = curl_version();
        $curl_version_value = esc_html( $curl_version['version'] ) . ', ' . esc_html( $curl_version['ssl_version'] );
    } else {
        $curl_version_value = esc_html__( 'N/A', 'cartify' );
    }


    if ( $wpdb->use_mysqli ) {
        $ver = mysqli_get_server_info( $wpdb->dbh );
    } else {
        $ver = mysql_get_server_info();
    }

    $db_version_value = ( ! empty( $wpdb->is_mysql ) && ! stristr( $ver, 'MariaDB' ) ) ? $wpdb->db_version() : '';



    $php_version = phpversion();

    if ( version_compare( $php_version, '5.6', '<' ) ) {
        $php_version_value = '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( wp_kses( __( '%s - We recommend a minimum PHP version of 5.6. See: <a href="%s" target="_blank">How to update your PHP version</a>', 'cartify' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $php_version ), esc_url( 'docs.woocommerce.com/document/how-to-update-your-php-version/' ) ) . '</mark>';
    } else {
        $php_version_value = '<mark class="yes">' . esc_html( $php_version ) . '</mark>';
    }


    if ( class_exists( 'ZipArchive' ) ) {
        $zip_value = '<mark class="yes">&#10003;</mark>';
    } else {
        $zip_value = '<mark class="no">&#10005;</mark>';
    }



    $info['theme']['content'][] = array(
        'label' => esc_html__( 'Name', 'cartify' ),
        'value' => esc_html( $active_theme->Name )
    );
    $info['theme']['content'][] = array(
        'label' => esc_html__( 'Version', 'cartify' ),
        'value' => esc_html( $active_theme->Version )
    );


    if( is_child_theme() ){

        $parent_theme         = wp_get_theme( $active_theme->Template );

        $info['theme']['content'][] = array(
            'label' => esc_html__( 'Child Theme', 'cartify' ),
            'value' => '&#10003;'
        );
        
        $info['theme']['content'][] = array(
            'label' => esc_html__( 'Parent Theme Name', 'cartify' ),
            'value' => $parent_theme->Name
        );
        
        $info['theme']['content'][] = array(
            'label' => esc_html__( 'Parent Theme Version', 'cartify' ),
            'value' => $parent_theme->Version
        );
        
    }
    else{
        $info['theme']['content'][] = array(
            'label' => esc_html__( 'Child Theme', 'cartify' ),
            'value' => '&#10005;'
        );
    }


    $info['wordpress']['content'][] = array(
        'label' => esc_html__( 'Home URL', 'cartify' ),
        'value' => esc_html( home_url() )
    );
    $info['wordpress']['content'][] = array(
        'label' => esc_html__( 'Site URL', 'cartify' ),
        'value' => esc_html( get_option( 'siteurl' ) )
    );
    $info['wordpress']['content'][] = array(
        'label' => esc_html__( 'WP Version', 'cartify' ),
        'value' => get_bloginfo('version')
    );
    $info['wordpress']['content'][] = array(
        'label' => esc_html__( 'WP Multisite', 'cartify' ),
        'value' => is_multisite() ? '&#10003;' : '&#10005;'
    );
    $info['wordpress']['content'][] = array(
        'label' => esc_html__( 'WP Debug Mode', 'cartify' ),
        'value' => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '&#10003;' : '&#10005;'
    );
    $info['wordpress']['content'][] = array(
        'label' => esc_html__( 'Language', 'cartify' ),
        'value' => get_locale()
    );

    $info['server']['content'][] = array(
        'label' => esc_html__( 'PHP Memory Limit', 'cartify' ),
        'value' => wp_kses( $memory_value, array( 'mark' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) )
    );
    $info['server']['content'][] = array(
        'label' => esc_html__( 'PHP Max Upload Size', 'cartify' ),
        'value' => wp_kses( $max_upload_size_value, array( 'mark' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) )
    );
    $info['server']['content'][] = array(
        'label' => esc_html__( 'PHP Post Max Size', 'cartify' ),
        'value' => wp_kses( $post_max_size_value, array( 'mark' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) )
    );
    $info['server']['content'][] = array(
        'label' => esc_html__( 'PHP Max Execution Time', 'cartify' ),
        'value' => wp_kses( $max_exec_time_value, array( 'mark' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) )
    );
    $info['server']['content'][] = array(
        'label' => esc_html__( 'PHP Max Input Vars', 'cartify' ),
        'value' => wp_kses( $max_input_var_value, array( 'mark' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) )
    );
    $info['server']['content'][] = array(
        'label' => esc_html__( 'cURL Version', 'cartify' ),
        'value' => wp_kses( $curl_version_value, array( 'mark' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) )
    );
    $info['server']['content'][] = array(
        'label' => esc_html__( 'PHP Version', 'cartify' ),
        'value' => wp_kses( $php_version_value, array( 'mark' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) )
    );
    $info['server']['content'][] = array(
        'label' => esc_html__( 'ZipArchives', 'cartify' ),
        'value' => wp_kses( $zip_value, array( 'mark' => array( 'class' => array() ), 'a' => array( 'href' => array(), 'target' => array() ) ) )
    );
    
    $info['server']['content'][] = array(
        'label' => esc_html__( 'MySQL Version', 'cartify' ),
        'value' => sanitize_text_field( $db_version_value )
    );

    $info['active_plugins']['content'] = agni_get_active_plugins();

    return $info;
}

function agni_get_active_plugins(){

    $plugins = array();

    $active_plugins = (array) get_option( 'active_plugins', array() );

    if ( is_multisite() ) {
        $network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
        $active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
    }

    foreach ( $active_plugins as $plugin ) {

        $plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
        $version_string = '';
        $network_string = '';

        if ( ! empty( $plugin_data['Name'] ) ) {

            // Link the plugin name to the plugin url if available.
            $plugin_name = esc_html( $plugin_data['Name'] );

            if ( ! empty( $plugin_data['PluginURI'] ) ) {
                $plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage' , 'cartify' ) . '" target="_blank">' . $plugin_name . '</a>';
            }

            $plugins[] = array(
                'label' => wp_kses( $plugin_name, array( 
                    'a' => array( 
                        'href' => array(), 
                        'title' => array(), 
                        'target' => array() 
                        ) 
                    ) 
                ),
                'value' => sprintf( 
                    esc_html_x( 'by %s', 'by author', 'cartify' ), 
                    $plugin_data['Author'] 
                    ) . ' &ndash; ' . esc_html( $plugin_data['Version'] )
                );

        }
    }

    return $plugins;
}

function agni_let_to_num( $size ) {
	$l = substr( $size, -1 );
	$ret = substr( $size, 0, -1 );
	switch ( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
		case 'T':
			$ret *= 1024;
		case 'G':
			$ret *= 1024;
		case 'M':
			$ret *= 1024;
		case 'K':
			$ret *= 1024;
	}
	return $ret;
}
