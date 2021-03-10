<?php

if ( ! defined( 'ABSPATH' ) )
    exit;



/**
 *  wpuxss_eml_pro_maybe_update
 *
 *  @since    2.5
 *  @created  28/01/18
 */

add_action( 'admin_init', 'wpuxss_eml_pro_maybe_update' );

if ( ! function_exists( 'wpuxss_eml_pro_maybe_update' ) ) {

    function wpuxss_eml_pro_maybe_update() {

        global $pagenow;

        $eml_transient = get_site_transient( 'eml_transient' );
        $period = isset( $eml_transient->update_error ) ? 30 * MINUTE_IN_SECONDS : 12 * HOUR_IN_SECONDS;


        if ( isset( $eml_transient->last_checked )
        && $period > ( time() - $eml_transient->last_checked )
        // making sure force-check is taken into account on update-core.php only
        && ( empty( $_GET['force-check'] ) || 'update-core.php' !== $pagenow ) ) {
            return;
        }

        $license_key = get_site_option( 'wpuxss_eml_pro_license_key', '' );
        wpuxss_eml_pro_look_for_update( $license_key );
    }
}



/**
 *  wpuxss_eml_pro_look_for_update
 *
 *  @since    2.5
 *  @created  28/01/18
 */

if ( ! function_exists( 'wpuxss_eml_pro_look_for_update' ) ) {

    function wpuxss_eml_pro_look_for_update( $license_key ) {

        $remote_info = wpuxss_eml_pro_remote_info( 'get-info', $license_key );
        $wpuxss_eml_pro_basename = wpuxss_get_eml_basename();
        $eml_transient = new stdClass;


        $eml_license_active = ( ! empty( $license_key ) && ! is_wp_error( $remote_info ) && 'active' === $remote_info->license_status ) ? 1 : 0;


        delete_site_transient( 'eml_transient' );


        $eml_transient->license_was_active = $eml_license_active;
        $eml_transient->last_checked = time();


        if ( is_wp_error( $remote_info ) ) {
            $eml_transient->update_error = $remote_info->get_error_message();
        }
        elseif ( '' === $license_key ) {
            // add nothing to $eml_transient in this case
        }
        elseif ( version_compare( $remote_info->version, EML_VERSION, '>' ) ) {

            $wpuxss_eml_pro_slug = wpuxss_get_eml_slug();


            $args = array(
                'action' => 'update',
                'key' => $license_key
            );

            $eml_transient->name = $remote_info->name;
            $eml_transient->slug = $wpuxss_eml_pro_slug;
            $eml_transient->plugin = $wpuxss_eml_pro_basename;
            $eml_transient->new_version = $remote_info->version;
            $eml_transient->requires = $remote_info->requires;
            $eml_transient->tested = $remote_info->tested;
            $eml_transient->added = $remote_info->added;
            $eml_transient->last_updated = $remote_info->last_updated;
            $eml_transient->url = $remote_info->url;
            $eml_transient->package = $eml_license_active ? wpuxss_eml_pro_get_url() . '?' . build_query( $args ) : '';
        }

        set_site_transient( 'eml_transient', $eml_transient );
    }
}



/**
 *  wpuxss_eml_pro_set_site_transient_update_plugins
 *
 *  @since    2.5
 *  @created  28/01/18
 */

add_action( 'load-plugins.php', 'wpuxss_eml_pro_set_site_transient_update_plugins' );
add_action( 'load-update.php', 'wpuxss_eml_pro_set_site_transient_update_plugins' );
add_action( 'load-update-core.php', 'wpuxss_eml_pro_set_site_transient_update_plugins' );
add_action( 'wp_update_plugins', 'wpuxss_eml_pro_set_site_transient_update_plugins' );

if ( ! function_exists( 'wpuxss_eml_pro_set_site_transient_update_plugins' ) ) {

    function wpuxss_eml_pro_set_site_transient_update_plugins() {

        $eml_transient = get_site_transient( 'eml_transient' );


        if ( false === $eml_transient ) {
            return;
        }


        unset( $eml_transient->last_checked );
        unset( $eml_transient->update_error );
        unset( $eml_transient->license_was_active );


        $wpuxss_eml_pro_basename = wpuxss_get_eml_basename();
        $transient = get_site_transient( 'update_plugins' );

        if ( ! is_object( $transient ) ) {
            $transient = new stdClass;
        }

        if ( ! isset( $eml_transient->package ) ) {
            unset( $transient->response[$wpuxss_eml_pro_basename] );
        }
        else {
            $transient->response[$wpuxss_eml_pro_basename] = $eml_transient;
        }

        set_site_transient( 'update_plugins', $transient );
    }
}



