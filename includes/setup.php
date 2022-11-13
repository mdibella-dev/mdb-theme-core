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
    // unregister_post_type()
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
    load_plugin_textdomain( 'mdb-theme-core', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages' );
}

add_action( 'init', 'mdb_theme_core\plugin_init', 9 );
