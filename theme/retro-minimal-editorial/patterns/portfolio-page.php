<?php
/**
 * Title: Portfolio Page Layout
 * Slug: retro-minimal/portfolio-page
 * Categories: retro-minimal
 * Description: Curated portfolio page with intro, selected portfolio work, skills, and links.
 */
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60)">
	<!-- Intro Header -->
	<!-- wp:heading {"level":1,"style":{"typography":{"fontFamily":"var:preset|font-family|serif","fontSize":"var:preset|font-size|xx-large"}}} -->
	<h1 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--serif);font-size:var(--wp--preset--font-size--xx-large)">Selected Work &amp; Portfolio</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"muted","fontSize":"medium"} -->
	<p class="has-muted-color has-text-color has-medium-font-size">A curated archive of production work, editorial websites, and client software engineering.</p>
	<!-- /wp:paragraph -->

	<!-- wp:separator {"className":"is-style-dashed"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity is-style-dashed"/>
	<!-- /wp:separator -->

	<!-- Portfolio Query Loop -->
	<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var:preset|font-family|mono","fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.04em"}}} -->
	<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--mono);font-size:var(--wp--preset--font-size--small);letter-spacing:0.04em;text-transform:uppercase">// Selected Projects</h2>
	<!-- /wp:heading -->

	<!-- wp:query {"query":{"perPage":6,"pages":0,"offset":0,"postType":"project","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":{"project_type":["portfolio"]}}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
			<!-- wp:template-part {"slug":"project-card","theme":"retro-minimal-editorial"} /-->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

	<!-- Skills & Tools -->
	<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var:preset|font-family|mono","fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.04em"},"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
	<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--mono);font-size:var(--wp--preset--font-size--small);letter-spacing:0.04em;text-transform:uppercase;margin-top:var(--wp--preset--spacing--50)">// Skills &amp; Stack</h2>
	<!-- /wp:heading -->

	<!-- wp:list {"className":"is-style-link-list"} -->
	<ul class="wp-block-list is-style-link-list">
		<li><strong>Engineering</strong> ..................................... TypeScript · React · Next.js · Node.js · Python · PHP / WordPress</li>
		<li><strong>Architecture</strong> .................................... Full Site Editing · Headless APIs · PostgreSQL · Tailwind / Vanilla CSS</li>
		<li><strong>Design</strong> .......................................... Typography · Design Systems · Neo-Brutalism · Editorial Layouts</li>
	</ul>
	<!-- /wp:list -->

	<!-- Socials & Contact -->
	<!-- wp:heading {"level":2,"style":{"typography":{"fontFamily":"var:preset|font-family|mono","fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.04em"},"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
	<h2 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--mono);font-size:var(--wp--preset--font-size--small);letter-spacing:0.04em;text-transform:uppercase;margin-top:var(--wp--preset--spacing--50)">// Inquiries &amp; Contact</h2>
	<!-- /wp:heading -->

	<!-- wp:pattern {"slug":"retro-minimal/link-list"} /-->
</div>
<!-- /wp:group -->
