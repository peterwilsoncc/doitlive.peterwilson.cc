<?php
/**
 * PWCC Helpers, populated publishing hooks.
 *
 * Ensure the post-publish related hooks only run when the term and meta
 * data has been saved by prefixing them with `populated/`.
 *
 * If in a rest request, the actions are fired at the conclusion of
 * the rest request on the `rest_request_after_callbacks` filter.
 *
 * Only a subset of post-publish hooks fire, the selection is limited
 * to those that contain the most data.
 *
 * Populated versions of the following actions fire:
 * - transition_post_status
 * - attachment_updated
 * - add_attachment
 * - wp_insert_post
 *
 * @package     PWCC Helpers
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */
namespace PWCC\Helpers\PopulatedPublish;

/**
 * Bootstrap populated publishing hooks.
 */
function bootstrap() {
	add_action( 'registered_post_type', __NAMESPACE__ . '\\post_type_rego_action', 10, 2 );

	add_action( 'transition_post_status', __NAMESPACE__ . '\\populated_transition_post_status', 10, 3 );
	add_action( 'attachment_updated', __NAMESPACE__ . '\\populated_post_updated', 10, 3 );
	add_action( 'post_updated', __NAMESPACE__ . '\\populated_post_updated', 10, 3 );
	add_action( 'add_attachment', __NAMESPACE__ . '\\populated_add_attachment' );
	add_action( 'wp_insert_post', __NAMESPACE__ . '\\populated_insert_post', 10, 3 );
}

/**
 * Register hooks required for all post types.
 *
 * Runs on the action `registered_post_type`.
 *
 * @param string        $post_type        Post type.
 * @param \WP_Post_Type $post_type_object Arguments used to register the post type.
 */
function post_type_rego_action( $post_type, $post_type_object ) {
	if ( $post_type_object->show_in_rest === false ) {
		// The hook doesn't exist for it to run on.
		return;
	}

	// Runs late to ensure another plugin hasn't filtered to an error.
	add_filter( "rest_pre_insert_{$post_type}", __NAMESPACE__ . '\\pre_insert_in_rest', PHP_INT_MAX, 2 );
}

/**
 * Activate and deactivate "in rest" for a prepared post.
 *
 * Runs on the filter `rest_pre_insert_{$post_type}`.
 *
 * @param \stdClass        $prepared_post An object representing a single post prepared
 *                                        for inserting or updating the database.
 * @param \WP_REST_Request $pre_request   Request object.
 *
 * @return \stdClass $prepared_post Unmodified object.
 */
function pre_insert_in_rest( $prepared_post, $pre_request ) {
	if ( is_wp_error( $prepared_post ) ) {
		return $prepared_post;
	}

	is_rest( true );

	/**
	 * Deactivate "in rest" after the callbacks have fired.
	 *
	 * Runs on the filter `rest_request_after_callbacks`.
	 *
	 * @param \WP_HTTP_Response $response Result to send to the client. Usually a WP_REST_Response.
	 * @param \WP_REST_Server   $handler  ResponseHandler instance (usually WP_REST_Server).
	 * @param \WP_REST_Request  $request  Request used to generate the response.
	 *
	 * @return \WP_HTTP_Response Unmodified response.
	 */
	$filter = function( $response, $handler, $request ) use ( &$filter, $pre_request ) {
		if ( $request !== $pre_request ) {
			// Callback for another internal rest request.
			return $response;
		}

		do_action( 'populated/fire_callbacks/' . is_rest() );
		remove_filter( 'rest_request_after_callbacks', $filter, PHP_INT_MAX );
		is_rest( false );
		return $response;
	};

	add_filter( 'rest_request_after_callbacks', $filter, PHP_INT_MAX, 3 );

	return $prepared_post;
}

/**
 * Determine if the current request is a rest request.
 *
 * @param bool|null $toggle Whether to toggle in/out of "in rest" state.
 *
 * @return bool Whether we are in a rest request.
 */
function is_rest( $toggle = null ) {
	static $in_rest = 0;

	if ( $toggle === true ) {
		$in_rest++;
	} elseif ( $toggle === false && $in_rest > 0 ) {
		$in_rest--;
	}

	// Safety.
	if ( $in_rest < 0 ) {
		$in_rest = 0;
	}

	return $in_rest;
}

