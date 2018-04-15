<?php
/**
 * PWCC Notes.
 *
 * @package     PWCCNotes
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: PWCC Notes.
 * Plugin URI:  https://peterwilson.cc/
 * Description: Manage Twitter via a WordPress install.
 * Version:     1.0.0
 * Author:      Peter Wilson
 * Author URI:  https://peterwilson.cc/
 * Text Domain: pwcc-notes
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
namespace PWCC\Notes;

require_once __DIR__ . '/inc/namespace.php';
require_once __DIR__ . '/inc/filters/namespace.php';
require_once __DIR__ . '/inc/metaboxes/namespace.php';
require_once __DIR__ . '/inc/publishing/namespace.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
