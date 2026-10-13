<?php
/**
 * Render: retro-minimal/category-filter
 *
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 *
 * @package RetroMinimalEditorial
 */

defined( 'ABSPATH' ) || exit;

$rme_cats = get_categories( array( 'hide_empty' => false ) );
$rme_current_cat = is_category() ? get_queried_object_id() : 0;
$rme_is_all = ! is_category();

$rme_blog_url = '';
if ( is_page_template( 'page-blog' ) || ( is_page() && 'blog' === get_post()->post_name ) ) {
	$rme_blog_url = get_permalink();
} else {
	$rme_blog_page_id = (int) get_option( 'page_for_posts' );
	if ( $rme_blog_page_id ) {
		$rme_blog_url = get_permalink( $rme_blog_page_id );
	} else {
		$rme_blog_pages = get_posts(
			array(
				'post_type'   => 'page',
				'meta_key'    => '_wp_page_template',
				'meta_value'  => 'page-blog',
				'numberposts' => 1,
			)
		);
		if ( ! empty( $rme_blog_pages ) ) {
			$rme_blog_url = get_permalink( $rme_blog_pages[0]->ID );
		} else {
			$page_by_path = get_page_by_path( 'blog' );
			$rme_blog_url = $page_by_path ? get_permalink( $page_by_path ) : home_url( '/blog/' );
		}
	}
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'      => 'rme-filter-row',
		'aria-label' => __( 'Category filter', 'retro-minimal-editorial' ),
	)
);
?>
<nav <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<span style="font-weight:700;margin-right:0.25rem;">// CATEGORIES:</span>
	<a href="<?php echo esc_url( $rme_blog_url ); ?>" class="<?php echo $rme_is_all ? 'is-active' : ''; ?>">[ ALL ]</a>
	<?php if ( ! empty( $rme_cats ) && ! is_wp_error( $rme_cats ) ) : ?>
		<?php foreach ( $rme_cats as $rme_cat ) :
			$rme_is_active = ( $rme_current_cat === (int) $rme_cat->term_id );
			$rme_color = function_exists( 'RetroMinimalCore\get_term_color' ) ? RetroMinimalCore\get_term_color( (int) $rme_cat->term_id ) : '#C9C6BD';
		?>
			<a
				href="<?php echo esc_url( get_category_link( $rme_cat ) ); ?>"
				class="rmc-chip<?php echo $rme_is_active ? ' is-active' : ''; ?>"
				style="--rmc-chip:<?php echo esc_attr( $rme_color ); ?>;"
			>
				[ <?php echo esc_html( strtoupper( $rme_cat->name ) ); ?> ]
			</a>
		<?php endforeach; ?>
	<?php endif; ?>
</nav>
