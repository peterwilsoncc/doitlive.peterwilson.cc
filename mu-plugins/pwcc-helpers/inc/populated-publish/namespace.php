<?php
/**
 * PWCC Helpers.
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
	add_filter( 'wp_insert_post_empty_content', __NAMESPACE__ . '\\maybe_empty_return', PHP_INT_MAX );
}

/**
 * Register hooks required for all post types.
 *
 * @param string        $post_type        Post type.
 * @param \WP_Post_Type $post_type_object Arguments used to register the post type.
 */
function post_type_rego_action( $post_type, $post_type_object ) {
	// Runs late to ensure another plugin hasn't filtered to an error.
	add_filter( "rest_pre_insert_{$post_type}", __NAMESPACE__ . '\\pre_insert_in_rest', PHP_INT_MAX );
}

/**
 * Activate and deactivate "in rest" for a prepared post.
 *
 * @param \stdClass $prepared_post An object representing a single post prepared
 *                                 for inserting or updating the database.
 *
 * @return \stdClass $prepared_post Unmodified object.
 */
function pre_insert_in_rest( $prepared_post ) {
	if ( is_wp_error( $prepared_post ) ) {
		return $prepared_post;
	}

	is_rest( true );

	$filter = function( $response ) use ( &$filter ) {
		remove_filter( 'rest_request_after_callbacks', $filter );
		is_rest( false );
		return $response;
	};

	add_filter( 'rest_request_after_callbacks', $filter );

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

	return $in_rest === 0;
}
