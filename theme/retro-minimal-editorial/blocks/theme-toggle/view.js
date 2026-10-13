/**
 * Front-end handler for retro-minimal/theme-toggle.
 *
 * Cycles: Auto (system) -> Light -> Dark -> Auto.
 * Persists in localStorage('rme-color-scheme').
 * Updates document.documentElement[data-theme] and meta[name="color-scheme"].
 */
( function () {
	const STORAGE_KEY = 'rme-color-scheme';
	const toggle = document.getElementById( 'rme-theme-toggle' );
	if ( ! toggle ) {
		return;
	}

	const stateEl = toggle.querySelector( '.rme-theme-toggle__state' );
	const iconEl = toggle.querySelector( '.rme-theme-toggle__icon' );
	const meta = document.querySelector( 'meta[name="color-scheme"]' );
	const mql = window.matchMedia ? window.matchMedia( '(prefers-color-scheme: dark)' ) : null;

	function getStored() {
		try {
			return localStorage.getItem( STORAGE_KEY );
		} catch ( e ) {
			return null;
		}
	}

	function setStored( val ) {
		try {
			if ( val ) {
				localStorage.setItem( STORAGE_KEY, val );
			} else {
				localStorage.removeItem( STORAGE_KEY );
			}
		} catch ( e ) {}
	}

	function updateUI( mode ) {
		let label = 'AUTO';
		let icon = '\u25D0'; // ◐

		if ( mode === 'light' ) {
			label = 'LIGHT';
			icon = '\u2600'; // ☀
		} else if ( mode === 'dark' ) {
			label = 'DARK';
			icon = '\u263E'; // ☾
		} else {
			// Auto: show what system currently resolves to
			const sysDark = mql && mql.matches;
			label = 'AUTO (' + ( sysDark ? 'DARK' : 'LIGHT' ) + ')';
		}

		if ( stateEl ) {
			stateEl.textContent = label;
		}
		if ( iconEl ) {
			iconEl.textContent = icon;
		}
		toggle.setAttribute( 'aria-label', 'Current theme: ' + label + '. Click to switch.' );
	}

	function applyTheme( mode ) {
		if ( mode === 'light' || mode === 'dark' ) {
			document.documentElement.setAttribute( 'data-theme', mode );
			if ( meta ) {
				meta.content = mode;
			}
		} else {
			document.documentElement.removeAttribute( 'data-theme' );
			if ( meta ) {
				meta.content = 'light dark';
			}
		}
		updateUI( mode );
	}

	// Initial sync
	const current = getStored();
	applyTheme( current === 'light' || current === 'dark' ? current : 'auto' );

	if ( mql ) {
		mql.addEventListener( 'change', function () {
			if ( ! getStored() ) {
				updateUI( 'auto' );
			}
		} );
	}

	toggle.addEventListener( 'click', function () {
		const stored = getStored();
		let next = 'light';

		if ( ! stored ) {
			// Currently auto: if system is dark, switch to light; if system is light, switch to dark
			next = ( mql && mql.matches ) ? 'light' : 'dark';
		} else if ( stored === 'light' ) {
			next = 'dark';
		} else if ( stored === 'dark' ) {
			next = 'auto';
		}

		setStored( next === 'auto' ? null : next );
		applyTheme( next );
	} );
} )();
