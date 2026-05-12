( function() {
	'use strict';

	// Get MD classes to iFrame onload
	function syncToIframe() {
		var iframe = document.querySelector( 'iframe[name="editor-canvas"]' );

		if ( ! iframe || ! iframe.contentDocument || ! iframe.contentDocument.body )
			return;

		[ 'md-builder', 'expanded', 'compact' ].forEach( function( cls ) {
			iframe.contentDocument.body.classList.toggle( cls, document.body.classList.contains( cls ) );
		} );
	}

	function applySidebarClass( hasSidebar ) {
		document.body.classList.toggle( 'expanded', ! hasSidebar );
		document.body.classList.toggle( 'compact', !! hasSidebar );
		syncToIframe();
	}

	function attachListeners( doc ) {
		var builderCheckbox = doc.getElementById( 'marketers_delight_layout_content_builder' ),
			sidebarAdd = doc.getElementById( 'marketers_delight_layout_sidebar_add' ),
			sidebarRemove = doc.getElementById( 'marketers_delight_layout_sidebar_remove' );

		if ( builderCheckbox )
			builderCheckbox.addEventListener( 'change', function() {
				document.body.classList.toggle( 'md-builder', !! this.checked );
				syncToIframe();
			} );

		if ( sidebarAdd )
			sidebarAdd.addEventListener( 'change', function() {
				applySidebarClass( this.checked );
			} );

		if ( sidebarRemove )
			sidebarRemove.addEventListener( 'change', function() {
				applySidebarClass( ! this.checked );
			} );

		return !! ( builderCheckbox || sidebarAdd || sidebarRemove );
	}

	wp.domReady( function() {
		var canvasIframe = document.querySelector( 'iframe[name="editor-canvas"]' );

		function initCanvas( iframe ) {
			iframe.addEventListener( 'load', syncToIframe );
			syncToIframe();
		}

		if ( canvasIframe )
			initCanvas( canvasIframe );
		else {
			var canvasObs = new MutationObserver( function() {
				canvasIframe = document.querySelector( 'iframe[name="editor-canvas"]' );

				if ( canvasIframe ) {
					canvasObs.disconnect();
					initCanvas( canvasIframe );
				}
			} );

			canvasObs.observe( document.body, { childList: true, subtree: true } );
		}

		// Account for timing of Gutenberg moving meta box into iFrame
		if ( attachListeners( document ) )
			return;

		var meta = new MutationObserver( function() {
			var frames = document.querySelectorAll( 'iframe' );
			for ( var i = 0; i < frames.length; i++ ) {
				try {
					if ( frames[ i ].contentDocument && attachListeners( frames[ i ].contentDocument ) ) {
						meta.disconnect();
						break;
					}
					frames[ i ].addEventListener( 'load', function() {
						if ( attachListeners( this.contentDocument ) ) meta.disconnect();
					}, { once: true } );
				} catch ( e ) {}
			}
		} ).observe( document.body, { childList: true, subtree: true } );
	} );

} )();