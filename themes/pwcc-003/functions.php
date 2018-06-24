<?php
namespace PWCC\Theme;

use HumanMade\AssetLoader;

function bootstrap() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\bootstrap' );

function enqueue() {
	$loaded = AssetLoader\enqueue_assets( __DIR__ . '/assets', [
		'handle'  => 'pwcc-003-app',
		'scripts' => [ ],
	] );

	if ( ! $loaded ) {
		wp_enqueue_script(
			'pwcc-003-app',
			get_stylesheet_directory_uri() . '/assets/build/app.js',
			[],
			'1.0.0-alpha',
			true
		);


		wp_enqueue_style(
			'pwcc-003-app',
			get_stylesheet_directory_uri() . '/assets/build/app.css',
			[],
			'1.0.0-alpha'
		);
	}
}
