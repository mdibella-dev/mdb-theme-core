<?php
/**
 * Functions to activate, initiate and deactivate the plugin.
 *
 * @author  Marco Di Bella
 * @package mdb-theme-core
 */


namespace mdb_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * The init function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_init()
{
    // Load text domain
    load_plugin_textdomain( 'mdb-theme-core', false, plugin_basename( PLUGIN_DIR ) . '/languages' );

    if( true == post_type_exists( 'portfolio' ) ) :
        unregister_post_type( 'portfolio' );
    endif;
}

add_action( 'init', __NAMESPACE__ . '\plugin_init', 9 );




/**
 * The activation function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_activation()
{
    // Do something!
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\plugin_activation' );



/**
 * The deactivation function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_deactivation()
{
    // Do something!
    // unregister_post_type()
}

register_deactivation_hook( __FILE__, __NAMESPACE__ . '\plugin_deactivation' );



/**
 * The uninstall function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_uninstall()
{
    // Do something!
    // Delete options!
    // Delete custom tables!
}

register_uninstall_hook( __FILE__, 'ph_PLUGIN_NAMESPACE\plugin_uninstall' );
