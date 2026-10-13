<?php
/**
 * Title: Projects Page Layout
 * Slug: retro-minimal/projects-page
 * Categories: retro-minimal, pages
 * Description: Full projects showcase pattern with title, type filter bar, and 3-column project grid query loop.
 */
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60)">
	<!-- Page Header -->
	<!-- wp:heading {"level":1,"style":{"typography":{"fontFamily":"var:preset|font-family|serif","fontSize":"var:preset|font-size|xx-large"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
	<h1 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--serif);font-size:var(--wp--preset--font-size--xx-large);margin-bottom:var(--wp--preset--spacing--20)">Projects &amp; Experiments</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|medium"},"color":{"text":"var:preset|color|muted"},"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}}} -->
	<p class="has-muted-color has-text-color" style="font-size:var(--wp--preset--font-size--medium);margin-bottom:var(--wp--preset--spacing--30)">Vibe-coded utilities, editorial software, client builds, and open-source experiments.</p>
	<!-- /wp:paragraph -->

	<!-- Project Type Filter Bar -->
	<!-- wp:pattern {"slug":"retro-minimal/project-type-filter"} /-->

	<!-- Projects Grid Query Loop -->
	<!-- wp:query {"query":{"perPage":9,"pages":0,"offset":0,"postType":"project","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:template-part {"slug":"project-card","theme":"retro-minimal-editorial"} /-->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
		<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var:preset|font-family|mono","fontSize":"var:preset|font-size|small"},"color":{"text":"var:preset|color|muted"},"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
		<p class="has-muted-color has-text-color" style="font-family:var(--wp--preset--font-family--mono);font-size:var(--wp--preset--font-size--small);margin-top:var(--wp--preset--spacing--30)">// NO PROJECTS RECORDED YET</p>
		<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->

		<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"space-between"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
			<!-- wp:query-pagination-previous {"label":"[ \u2190 PREV ]"} /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next {"label":"[ NEXT \u2192 ]"} /-->
		<!-- /wp:query-pagination -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
