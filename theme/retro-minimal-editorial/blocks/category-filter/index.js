/**
 * Editor preview for retro-minimal/category-filter (no build step).
 */
( function ( wp ) {
	const el = wp.element.createElement;
	const { useBlockProps } = wp.blockEditor;
	const { useEntityRecords } = wp.coreData;

	wp.blocks.registerBlockType( 'retro-minimal/category-filter', {
		edit() {
			const blockProps = useBlockProps( { className: 'rme-filter-row' } );
			const { records: categories, hasResolved } = useEntityRecords( 'taxonomy', 'category', { per_page: -1, hide_empty: false } ) || {};

			const defaultChips = [
				{ name: 'DEV', color: '#8ED8DB' },
				{ name: 'ESSAYS', color: '#C9BCF2' },
				{ name: 'LIFE', color: '#F4A9C8' },
				{ name: 'NOTES', color: '#EAD9A6' },
				{ name: 'VIBE-CODING', color: '#9CF2B0' },
			];

			let chipElements = [];
			if ( hasResolved && Array.isArray( categories ) && categories.length > 0 ) {
				chipElements = categories.map( function ( cat ) {
					const color = ( cat.meta && cat.meta.rmc_label_color ) || '#C9C6BD';
					return el(
						'span',
						{
							key: cat.id,
							className: 'rmc-chip',
							style: { '--rmc-chip': color, cursor: 'default' },
						},
						'[ ' + ( cat.name || '' ).toUpperCase() + ' ]'
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
				el( 'span', { style: { fontWeight: '700', marginRight: '0.25rem' } }, '// CATEGORIES:' ),
				el( 'span', { className: 'is-active', style: { padding: '0.2em 0.6em', border: '1px solid var(--wp--preset--color--rule)', background: 'var(--wp--preset--color--ink)', color: 'var(--wp--preset--color--paper)', cursor: 'default' } }, '[ ALL ]' ),
				...chipElements
			);
		},
		save: () => null,
	} );
} )( window.wp );
