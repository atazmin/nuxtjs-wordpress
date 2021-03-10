<?php

if ( ! defined( 'ABSPATH' ) )
	exit;



/**
 *  wpuxss_eml_pro_prepare_attachment_for_js
 *
 *  @since    2.0
 *  @created  30/07/14
 */

add_filter( 'wp_prepare_attachment_for_js', 'wpuxss_eml_pro_prepare_attachment_for_js', 10, 3 );

if ( ! function_exists( 'wpuxss_eml_pro_prepare_attachment_for_js' ) ) {

    function wpuxss_eml_pro_prepare_attachment_for_js( $response, $attachment, $meta ) {

        foreach ( get_object_taxonomies ( 'attachment', 'names' ) as $taxonomy ) {

            $term_ids = wp_get_object_terms( $attachment->ID, $taxonomy, array( 'fields' => 'ids' ) );
            $response['taxonomies'][$taxonomy] = $term_ids;
        }

        return $response;
    }
}



/**
 *  wpuxss_eml_add_attachment
 *
 *  @since    2.2
 *  @created  11/02/16
 */

add_action( 'add_attachment', 'wpuxss_eml_add_attachment' );

if ( ! function_exists( 'wpuxss_eml_add_attachment' ) ) {

    function wpuxss_eml_add_attachment( $id ) {

        $attachment = get_post( $id );

        if ( ! $attachment->post_parent )
            return;

        $post = get_post( $attachment->post_parent );
        $wpuxss_eml_taxonomies = get_option('wpuxss_eml_taxonomies');

        foreach ( get_object_taxonomies( $post->post_type, 'names' ) as $taxonomy ) {

            if ( ! isset( $wpuxss_eml_taxonomies[$taxonomy] ) ||
                 $wpuxss_eml_taxonomies[$taxonomy]['eml_media'] ||
                 ! $wpuxss_eml_taxonomies[$taxonomy]['assigned'] ||
                 ! $wpuxss_eml_taxonomies[$taxonomy]['taxonomy_auto_assign'] ) {
                continue;
            }

            $term_ids = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );

            if ( is_wp_error( $term_ids ) || empty( $term_ids ) )
                continue;

            wp_set_object_terms( $id, $term_ids, $taxonomy, false );
        }
    }
}



/**
 *  wpuxss_eml_synchronize_terms
 *
 *  @since    2.2
 *  @created  21/02/16
 */

add_action( 'wp_ajax_eml-synchronize-terms', 'wpuxss_eml_synchronize_terms' );

if ( ! function_exists( 'wpuxss_eml_synchronize_terms' ) ) {

    function wpuxss_eml_synchronize_terms() {

        if ( ! isset( $_REQUEST['post_type'] ) )
            wp_send_json_error();

        if ( ! isset( $_REQUEST['taxonomy'] ) )
            wp_send_json_error();

        check_ajax_referer( 'eml-bulk-edit-nonce', 'nonce' );

        $args = array(
            'posts_per_page' => -1,
            'post_type'      => $_REQUEST['post_type'],
            'post_status'    => 'publish',
        );


        foreach( get_posts( $args ) as $post ) {

            $attachments = get_attached_media( '', $post->ID );

            if ( empty( $attachments ) )
                continue;

            $term_ids = wp_get_object_terms( $post->ID, $_REQUEST['taxonomy'], array( 'fields' => 'ids' ) );

            if ( is_wp_error( $term_ids ) || empty( $term_ids ) )
                continue;

            foreach( $attachments as $attachment ) {

                wp_set_object_terms( $attachment->ID, $term_ids, $_REQUEST['taxonomy'], false );
            }
        }

        wp_send_json_success();
    }
}



/**
 *  wpuxss_eml_posts_join_request
 *
 *  @since    2.6
 *  @created  08/03/18
 */

add_filter( 'posts_join', 'wpuxss_eml_posts_join', 10, 2 );

