<?php
/**
 * Functions to handle the backend.
 *
 * @author   Marco Di Bella
 * @package  mdb-theme-core
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Load the backend scripts and styles.
 *
 * @since  1.0.0
 */

function mdb_tc__plugin_scripts()
{
    wp_enqueue_style(
        'mdb_tc',
        plugins_url( 'assets/build/css/backend.min.css', dirname( __FILE__ ) ),
        $plugin_version
    );
}

add_action( 'admin_enqueue_scripts','mdb_tc__plugin_scripts' );
