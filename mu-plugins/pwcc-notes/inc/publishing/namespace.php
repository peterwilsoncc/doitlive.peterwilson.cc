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
	add_action( 'wp_insert_post', __NAMESPACE__ . '\\publish_post', 10, 2 );

	add_action( 'pwcc/notes/tweet/text', __NAMESPACE__ . '\\tweet_update' );
}

/**
 * Add Twitter text to post_content if it is empty for notes.
 *
 * Runs on the `wp_insert_post_data` filter.
 *
 * @todo WP-API support: meta data is not passed to `wp_insert_post()` via WP-API updates.
 * @todo Remove nofollow added to links by `make_clickable()`.
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

/**
 * Tweet when the post is published.
 *
 * Set up the required cron jobs for publishing a tweet.
 *
 * Runs on the `wp_insert_post` action.
 *
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
		$time += 10;
	}

	wp_schedule_single_event(
		$time + 120,
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
 * @param $args Arguments array containing post_id.
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
			if ( $twitter_id ) {
				$images[ $key ] = $twitter_id;
				unset( $twitter_id );
				continue;
			}
			$retry = true;
			break;
		}
		$status_update['media_ids'] = join( ',', $images );
	}

	if ( $retry ) {
		// Images have not uploaded, schedule retry.
		$timeout_stamp = wp_next_scheduled(
			'pwcc/notes/tweet/timeout',
			[
				[
					'post_id' => $post_id,
				]
			]
		);

		if ( ! $timeout_stamp ) {
			// Time to attempt tweeting has expired.
			return false;
		}

		// Retry in 15 seconds.
		wp_schedule_single_event(
			time() + 15,
			'pwcc/notes/tweet/text',
			[ $args ]
		);

		return true;
	}

	// Setup status update to send to Twitter.
	$status_update['status'] = trim( $tweet['text'] );

	if ( $tweet['append_url'] === '1' ) {
		$status_update['status'] .= ' ' . get_permalink( $post_id );
	}

	$connection = Notes\twitter_connection();
	$response = $connection->post( "statuses/update", $status_update );

	if ( $connection->getLastHttpCode() === 200 ) {
		$twitter_id = $response->id_str;
		$twitter_user = $response->user->screen_name;
		$twitter_url = "https://twitter.com/" . $twitter_user . "/status/" . $twitter_id;

		update_post_meta( $post_id, 'twitter_id', $twitter_id );
		update_post_meta( $post_id, 'twitter_permalink', $twitter_url );

		return true;
	}
	return false;
}
