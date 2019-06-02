<?php

if ( ! function_exists( 'add_action' ) ) {
	return;
}

require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/environments.php';

add_filter(
	'altis.config',
	function( $config ) {
		array_walk(
			$config['modules']['cloud'],
			function( &$status, $feature ) {
				switch ( $feature ) {
					case 'enabled':
					case 'cavalcade':
					case 's3-uploads':
					case 'batcache':
					case 'memcached':
						$status = true;
						break;
					default:
						$status = false;
						break;
				}
			}
		);
		var_dump( 'config', $config );
		return $config;
	},
	5
);

var_dump( '.config/load.php' );
