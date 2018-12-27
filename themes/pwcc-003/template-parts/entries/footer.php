<?php if ( get_the_category() ) : ?>
	<footer class="entry-footer">
		<span class="entry-footer__categories">
			<svg class="icon icon--general" role="img" aria-hidden>
				<use xlink:href="#pwcc-icon-general"></use>
			</svg>
			<span class="">Posted in </span>
			<?php the_category( ', ' ); ?>
		</span>
	</footer>
<?php endif; ?>
