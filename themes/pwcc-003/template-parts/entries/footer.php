<?php
$vars = $this->vars;
$vars['display_class'] = ! empty( $vars['display_class'] ) ? $vars['display_class'] : 'content';
?>
<?php if ( get_the_category() ) : ?>
	<footer class="entry-footer entry-footer--<?php echo sanitize_html_class( $vars['display_class'] ); ?>">
		<span class="entry-footer__categories">
			<svg class="icon icon--general" role="img" aria-hidden>
				<use xlink:href="#pwcc-icon-general"></use>
			</svg>
			<span class="screen-reader-text">Posted in </span>
			<?php the_category( ', ' ); ?>
		</span>
	</footer>
<?php endif; ?>
