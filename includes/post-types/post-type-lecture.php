<?php
/**
 * Custom post type to manage lectures.
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

function lecture__register()
{
    $labels = [
        'name'                  => __( 'Lectures', 'mdb-theme-core' ),
        'singular_name'         => __( 'Lecture', 'mdb-theme-core' ),
        'menu_name'             => __( 'Lectures', 'mdb-theme-core' ),
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
    ];

    $args = [
        'label'                 => __( 'Lectures', 'mdb-theme-core' ),
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
            'slug'          => 'lecture',
            'with_front'    => true
        ],
        'query_var'             => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-database',
        'supports'              => ['title'],
        'show_in_graphql'       => false,
    ];

    register_post_type( 'lecture', $args );
}

add_action( 'init', 'mdb_theme_core\lecture__register' );



/**
 * Determines the columns of the lecture list (backend).
 *
 * @since 1.0.0
 *
 * @param array $default The defaults for columns.
 *
 * @return array An associative array describing the columns to use.
 */

function lecture__manage_posts_columns( $default )
{
    $columns['cb']                    = $default['cb'];
    $columns['title']                 = __( 'Lecture', 'mdb-theme-core' );
    $columns['speech-event']          = __( 'Event', 'mdb-theme-core' );
    $columns['speech-event-date']     = __( 'Date', 'mdb-theme-core' );
    $columns['speech-event-location'] = __( 'Location', 'mdb-theme-core' );

    return $columns;
}

add_filter( 'manage_lecture_posts_columns', 'mdb_theme_core\lecture__manage_posts_columns', 10 );



/**
 * Generates the output of the columns.
 *
 * @since 1.0.0
 *
 * @param string $column_name Designation of the column to be output.
 * @param int    $post_id     ID of the contribution (aka record) to be output.
 */

function lecture__manage_posts_custom_column( $column_name, $post_id )
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

add_action( 'manage_lecture_posts_custom_column', 'mdb_theme_core\lecture__manage_posts_custom_column', 10, 2 );



/**
 * Registers sortable columns (by assigning appropriate orderby parameters).
 *
 * @since 1.0.0
 *
 * @param array $columns The columns.
 *
 * @return array An associative array.
 */

function lecture__manage_sortable_columns( $columns )
{
    $columns['speech-event-date']     = 'event-date';
    $columns['speech-event-location'] = 'event-location';

    return $columns;
}

add_filter( 'manage_edit-lecture_sortable_columns', 'mdb_theme_core\lecture__manage_sortable_columns' );



/**
 * Produces a sorted output.
 *
 * @since 1.0.0
 *
 * @param WP_Query $query A data object of the last query made.
 */

function lecture__pre_get_posts( $query )
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

add_action( 'pre_get_posts', 'mdb_theme_core\lecture__pre_get_posts', 1 );



/**
 * Adds a JS script for:
 * - moving various standard WordPress input fields to a new mask (created with ACF),
 * - collapsing flexible fields by default.
 *
 * @since 1.0.0
 *
 * @see http://www.advancedcustomfields.com/resources/moving-wp-elements-content-editor-within-acf-fields/
 * @see https://support.advancedcustomfields.com/forums/topic/issue-with-closing-flexible-fields-by-default/
 */

function lecture__admin_head()
{
?>
<script type="text/javascript">
jQuery(function($)
{
    $(document).ready(function(){
        $('.acf-field-5b572cf39d39a .acf-input').append( $('#title') );
    });
});
</script>
<?php
}

add_action( 'acf/input/admin_head', 'mdb_theme_core\lecture__admin_head' );
