/**
 * Editor preview for retro-minimal/project-filter (no build step).
 */
( function ( wp ) {
	const el = wp.element.createElement;
	const { useBlockProps } = wp.blockEditor;
	const { useEntityRecords } = wp.coreData;

	wp.blocks.registerBlockType( 'retro-minimal/project-filter', {
		edit() {
			const blockProps = useBlockProps( { className: 'rme-filter-row' } );
			const { records: types, hasResolved } = useEntityRecords( 'taxonomy', 'project_type', { per_page: -1, hide_empty: false } ) || {};

			const defaultChips = [
				{ name: 'CLIENT', color: '#F5C451' },
				{ name: 'PORTFOLIO', color: '#C9BCF2' },
				{ name: 'VIBE-CODED', color: '#9CF2B0' },
			];

			let chipElements = [];
			if ( hasResolved && Array.isArray( types ) && types.length > 0 ) {
				chipElements = types.map( function ( term ) {
					const color = ( term.meta && term.meta.rmc_label_color ) || '#C9C6BD';
					return el(
						'span',
						{
							key: term.id,
							className: 'rmc-chip',
							style: { '--rmc-chip': color, cursor: 'default' },
						},
						'[ ' + ( term.name || '' ).toUpperCase() + ' ]'
					);
				} );
			} else {
				chipElements = defaultChips.map( function ( chip, idx ) {
					return el(
						'span',
						{
							key: idx,
							className: 'rmc-chip',
							style: { '--rmc-chip': chip.color, cursor: 'default' },
						},
						'[ ' + chip.name + ' ]'
					);
				} );
			}

			return el(
				'nav',
				blockProps,
				el( 'span', { style: { fontWeight: '700', marginRight: '0.25rem' } }, '// FILTER:' ),
				el( 'span', { className: 'is-active', style: { padding: '0.2em 0.6em', border: '1px solid var(--wp--preset--color--rule)', background: 'var(--wp--preset--color--ink)', color: 'var(--wp--preset--color--paper)', cursor: 'default' } }, '[ ALL ]' ),
				...chipElements
			);
		},
		save: () => null,
	} );
} )( window.wp );
