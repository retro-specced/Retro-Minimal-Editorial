<?php
/**
 * Query Loop flags (set inside a Query block's "query" attribute in templates):
 *
 *   "rmeRelated": true         same category as the current post, excluding it
 *   "rmeExcludeCurrent": true  exclude the current post (e.g. "Other projects")
 *
 * Unknown keys in the query attribute are preserved by the editor, so these
 * stay editable in the Site Editor like any other Query Loop.
 *
 * @package RetroMinimalEditorial
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

add_filter(
	'query_loop_block_query_vars',
	static function ( array $query, WP_Block $block ): array {
		$flags = $block->context['query'] ?? array();
		if ( ! is_singular() ) {
			return $query;
		}

		$current = get_queried_object_id();

		if ( ! empty( $flags['rmeRelated'] ) || ! empty( $flags['rmeExcludeCurrent'] ) ) {
			$query['post__not_in'] = array_merge( (array) ( $query['post__not_in'] ?? array() ), array( $current ) );
		}

		if ( ! empty( $flags['rmeRelated'] ) ) {
			$cats = wp_get_post_categories( $current );
			if ( $cats ) {
				$query['category__in'] = array( (int) $cats[0] );
			}
		}

		return $query;
	},
	10,
	2
);
