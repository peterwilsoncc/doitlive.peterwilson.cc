<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--excerpt' ); ?>>
	<?php get_extended_template_part( 'entries/header', get_post_type() ); ?>
	<div class="entry__content entry__content--excerpt entry-summary">
		<?php the_excerpt(); ?>
	</div>
	<?php get_extended_template_part( 'entries/footer', get_post_type() ); ?>
</article>
<!-- // .post-<?php the_ID(); ?> -->
