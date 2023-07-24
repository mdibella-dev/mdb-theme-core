<?php
/**
 * Custom taxonomy to group publikation-posts.
 *
 * @author  Marco Di Bella
 * @package mdb-theme-core
 */


namespace mdb_theme_core\taxonomies\publication_group;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Register the custom taxonomy.
 *
 * @since 1.0.0
 */

function register()
{
    $labels = [
        'name'                  => __( 'Publication groups', 'mdb-theme-core' ),
        'singular_name'         => __( 'Publication group', 'mdb-theme-core' ),
        'menu_name'             => __( 'Publication groups', 'mdb-theme-core' ),
        'all_items'             => __( 'All publication groups', 'mdb-theme-core' ),
        'edit_item'             => __( 'Edit publication group', 'mdb-theme-core' ),
        'view_item'             => __( 'View publication group', 'mdb-theme-core' ),
        'add_new_item'          => __( 'New publication group', 'mdb-theme-core' ),
        'search_items'          => __( 'Search publication groups', 'mdb-theme-core' ),
        'choose_from_most_used' => __( 'Popular publication groups', 'mdb-theme-core' ),
        'not_found'             => __( 'No publication group found', 'mdb-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Publication group', 'mdb-theme-core' ),
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
add_action( 'init', __NAMESPACE__ . '\register' );
