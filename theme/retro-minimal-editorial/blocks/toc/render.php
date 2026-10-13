<?php
/**
 * Render: retro-minimal/toc
 *
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 *
 * @package RetroMinimalEditorial
 */

defined( 'ABSPATH' ) || exit;

$rme_post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
if ( ! $rme_post_id || get_post_meta( $rme_post_id, 'rme_hide_toc', true ) ) {
	return;
}

$rme_headings = rme_toc_headings( (string) get_post_field( 'post_content', $rme_post_id ) );
$rme_min      = max( 1, (int) ( $attributes['minHeadings'] ?? 3 ) );
if ( count( $rme_headings ) < $rme_min ) {
	return;
}

$rme_items = '';
foreach ( $rme_headings as $rme_h ) {
	$rme_items .= sprintf(
		'<li class="rme-toc__item rme-toc__item--h%1$d"><a href="#%2$s">%3$s</a></li>',
		$rme_h['level'],
		esc_attr( $rme_h['id'] ),
		esc_html( $rme_h['text'] )
	);
}

printf(
	'<nav %1$s aria-labelledby="rme-toc-title"><details open><summary id="rme-toc-title" class="rme-toc__title">%2$s</summary><ol class="rme-toc__list">%3$s</ol></details></nav>',
	get_block_wrapper_attributes( array( 'class' => 'rme-toc' ) ), // phpcs:ignore WordPress.Security.EscapeOutput -- core-escaped.
	esc_html__( 'Contents', 'retro-minimal-editorial' ),
	$rme_items // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
);