/**
 * Set up populated post transition hooks.
 *
 * Runs on the `transition_post_status` action.
 *
 * @param string   $new_status New post status.
 * @param string   $old_status Old post status.
 * @param \WP_Post $post       Post object.
 */
function populated_transition_post_status( $new_status, $old_status, $post ) {
	if ( ! is_rest() || ! get_post_type_object( get_post_type( $post ) )->show_in_rest ) {
		do_action( 'populated/transition_post_status', $new_status, $old_status, $post );
		return;
	}

	$rest_id = is_rest();

	/**
	 * Fire the hooks once the callback has finished firing.
	 *
	 * Runs on the action `populated/fire_callbacks/{$rest_id}`.
	 */
	$closure = function() use ( &$closure, $new_status, $old_status, $post, $rest_id ) {
		remove_action( "populated/fire_callbacks/{$rest_id}", $closure );
		do_action( 'populated/transition_post_status', $new_status, $old_status, $post );
	};

	add_action( "populated/fire_callbacks/{$rest_id}", $closure );
}

/**
 * Setup populated "edit" and "updated" hooks.
 *
 * Runs on the `attachment_updated` and `post_updated` actions.
 *
 * @param int      $post_id      Post ID.
 * @param \WP_Post $post_after   Post object following the update.
 * @param \WP_Post $post_before  Post object before the update.
 */
function populated_post_updated( $post_id, $post_after, $post_before ) {
	if ( $post_after->post_type === 'attachment' ) {
		$type = 'attachment';
	} else {
		$type = 'post';
	}

	if ( ! is_rest() || ! get_post_type_object( get_post_type( $post_id ) )->show_in_rest ) {
		do_action( "populated/{$type}_updated", $post_id, $post_after, $post_before );
		return;
	}

	$rest_id = is_rest();

	/**
	 * Fire the hooks once the callback has finished firing.
	 *
	 * Runs on the action `populated/fire_callbacks/{$rest_id}`.
	 */
	$closure = function() use ( &$closure, $post_id, $post_after, $post_before, $type, $rest_id ) {
		remove_action( "populated/fire_callbacks/{$rest_id}", $closure );
		do_action( "populated/{$type}_updated", $post_id, $post_after, $post_before );
	};

	add_action( "populated/fire_callbacks/{$rest_id}", $closure );
}

/**
 * Set up the populated add attachment hooks.
 *
 * Runs on the `add_attachment` action.
 *
 * @param int $post_id Attachment ID.
 */
function populated_add_attachment( $post_id ) {
	if ( ! is_rest() ) {
		do_action( 'populated/add_attachment', $post_id );
		return;
	}

	$rest_id = is_rest();

	/**
	 * Fire the hooks once the callback has finished firing.
	 *
	 * Runs on the action `populated/fire_callbacks/{$rest_id}`.
	 */
	$closure = function() use ( &$closure, $post_id, $rest_id ) {
		remove_action( "populated/fire_callbacks/{$rest_id}", $closure );
		do_action( 'populated/add_attachment', $post_id );
	};

	add_action( "populated/fire_callbacks/{$rest_id}", $closure );
}

/**
 * Set up populated insert post hooks.
 *
 * Runs on the `wp_insert_post` action.
 *
 * @param int      $post_id Post ID.
 * @param \WP_Post $post    Post object.
 * @param bool     $update  Whether this is an existing post being updated or not.
 */
function populated_insert_post( $post_id, $post, $update ) {
	if ( ! is_rest() || ! get_post_type_object( get_post_type( $post_id ) )->show_in_rest ) {
		do_action( 'populated/wp_insert_post', $post_id, $post, $update );
		return;
	}

	$rest_id = is_rest();

	/**
	 * Fire the hooks once the callback has finished firing.
	 *
	 * Runs on the action `populated/fire_callbacks/{$rest_id}`.
	 */
	$closure = function() use ( &$closure, $post_id, $post, $update, $rest_id ) {
		remove_action( "populated/fire_callbacks/{$rest_id}", $closure );
		do_action( 'populated/wp_insert_post', $post_id, $post, $update );
	};

	add_action( "populated/fire_callbacks/{$rest_id}", $closure );
}
