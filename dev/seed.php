<?php
/**
 * Seed script for Retro-Minimal Editorial development.
 *
 * Can be run via WP-CLI:
 *   wp eval-file dev/seed.php
 * Or included in local environment bootstrap.
 *
 * @package RetroMinimalEditorial
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Must be run within WordPress environment.' );
}

echo "=== Seeding Retro-Minimal Editorial Demo Content ===\n";

// 1. Activate theme and companion plugin if needed
switch_theme( 'retro-minimal-editorial' );
if ( ! is_plugin_active( 'retro-minimal-core/retro-minimal-core.php' ) ) {
	activate_plugin( 'retro-minimal-core/retro-minimal-core.php' );
}

// 2. Setup Category Swatches
$category_swatches = array(
	'Dev'         => array( 'slug' => 'dev', 'color' => '#8ED8DB', 'desc' => 'Technical writeups, code experiments, and system architecture.' ),
	'Vibe-Coding' => array( 'slug' => 'vibe-coding', 'color' => '#9CF2B0', 'desc' => 'Rapid software prototyping with frontier AI models.' ),
	'Notes'       => array( 'slug' => 'notes', 'color' => '#EAD9A6', 'desc' => 'Short notes, quick learnings, and snippets.' ),
	'Essays'      => array( 'slug' => 'essays', 'color' => '#C9BCF2', 'desc' => 'Long-form editorial essays on software and design.' ),
	'Life'        => array( 'slug' => 'life', 'color' => '#F4A9C8', 'desc' => 'Personal reflections, logs, and everyday observations.' ),
);

$cat_ids = array();
foreach ( $category_swatches as $name => $meta ) {
	$term = get_term_by( 'slug', $meta['slug'], 'category' );
	if ( ! $term ) {
		$res = wp_insert_term( $name, 'category', array( 'slug' => $meta['slug'], 'description' => $meta['desc'] ) );
		$term_id = is_array( $res ) ? (int) $res['term_id'] : 0;
	} else {
		$term_id = (int) $term->term_id;
	}

	if ( $term_id ) {
		update_term_meta( $term_id, 'rmc_label_color', $meta['color'] );
		$cat_ids[ $meta['slug'] ] = $term_id;
		echo "  -> Category: {$name} [{$meta['color']}]\n";
	}
}

// 3. Setup Project Types and Stack
$project_types = array(
	'vibe-coded' => array( 'name' => 'Vibe-coded', 'color' => '#9CF2B0' ),
	'portfolio'  => array( 'name' => 'Portfolio', 'color' => '#C9BCF2' ),
	'client'     => array( 'name' => 'Client', 'color' => '#F5C451' ),
);

$type_ids = array();
foreach ( $project_types as $slug => $data ) {
	$term = get_term_by( 'slug', $slug, 'project_type' );
	if ( ! $term ) {
		$res = wp_insert_term( $data['name'], 'project_type', array( 'slug' => $slug ) );
		$term_id = is_array( $res ) ? (int) $res['term_id'] : 0;
	} else {
		$term_id = (int) $term->term_id;
	}
	if ( $term_id ) {
		update_term_meta( $term_id, 'rmc_label_color', $data['color'] );
		$type_ids[ $slug ] = $term_id;
	}
}

$stacks = array( 'Next.js', 'Claude', 'Supabase', 'TypeScript', 'WordPress', 'Python', 'Tailwind CSS' );
foreach ( $stacks as $s ) {
	if ( ! term_exists( $s, 'project_stack' ) ) {
		wp_insert_term( $s, 'project_stack' );
	}
}

// 4. Create Pages & Configure Reading Settings
$pages = array(
	'home' => array(
		'title'   => 'Home',
		'content' => '<!-- wp:pattern {"slug":"retro-minimal/hub-intro"} /-->',
	),
	'blog' => array(
		'title'   => 'Writing',
		'content' => '',
	),
	'portfolio' => array(
		'title'   => 'Portfolio',
		'content' => '<!-- wp:pattern {"slug":"retro-minimal/portfolio-page"} /-->',
	),
	'about' => array(
		'title'   => 'About',
		'content' => '<!-- wp:paragraph --><p>Welcome to my editorial retro hub. Built with WordPress Full Site Editing and designed for 10+ minute uninterrupted reading.</p><!-- /wp:paragraph -->',
	),
);

$page_ids = array();
foreach ( $pages as $slug => $data ) {
	$existing = get_page_by_path( $slug );
	if ( ! $existing ) {
		$id = wp_insert_post( array(
			'post_title'   => $data['title'],
			'post_name'    => $slug,
			'post_content' => $data['content'],
			'post_status'  => 'publish',
			'post_type'    => 'page',
		) );
		$page_ids[ $slug ] = $id;
		echo "  -> Page: {$data['title']}\n";
	} else {
		$page_ids[ $slug ] = $existing->ID;
	}
}

if ( ! empty( $page_ids['home'] ) && ! empty( $page_ids['blog'] ) ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $page_ids['home'] );
	update_option( 'page_for_posts', $page_ids['blog'] );
	echo "  -> Configured Homepage: Home, Posts page: Blog\n";
}

// 5. Seed Sample Blog Posts (with 3+ headings to test TOC)
$sample_posts = array(
	array(
		'title'    => 'Building a CRT Shader in 40 Lines of GLSL',
		'slug'     => 'building-a-crt-shader-in-40-lines',
		'cat'      => 'dev',
		'excerpt'  => 'A tiny GLSL experiment recreating the phosphor glow and subtle curvature of 90s monochrome monitors.',
		'headings' => array( 'The Geometry of Cathode Rays', 'Phosphor Decay and Glow', 'Scanline Curvature', 'Putting It All Together' ),
	),
	array(
		'title'    => 'Vibe-Coding a Full Stack Application Overnight',
		'slug'     => 'vibe-coding-a-full-stack-app-overnight',
		'cat'      => 'vibe-coding',
		'excerpt'  => 'How frontier language models reshape developer velocity from typing syntax to architectural curation.',
		'headings' => array( 'The Shifting Nature of Flow', 'Prompt-Driven Architecture', 'Verification as the Primary Skill' ),
	),
	array(
		'title'    => 'The Typography of Editorial Brutalism',
		'slug'     => 'the-typography-of-editorial-brutalism',
		'cat'      => 'essays',
		'excerpt'  => 'Why warm paper backgrounds, Newsreader optical serifs, and monospace rules outlive sterile SaaS layouts.',
		'headings' => array( 'The Fatigue of Generic SaaS', 'Optical Sizing in Variable Fonts', 'Monospace as Structural Frame' ),
	),
	array(
		'title'    => 'Quick TIL: Bash Process Substitution Gotchas',
		'slug'     => 'bash-process-substitution-gotchas',
		'cat'      => 'notes',
		'excerpt'  => 'A brief note on file descriptor leaks when combining while loops with subshells in Linux.',
		'headings' => array( 'Subshell Execution Environment', 'Using Here-Strings Instead' ),
	),
	array(
		'title'    => 'A Walk Around the Harbor in Autumn',
		'slug'     => 'a-walk-around-the-harbor-in-autumn',
		'cat'      => 'life',
		'excerpt'  => 'Taking an afternoon offline to watch the cold mist roll across the docks and quiet water.',
		'headings' => array( 'Leaving the Terminal Behind', 'The Sound of the Foghorn', 'Returning with Fresh Eyes' ),
	),
);

foreach ( $sample_posts as $p ) {
	$existing = get_page_by_path( $p['slug'], OBJECT, 'post' );
	if ( ! $existing ) {
		$content = '<!-- wp:paragraph --><p>' . esc_html( $p['excerpt'] ) . '</p><!-- /wp:paragraph -->';
		foreach ( $p['headings'] as $h ) {
			$content .= "\n<!-- wp:heading --><h2>" . esc_html( $h ) . "</h2><!-- /wp:heading -->\n";
			$content .= "<!-- wp:paragraph --><p>Reading text and detailed exposition for section " . esc_html( $h ) . ". Every post in this theme is engineered for long-form comfort, generous line-height, and optimal reading cadence across devices.</p><!-- /wp:paragraph -->";
		}

		$post_id = wp_insert_post( array(
			'post_title'   => $p['title'],
			'post_name'    => $p['slug'],
			'post_excerpt' => $p['excerpt'],
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'post',
			'post_category'=> isset( $cat_ids[ $p['cat'] ] ) ? array( $cat_ids[ $p['cat'] ] ) : array(),
		) );
		echo "  -> Post: {$p['title']} ({$p['cat']})\n";
	}
}

// 6. Seed Sample Projects
$sample_projects = array(
	array(
		'title'       => 'Phosphor Canvas',
		'slug'        => 'phosphor-canvas',
		'type'        => 'vibe-coded',
		'stack'       => array( 'TypeScript', 'Next.js', 'Claude' ),
		'excerpt'     => 'A real-time WebGL terminal and retro ASCII canvas generator built in a weekend.',
		'year'        => '2026',
		'status'      => 'Active / Live',
		'role'        => 'Design & Code',
		'models'      => 'Claude 3.7 Sonnet (Architecture), Gemini 2.5 Pro (GLSL)',
		'show_models' => true,
		'live_url'    => 'https://example.com/demo/phosphor',
		'show_live'   => true,
		'source_url'  => 'https://github.com/gerald/phosphor-canvas',
		'show_source' => true,
	),
	array(
		'title'       => 'Monospace Editorial',
		'slug'        => 'monospace-editorial',
		'type'        => 'portfolio',
		'stack'       => array( 'WordPress', 'TypeScript' ),
		'excerpt'     => 'A minimal publishing platform for independent writers, featuring zero-build architecture.',
		'year'        => '2025',
		'status'      => 'Active / Live',
		'role'        => 'Full-Stack & Theme Author',
		'models'      => '',
		'show_models' => false,
		'live_url'    => 'https://example.com/demo/monospace',
		'show_live'   => true,
		'source_url'  => 'https://github.com/gerald/monospace-editorial',
		'show_source' => true,
	),
	array(
		'title'       => 'Starlight Analytics',
		'slug'        => 'starlight-analytics',
		'type'        => 'client',
		'stack'       => array( 'Python', 'Supabase', 'Next.js' ),
		'excerpt'     => 'Privacy-first telemetry pipeline and dashboard with streaming server-sent events.',
		'year'        => '2025',
		'status'      => 'Client Live',
		'role'        => 'Data Architecture & Backend',
		'models'      => '',
		'show_models' => false,
		'live_url'    => 'https://example.com/starlight',
		'show_live'   => true,
		'source_url'  => '',
		'show_source' => false,
	),
	array(
		'title'       => 'Retro-Minimal Core',
		'slug'        => 'retro-minimal-core-project',
		'type'        => 'vibe-coded',
		'stack'       => array( 'WordPress', 'PHP' ),
		'excerpt'     => 'Companion content layer plugin decoupling custom post types and category color metadata.',
		'year'        => '2026',
		'status'      => 'Open Source',
		'role'        => 'Plugin Development',
		'models'      => 'Claude Opus 3.5 & Gemini 2.5 Pro',
		'show_models' => true,
		'live_url'    => '',
		'show_live'   => false,
		'source_url'  => 'https://github.com/gerald/retro-minimal-core',
		'show_source' => true,
	),
	array(
		'title'       => 'Papercraft Reader',
		'slug'        => 'papercraft-reader',
		'type'        => 'portfolio',
		'stack'       => array( 'Next.js', 'TypeScript' ),
		'excerpt'     => 'An offline-capable progressive web application for reading distraction-free essays.',
		'year'        => '2024',
		'status'      => 'Archived',
		'role'        => 'Product Design & Frontend',
		'models'      => '',
		'show_models' => false,
		'live_url'    => 'https://example.com/papercraft',
		'show_live'   => true,
		'source_url'  => 'https://github.com/gerald/papercraft-reader',
		'show_source' => true,
	),
	array(
		'title'       => 'Logos Design Studio',
		'slug'        => 'logos-design-studio',
		'type'        => 'client',
		'stack'       => array( 'WordPress', 'Tailwind CSS' ),
		'excerpt'     => 'Bespoke corporate identity portfolio for a boutique architectural consulting firm.',
		'year'        => '2024',
		'status'      => 'Completed',
		'role'        => 'Brand Identity & Web Dev',
		'models'      => '',
		'show_models' => false,
		'live_url'    => 'https://example.com/logos',
		'show_live'   => true,
		'source_url'  => '',
		'show_source' => false,
	),
);

foreach ( $sample_projects as $proj ) {
	$existing = get_page_by_path( $proj['slug'], OBJECT, 'project' );
	$proj_id = $existing ? $existing->ID : 0;

	if ( ! $existing ) {
		$content = '<!-- wp:paragraph --><p>' . esc_html( $proj['excerpt'] ) . '</p><!-- /wp:paragraph -->' . "\n";
		$content .= '<!-- wp:heading --><h2>Overview &amp; Objectives</h2><!-- /wp:heading -->' . "\n";
		$content .= '<!-- wp:paragraph --><p>Case study walkthrough describing technical decisions, performance optimizations, and design philosophy behind ' . esc_html( $proj['title'] ) . '.</p><!-- /wp:paragraph -->';

		$proj_id = wp_insert_post( array(
			'post_title'   => $proj['title'],
			'post_name'    => $proj['slug'],
			'post_excerpt' => $proj['excerpt'],
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'project',
		) );

		if ( $proj_id ) {
			if ( isset( $type_ids[ $proj['type'] ] ) ) {
				wp_set_object_terms( $proj_id, (int) $type_ids[ $proj['type'] ], 'project_type' );
			}
			wp_set_object_terms( $proj_id, $proj['stack'], 'project_stack' );
			echo "  -> Project: {$proj['title']} ({$proj['type']})\n";
		}
	}

	if ( $proj_id ) {
		update_post_meta( $proj_id, 'rmc_project_year', $proj['year'] ?? '' );
		update_post_meta( $proj_id, 'rmc_project_status', $proj['status'] ?? '' );
		update_post_meta( $proj_id, 'rmc_project_role', $proj['role'] ?? '' );
		update_post_meta( $proj_id, 'rmc_project_models', $proj['models'] ?? '' );
		update_post_meta( $proj_id, 'rmc_project_show_models', (bool) ( $proj['show_models'] ?? false ) );
		update_post_meta( $proj_id, 'rmc_project_live_url', $proj['live_url'] ?? '' );
		update_post_meta( $proj_id, 'rmc_project_show_live', (bool) ( $proj['show_live'] ?? true ) );
		update_post_meta( $proj_id, 'rmc_project_source_url', $proj['source_url'] ?? '' );
		update_post_meta( $proj_id, 'rmc_project_show_source', (bool) ( $proj['show_source'] ?? true ) );
	}
}

echo "=== Seeding Complete! ===\n";
