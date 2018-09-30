<?php
/**
 * @package     PWCCMultiDomain
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\MultiDomain\Taxonomies;

use PWCC\MultiDomain;
use WP_Error;

/**
 * Initialise the namespace.
 *
 * Runs on the `plugins_loaded` filter.
 */
function bootstrap() {
	add_filter( 'term_link', __NAMESPACE__ . '\\filter_term_link', 10, 3 );
	add_filter( 'paginate_links', __NAMESPACE__ . '\\filter_paginate_links' );
}

/**
 * Array of custom domains used for taxonomies.
 *
 * @return array Taxonomy => custom home URL key/value pairs.
 */
function custom_home_urls() {
	$custom_homes = [];

	/**
	 * Filters the custom home URLs used for post types.
	 *
	 * @param array Taxonomy => custom home URL key/value pairs.
	 */
	$custom_homes = apply_filters( 'pwcc/multi-domain/taxonomies/domains', $custom_homes );

	$custom_homes = array_map( function( $value ) {
		return esc_url_raw( trailingslashit( $value ) );
	}, $custom_homes );

	return $custom_homes;
}

/**
 * Returns the preferred home URL for a taxonomy.
 *
 * @param string $taxonomy The taxonomy.
 * @return string|WP_Error The home URL. WP_Error if unable to determine.
 */
function get_taxos_custom_home( string $taxonomy ) {
	$custom_home_urls = custom_home_urls();

	if ( isset( $custom_home_urls[ $taxonomy ] ) ) {
		return $custom_home_urls[ $taxonomy ];
	}

	if ( isset( $custom_home_urls['DEFAULT'] ) ) {
		return $custom_home_urls['DEFAULT'];
	}

	if ( ! doing_filter( 'home_url' ) ) {
		return get_home_url();
	}

	return new WP_Error(
		'pwcc/multi-domain/taxonomies/unable-to-determine-domain',
		'Unable to determine domain to use for Taxonomy'
	);
}

/**
 * Filter term permalink to preferred use preferred home URL.
 *
 * Runs on the following filters:
 * - term_link
 *
 * @param string $termlink Term link URL.
 * @param \WP_Term $term     Term object.
 * @param string $taxonomy Taxonomy slug.
 */
function filter_term_link( string $termlink, $term, $taxonomy ) {
	$termlink_home = get_taxos_custom_home( $taxonomy );
	if ( is_wp_error( $termlink_home ) ) {
		return $termlink;
	}

	return MultiDomain\normalise_url( $termlink, $termlink_home );
}

/**
 * Filters the paginated links for the taxonomy archive pages.
 *
 * Runs on the `paginate_links` filter
 *
 * @param string $link The paginated link URL.
 * @return string The modified paginated link URL.
 */
function filter_paginate_links( string $link ) {
	if ( ! is_tax() && ! is_category() && ! is_tag() ) {
		// It's handled elsewhere.
		return $link;
	}

	$object    = get_queried_object();
	$taxonomy = $object->taxonomy;
	$real_home = get_taxos_custom_home( $taxonomy );
	if ( is_wp_error( $real_home ) ) {
		return $link;
	}

	return MultiDomain\normalise_url( $link, $real_home );
}
