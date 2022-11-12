<?php
/**
 * Custom taxonomy to group publikation-posts.
 *
 * @author   Marco Di Bella
 * @package  mdb-theme-core
 */


namespace mdb_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Register the custom taxonomy.
 *
 * @since  1.0.0
 */

function publication_group__register()
{
    $labels = [
        'name'                  => __( 'Publication groups', 'mdb_theme_core' ),
        'singular_name'         => __( 'Publication group', 'mdb_theme_core' ),
        'menu_name'             => __( 'Publication groups', 'mdb_theme_core' ),
        'all_items'             => __( 'All publication groups', 'mdb_theme_core' ),
        'edit_item'             => __( 'Edit publication group', 'mdb_theme_core' ),
        'view_item'             => __( 'View publication group', 'mdb_theme_core' ),
        'add_new_item'          => __( 'New publication group', 'mdb_theme_core' ),
        'search_items'          => __( 'Search publication groups', 'mdb_theme_core' ),
        'choose_from_most_used' => __( 'Popular publication groups', 'mdb_theme_core' ),
        'not_found'             => __( 'No publication group found', 'mdb_theme_core' ),
    ];

    $args = [
        'label'                 => __( 'Publication group', 'mdb_theme_core' ),
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'hierarchical'          => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'query_var'             => true,
        'rewrite'               => [
            'slug'          => 'publication_group',
            'with_front'    => true,
        ],
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        'show_tagcloud'         => true,
        'rest_base'             => 'publication_group',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_namespace'        => 'wp/v2',
        'show_in_quick_edit'    => false,
        'sort'                  => false,
        'show_in_graphql'       => false,
    ];

    register_taxonomy( 'publication_group', ['publication'], $args );
}
add_action( 'init', 'mdb_theme_core\publication_group__register' );
