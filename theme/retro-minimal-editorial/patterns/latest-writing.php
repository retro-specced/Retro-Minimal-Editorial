<?php
/**
 * Title: Latest Writing
 * Slug: retro-minimal/latest-writing
 * Categories: retro-minimal
 * Description: Query Loop showing 6 latest blog posts as compact text rows.
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"rme-showcase-section","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group rme-showcase-section" style="margin-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:group {"className":"rme-showcase-header","layout":{"type":"flex","justifyContent":"space-between","alignItems":"baseline"}} -->
	<div class="wp-block-group rme-showcase-header">
		<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var:preset|font-family|mono","fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.04em"}}} -->
		<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--mono);font-size:var(--wp--preset--font-size--small);letter-spacing:0.04em;text-transform:uppercase">// Latest Writing</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"rme-showcase-link","fontSize":"x-small","fontFamily":"mono"} -->
		<p class="rme-showcase-link has-x-small-font-size has-mono-font-family"><a href="/blog/">View all posts &rarr;</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template -->
			<!-- wp:template-part {"slug":"post-row","theme":"retro-minimal-editorial"} /-->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
