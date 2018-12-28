<?php
namespace PWCC\Theme;

use HM\Asset_Loader;
use WP_Query;

const CACHE_GROUP = 'theme:pwcc_003';

function bootstrap() {
	theme_setup();
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue' );
	add_action( 'wp_footer', __NAMESPACE__ . '\\maybe_show_debug_info', 9999 );
	add_filter( 'img_caption_shortcode_width', '__return_zero' );

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

/**
 * Echo the copyright range for the site.
 */
function the_copyright_dates() {
	echo esc_html( get_copyright_dates() );
}

/**
 * Get the copyright range for the site.
 *
 * @return string The copyright period represented as a string.
 */
function get_copyright_dates() {
	$user_id = get_current_user_id();
	$cache_key = "copyright_dates_user{$user_id}";

	$dates = wp_cache_get( $cache_key, CACHE_GROUP );
	if ( $dates ) {
		return $dates;
	}

	$dates = _query_copyright_dates();
	wp_cache_set( $cache_key, $dates, CACHE_GROUP, DAY_IN_SECONDS );

	return $dates;
}

/**
 * Query the DB for first and last copyright dates.
 *
 * Queries the database for the oldest and newest posts
 * to generate the copyright string.
 *
 * @access private.
 *
 * @return string The copyright period represented as a string.
 */
function _query_copyright_dates() {
	$args = [
		'post_type' => 'any',
		'posts_per_page' => 1,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'no_found_rows' => false,
	];

	$oldest = new WP_Query( array_merge( $args, [ 'order' => 'ASC' ] ) );

	/*
	 * By definition if there is no oldest post there will be no
	 * newest post so it's safe to bail early if the ASC query returns
	 * no posts.
	 */
	if ( $oldest->post_count === 0 ) {
		return '';
	}

	$oldest_post_year = get_the_date( 'Y', $oldest->posts[0]->ID );

	// If the oldest post year is the current year, there will be no time span.
	if ( $oldest_post_year === date( 'Y', time() ) ) {
		return $oldest_post_year;
	}

	$newest = new WP_Query( array_merge( $args, [ 'order' => 'DESC' ] ) );
	// No count check needed. If there is an oldest post, there is a newest.
	$newest_post_year = get_the_date( 'Y', $newest->posts[0]->ID );

	// No time span if both oldest and newest post are from the same year.
	if ( $oldest_post_year === $newest_post_year ) {
		return $oldest_post_year;
	}

	return "{$oldest_post_year}&ndash;{$newest_post_year}";
}

function maybe_show_debug_info() {
	if ( PWCC_ENV !== 'local' ) {
		return;
	}
	?>
	<div style="color: black; background-color: yellow; text-align: center; font-size: 1em;">
		<p>DB Queries: <?php echo esc_html( get_num_queries() ); ?></p>
	</div>
	<?php
}
