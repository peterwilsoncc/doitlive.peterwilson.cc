<?php
namespace PWCC\Theme\Navigation;

/**
 * Bootstrap navigation.
 *
 * Runs on the action `after_theme_setup`.
 */
function bootstrap() {
	register_navigation();
}

/**
 * Register theme navigation locations.
 */
function register_navigation() {
	register_nav_menus( [
		'global-nav' => 'Global navigation',
	] );
}
