<?php
/**
 * PWCC Helpers.
 *
 * @package     PWCC Helpers
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\Helpers;

/**
 * Fast Bootstrap helpers.
 *
 * These filters are needed before WP completes bootstrapping.
 *
 * Runs as the plugin is included.
 */
function fast_bootstrap() {
	JetpackFixes\fast_bootstrap();
}

/**
 * Bootstrap helpers.
 *
 * Runs on the `plugins_loaded` hook.
 */
function bootstrap() {
	JetpackFixes\bootstrap();
	CavalcadeMods\bootstrap();
	TachyonMods\bootstrap();
	TwentyFourteen\bootstrap();

	if ( ! is_admin() ) {
		add_filter( 'pre_get_posts', __NAMESPACE__ . '\\show_public_preview' );
	}
}

/**
 * Filter the Yoast SEO robots meta tag for public previews.
 *
 * Helper for Public Post Preview plugin.
 *
 * Detects if a page is a public preview using the same logic as the PPP plugin
 * and filters the Yoast SEO robots meta tag to noindex,follow if it is.
 *
 * This runs on the `pre_get_posts` filter but makes no changes to the query.
 *
 * @param WP_Query $query The WP_Query instance.
 * @return WP_Query The unmodified WP_Query instance.
 */
function show_public_preview( $query ) {
	if (
		class_exists( 'DS_Public_Post_Preview' ) &&
		$query->is_main_query() &&
		$query->is_preview() &&
		$query->is_singular() &&
		$query->get( '_ppp' )
	) {
		add_filter( 'wpseo_robots', __NAMESPACE__ . '\\return_noindex_follow' );
	}
	return $query;
}

/**
 * Return the string 'noindex, follow'.
 *
 * @return string The string 'noindex, follow'.
 */
function return_noindex_follow() {
	return 'noindex, follow';
}
