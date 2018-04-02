<?php
/**
 * PWCC Notes.
 *
 * @package     PWCCNotes
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\Notes\Publishing;

/**
 * Bootstrap Publishing workflow.
 *
 * Runs on same hook as `PWCC\Notes\bootstrap()`.
 */
function bootstrap() {
	add_filter( 'wp_insert_post_data', __NAMESPACE__ . '\\insert_post_data', 10, 2 );
}

/**
 * Add Twitter text to post_content if it is empty for notes.
 *
 * Runs on the `wp_insert_post_data` filter.
 *
 * @todo WP-API support: meta data is not passed to `wp_insert_post()` via WP-API updates.
 * @todo Remove nofollow added to links by `make_clickable()`.
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 * @param array $data    An array of slashed post data.
 * @param array $postarr An array of sanitized, but otherwise unmodified post data.
 * @return array Modified array of slashed post data.
 */
function insert_post_data( array $data, array $postarr ) {
	global $wpdb;
	$data = wp_unslash( $data );

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		// Do not do anything if this is an autosave.
		return wp_slash( $data );
	}

	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		// Or if it's a cron job.
		return wp_slash( $data );
	}

	if ( $postarr['action'] === 'editpost' ) {
		// Or if it's an inline save.
		return wp_slash( $data );
	}

	$post_content = trim( $data['post_content'] );

	if ( $data['post_type'] !== 'pwcc_notes' || $post_content ) {
		// Do nothing if it's not a note or post_content is filled.
		return wp_slash( $data );
	}

	$note_content = trim( $postarr['_pwccindieweb-note']['cmb-group-0']['text']['cmb-field-0'] );
	$attachments = $postarr['_pwccindieweb-note']['cmb-group-0']['images'];

	// Set the post content to the note content.
	$data['post_content'] = $note_content;

	// Links ought to be clickable.
	$data['post_content'] = make_clickable( $data['post_content'] );

	// As should Twitter handles.
	$data['post_content'] = preg_replace(
		'/(^|\s)@([a-z0-9_]+)/i',
		'$1<a href="https://twitter.com/$2">@$2</a>',
		$data['post_content']
	);

	// Images? Append them to the post.
	$gallery = array_filter( $attachments, 'wp_attachment_is_image' );

	if ( ! empty( $gallery ) ) {
		/*
		 * Insert the images as a gallery.
		 *
		 * Images uploaded via the iPhone are often upside down,
		 * inserting this content as a gallery allows the images to be
		 * rotated without the need to edit the post too.
		 */

		$shortcode = '[gallery ';
		$shortcode .= 'columns="1" ';
		$shortcode .= 'size="full" ';
		$shortcode .= 'link="none" ';
		$shortcode .= 'ids="' . join( ',', $gallery ) . '"]';

		$data['post_content'] .= "\n\n${shortcode}";
	}

	// Emojify fields.
	$emoji_fields = [ 'post_content' ];

	foreach ( $emoji_fields as $emoji_field ) {
		if ( isset( $data[ $emoji_field ] ) ) {
			$charset = $wpdb->get_col_charset( $wpdb->posts, $emoji_field );
			if ( $charset === 'utf8' ) {
				$data[ $emoji_field ] = wp_encode_emoji( $data[ $emoji_field ] );
			}
		}
	}

	return wp_slash( $data );
}
