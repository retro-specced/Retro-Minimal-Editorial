<?php
/**
 * Table of contents support.
 *
 * Heading anchors are generated with the same algorithm in two places:
 *   1. rme_toc_headings() reads the raw post content (for the TOC block,
 *      which renders before the post content in the template).
 *   2. A `the_content` filter adds matching `id`s to the rendered H2/H3s.
 * Headings that already have an HTML anchor keep it.
 *
 * Per-post opt-out: post meta `rme_hide_toc` (toggle in the editor sidebar).
 *
 * @package RetroMinimalEditorial
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

const RME_TOC_HEADING_REGEX = '/<h([23])\b([^>]*)>(.*?)<\/h\1>/is';

/**
 * Allocate a unique anchor for a heading.
 *
 * @param string              $text  Heading text (plain).
 * @param array<string, bool> $used  Already-used ids (by reference).
 */
function rme_toc_allocate_id( string $text, array &$used ): string {
	$base = sanitize_title( $text );
	$base = '' !== $base ? $base : 'section';
	$id   = $base;
	$n    = 2;
	while ( isset( $used[ $id ] ) ) {
		$id = $base . '-' . $n++;
	}
	$used[ $id ] = true;
	return $id;
}

function rme_toc_existing_id( string $attrs ): ?string {
	return preg_match( '/\bid\s*=\s*(["\'])(.*?)\1/i', $attrs, $m ) ? $m[2] : null;
}

/**
 * Extract H2/H3 headings (level, text, id) from post HTML.
 *
 * @return array<int, array{level:int, text:string, id:string}>
 */
function rme_toc_headings( string $html ): array {
	if ( ! preg_match_all( RME_TOC_HEADING_REGEX, $html, $matches, PREG_SET_ORDER ) ) {
		return array();
	}

	$used = array();
	$out  = array();
	foreach ( $matches as $h ) {
		$text = trim( html_entity_decode( wp_strip_all_tags( $h[3] ), ENT_QUOTES, 'UTF-8' ) );
		if ( '' === $text ) {
			continue;
		}
		$existing = rme_toc_existing_id( $h[2] );
		if ( null !== $existing ) {
			$used[ $existing ] = true;
			$id                = $existing;
		} else {
			$id = rme_toc_allocate_id( $text, $used );
		}
		$out[] = array(
			'level' => (int) $h[1],
			'text'  => $text,
			'id'    => $id,
		);
	}
	return $out;
}

/**
 * Add ids to rendered H2/H3 headings on singular views.
 */
add_filter(
	'the_content',
	static function ( string $content ): string {
		if ( ! is_singular() || ! str_contains( $content, '<h' ) ) {
			return $content;
		}

		$used = array();
		return (string) preg_replace_callback(
			RME_TOC_HEADING_REGEX,
			static function ( array $h ) use ( &$used ): string {
				$text = trim( html_entity_decode( wp_strip_all_tags( $h[3] ), ENT_QUOTES, 'UTF-8' ) );
				if ( '' === $text ) {
					return $h[0];
				}
				$existing = rme_toc_existing_id( $h[2] );
				if ( null !== $existing ) {
					$used[ $existing ] = true;
					return $h[0];
				}
				$id = rme_toc_allocate_id( $text, $used );
				return sprintf( '<h%1$s id="%2$s"%3$s>%4$s</h%1$s>', $h[1], esc_attr( $id ), $h[2], $h[3] );
			},
			$content
		);
	},
	20
);

/**
 * Per-post opt-out flag.
 */
add_action(
	'init',
	static function (): void {
		foreach ( array( 'post', 'project', 'page' ) as $type ) {
			register_post_meta(
				$type,
				'rme_hide_toc',
				array(
					'type'          => 'boolean',
					'single'        => true,
					'default'       => false,
					'show_in_rest'  => true,
					'auth_callback' => static fn(): bool => current_user_can( 'edit_posts' ),
				)
			);
		}
	}
);
