<?php
/**
 * Dark mode.
 *
 * Model (per modern dark-mode-toggle guidance):
 *   - <meta name="color-scheme"> is the single source of truth:
 *       "light dark" = follow the OS (default), "light" / "dark" = user override.
 *   - CSS maps that meta to the `color-scheme` property, and the palette uses
 *     light-dark(), so everything follows it, with no JS required to render.
 *   - A tiny inline script applies a stored override before first paint.
 *
 * @package RetroMinimalEditorial
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

const RME_SCHEME_STORAGE_KEY = 'rme-color-scheme';

add_action(
	'wp_head',
	static function (): void {
		echo '<meta name="color-scheme" content="light dark">' . "\n";
		// Must run synchronously, right after the meta, to prevent a flash of the wrong scheme.
		wp_print_inline_script_tag(
			sprintf(
				'try{var s=localStorage.getItem(%s);if(s==="light"||s==="dark"){document.documentElement.setAttribute("data-theme",s);var m=document.querySelector(\'meta[name="color-scheme"]\');if(m)m.content=s;}}catch(e){}',
				wp_json_encode( RME_SCHEME_STORAGE_KEY )
			)
		);
	},
	1
);
