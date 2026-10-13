<?php
/**
 * Title: Project Meta
 * Slug: retro-minimal/project-meta
 * Categories: retro-minimal
 * Description: Action buttons (Live / Source) and project details list for single project headers.
 */
?>
<!-- wp:group {"className":"is-style-bordered","style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-bordered" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--40)">
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-default"} -->
		<div class="wp-block-button is-style-default"><a class="wp-block-button__link" href="https://example.com" target="_blank" rel="noopener">Live Demo &#8599;</a></div>
		<!-- /wp:button -->
		<!-- wp:button {"className":"is-style-ghost"} -->
		<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link" href="https://github.com/gerald" target="_blank" rel="noopener">Source Code &#8599;</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

	<!-- wp:list {"className":"is-style-link-list","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
	<ul class="wp-block-list is-style-link-list" style="margin-top:var(--wp--preset--spacing--30)">
		<li><strong>Year</strong> .............................................. 2026</li>
		<li><strong>Status</strong> ............................................ Active / Live</li>
		<li><strong>Role</strong> .............................................. Design &amp; Code</li>
	</ul>
	<!-- /wp:list -->
</div>
<!-- /wp:group -->
