<?php
/**
 * Custom post type to manage publications.
 *
 * @author  Marco Di Bella
 * @package mdb-theme-core
 * @uses    ACF
 */


namespace mdb_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Register the custom post type.
 *
 * @since 1.0.0
 */

function publication__register()
{
    $labels = [
        'name'                  => __( 'Publications', PLUGIN_DOMAIN ),
        'singular_name'         => __( 'Publication', PLUGIN_DOMAIN ),
        'menu_name'             => __( 'Publications', PLUGIN_DOMAIN ),
        'all_items'             => __( 'All publications', PLUGIN_DOMAIN ),
        'add_new'               => __( 'Add publication', PLUGIN_DOMAIN ),
        'add_new_item'          => __( 'Add new publication', PLUGIN_DOMAIN ),
        'edit_item'             => __( 'Edit publication', PLUGIN_DOMAIN ),
        'new_item'              => __( 'New publication', PLUGIN_DOMAIN ),
        'view_item'             => __( 'View publication', PLUGIN_DOMAIN ),
        'search_items'          => __( 'Search publications', PLUGIN_DOMAIN ),
        'not_found'             => __( 'No publication found', PLUGIN_DOMAIN ),
        'not_found_in_trash'    => __( 'No publication found in trash', PLUGIN_DOMAIN ),
        'featured_image'        => __( 'Cover', PLUGIN_DOMAIN ),
        'set_featured_image'    => __( 'Set cover', PLUGIN_DOMAIN ),
        'remove_featured_image' => __( 'Remove cover', PLUGIN_DOMAIN ),
        'use_featured_image'    => __( 'Use as cover', PLUGIN_DOMAIN ),
        'item_published'        => __( 'Publication published', PLUGIN_DOMAIN ),
        'item_updated'          => __( 'Publication updated', PLUGIN_DOMAIN ),
    ];

    $args = [
        'label'                 => __( 'Publications', PLUGIN_DOMAIN ),
        'labels'                => $labels,
        'description'           => 'All forms of publications: articles in specialist journals, books, etc.',
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
 * @since  1.0.0
 * @param  array $default    The defaults for columns.
 * @return array             An associative array describing the columns to use.
 */

function publication__manage_posts_columns( $default )
{
    $columns['cb']                         = $default['cb'];
    $columns['cover']                      = __( 'Cover', PLUGIN_DOMAIN );
    $columns['title']                      = __( 'Title', PLUGIN_DOMAIN );
    $columns['taxonomy-publication_group'] = __( 'Publication group', PLUGIN_DOMAIN );
    $columns['year']                       = __( 'Published', PLUGIN_DOMAIN );
    $columns['citation']                   = __( 'Citations', PLUGIN_DOMAIN );

    return $columns;
}

add_filter( 'manage_publication_posts_columns', 'mdb_theme_core\publication__manage_posts_columns', 10 );



/**
 * Generates the output of the columns.
 *
 * @since 1.0.0
 * @param string $column_name    Designation of the column to be output.
 * @param int    $post_id        ID of the contribution (aka record) to be output.
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
                    __( 'Edit', PLUGIN_DOMAIN )
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
 * @since  1.0.0
 * @param  array $columns    The columns.
 * @return array             An associative array.
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
 * @since 1.0.0
 * @param WP_Query $query    A data object of the last query made.
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
