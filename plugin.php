<?php
/**
 * Plugin Name:     Marco Di Bella - Theme Core Functions
 * Plugin URI:      https://github.com/mdibella-dev/mdb-theme-core
 * Description:     Custom post types and core functions for Marco Di Bella's theme (mdb-theme-fse).
 * Author:          Marco Di Bella
 * Author URI:      https://www.marcodibella.de
 * Version:         1.2.0
 * Text Domain:     mdb-theme-core
 * Domain Path:     /languages
 *
 * @author   Marco Di Bella
 * @package  mdb-theme-core
 */

namespace mdb_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/** Variables and definitions **/

$plugin_path    = plugin_dir_path( __FILE__ );
$plugin_version = '1.2.0';

define( 'MDB_BUILD_STRING', 1 );
define( 'MDB_BUILD_ARRAY', 2 );



/** Include function library */

require_once( $plugin_path . 'includes/post-types/post-type-publication.php' );
require_once( $plugin_path . 'includes/post-types/post-type-lecture.php' );
require_once( $plugin_path . 'includes/taxonomies/taxonomy-publication-group.php' );
require_once( $plugin_path . 'includes/taxonomies/taxonomy-publication-keyword.php' );
require_once( $plugin_path . 'includes/api/publication.php' );
require_once( $plugin_path . 'includes/block-editor.php' );
require_once( $plugin_path . 'includes/backend.php' );
require_once( $plugin_path . 'includes/setup.php' );
