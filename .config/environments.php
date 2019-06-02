<?php
use const Altis\ROOT_DIR;

if ( file_exists( ROOT_DIR . '/chassis/local-config-db.php' ) ) {
	defined( 'WP_DEBUG' ) or define( 'WP_DEBUG', true );
	defined( 'WP_DEBUG_DISPLAY' ) or define( 'WP_DEBUG_DISPLAY', true );
	error_reporting( E_ALL );
	ini_set( 'display_errors', 1 );
	require_once ROOT_DIR . '/chassis/local-config-db.php';
} elseif ( file_exists( ROOT_DIR . '/wp-config-production.php' ) ) {

}
