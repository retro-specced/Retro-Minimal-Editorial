<?php
/**
 * Navigation: Classic Menus support, admin menu visibility, and block navigation sync.
 *
 * In WordPress Full Site Editing (block themes), Appearance > Menus is hidden by default.
 * This file:
 *  1. Re-enables classic menus and registers the 'primary' menu location.
 *  2. Adds Appearance > Menus to the wp-admin sidebar.
 *  3. Syncs classic menu items to the wp_navigation block post so rearranging pages
 *     in Appearance > Menus immediately updates the front-end Navigation block.
 *
 * @package RetroMinimalEditorial
 */

declare( strict_types = 1 );

namespace RetroMinimalEditorial;

defined( 'ABSPATH' ) || exit;

/**
 * Register classic menu support and primary navigation location.
 */
add_action(
	'after_setup_theme',
	static function (): void {
		add_theme_support( 'menus' );
		register_nav_menus(
			array(
				'primary' => __( 'Primary Navigation', 'retro-minimal-editorial' ),
			)
		);
	}
);

/**
 * Explicitly add Appearance > Menus to the admin menu.
 * Block themes hide nav-menus.php by default in WordPress 5.9+.
 */
add_action(
	'admin_menu',
	static function (): void {
		add_theme_page(
			__( 'Menus', 'retro-minimal-editorial' ),
			__( 'Menus', 'retro-minimal-editorial' ),
			'edit_theme_options',
			'nav-menus.php'
		);
	}
);

/**
 * Synchronize a classic nav menu to the primary wp_navigation post.
 *
 * @param int $menu_id The menu ID being updated (0 to pick assigned or first menu).
 */
function sync_classic_menu_to_block_navigation( int $menu_id = 0 ): void {
	// Only users with theme customization permissions should sync navigation.
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	// Prevent recursive sync loops.
	static $syncing = false;
	if ( $syncing ) {
		return;
	}

	$target_menu_id = $menu_id;
	$locations      = get_nav_menu_locations();

	if ( ! empty( $locations['primary'] ) ) {
		$target_menu_id = (int) $locations['primary'];
	} elseif ( ! $target_menu_id ) {
		$menus = wp_get_nav_menus();
		if ( ! empty( $menus ) ) {
			$target_menu_id = (int) $menus[0]->term_id;
		}
	}

	if ( ! $target_menu_id ) {
		return;
	}

	$menu_items = wp_get_nav_menu_items( $target_menu_id );
	if ( empty( $menu_items ) || ! is_array( $menu_items ) ) {
		return;
	}

	$block_markup = '';

	// 1. Try WordPress core's native classic-to-block converter (WP 6.3+).
	if ( class_exists( '\WP_Classic_To_Block_Menu_Converter' ) ) {
		$menu_term = wp_get_nav_menu_object( $target_menu_id );
		if ( $menu_term && ! is_wp_error( $menu_term ) ) {
			$converted = \WP_Classic_To_Block_Menu_Converter::convert( $menu_term );
			if ( ! is_wp_error( $converted ) && ! empty( trim( (string) $converted ) ) ) {
				$block_markup = trim( (string) $converted );
			}
		}
	}

	// 2. Fallback converter if core converter is not available or returned empty.
	if ( empty( $block_markup ) ) {
		usort( $menu_items, static fn( $a, $b ) => (int) $a->menu_order <=> (int) $b->menu_order );

		$markup_items = array();
		foreach ( $menu_items as $item ) {
			// Top-level items (or flat menu).
			if ( empty( $item->menu_item_parent ) || '0' === (string) $item->menu_item_parent ) {
				$label = ! empty( $item->title ) ? $item->title : get_the_title( (int) $item->object_id );
				$url   = ! empty( $item->url ) ? $item->url : get_permalink( (int) $item->object_id );

				$kind = 'custom';
				$type = $item->object ?? 'custom';
				$id   = (int) ( $item->object_id ?? 0 );

				if ( 'page' === $item->object ) {
					$kind = 'post-type';
					$type = 'page';
				} elseif ( 'post' === $item->object ) {
					$kind = 'post-type';
					$type = 'post';
				}

				$attrs = array(
					'label' => wp_strip_all_tags( (string) $label ),
					'url'   => esc_url_raw( (string) $url ),
					'type'  => sanitize_key( (string) $type ),
					'kind'  => sanitize_key( (string) $kind ),
				);
				if ( $id > 0 && 'custom' !== $kind ) {
					$attrs['id'] = $id;
				}

				$json           = wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
				$markup_items[] = '<!-- wp:navigation-link ' . $json . ' /-->';
			}
		}

		$block_markup = implode( "\n", $markup_items );
	}

	if ( empty( $block_markup ) ) {
		return;
	}

	$syncing = true;

	// Find the most recent published wp_navigation post or create one.
	$existing_navs = get_posts(
		array(
			'post_type'      => 'wp_navigation',
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	if ( ! empty( $existing_navs ) ) {
		wp_update_post(
			array(
				'ID'           => $existing_navs[0]->ID,
				'post_content' => $block_markup,
				'post_status'  => 'publish',
			)
		);
	} else {
		wp_insert_post(
			array(
				'post_title'   => __( 'Primary Navigation', 'retro-minimal-editorial' ),
				'post_name'    => 'primary-navigation',
				'post_content' => $block_markup,
				'post_status'  => 'publish',
				'post_type'    => 'wp_navigation',
			)
		);
	}

	$syncing = false;
}

// Hook into classic menu updates.
add_action( 'wp_update_nav_menu', 'RetroMinimalEditorial\\sync_classic_menu_to_block_navigation' );
add_action( 'after_switch_theme', 'RetroMinimalEditorial\\sync_classic_menu_to_block_navigation' );

// Auto-sync on admin if no navigation block post exists yet.
add_action(
	'init',
	static function (): void {
		if ( is_admin() && current_user_can( 'edit_theme_options' ) ) {
			$has_nav = get_posts(
				array(
					'post_type'      => 'wp_navigation',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'fields'         => 'ids',
				)
			);
			if ( empty( $has_nav ) ) {
				sync_classic_menu_to_block_navigation();
			}
		}
	},
	20
);
