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

use PWCC\Notes;

/**
 * Bootstrap Publishing workflow.
 *
 * Runs on same hook as `PWCC\Notes\bootstrap()`.
 */
function bootstrap() {
	add_filter( 'wp_insert_post_data', __NAMESPACE__ . '\\insert_post_data', 10, 2 );
	add_action( 'wp_after_insert_post', __NAMESPACE__ . '\\publish_post', 10, 2 );

	add_action( 'pwcc/notes/tweet/text', __NAMESPACE__ . '\\tweet_update' );
	add_action( 'pwcc/notes/tweet/image', __NAMESPACE__ . '\\upload_image_to_twitter' );
	add_action( 'pwcc/notes/tweet/image/expired', __NAMESPACE__ . '\\delete_image_twitter_id' );
}

/**
 * Add Twitter text to post_content if it is empty for notes.
 *
 * Runs on the `wp_insert_post_data` filter.
 *
 * @todo WP-API support: meta data is not passed to `wp_insert_post()` via WP-API updates.
 * @todo Remove nofollow added to links by `make_clickable()`.
 * @todo Make this better and work with scheduled posts and publishing from bulk edit.
 *
 * @global \wpdb $wpdb WordPress database abstraction object.
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

	if ( ! isset( $postarr['action'] ) || $postarr['action'] !== 'editpost' ) {
		// Or if it's not saving from the edit form.
		return wp_slash( $data );
	}

	$post_content = trim( $data['post_content'] );

	if ( $data['post_type'] !== 'pwcc_notes' || $post_content ) {
		// Do nothing if it's not a note or post_content is filled.
		return wp_slash( $data );
	}

	if ( ! isset( $postarr['_pwccindieweb-note']['cmb-group-0'] ) ) {
		// Somethings gone wrong.
		return wp_slash( $data );
	}

	$note_content = trim( $postarr['_pwccindieweb-note']['cmb-group-0']['text']['cmb-field-0'] );
	$attachments = $postarr['_pwccindieweb-note']['cmb-group-0']['images'];

	// Set the post content to the note content.
	$data['post_content'] = wp_unslash( $note_content );

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

/**
 * Tweet when the post is published.
 *
 * Set up the required cron jobs for publishing a tweet.
 *
 * Runs on the `wp_insert_post` action.
 *
 * @todo Fix for Gutenberg
 * @todo Fix hackyity delay of scheduling tweeting text.
 * @todo Allow for sites that don't have Cavalcade, Cron Control or another cron runner.
 *
 * @param int      $post_id Post ID.
 * @param \WP_Post $post    Post object.
 */
function publish_post( $post_id, $post ) {
	if ( $post->post_status !== 'publish' ) {
		// Unpublished post. Do nothing.
		return;
	}

	if ( ! Notes\can_tweet() ) {
		// Tweeting is not possible.
		return;
	}

	if ( get_post_meta( $post_id, 'twitter_id', true ) ) {
		// This has been tweeted before. Do nothing.
		return;
	}

	$tweet = get_post_meta( $post_id, '_pwccindieweb-note', true );

	// Meta data is not set on `publish_post` via WP-API.
	if ( ! is_array( $tweet ) ) {
		// Meta data is not in an expected format.
		return;
	}

	if ( $tweet['post_on_twitter'] !== '1' ) {
		// Flagged do not tweet. Do nothing.
		return;
	}

	// Check what the post contains.
	$has_text = (bool) trim( $tweet['text'] ) || ( $tweet['append_url'] === '1' );
	$images = array_filter( $tweet['images'], 'wp_attachment_is_image' );
	$has_images = ! empty( $images );

	if ( ! $has_text && ! $has_images ) {
		// Got nothing for ya.
		return;
	}

	/*
	 * This process presumes a cron runner.
	 *
	 * - Check if in process of being tweeted.
	 * - Setup images to upload
	 * - Set up tweet.
	 */

	$args = [
		'post_id' => $post_id,
	];

	$next_scheduled = wp_next_scheduled(
		'pwcc/notes/tweet/timeout',
		[ $args ]
	);

	if ( $next_scheduled ) {
		// Tweeting in progress
		return;
	}

	$time = time();
	if ( $has_images ) {
		// Give images a few seconds to be uploaded.
		$time += 15;
	}

	wp_schedule_single_event(
		$time + ( 4 * MINUTE_IN_SECONDS ),
		'pwcc/notes/tweet/timeout',
		[ $args ]
	);

	wp_schedule_single_event(
		$time,
		'pwcc/notes/tweet/text',
		[ $args ]
	);

	foreach ( $images as $image_id ) {
		$args['image_id'] = $image_id;
		wp_schedule_single_event(
			time(),
			'pwcc/notes/tweet/image',
			[ $args ]
		);
		unset( $args['image_id'] );
	}
}

/**
 * Upload an update to the Twitter API.
 *
 * @param array $args Arguments array containing post_id.
 * @return bool Success/failure.
 */
