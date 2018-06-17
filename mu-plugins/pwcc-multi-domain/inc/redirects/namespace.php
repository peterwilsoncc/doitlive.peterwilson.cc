<?php
/**
 * @package     PWCCMultiDomain
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\MultiDomain\Redirects;

use PWCC\MultiDomain;
use PWCC\MultiDomain\PostTypes;
use PWCC\MultiDomain\Taxonomies;

/**
 * Initialise the namespace.
 *
 * Runs on the `plugins_loaded` filter.
 */
function bootstrap() {
	add_action( 'template_redirect', __NAMESPACE__ . '\\filter_template_redirect' );
}

/**
 * Redirect to correct domain.
 *
 * Fires on the action `template_redirect`.
 *
 * Ideally this would fire on redirect_canonical but WP
 * doesn't fire the hook if it determines the URL is valid.
 */
function filter_template_redirect() {
	if ( is_404() ) {
		// Do not redirect.
		return;
	}

	if ( isset( $_SERVER['HTTP_HOST'] ) ) {
		// build the URL in the address bar
		$requested_url  = is_ssl() ? 'https://' : 'http://';
		$requested_url .= $_SERVER['HTTP_HOST'];
		$requested_url .= $_SERVER['REQUEST_URI'];
	}

	$original = @parse_url( $requested_url );
	if ( false === $original ) {
		return;
	}

	if ( is_front_page() || is_home() ) {
		// It's the front/blog page.
		front_page_redirect( $requested_url );
	} elseif ( is_singular() ) {
		// It's a post.
		singular_redirect( $requested_url );
	} elseif ( is_post_type_archive() ) {
		// It's a post type archive.
		post_type_archive_redirect( $requested_url );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		// It's a taxonomy archive.
		term_archive_redirect( $requested_url );
	} elseif ( is_date() ) {
		// It's a date archive (posts).
		date_archive_redirect( $requested_url );
	}
}

/**
 * @param string $requested_url The URL requested by the visitor.
 */
function front_page_redirect( string $requested_url ) {
	$post_type = 'DEFAULT';
	$real_home = PostTypes\get_post_types_custom_home( $post_type );
	if ( is_wp_error( $real_home ) ) {
		return;
	}

	safe_redirect( $requested_url, $real_home );
}

/**
 * Redirect a singular post to normalised URL.
 *
 * @param string $requested_url The URL requested by the visitor.
 */
function singular_redirect( string $requested_url ) {
	$object    = get_queried_object();
	$post_type = $object->post_type;
	$real_home = PostTypes\get_post_types_custom_home( $post_type );
	if ( is_wp_error( $real_home ) ) {
		return;
	}

	safe_redirect( $requested_url, $real_home );
}

/**
 * Redirect a CPT archive to normalised URL.
 *
 * @param string $requested_url The URL requested by the visitor.
 */
function post_type_archive_redirect( string $requested_url ) {
	$object    = get_queried_object();
	$post_type = $object->name;
	$real_home = PostTypes\get_post_types_custom_home( $post_type );
	if ( is_wp_error( $real_home ) ) {
		return;
	}

	safe_redirect( $requested_url, $real_home );
}

/**
 * Redirect a term archive to normalised URL.
 *
 * @param string $requested_url The URL requested by the visitor.
 */
function term_archive_redirect( string $requested_url ) {
	$object    = get_queried_object();
	$taxonomy = $object->taxonomy;
	$real_home = Taxonomies\get_taxos_custom_home( $taxonomy );
	if ( is_wp_error( $real_home ) ) {
		return;
	}

	safe_redirect( $requested_url, $real_home );
}

/**
 * Redirect a date archive to normalised URL.
 *
 * This only fires when the post type is dates. Other date based archives
 * register as post type archives.
 *
 * @param string $requested_url The URL requested by the visitor.
 */
function date_archive_redirect( string $requested_url ) {
	$post_type = 'post';
	$real_home = PostTypes\get_post_types_custom_home( $post_type );
	if ( is_wp_error( $real_home ) ) {
		return;
	}

	safe_redirect( $requested_url, $real_home );
}

/**
 * Redirect to normalised URL safely if needs be.
 *
 * Wrapper for `wp_safe_redirect` with some logic checking.
 *
 * @param string $requested_url URL requested by the visitor.
 * @param string $real_home The preferred home_url for the requested URL.
 */
function safe_redirect( string $requested_url, string $real_home ) {
	// Determine normalised URL
	$normalised_url = MultiDomain\normalise_url( $requested_url, $real_home );

	if ( $requested_url === $normalised_url ) {
		// No need to redirect.
		return;
	}

	wp_safe_redirect( $normalised_url );
}
