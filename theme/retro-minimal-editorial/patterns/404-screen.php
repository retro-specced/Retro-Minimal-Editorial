<?php
/**
 * Title: 404 Error Screen
 * Slug: retro-minimal/404-screen
 * Categories: retro-minimal
 * Description: Monospace retro brutalist 404 error screen.
 */
?>
<!-- wp:group {"className":"is-style-terminal","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-terminal" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50);padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":1,"style":{"typography":{"fontFamily":"var:preset|font-family|mono","fontSize":"var:preset|font-size|large","letterSpacing":"0.04em"}}} -->
	<h1 class="wp-block-heading" style="font-family:var(--wp--preset--font-family--mono);font-size:var(--wp--preset--font-size--large);letter-spacing:0.04em">ERROR 404 &mdash; FILE NOT FOUND</h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"muted","fontSize":"small","fontFamily":"mono"} -->
	<p class="has-muted-color has-text-color has-small-font-size has-mono-font-family">The requested URL could not be located in the file directory. The target document may have moved or been decommissioned.</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"fontSize":"x-small","fontFamily":"mono"} -->
	<p class="has-x-small-font-size has-mono-font-family"><code>&gt; RETURN TO SAFE DIRECTORY:</code></p>
	<!-- /wp:paragraph -->

	<!-- wp:list {"className":"is-style-link-list"} -->
	<ul class="wp-block-list is-style-link-list">
		<li>&rarr; <a href="/">Root Directory (Home)</a></li>
		<li>&rarr; <a href="/blog/">Writing Index (Blog)</a></li>
		<li>&rarr; <a href="/projects/">Project Showcase (Projects)</a></li>
		<li>&rarr; <a href="/portfolio/">Curated Portfolio</a></li>
	</ul>
	<!-- /wp:list -->
</div>
<!-- /wp:group -->
