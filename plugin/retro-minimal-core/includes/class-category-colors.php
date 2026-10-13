<?php
/**
 * Per-term label colors (categories + project types by default).
 *
 * - Adds a "Label color" field (core wp-color-picker + recommended swatches)
 *   to the Add / Edit term screens.
 * - Stores the hex in term meta `rmc_label_color`.
 * - Adds a swatch column to the term list table.
 * - On the front end, attaches `--rmc-chip: #hex` to category links rendered
 *   by core blocks (Post Terms, Categories list) so the theme can paint chips.
 *
 * Extend to other taxonomies with:
 *   add_filter( 'rmc_color_taxonomies', fn( $t ) => [ ...$t, 'project_type' ] );
 *
 * @package RetroMinimalCore
 */

declare( strict_types = 1 );

namespace RetroMinimalCore;

defined( 'ABSPATH' ) || exit;

final class Category_Colors {

	public const META_KEY = 'rmc_label_color';

	/** Chip text color; every swatch is checked for contrast against it. */
	public const INK = '#1A1A1A';

	/** Fallback for terms without a color. */
	public const DEFAULT_COLOR = '#C9C6BD';

	/** Recommended retro swatches (name => hex). */
	public const SWATCHES = array(
		'Phosphor'      => '#9CF2B0',
		'Terminal Cyan' => '#8ED8DB',
		'CRT Amber'     => '#F5C451',
		'Manila'        => '#EAD9A6',
		'Lavender'      => '#C9BCF2',
		'Bubblegum'     => '#F4A9C8',
		'Signal Orange' => '#F59E6B',
		'Cobalt Wash'   => '#A7BCF7',
		'Platinum'      => '#C9C6BD',
	);

	public static function init(): void {
		add_action( 'init', array( self::class, 'register_meta' ) );
		add_action( 'admin_init', array( self::class, 'register_admin_hooks' ) );
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue_admin' ) );

		add_filter( 'render_block_core/post-terms', array( self::class, 'filter_post_terms' ), 10, 3 );
		add_filter( 'render_block_core/categories', array( self::class, 'filter_categories_list' ), 10, 2 );
		add_filter( 'post_class', array( self::class, 'filter_post_class' ), 10, 3 );
		add_action( 'wp_head', array( self::class, 'print_frontend_css_variables' ) );
	}

	/**
	 * Taxonomies that get a color field.
	 *
	 * @return string[]
	 */
	public static function taxonomies(): array {
		$taxonomies = (array) apply_filters( 'rmc_color_taxonomies', array( 'category', Project_CPT::TAX_TYPE ) );
		return array_values( array_filter( $taxonomies, 'taxonomy_exists' ) );
	}

	/* ------------------------------------------------------------------ */
	/* Data                                                                */
	/* ------------------------------------------------------------------ */

	public static function register_meta(): void {
		foreach ( self::taxonomies() as $taxonomy ) {
			register_term_meta(
				$taxonomy,
				self::META_KEY,
				array(
					'type'              => 'string',
					'description'       => __( 'Label color (hex) used for chips.', 'retro-minimal-core' ),
					'single'            => true,
					'show_in_rest'      => true,
					'sanitize_callback' => array( self::class, 'sanitize_color' ),
					'auth_callback'     => static fn(): bool => current_user_can( 'manage_categories' ),
				)
			);
		}
	}

	public static function sanitize_color( mixed $value ): string {
		$hex = sanitize_hex_color( is_string( $value ) ? trim( $value ) : '' );
		return $hex ? strtoupper( $hex ) : '';
	}

	/**
	 * Stored color for a term, or the Platinum fallback.
	 */
	public static function get_color( int $term_id ): string {
		$color = get_term_meta( $term_id, self::META_KEY, true );
		return is_string( $color ) && '' !== $color ? $color : self::DEFAULT_COLOR;
	}

	/**
	 * WCAG contrast ratio between two hex colors.
	 */
	public static function contrast( string $hex_a, string $hex_b ): float {
		$la = self::luminance( $hex_a );
		$lb = self::luminance( $hex_b );
		return ( max( $la, $lb ) + 0.05 ) / ( min( $la, $lb ) + 0.05 );
	}

