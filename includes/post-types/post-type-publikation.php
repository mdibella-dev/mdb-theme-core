<?php
/**
 * Custom post type to manage lectures.
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 * @package mdb-theme-core
 */


defined( 'ABSPATH' ) or exit;



/**
 * Register the custom post type.
 *
 * @since  1.0.0
 */

function mdb_tc__publikation__register()
{
    $labels = [
        'name'                  => __( 'Publikationen', 'mdb_tc' ),
        'singular_name'         => __( 'Publikation', 'mdb_tc' ),
        'menu_name'             => __( 'Publikationen', 'mdb_tc' ),
        'all_items'             => __( 'Alle Publikationen', 'mdb_tc' ),
        'add_new'               => __( 'Hinzufügen', 'mdb_tc' ),
        'add_new_item'          => __( 'Neue Publikation hinzufügen', 'mdb_tc' ),
        'edit_item'             => __( 'Publikation bearbeiten', 'mdb_tc' ),
        'new_item'              => __( 'Neue Publikation', 'mdb_tc' ),
        'view_item'             => __( 'Publikation anzeigen', 'mdb_tc' ),
        'search_items'          => __( 'Publikationen durchsuchen', 'mdb_tc' ),
        'not_found'             => __( 'Keine Publikation gefunden', 'mdb_tc' ),
        'not_found_in_trash'    => __( 'Keine Publikationen im Papierkorb gefunden', 'mdb_tc' ),
        'featured_image'        => __( 'Titelbild', 'mdb_tc' ),
        'set_featured_image'    => __( 'Titelbild festlegen', 'mdb_tc' ),
        'remove_featured_image' => __( 'Titelbild entfernen', 'mdb_tc' ),
        'use_featured_image'    => __( 'Als Titelbild verwenden', 'mdb_tc' ),
        'item_published'        => __( 'Publikation veröffentlicht', 'mdb_tc' ),
        'item_updated'          => __( 'Publikation aktualisiert', 'mdb_tc' ),
    ];

    $args = [
        'label'                 => __( 'Publikationen', 'mdb_tc' ),
        'labels'                => $labels,
        'description'           => 'Alle Formen von Publikationen: Beiträge in Fachzeitschriften, Büchern etc.',
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_rest'          => true,
        'rest_base'             => '',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rest_namespace'        => 'wp/v2',
        'has_archive'           => false,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'delete_with_user'      => false,
        'exclude_from_search'   => false,
        'capability_type'       => 'post',
        'map_meta_cap'          => true,
        'hierarchical'          => false,
        'can_export'            => false,
        'rewrite'               => [
            'slug'          => 'publikation',
            'with_front'    => true
        ],
        'query_var'             => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-media-document',
        'supports'              => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'author' ],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'publikation', $args );
}

add_action( 'init', 'mdb_tc__publikation__register' );
