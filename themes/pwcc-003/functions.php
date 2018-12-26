<?php
namespace PWCC\Theme;

include_once __DIR__ . '/inc/namespace.php';
include_once __DIR__ . '/inc/html-header/namespace.php';
include_once __DIR__ . '/inc/template-tags/namespace.php';
include_once __DIR__ . '/inc/navigation/namespace.php';

add_action( 'after_setup_theme', __NAMESPACE__ . '\\bootstrap' );
