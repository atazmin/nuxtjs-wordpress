<?php

if ( ! defined( 'ABSPATH' ) )
	exit;



/**
 *  wpuxss_eml_pro_on_admin_init
 *
 *  @since    2.0
 *  @created  01/10/14
 */

add_action( 'admin_init', 'wpuxss_eml_pro_on_admin_init' );

if ( ! function_exists( 'wpuxss_eml_pro_on_admin_init' ) ) {

    function wpuxss_eml_pro_on_admin_init() {

        // plugin settings: updates
        register_setting(
            'eml-update-settings',
            'wpuxss_eml_pro_update_options',
            'wpuxss_eml_pro_update_options_validate'
        );

        // plugin settings: license
        register_setting(
            'eml-license-settings',
            'wpuxss_eml_pro_license_key',
            // called explicitly for multisite
            // in wpuxss_eml_update_network_update_settings
            'wpuxss_eml_pro_license_key_validate'
        );
    }
}



/**
 *  wpuxss_eml_pro_update_options_validate
 *
 *  @type     callback function
 *  @since    2.5
 *  @created  12/01/18
 */

if ( ! function_exists( 'wpuxss_eml_pro_update_options_validate' ) ) {

    function wpuxss_eml_pro_update_options_validate( $input ) {

        foreach ( (array)$input as $key => $option ) {
            $input[$key] = isset( $option ) && !! $option ? 1 : 0;
        }

        return $input;
    }
}



/**
 *  wpuxss_eml_pro_license_key_validate
 *
 *  @type     callback function
 *  @since    2.0
 *  @created  13/10/14
 */

if ( ! function_exists( 'wpuxss_eml_pro_license_key_validate' ) ) {

    function wpuxss_eml_pro_license_key_validate( $license_key ) {


        // deactivation
        if ( isset( $_POST['eml-license-deactivate'] ) ) {

            $license_key = '';
            $success_message = is_multisite() ? __('Your license has been network deactivated.', 'enhanced-media-library') : __('Your license has been deactivated.', 'enhanced-media-library');

            add_settings_error(
                'eml-settings',
                'eml_license_deactivated',
                $success_message,
                'updated'
            );

            wpuxss_eml_pro_look_for_update( $license_key );

            return $license_key;
        }


        // activation
        if ( isset( $_POST['eml-license-activate'] ) ) {

            if ( '' === $license_key ) {

                add_settings_error(
                    'eml-settings',
                    'eml_empty_license',
                    __( 'Please check if your license key is correct and try again.', 'enhanced-media-library' ),
                    'error'
                );

                return $license_key;
            }

            $license_key = sanitize_text_field( $license_key );

            wpuxss_eml_pro_look_for_update( $license_key );

            $eml_transient = get_site_transient( 'eml_transient' );

            if ( isset( $eml_transient->update_error ) ) {

                $license_key = '';

                add_settings_error(
                    'eml-settings',
                    'eml_wrong_license',
                    sprintf(
                        __('Activation failed with the error: %s. Please <a href="%s">contact plugin authors</a>.', 'enhanced-media-library'),
                        esc_html($eml_transient->update_error),
                        esc_url('https://wpuxsolutions.com/support/')
                    ),
                    'error'
                );

                return $license_key;
            }

            if ( ! (bool) $eml_transient->license_was_active ) {

                $license_key = '';

                add_settings_error(
                    'eml-settings',
                    'eml_wrong_license',
                    sprintf(
                        __('Your license key is incorrect or canceled. Please <a href="%s">contact plugin authors</a>.', 'enhanced-media-library'),
                        esc_url('https://wpuxsolutions.com/support/')
                    ),
                    'error'
                );

                return $license_key;
            }

            $success_message = is_multisite() ? __('Your license has been network activated.', 'enhanced-media-library') : __('Your license has been activated.', 'enhanced-media-library');

            add_settings_error(
                'eml-settings',
                'eml_license_activated',
                $success_message,
                'updated'
            );
        }

        return $license_key;
    }
}



