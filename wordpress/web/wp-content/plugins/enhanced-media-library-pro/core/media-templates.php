<?php

if ( ! defined( 'ABSPATH' ) )
    exit;



/**
 *  wpuxss_eml_print_media_templates
 *
 *  @since    2.4
 *  @created  07/01/17
 */

add_action( 'print_media_templates', 'wpuxss_eml_print_media_templates' );

if ( ! function_exists( 'wpuxss_eml_print_media_templates' ) ) {

    function wpuxss_eml_print_media_templates() {

        $remove_button = '<button type="button" class="button-link attachment-close media-modal-icon"><span class="screen-reader-text">' . __( 'Remove' ) . '</span></button>';

        $deselect_button = '<button type="button" class="button-link check" tabindex="-1"><span class="media-modal-icon"></span><span class="screen-reader-text">' . __( 'Deselect' ) . '</span></button>'; ?>


        <script type="text/html" id="tmpl-attachment-grid-view">

            <div class="attachment-preview js--select-attachment type-{{ data.type }} subtype-{{ data.subtype }} {{ data.orientation }}">
                <div class="eml-attacment-inline-toolbar">
                    <# if ( data.can.save && data.buttons.edit ) { #>
                        <i class="eml-icon dashicons dashicons-edit edit" data-name="edit"></i>
                    <# } #>
                </div>

                <# show_caption = parseInt(wpuxss_eml_media_grid_l10n.grid_show_caption);
                caption_type = wpuxss_eml_media_grid_l10n.grid_caption_type;
                caption = data[caption_type].length <= 15 ? data[caption_type] : data[caption_type].substring(0, 15) + '...';
                non_image_caption = ( 'image' !== data.type && caption ) ? caption : data.filename;
                title = show_caption ? data[caption_type] : ''; #>
                <div class="thumbnail" title="{{ title }}">
                    <# if ( data.uploading ) { #>
                        <div class="media-progress-bar"><div style="width: {{ data.percent }}%"></div></div>
                    <# } else if ( 'image' === data.type && data.sizes ) { #>
                        <div class="centered">
                            <img src="{{ data.size.url }}" draggable="false" alt="" />
                        </div>
                        <# if ( show_caption && caption ) { #>
                                <div class="filename">
                                    <div>{{ caption }}</div>
                                </div>
                        <# } #>
                    <# } else { #>
                        <div class="centered">
                            <# if ( data.image && data.image.src && data.image.src !== data.icon ) { #>
                                <img src="{{ data.image.src }}" class="thumbnail" draggable="false" alt="" />
                            <# } else if ( data.sizes && data.sizes.medium ) { #>
                                <img src="{{ data.sizes.medium.url }}" class="thumbnail" draggable="false" alt="" />
                            <# } else { #>
                                <img src="{{ data.icon }}" class="icon" draggable="false" alt="" />
                            <# } #>
                        </div>
                        <div class="filename">
                            <div>{{ non_image_caption }}</div>
                        </div>
                    <# } #>
                </div>
                <# if ( data.buttons.close ) { #>
                    <?php echo $remove_button; ?>
                <# } #>
            </div>
            <# if ( data.buttons.check ) { #>
                <?php echo $deselect_button; ?>
            <# } #>
            <#
            var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly';
            if ( data.describe ) {
                if ( 'image' === data.type ) { #>
                    <input type="text" value="{{ data.caption }}" class="describe" data-setting="caption"
                        placeholder="<?php esc_attr_e('Caption this image&hellip;'); ?>" {{ maybeReadOnly }} />
                <# } else { #>
                    <input type="text" value="{{ data.title }}" class="describe" data-setting="title"
                        <# if ( 'video' === data.type ) { #>
                            placeholder="<?php esc_attr_e('Describe this video&hellip;'); ?>"
                        <# } else if ( 'audio' === data.type ) { #>
                            placeholder="<?php esc_attr_e('Describe this audio file&hellip;'); ?>"
                        <# } else { #>
                            placeholder="<?php esc_attr_e('Describe this media file&hellip;'); ?>"
                        <# } #> {{ maybeReadOnly }} />
                <# }
            } #>
        </script>

        <script type="text/html" id="tmpl-eml-media-selection">

            <div class="selection-info">
                <span class="count"></span>
            </div>
            <div class="selection-view"></div>

        </script>

    <?php }
}

?>