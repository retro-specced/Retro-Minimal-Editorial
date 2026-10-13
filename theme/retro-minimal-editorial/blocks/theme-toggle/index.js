/**
 * Editor UI for retro-minimal/theme-toggle: a static preview (no build step).
 */
( function ( wp ) {
	const el = wp.element.createElement;
	const { __ } = wp.i18n;
	const { useBlockProps } = wp.blockEditor;

	wp.blocks.registerBlockType( 'retro-minimal/theme-toggle', {
		edit() {
			return el(
				'div',
				useBlockProps( { className: 'rme-theme-toggle-wrap rme-theme-toggle--editor-preview' } ),
				el(
					'button',
					{
						type: 'button',
						className: 'rme-theme-toggle',
						disabled: true,
					},
					el(
						'span',
						{ className: 'rme-theme-toggle__label' },
						'[ \u25D0 THEME: AUTO ]'
					)
				)
			);
		},
		save: () => null,
	} );
} )( window.wp );
