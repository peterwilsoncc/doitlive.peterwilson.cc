<?php

use const Altis\ROOT_DIR;
/*
 * In Altis, Multisite is always enabled, unless some spooky
 * early loading code tried to change that of course.
 *
 * This is that spooky early loading code.
 */
if ( ! defined( 'MULTISITE' ) ) {
	define( 'MULTISITE', false );
}

// Needed before Altis defines them.
define( 'ABSPATH', ROOT_DIR . '/wordpress/' );
define( 'WP_CONTENT_DIR', ROOT_DIR . '/content' );


// =======================
// Use built-in themes too
// =======================
if ( empty( $GLOBALS['wp_theme_directories'] ) ) {
	$GLOBALS['wp_theme_directories'] = array();
}

if ( file_exists( WP_CONTENT_DIR . '/themes' ) ) {
	$GLOBALS['wp_theme_directories'][] = WP_CONTENT_DIR . '/themes';
}
$GLOBALS['wp_theme_directories'][] = ABSPATH . 'wp-content/themes';
$GLOBALS['wp_theme_directories'][] = ABSPATH . 'wp-content/themes';
