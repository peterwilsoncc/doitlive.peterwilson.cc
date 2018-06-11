<?php

if ( ! defined( 'WP_CACHE' ) ) {
	define( 'WP_CACHE', true );
}

require_once __DIR__ . '/dropins/wordpress-pecl-memcached-object-cache/object-cache.php';
