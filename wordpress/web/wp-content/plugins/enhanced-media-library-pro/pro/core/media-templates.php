<?php

if ( ! defined( 'ABSPATH' ) )
	exit;



/**
 *  wpuxss_eml_pro_print_media_templates
 *
 *  @since    2.0
 *  @created  03/08/14
 */

add_action( 'print_media_templates', 'wpuxss_eml_pro_print_media_templates' );

if ( ! function_exists( 'wpuxss_eml_pro_print_media_templates' ) ) {

    function wpuxss_eml_pro_print_media_templates() { ?>


        <script type="text/html" id="tmpl-attachments-details">

            <h3><?php _e( 'Attachments Details', 'enhanced-media-library' ); ?></h3>             

            <form class="compat-item">
                <table class="compat-attachment-fields">

                    <?php $wpuxss_eml_tax_options = get_option('wpuxss_eml_tax_options');

                    foreach( get_taxonomies_for_attachments() as $taxonomy ) :

                        $t = (array) get_taxonomy($taxonomy);
                        if ( ! $t['public'] || ! $t['show_ui'] )
                            continue;
                        if ( empty($t['label']) )
                            $t['label'] = $taxonomy;
                        if ( empty($t['args']) )
                            $t['args'] = array();

                        if ( function_exists( 'wp_terms_checklist' ) &&
                           ( (bool) $wpuxss_eml_tax_options['edit_all_as_hierarchical'] || (bool) $t['hierarchical'] ) ) {

                            ob_start();

                                wp_terms_checklist( 0, array( 'taxonomy' => $taxonomy, 'checked_ontop' => false, 'walker' => new Walker_Media_Taxonomy_Checklist() ) );

                                if ( ob_get_contents() != false )
                                    $html = '<ul class="term-list">' . ob_get_contents() . '</ul>';
                                else
                                    $html = '<ul class="term-list"><li>No ' . esc_html($t['label']) . '</li></ul>';

                            ob_end_clean();

                            $t['input'] = 'html';
                            $t['html'] = $html; ?>

                            <tr class="compat-field-<?php echo esc_attr($taxonomy); ?>">
                                <th scope="row" class="label eml-tax-label">
                                    <label for="attachments-<?php echo esc_attr($taxonomy); ?>"><span class="alignleft"><?php echo esc_html($t['label']); ?></span><br class="clear" /></label>
                                </th>
                                <td class="field eml-tax-field"><?php echo $t['html']; ?></td>
                            </tr>

                        <?php } ?>

                    <?php endforeach; ?>

                </table>

            </form>

        </script>



        <?php
        $select_all_button = '<button type="button" class="button-link select" data-action="select">' . __( 'Select All', 'enhanced-media-library' ) . '</button>';
        $deselect_all_button = '<button type="button" class="button-link deselect" data-action="deselect">' . __( 'Deselect All', 'enhanced-media-library' ) . '</button>';
        $delete_selected_button = '<button type="button" class="button-link delete" data-action="delete">' . __( 'Delete Selected', 'enhanced-media-library' ) . '</button>';
        $trash_selected_button = '<button type="button" class="button-link trash" data-action="trash">' . __( 'Trash Selected', 'enhanced-media-library' ) . '</button>';
        $restore_selected_button = '<button type="button" class="button-link restore" data-action="restore">' . __( 'Untrash Selected', 'enhanced-media-library' ) . '</button>';
        $delete_permanently_button = '<button type="button" class="button-link delete-permanently" data-action="delete-permanently">' . __( 'Delete Selected', 'enhanced-media-library' ) . '</button>';
        ?>

        <script type="text/html" id="tmpl-media-bulk-selection">

            <div class="selection-info">
                <span class="count"></span>
                <?php echo $select_all_button; ?>
                <# if ( data.clearable ) { #>
                    <?php echo $deselect_all_button; ?>
                <# } #>
                <# if ( ! data.uploading ) { #>
                    <?php if ( MEDIA_TRASH ):
                        echo $trash_selected_button;
                        echo $restore_selected_button;
                        echo $delete_permanently_button;
                    else:
                        echo $delete_selected_button;
                    endif; ?>
                <# } #>
            </div>
            <div class="selection-view"></div>

        </script>

    <?php }
}
?>
