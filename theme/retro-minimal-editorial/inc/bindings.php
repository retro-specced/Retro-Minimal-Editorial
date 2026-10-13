<?php
/**
 * Block Bindings source: "retro-minimal/post-slug" → "{post-slug}.exe".
 *
 * Bind a Paragraph's content to it to get a window title bar:
 *   {"metadata":{"bindings":{"content":{"source":"retro-minimal/post-slug"}}}}
 *
 * @package RetroMinimalEditorial
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function (): void {
		if ( ! function_exists( 'register_block_bindings_source' ) ) {
			return;
		}

		register_block_bindings_source(
			'retro-minimal/post-slug',
			array(
				'label'              => __( 'Post slug (.exe)', 'retro-minimal-editorial' ),
				'uses_context'       => array( 'postId' ),
				'get_value_callback' => static function ( array $args, WP_Block $block ): string {
					$post_id = (int) ( $block->context['postId'] ?? get_the_ID() );
					$slug    = $post_id ? (string) get_post_field( 'post_name', $post_id ) : '';
					if ( '' === $slug && $post_id ) {
						$slug = sanitize_title( (string) get_the_title( $post_id ) );
					}
					$slug = '' !== $slug ? $slug : 'untitled';
					return esc_html( urldecode( $slug ) . '.exe' );
				},
			)
		);
	}
);
