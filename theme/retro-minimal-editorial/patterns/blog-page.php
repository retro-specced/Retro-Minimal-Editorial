<?php
/**
 * Title: Blog Archive Layout
 * Slug: retro-minimal/blog-page
 * Categories: retro-minimal, pages
 * Description: Full blog archive page pattern with title, category filter bar, and post rows query loop with dot leaders.
 */
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60)">
	<!-- Page Header -->
	<!-- wp:heading {"level":1,"style":{"typography":{"fontFamily":"var:preset|font-family|serif","fontSize":"var:preset|font-size|xx-large"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
	<h1 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--serif);font-size:var(--wp--preset--font-size--xx-large);margin-bottom:var(--wp--preset--spacing--20)">Writing &amp; Notes</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"muted","fontSize":"medium","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}}} -->
	<p class="has-muted-color has-text-color has-medium-font-size" style="margin-bottom:var(--wp--preset--spacing--30)">Long-form essays, technical notes, architecture writeups, and dispatches.</p>
	<!-- /wp:paragraph -->

	<!-- Category Filter Bar -->
	<!-- wp:pattern {"slug":"retro-minimal/category-filter"} /-->

	<!-- Blog Query Loop -->
	<!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template -->
			<!-- wp:template-part {"slug":"post-row","theme":"retro-minimal-editorial"} /-->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
		<!-- wp:paragraph {"textColor":"muted","fontSize":"small","fontFamily":"mono","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
		<p class="has-muted-color has-text-color has-small-font-size has-mono-font-family" style="margin-top:var(--wp--preset--spacing--30)">// NO POSTS FOUND IN ARCHIVE</p>
		<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->

		<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"space-between"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
			<!-- wp:query-pagination-previous {"label":"[ \u2190 PREV ]"} /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next {"label":"[ NEXT \u2192 ]"} /-->
		<!-- /wp:query-pagination -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
