<?php
/**
 * Functions to activate, initiate and deactivate the plugin.
 *
 * @author   Marco Di Bella <mdb@marcodibella.de>
 * @package  mdb-theme-core
 */


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * The activation function for the plugin.
 *
 * @since  1.0.0
 */

function mdb_tc__plugin_activation()
{
    // Do something!
}

register_activation_hook( __FILE__, 'mdb_tc__plugin_activation' );



/**
 * The deactivation function for the plugin.
 *
 * @since  1.0.0
 */

function mdb_tc__plugin_deactivation()
{
    // Do something!
}

register_deactivation_hook( __FILE__, 'mdb_tc__plugin_deactivation' );
