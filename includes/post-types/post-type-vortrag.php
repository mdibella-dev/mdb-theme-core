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

function mdb_tc__vortrag__register()
{
    $labels = [
        'name'                  => __( 'Vorträge', 'mdb_tc' ),
        'singular_name'         => __( 'Vortrag', 'mdb_tc' ),
        'menu_name'             => __( 'Vorträge', 'mdb_tc' ),
        'all_items'             => __( 'Alle Vorträge', 'mdb_tc' ),
        'add_new'               => __( 'Hinzufügen', 'mdb_tc' ),
        'add_new_item'          => __( 'Neuen Vortrag hinzufügen', 'mdb_tc' ),
        'edit_item'             => __( 'Vortrag bearbeiten', 'mdb_tc' ),
        'new_item'              => __( 'Neuer Vortrag', 'mdb_tc' ),
        'view_item'             => __( 'Vortrag anzeigen', 'mdb_tc' ),
        'search_items'          => __( 'Vorträge durchsuchen', 'mdb_tc' ),
        'not_found'             => __( 'Keinen Vortrag gefunden', 'mdb_tc' ),
        'not_found_in_trash'    => __( 'Keinen Vortrag im Papierkorb gefunden', 'mdb_tc' ),
    ];

    $args = [
        'label'                 => __( 'Vorträge', 'mdb_tc' ),
        'labels'                => $labels,
        'description'           => '',
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_rest'          => false,
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
        'can_export'            => true,
        'rewrite'               => [
            'slug'          => 'vortrag',
            'with_front'    => true
        ],
        'query_var'             => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-welcome-learn-more',
        'supports'              => ['title'],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'vortrag', $args );
}

add_action( 'init', 'mdb_tc__vortrag__register' );
