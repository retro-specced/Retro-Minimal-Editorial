<?php
/**
 * Title: Project Type Filter Row
 * Slug: retro-minimal/project-type-filter
 * Categories: retro-minimal
 * Description: Monospace filter bar for project showcase.
 */

$rme_types = taxonomy_exists( 'project_type' ) ? get_terms( array( 'taxonomy' => 'project_type', 'hide_empty' => false ) ) : array();
$rme_current_term = is_tax( 'project_type' ) ? get_queried_object_id() : 0;
$rme_is_all = ! is_tax( 'project_type' );
$rme_archive_url = get_post_type_archive_link( 'project' ) ?: home_url( '/projects/' );
?>
<nav class="rme-filter-row" aria-label="<?php esc_attr_e( 'Project type filter', 'retro-minimal-editorial' ); ?>">
	<span style="font-weight:700;margin-right:0.25rem;">// FILTER:</span>
	<a href="<?php echo esc_url( $rme_archive_url ); ?>" class="<?php echo $rme_is_all ? 'is-active' : ''; ?>">[ ALL ]</a>
	<?php if ( ! empty( $rme_types ) && ! is_wp_error( $rme_types ) ) : ?>
		<?php foreach ( $rme_types as $rme_term ) :
			$rme_is_active = ( $rme_current_term === (int) $rme_term->term_id );
			$rme_color = function_exists( 'RetroMinimalCore\get_term_color' ) ? RetroMinimalCore\get_term_color( (int) $rme_term->term_id ) : '#C9C6BD';
		?>
			<a
				href="<?php echo esc_url( get_term_link( $rme_term ) ); ?>"
				class="rmc-chip<?php echo $rme_is_active ? ' is-active' : ''; ?>"
				style="--rmc-chip:<?php echo esc_attr( $rme_color ); ?>;"
			>
				[ <?php echo esc_html( strtoupper( $rme_term->name ) ); ?> ]
			</a>
		<?php endforeach; ?>
	<?php endif; ?>
</nav>
