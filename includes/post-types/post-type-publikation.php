<?php
/**
 * Custom post type for managing publications.
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 * @package mdb-theme-core
 * @uses    ACF
 */


defined( 'ABSPATH' ) or exit;



/**
 * Determines the columns of the publication list (backend).
 *
 * @since  1.0.0
 * @param  array   $default    The defaults for columns.
 * @return array               An associative array describing the columns to use.
 */

function mdb_tc__publikation__manage_posts_columns( $default )
{
    $columns['cb']                             = $default['cb'];
    $columns['cover']                          = __( 'Titelbild', 'mdb_tc' );
    $columns['title']                          = __( 'Titel', 'mdb_tc' );
    $columns['taxonomy-publikation_kategorie'] = __( 'Publikationsform', 'mdb_tc' );
    $columns['year']                           = __( 'Veröffentlichung', 'mdb_tc' );
    $columns['citation']                       = __( 'Zitate', 'mdb_tc' );

    return $columns;
}

add_filter( 'manage_publikation_posts_columns', 'mdb_tc__publikation__manage_posts_columns', 10 );



/**
 * Generates the output of the columns.
 *
 * @since 1.0.0
 * @param string $column_name    Designation of the column to be output.
 * @param int    $post_id        ID of the contribution (aka record) to be output.
 */

function mdb_tc__publikation__manage_posts_custom_column( $column_name, $post_id )
{
    $data = mdb_publication__get_data( $post_id );

    switch( $column_name ) :

        case 'cover':
            if( true === has_post_thumbnail( $post_id ) ) :
                echo sprintf(
                    '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                    $post_id,
                    get_the_post_thumbnail( $post_id, array( 100, 0 ) ),
                    __( 'Bearbeiten', 'mdb_tc' )
                );
            endif;
        break;

        case 'year':
            echo $data['pubyear'];
        break;

        case 'citation' :
            if( isset( $data['citation'] ) ) :
                echo sizeof( $data['citation'] );
            else :
                echo '0';
            endif;
        break;

    endswitch;
}

add_action( 'manage_publikation_posts_custom_column', 'mdb_tc__publikation__manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since  1.0.0
 * @param  array  $columns   The columns.
 * @return array             An associative array.
 */

function mdb_tc__publikation__manage_sortable_columns( $columns )
{
    $columns['year']     = 'year';
    $columns['citation'] = 'citation';
    return $columns;
}

add_filter( 'manage_edit-publikation_sortable_columns', 'mdb_tc__publikation__manage_sortable_columns' );



/**
 * Produces a sorted output.
 *
 * @since 1.0.0
 * @param WP_Query $query   A data object of the last query made.
 */

function mdb_tc__publikation__pre_get_posts( $query )
{
    if( $query->is_main_query() and is_admin() ) :

        $orderby = $query->get( 'orderby' );

        switch( $orderby ) :

            case 'title':
                $query->set( 'orderby', 'title' );
            break;

            case 'year':
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'dokumenttypspezifische_angaben_ref_pubyear' );
            break;

            case 'citation':
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'meta_key', 'citations' );
            break;

            default:
            break;

        endswitch;
    endif;
}

add_action( 'pre_get_posts', 'mdb_tc__publikation__pre_get_posts', 1 );
