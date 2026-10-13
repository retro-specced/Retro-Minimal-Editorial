/**
 * Retro-Minimal Core — term label color field.
 * Uses WordPress' bundled wp-color-picker (no build step).
 */
( function ( $ ) {
	'use strict';

	const cfg = window.rmcTermColor || {};
	const { __, sprintf } = wp.i18n;
	const palette = Object.values( cfg.swatches || {} );

	function luminance( hex ) {
		let h = String( hex ).replace( '#', '' );
		if ( h.length === 3 ) {
			h = h.split( '' ).map( ( c ) => c + c ).join( '' );
		}
		const ch = [ 0, 2, 4 ].map( ( i ) => {
			const c = parseInt( h.substr( i, 2 ), 16 ) / 255;
			return c <= 0.03928 ? c / 12.92 : Math.pow( ( c + 0.055 ) / 1.055, 2.4 );
		} );
		return 0.2126 * ch[ 0 ] + 0.7152 * ch[ 1 ] + 0.0722 * ch[ 2 ];
	}

	function contrast( a, b ) {
		const la = luminance( a );
		const lb = luminance( b );
		return ( Math.max( la, lb ) + 0.05 ) / ( Math.min( la, lb ) + 0.05 );
	}

	function update( $wrap, value ) {
		const color = /^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test( value ) ? value : cfg.fallback;
		$wrap.find( '.rmc-preview' ).css( '--swatch', color );
		$wrap.find( '.rmc-swatch' ).each( function () {
			const active = value && this.dataset.color.toLowerCase() === value.toLowerCase();
			this.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
		} );

		const ratio = contrast( color, cfg.ink );
		const $warn = $wrap.find( '.rmc-contrast-warning' );
		if ( ratio < 4.5 ) {
			$warn
				.text(
					sprintf(
						/* translators: %s: contrast ratio, e.g. 3.2 */
						__( '⚠ Low contrast: %s:1 against the label text. Aim for at least 4.5:1, so pick a lighter color.', 'retro-minimal-core' ),
						ratio.toFixed( 1 )
					)
				)
				.prop( 'hidden', false );
		} else {
			$warn.text( '' ).prop( 'hidden', true );
		}
	}

	function setup( $wrap ) {
		const $input = $wrap.find( '.rmc-color-field' );
		if ( ! $input.length || $input.data( 'rmcReady' ) ) {
			return;
		}
		$input.data( 'rmcReady', true );

		$input.wpColorPicker( {
			palettes: palette,
			change( event, ui ) {
				update( $wrap, ui.color.toString() );
			},
			clear() {
				update( $wrap, '' );
			},
		} );

		$wrap.on( 'click', '.rmc-swatch', function () {
			$input.wpColorPicker( 'color', this.dataset.color );
			update( $wrap, this.dataset.color );
		} );

		$input.on( 'input', function () {
			update( $wrap, this.value );
		} );

		update( $wrap, $input.val() );
	}

	$( function () {
		$( '.term-rmc-color-wrap' ).each( function () {
			setup( $( this ) );
		} );

		// The "Add New Category" form submits via AJAX and resets itself. Clear the picker too.
		$( document ).ajaxComplete( function ( event, xhr, settings ) {
			if ( settings && typeof settings.data === 'string' && settings.data.indexOf( 'action=add-tag' ) !== -1 ) {
				const $wrap = $( '#addtag .term-rmc-color-wrap' );
				$wrap.find( '.rmc-color-field' ).wpColorPicker( 'color', '' ).val( '' );
				$wrap.find( '.wp-color-result' ).css( 'background-color', '' );
				update( $wrap, '' );
			}
		} );
	} );
} )( jQuery );
