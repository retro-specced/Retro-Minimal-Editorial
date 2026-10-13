<?php
/**
 * Project post type + Type / Stack taxonomies.
 *
 * URLs:
 *   /projects/                  archive
 *   /projects/{slug}/           single project
 *   /projects/type/{term}/      filtered by project type
 *   /projects/stack/{term}/     filtered by stack
 *
 * @package RetroMinimalCore
 */

declare( strict_types = 1 );

namespace RetroMinimalCore;

defined( 'ABSPATH' ) || exit;

final class Project_CPT {

	public const POST_TYPE = 'project';
	public const TAX_TYPE  = 'project_type';
	public const TAX_STACK = 'project_stack';

	/** Default project types created on activation (slug => name). */
	public const DEFAULT_TYPES = array(
		'vibe-coded' => 'Vibe-coded',
		'portfolio'  => 'Portfolio',
		'client'     => 'Client',
	);

	public static function init(): void {
		add_action( 'init', array( self::class, 'register' ) );
	}

	public static function register(): void {
		// Taxonomies are registered first so their more specific
		// /projects/type/… rewrite rules are matched before the post type's.
		register_taxonomy(
			self::TAX_TYPE,
			array( self::POST_TYPE ),
			array(
				'labels'            => array(
					'name'          => __( 'Project Types', 'retro-minimal-core' ),
					'singular_name' => __( 'Project Type', 'retro-minimal-core' ),
					'menu_name'     => __( 'Types', 'retro-minimal-core' ),
					'all_items'     => __( 'All Types', 'retro-minimal-core' ),
					'edit_item'     => __( 'Edit Type', 'retro-minimal-core' ),
					'add_new_item'  => __( 'Add New Type', 'retro-minimal-core' ),
					'search_items'  => __( 'Search Types', 'retro-minimal-core' ),
				),
				'public'            => true,
				'hierarchical'      => true, // Checkbox UI in the editor.
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'       => 'projects/type',
					'with_front' => false,
				),
			)
		);

		register_taxonomy(
			self::TAX_STACK,
			array( self::POST_TYPE ),
			array(
				'labels'            => array(
					'name'                       => __( 'Stack', 'retro-minimal-core' ),
					'singular_name'              => __( 'Stack Item', 'retro-minimal-core' ),
					'menu_name'                  => __( 'Stack', 'retro-minimal-core' ),
					'all_items'                  => __( 'All Stack Items', 'retro-minimal-core' ),
					'edit_item'                  => __( 'Edit Stack Item', 'retro-minimal-core' ),
					'add_new_item'               => __( 'Add New Stack Item', 'retro-minimal-core' ),
					'search_items'               => __( 'Search Stack', 'retro-minimal-core' ),
					'separate_items_with_commas' => __( 'Separate with commas, e.g. Next.js, Supabase, Claude', 'retro-minimal-core' ),
				),
				'public'            => true,
				'hierarchical'      => false,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'       => 'projects/stack',
					'with_front' => false,
				),
			)
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'        => array(
					'name'               => __( 'Projects', 'retro-minimal-core' ),
					'singular_name'      => __( 'Project', 'retro-minimal-core' ),
					'add_new_item'       => __( 'Add New Project', 'retro-minimal-core' ),
					'edit_item'          => __( 'Edit Project', 'retro-minimal-core' ),
					'new_item'           => __( 'New Project', 'retro-minimal-core' ),
					'view_item'          => __( 'View Project', 'retro-minimal-core' ),
					'view_items'         => __( 'View Projects', 'retro-minimal-core' ),
					'search_items'       => __( 'Search Projects', 'retro-minimal-core' ),
					'not_found'          => __( 'No projects found.', 'retro-minimal-core' ),
					'all_items'          => __( 'All Projects', 'retro-minimal-core' ),
					'archives'           => __( 'Projects', 'retro-minimal-core' ),
					'featured_image'     => __( 'Screenshot', 'retro-minimal-core' ),
					'set_featured_image' => __( 'Set screenshot', 'retro-minimal-core' ),
				),
				'description'   => __( 'Vibe-coded experiments, portfolio pieces and client work.', 'retro-minimal-core' ),
				'public'        => true,
				'show_in_rest'  => true,
				'menu_position' => 5,
				'menu_icon'     => 'dashicons-desktop',
				'has_archive'   => 'projects',
				'rewrite'       => array(
					'slug'       => 'projects',
					'with_front' => false,
				),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
				'taxonomies'    => array( self::TAX_TYPE, self::TAX_STACK ),
			)
		);
	}

	/**
	 * Create the default project types if they don't exist yet.
	 */
	public static function seed_default_types(): void {
		$colors = array(
			'vibe-coded' => Category_Colors::SWATCHES['Phosphor'],
			'portfolio'  => Category_Colors::SWATCHES['Lavender'],
			'client'     => Category_Colors::SWATCHES['CRT Amber'],
		);

		foreach ( self::DEFAULT_TYPES as $slug => $name ) {
			if ( term_exists( $slug, self::TAX_TYPE ) ) {
				continue;
			}
			$result = wp_insert_term( $name, self::TAX_TYPE, array( 'slug' => $slug ) );
			if ( ! is_wp_error( $result ) && isset( $colors[ $slug ] ) ) {
				update_term_meta( (int) $result['term_id'], Category_Colors::META_KEY, $colors[ $slug ] );
			}
		}
	}
}
