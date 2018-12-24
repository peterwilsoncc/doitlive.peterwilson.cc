<?php
namespace PWCC\Theme\Navigation;

/**
 * Bootstrap navigation.
 *
 * Runs on the action `after_theme_setup`.
 */
function bootstrap() {
	register_navigation();

	add_filter( 'nav_menu_submenu_css_class', __NAMESPACE__ . '\\filter_submenu_css_class', 10, 3 );
	add_filter( 'nav_menu_css_class', __NAMESPACE__ . '\\filter_menu_item_css_class', 10, 4 );
}

/**
 * Register theme navigation locations.
 */
function register_navigation() {
	register_nav_menus( [
		'global-nav' => 'Global navigation',
	] );
}

/**
 * Filter the submenu classes to something a bit more BEM like.
 *
 * Selected classes are removed, taking into account those needed by the customizer.
 *
 * Runs on the filter `nav_menu_submenu_css_class`.
 *
 * @param array     $classes The CSS classes that are applied to the menu `<ul>` element.
 * @param \stdClass $args    An object of `wp_nav_menu()` arguments.
 * @param int       $depth   Depth of menu item. Used for padding.
 *
 * @return array Modified list of CSS classes applied to the `ul` element.
 */
function filter_submenu_css_class( $classes, $args, $depth ) {
	// Make the args an array.
	$args = (array) $args;

	// Classes to remove.
	$remove_classes = [ 'sub-menu' ];

	$base_class = explode( '__', $args['container_class'], 2 )[0];
	$classes[] = sanitize_html_class( "${base_class}__sub-menu" );
	$classes[] = sanitize_html_class( "${base_class}__sub-menu--depth-" . ( $depth + 1 ) );

	$classes = array_diff( $classes, $remove_classes );

	return $classes;
}

/**
 * Filter the menu item classes to something a bit more BEM like.
 *
 * Selected classes are removed, taking into account those needed by the customizer.
 *
 * Runs on the filter `nav_menu_css_class`.
 *
 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
 * @param \WP_Post  $item    The current menu item.
 * @param \stdClass $args    An object of wp_nav_menu() arguments.
 * @param int      $depth   Depth of menu item. Used for padding.
 *
 * @return array Modified list of CSS classes applied to the `li` element.
 */
function filter_menu_item_css_class( $classes, $item, $args, $depth ) {
	// Make the args an array.
	$args = (array) $args;

	// Classes to remove.
	$remove_classes = [
		'menu-item',
		'current-menu-item',
		'current-menu-parent',
		'current-menu-ancestor',
		'menu-item-has-children',
		sanitize_html_class( "menu-item-{$item->post_name}" ),
		sanitize_html_class( "menu-item-type-{$item->type}" ),
		sanitize_html_class( "menu-item-object-{$item->object}" ),
	];

	$base_class = explode( '__', $args['container_class'], 2 )[0];
	$classes[] = sanitize_html_class( "${base_class}__menu-item" );

	if ( in_array( 'current-menu-item', $classes, true ) ) {
		$classes[] = sanitize_html_class( "${base_class}__menu-item--current" );
	}

	if ( in_array( 'current-menu-ancestor', $classes, true ) ) {
		$classes[] = sanitize_html_class( "${base_class}__menu-item--ancestor" );
	}

	if ( in_array( 'menu-item-has-children', $classes, true ) ) {
		$classes[] = sanitize_html_class( "${base_class}__menu-item--has-children" );
	}

	$classes = array_diff( $classes, $remove_classes );

	return $classes;
}
