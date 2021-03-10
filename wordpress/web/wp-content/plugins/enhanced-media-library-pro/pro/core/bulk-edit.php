<?php

if ( ! defined( 'ABSPATH' ) )
	exit;



/**
 *  wpuxss_eml_save_attachments
 *
 *  @since    2.0
 *  @created  09/08/14
 */

add_action( 'wp_ajax_eml-save-attachments', 'wpuxss_eml_save_attachments', 0 );

if ( ! function_exists( 'wpuxss_eml_save_attachments' ) ) {

    function wpuxss_eml_save_attachments() {

        global $wpdb;


        if ( empty( $_REQUEST['attachments'] ) )
            wp_send_json_error();


        check_ajax_referer( 'eml-bulk-edit-nonce', 'nonce' );


        $wpuxss_eml_lib_options = get_option('wpuxss_eml_lib_options');

        $attachments = $_REQUEST['attachments'];
        $new_attachments = array();

        $all_removed = false;
        $all_added = false;
        $rows2remove = array();
        $rows2add = array();

        $new_term_ids = array();

        $new_tt_ids = array();
        $old_tt_ids = array();
        $deleted_tt_ids = array();
        $affected_tt_ids = array();


        $terms = get_terms( get_object_taxonomies( 'attachment','names' ), array('fields'=>'all','get'=>'all') );
        $term_pairs = wpuxss_eml_get_media_term_pairs( $terms, 'tt_id=>id' );


        foreach ( $attachments as $attachment_id => $taxonomies ) {

            $attachment_id = intval( $attachment_id );

            if ( ! current_user_can( 'edit_post', $attachment_id ) ) {
                unset( $attachments[$attachment_id] );
                continue;
            }

            if ( ! $attachment = get_post( $attachment_id ) ) {
                unset( $attachments[$attachment_id] );
                continue;
            }

            if ( 'attachment' != $attachment->post_type ) {
                unset( $attachments[$attachment_id] );
                continue;
            }
        }


        foreach ( $attachments as $attachment_id => $taxonomies ) {

            $attachment_id = intval( $attachment_id );


            foreach( $taxonomies as $taxonomy => $tt_ids ) {

                foreach( $tt_ids as $tt_id => $action ) {

                    if ( 'remove' === $action ) {
                        $rows2remove[] = array( $attachment_id, $tt_id );
                        $deleted_tt_ids[$attachment_id][] = $tt_id;
                    }

                    if ( 'add' === $action ) {

                        $rows2add[] = array( $attachment_id, $tt_id );

                        $old_tt_ids[$attachment_id][$taxonomy] = wp_get_object_terms( $attachment_id, $taxonomy, array('fields' => 'tt_ids', 'orderby' => 'none') );

                        $new_term_ids[$attachment_id][$taxonomy][] = $term_pairs[$tt_id];
                        $new_tt_ids[$attachment_id][$taxonomy][] = $tt_id;

                        do_action( 'add_term_relationship', $attachment_id, $tt_id );
                    }

                    $affected_tt_ids[$tt_id] = $taxonomy;

                } // foreach $tt_ids
            } // foreach $taxonomies
        } // foreach $attachments


        foreach ( $deleted_tt_ids as $attachment_id => $tt_ids ) {
            do_action( 'delete_term_relationships', $attachment_id, $tt_ids );
        }


        if ( ! empty( $rows2remove ) ) {

            $rows2remove_format = join( ', ', array_fill( 0, count( $rows2remove ), '(%d,%d)' ) );
            $rows2remove = call_user_func_array( 'array_merge', $rows2remove );

            $all_removed = $wpdb->query( $wpdb->prepare(
                "
                    DELETE FROM $wpdb->term_relationships
                    WHERE (object_id,term_taxonomy_id) IN ($rows2remove_format)
                ",
                $rows2remove
            ) );
        }


        if ( ! empty( $rows2add ) ) {

            $rows2add_format = join( ', ', array_fill( 0, count( $rows2add ), '(%d,%d)' ) );
            $rows2add = call_user_func_array( 'array_merge', $rows2add );

            $all_added = $wpdb->query( $wpdb->prepare(
                "
                    INSERT INTO $wpdb->term_relationships (object_id,term_taxonomy_id)
                    VALUES $rows2add_format
                ",
                $rows2add
            ) );
        }


        if ( false === $all_removed && false === $all_added )
            wp_send_json_error();


        foreach( $affected_tt_ids as $tt_id => $taxonomy ) {
            wp_update_term_count_now( array( $tt_id ), $taxonomy );
        }

        if ( (bool) $wpuxss_eml_lib_options['show_count'] ) {

            foreach( $term_pairs as $tt_id => $term_id ) {
                $new_attachments['tcount'][$term_id] = wpuxss_eml_get_media_term_count( $term_id, $tt_id );
            }
        }


        foreach ( $attachments as $attachment_id => $taxonomies ) {

            $attachment_id = intval( $attachment_id );


            $new_attachments[$attachment_id]['date'] = strtotime( $attachment->post_date_gmt ) * 1000;
            $new_attachments[$attachment_id]['modified'] = strtotime( $attachment->post_modified_gmt ) * 1000;

            foreach ( $taxonomies as $taxonomy => $tt_ids ) {

                $tt_ids = array_keys( $tt_ids );

                $term_ids = wp_get_object_terms( $attachment_id, $taxonomy, array( 'fields' => 'ids' ) );
                $new_attachments[$attachment_id]['taxonomies'][$taxonomy] = $term_ids;

                foreach( $tt_ids as $tt_id ) {
                    do_action( 'added_term_relationship', $attachment_id, $tt_id );
                }

                if ( false !== $all_added ) {
                    do_action( 'set_object_terms', $attachment_id, $new_term_ids[$attachment_id][$taxonomy], $new_tt_ids[$attachment_id][$taxonomy], $taxonomy, false, $old_tt_ids[$attachment_id][$taxonomy] );
                }

                wp_cache_delete( $attachment_id, $taxonomy . '_relationships' );

            } // foreach $taxonomies
        } // foreach $attachments


        foreach ( $deleted_tt_ids as $attachment_id => $tt_ids ) {
            do_action( 'deleted_term_relationships', $attachment_id, $tt_ids );
        }


        wp_send_json_success( $new_attachments );
    }
}



