<?php
/**
 * The loop for a listing page.
 *
 * @package pwcc-003 theme.
 */
?>
<main class="main hfeed">
	<?php
	while ( have_posts() ) {
		the_post();
		get_extended_template_part( 'entries/excerpt', get_post_type() );
	}
	?>
</main>
