/**
 * Editor integrations for Retro-Minimal Editorial.
 *
 * Adds "Table of Contents" settings panel to the post sidebar
 * to control the `rme_hide_toc` post meta toggle.
 */
( function ( wp ) {
	const { registerPlugin } = wp.plugins;
	const { PluginDocumentSettingPanel } = wp.editor || wp.editPost;
	const { ToggleControl } = wp.components;
	const { useSelect, useDispatch } = wp.data;
	const { __ } = wp.i18n;
	const el = wp.element.createElement;

	if ( ! PluginDocumentSettingPanel || ! registerPlugin ) {
		return;
	}

	function TocSettingsPanel() {
		const postType = useSelect( ( select ) => select( 'core/editor' ).getCurrentPostType(), [] );
		const meta = useSelect( ( select ) => select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {}, [] );
		const { editPost } = useDispatch( 'core/editor' );

		// Only show on singular posts/projects
		if ( ! [ 'post', 'project', 'page' ].includes( postType ) ) {
			return null;
		}

		const hideToc = Boolean( meta.rme_hide_toc );

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'rme-toc-settings',
				title: __( 'Table of Contents', 'retro-minimal-editorial' ),
				icon: 'list-view',
			},
			el( ToggleControl, {
				label: __( 'Hide table of contents on this post', 'retro-minimal-editorial' ),
				help: hideToc
					? __( 'TOC block will not render on this post.', 'retro-minimal-editorial' )
					: __( 'TOC renders automatically if there are 3+ headings.', 'retro-minimal-editorial' ),
				checked: hideToc,
				onChange: ( val ) => {
					editPost( { meta: { ...meta, rme_hide_toc: val } } );
				},
				__nextHasNoMarginBottom: true,
			} )
		);
	}

	registerPlugin( 'rme-editor-settings', {
		render: TocSettingsPanel,
		icon: 'admin-appearance',
	} );
} )( window.wp );
