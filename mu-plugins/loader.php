<?php

/*
Plugin Name: HM MU Plugin Loader
Description: Loads the MU plugins required to run the site
Author: Human Made Limited
Author URI: http://hmn.md/
Version: 1.0
*/

if ( ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) ) {
	return;
}

$hm_mu_plugins = [
	'vendor/cavalcade/plugin.php',
	'vendor/cmb2/init.php',
	'vendor/extended-cpts/extended-cpts.php',
];

foreach ( $hm_mu_plugins as $k => $file ) {
	if ( file_exists( WPMU_PLUGIN_DIR . '/' . $file ) ) {
		continue;
	}
	$file = wp_basename( $file );
	// phpcs:ignore
	trigger_error(
		/* translators: %s refers to the plugin file. */
		sprintf( __( 'Plugin file %s does not exist' ), esc_html( $file ) ),
		E_USER_WARNING
	);
	unset( $hm_mu_plugins[ $k ] );
}

add_action( 'pre_current_active_plugins', function() use ( $hm_mu_plugins ) {
	global $plugins, $wp_list_table;

	// Add our own mu-plugins to the page
	foreach ( $hm_mu_plugins as $plugin_file ) {
		$plugin_data = get_plugin_data( WPMU_PLUGIN_DIR . "/$plugin_file", false, false ); // Do not apply markup/translate as it'll be cached.

		if ( empty( $plugin_data['Name'] ) ) {
			$plugin_data['Name'] = $plugin_file;
		}

		$plugins['mustuse'][ $plugin_file ] = $plugin_data;
	}

	// Recount totals
	$GLOBALS['totals']['mustuse'] = count( $plugins['mustuse'] );

	// Only apply the rest if we're actually looking at the page
	if ( 'mustuse' !== $GLOBALS['status'] ) {
		return;
	}

	// Reset the list table's data
	$wp_list_table->items = $plugins['mustuse'];
	foreach ( $wp_list_table->items as $plugin_file => $plugin_data ) {
		$wp_list_table->items[ $plugin_file ] = _get_plugin_data_markup_translate( $plugin_file, $plugin_data, false, true );
	}

	$total_this_page = $GLOBALS['totals']['mustuse'];

	if ( $GLOBALS['orderby'] ) {
		uasort( $wp_list_table->items, [ $wp_list_table, '_order_callback' ] );
	}

	// Force showing all plugins
	// See https://core.trac.wordpress.org/ticket/27110
	$plugins_per_page = $total_this_page;

	$wp_list_table->set_pagination_args( [
		'total_items' => $total_this_page,
		'per_page'    => $plugins_per_page,
	] );
} );

add_action( 'network_admin_plugin_action_links', function( $actions, $plugin_file, $plugin_data, $context ) use ( $hm_mu_plugins ) {
	if ( 'mustuse' !== $context || ! in_array( $plugin_file, $hm_mu_plugins, true ) ) {
		return;
	}

	$actions[] = sprintf( '<span style="color:#333;">File: <code>%s</code></span>', $plugin_file );

	return $actions;
}, 10, 4 );

foreach ( $hm_mu_plugins as $file ) {
	require_once WPMU_PLUGIN_DIR . '/' . $file;
}
unset( $file );
