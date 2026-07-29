( function( window, document, $ ) { 'use strict';

	var editorContext = window.mdEditorContext || {},
		isBlockEditor = !! editorContext.isBlockEditor,
		contentStyles = editorContext.contentStyles || [ 'box', 'border', 'plain' ],
		contentStyleClasses = [],
		blockEditorClasses = [ 'md-builder', 'expanded', 'compact' ];

	// Build class lists for each editor

	contentStyles.forEach( function( style ) {
		var cls = 'is-' + style + '-style';
		contentStyleClasses.push( cls );
		blockEditorClasses.push( cls );
	} );

	// Sync admin body classes to editor surfaces

	function syncClasses( target, classes ) {
		if ( ! target )
			return;

		classes.forEach( function( cls ) {
			target.classList.toggle( cls, document.body.classList.contains( cls ) );
		} );
	}

	function syncBlockEditor() {
		var iframe = document.querySelector( 'iframe[name="editor-canvas"]' );

		if ( ! iframe || ! iframe.contentDocument )
			return;

		syncClasses( iframe.contentDocument.documentElement, blockEditorClasses );
	}

	function syncClassicEditor( editor ) {
		var editorBody = editor && editor.getBody ? editor.getBody() : null,
			iframe;

		if ( ! editorBody ) {
			iframe = document.getElementById( 'content_ifr' );
			editorBody = iframe && iframe.contentDocument ? iframe.contentDocument.body : null;
		}

		syncClasses( editorBody, contentStyleClasses );
	}

	function syncEditor( editor ) {
		if ( isBlockEditor )
			syncBlockEditor();
		else
			syncClassicEditor( editor );
	}

	// Mirror frontend Layout classes from MD meta boxes

	function applySidebarClass( hasSidebar ) {
		document.body.classList.toggle( 'expanded', ! hasSidebar );
		document.body.classList.toggle( 'compact', !! hasSidebar );
		syncBlockEditor();
	}

	function applyContentStyle( style ) {
		contentStyles.forEach( function( contentStyle ) {
			document.body.classList.toggle( 'is-' + contentStyle + '-style', contentStyle === style );
		} );
		syncEditor();
	}

	function getContentStyle( contentStyle, builderCheckbox ) {
		if ( builderCheckbox && builderCheckbox.checked )
			return 'plain';

		return ( contentStyle && contentStyle.value ) || editorContext.inheritedContentStyle || 'box';
	}

	// Find Layout options in MD meta boxes

	function attachListeners( doc ) {
		var builderCheckbox = doc.getElementById( 'marketers_delight_layout_content_builder' ),
			contentStyle = doc.getElementById( 'marketers_delight_layout_content_style' ),
			sidebarAdd = isBlockEditor ? doc.getElementById( 'marketers_delight_layout_sidebar_add' ) : null,
			sidebarRemove = isBlockEditor ? doc.getElementById( 'marketers_delight_layout_sidebar_remove' ) : null;

		if ( builderCheckbox )
			builderCheckbox.addEventListener( 'change', function() {
				document.body.classList.toggle( 'md-builder', !! this.checked );
				applyContentStyle( getContentStyle( contentStyle, this ) );
			} );

		if ( contentStyle )
			contentStyle.addEventListener( 'change', function() {
				applyContentStyle( getContentStyle( this, builderCheckbox ) );
			} );

		if ( sidebarAdd )
			sidebarAdd.addEventListener( 'change', function() {
				applySidebarClass( this.checked );
			} );

		if ( sidebarRemove )
			sidebarRemove.addEventListener( 'change', function() {
				applySidebarClass( ! this.checked );
			} );

		return !! ( builderCheckbox || contentStyle || sidebarAdd || sidebarRemove );
	}

	// Initialize Block Editor iframe and delayed meta boxes

	function initBlockEditor() {
		var canvasIframe = document.querySelector( 'iframe[name="editor-canvas"]' );

		function initCanvas( iframe ) {
			iframe.addEventListener( 'load', syncBlockEditor );
			syncBlockEditor();
		}

		if ( canvasIframe )
			initCanvas( canvasIframe );
		else {
			var canvasObserver = new MutationObserver( function() {
				canvasIframe = document.querySelector( 'iframe[name="editor-canvas"]' );

				if ( canvasIframe ) {
					canvasObserver.disconnect();
					initCanvas( canvasIframe );
				}
			} );

			canvasObserver.observe( document.body, { childList: true, subtree: true } );
		}

		if ( attachListeners( document ) )
			return;

		var metaObserver = new MutationObserver( function() {
			var frames = document.querySelectorAll( 'iframe' );

			for ( var i = 0; i < frames.length; i++ ) {
				try {
					if ( frames[i].contentDocument && attachListeners( frames[i].contentDocument ) ) {
						metaObserver.disconnect();
						break;
					}

					frames[i].addEventListener( 'load', function() {
						if ( attachListeners( this.contentDocument ) )
							metaObserver.disconnect();
					}, { once: true } );
				} catch ( e ) {}
			}
		} );

		metaObserver.observe( document.body, { childList: true, subtree: true } );
	}

	// Initialize Classic Editor iframe

	function initClassicEditor() {
		attachListeners( document );
		syncClassicEditor();

		$( document ).on( 'tinymce-editor-setup.mdEditorClasses', function( event, editor ) {
			if ( editor.id === 'content' )
				syncClassicEditor( editor );
		} );
	}

	$( function() {
		if ( isBlockEditor )
			initBlockEditor();
		else
			initClassicEditor();
	} );

} )( window, document, jQuery );
