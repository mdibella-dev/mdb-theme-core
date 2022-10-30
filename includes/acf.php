<?php
/**
 * Additions to Advanced Custom Fields (ACF).
 *
 * @author   Marco Di Bella <mdb@marcodibella.de>
 * @package  mdb-theme-core
 * @uses     ACF
 */


namespace mdb_theme_core;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Adds a JS script for:
 * - moving various standard WordPress input fields to a new mask (created with ACF),
 * - collapsing flexible fields by default.
 *
 * @since  1.0.0
 * @see    http://www.advancedcustomfields.com/resources/moving-wp-elements-content-editor-within-acf-fields/
 * @see    https://support.advancedcustomfields.com/forums/topic/issue-with-closing-flexible-fields-by-default/
 */

function mdb_tc__vortrag__admin_head()
{
?>
<script type="text/javascript">
jQuery(function($)
{
    $(document).ready(function(){
        $('.acf-field-5b572cf39d39a .acf-input').append( $('#title') );
    });
});
</script>
<?php
}

add_action( 'acf/input/admin_head', 'mdb_theme_core\mdb_tc__vortrag__admin_head' );
