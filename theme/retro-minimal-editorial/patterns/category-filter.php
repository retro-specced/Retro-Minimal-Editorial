<?php
/**
 * Title: Category Filter Row
 * Slug: retro-minimal/category-filter
 * Categories: retro-minimal
 * Description: Monospace horizontal category filter bar with All link and chips.
 */

$rme_cats = get_categories( array( 'hide_empty' => false ) );
$rme_current_cat = is_category() ? get_queried_object_id() : 0;
$rme_is_all = ! is_category();
$rme_blog_url = ( $p = get_option( 'page_for_posts' ) ) ? get_permalink( $p ) : home_url( '/blog/' );
?>
<nav class="rme-filter-row" aria-label="<?php esc_attr_e( 'Category filter', 'retro-minimal-editorial' ); ?>">
	<span style="font-weight:700;margin-right:0.25rem;">// CATEGORIES:</span>
	<a href="<?php echo esc_url( $rme_blog_url ); ?>" class="<?php echo $rme_is_all ? 'is-active' : ''; ?>">[ ALL ]</a>
	<?php foreach ( $rme_cats as $rme_cat ) :
		$rme_is_active = ( $rme_current_cat === (int) $rme_cat->term_id );
		$rme_color = function_exists( 'RetroMinimalCore\get_term_color' ) ? RetroMinimalCore\get_term_color( (int) $rme_cat->term_id ) : '#C9C6BD';
	?>
		<a
			href="<?php echo esc_url( get_term_link( $rme_cat ) ); ?>"
			class="rmc-chip<?php echo $rme_is_active ? ' is-active' : ''; ?>"
			style="--rmc-chip:<?php echo esc_attr( $rme_color ); ?>;"
		>
			[ <?php echo esc_html( strtoupper( $rme_cat->name ) ); ?> ]
		</a>
	<?php endforeach; ?>
</nav>