/**
 *  wpuxss_eml_bulk_attachments
 *
 *  @since    2.3
 *  @created  11/06/16
 */

add_action( 'wp_ajax_eml-bulk-attachments', 'wpuxss_eml_bulk_attachments' );

if ( ! function_exists( 'wpuxss_eml_bulk_attachments' ) ) {

    function wpuxss_eml_bulk_attachments() {

        global $wpdb;


        if ( empty( $_REQUEST['attachments'] ) )
            wp_send_json_error();


        check_ajax_referer( 'eml-bulk-edit-nonce', 'nonce' );


        $wpuxss_eml_lib_options = get_option('wpuxss_eml_lib_options');

        $attachments = $_REQUEST['attachments'];
        $bulk_action = isset( $_REQUEST['bulk_action'] ) ? $_REQUEST['bulk_action'] : '';

        $changed = false;
        $affected_terms = array();
        $affected_attachments = array();


        foreach ( $attachments as $attachment_id ) {

            $attachment_id = intval( $attachment_id );


            if ( ! current_user_can( 'delete_post', $attachment_id ) )
                continue;

            if ( ! $attachment = get_post( $attachment_id ) )
                continue;

            if ( 'attachment' !== $attachment->post_type )
                continue;

            if ( 'trash' === $bulk_action && 'trash' === $attachment->post_status )
                continue;

            if ( 'restore' === $bulk_action && 'trash' !== $attachment->post_status )
                continue;


            $affected_attachments[$attachment_id] = $attachment;


            $results = $wpdb->get_results( $wpdb->prepare(
                "
                    SELECT $wpdb->term_relationships.term_taxonomy_id, $wpdb->term_taxonomy.taxonomy, $wpdb->term_taxonomy.term_id
                    FROM $wpdb->term_relationships
                    INNER JOIN $wpdb->term_taxonomy ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id
                    WHERE object_id = %d
                ",
                $attachment_id
            ) );


            if ( false !== $results )
                $affected_attachments[$attachment_id]->terms = $results;
        }


        $attachment_ids = array_keys( $affected_attachments );
        $attachment_ids_format = join( ', ', array_fill( 0, count( $attachment_ids ), '%d' ) );


        if ( 'trash' === $bulk_action && EMPTY_TRASH_DAYS && MEDIA_TRASH ) {

            foreach ( $affected_attachments as $attachment_id => $attachment ) {

                do_action( 'wp_trash_post', $attachment_id );

                add_post_meta( $attachment_id, '_wp_trash_meta_status', $attachment->post_status );
                add_post_meta( $attachment_id, '_wp_trash_meta_time', time() );
            }

            $changed = $wpdb->query( $wpdb->prepare(
             	"
                    UPDATE $wpdb->posts
                    SET $wpdb->posts.post_status = 'trash'
                    WHERE $wpdb->posts.ID IN ($attachment_ids_format)
                    AND $wpdb->posts.post_status != 'trash'
             	",
                $attachment_ids
            ) );

            if ( false !== $changed ) {

                foreach ( $affected_attachments as $attachment_id => $attachment ) {

                    wp_trash_post_comments( $attachment );
                    do_action( 'trashed_post', $attachment_id );
                }
            }
        }
        elseif ( 'restore' === $bulk_action && MEDIA_TRASH  ) {

            foreach ( $affected_attachments as $attachment_id => $attachment ) {

                do_action( 'untrash_post', $attachment_id );

                $post_status = get_post_meta( $attachment_id, '_wp_trash_meta_status', true );

                delete_post_meta( $attachment_id, '_wp_trash_meta_status' );
                delete_post_meta( $attachment_id, '_wp_trash_meta_time' );
            }

            $changed = $wpdb->query( $wpdb->prepare(
                "
                    UPDATE $wpdb->posts
                    SET $wpdb->posts.post_status = '$post_status'
                    WHERE $wpdb->posts.ID IN ($attachment_ids_format)
                    AND $wpdb->posts.post_status = 'trash'
                ",
                $attachment_ids
            ) );

            if ( false !== $changed ) {

                foreach ( $affected_attachments as $attachment_id => $attachment ) {

                    wp_untrash_post_comments( $attachment );
                    do_action( 'untrashed_post', $attachment_id );
                }
            }
        }
        elseif ( 'delete' === $bulk_action || 'delete-permanently' === $bulk_action ) {

            foreach ( $affected_attachments as $attachment_id => $attachment ) {

                if ( ! wp_delete_attachment( $attachment_id ) ) {
                    unset( $affected_attachments[$attachment_id] );
                }
            }

            if ( ! empty( $affected_attachments ) )
                $changed = true;
        }


         if ( false === $changed )
             wp_send_json_error();


         foreach ( $affected_attachments as $attachment_id => $attachment ) {

             foreach( $attachment->terms as $term ) {

                 $affected_terms[$term->term_id] = array(
                     'tt_id' => $term->term_taxonomy_id,
                     'taxonomy' => $term->taxonomy
                 );
             }
         }


         foreach( $affected_attachments as $attachment_id => $attachment ) {
             $affected_attachments[$attachment_id] = $attachment_id;
         }


         foreach( $affected_terms as $term_id => $term ) {

             if ( (bool) $wpuxss_eml_lib_options['show_count'] )
                $affected_attachments['tcount'][$term_id] = wpuxss_eml_get_media_term_count( $term_id, $term['tt_id'] );

             wp_update_term_count_now( array( $term['tt_id'] ), $term['taxonomy'] );
         }


         wp_send_json_success( $affected_attachments );
     }
 }



/**
 *  wpuxss_eml_add_bulk_actions
 *
 *  @since    2.3.6
 *  @created  4/12/16
 */

add_filter( 'bulk_actions-upload', 'wpuxss_eml_add_bulk_actions' );

if ( ! function_exists( 'wpuxss_eml_add_bulk_actions' ) ) {

    function wpuxss_eml_add_bulk_actions( $bulk_actions ) {

        $bulk_actions['emlbulkedit'] = __( 'Bulk Edit', 'enhanced-media-library' );

        return $bulk_actions;
    }
}

?>