/**
 *  wpuxss_eml_pro_on_update_options_update
 *
 *  updates transient on wpuxss_eml_pro_update_options change
 *
 *  @since    2.5
 *  @created  28/01/18
 */

add_action( 'update_option_wpuxss_eml_pro_update_options', 'wpuxss_eml_pro_on_update_options_update', 10, 2 );

if ( ! function_exists( 'wpuxss_eml_pro_on_update_options_update' ) ) {

    function wpuxss_eml_pro_on_update_options_update( $old_value, $update_options ) {

        if ( $old_value === $update_options ) {
            return;
        }

        $license_key = get_site_option( 'wpuxss_eml_pro_license_key', '' );
        wpuxss_eml_pro_look_for_update( $license_key );
    }
}



/**
 *  wpuxss_eml_pro_extend_settings_page
 *
 *  adds Update options to Settings > EML page
 *
 *  @since    2.1
 *  @created  25/10/15
 */

add_action( 'wpuxss_eml_extend_settings_page', 'wpuxss_eml_pro_extend_settings_page' );

if ( ! function_exists( 'wpuxss_eml_pro_extend_settings_page' ) ) {

    function wpuxss_eml_pro_extend_settings_page() {

        $wpuxss_eml_pro_update_options = get_site_option( 'wpuxss_eml_pro_update_options', array() );
        $license_key = get_site_option( 'wpuxss_eml_pro_license_key', '' );

        $eml_transient = get_site_transient( 'eml_transient' );
        $plugin_name   = __( 'Enhanced Media Library PRO', 'enhanced-media-library' ); ?>


        <div class="postbox">

            <h3 class="hndle" id="eml-license-key-section"><?php _e('Updates','enhanced-media-library'); ?></h3>


            <div class="inside">

                <?php if ( isset( $eml_transient->update_error ) ) :

                    $error_text = ( '' !== $license_key ) ? __( 'to check if your license key is correct', 'enhanced-media-library' ) : __( 'to check if an update is available', 'enhanced-media-library' ); ?>

                    <div class="notice inline notice-warning notice-alt"><p>
                        <?php printf(
                            __( '%s could not establish a secure connection %s. An error occurred: %s. Please <a href="%s">contact plugin authors</a>.', 'enhanced-media-library' ),
                            esc_html($plugin_name),
                            esc_html($error_text),
                            sprintf(
                                '<strong>%s</strong>',
                                esc_html($eml_transient->update_error)
                            ),
                            esc_url('https://wpuxsolutions.com/support/')
                        ); ?>
                    </p></div>

                <?php else :

                    if ( is_network_admin() ) : ?>
                        <form method="post" id="wpuxss-eml-form-updates">
                    <?php else : ?>
                        <form method="post" action="options.php" id="wpuxss-eml-form-updates">
                    <?php endif; ?>

                        <?php settings_fields( 'eml-license-settings' ); ?>

                        <?php if ( (bool) $eml_transient->license_was_active ) : ?>

                            <h4><?php _e('Your license is active!','enhanced-media-library'); ?></h4>

                            <input name="wpuxss_eml_pro_license_key" type="hidden" id="wpuxss_eml_pro_license_key" value="" />

                            <?php submit_button( __('Deactivate License','enhanced-media-library'), 'primary', 'eml-license-deactivate', true, array( 'id' => 'eml-submit-license-deactivate' ) ); ?>

                        <?php else : ?>

                            <p><?php printf(
                                __('To unlock updates please enter your license key below. You can get your license key in <a href="%s">Your Account</a>. If you do not have a license, you are welcome to <a href="%s">purchase it</a>.', 'enhanced-media-library'),
                                esc_url('https://wpuxsolutions.com/account/'),
                                esc_url('https://wpuxsolutions.com/pricing/')
                            ); ?></p>

                            <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="wpuxss_eml_pro_license_key"><?php _e('License Key','enhanced-media-library'); ?></label></th>
                                    <td>
                                        <input name="wpuxss_eml_pro_license_key" type="text" id="wpuxss_eml_pro_license_key" value="" />
                                        <?php submit_button( __( 'Activate License', 'enhanced-media-library' ), 'primary', 'eml-license-activate', true, array( 'id' => 'eml-submit-license-activate' ) ); ?>
                                    </td>
                                </tr>
                            </table>

                        <?php endif; ?>

                    </form>

                <?php endif; ?>

                <?php if ( isset( $eml_transient->update_error ) || isset( $wpuxss_eml_pro_update_options['ssl_verification_off'] ) && (bool) $wpuxss_eml_pro_update_options['ssl_verification_off'] ) :

                    if ( is_network_admin() ) : ?>
                        <form method="post">
                    <?php else : ?>
                        <form method="post" action="options.php">
                    <?php endif; ?>

                        <?php settings_fields( 'eml-update-settings' ); ?>

                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Turn off SSL verification','enhanced-media-library'); ?></th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span><?php _e('Turn off SSL verification','enhanced-media-library'); ?></span></legend>
                                        <label><input name="wpuxss_eml_pro_update_options[ssl_verification_off]" type="hidden" value="0" /><input name="wpuxss_eml_pro_update_options[ssl_verification_off]" type="checkbox" value="1" <?php checked( true, (bool) $wpuxss_eml_pro_update_options['ssl_verification_off'], true ); ?> /> <?php _e('Try this if you see the error message above.', 'enhanced-media-library'); ?></label>
                                        <p class="description"><?php printf(
                                            __( 'This will turn off SSL verification for the update requests that %s sends to its server %s only.', 'enhanced-media-library'),
                                            esc_html($plugin_name),
                                            esc_url('https://wpuxsolutions.com')
                                        ); ?></p>
                                    </fieldset>

                                    <?php submit_button( __( 'Save Changes' ), 'primary', 'submit', true, array( 'id' => 'eml-submit-update-settings' ) ); ?>
                                </td>
                            </tr>
                        </table>

                    </form>

                <?php endif; ?>

            </div>
        </div>

    <?php
    }
}



