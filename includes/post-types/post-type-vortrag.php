<?php
/**
 * Custom post type to manage lectures.
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 * @package mdb-theme-core
 * @uses    ACF
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



/**
 * Determines the columns of the lecture list (backend).
 *
 * @since  1.0.0
 * @param  array   $default    The defaults for columns.
 * @return array               An associative array describing the columns to use.
 */

function mdb_tc__vortrag__manage_posts_columns( $default )
{
    $columns['cb']                    = $default['cb'];
    $columns['title']                 = __( 'Titel', 'mdb_tc' );
    $columns['speech-event']          = __( 'Veranstaltung', 'mdb_tc' );
    $columns['speech-event-date']     = __( 'Datum', 'mdb_tc' );
    $columns['speech-event-location'] = __( 'Ort', 'mdb_tc' );

    return $columns;
}

add_filter( 'manage_vortrag_posts_columns', 'mdb_tc__vortrag__manage_posts_columns', 10 );



/**
 * Generates the output of the columns.
 *
 * @since 1.0.0
 * @param string $column_name    Designation of the column to be output.
 * @param int    $post_id        ID of the contribution (aka record) to be output.
 */

function mdb_tc__vortrag__manage_posts_custom_column( $column_name, $post_id )
{
    switch( $column_name ) :

        case 'speech-event':
            echo get_field( 'speech-event', $post_id );
        break;

        case 'speech-event-date':
            echo get_the_date( 'F Y', $post_id );
        break;

        case 'speech-event-location':
            $location = get_field( 'speech-event-location', $post_id );

            echo ( ! empty( $location ) )? $location : '&mdash;';
        break;

    endswitch;
}

add_action( 'manage_vortrag_posts_custom_column', 'mdb_tc__vortrag__manage_posts_custom_column', 10, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since  1.0.0
 * @param  array  $columns   The columns.
 * @return array             An associative array.
 */

function mdb_tc__vortrag__manage_sortable_columns( $columns )
{
    $columns['speech-event-date']     = 'event-date';
    $columns['speech-event-location'] = 'event-location';

    return $columns;
}

add_filter( 'manage_edit-vortrag_sortable_columns', 'mdb_tc__vortrag__manage_sortable_columns' );



/**
 * Produces a sorted output.
 *
 * @since 1.0.0
 * @param WP_Query $query   A data object of the last query made.
 */

function mdb_tc__vortrag__pre_get_posts( $query )
{
    if( $query->is_main_query() and is_admin() ) :

        $orderby = $query->get( 'orderby' );

        switch( $orderby ) :

            case 'event-date':
                $query->set( 'orderby', 'publish_date' );
            break;

            case 'title':
                $query->set( 'orderby', 'title' );
            break;

            case 'event-location':
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'speech-event-location' );
            break;

        endswitch;
    endif;
}

add_action( 'pre_get_posts', 'mdb_tc__vortrag__pre_get_posts', 1 );
