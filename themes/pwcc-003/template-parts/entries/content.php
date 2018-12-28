<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--content' ); ?>>
	<?php
	get_extended_template_part(
		'entries/header',
		get_post_type(),
		[
			'display_class' => 'entry',
		]
	);
	?>
	<div class="entry__content entry-content">
		<?php the_content(); ?>
	</div>
	<?php
	get_extended_template_part(
		'entries/footer',
		get_post_type(),
		[
			'display_class' => 'entry',
		]
	);
	?>
</article>
<!-- // .post-<?php the_ID(); ?> -->
