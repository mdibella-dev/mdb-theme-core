<?php
/**
 * Custom taxonomy to support keywords in custom post type publikation.
 *
 * @author   Marco Di Bella <mdb@marcodibella.de>
 * @package  mdb-theme-core
 */


defined( 'ABSPATH' ) or exit;



/**
 * Register the custom taxonomy.
 *
 * @since  1.0.0
 */

function mdb_tc__keyword__register()
{
    $labels = [
        'name'          => __( 'Keywords', 'mdb_tc' ),
        'singular_name' => __( 'Keyword', 'mdb_tc' ),
    ];


    $args = [
        'label'                 => __( 'Keywords', 'mdb_tc' ),
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'hierarchical'          => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'query_var'             => true,
        'rewrite'               => [
            'slug'          => 'publication_keyword',
            'with_front'    => true,
        ],
        'show_admin_column'     => false,
        'show_in_rest'          => true,
        'show_tagcloud'         => true,
        'rest_base'             => 'publication_keyword',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_namespace'        => 'wp/v2',
        'show_in_quick_edit'    => false,
        'sort'                  => false,
        'show_in_graphql'       => false,
    ];
    register_taxonomy( 'publication_keyword', [ 'publication' ], $args );
}
add_action( 'init', 'mdb_tc__keyword__register' );