if ( ! function_exists( 'wpuxss_eml_posts_join' ) ) {

    function wpuxss_eml_posts_join( $join, $wp_query ) {

        global $wpdb;

        if ( ! $wp_query->is_admin ||
             ! $wp_query->get('s') ||
             'attachment' !== $wp_query->get('post_type') ) {

            return $join;
        }


        $wpuxss_eml_lib_options = get_option( 'wpuxss_eml_lib_options', array() );


        if ( in_array( 'authors', $wpuxss_eml_lib_options['search_in'] ) ) {
            $join .= "
                LEFT JOIN
                    {$wpdb->users} users ON ( {$wpdb->posts}.post_author = users.ID )
                ";
        }

        if ( in_array( 'taxonomies', $wpuxss_eml_lib_options['search_in'] ) ) {
            $join .= "
                LEFT JOIN
                    {$wpdb->term_relationships} tr ON ( {$wpdb->posts}.ID = tr.object_id )
                LEFT JOIN
                    {$wpdb->term_taxonomy} tt ON ( tr.term_taxonomy_id = tt.term_taxonomy_id )
                LEFT JOIN
                    {$wpdb->terms} t ON ( tt.term_id = t.term_id )
                ";
        }

        return $join;
    }
}



/**
 *  wpuxss_eml_posts_distinct_request
 *
 *  @since    2.6
 *  @created  08/03/18
 */

add_filter( 'posts_distinct', 'wpuxss_eml_posts_distinct' );

if ( ! function_exists( 'wpuxss_eml_posts_distinct' ) ) {

    function wpuxss_eml_posts_distinct( $distinct ) {

        $wpuxss_eml_lib_options = get_option( 'wpuxss_eml_lib_options', array() );

        if ( ! empty( $wpuxss_eml_lib_options['search_in'] ) ) {
            $distinct = 'DISTINCT';
        }

        return $distinct;
    }
}



/**
 *  wpuxss_eml_posts_search
 *
 *  @since    2.6
 *  @created  08/03/18
 */

add_filter( 'posts_search', 'wpuxss_eml_posts_search', 10, 2 );

if ( ! function_exists( 'wpuxss_eml_posts_search' ) ) {

    function wpuxss_eml_posts_search( $search, $wp_query ) {

        global $wpdb;


        if ( ! $wp_query->is_admin ||
             ! $wp_query->get('s') ||
             'attachment' !== $wp_query->get('post_type') ) {

            return $search;
        }


        $s = '%' . $wpdb->esc_like( $wp_query->get('s') ) . '%';
        $wpuxss_eml_lib_options = get_option( 'wpuxss_eml_lib_options', array() );

        $search = "AND ( 1=0 ";

        if ( in_array( 'titles', $wpuxss_eml_lib_options['search_in'] ) ) {
            $search .= $wpdb->prepare( " OR ({$wpdb->posts}.post_title LIKE '%s')", $s );
        }

        if ( in_array( 'captions', $wpuxss_eml_lib_options['search_in'] ) ) {
            $search .= $wpdb->prepare( " OR ({$wpdb->posts}.post_excerpt LIKE '%s')", $s );
        }

        if ( in_array( 'descriptions', $wpuxss_eml_lib_options['search_in'] ) ) {
            $search .= $wpdb->prepare( " OR ({$wpdb->posts}.post_content LIKE '%s')", $s );
        }
        // because of _filter_query_attachment_filenames in wp-includes\post.php
        else {
            $search .= $wpdb->prepare( " OR (sq1.meta_value LIKE '%s')", $s );
        }

        if ( in_array( 'authors', $wpuxss_eml_lib_options['search_in'] ) ) {
            $search .= $wpdb->prepare( " OR (users.display_name LIKE '%s')", $s );
        }

        if ( in_array( 'taxonomies', $wpuxss_eml_lib_options['search_in'] ) ) {

            $processed_taxonomies = get_object_taxonomies( 'attachment', 'names' );

            foreach( $processed_taxonomies as $taxonomy ) {
                $search .= $wpdb->prepare( " OR
                    (
                        tt.taxonomy = '%s'
                    AND
                        t.name LIKE '%s'
                    )", $taxonomy, $s );
            }
        }

        $search .= " )";

        return $search;
    }
}

?>
