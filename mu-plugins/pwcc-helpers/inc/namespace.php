<?php
/**
 * PWCC Helpers.
 *
 * @package     PWCC Helpers
 * @author      Peter Wilson
 * @copyright   2018 Peter Wilson
 * @license     GPL-2.0+
 */
namespace PWCC\Helpers;

/**
 * Fast Bootstrap helpers.
 *
 * These filters are needed before WP completes bootstrapping.
 *
 * Runs as the plugin is included.
 */
function fast_bootstrap() {
	JetpackFixes\fast_bootstrap();
}

/**
 * Bootstrap helpers.
 *
 * Runs on the `plugins_loaded` hook.
 */
function bootstrap() {
	JetpackFixes\bootstrap();
	CavalcadeMods\bootstrap();
	Media\bootstrap();
}
