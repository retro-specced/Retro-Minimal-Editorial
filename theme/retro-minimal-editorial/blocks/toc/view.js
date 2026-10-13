/**
 * Highlights the TOC entry for the section currently being read.
 */
( function () {
	const toc = document.querySelector( '.rme-toc' );
	if ( ! toc || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	const links = new Map();
	toc.querySelectorAll( 'a[href^="#"]' ).forEach( ( a ) => {
		const target = document.getElementById( decodeURIComponent( a.hash.slice( 1 ) ) );
		if ( target ) {
			links.set( target, a );
		}
	} );
	if ( ! links.size ) {
		return;
	}

	let current = null;
	const visible = new Set();

	const observer = new IntersectionObserver(
		( entries ) => {
			entries.forEach( ( e ) => ( e.isIntersecting ? visible.add( e.target ) : visible.delete( e.target ) ) );
			const first = [ ...links.keys() ].find( ( h ) => visible.has( h ) );
			if ( first && first !== current ) {
				links.get( current )?.removeAttribute( 'aria-current' );
				links.get( first ).setAttribute( 'aria-current', 'true' );
				current = first;
			}
		},
		{ rootMargin: '0px 0px -70% 0px' }
	);

	links.forEach( ( _a, heading ) => observer.observe( heading ) );
} )();
