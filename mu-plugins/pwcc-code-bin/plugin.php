<?php
/**
 * PWCC Code Bin.
 *
 * @package     PWCCCodeBin
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: PWCC Code Bin.
 * Plugin URI:  https://peterwilson.cc/
 * Description: A self hosted code bin.
 * Version:     %%VERSION%%
 * Author:      Peter Wilson
 * Author URI:  https://peterwilson.cc/
 * Text Domain: pwcc-code-bin
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
namespace PWCC\CodeBin;

require_once __DIR__ . '/inc/namespace.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
