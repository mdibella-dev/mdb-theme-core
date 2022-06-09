<?php
/**
 * Plugin Name:     Marco Di Bella - Kernfunktionen
 * Author:          Marco Di Bella
 * Author URI:      https://www.marcodibella.de
 * Description:     Verschiedene Funktionen, die ursprünglich im Theme von Marco Di Bella (mdb-theme) enthalten waren und nunmehr ausgegliedert worden sind.
 * Version:         1.0.0
 * Text Domain:     mdb_tc
 * Domain Path:     /languages
 *
 * @author  Marco Di Bella <mdb@marcodibella.de>
 * @package mdb_theme_core
 */


defined( 'ABSPATH' ) or exit;



/** Variables **/

$plugin_path = plugin_dir_path( __FILE__ );


/** Include function library */

require_once( $plugin_path . 'includes/post-types/post-type-publikation-table.php' );
require_once( $plugin_path . 'includes/post-types/post-type-vortrag-table.php' );
require_once( $plugin_path . 'includes/post-types/post-type-vortrag.php' );
require_once( $plugin_path . 'includes/api/publikation.php' );
require_once( $plugin_path . 'includes/acf.php' );
require_once( $plugin_path . 'includes/block-editor.php' );
require_once( $plugin_path . 'includes/setup.php' );
