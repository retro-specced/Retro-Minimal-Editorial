/**
 * Editor UI for retro-minimal/reading-time: static preview (no build step).
 */
( function ( wp ) {
	const el = wp.element.createElement;
	const { useBlockProps } = wp.blockEditor;

	wp.blocks.registerBlockType( 'retro-minimal/reading-time', {
		edit() {
			return el(
				'span',
				useBlockProps( { className: 'rme-reading-time' } ),
				'4 min'
			);
		},
		save: () => null,
	} );
} )( window.wp );
