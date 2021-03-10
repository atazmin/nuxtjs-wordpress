<?php

if ( ! defined( 'ABSPATH' ) )
	exit;



/**
 *  wpuxss_eml_pro_print_media_settings_templates
 *
 *  @since    2.1.6
 *  @created  19/01/16
 */

add_action( 'print_media_templates', 'wpuxss_eml_pro_print_media_settings_templates' );

if ( ! function_exists( 'wpuxss_eml_pro_print_media_settings_templates' ) ) {

    function wpuxss_eml_pro_print_media_settings_templates() { ?>

        <script type="text/html" id="tmpl-eml-pro-gallery-order">

            <span class="setting eml-orderby">
                <label for="gallery-settings-orderby" class="name"><?php _e( 'Order By', 'enhanced-media-library' ); ?></label>
                    <select id="gallery-settings-orderby" class="orderby" name="orderby"
                        data-setting="orderby">
                        <option value="date" <# if ( 'date' == wp.media.gallery.defaults.orderby ) { #>selected="selected"<# } #>>
                            <?php _e( 'Date', 'enhanced-media-library' ); ?>
                        </option>
                        <option value="title" <# if ( 'title' == wp.media.gallery.defaults.orderby ) { #>selected="selected"<# } #>>
                            <?php _e( 'Title', 'enhanced-media-library' ); ?>
                        </option>
                        <option value="menuOrder" <# if ( 'menuOrder' == wp.media.gallery.defaults.orderby ) { #>selected="selected"<# } #>>
                            <?php _e( 'Custom Order', 'enhanced-media-library' ); ?>
                        </option>
                        <option value="rand" <# if ( 'rand' == wp.media.gallery.defaults.orderby ) { #>selected="selected"<# } #>>
                            <?php _e( 'Random', 'enhanced-media-library' ); ?>
                        </option>
                    </select>
                </label>
            </span>

            <span class="setting eml-order">
                <label for="gallery-settings-order" class="name"><?php _e( 'Order', 'enhanced-media-library' ); ?></label>
                    <select id="gallery-settings-order" class="order" name="order"
                        data-setting="order">
                        <option value="ASC" <# if ( 'ASC' == wp.media.gallery.defaults.order ) { #>selected="selected"<# } #>>
                            <?php _e( 'Ascending', 'enhanced-media-library' ); ?>
                        </option>
                        <option value="DESC" <# if ( 'DESC' == wp.media.gallery.defaults.order ) { #>selected="selected"<# } #>>
                            <?php _e( 'Descending', 'enhanced-media-library' ); ?>
                        </option>
                    </select>
                </label>
            </span>

        </script>

        <script type="text/html" id="tmpl-eml-pro-gallery-additional-params">

            <span class="setting eml-limit">
                <label for="gallery-settings-limit" class="name"><?php _e( 'Limit', 'enhanced-media-library' ); ?></label>
                    <input id="gallery-settings-limit" class="limit" data-setting="limit" type="text" value="{{wp.media.gallery.defaults.limit}}" />
                </label>
            </span>

        </script>


        <?php // TODO: change as everything else ?>
        <script type="text/html" id="tmpl-eml-pro-playlist-additional-params">

            <span class="setting eml-orderby">
                <label for="gallery-settings-orderby" class="name"><?php _e( 'Order By', 'enhanced-media-library' ); ?></label>
                    <select id="gallery-settings-orderby" class="orderby" name="orderby"
                        data-setting="orderby">
                        <option value="date" <# if ( 'date' == wp.media.playlist.defaults.orderby ) { #>selected="selected"<# } #>>
                            <?php _e( 'Date', 'enhanced-media-library' ); ?>
                        </option>
                        <option value="title" <# if ( 'title' == wp.media.playlist.defaults.orderby ) { #>selected="selected"<# } #>>
                            <?php _e( 'Title', 'enhanced-media-library' ); ?>
                        </option>
                        <option value="menuOrder" <# if ( 'menuOrder' == wp.media.playlist.defaults.orderby ) { #>selected="selected"<# } #>>
                            <?php _e( 'Custom Order', 'enhanced-media-library' ); ?>
                        </option>
                        <option value="rand" <# if ( 'rand' == wp.media.playlist.defaults.orderby ) { #>selected="selected"<# } #>>
                            <?php _e( 'Random', 'enhanced-media-library' ); ?>
                        </option>
                    </select>
                </label>
            </span>

            <span class="setting eml-order">
                <label for="gallery-settings-order" class="name"><?php _e( 'Order', 'enhanced-media-library' ); ?></label>
                    <select id="gallery-settings-order" class="order" name="order"
                        data-setting="order">
                        <option value="ASC" <# if ( 'ASC' == wp.media.gallery.defaults.order ) { #>selected="selected"<# } #>>
                            <?php _e( 'Ascending', 'enhanced-media-library' ); ?>
                        </option>
                        <option value="DESC" <# if ( 'DESC' == wp.media.gallery.defaults.order ) { #>selected="selected"<# } #>>
                            <?php _e( 'Descending', 'enhanced-media-library' ); ?>
                        </option>
                    </select>
                </label>
            </span>

            <span class="setting eml-limit">
                <label for="gallery-settings-limit" class="name"><?php _e( 'Limit', 'enhanced-media-library' ); ?></label>
                    <input id="gallery-settings-limit" class="limit" data-setting="limit" type="text" value="{{wp.media.gallery.defaults.limit}}" />
                </label>
            </span>

        </script>
    <?php }
}

?>
