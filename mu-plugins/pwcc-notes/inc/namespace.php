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
	add_filter( 'cmb_meta_boxes', __NAMESPACE__ . '\\register_meta_boxes' );
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
 * Register meta boxes using CMB2.
 *
 * Runs on the `cmb_meta_boxes` hook.
 *
 * @param array $meta_boxes Registered meta boxes.
 * @return array Modified meta boxes.
 */
function register_meta_boxes( array $meta_boxes ) {
	return $meta_boxes;
}
