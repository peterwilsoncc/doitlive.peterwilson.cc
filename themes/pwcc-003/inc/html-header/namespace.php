<?php
namespace PWCC\Theme\HTML_Header;

/**
 * Bootstrap the theme's HTML header.
 */
function bootstrap() {
	add_action( 'wp_head', __NAMESPACE__ . '\\before_title_markup', 0 );
	add_action( 'wp_head', __NAMESPACE__ . '\\before_asset_markup', 5 );
}

/**
 * Output meta data required early in the HTML.
 *
 * This markup is output before the title tag and HTML
 *
 * Runs on the action `wp_head, 0`.
 */
function before_title_markup() {
	?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php
}

/**
 * Output markup required before the JS and style sheets are printed.
 *
 * Runs on the action `wp_head, 5`.
 */
function before_asset_markup() {
	?>
	<script>
		(function( c ) {
			c.add( 'js' );
			c.remove( 'no-js' );
		} )( document.documentElement.classList );
	</script>
	<?php
}
