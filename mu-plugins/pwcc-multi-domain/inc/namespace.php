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
	Taxonomies\bootstrap();

	add_filter( 'pwcc/multi-domain/post-types/domains', __NAMESPACE__ . '\\default_domain' );
	add_filter( 'pwcc/multi-domain/taxonomies/domains', __NAMESPACE__ . '\\default_domain' );

	add_filter( 'allowed_redirect_hosts', __NAMESPACE__ . '\\allowed_hosts' );
}

/**
 * Include custom host names in allowed redirects.
 *
 * Ensures comment form and other redirects work correctly
 * on the custom domains.
 *
 * Runs on the filter `allowed_redirect_hosts`.
 *
 * @param array $allowed_hosts
 * @return array
 */
function allowed_hosts( $allowed_hosts ) {
	// Combine post types and taxos.
	$custom_allowed_hosts = array_merge(
		PostTypes\custom_home_urls(),
		Taxonomies\custom_home_urls()
	);

	$custom_allowed_hosts = array_map( function( $home ){
		return wp_parse_url( $home, PHP_URL_HOST );
	}, $custom_allowed_hosts );

	$custom_allowed_hosts = array_unique( $custom_allowed_hosts );

	return array_merge( $allowed_hosts, $custom_allowed_hosts );
}

/**
 * Modify a URL to use the custom home link.
 *
 * @param string $url The URL being modified to a new URL.
 * @param string $urls_home The URLs home site.
 * @return string The modified URL.
 */
function normalise_url( string $url, string $urls_home ) {
	// Combine post types and taxos.
	$custom_home_urls = array_merge(
		PostTypes\custom_home_urls(),
		Taxonomies\custom_home_urls()
	);

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

	foreach ( $home_urls as $home_url ) {
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

/**
 * Define default domain according to environment.
 *
 * Runs on the filters:
 * - pwcc/multi-domain/post-types/domains
 * - pwcc/multi-domain/taxonomies/domains
 *
 * @param array $domains Custom home domains.
 * @return array Modified custom home domains.
 */
function default_domain( array $domains ) {
	if ( defined( 'PWCC_ENV' ) && PWCC_ENV === 'prod' ) {
		$domains['DEFAULT'] = 'https://peterwilson.cc/';
		return $domains;
	}

	$domains['DEFAULT'] = 'http://peterwilsoncc.local/';
	return $domains;
}
