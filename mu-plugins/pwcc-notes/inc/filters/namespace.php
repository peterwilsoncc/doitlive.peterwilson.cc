<?php
/**
 * PWCC Notes.
 *
 * @package     PWCCNotes
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */

namespace PWCC\Notes\Filters;

/**
 * Set up filters.
 */
function bootstrap() {
	add_filter( 'the_title', __NAMESPACE__ . '\\filter_the_title', 10, 2  );
	add_filter( 'wpseo_title', __NAMESPACE__ . '\\filter_wpseo_title', 10 );

	add_filter( 'get_comment_author_url', __NAMESPACE__ . '\\filter_comment_author_url', 10, 2 );
}

/**
 * Filter the post title.
 *
 * Runs on the filter `the_title`.
 *
 * @param string $title   The post title.
 * @param int    $post_id The post ID.
 * @return string The modified post title.
 */
function filter_the_title( $title, $post_id ) {
	$post = get_post( $post_id );

	if ( $post->post_type !== 'pwcc_notes' ) {
		return $title;
	}

	if ( $title === '' ) {
		$title .= 'Noted ';

		// Date it
		$title .= get_the_date( get_option( 'date_format' ), $post->ID );
	}

	return $title;
}

/**
 * Modify the post title displayed by Yoast SEO.
 *
 * Runs on the filter `wpseo_title`.
 *
 * @param string $title The post title displayed in the `title` element.
 * @return string The modified post title.
 */
function filter_wpseo_title( $title ) {
	if ( ! function_exists( 'wpseo_replace_vars' ) ) {
		// This should never happen, the filter is fired by Yoast SEO.
		return $title;
	}

	if ( ! is_singular( 'pwcc_notes' ) ) {
		// Only do this on single notes pages.
		return $title;
	}

	// Get the current post.
	$post = get_post();

	/*
	 * The post has a title if the complete SEO replacement differs from
	 * the incomplete SEO replacement.
	 */
	if ( wpseo_replace_vars( '%%page%% %%sep%% %%sitename%%', $post ) !== $title ) {
		return $title;
	}

	$pwcc_title = '';
	$pwcc_title .= 'Noted ';
	$pwcc_title .= get_the_date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) );

	$title = wpseo_replace_vars( $pwcc_title . ' %%page%% %%sep%% %%sitename%%', $post );

	return $title;
}

/**
 * Filter the author URL for the meta data URL if it exists.
 *
 * This is due to the now disabled semantic linkbacks plugin as
 * it stores the author's URL as meta data and the bridgy link
 * as in the comment table's field.
 *
 * Runs on the filter `get_comment_author_url`.
 *
 * @param string $url        The comment author's URL.
 * @param int    $comment_id The comment ID.
 * @return mixed|string The modified comment author's URL.
 */
function filter_comment_author_url( string $url, int $comment_id ) {
	$meta_url = get_comment_meta(
		$comment_id,
		'semantic_linkbacks_author_url',
		true
	);

	if ( ! $meta_url ) {
		return $url;
	}

	return esc_url( $meta_url, [ 'http', 'https' ] );
}
