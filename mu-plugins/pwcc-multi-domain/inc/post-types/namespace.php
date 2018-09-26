<?php
/**
 * @package     PWCCMultiDomain
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\MultiDomain\PostTypes;

use PWCC\MultiDomain;
use WP_Error;

/**
 * Initialise the namespace.
 *
 * Runs on the `plugins_loaded` filter.
 */
function bootstrap() {
	$filters = [ 'post_link', 'page_link', 'post_type_link', 'get_canonical_url', 'attachment_link' ];
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
	$custom_homes = [];

	/**
	 * Filters the custom home URLs used for post types.
	 *
	 * @param array Post Type => custom home key/value pairs.
	 */
	$custom_homes = apply_filters( 'pwcc/multi-domain/post-types/domains', $custom_homes );

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
		'pwcc/multi-domain/post-types/unable-to-determine-domain',
		'Unable to determine domain to use for Post Type'
	);
}

/**
 * Filter post permalink to use preferred home URL.
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

	if ( $post->post_type === 'attachment' && $post->post_parent ) {
		/*
		 * Attachments are a special case, the canonical URL is based
		 * on that of the parent post.
		 */
		$post = get_post( $post->post_parent );
	}

	$permalink_home = get_post_types_custom_home( $post->post_type );
	if ( is_wp_error( $permalink_home ) ) {
		return $permalink;
	}

	return MultiDomain\normalise_url( $permalink, $permalink_home );
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

	return MultiDomain\normalise_url( $home_url, $real_home );
}

/**
 * Filter post type archive use preferred home URL.
 *
 * Runs on the `post_type_archive_link` filter.
 *
 * @param string $link      The post type archive permalink.
 * @param string $post_type Post type name.
 * @return string The modified post type archive URL.
 */
function filter_post_type_archive_link( $link, $post_type ) {
	$real_home = get_post_types_custom_home( $post_type );
	if ( is_wp_error( $real_home ) ) {
		return $link;
	}

	return MultiDomain\normalise_url( $link, $real_home );
}
