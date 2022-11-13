<?php
/**
 * Functions to activate, initiate and deactivate the plugin.
 *
 * @author   Marco Di Bella
 * @package  mdb-theme-core
 */


namespace mdb_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * The activation function for the plugin.
 *
 * @since  1.0.0
 */

function plugin_activation()
{
    // Do something!
}

register_activation_hook( __FILE__, 'mdb_theme_core\plugin_activation' );



/**
 * The deactivation function for the plugin.
 *
 * @since  1.0.0
 */

function plugin_deactivation()
{
    // Do something!
}

register_deactivation_hook( __FILE__, 'mdb_theme_core\plugin_deactivation' );



/**
 * The init function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_init()
{
    // Load text domain
    // The in the Codex described method to determine the path of the languages folder fails because we are in a subfolfer (/includes).
    load_plugin_textdomain( 'mdb-theme-core', false, '/mdb-theme-core/languages' );
}

add_action( 'init', 'mdb_theme_core\plugin_init' );
