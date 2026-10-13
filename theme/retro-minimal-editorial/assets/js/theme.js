/**
 * Retro-Minimal Editorial: front-end responsive enhancements.
 * Repositions showcase links ("View all posts →", "All projects →") beneath
 * their respective query loops on mobile screens (<= 640px).
 */
( function () {
	'use strict';

	function repositionShowcaseLinks() {
		const isMobile = window.innerWidth <= 640;
		const queryBlocks = document.querySelectorAll( '.wp-block-query' );

		queryBlocks.forEach( function ( queryBlock ) {
			const parent = queryBlock.parentElement;
			if ( ! parent ) {
				return;
			}

			// Locate the showcase link pointing to /blog/ or /projects/
			let linkP = parent.querySelector( '.rme-showcase-link' );
			if ( ! linkP ) {
				// Search sibling group preceding the query
				const prev = queryBlock.previousElementSibling;
				if ( prev ) {
					const links = prev.querySelectorAll( 'a' );
					for ( let i = 0; i < links.length; i++ ) {
						const href = links[ i ].getAttribute( 'href' ) || '';
						if ( href.includes( '/blog' ) || href.includes( '/projects' ) ) {
							linkP = links[ i ].closest( 'p' );
							break;
						}
					}
				}
			}

			if ( ! linkP ) {
				return;
			}

			const nextElem = queryBlock.nextElementSibling;
			const isAlreadyAfter = ( nextElem === linkP );

			if ( isMobile ) {
				if ( ! isAlreadyAfter ) {
					// Insert marker in original location if not already created
					let marker = linkP.parentElement.querySelector( '.rme-showcase-link-marker[data-for="' + ( linkP.id || 'showcase' ) + '"]' );
					if ( ! marker ) {
						marker = document.createElement( 'span' );
						marker.className = 'rme-showcase-link-marker';
						marker.style.display = 'none';
						if ( ! linkP.id ) {
							linkP.id = 'rme-link-' + Math.random().toString( 36 ).slice( 2, 8 );
						}
						marker.dataset.for = linkP.id;
						linkP.parentElement.insertBefore( marker, linkP );
					}
					linkP.classList.add( 'rme-showcase-link--mobile' );
					queryBlock.after( linkP );
				}
			} else {
				if ( isAlreadyAfter && linkP.id ) {
					const marker = parent.querySelector( '.rme-showcase-link-marker[data-for="' + linkP.id + '"]' );
					if ( marker && marker.parentElement ) {
						linkP.classList.remove( 'rme-showcase-link--mobile' );
						marker.parentElement.insertBefore( linkP, marker );
					}
				}
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', repositionShowcaseLinks );
	} else {
		repositionShowcaseLinks();
	}

	let resizeTimer;
	window.addEventListener( 'resize', function () {
		clearTimeout( resizeTimer );
		resizeTimer = setTimeout( repositionShowcaseLinks, 100 );
	} );
} )();
