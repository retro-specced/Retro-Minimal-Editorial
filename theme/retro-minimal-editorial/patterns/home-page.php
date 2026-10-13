<?php
/**
 * Title: Home Page (Personal Hub)
 * Slug: retro-minimal/home-page
 * Categories: retro-minimal, pages
 * Description: Complete personal hub home page pattern with intro, latest writing rows, and featured projects window cards.
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:heading {"level":1,"style":{"typography":{"fontFamily":"var:preset|font-family|serif","fontSize":"var:preset|font-size|xx-large","lineHeight":"1.05"}}} -->
	<h1 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--serif);font-size:var(--wp--preset--font-size--xx-large);line-height:1.05">Gerald</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"muted","fontSize":"medium","style":{"typography":{"lineHeight":"1.4"}}} -->
	<p class="has-muted-color has-text-color has-medium-font-size" style="line-height:1.4">Software engineer &amp; designer building thoughtful tools, editorial interfaces, and small-web experiments.</p>
	<!-- /wp:paragraph -->

	<!-- wp:list {"className":"is-style-link-list"} -->
	<ul class="wp-block-list is-style-link-list">
		<li><span class="rme-link-label">&rarr; GitHub</span><span class="rme-link-leader" aria-hidden="true"></span><a href="https://github.com/gerald">github.com/gerald</a></li>
		<li><span class="rme-link-label">&rarr; Writing</span><span class="rme-link-leader" aria-hidden="true"></span><a href="/blog/">Read latest posts</a></li>
		<li><span class="rme-link-label">&rarr; Projects</span><span class="rme-link-leader" aria-hidden="true"></span><a href="/projects/">View showcase</a></li>
	</ul>
	<!-- /wp:list -->
</div>
<!-- /wp:group -->

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

<!-- wp:group {"align":"wide","className":"rme-showcase-section","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide rme-showcase-section" style="margin-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:group {"className":"rme-showcase-header","layout":{"type":"flex","justifyContent":"space-between","alignItems":"baseline"}} -->
	<div class="wp-block-group rme-showcase-header">
		<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var:preset|font-family|mono","fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.04em"}}} -->
		<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--mono);font-size:var(--wp--preset--font-size--small);letter-spacing:0.04em;text-transform:uppercase">// Featured Projects</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"rme-showcase-link","fontSize":"x-small","fontFamily":"mono"} -->
		<p class="rme-showcase-link has-x-small-font-size has-mono-font-family"><a href="/projects/">All projects &rarr;</a></p>
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
