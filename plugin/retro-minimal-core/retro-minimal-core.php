<?php
/**
 * Plugin Name:       Retro-Minimal Core
 * Plugin URI:        https://github.com/gerald/retro-minimal-editorial
 * Description:       Content layer for the Retro-Minimal Editorial theme: the Project post type (with Type and Stack taxonomies) and per-category label colors. Keeps your content portable if you ever switch themes.
 * Version:           1.0.0
 * Requires at least: 7.0
 * Requires PHP:      8.2
 * Author:            Gerald
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       retro-minimal-core
 *
 * @package RetroMinimalCore
 */

declare( strict_types = 1 );

namespace RetroMinimalCore;

defined( 'ABSPATH' ) || exit;

const VERSION = '1.0.0';
const FILE    = __FILE__;

require_once __DIR__ . '/includes/class-project-cpt.php';
require_once __DIR__ . '/includes/class-category-colors.php';

Project_CPT::init();
Category_Colors::init();

/**
 * Activation: register content types, seed the project types, flush rewrites.
 */
register_activation_hook(
	__FILE__,
	static function (): void {
		Project_CPT::register();
		Project_CPT::seed_default_types();
		flush_rewrite_rules();
	}
);

register_deactivation_hook(
	__FILE__,
	static function (): void {
		flush_rewrite_rules();
	}
);

/**
 * Public helper for themes: get the label color for a term.
 *
 * Returns the stored hex color, or the Platinum fallback when none is set.
 *
 * @param int $term_id Term ID.
 * @return string Hex color.
 */
function get_term_color( int $term_id ): string {
	return Category_Colors::get_color( $term_id );
}
