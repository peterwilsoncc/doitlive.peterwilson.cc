<?php
namespace PWCC\Theme\Template_Tags;

function the_title() {
	if ( is_singular() ) {
		\the_title( '<h1 class="entry__title entry-title">', '</h1>' );
	} else {
		\the_title( '<h2 class="entry__title entry-title">', '</h2>' );
	}
}
