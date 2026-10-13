/**
 * Editor UI for retro-minimal/project-meta: dynamic editor preview (no build step).
 */
( function ( wp ) {
	const el = wp.element.createElement;
	const { __ } = wp.i18n;
	const { useBlockProps } = wp.blockEditor || wp.editor;
	const { useSelect } = wp.data;

	wp.blocks.registerBlockType( 'retro-minimal/project-meta', {
		edit() {
			const blockProps = useBlockProps( {
				className: 'wp-block-group is-style-bordered rme-project-meta',
			} );

			// Read current meta from editor state if available
			const meta = useSelect( ( select ) => {
				const editor = select( 'core/editor' );
				return editor ? ( editor.getEditedPostAttribute( 'meta' ) || {} ) : {};
			}, [] );

			const year = ( meta.rmc_project_year !== undefined && meta.rmc_project_year !== '' )
				? meta.rmc_project_year
				: '2026';
			const status = ( meta.rmc_project_status !== undefined && meta.rmc_project_status !== '' )
				? meta.rmc_project_status
				: 'Active / Live';
			const role = ( meta.rmc_project_role !== undefined && meta.rmc_project_role !== '' )
				? meta.rmc_project_role
				: 'Design & Code';

			const showLive = meta.rmc_project_show_live !== false;
			const liveUrl = meta.rmc_project_live_url || '';
			const showSource = meta.rmc_project_show_source !== false;
			const sourceUrl = meta.rmc_project_source_url || '';

			// Show buttons if toggled on AND has URL, or as placeholder in template editor
			const hasLive = showLive && ( liveUrl !== '' || ( ! meta.rmc_project_year && ! meta.rmc_project_live_url ) );
			const hasSource = showSource && ( sourceUrl !== '' || ( ! meta.rmc_project_year && ! meta.rmc_project_source_url ) );

			return el(
				'div',
				blockProps,
				( hasLive || hasSource ) && el(
					'div',
					{ className: 'wp-block-buttons', style: { marginBottom: 'var(--wp--preset--spacing--30)' } },
					hasLive && el(
						'div',
						{ className: 'wp-block-button is-style-default' },
						el( 'a', { className: 'wp-block-button__link', href: '#', onClick: ( e ) => e.preventDefault() }, 'Live Demo \u2197' )
					),
					hasSource && el(
						'div',
						{ className: 'wp-block-button is-style-ghost' },
						el( 'a', { className: 'wp-block-button__link', href: '#', onClick: ( e ) => e.preventDefault() }, 'Source Code \u2197' )
					)
				),
				el(
					'ul',
					{ className: 'wp-block-list is-style-link-list', style: { margin: 0 } },
					el( 'li', null, el( 'strong', null, __( 'Year', 'retro-minimal-editorial' ) ), ' .............................................. ' + year ),
					el( 'li', null, el( 'strong', null, __( 'Status', 'retro-minimal-editorial' ) ), ' ............................................ ' + status ),
					el( 'li', null, el( 'strong', null, __( 'Role', 'retro-minimal-editorial' ) ), ' .............................................. ' + role )
				)
			);
		},
		save: () => null,
	} );
} )( window.wp );
