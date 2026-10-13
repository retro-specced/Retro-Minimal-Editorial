<?php
/**
 * Title: Featured Projects
 * Slug: retro-minimal/featured-projects
 * Categories: retro-minimal
 * Description: Query Loop showing 3 project window-cards in a grid.
 */
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","alignItems":"baseline"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var:preset|font-family|mono","fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.04em"}}} -->
		<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--mono);font-size:var(--wp--preset--font-size--small);letter-spacing:0.04em;text-transform:uppercase">// Featured Projects</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var:preset|font-family|mono","fontSize":"var:preset|font-size|x-small"}}} -->
		<p style="font-family:var(--wp--preset--font-family--mono);font-size:var(--wp--preset--font-size--x-small)"><a href="/projects/">All projects &rarr;</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"query":{"perPage":3,"pages":0,"offset":0,"postType":"project","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:template-part {"slug":"project-card","theme":"retro-minimal-editorial"} /-->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
