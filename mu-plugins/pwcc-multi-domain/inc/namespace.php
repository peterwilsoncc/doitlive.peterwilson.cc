<?php
/**
 * @package     PWCCMultiDomain
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\MultiDomain;

/**
 * Initialise the plugin.
 *
 * Runs on the `plugins_loaded` filter.
 */
function bootstrap() {
	PostTypes\bootstrap();
}

/**
 * Modify a URL to use the custom home link.
 *
 * @param string $url The URL being modified to a new URL.
 * @param string $urls_home The URLs home site.
 * @param array $custom_home_urls Array of custom home URLs.
 * @return string The modified URL.
 */
function normalise_url( string $url, string $urls_home, $custom_home_urls = null ) {
	if ( $custom_home_urls === null ) {
		// Default to Post types.
		$custom_home_urls = PostTypes\custom_home_urls();
	}

	// Trust WP to get the scheme correct.
	$scheme = wp_parse_url( $url, PHP_URL_SCHEME );

	$fix_trailing_slash = function( $normalised_url ) use ( $url ) {
		if ( substr( $url, -1 ) === '/' ) {
			return trailingslashit( $normalised_url );
		}
		return untrailingslashit( $normalised_url );
	};

	/*
	 * Trailing slash and set schemes of all URLs.
	 */
	$url = trailingslashit( $url );
	$urls_home = trailingslashit( set_url_scheme( $urls_home, $scheme ) );

	$home_urls = array_unique( $custom_home_urls );
	$home_urls = array_map( function( $url ) use ( $scheme ) {
		return trailingslashit( set_url_scheme( $url, $scheme ) );
	}, $home_urls );

	// If it already matches, no need to loop.
	if ( strpos( $url, $urls_home ) === 0 ) {
		return $fix_trailing_slash( $url );
	}

	foreach( $home_urls as $home_url ) {
		if ( strpos( $url, $home_url ) === 0 ) {
			// Replace the home portion with the custom URL.
			$url = $urls_home . substr( $url, strlen( $home_url ) );
			// Escape it.
			$url = esc_url_raw( $url );
			// Fix the trailing slash and return.
			return $fix_trailing_slash( $url );
		}
	}

	return $fix_trailing_slash( $url );
}