	private static function luminance( string $hex ): float {
		$hex = ltrim( $hex, '#' );
		if ( 3 === strlen( $hex ) ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		$channels = array_map(
			static function ( string $pair ): float {
				$c = hexdec( $pair ) / 255;
				return $c <= 0.03928 ? $c / 12.92 : ( ( $c + 0.055 ) / 1.055 ) ** 2.4;
			},
			str_split( $hex, 2 )
		);
		return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
	}

	/* ------------------------------------------------------------------ */
	/* Admin                                                               */
	/* ------------------------------------------------------------------ */

	public static function register_admin_hooks(): void {
		foreach ( self::taxonomies() as $taxonomy ) {
			add_action( "{$taxonomy}_add_form_fields", array( self::class, 'render_add_field' ) );
			add_action( "{$taxonomy}_edit_form_fields", array( self::class, 'render_edit_field' ) );
			add_action( "created_{$taxonomy}", array( self::class, 'save' ) );
			add_action( "edited_{$taxonomy}", array( self::class, 'save' ) );
			add_filter( "manage_edit-{$taxonomy}_columns", array( self::class, 'add_column' ) );
			add_filter( "manage_{$taxonomy}_custom_column", array( self::class, 'render_column' ), 10, 3 );
		}
	}

	public static function enqueue_admin( string $hook ): void {
		if ( ! in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->taxonomy, self::taxonomies(), true ) ) {
			return;
		}

		$base = plugin_dir_url( FILE ) . 'assets/';
		wp_enqueue_style( 'rmc-term-color', $base . 'admin-term-color.css', array( 'wp-color-picker' ), VERSION );
		wp_enqueue_script( 'rmc-term-color', $base . 'admin-term-color.js', array( 'jquery', 'wp-color-picker', 'wp-i18n' ), VERSION, true );
		wp_localize_script(
			'rmc-term-color',
			'rmcTermColor',
			array(
				'swatches' => self::SWATCHES,
				'ink'      => self::INK,
				'fallback' => self::DEFAULT_COLOR,
			)
		);
	}

	private static function field_markup( string $value ): string {
		$swatches = '';
		foreach ( self::SWATCHES as $name => $hex ) {
			$swatches .= sprintf(
				'<button type="button" class="rmc-swatch" data-color="%1$s" style="--swatch:%1$s" aria-label="%2$s"><span aria-hidden="true">%3$s</span></button>',
				esc_attr( $hex ),
				/* translators: 1: swatch name, 2: hex color. */
				esc_attr( sprintf( __( 'Use %1$s (%2$s)', 'retro-minimal-core' ), $name, $hex ) ),
				esc_html( $name )
			);
		}

		return sprintf(
			'<input type="text" name="%1$s" id="%1$s" class="rmc-color-field" value="%2$s" data-default-color="" autocomplete="off" />
			<div class="rmc-swatches" role="group" aria-label="%3$s">%4$s</div>
			<p class="rmc-preview-wrap">%5$s <span class="rmc-preview">%6$s</span></p>
			<p class="rmc-contrast-warning" role="status" hidden></p>
			<p class="description">%7$s</p>',
			esc_attr( self::META_KEY ),
			esc_attr( $value ),
			esc_attr__( 'Recommended colors', 'retro-minimal-core' ),
			$swatches,
			esc_html__( 'Preview:', 'retro-minimal-core' ),
			esc_html__( '[ LABEL ]', 'retro-minimal-core' ),
			esc_html__( 'Background color for this category\'s label chip. Leave empty to use Platinum.', 'retro-minimal-core' )
		);
	}

	public static function render_add_field(): void {
		wp_nonce_field( 'rmc_save_color', 'rmc_color_nonce' );
		printf(
			'<div class="form-field term-rmc-color-wrap"><label for="%1$s">%2$s</label>%3$s</div>',
			esc_attr( self::META_KEY ),
			esc_html__( 'Label color', 'retro-minimal-core' ),
			self::field_markup( '' ) // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in field_markup().
		);
	}

