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
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_assets' );

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
