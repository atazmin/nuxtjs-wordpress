<?php

if ( ! defined( 'ABSPATH' ) )
    exit;



    include_once( 'core/bulk-edit.php' );
    include_once( 'core/taxonomies.php' );
    include_once( 'core/media-templates.php' );
    include_once( 'core/update.php' );

    if ( wpuxss_eml_enhance_media_shortcodes() ) {
        include_once( 'core/medialist.php' );
    }

    if ( is_admin() ) {
        include_once( 'core/options-pages.php' );
    }




/**
 *  wpuxss_eml_pro_admin_enqueue_scripts
 *
 *  @since    2.0
 *  @created  04/09/14
 */

add_action( 'admin_enqueue_scripts', 'wpuxss_eml_pro_admin_enqueue_scripts' );

if ( ! function_exists( 'wpuxss_eml_pro_admin_enqueue_scripts' ) ) {

    function wpuxss_eml_pro_admin_enqueue_scripts() {

        global $wpuxss_eml_dir,
               $current_screen;


        $media_library_mode = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';


        if ( isset( $current_screen ) &&
             ( ( 'upload' === $current_screen->base && 'list' === $media_library_mode ) ||
             ( 'media' === $current_screen->base && 'add' === $current_screen->action ) ) ) {

            wp_enqueue_media();

            wp_enqueue_script(
                'wpuxss-eml-pro-bulk-popup-script',
                $wpuxss_eml_dir . 'pro/js/eml-bulk-popup.js',
                array( 'wpuxss-eml-pro-bulk-edit-script' ),
                EML_VERSION,
                true
            );
        }


        // admin styles
        wp_enqueue_style(
            'wpuxss-eml-pro-admin-custom-style',
            $wpuxss_eml_dir . 'pro/css/eml-pro-admin.css',
            array( 'wpuxss-eml-admin-custom-style' ),
            EML_VERSION,
            'all'
        );
        wp_style_add_data( 'wpuxss-eml-pro-admin-custom-style', 'rtl', 'replace' );

        wp_enqueue_style(
            'wpuxss-eml-pro-admin-media-style',
            $wpuxss_eml_dir . 'pro/css/eml-pro-admin-media.css',
            array( 'wpuxss-eml-admin-media-style' ),
            EML_VERSION,
            'all'
        );
        wp_style_add_data( 'wpuxss-eml-pro-admin-media-style', 'rtl', 'replace' );

    }
}



/**
 *  wpuxss_eml_pro_enqueue_media
 *
 *  @since    2.0
 *  @created 04/09/14
 */

add_action( 'wp_enqueue_media', 'wpuxss_eml_pro_enqueue_media' );

