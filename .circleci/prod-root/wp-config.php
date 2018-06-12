<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

include __DIR__ . '/pwcc-config.php';

define( 'PWCC_SCHEME', PWCC\Config\is_ssl() ? 'https' : 'http' );

if ( file_exists( dirname( __FILE__ ) . '/production-config.php' ) ) {
	include( dirname( __FILE__ ) . '/production-config.php' );
}

if ( PWCC_ENV === 'prod' && PWCC_SCHEME === 'http' ) {
	$redirect= 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header( 'HTTP/1.1 301 Moved Permanently' );
	header( "Location: $redirect" );
}

// ======================================
// Fake HTTP Host (for CLI compatibility)
// ======================================
if ( ! isset( $_SERVER['HTTP_HOST'] ) ) {
	$_SERVER['HTTP_HOST'] = 'peterwilson.cc';
}

// ========================
// Custom Content Directory
// ========================
defined( 'WP_CONTENT_DIR' ) or define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
defined('WP_CONTENT_URL') or define( 'WP_CONTENT_URL', PWCC_SCHEME . '://' . $_SERVER['HTTP_HOST'] . '/content' );

// =======================================
// Custom path to WordPress.
// =======================================
if ( !defined('ABSPATH') )
	define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );

// =======================
// Use built-in themes too
// =======================
if ( empty( $GLOBALS['wp_theme_directories'] ) ) {
	$GLOBALS['wp_theme_directories'] = [];
}
if ( file_exists( WP_CONTENT_DIR . '/themes' ) ) {
	$GLOBALS['wp_theme_directories'][] = WP_CONTENT_DIR . '/themes';
}
$GLOBALS['wp_theme_directories'][] = ABSPATH . 'wp-content/themes';
$GLOBALS['wp_theme_directories'][] = ABSPATH . 'wp-content/themes';

// =======================================
// Check that we actually have a DB config
// =======================================
if ( ! defined( 'DB_HOST' ) || strpos( DB_HOST, '%%' ) !== false ) {
	header('X-WP-Error: dbconf', true, 500);
	echo '<h1>Database configuration is incomplete.</h1>';
	echo "<p>If you're developing locally, ensure you have a local-config.php.
	If this is in production, deployment is broken.</p>";
	die(1);
}

// ===============
// Database prefix
// ===============
$table_prefix  = 'wp_';

// =======================================
// Do not debug on production servers.
// =======================================
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}
if ( ! defined( 'WP_DEBUG_DISPLAY' ) ) {
	ini_set( 'display_errors', 0 );
	define( 'WP_DEBUG_DISPLAY', false );
}

// ====================================
// Do not update on production servers.
// ====================================

defined( 'DISALLOW_FILE_EDIT' ) OR define( 'DISALLOW_FILE_EDIT', true );
defined( 'AUTOMATIC_UPDATER_DISABLED' ) OR define( 'AUTOMATIC_UPDATER_DISABLED', true );

// ===================
// Bootstrap WordPress
// ===================
if ( ! file_exists( ABSPATH . 'wp-settings.php' ) ) {
	header('X-WP-Error: wpmissing', true, 500);
	echo '<h1>WordPress is missing.</h1>';
	die(1);
}
require_once( ABSPATH . 'wp-settings.php' );