/**
 *  wpuxss_eml_update_network_update_settings
 *
 *  Ensures update of the update settings for multisite
 *
 *  @since    2.6
 *  @created  02/05/18
 */

add_action( 'network_admin_menu', 'wpuxss_eml_update_network_update_settings' );

if ( ! function_exists( 'wpuxss_eml_update_network_update_settings' ) ) {

    function wpuxss_eml_update_network_update_settings() {

        if ( ! isset( $_POST['eml-license-activate'] ) && ! isset( $_POST['eml-license-deactivate'] ) )
            return;

        check_admin_referer( 'eml-license-settings-options' );

        if ( ! current_user_can( 'manage_network_options' ) )
            return;


        $license_key = isset( $_POST['wpuxss_eml_pro_license_key'] ) ? $_POST['wpuxss_eml_pro_license_key'] : '';
        $license_key = wpuxss_eml_pro_license_key_validate( $license_key );

        update_site_option( 'wpuxss_eml_pro_license_key', $license_key );
    }
}



/**
 *  wpuxss_eml_pro_add_options
 *
 *  @since    2.5
 *  @created  02/02/18
 */

add_filter( 'wpuxss_eml_pro_add_options', 'wpuxss_eml_pro_add_options' );

if ( ! function_exists( 'wpuxss_eml_pro_add_options' ) ) {

    function wpuxss_eml_pro_add_options( $options ) {

        $pro_options = array(
            'wpuxss_eml_pro_bulkedit_savebutton_off',
            'wpuxss_eml_pro_update_options',
            'wpuxss_eml_pro_license_key'
        );

        $options = array_merge( $options, $pro_options );

        return $options;
    }
}



/**
 *  wpuxss_eml_pro_add_transients
 *
 *  @since    2.5
 *  @created  02/02/18
 */

add_filter( 'wpuxss_eml_pro_add_transients', 'wpuxss_eml_pro_add_transients' );

if ( ! function_exists( 'wpuxss_eml_pro_add_transients' ) ) {

    function wpuxss_eml_pro_add_transients( $transients ) {

        $pro_transients = array(
            'eml_transient'
        );

        $transients = array_merge( $transients, $pro_transients );

        return $transients;
    }
}

?>
