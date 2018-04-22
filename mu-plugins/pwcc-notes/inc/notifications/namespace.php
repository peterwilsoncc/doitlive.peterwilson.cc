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

	// * Get the page.
	// * Check if there are more on the following page.
	//      * If so, set up a task with `max_id` & `ignore_locks` set.
	//      * RETURN without deleting lock.
	// * Process tweets.
	unlock_notifications();
}
