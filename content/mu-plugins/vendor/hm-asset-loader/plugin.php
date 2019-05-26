<?php

// phpcs:disable HM.Files.NamespaceDirectoryName.NoIncDirectory

/**
 * Plugin Name: HM Asset Loader
 * Plugin URI: https://humanmade.com
 * Description: Helper for loading scripts & styles with support for Webpack DevServer.
 * Author: Human Made
 * Author URI: https://humanmade.com
 * Version: 1.0.0
 */

namespace HM\Asset_Loader;

/**
 * Attempt to load a file at the specified path and parse its contents as JSON.
 *
 * @param string $path The path to the JSON file to load.
 * @return array|null;
 */
function load_asset_file( $path ) {
	if ( ! file_exists( $path ) ) {
		return null;
	}

	// phpcs:ignore
	$contents = file_get_contents( $path );

	if ( empty( $contents ) ) {
		return null;
	}

	return json_decode( $contents, true );
}

/**
 * Get assets list from manifest file
 *
 * Checks a directory for a root or build asset manifest file, and attempt to
 * decode and return the asset list JSON if found.
 *
 * @param string $directory Root directory containing `src` and `build` directory.
 *
 * @return array|null;
 */
function get_assets_list( string $manifest_path ) {
	$dev_assets = load_asset_file( $manifest_path );

	if ( empty( $dev_assets ) ) {
		return null;
	}

	return $dev_assets;
}

/**
 * Build assets sources
 *
 * @param array $args Arguments passed to enqueue().
 *
 * @return array
 */
function build_src( $args ) {
	$script_url = sprintf( '%s/dist/%s.js', $args['dir_url'], $args['entry'] );
	$srcs       = [
		'script' => $script_url,
	];

	$manifest_path = sprintf( '%s/dist/%s-manifest.json', $args['dir_path'], $args['entry'] );
	// If manifest file doesn't exist, we're in production mode.
	if ( ! file_exists( $manifest_path ) ) {
		if ( $args['has_css'] ) {
			$srcs['style'] = str_replace( '.js', '.css', $script_url );
		}

		return $srcs;
	}

	$list = get_assets_list( $manifest_path );

	if ( empty( $list ) ) {
		return $srcs;
	}

	// This replaces the asset source with the one found in manifest file.
	array_walk( $srcs, function ( &$src ) use ( $list ) {
		$key = str_replace( home_url( '/content/' ), '', $src );

		if ( isset( $list[ $key ] ) ) {
			$src = $list[ $key ];
		}
	} );

	return $srcs;
}

/**
 * Enqueue assets
 *
 * @param array $args {
 *     @type string  $entry       Entry name as added to webpack.
 *     @type string  $handle      Handle to register the asset.
 *     @type bool    $has_css     Whether or not this entry has a CSS output.
 *     @type string  $dir_path    Absolute path of where the assets are located.
 *     @type string  $dir_url     Absolute URL of where the assets are located.
 *     @type array   $script_deps Script dependencies.
 *     @type array   $style_deps  Style dependencies.
 *     @type bool    $in_footer   Whether to enqueu the script in the footer instead of the head.
 * }
 *
 * @return bool
 */
function enqueue( array $args ) {
	$defaults = [
		'entry'       => '',
		'handle'      => '',
		'has_css'     => false,
		'dir_path'    => '',
		'dir_url'     => '',
		'script_deps' => [],
		'style_deps'  => [],
		'in_footer'   => true,
		'text_domain' => '',
		'version'     => null,
	];

	if ( empty( $args['entry'] ) || empty( $args['dir_path'] ) || empty( $args['dir_url'] ) ) {
		return false;
	}

	if ( empty( $args['handle'] ) ) {
		$args['handle'] = $args['entry'];
	}

	$args = wp_parse_args( $args, $defaults );
	$srcs = build_src( $args );

	if ( isset( $srcs['script'] ) ) {
		wp_enqueue_script(
			$args['handle'],
			$srcs['script'],
			$args['script_deps'],
			$args['version'],
			$args['in_footer']
		);

		if ( ! empty( $args['text_domain'] ) ) {
			$locale_data = gutenberg_get_jed_locale_data( $args['text_domain'] );

			wp_add_inline_script(
				$args['handle'],
				sprintf(
					'wp.i18n.setLocaleData( %s, %s );',
					json_encode( $locale_data ), // phpcs:ignore
					json_encode( $args['text_domain'] ) // phpcs:ignore
				),
				'before'
			);
		}
	}

	if ( ! $args['has_css'] ) {
		return true;
	}

	if ( isset( $srcs['style'] ) ) {
		wp_enqueue_style(
			$args['handle'],
			$srcs['style'],
			$args['style_deps'],
			$args['version']
		);
	} else {
		/*
		 * Always enqueue style dependencies because in development mode,
		 * the style is not enqueued as it will be added to the head by webpack.
		 */
		foreach ( $args['style_deps'] as $style_dep ) {
			wp_enqueue_style( $style_dep );
		}
	}

	return true;
}
