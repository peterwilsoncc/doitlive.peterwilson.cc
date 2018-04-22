<?php
/**
 * PWCC Notes.
 *
 * @package     PWCCNotes
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\Notes\Notifications;

use PWCC\Notes;

/**
 * Option name to indicating processing is in progress.
 */
const LOCKING_OPTION = 'pwcc_notes_notifications_lock';

/**
 * Option name of lowest tweet ID to return.
 */
const SINCE_ID_OPTION = 'pwcc_notes_notifications_since_id';

/**
 * Option name of maximum tweet ID to return.
 */
const MAX_ID_OPTION = 'pwcc_notes_notifications_max_id';

/**
 * Bootstrap notifications
 */
function bootstrap() {
	read_notifications();
}

/**
 * Lock the notifications to avoid double processing.
 *
 * @return bool True if succesfully locked, false if already locked/failed.
 */
function lock_notifications() {
	return update_option( LOCKING_OPTION, 'locked' );
}

/**
 * Unlock notifications processing.
 */
function unlock_notifications() {
	delete_option( LOCKING_OPTION );
}

/**
 * Parse the notifications timeline.
 *
 * Runs on the action `pwcc/notes/notifications/twitter`.
 *
 * @param array $args {
 *      An arguments array.
 *
 *      @type int    $count            Number of tweets to return. Default 200.
 *      @type string $since_id         ID of lowest number tweet to return.
 *      @type int    $trim_user        1 to trim users, 0 (default) to include.
 *      @type int    $include_entities 1 (default) to include entities, 0 to exclude.
 *      @type string $tweet_mode       compact or extended.
 *      @type int    $include_rts      1 (default) include retweets, 0 do not.
 *      @type bool   $ignore_locks     Wheterh to ignore locking mechanism.
 * }
 * @return bool True successful, false failure.
 */
function read_notifications( array $args = [] ) {
	$defaults = [
		'count' => 200,
		'since_id' => '985426733556940800',
		'trim_user' => 0,
		'include_entities' => 1,
		'tweet_mode' => 'extended',
		'include_rts' => 1,
		'ignore_locks' => false,
	];

	$args = wp_parse_args( $args, $defaults );
	$ignore_locks = $args['ignore_locks'];
	// Ignore locks is only used by this function.
	unset( $args['ignore_locks'] );

	if ( ! lock_notifications() && ! $ignore_locks ) {
		// Locked and we're not ignoring them.
		return false;
	}

	$connection = Notes\twitter_connection();

	// Get the page.
	$response = $connection->get( 'statuses/mentions_timeline', $args );

	if ( $connection->getLastHttpCode() !== 200 ) {
		// Failed, do nothing and wait for next attempt.
		unlock_notifications();
		return false;
	}

	if ( empty( $response ) ) {
		// Succeeded, nothing to process.
		unlock_notifications();
		return true;
	}

	// Check if there are more on the following page.
	$last_reply = end( $response );
	$last_reply_id = $last_reply->id;

	if ( $last_reply_id === (int) $args['since_id'] ) {
		$is_next_page = false;
	} else {
		$is_next_page = is_next_page(
			$args,
			$last_reply_id - 1,
			'statuses/mentions_timeline'
		);
	}

	if ( $is_next_page ) {
		// Set up a task with `max_id` & `ignore_locks` set.
		$next_page_args = [
			'ignore_locks' => true,
			'max_id' => $last_reply_id - 1,
		];
		$next_page_args = wp_parse_args( $next_page_args, $args );
		// @todo set up task to process.
		// RETURN without deleting lock.
	}

	$time = time();
	unlock_notifications();

	var_dump( $response );

	while ( ! empty( $response ) ) {
		$processing = array_pop( $response );
		/*
		 * @todo
		 *  - process entities.
		 *  - include images (hot link or sideload???) [extended entities->media]
		 *  -
		 */
		$comment_args = [
			'status_id' => $processing->id_str,
			'content' => $processing->full_text,
			'status_reply_to' => $processing->in_reply_to_status_id_str,
			'time' => $processing->created_at,
			'author' => [
				'display_name' => $processing->user->name,
				'url' => $processing->user->url,
				'avatar' => $processing->user->profile_image_url_https,
			],
		];

		var_dump( $comment_args );
	}
	exit;

	// * Process tweets.

	exit;
}

/**
 * Whether or not there is a next page of replies.
 *
 * @param array  $args     @see {read_notifications}.
 * @param int    $max_id   Maximum ID to check for.
 * @param string $timeline Timeline to check. Default 'statuses/mentions_timeline'.
 * @return bool|null Whether or not there is a next page. Null if check failed.
 */
function is_next_page( array $args, int $max_id, string $timeline = 'statuses/mentions_timeline' ) {
	$overrides = [
		'count' => 1,
		'trim_user' => 1,
		'include_entities' => 0,
		'tweet_mode' => 'compact',
		'max_id' => $max_id,
	];

	$args = wp_parse_args( $overrides, $args );

	$connection = Notes\twitter_connection();

	// Get the page.
	$response = $connection->get( $timeline, $args );

	if ( $connection->getLastHttpCode() !== 200 ) {
		// Failed, do nothing and wait for next attempt.
		return null;
	}

	// There is a next page if the array contains data.
	return ! empty( $response );
}
