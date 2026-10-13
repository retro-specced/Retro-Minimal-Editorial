<?php
/**
 * Render: retro-minimal/reading-time
 *
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 *
 * @package RetroMinimalEditorial
 */

defined( 'ABSPATH' ) || exit;

$rme_post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
if ( ! $rme_post_id ) {
	return;
}

$rme_raw = (string) get_post_field( 'post_content', $rme_post_id );
$rme_words = str_word_count( wp_strip_all_tags( $rme_raw ) );
$rme_minutes = max( 1, (int) ceil( $rme_words / 230 ) );

printf(
	'<span %1$s>%2$s</span>',
	get_block_wrapper_attributes( array( 'class' => 'rme-reading-time' ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_html( sprintf( _n( '%d min', '%d min', $rme_minutes, 'retro-minimal-editorial' ), $rme_minutes ) )
);
