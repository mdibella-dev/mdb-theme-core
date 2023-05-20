<?php
/**
 * Custom post type to manage portfolio items.
 *
 * @author  Marco Di Bella
 * @package mdb-theme-core
 * @uses    ACF
 */


namespace mdb_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Register the custom post type.
 *
 * @since 1.0.0
 */

function portfolio__register()
{
    $labels = [
        'name'                  => __( 'Portfolio items', 'mdb-theme-core' ),
        'singular_name'         => __( 'Portfolio item', 'mdb-theme-core' ),
        'menu_name'             => __( 'Portfolio', 'mdb-theme-core' ),
        'all_items'             => __( 'All items', 'mdb-theme-core' ),
        'add_new'               => __( 'Add', 'mdb-theme-core' ),
        'add_new_item'          => __( 'Add new item', 'mdb-theme-core' ),
        'edit_item'             => __( 'Edit item', 'mdb-theme-core' ),
        'new_item'              => __( 'New item', 'mdb-theme-core' ),
        'view_item'             => __( 'View item', 'mdb-theme-core' ),
        'search_items'          => __( 'Search items', 'mdb-theme-core' ),
        'not_found'             => __( 'No item found', 'mdb-theme-core' ),
        'not_found_in_trash'    => __( 'No item found in trash', 'mdb-theme-core' ),
        'item_published'        => __( 'Item published', 'mdb-theme-core' ),
        'item_updated'          => __( 'Item updated', 'mdb-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Portfolio items', 'mdb-theme-core' ),
        'labels'                => $labels,
        'description'           => 'All forms of portfolio items',
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
            'slug'          => 'portfolio',
            'with_front'    => true
        ],
        'query_var'             => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-media-document',
        'supports'              => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'author' ],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'portfolio', $args );
}

add_action( 'init', 'mdb_theme_core\portfolio__register' );


add_action( 'pre_get_posts', 'mdb_theme_core\publication__pre_get_posts', 1 );
