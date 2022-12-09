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
        'name'                  => __( 'Publication groups', PLUGIN_DOMAIN ),
        'singular_name'         => __( 'Publication group', PLUGIN_DOMAIN ),
        'menu_name'             => __( 'Publication groups', PLUGIN_DOMAIN ),
        'all_items'             => __( 'All publication groups', PLUGIN_DOMAIN ),
        'edit_item'             => __( 'Edit publication group', PLUGIN_DOMAIN ),
        'view_item'             => __( 'View publication group', PLUGIN_DOMAIN ),
        'add_new_item'          => __( 'New publication group', PLUGIN_DOMAIN ),
        'search_items'          => __( 'Search publication groups', PLUGIN_DOMAIN ),
        'choose_from_most_used' => __( 'Popular publication groups', PLUGIN_DOMAIN ),
        'not_found'             => __( 'No publication group found', PLUGIN_DOMAIN ),
    ];

    $args = [
        'label'                 => __( 'Publication group', PLUGIN_DOMAIN ),
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
