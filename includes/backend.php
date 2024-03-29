<?php
/**
 * Functions to handle the backend.
 *
 * @author  Marco Di Bella
 * @package mdb-theme-core
 */


namespace mdb_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Load the backend scripts and styles.
 *
 * @since 1.0.0
 */

function admin_enqueue_scripts() {
    wp_enqueue_style(
        'mdb-theme-core',
        PLUGIN_URL . 'assets/build/css/backend.min.css',
        [],
        PLUGIN_VERSION
    );
}

add_action( 'admin_enqueue_scripts',__NAMESPACE__ . '\admin_enqueue_scripts' );