	public static function render_edit_field( \WP_Term $term ): void {
		$value = (string) get_term_meta( $term->term_id, self::META_KEY, true );
		echo '<tr class="form-field term-rmc-color-wrap"><th scope="row">';
		printf( '<label for="%1$s">%2$s</label>', esc_attr( self::META_KEY ), esc_html__( 'Label color', 'retro-minimal-core' ) );
		echo '</th><td>';
		wp_nonce_field( 'rmc_save_color', 'rmc_color_nonce' );
		echo self::field_markup( $value ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in field_markup().
		echo '</td></tr>';
	}

	public static function save( int $term_id ): void {
		if (
			! isset( $_POST['rmc_color_nonce'] ) ||
			! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['rmc_color_nonce'] ) ), 'rmc_save_color' ) ||
			! current_user_can( 'manage_categories' )
		) {
			return;
		}

		$color = self::sanitize_color( wp_unslash( $_POST[ self::META_KEY ] ?? '' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized by sanitize_color().
		if ( '' === $color ) {
			delete_term_meta( $term_id, self::META_KEY );
		} else {
			update_term_meta( $term_id, self::META_KEY, $color );
		}
	}

	/**
	 * @param array<string,string> $columns
	 * @return array<string,string>
	 */
	public static function add_column( array $columns ): array {
		$out = array();
		foreach ( $columns as $key => $label ) {
			if ( 'name' === $key ) {
				$out['rmc_color'] = '<span class="screen-reader-text">' . esc_html__( 'Label color', 'retro-minimal-core' ) . '</span><span aria-hidden="true">■</span>';
			}
			$out[ $key ] = $label;
		}
		return $out;
	}

	public static function render_column( string $content, string $column, int $term_id ): string {
		if ( 'rmc_color' !== $column ) {
			return $content;
		}
		$stored = (string) get_term_meta( $term_id, self::META_KEY, true );
		$color  = '' !== $stored ? $stored : self::DEFAULT_COLOR;
		$title  = '' !== $stored ? $stored : __( 'Default (Platinum)', 'retro-minimal-core' );
		return sprintf(
			'<span class="rmc-col-swatch%1$s" style="--swatch:%2$s" title="%3$s"></span><span class="screen-reader-text">%3$s</span>',
			'' === $stored ? ' is-default' : '',
			esc_attr( $color ),
			esc_attr( $title )
		);
	}

	/* ------------------------------------------------------------------ */
	/* Front end                                                           */
	/* ------------------------------------------------------------------ */

	/**
	 * Add chip classes + color variable to links rendered by core/post-terms.
	 *
	 * Core renders `<a href="…" rel="tag">Name</a>` with no term identifier,
	 * so links are matched to terms by URL.
	 */
	public static function filter_post_terms( string $content, array $block, \WP_Block $instance ): string {
		$taxonomy = $block['attrs']['term'] ?? '';
		$post_id  = (int) ( $instance->context['postId'] ?? 0 );

		if ( '' === $content || ! $post_id || ! in_array( $taxonomy, self::taxonomies(), true ) ) {
			return $content;
		}

		$terms = get_the_terms( $post_id, $taxonomy );
		if ( ! is_array( $terms ) ) {
			return $content;
		}

		$by_url = array();
		foreach ( $terms as $term ) {
			$link = get_term_link( $term );
			if ( is_string( $link ) ) {
				$by_url[ untrailingslashit( $link ) ] = $term;
			}
		}

		$tags = new \WP_HTML_Tag_Processor( $content );
		while ( $tags->next_tag( 'a' ) ) {
			$href = $tags->get_attribute( 'href' );
			$term = is_string( $href ) ? ( $by_url[ untrailingslashit( $href ) ] ?? null ) : null;
			if ( ! $term ) {
				continue;
			}
			$tags->add_class( 'rmc-chip' );
			$tags->add_class( 'rmc-term-' . sanitize_html_class( $term->slug ) );
			$tags->set_attribute( 'style', '--rmc-chip:' . self::get_color( $term->term_id ) );
		}

		return $tags->get_updated_html();
	}

	/**
	 * Add the color variable to core/categories list items (`cat-item-{id}`).
	 */
	public static function filter_categories_list( string $content, array $block ): string {
		$taxonomy = $block['attrs']['taxonomy'] ?? 'category';
		if ( '' === $content || ! in_array( $taxonomy, self::taxonomies(), true ) ) {
			return $content;
		}

		$tags = new \WP_HTML_Tag_Processor( $content );
		while ( $tags->next_tag( 'li' ) ) {
			$class = (string) $tags->get_attribute( 'class' );
			if ( preg_match( '/\bcat-item-(\d+)\b/', $class, $m ) ) {
				$tags->set_attribute( 'style', '--rmc-chip:' . self::get_color( (int) $m[1] ) );
			}
		}

		return $tags->get_updated_html();
	}

	/**
	 * Expose the primary category's color on the post wrapper.
	 *
	 * @param string[] $classes
	 * @return string[]
	 */
	public static function filter_post_class( array $classes, array $css_class, int $post_id ): array {
		if ( in_array( 'category', self::taxonomies(), true ) ) {
			$cats = get_the_category( $post_id );
			if ( ! empty( $cats ) ) {
				$classes[] = 'rmc-primary-' . sanitize_html_class( $cats[0]->slug );
			}
		}
		if ( in_array( Project_CPT::TAX_TYPE, self::taxonomies(), true ) ) {
			$types = get_the_terms( $post_id, Project_CPT::TAX_TYPE );
			if ( ! empty( $types ) && ! is_wp_error( $types ) ) {
				$classes[] = 'rmc-primary-' . sanitize_html_class( $types[0]->slug );
			}
		}
		return $classes;
	}

	/**
	 * Print inline style block with custom properties for term colors (--rmc-cat-{id}).
	 */
	public static function print_frontend_css_variables(): void {
		$terms = get_terms(
			array(
				'taxonomy'   => self::taxonomies(),
				'hide_empty' => false,
			)
		);
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		$css = '';
		foreach ( $terms as $term ) {
			$color = self::get_color( (int) $term->term_id );
			$css  .= sprintf( '--rmc-cat-%d:%s;', (int) $term->term_id, esc_attr( $color ) );
		}

		if ( '' !== $css ) {
			echo '<style id="rmc-term-colors">:root{' . $css . '}</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
