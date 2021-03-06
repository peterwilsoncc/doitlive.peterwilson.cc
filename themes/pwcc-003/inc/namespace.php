<?php
namespace PWCC\Theme;

use HM\Asset_Loader;

function bootstrap() {
	theme_setup();
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue' );

	HTML_Header\bootstrap();
	Navigation\bootstrap();
}

/**
 * Get the version of this theme.
 *
 * If the value returned is a placeholder, use the current time.
 *
 * @return int|string The plugin version.
 */
function get_theme_version() {
	static $version;

	if ( $version ) {
		return $version;
	}

	$theme_data = wp_get_theme();
	$version = $theme_data->get( 'Version' ) === '%%VERSION%%' ? microtime( true ) : $theme_data->get( 'Version' );

	return $version;
}

/**
 * Set up theme defaults and supported features within WordPress.
 *
 * Runs on the action `after_setup_theme`.
 */
function theme_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		]
	);

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );
}

/**
 * Enqueue theme CSS and JavaScript.
 *
 * Runs on the action `wp_enqueue_scripts`.
 */
function enqueue() {
	Asset_Loader\enqueue([
		'entry'       => 'app',
		'handle'      => 'pwcc-003',
		'has_css'     => true,
		'dir_url'     => get_stylesheet_directory_uri() . '/assets',
		'dir_path'    => dirname( __DIR__ ) . '/assets',
		'in_footer'   => true,
		'style_deps'  => [],
		'script_deps' => [],
		'version'     => get_theme_version(),
	]);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
