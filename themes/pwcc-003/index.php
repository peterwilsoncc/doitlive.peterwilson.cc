<?php
/**
 * Theme index file.
 *
 * @package pwcc-003 theme.
 */
get_header();

if ( have_posts() ) {
	get_extended_template_part( 'loops/loop', is_singular() ? 'singular' : 'listing' );
} else {
	get_extended_template_part( 'loops/loop', '404' );
}
get_footer();
