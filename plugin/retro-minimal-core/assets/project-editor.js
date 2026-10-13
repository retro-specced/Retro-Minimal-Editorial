/**
 * Retro-Minimal Core: Project Details Editor Sidebar
 *
 * Adds "Project Details" settings panel to the Gutenberg editor sidebar
 * when editing a Project. Controls Year, Status, Role, Live Demo URL, and Source Code URL.
 */
( function ( wp ) {
	'use strict';

	const { registerPlugin } = wp.plugins || {};
	const PluginDocumentSettingPanel = ( wp.editPost && wp.editPost.PluginDocumentSettingPanel ) || ( wp.editor && wp.editor.PluginDocumentSettingPanel );
	const { TextControl, ToggleControl } = wp.components || {};
	const { useSelect, useDispatch } = wp.data || {};
	const { __ } = wp.i18n || {};
	const el = wp.element ? wp.element.createElement : null;

	if ( ! PluginDocumentSettingPanel || ! registerPlugin || ! el ) {
		return;
	}

	function ProjectDetailsPanel() {
		const postType = useSelect( ( select ) => {
			const editor = select( 'core/editor' );
			return editor ? editor.getCurrentPostType() : null;
		}, [] );

		const meta = useSelect( ( select ) => {
			const editor = select( 'core/editor' );
			return editor ? ( editor.getEditedPostAttribute( 'meta' ) || {} ) : {};
		}, [] );

		const { editPost } = useDispatch( 'core/editor' ) || {};

		if ( postType !== 'project' || ! editPost ) {
			return null;
		}

		function updateMeta( key, value ) {
			editPost( {
				meta: {
					...meta,
					[ key ]: value,
				},
			} );
		}

		const showLive = meta.rmc_project_show_live !== false && meta.rmc_project_show_live !== '0' && meta.rmc_project_show_live !== 0;
		const showSource = meta.rmc_project_show_source !== false && meta.rmc_project_show_source !== '0' && meta.rmc_project_show_source !== 0;

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'rmc-project-details',
				title: __( 'Project Details', 'retro-minimal-core' ),
				icon: 'desktop',
			},
			el( TextControl, {
				label: __( 'Year', 'retro-minimal-core' ),
				help: __( 'Release or creation year (e.g. 2026).', 'retro-minimal-core' ),
				value: meta.rmc_project_year || '',
				onChange: ( val ) => updateMeta( 'rmc_project_year', val ),
				__nextHasNoMarginBottom: true,
			} ),
			el( TextControl, {
				label: __( 'Status', 'retro-minimal-core' ),
				help: __( 'e.g. Active / Live, In Progress, Archived.', 'retro-minimal-core' ),
				value: meta.rmc_project_status || '',
				onChange: ( val ) => updateMeta( 'rmc_project_status', val ),
				__nextHasNoMarginBottom: true,
			} ),
			el( TextControl, {
				label: __( 'Role', 'retro-minimal-core' ),
				help: __( 'Your role (e.g. Design & Code, Full-Stack).', 'retro-minimal-core' ),
				value: meta.rmc_project_role || '',
				onChange: ( val ) => updateMeta( 'rmc_project_role', val ),
				__nextHasNoMarginBottom: true,
			} ),
			el( 'hr', { style: { margin: '14px 0', border: 'none', borderTop: '1px solid #ddd' } } ),
			el( ToggleControl, {
				label: __( 'Show Live Demo Button', 'retro-minimal-core' ),
				help: showLive ? __( 'Button will display if URL is provided.', 'retro-minimal-core' ) : __( 'Button hidden.', 'retro-minimal-core' ),
				checked: showLive,
				onChange: ( val ) => updateMeta( 'rmc_project_show_live', val ),
				__nextHasNoMarginBottom: true,
			} ),
			showLive && el( TextControl, {
				label: __( 'Live Demo URL', 'retro-minimal-core' ),
				type: 'url',
				placeholder: 'https://...',
				value: meta.rmc_project_live_url || '',
				onChange: ( val ) => updateMeta( 'rmc_project_live_url', val ),
				__nextHasNoMarginBottom: true,
			} ),
			el( 'hr', { style: { margin: '14px 0', border: 'none', borderTop: '1px solid #ddd' } } ),
			el( ToggleControl, {
				label: __( 'Show Source Code Button', 'retro-minimal-core' ),
				help: showSource ? __( 'Button will display if URL is provided.', 'retro-minimal-core' ) : __( 'Button hidden.', 'retro-minimal-core' ),
				checked: showSource,
				onChange: ( val ) => updateMeta( 'rmc_project_show_source', val ),
				__nextHasNoMarginBottom: true,
			} ),
			showSource && el( TextControl, {
				label: __( 'Source Code URL', 'retro-minimal-core' ),
				type: 'url',
				placeholder: 'https://github.com/...',
				value: meta.rmc_project_source_url || '',
				onChange: ( val ) => updateMeta( 'rmc_project_source_url', val ),
				__nextHasNoMarginBottom: true,
			} )
		);
	}

	registerPlugin( 'rmc-project-editor', {
		render: ProjectDetailsPanel,
		icon: 'desktop',
	} );
} )( window.wp );
