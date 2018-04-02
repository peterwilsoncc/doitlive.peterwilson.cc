<?php
/**
 * PWCC Notes.
 *
 * @package     PWCCNotes
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\Notes;

use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Bootstrap notes.
 *
 * Runs on the `plugins_loaded` hook.
 */
function bootstrap() {
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		// Extended Post Types plugin is not enabled. Panic.
		exit;
	}
	add_action( 'init', __NAMESPACE__ . '\\register_cpt' );
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_assets' );
	add_filter( 'wp_insert_post_empty_content', __NAMESPACE__ . '\\post_empty_content', 10, 2 );

	// Bootstrap sub components.
	MetaBoxes\bootstrap();
	Publishing\bootstrap();
}

/**
 * Register Note CPT.
 *
 * Runs on the `init` hook.
 */
function register_cpt() {
	register_extended_post_type(
		'pwcc_notes',
		[
			'description'     => __( 'Short, Twitter like, notes.', 'pwcc' ),
			'capability_type' => 'post',
			'hierarchical'    => false,
			'supports'        => [ 'comments', 'trackbacks', 'author', 'editor', 'title', 'post-formats' ],
			'has_archive'     => true,
		],
		[
			'singular' => __( 'Note', 'pwcc' ),
			'plural'   => __( 'Notes', 'pwcc' ),
			'slug'     => '~',
		]
	);
}

/**
 * Enqueue assets for use in the admin.
 *
 * Runs on the `admin_enqueue_scripts` hook.
 */
function enqueue_admin_assets( $hook_name ) {
	wp_register_script(
		'pwcc-notes-twttr-text',
		plugin_dir_url( __DIR__ ) . 'assets/js/twitter-text.min.js',
		[],
		'2.0.5',
		true
	);

	wp_register_script(
		'pwcc-notes',
		plugin_dir_url( __DIR__ ) . 'assets/js/notes.js',
		[ 'jquery', 'pwcc-notes-twttr-text' ],
		'20180401.001',
		true
	);

	if ( ! in_array( $hook_name, [ 'post-new.php', 'post.php' ], true ) ) {
		// Only register scripts, do not enqueue.
		return;
	}

	wp_enqueue_script( 'pwcc-notes' );
}

/**
 * Include Twitter field in maybe_empty check for Notes.
 *
 * A `pwcc_notes` post type is not considered empty if the
 * Twitter field has content.
 *
 * Runs on the `wp_insert_post_empty_content` filter.
 *
 * @todo WP-API support: meta data is not passed to `wp_insert_post` via WP-API updates.
 *
 * @param  bool  $is_empty Whether the post should be considered "empty".
 * @param  array $postarr  Array of post data.
 * @return bool Modified calculation of whether post should be considered "empty".
 */
function post_empty_content( bool $is_empty, array $postarr ) {
	if ( ! $is_empty || $postarr['post_type'] !== 'pwcc_notes' ) {
		// Post is already known to have content or is not a note.
		return $is_empty;
	}

	// Put relevant content in variables.
	$note_content = trim( $postarr['_pwccindieweb-note']['cmb-group-0']['text']['cmb-field-0'] );

	// Extend maybe empty check.
	$is_empty = $is_empty && ! $note_content;

	return (bool) $is_empty;
}

/**
 * Is it possible to tweet?
 *
 * @return bool Whether constants are set for tweeting.
 */
function can_tweet() {
	return defined( 'PWCC_TWTTR_CONSUMER_KEY' )
		&& defined( 'PWCC_TWTTR_CONSUMER_SECRET' )
		&& defined( 'PWCC_TWTTR_ACCESS_TOKEN' )
		&& defined( 'PWCC_TWTTR_ACCESS_SECRET' );
}

/**
 * Connect to the Twitter API.
 *
 * @return TwitterOAuth Authenticated Twitter connection.
 */
function twitter_connection() {
	return new TwitterOAuth(
		PWCC_TWTTR_CONSUMER_KEY,
		PWCC_TWTTR_CONSUMER_SECRET,
		PWCC_TWTTR_ACCESS_TOKEN,
		PWCC_TWTTR_ACCESS_SECRET
	);
}
