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
	add_action( 'cmb2_admin_init', __NAMESPACE__ . '\\register_meta_boxes' );
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
 * Runs on the `cmb2_admin_init` hook.
 */
function register_meta_boxes() {
	$cmb = new_cmb2_box( [
		'id'           => 'pwccindieweb_notes_metabox',
		'title'        => __( 'Notes', 'pwcc' ),
		'object_types' => [ 'pwcc_notes', 'post', 'page' ],
		'context'      => 'normal',
		'priority'     => 'high',
	] );

	$group = $cmb->add_field( [
		'name'       => esc_html__( 'Twitter', 'pwcc' ),
		'desc'       => esc_html__( 'Post to Twitter', 'pwcc' ),
		'id'         => '_pwccindieweb-note',
		'type'       => 'group',
		'classes'    => 'pwccindieweb-twitter',
		'repeatable' => false,
	] );

	$cmb->add_group_field(
		$group,
		[
			'name' => __( 'Text', 'pwcc' ),
			'id' => 'text',
			'type' => 'textarea_small',
			'classes' => 'pwccindieweb-note-text',
		]
	);

	$cmb->add_group_field(
		$group,
		[
			'name'    => __( 'Post on twitter', 'pwcc' ),
			'id'      => 'post_on_twitter',
			'type'    => 'radio_inline',
			'options' => [
				'1'  => esc_html__( 'Yes', 'pwcc' ),
				'no' => esc_html__( 'No', 'pwcc' ),
			],
			'default' => 'no',
			'classes' => 'pwccindieweb-note-post-on-twitter',
		]
	);

	$cmb->add_group_field(
		$group,
		[
			'name'    => __( 'Append URL to post', 'pwcc' ),
			'id'      => 'append_url',
			'type'    => 'radio_inline',
			'options' => [
				'1'  => esc_html__( 'Yes', 'pwcc' ),
				'no' => esc_html__( 'No', 'pwcc' ),
			],
			'default' => 'no',
			'classes' => 'pwccindieweb-note-append-url',
		]
	);

	$cmb->add_group_field(
		$group,
		[
			'name'    => __( 'Images to include', 'pwcc' ),
			'id'      => 'images',
			'type'    => 'file_list',
			'classes' => 'pwccindieweb-note-images',
		]
	);
}
