<?php
/**
 * Plugin Name:     Marco Di Bella - Theme Core Functions
 * Plugin URI:      https://github.com/mdibella-dev/mdb-theme-core
 * Description:     Custom post types and core functions for Marco Di Bella's theme (mdb-theme-fse).
 * Author:          Marco Di Bella
 * Author URI:      https://www.marcodibella.de
 * Version:         1.3.0
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

define( __NAMESPACE__ . '\PLUGIN_VERSION', '1.3.0' );
define( __NAMESPACE__ . '\PLUGIN_DOMAIN', 'mdb-theme-core' );
define( __NAMESPACE__ . '\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'MDB_BUILD_STRING', 1 );            // need global scope here
define( 'MDB_BUILD_ARRAY', 2 );             // need global scope here



/** Include function library */

require_once( PLUGIN_DIR . 'includes/post-types/post-type-publication.php' );
require_once( PLUGIN_DIR . 'includes/post-types/post-type-lecture.php' );

require_once( PLUGIN_DIR . 'includes/taxonomies/taxonomy-publication-group.php' );
require_once( PLUGIN_DIR . 'includes/taxonomies/taxonomy-publication-keyword.php' );

require_once( PLUGIN_DIR . 'includes/api/publication.php' );
require_once( PLUGIN_DIR . 'includes/block-editor.php' );
require_once( PLUGIN_DIR . 'includes/backend.php' );
require_once( PLUGIN_DIR . 'includes/setup.php' );
