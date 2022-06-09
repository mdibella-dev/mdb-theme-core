<?php
/**
 * Table related functions for the custom post type to manage lectures.
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 * @package mdb-theme-core
 * @uses    ACF
 */


defined( 'ABSPATH' ) or exit;



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
