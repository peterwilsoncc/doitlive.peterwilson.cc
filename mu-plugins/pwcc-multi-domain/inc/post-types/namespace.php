<?php
/**
 * @package     PWCCMultiDomain
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\MultiDomain\PostTypes;

use WP_Error;

/**
 * Initialise the namespace.
 *
 * Runs on the `plugins_loaded` filter.
 */
function bootstrap() {
	$filters = [ 'post_link', 'page_link', 'post_type_link', 'get_canonical_url' ];
	foreach ( $filters as $filter ) {
		add_filter( $filter, __NAMESPACE__ . '\\filter_permalink', 10, 2 );
	}

	add_filter( 'home_url', __NAMESPACE__ . '\\filter_home_url', 10, 4 );

	add_filter( 'post_type_archive_link', __NAMESPACE__ . '\\filter_post_type_archive_link', 10, 2 );
}

/**
 * Array of custom domains used for post types.
 *
 * @return array Post type => custom home URL key/value pairs.
 */
function custom_home_urls() {
	$custom_homes = [
		'pwcc_notes' => 'http://peterwilsonme.local',
		'DEFAULT' => 'http://peterwilsoncc.local',
	];

	/**
	 * Filters the custom home URLs used for post types.
	 *
	 * @param array Post Type => custom home key/value pairs.
	 */
	$custom_homes = apply_filters( 'pwcc/multi-domain/domains', $custom_homes );

	$custom_homes = array_map( function( $value ) {
		return esc_url_raw( trailingslashit( $value ) );
	}, $custom_homes );

	return $custom_homes;
}

/**
 * Returns the preferred home URL for a custom post type.
 *
 * @param string $post_type The custom post type.
 * @return string|WP_Error The home URL. WP_Error if unable to determine.
 */
function get_post_types_custom_home( string $post_type ) {
	$custom_home_urls = custom_home_urls();

	if ( isset( $custom_home_urls[ $post_type ] ) ) {
		return $custom_home_urls[ $post_type ];
	}

	if ( isset( $custom_home_urls['DEFAULT'] ) ) {
		return $custom_home_urls['DEFAULT'];
	}

	if ( ! doing_filter( 'home_url' ) ) {
		return get_home_url();
	}

	return new WP_Error(
		'pwcc/multi-domain/unable-to-determine-domain',
		'Unable to determine domain to use for Post Type'
	);
}

function normalise_url( $url, $urls_home ) {
	// Trust WP to get the scheme correct.
	$scheme = wp_parse_url( $url, PHP_URL_SCHEME );

	$fix_trailing_slash = function( $normalised_url ) use ( $url ) {
		if ( substr( $url, -1 ) === '/' ) {
			return trailingslashit( $normalised_url );
		}
		return untrailingslashit( $normalised_url );
	};

	// Ensure schemes match.
	$urls_home = set_url_scheme( $urls_home, $scheme );

	$home_urls = array_unique( custom_home_urls() );
	$home_urls = array_map( function( $url ) use ( $scheme ) {
		return set_url_scheme( $url, $scheme );
	}, $home_urls );

	// If it already matches, no need to loop.
	if ( strpos( $url, $urls_home ) === 0 ) {
		return $url;
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

	return $url;
}

/**
 * Filter post permalink to preferred use preferred home URL.
 *
 * Runs on the following filters:
 * - post_link
 * - page_link
 * - post_type_link
 * - get_canonical_url
 *
 * @param string       $permalink The post's permalink.
 * @param \WP_Post|int $post      The post object or ID.
 * @return string The modified post permalink.
 */
function filter_permalink( string $permalink, $post ) {
	// Normalise the post.
	$post = get_post( $post );

	if ( ! $post ) {
		return $permalink;
	}

	$permalink_home = get_post_types_custom_home( $post->post_type );
	if ( is_wp_error( $permalink_home ) ) {
		return $permalink;
	}

	return normalise_url( $permalink, $permalink_home );
}

/**
 * Filter the home URL to use the default home.
 *
 * Runs on the `home_url` filter.
 *
 * @param string      $home_url    The complete home URL including scheme and path.
 * @param string      $path        Path relative to the home URL. Blank string if no path is specified.
 * @param string|null $orig_scheme Scheme to give the home URL context. Accepts 'http', 'https',
 *                                 'relative', 'rest', or null.
 * @param int|null    $blog_id     Site ID, or null for the current site.
 * @return string The modified home URL.
 */
function filter_home_url( string $home_url, string $path, $orig_scheme, $blog_id ) {
	if ( ! in_array( $path, [ '', '/' ], true ) ) {
		// Do not modify if the path is defined.
		return $home_url;
	}

	if ( ! in_array( $blog_id, [ null, get_current_blog_id() ], true ) ) {
		// Do not modify if the URL isn't for the current blog.
		return $home_url;
	}

	$real_home = get_post_types_custom_home( 'DEFAULT' );
	if ( is_wp_error( $real_home ) ) {
		return $home_url;
	}

	return normalise_url( $home_url, $real_home );
}

/**
 *
 * Runs on the `post_type_archive_link` filter.
 *
 * @param string $link      The post type archive permalink.
 * @param string $post_type Post type name.
 */
function filter_post_type_archive_link( $link, $post_type ) {
	$real_home = get_post_types_custom_home( $post_type );
	if ( is_wp_error( $real_home ) ) {
		return $link;
	}

	return normalise_url( $link, $real_home );
}