function tweet_update( $args ) {
	$post_id = $args['post_id'];
	$status_update = [];

	$tweet = get_post_meta( $post_id, '_pwccindieweb-note', true );

	if ( $tweet['post_on_twitter'] !== '1' ) {
		// Flagged do not tweet. Do nothing.
		return true;
	}

	// Check what the post contains.
	$has_text = (bool) trim( $tweet['text'] ) || ( $tweet['append_url'] === '1' );
	$images = array_filter( $tweet['images'], 'wp_attachment_is_image' );
	$has_images = ! empty( $images );

	if ( ! $has_text && ! $has_images ) {
		// Nothing to tweet, call it done.
		return true;
	}

	$retry = false;

	if ( $has_images ) {
		// Check the images have uploaded.
		foreach ( $images as $key => $image ) {
			$twitter_id = get_post_meta( $post_id, '_pwccindieweb-twimg-' . intval( $image ), true );
			if ( $twitter_id && $twitter_id !== '-1' ) {
				$images[ $key ] = $twitter_id;
				unset( $twitter_id );
				continue;
			} elseif ( $twitter_id !== '-1' ) {
				// Still waiting for upload.
				$retry = true;
			}
		}
		$status_update['media_ids'] = join( ',', $images );
	}

	if ( $retry ) {
		// Images have not uploaded, schedule retry.
		$timeout_stamp = wp_next_scheduled(
			'pwcc/notes/tweet/timeout',
			[ $args ]
		);

		if ( $timeout_stamp ) {
			// Retry in 15 seconds.
			wp_schedule_single_event(
				time() + 30,
				'pwcc/notes/tweet/text',
				[ $args ]
			);
			return true;
		}

		if ( ! $status_update['media_ids'] ) {
			// No images have uploaded. Let it be.
			return false;

		}
	}

	// Setup status update to send to Twitter.
	$status_update['status'] = trim( $tweet['text'] );

	if ( $tweet['append_url'] === '1' ) {
		$status_update['status'] .= ' ' . get_permalink( $post_id );
	}

	$connection = Notes\twitter_connection();
	$response = $connection->post( 'statuses/update', $status_update );

	if ( $connection->getLastHttpCode() === 200 ) {
		$twitter_id = $response->id_str;
		$twitter_user = $response->user->screen_name;
		$twitter_url = esc_url_raw( "https://twitter.com/${twitter_user}/status/${twitter_id}" );

		update_post_meta( $post_id, 'twitter_id', wp_slash( $twitter_id ) );
		update_post_meta( $post_id, 'twitter_permalink', wp_slash( $twitter_url ) );

		return true;
	}
	return false;
}

/**
 * Upload an image to the Twitter API.
 *
 * @param array $args { Post ID and Image ID }
 * @return bool Whether function was successful.
 */
function upload_image_to_twitter( array $args ) {
	$post_id  = $args['post_id'];
	$image_id = $args['image_id'];

	$tweet = get_post_meta( $post_id, '_pwccindieweb-note', true );

	if ( $tweet['post_on_twitter'] !== '1' ) {
		// Flagged do not tweet. Do nothing.
		return true;
	}

	$twitter_id = get_post_meta( $post_id, '_pwccindieweb-twimg-' . intval( $image_id ), true );
	if ( $twitter_id ) {
		// The image has been uploaded.
		return true;
	}

	$file = get_attached_file( $image_id );
	if ( ! file_exists( $file ) ) {
		// @todo fail gracefully.
		return false;
	}

	switch ( get_post_mime_type( $image_id ) ) {
		case 'image/gif':
			$max_file_size = 15 * 1000 * 1000; // 15 MB
			break;
		default:
			$max_file_size = 5 * 1000 * 1000; // 5 MB
			break;
	}

	if ( filesize( $file ) > $max_file_size ) {
		/*
		 * File is too large.
		 *
		 * Update post meta with a magic number.
		 */
		update_post_meta(
			$post_id,
			wp_slash( '_pwccindieweb-twimg-' . intval( $image_id ) ),
			wp_slash( '-1' )
		);
		return false;
	}
	$connection = Notes\twitter_connection();
	$image_upload = $connection->upload(
		'media/upload',
		[
			'media' => $file,
		]
	);

	if ( $connection->getLastHttpCode() !== 200 ) {
		return false;
	}

	/*
	 * Get expiry time for uploaded meda.
	 *
	 * This is used to delete the image ID one minute before Twitter
	 * expires it for use with new tweets.
	 */
	$expiry_time = time() + $image_upload->expires_after_secs - 60;

	update_post_meta(
		$post_id,
		wp_slash( '_pwccindieweb-twimg-' . intval( $image_id ) ),
		wp_slash( $image_upload->media_id_string )
	);

	// @todo remove this entry after the media expires for use with new tweets.
	wp_schedule_single_event(
		$expiry_time,
		'pwcc/notes/tweet/image/expired',
		[ $args ]
	);

	return true;
}

/**
 * Delete expired twitter image ID meta data from post.
 *
 * @param array $args { Post ID and Image ID }
 */
function delete_image_twitter_id( $args ) {
	$post_id  = $args['post_id'];
	$image_id = $args['image_id'];

	delete_post_meta( $post_id, wp_slash( '_pwccindieweb-twimg-' . intval( $image_id ) ) );
}
