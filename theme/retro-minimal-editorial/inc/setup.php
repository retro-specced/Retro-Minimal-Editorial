<?php
/**
 * Theme setup: supports, assets, block styles, blocks, pattern category.
 *
 * @package RetroMinimalEditorial
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function (): void {
		add_theme_support( 'editor-styles' );
		add_theme_support( 'responsive-embeds' );
		add_editor_style( array( 'assets/css/theme.css' ) );
	}
);

/**
 * Front-end styles. theme.css holds component styles (shared with the editor),
 * color-scheme.css switches the palette to dark mode on the front end.
 */
add_action(
	'wp_enqueue_scripts',
	static function (): void {
		$css_path = get_theme_file_path( 'assets/css/theme.css' );
		$version  = file_exists( $css_path ) ? (string) filemtime( $css_path ) : RME_VERSION;
		$css_uri  = get_theme_file_uri( 'assets/css/' );
		$js_uri   = get_theme_file_uri( 'assets/js/' );

		wp_enqueue_style( 'rme-theme', $css_uri . 'theme.css', array(), $version );
		wp_enqueue_style( 'rme-color-scheme', $css_uri . 'color-scheme.css', array( 'rme-theme' ), $version );
		wp_enqueue_script( 'rme-theme', $js_uri . 'theme.js', array(), $version, true );
	}
);

/**
 * Preload the body font (used above the fold on every page).
 */
add_action(
	'wp_head',
	static function (): void {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( 'assets/fonts/newsreader-latin-opsz-normal.woff2' ) )
		);
	},
	2
);

/**
 * Block style variations (styled in assets/css/theme.css).
 */
add_action(
	'init',
	static function (): void {
		$styles = array(
			'core/group'     => array(
				'window'   => __( 'Window', 'retro-minimal-editorial' ),
				'bordered' => __( 'Bordered', 'retro-minimal-editorial' ),
				'terminal' => __( 'Terminal', 'retro-minimal-editorial' ),
			),
			'core/image'     => array(
				'bordered' => __( 'Bordered', 'retro-minimal-editorial' ),
			),
			'core/separator' => array(
				'asterisks' => __( '* * *', 'retro-minimal-editorial' ),
				'dashed'    => __( 'Dashed', 'retro-minimal-editorial' ),
			),
			'core/list'      => array(
				'link-list' => __( 'Link list', 'retro-minimal-editorial' ),
			),
			'core/button'    => array(
				'ghost' => __( 'Ghost', 'retro-minimal-editorial' ),
			),
		);

		foreach ( $styles as $block => $variations ) {
			foreach ( $variations as $name => $label ) {
				register_block_style(
					$block,
					array(
						'name'  => $name,
						'label' => $label,
					)
				);
			}
		}

		register_block_pattern_category(
			'retro-minimal',
			array( 'label' => __( 'Retro-Minimal', 'retro-minimal-editorial' ) )
		);

		register_block_type( get_theme_file_path( 'blocks/toc' ) );
		register_block_type( get_theme_file_path( 'blocks/theme-toggle' ) );
		register_block_type( get_theme_file_path( 'blocks/reading-time' ) );
		register_block_type( get_theme_file_path( 'blocks/project-meta' ) );
		register_block_type( get_theme_file_path( 'blocks/project-filter' ) );
		register_block_type( get_theme_file_path( 'blocks/category-filter' ) );
	}
);

/**
 * Fallback for project cards without a featured image: monospace placeholder with blinking cursor.
 */
add_filter(
	'render_block_core/post-featured-image',
	static function ( string $content, array $block, WP_Block $instance ): string {
		$post_id = (int) ( $instance->context['postId'] ?? 0 );
		if ( ! $post_id ) {
			return $content;
		}
		$post_type = get_post_type( $post_id );
		if ( 'project' === $post_type && ( '' === trim( $content ) || ! has_post_thumbnail( $post_id ) ) ) {
			return '<div class="wp-block-post-featured-image rme-screenshot-placeholder"><div class="rme-screenshot-placeholder__inner"><code>&gt; no preview<span class="rme-blink">_</span></code></div></div>';
		}
		return $content;
	},
	10,
	3
);

/**
 * Editor-only helpers: "Hide table of contents" toggle + slug binding preview.
 */
add_action(
	'enqueue_block_editor_assets',
	static function (): void {
		wp_enqueue_script(
			'rme-editor',
			get_theme_file_uri( 'assets/js/editor.js' ),
			array( 'wp-plugins', 'wp-editor', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n', 'wp-blocks', 'wp-core-data' ),
			RME_VERSION,
			true
		);
	}
);

/**
 * Template parts / blocks print a "skip to content" link automatically when a
 * <main> exists. Nothing to do here; kept as a reminder for template authors.
 */