/**
 *  wpuxss_eml_update_request_error_message
 *
 *  @since    2.4.2
 *  @created  17/01/17
 */

add_action( 'after_plugin_row_' . wpuxss_get_eml_basename(), 'wpuxss_eml_update_request_error_message', 10, 3 );

if ( ! function_exists( 'wpuxss_eml_update_request_error_message' ) ) {

    function wpuxss_eml_update_request_error_message( $file, $plugin_data, $status ) {

        if ( ! current_user_can( 'update_plugins' ) )
            return;

        if ( is_multisite() && ! is_network_admin() )
            return;


        $wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );

        if ( is_network_admin() ) {
            $active_class = is_plugin_active_for_network( $file ) ? ' active' : '';
        }
        else {
            $active_class = is_plugin_active( $file ) ? ' active' : '';
        }

        $open = '<tr class="plugin-update-tr' . esc_attr($active_class) . '" id="' . esc_attr( wpuxss_get_eml_slug() . '-update-error' ) . '" data-slug="' . esc_attr( wpuxss_get_eml_slug() ) . '" data-plugin="' . esc_attr( $file ) . '"><td colspan="' . esc_attr( $wp_list_table->get_column_count() ) . '" class="plugin-update colspanchange"><div class="update-message notice inline notice-warning notice-alt"><p>';
        $close = '</p></div></td></tr>';


        $license_key = get_site_option( 'wpuxss_eml_pro_license_key', '' );

        if ( '' === $license_key ) {

            echo $open;

            printf(
                '<strong>%s</strong> ',
                sprintf(
                    __('Auto-updates disabled for %s.','enhanced-media-library'),
                     esc_html($plugin_data['Name'])
                )
            );

            $utility_link = is_multisite() ? network_admin_url('settings.php?page=eml-settings#eml-license-key-section') : admin_url('options-general.php?page=eml-settings#eml-license-key-section');

            printf(
                __('To unlock updates, please <a href="%s">activate your license</a>. You can get your license key in <a href="%s">Your Account</a>. If you do not have a license, you are welcome to <a href="%s">purchase it</a>.', 'enhanced-media-library'),
                $utility_link,
                esc_url('https://wpuxsolutions.com/account/'),
                esc_url('https://wpuxsolutions.com/pricing/')
            );

            echo $close;

            return;
        }


        $eml_transient = get_site_transient( 'eml_transient' );

        if ( ! isset( $eml_transient->update_error ) ) {
            return;
        }


        echo $open;

        printf( __( '%s could not establish a secure connection %s. An error occurred: %s. Please <a href="%s">contact plugin authors</a>.', 'enhanced-media-library' ),
            esc_html($plugin_data['Name']),
            __( 'to check if an update is available', 'enhanced-media-library' ),
            sprintf(
                '<strong>%s</strong>',
                esc_html($eml_transient->update_error)
            ),
            esc_url('https://wpuxsolutions.com/support/')
        );

        echo $close;
    }
}



/**
 *  wpuxss_eml_pro_plugins_api
 *
 *  @since    2.0
 *  @created  13/10/14
 */

add_filter( 'plugins_api', 'wpuxss_eml_pro_plugins_api', 10, 3 );

