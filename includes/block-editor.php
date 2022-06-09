<?php
/**
 * Settings and functions related to the block editor (aka Gutenberg).
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 * @package mdb-theme-core
 */


defined( 'ABSPATH' ) or exit;



/**
 * Disables the block editor for various post types.
 *
 * @since  1.0.0
 * @param  bool   $can_edit     The current determination of whether Gutenberg can edit the specified post type (true) or not (false).
 * @param  string $post_type    The post type.
 * @return bool                 The new definition of whether Gutenberg can edit the specified post type (true) or not (false).
 *
 * @see    https://digwp.com/2018/04/how-to-disable-gutenberg/
 * @see    https://stackoverflow.com/questions/52199629/how-to-disable-gutenberg-editor-for-certain-post-types/52199630
 * @see    https://www.billerickson.net/disabling-gutenberg-certain-templates/
 */

function mdb_tc__disable_gutenberg( $can_edit, $post_type )
{
    if( ( 'lecture' === $post_type ) or ( 'publication' === $post_type ) ) :
        $can_edit = false;
    endif;

    return $can_edit;
}

add_filter( 'gutenberg_can_edit_post_type', 'mdb_tc__disable_gutenberg' );
add_filter( 'use_block_editor_for_post_type', 'mdb_tc__disable_gutenberg', 10, 2 );
