<?php
/**
 * Retro-Minimal Editorial: theme bootstrap.
 *
 * Everything here is small and build-free:
 *   inc/setup.php          supports, assets, block styles, pattern category
 *   inc/color-scheme.php   dark mode (meta color-scheme + no-flash inline script)
 *   inc/toc.php            heading anchors + table of contents helpers
 *   inc/query.php          "related posts" / "other projects" query flags
 *   inc/bindings.php       {post-slug}.exe block binding for window title bars
 *
 * @package RetroMinimalEditorial
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

const RME_VERSION = '1.0.0';

require_once __DIR__ . '/inc/setup.php';
require_once __DIR__ . '/inc/color-scheme.php';
require_once __DIR__ . '/inc/toc.php';
require_once __DIR__ . '/inc/query.php';
require_once __DIR__ . '/inc/bindings.php';
require_once __DIR__ . '/inc/navigation.php';
