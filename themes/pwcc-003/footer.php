<?php
use PWCC\Theme;
?>
<footer class="footer">
	<div class="footer__legal legal">
		<p class="legal__copyright">
			&copy; Peter Wilson <?php Theme\the_copyright_dates(); ?>
		</p>
		<?php
		wp_nav_menu( [
			'theme_location' => 'legal-nav',
			'container' => 'nav',
			'container_id' => null,
			'container_class' => 'legal__nav legal-nav',
			'menu_id' => 'global-nav__menu',
			'menu_class' => 'global-nav__menu',
			'fallback_cb' => false,
			'depth' => 1,
			'item_spacing' => 'discard',
		] );
		?>
	</div>
</footer>
<?php
get_extended_template_part( 'svg-symbols' );
wp_footer();
?>
</body>
</html>
