<?php
/**
 * Render: retro-minimal/project-filter
 *
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 *
 * @package RetroMinimalEditorial
 */

defined( 'ABSPATH' ) || exit;

$rme_types = taxonomy_exists( 'project_type' ) ? get_terms( array( 'taxonomy' => 'project_type', 'hide_empty' => false ) ) : array();
$rme_current_term = is_tax( 'project_type' ) ? get_queried_object_id() : 0;
$rme_is_all = ! is_tax( 'project_type' );

$rme_archive_url = '';
if ( is_page_template( 'page-projects' ) || ( is_page() && 'projects' === get_post()->post_name ) ) {
	$rme_archive_url = get_permalink();
} else {
	$rme_projects_pages = get_posts(
		array(
			'post_type'   => 'page',
			'meta_key'    => '_wp_page_template',
			'meta_value'  => 'page-projects',
			'numberposts' => 1,
		)
	);
	if ( ! empty( $rme_projects_pages ) ) {
		$rme_archive_url = get_permalink( $rme_projects_pages[0]->ID );
	} else {
		$rme_archive_url = get_post_type_archive_link( 'project' );
		if ( ! $rme_archive_url ) {
			$page_by_path = get_page_by_path( 'projects' );
			$rme_archive_url = $page_by_path ? get_permalink( $page_by_path ) : home_url( '/projects/' );
		}
	}
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'      => 'rme-filter-row',
		'aria-label' => __( 'Project type filter', 'retro-minimal-editorial' ),
	)
);
?>
<nav <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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
