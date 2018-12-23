<?php
namespace PWCC\Theme;

use HumanMade\AssetLoader;

function bootstrap() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue' );
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

function enqueue() {
	$loaded = AssetLoader\enqueue_assets(
		__DIR__ . '/../assets', [
			'handle'  => 'pwcc-003-app',
			'scripts' => [],
		]
	);

	if ( ! $loaded ) {
		wp_enqueue_script(
			'pwcc-003-app',
			get_stylesheet_directory_uri() . '/assets/build/app.js',
			[],
			get_theme_version(),
			true
		);

		wp_enqueue_style(
			'pwcc-003-app',
			get_stylesheet_directory_uri() . '/assets/build/app.css',
			[],
			get_theme_version()
		);
	}
}
