<?php
/**
 * Title: Hub Intro
 * Slug: retro-minimal/hub-intro
 * Categories: retro-minimal
 * Description: Header intro section with name, bio, and quick links.
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