if ( ! function_exists( 'wpuxss_eml_pro_enqueue_media' ) ) {

    function wpuxss_eml_pro_enqueue_media() {

        global $wpuxss_eml_dir;


        if ( ! is_admin() ) {
            return;
        }


        $wpuxss_eml_tax_options = get_option( 'wpuxss_eml_tax_options', array() );


        wp_enqueue_script(
            'wpuxss-eml-pro-bulk-edit-script',
            $wpuxss_eml_dir . 'pro/js/eml-bulk-edit.js',
            array( 'wpuxss-eml-media-models-script', 'wpuxss-eml-media-views-script', 'wpuxss-eml-admin-script' ),
            EML_VERSION,
            true
        );

        $bulk_edit_l10n = array(
            'toolTip_all' => __( 'ALL files belong to this item', 'enhanced-media-library' ),
            'toolTip_some' => __( 'SOME files belong to this item', 'enhanced-media-library' ),
            'toolTip_none' => __( 'NO files belong to this item', 'enhanced-media-library' ),
            'media_new_close' => __( 'Close', 'enhanced-media-library' ),
            'media_new_title' => __( 'Edit Media Files', 'enhanced-media-library' ),
            'media_new_button' => __( 'Bulk Edit', 'enhanced-media-library' ),

            'in_progress_select_text' => __( 'Selecting', 'enhanced-media-library' ),
            'delete_warning_title' => __( 'Delete Selected Permanently', 'enhanced-media-library' ),
            'delete_warning_text' => __( 'You are about to permanently delete all selected items.', 'enhanced-media-library' ),
            'delete_warning_yes' => __( 'Delete', 'enhanced-media-library' ),
            'delete_warning_no' => __( 'Cancel', 'enhanced-media-library' ),
            'in_progress_trash_text' => __( 'Moving to Trash', 'enhanced-media-library' ),
            'in_progress_restore_text' => __( 'Restoring', 'enhanced-media-library' ),
            'in_progress_delete_text' => __( 'Deleting', 'enhanced-media-library' ),

            // 'bulk_edit_nonce' => wp_create_nonce( 'eml-bulk-edit-nonce' ),
            'bulk_edit_save_button_off' => ! (bool) $wpuxss_eml_tax_options['bulk_edit_save_button']
        );

        wp_localize_script(
            'wpuxss-eml-pro-bulk-edit-script',
            'wpuxss_eml_pro_bulk_edit_l10n',
            $bulk_edit_l10n
        );


        if ( wpuxss_eml_enhance_media_shortcodes() ) {

            wp_enqueue_script(
                'wpuxss-eml-pro-enhanced-medialist-script',
                $wpuxss_eml_dir . 'pro/js/eml-enhanced-medialist.js',
                array( 'wpuxss-eml-enhanced-medialist-script' ),
                EML_VERSION,
                true
            );

            $enhanced_medialist_l10n = array(
                'createNewGallery'  => __( 'Create a filter-based gallery', 'enhanced-media-library' ),
                'createNewPlaylist'  => __( 'Create a filter-based playlist', 'enhanced-media-library' ),
                'createNewVideoPlaylist'  => __( 'Create a filter-based video playlist', 'enhanced-media-library' )
            );

            wp_localize_script(
                'wpuxss-eml-pro-enhanced-medialist-script',
                'wpuxss_eml_pro_enhanced_medialist_l10n',
                $enhanced_medialist_l10n
            );
        }
    }
}



/**
 *  wpuxss_eml_pro_set_options
 *
 *  @since    2.7
 *  @created 31/08/18
 */

add_action( 'wpuxss_eml_set_options', 'wpuxss_eml_pro_set_options' );

if ( ! function_exists( 'wpuxss_eml_pro_set_options' ) ) {

    function wpuxss_eml_pro_set_options() {

        delete_option( 'wpuxss_eml_pro_bulkedit_savebutton_off' );

        if ( is_multisite() ) {

            $wpuxss_eml_pro_update_options = get_option( 'wpuxss_eml_pro_update_options' );
            $wpuxss_eml_pro_license_key = get_option( 'wpuxss_eml_pro_license_key' );

            delete_option( 'wpuxss_eml_pro_update_options' );
            delete_option( 'wpuxss_eml_pro_license_key' );

            if ( false === get_site_option( 'wpuxss_eml_pro_update_options' ) ) {
                update_site_option( 'wpuxss_eml_pro_update_options', $wpuxss_eml_pro_update_options );
            }

            if ( false === get_site_option( 'wpuxss_eml_pro_license_key' ) ) {
                update_site_option( 'wpuxss_eml_pro_license_key', $wpuxss_eml_pro_license_key );
            }
        }
    }
}



/**
 *  wpuxss_eml_pro_set_site_options
 *
 *  @since    2.7
 *  @created 31/08/18
 */

add_action( 'wpuxss_eml_set_site_options', 'wpuxss_eml_pro_set_site_options' );

