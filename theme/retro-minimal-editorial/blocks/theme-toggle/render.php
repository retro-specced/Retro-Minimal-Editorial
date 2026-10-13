<?php
/**
 * Render: retro-minimal/theme-toggle
 *
 * @var array    $attributes
 * @var string   $content
 * @var WP_Block $block
 *
 * @package RetroMinimalEditorial
 */

defined( 'ABSPATH' ) || exit;

$rme_wrapper_attrs = get_block_wrapper_attributes( array( 'class' => 'rme-theme-toggle-wrap' ) );
?>
<div <?php echo $rme_wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<button
		type="button"
		class="rme-theme-toggle"
		id="rme-theme-toggle"
		aria-label="<?php esc_attr_e( 'Toggle dark or light theme', 'retro-minimal-editorial' ); ?>"
		aria-live="polite"
	>
		<span class="rme-theme-toggle__label" aria-hidden="true">[ <span class="rme-theme-toggle__icon">&#9680;</span> <span class="rme-theme-toggle__state">AUTO</span> ]</span>
		<span class="screen-reader-text"><?php esc_html_e( 'Toggle color scheme', 'retro-minimal-editorial' ); ?></span>
	</button>
</div>
