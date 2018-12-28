<article id="post-<?php the_ID(); ?>" <?php post_class( 'excerpt' ); ?>>
	<?php
	get_extended_template_part(
		'entries/header',
		get_post_type(),
		[
			'display_class' => 'excerpt',
		]
	);
	?>
	<div class="excerpt__content entry-summary">
		<?php the_excerpt(); ?>
	</div>
	<?php
	get_extended_template_part(
		'entries/footer',
		get_post_type(),
		[
			'display_class' => 'excerpt',
		]
	);
	?>
</article>
<!-- // .post-<?php the_ID(); ?> -->
