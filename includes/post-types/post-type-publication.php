<?php
/**
 * Custom post type to manage publications.
 *
 * @author  Marco Di Bella
 * @package mdb-theme-core
 * @uses    ACF
 */


namespace mdb_theme_core\post_types\publication;

use mdb_theme_core\api as publication;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Register the custom post type.
 *
 * @since 1.0.0
 */

function register() {
    $labels = [
        'name'                  => __( 'Publications', 'mdb-theme-core' ),
        'singular_name'         => __( 'Publication', 'mdb-theme-core' ),
        'menu_name'             => __( 'Publications', 'mdb-theme-core' ),
        'all_items'             => __( 'All items', 'mdb-theme-core' ),
        'add_new'               => __( 'Add', 'mdb-theme-core' ),
        'add_new_item'          => __( 'Add new item', 'mdb-theme-core' ),
        'edit_item'             => __( 'Edit item', 'mdb-theme-core' ),
        'new_item'              => __( 'New item', 'mdb-theme-core' ),
        'view_item'             => __( 'View item', 'mdb-theme-core' ),
        'search_items'          => __( 'Search items', 'mdb-theme-core' ),
        'not_found'             => __( 'No item found', 'mdb-theme-core' ),
        'not_found_in_trash'    => __( 'No item found in trash', 'mdb-theme-core' ),
        'item_published'        => __( 'Item published', 'mdb-theme-core' ),
        'item_updated'          => __( 'Item updated', 'mdb-theme-core' ),
        'featured_image'        => __( 'Cover', 'mdb-theme-core' ),
        'set_featured_image'    => __( 'Set cover', 'mdb-theme-core' ),
        'remove_featured_image' => __( 'Remove cover', 'mdb-theme-core' ),
        'use_featured_image'    => __( 'Use as cover', 'mdb-theme-core' ),
    ];

    $args = [
        'label'                 => __( 'Publications', 'mdb-theme-core' ),
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
            'slug'       => _x( 'publication', 'slug name', 'mdb-theme-core' ),
            'with_front' => true
        ],
        'query_var'             => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-database',
        'supports'              => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'author' ],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'publication', $args );
}

add_action( 'init', __NAMESPACE__ . '\register' );



/**
 * Determines the columns of the publication list (backend).
 *
 * @since 1.0.0
 *
 * @param array $default The defaults for columns.
 *
 * @return array An associative array describing the columns to use.
 */

function manage_posts_columns( $default ) {
    $columns['cb']                         = $default['cb'];
    $columns['cover']                      = __( 'Cover', 'mdb-theme-core' );
    $columns['title']                      = __( 'Title', 'mdb-theme-core' );
    $columns['taxonomy-publication_group'] = __( 'Publication group', 'mdb-theme-core' );
    $columns['year']                       = __( 'Published', 'mdb-theme-core' );
    $columns['citation']                   = __( 'Citations', 'mdb-theme-core' );

    return $columns;
}

add_filter( 'manage_publication_posts_columns', __NAMESPACE__ . '\manage_posts_columns', 10 );



/**
 * Generates the output of the columns.
 *
 * @since 1.0.0
 *
 * @param string $column_name Designation of the column to be output.
 * @param int    $post_id     ID of the contribution (aka record) to be output.
 */

function manage_posts_custom_column( $column_name, $post_id ) {
    $data = publication\get_data( $post_id );

    switch( $column_name ) {
        case 'cover':
            if ( true === has_post_thumbnail( $post_id ) ) {
                echo sprintf(
                    '<a href="/wp-admin/post.php?post=%1$s&action=edit" title="%3$s">%2$s</a>',
                    $post_id,
                    get_the_post_thumbnail( $post_id, array( 100, 0 ) ),
                    __( 'Edit', 'mdb-theme-core' )
                );
            }
            break;

        case 'year':
            echo $data['pubyear'];
            break;

        case 'citation':
            if ( isset( $data['citation'] ) ) {
                echo sizeof( $data['citation'] );
            } else {
                echo '0';
            }
            break;
    }
}

add_action( 'manage_publication_posts_custom_column', __NAMESPACE__ . '\manage_posts_custom_column', 9999, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since 1.0.0
 *
 * @param array $columns The columns.
 *
 * @return array An associative array.
 */

function manage_sortable_columns( $columns ) {
    $columns['year']     = 'year';
    $columns['citation'] = 'citation';
    return $columns;
}

add_filter( 'manage_edit-publication_sortable_columns', __NAMESPACE__ . '\manage_sortable_columns' );



/**
 * Produces a sorted output.
 *
 * @since 1.0.0
 *
 * @param WP_Query $query A data object of the last query made.
 */

function pre_get_posts( $query ) {
    if ( $query->is_main_query() and is_admin() ) {

        $orderby = $query->get( 'orderby' );

        switch( $orderby ) {
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
        }
    }
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\pre_get_posts', 1 );
