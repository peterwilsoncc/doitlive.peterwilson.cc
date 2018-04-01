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
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\register_admin_assets' );

	// Bootstrap sub components.
	MetaBoxes\bootstrap();
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
			'description'     => 'Short, Twitter like, notes.',
			'capability_type' => 'post',
			'hierarchical'    => false,
			'supports'        => [ 'comments', 'trackbacks', 'author', 'editor', 'title', 'post-formats' ],
			'has_archive'     => true,
		],
		[
			'singular' => 'Note',
			'plural'   => 'Notes',
			'slug'     => '~',
		]
	);
}

/**
 * Register assets for use in the admin.
 *
 * Runs on the `admin_enqueue_scripts` hook.
 */
function register_admin_assets() {
	wp_register_script(
		'pwcc-notes-twttr-text',
		plugin_dir_url( __DIR__ ) . 'assets/js/twitter-text.min.js',
		[],
		'2.0.5',
		true
	);
}
