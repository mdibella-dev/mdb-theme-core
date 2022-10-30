<?php
/**
 * Custom post type to manage publications.
 *
 * @author   Marco Di Bella <mdb@marcodibella.de>
 * @package  mdb-theme-core
 * @uses     ACF
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Register the custom post type.
 *
 * @since  1.0.0
 */

function mdb_tc__publikation__register()
{
    $labels = [
        'name'                  => __( 'Publikationen', 'mdb_tc' ),
        'singular_name'         => __( 'Publikation', 'mdb_tc' ),
        'menu_name'             => __( 'Publikationen', 'mdb_tc' ),
        'all_items'             => __( 'Alle Publikationen', 'mdb_tc' ),
        'add_new'               => __( 'Hinzufügen', 'mdb_tc' ),
        'add_new_item'          => __( 'Neue Publikation hinzufügen', 'mdb_tc' ),
        'edit_item'             => __( 'Publikation bearbeiten', 'mdb_tc' ),
        'new_item'              => __( 'Neue Publikation', 'mdb_tc' ),
        'view_item'             => __( 'Publikation anzeigen', 'mdb_tc' ),
        'search_items'          => __( 'Publikationen durchsuchen', 'mdb_tc' ),
        'not_found'             => __( 'Keine Publikation gefunden', 'mdb_tc' ),
        'not_found_in_trash'    => __( 'Keine Publikationen im Papierkorb gefunden', 'mdb_tc' ),
        'featured_image'        => __( 'Titelbild', 'mdb_tc' ),
        'set_featured_image'    => __( 'Titelbild festlegen', 'mdb_tc' ),
        'remove_featured_image' => __( 'Titelbild entfernen', 'mdb_tc' ),
        'use_featured_image'    => __( 'Als Titelbild verwenden', 'mdb_tc' ),
        'item_published'        => __( 'Publikation veröffentlicht', 'mdb_tc' ),
        'item_updated'          => __( 'Publikation aktualisiert', 'mdb_tc' ),
    ];

    $args = [
        'label'                 => __( 'Publikationen', 'mdb_tc' ),
        'labels'                => $labels,
        'description'           => 'Alle Formen von Publikationen: Beiträge in Fachzeitschriften, Büchern etc.',
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
            'slug'          => 'publication',
            'with_front'    => true
        ],
        'query_var'             => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-media-document',
        'supports'              => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'author' ],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'publication', $args );
}

add_action( 'init', 'mdb_tc__publikation__register' );



/**
 * Determines the columns of the publication list (backend).
 *
 * @since   1.0.0
 * @param   array   $default     The defaults for columns.
 * @return  array   An associative array describing the columns to use.
 */

function mdb_tc__publication__manage_posts_columns( $default )
{
    $columns['cb']                         = $default['cb'];
    $columns['cover']                      = __( 'Titelbild', 'mdb_tc' );
    $columns['title']                      = __( 'Titel', 'mdb_tc' );
    $columns['taxonomy-publication_group'] = __( 'Publikationsgruppe', 'mdb_tc' );
    $columns['year']                       = __( 'Veröffentlichung', 'mdb_tc' );
    $columns['citation']                   = __( 'Zitate', 'mdb_tc' );

    return $columns;
}

add_filter( 'manage_publication_posts_columns', 'mdb_tc__publication__manage_posts_columns', 10 );



/**
 * Generates the output of the columns.
 *
 * @since  1.0.0
 * @param  string $column_name    Designation of the column to be output.
 * @param  int    $post_id        ID of the contribution (aka record) to be output.
 */

function mdb_tc__publication__manage_posts_custom_column( $column_name, $post_id )
{
    $data = mdb_tc__publication__get_data( $post_id );

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

add_action( 'manage_publication_posts_custom_column', 'mdb_tc__publication__manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since   1.0.0
 * @param   array  $columns    The columns.
 * @return  array  An associative array.
 */

function mdb_tc__publication__manage_sortable_columns( $columns )
{
    $columns['year']     = 'year';
    $columns['citation'] = 'citation';
    return $columns;
}

add_filter( 'manage_edit-publication_sortable_columns', 'mdb_tc__publication__manage_sortable_columns' );



/**
 * Produces a sorted output.
 *
 * @since  1.0.0
 * @param  WP_Query  $query    A data object of the last query made.
 */

function mdb_tc__publication__pre_get_posts( $query )
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

add_action( 'pre_get_posts', 'mdb_tc__publication__pre_get_posts', 1 );