if ( ! function_exists( 'wpuxss_eml_pro_plugins_api' ) ) {

    function wpuxss_eml_pro_plugins_api( $res, $action, $args ) {

        $wpuxss_eml_pro_slug = wpuxss_get_eml_slug();


        if ( ! isset( $args->slug ) || $args->slug != $wpuxss_eml_pro_slug ) {
            return $res;
        }


        // getting info from the free version
        $args->slug = 'enhanced-media-library';

        $url = $http_url = 'http://api.wordpress.org/plugins/info/1.0/';
        if ( $ssl = wp_http_supports( array( 'ssl' ) ) ) {
            $url = set_url_scheme( $url, 'https' );
        }

        $http_args = array(
            'timeout' => 15,
            'body' => array(
                'action' => $action,
                'request' => serialize( $args )
            )
        );
        $request = wp_remote_post( $url, $http_args );

        if ( $ssl && is_wp_error( $request ) ) {

            trigger_error( __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)','enhanced-media-library' ), headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE );
            $request = wp_remote_post( $http_url, $http_args );
        }

        if ( is_wp_error($request) ) {

            $res = new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.','enhanced-media-library' ), $request->get_error_message() );
        }
        else {

            $res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
            if ( ! is_object( $res ) && ! is_array( $res ) )
            $res = new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.','enhanced-media-library' ), wp_remote_retrieve_body( $request ) );
        }


        // getting info for PRO from the latest transient
        // TODO: maybe use eml_transient?
        $info = new stdClass();
        $transient = get_site_transient( 'update_plugins' );
        $wpuxss_eml_pro_basename = wpuxss_get_eml_basename();


        if ( isset( $transient->response[$wpuxss_eml_pro_basename] ) ) {
            $info = $transient->response[$wpuxss_eml_pro_basename];
        }
        else {
            return $res;
        }


        $res->name          = $info->name;
        $res->slug          = $info->slug;
        $res->version       = $info->new_version;
        $res->requires      = $info->requires;
        $res->tested        = $info->tested;
        $res->added         = $info->added;
        $res->last_updated  = $info->last_updated;
        $res->download_link = $info->package;

        return $res;
    }
}



/**
 *  wpuxss_eml_pro_in_plugin_update_message
 *
 *  @since    2.0
 *  @created  13/10/14
 */

add_action( 'in_plugin_update_message-' . wpuxss_get_eml_basename(), 'wpuxss_eml_pro_in_plugin_update_message', 10, 2 );

if ( ! function_exists( 'wpuxss_eml_pro_in_plugin_update_message' ) ) {

    function wpuxss_eml_pro_in_plugin_update_message( $plugin_data, $r ) {

        $transient = get_site_transient( 'update_plugins' );
        $wpuxss_eml_pro_basename = wpuxss_get_eml_basename();


        if ( '' !== $transient->response[$wpuxss_eml_pro_basename]->package ) {
            return;
        }


        echo '<br />' . sprintf(
            __('To unlock updates, please <a href="%s">activate your license</a>. You can get your license key in <a href="%s">Your Account</a>. If you do not have a license, you are welcome to <a href="%s">purchase it</a>.', 'enhanced-media-library'),
            self_admin_url('options-general.php?page=eml-settings#eml-license-key-section'),
            esc_url('https://wpuxsolutions.com/account/'),
            esc_url('https://wpuxsolutions.com/pricing/')
        );
    }
}



/**
 *  wpuxss_eml_pro_remote_info
 *
 *  @since    2.1
 *  @created  28/10/15
 */

if ( ! function_exists( 'wpuxss_eml_pro_remote_info' ) ) {

    function wpuxss_eml_pro_remote_info( $action, $license_key = '' ) {

        global $pagenow;


        $wpuxss_eml_pro_update_options = get_site_option( 'wpuxss_eml_pro_update_options', array() );
        $url = wpuxss_eml_pro_get_url();
        $ssl_verification_off = isset( $wpuxss_eml_pro_update_options['ssl_verification_off'] ) && (bool) $wpuxss_eml_pro_update_options['ssl_verification_off'] ? 1 : 0;
        $force_check = ! empty( $_GET['force-check'] ) ? 1 : 0;
        $eml_transient = get_site_transient( 'eml_transient' );
        $last_checked = isset( $eml_transient->last_checked ) ? $eml_transient->last_checked : '';

        $args = array(
            'timeout' => 15,
            'body' => array(
                'action' => $action,
                'key' => $license_key,
                'version' => EML_VERSION,
                'pagenow' => $pagenow,
                'last_checked' => $last_checked,
                'force_check' => $force_check,
                'sslverify' => ! $ssl_verification_off
            )
        );

        $request = wp_remote_post( $url, $args );


        if ( ! is_wp_error( $request ) )
            $response = maybe_unserialize( wp_remote_retrieve_body( $request ) );
        else
            $response = $request;

        return $response;
    }
}



/**
 *  wpuxss_eml_pro_get_url
 *
 *  @since    2.1
 *  @created  28/10/15
 */

if ( ! function_exists( 'wpuxss_eml_pro_get_url' ) ) {

    function wpuxss_eml_pro_get_url() {

        return 'https://wpuxsolutions.com/downloads/plugins/enhanced-media-library-pro/';
    }
}

?>
