<?php
namespace PWCC\Theme;

include_once __DIR__ . '/inc/namespace.php';
include_once __DIR__ . '/inc/html-header/namespace.php';

add_action( 'after_setup_theme', __NAMESPACE__ . '\\bootstrap' );
