<?php
$vars = $this->vars;
$vars['display_class'] = ! empty( $vars['display_class'] ) ? $vars['display_class'] : 'content';
?>
<header class="entry-header entry-header--<?php echo sanitize_html_class( $vars['display_class'] ); ?>">
	<?php if ( get_the_title() ) : ?>
		<?php if ( is_singular() && ! is_embed() ) : ?>
			<h1 class="entry-header__title entry-title">
				<?php the_title(); ?>
			</h1>
		<?php elseif ( is_embed() ) : ?>
			<h1 class="entry-header__title entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php the_title(); ?>
				</a>
			</h1>
		<?php else : ?>
			<h2 class="entry-header__title entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php the_title(); ?>
				</a>
			</h2>
		<?php endif; ?>
	<?php endif; ?>
	<div class="entry__meta-data">
		<p class="entry__published-date">
			<?php if ( ! get_the_title() ) : ?>
				<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php endif; ?>
			<time class="published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<?php if ( ! get_the_title() ) : ?>
				</a>
			<?php endif; ?>
		</p>
	</div>
</header>
