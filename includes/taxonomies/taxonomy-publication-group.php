<?php
/**
 * Custom taxonomy to group publikation-posts.
 *
 * @author   Marco Di Bella <mdb@marcodibella.de>
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
        'name'                  => __( 'Publikationsgruppe', 'mdb_tc' ),
        'singular_name'         => __( 'Publikationsgruppe', 'mdb_tc' ),
        'menu_name'             => __( 'Publikationsgruppen', 'mdb_tc' ),
        'all_items'             => __( 'Alle Publikationsgruppen', 'mdb_tc' ),
        'edit_item'             => __( 'Bearbeiten', 'mdb_tc' ),
        'view_item'             => __( 'Anzeigen', 'mdb_tc' ),
        'add_new_item'          => __( 'Neue Publikationsgruppe', 'mdb_tc' ),
        'search_items'          => __( 'Publikationsgruppen durchsuchen', 'mdb_tc' ),
        'choose_from_most_used' => __( 'Beliebte Publikationsgruppen', 'mdb_tc' ),
        'not_found'             => __( 'Keine Publikationsgruppe gefunden', 'mdb_tc' ),
    ];

    $args = [
        'label'                 => __( 'Publikationsgruppe', 'mdb_tc' ),
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
