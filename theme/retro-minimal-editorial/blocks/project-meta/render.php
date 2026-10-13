<?php
/**
 * Render: retro-minimal/project-meta
 *
 * Dynamically displays project action buttons and details based on post meta.
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

$rme_year         = trim( (string) get_post_meta( $rme_post_id, 'rmc_project_year', true ) );
$rme_status       = trim( (string) get_post_meta( $rme_post_id, 'rmc_project_status', true ) );
$rme_role         = trim( (string) get_post_meta( $rme_post_id, 'rmc_project_role', true ) );
$rme_live_url     = trim( (string) get_post_meta( $rme_post_id, 'rmc_project_live_url', true ) );
$rme_show_live    = get_post_meta( $rme_post_id, 'rmc_project_show_live', true );
$rme_source_url   = trim( (string) get_post_meta( $rme_post_id, 'rmc_project_source_url', true ) );
$rme_show_source  = get_post_meta( $rme_post_id, 'rmc_project_show_source', true );

$rme_has_live   = ( false !== $rme_show_live && '0' !== $rme_show_live && 0 !== $rme_show_live ) && '' !== $rme_live_url;
$rme_has_source = ( false !== $rme_show_source && '0' !== $rme_show_source && 0 !== $rme_show_source ) && '' !== $rme_source_url;
$rme_has_items  = '' !== $rme_year || '' !== $rme_status || '' !== $rme_role;

// If no buttons and no details exist, render nothing.
if ( ! $rme_has_live && ! $rme_has_source && ! $rme_has_items ) {
	return;
}

$rme_wrapper_attrs = get_block_wrapper_attributes(
	array( 'class' => 'wp-block-group is-style-bordered rme-project-meta' )
);
?>
<div <?php echo $rme_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $rme_has_live || $rme_has_source ) : ?>
		<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex" style="display:flex;flex-wrap:wrap;align-items:center;gap:var(--wp--preset--spacing--20,0.75rem);margin-bottom:<?php echo $rme_has_items ? 'var(--wp--preset--spacing--30)' : '0'; ?>;">
			<?php if ( $rme_has_live ) : ?>
				<div class="wp-block-button is-style-default">
					<a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $rme_live_url ); ?>" target="_blank" rel="noopener">Live Demo &#8599;</a>
				</div>
			<?php endif; ?>
			<?php if ( $rme_has_source ) : ?>
				<div class="wp-block-button is-style-ghost">
					<a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $rme_source_url ); ?>" target="_blank" rel="noopener">Source Code &#8599;</a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $rme_has_items ) : ?>
		<ul class="wp-block-list is-style-link-list" style="margin:0;">
			<?php if ( '' !== $rme_year ) : ?>
				<li><strong>Year</strong> .............................................. <?php echo esc_html( $rme_year ); ?></li>
			<?php endif; ?>
			<?php if ( '' !== $rme_status ) : ?>
				<li><strong>Status</strong> ............................................ <?php echo esc_html( $rme_status ); ?></li>
			<?php endif; ?>
			<?php if ( '' !== $rme_role ) : ?>
				<li><strong>Role</strong> .............................................. <?php echo esc_html( $rme_role ); ?></li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>
</div>
