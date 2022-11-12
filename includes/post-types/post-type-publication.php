<?php
/**
 * Custom post type to manage publications.
 *
 * @author   Marco Di Bella
 * @package  mdb-theme-core
 * @uses     ACF
 */


namespace mdb_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Register the custom post type.
 *
 * @since  1.0.0
 */

function publication__register()
{
    $labels = [
        'name'                  => __( 'Publikationen', 'mdb_theme_core' ),
        'singular_name'         => __( 'Publikation', 'mdb_theme_core' ),
        'menu_name'             => __( 'Publikationen', 'mdb_theme_core' ),
        'all_items'             => __( 'Alle Publikationen', 'mdb_theme_core' ),
        'add_new'               => __( 'Hinzufügen', 'mdb_theme_core' ),
        'add_new_item'          => __( 'Neue Publikation hinzufügen', 'mdb_theme_core' ),
        'edit_item'             => __( 'Publikation bearbeiten', 'mdb_theme_core' ),
        'new_item'              => __( 'Neue Publikation', 'mdb_theme_core' ),
        'view_item'             => __( 'Publikation anzeigen', 'mdb_theme_core' ),
        'search_items'          => __( 'Publikationen durchsuchen', 'mdb_theme_core' ),
        'not_found'             => __( 'Keine Publikation gefunden', 'mdb_theme_core' ),
        'not_found_in_trash'    => __( 'Keine Publikationen im Papierkorb gefunden', 'mdb_theme_core' ),
        'featured_image'        => __( 'Titelbild', 'mdb_theme_core' ),
        'set_featured_image'    => __( 'Titelbild festlegen', 'mdb_theme_core' ),
        'remove_featured_image' => __( 'Titelbild entfernen', 'mdb_theme_core' ),
        'use_featured_image'    => __( 'Als Titelbild verwenden', 'mdb_theme_core' ),
        'item_published'        => __( 'Publikation veröffentlicht', 'mdb_theme_core' ),
        'item_updated'          => __( 'Publikation aktualisiert', 'mdb_theme_core' ),
    ];

    $args = [
        'label'                 => __( 'Publikationen', 'mdb_theme_core' ),
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

add_action( 'init', 'mdb_theme_core\publication__register' );



/**
 * Determines the columns of the publication list (backend).
 *
 * @since   1.0.0
 * @param   array   $default     The defaults for columns.
 * @return  array   An associative array describing the columns to use.
 */

function publication__manage_posts_columns( $default )
{
    $columns['cb']                         = $default['cb'];
    $columns['cover']                      = __( 'Titelbild', 'mdb_theme_core' );
    $columns['title']                      = __( 'Titel', 'mdb_theme_core' );
    $columns['taxonomy-publication_group'] = __( 'Publikationsgruppe', 'mdb_theme_core' );
    $columns['year']                       = __( 'Veröffentlichung', 'mdb_theme_core' );
    $columns['citation']                   = __( 'Zitate', 'mdb_theme_core' );

    return $columns;
}

add_filter( 'manage_publication_posts_columns', 'mdb_theme_core\publication__manage_posts_columns', 10 );



/**
 * Generates the output of the columns.
 *
 * @since  1.0.0
 * @param  string $column_name    Designation of the column to be output.
 * @param  int    $post_id        ID of the contribution (aka record) to be output.
 */

function publication__manage_posts_custom_column( $column_name, $post_id )
{
    $data = publication__get_data( $post_id );

    switch( $column_name ) :

        case 'cover':
            if( true === has_post_thumbnail( $post_id ) ) :
                echo sprintf(
                    '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                    $post_id,
                    get_the_post_thumbnail( $post_id, array( 100, 0 ) ),
                    __( 'Bearbeiten', 'mdb_theme_core' )
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

add_action( 'manage_publication_posts_custom_column', 'mdb_theme_core\publication__manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since   1.0.0
 * @param   array  $columns    The columns.
 * @return  array  An associative array.
 */

function publication__manage_sortable_columns( $columns )
{
    $columns['year']     = 'year';
    $columns['citation'] = 'citation';
    return $columns;
}

add_filter( 'manage_edit-publication_sortable_columns', 'mdb_theme_core\publication__manage_sortable_columns' );



/**
 * Produces a sorted output.
 *
 * @since  1.0.0
 * @param  WP_Query  $query    A data object of the last query made.
 */

function publication__pre_get_posts( $query )
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

add_action( 'pre_get_posts', 'mdb_theme_core\publication__pre_get_posts', 1 );
