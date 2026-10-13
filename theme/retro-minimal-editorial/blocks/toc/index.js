/**
 * Editor UI for retro-minimal/toc: a static preview (no build step).
 */
( function ( wp ) {
	const el = wp.element.createElement;
	const { __ } = wp.i18n;
	const { useBlockProps, InspectorControls } = wp.blockEditor;
	const { PanelBody, RangeControl } = wp.components;

	wp.blocks.registerBlockType( 'retro-minimal/toc', {
		edit( { attributes, setAttributes } ) {
			return el(
				'div',
				useBlockProps( { className: 'rme-toc rme-toc--placeholder' } ),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Settings', 'retro-minimal-editorial' ) },
						el( RangeControl, {
							label: __( 'Minimum headings to show', 'retro-minimal-editorial' ),
							value: attributes.minHeadings,
							min: 1,
							max: 10,
							onChange: ( v ) => setAttributes( { minHeadings: v || 3 } ),
							__nextHasNoMarginBottom: true,
						} )
					)
				),
				el( 'p', { className: 'rme-toc__title' }, __( 'Contents', 'retro-minimal-editorial' ) ),
				el(
					'ol',
					{ className: 'rme-toc__list' },
					el( 'li', null, __( 'Built from the post\u2019s H2/H3 headings', 'retro-minimal-editorial' ) ),
					el( 'li', null, __( 'Shown when there are at least ', 'retro-minimal-editorial' ) + attributes.minHeadings )
				)
			);
		},
		save: () => null,
	} );
} )( window.wp );