if ( ! function_exists( 'wpuxss_eml_pro_set_site_options' ) ) {

    function wpuxss_eml_pro_set_site_options() {

        $wpuxss_eml_pro_update_options = get_site_option( 'wpuxss_eml_pro_update_options', array() );

        $wpuxss_eml_pro_update_options_defaults = array(
            'ssl_verification_off' => 0
        );

        $wpuxss_eml_pro_update_options = array_intersect_key( $wpuxss_eml_pro_update_options, $wpuxss_eml_pro_update_options_defaults );
        $wpuxss_eml_pro_update_options = array_merge( $wpuxss_eml_pro_update_options_defaults, $wpuxss_eml_pro_update_options );

        update_site_option( 'wpuxss_eml_pro_update_options', $wpuxss_eml_pro_update_options );

        $license_key = get_site_option( 'wpuxss_eml_pro_license_key', '' );
        wpuxss_eml_pro_look_for_update( $license_key );
    }
}



/**
 *  wpuxss_eml_pro_deactivate_extras
 *
 *  @since    2.6
 *  @created 27/03/18
 */

add_action( 'activated_plugin', 'wpuxss_eml_pro_deactivate_extras', 10, 2 );

if ( ! function_exists( 'wpuxss_eml_pro_deactivate_extras' ) ) {

    function wpuxss_eml_pro_deactivate_extras( $plugin, $network_wide ) {

        if ( $network_wide ) {
            $active = count( preg_grep( '/enhanced-media-library/i', array_keys( (array) get_site_option( 'active_sitewide_plugins', array() ) ) ) );
        }
        else {
            $active = count( preg_grep( '/enhanced-media-library/i', (array) get_option( 'active_plugins', array() ) ) );
        }


        if ( $active < 2 )
            return;


        $deactivate = wpuxss_eml_preg_grep_keys( '/enhanced-media-library/i', get_plugins() );
        uasort( $deactivate, 'wpuxss_eml_sort_plugins' );


        foreach ( $deactivate as $basename => $plugin ) {

            if ( 'Enhanced Media Library PRO' === $plugin['Name'] && is_plugin_active($basename) ) {
                unset( $deactivate[$basename] );
                break;
            }
        }

        deactivate_plugins( array_keys( $deactivate ) );

        wp_safe_redirect( self_admin_url( 'plugins.php?eml-notice-warning=true' ) );
        exit;
    }
}



/**
 *  wpuxss_eml_sort_plugins
 *
 *  @since    2.6
 *  @created 27/03/18
 */

if ( ! function_exists( 'wpuxss_eml_sort_plugins' ) ) {

    function wpuxss_eml_sort_plugins( $a, $b ) {

        if ( $a['Name'] == $b['Name'] ) {
            return version_compare( $a['Version'], $b['Version'], '<' );
        }
        return strcmp( $a['Name'], $b['Name'] );
    }
}



/**
 *  wpuxss_eml_preg_grep_keys
 *
 *  @since    2.6
 *  @created 27/03/18
 */

if ( ! function_exists( 'wpuxss_eml_preg_grep_keys' ) ) {

    function wpuxss_eml_preg_grep_keys( $pattern, $input, $flags = 0 ) {
        return array_intersect_key( $input, array_flip( preg_grep( $pattern, array_keys( $input ), $flags ) ) );
    }
}



/**
 *  wpuxss_eml_pro_one_version_active_notice
 *
 *  @since    2.6
 *  @created 27/03/18
 */

add_action( 'admin_notices', 'wpuxss_eml_pro_one_version_active_notice' );
add_action( 'network_admin_notices', 'wpuxss_eml_pro_one_version_active_notice' );

if ( ! function_exists( 'wpuxss_eml_pro_one_version_active_notice' ) ) {

    function wpuxss_eml_pro_one_version_active_notice() {

        if ( isset( $_GET['eml-notice-warning'] ) ) {
            echo '<div class="notice notice-warning eml-admin-notice"><p>' . __( 'Only one version of <strong>Enhanced Media Library</strong> should be active at a time.', 'enhanced-media-library' ) . '</p></div>';
        }
    }
}

?>
