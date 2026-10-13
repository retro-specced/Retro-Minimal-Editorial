/**
 * Retro-Minimal Editorial: front-end scripts.
 */
( function () {
	'use strict';

	/**
	 * Adapt dot leaders in .is-style-link-list so links stay right-aligned
	 * and middle dots dynamically fill the remaining width without wrapping.
	 */
	function initAdaptiveDotLeaders() {
		var listItems = document.querySelectorAll( '.wp-block-list.is-style-link-list li' );
		if ( ! listItems.length ) {
			return;
		}

		listItems.forEach( function ( li ) {
			if ( li.querySelector( '.rme-link-leader' ) ) {
				return; // Already structured
			}

			var a = li.querySelector( 'a' );
			if ( ! a ) {
				// Also handle metadata rows with literal dots, e.g. "Year ............ 2026"
				var text = li.textContent || '';
				var dotMatch = text.match( /^(.*?)\s*(\.{3,})\s*(.*?)$/ );
				if ( dotMatch ) {
					var strong = li.querySelector( 'strong' );
					var labelHtml = strong ? strong.outerHTML : dotMatch[1].trim();
					var valText = dotMatch[3].trim();

					li.innerHTML = '';
					var lbl = document.createElement( 'span' );
					lbl.className = 'rme-link-label';
					lbl.innerHTML = labelHtml;

					var ldr = document.createElement( 'span' );
					ldr.className = 'rme-link-leader';
					ldr.setAttribute( 'aria-hidden', 'true' );
					ldr.textContent = '................................................................';

					var val = document.createElement( 'span' );
					val.className = 'rme-link-value';
					val.textContent = valText;

					li.appendChild( lbl );
					li.appendChild( ldr );
					li.appendChild( val );
				}
				return;
			}

			// Item contains an <a> tag
			var aClone = a.cloneNode( true );
			var childNodes = Array.prototype.slice.call( li.childNodes );
			var labelParts = [];

			for ( var i = 0; i < childNodes.length; i++ ) {
				if ( childNodes[i] === a ) {
					break;
				}
				if ( childNodes[i].nodeType === Node.ELEMENT_NODE ) {
					labelParts.push( childNodes[i].outerHTML );
				} else if ( childNodes[i].nodeType === Node.TEXT_NODE ) {
					labelParts.push( childNodes[i].textContent );
				}
			}

			var rawLabel = labelParts.join( '' ).trim();
			// Strip trailing literal dots and whitespace
			var cleanLabel = rawLabel.replace( /[\.\s]+$/, '' ).trim();

			li.innerHTML = '';

			var labelSpan = document.createElement( 'span' );
			labelSpan.className = 'rme-link-label';
			labelSpan.innerHTML = cleanLabel;

			var leaderSpan = document.createElement( 'span' );
			leaderSpan.className = 'rme-link-leader';
			leaderSpan.setAttribute( 'aria-hidden', 'true' );
			leaderSpan.textContent = '................................................................';

			var valueWrap = document.createElement( 'span' );
			valueWrap.className = 'rme-link-value';
			valueWrap.appendChild( aClone );

			li.appendChild( labelSpan );
			li.appendChild( leaderSpan );
			li.appendChild( valueWrap );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAdaptiveDotLeaders );
	} else {
		initAdaptiveDotLeaders();
	}
} )();
