<script>

foreach: function( items, fn ) {
	if ( Object.prototype.toString.call( items ) !== '[object Array]' )
		items = items.split( ' ' );
	for ( var i = 0; i < items.length; i++ )
		fn( items[i], i );
},
hasClass: function( el, className ) {
	return new RegExp( '(^|\\s)' + className + '(\\s|$)').test( el.className );
},
addClass: function( el, classes ) {
	MD.foreach( classes, function( className ) {
		if ( ! MD.hasClass( el, className ) )
			el.className += ( el.className ? ' ' : '' ) + className;
	});
},
removeClass: function( el, classes ) {
	MD.foreach( classes, function( className ) {
		if ( MD.hasClass( el, className ) )
			el.className = el.className.replace( new RegExp( '(?:^|\\s)' + className + '(?!\\S)' ), '' );
	});
},
toggleClass: function( el, classes ) {
	MD.foreach( classes, function( className ) {
		( MD.hasClass( el, className ) ? MD.removeClass : MD.addClass )( el, className );
	});
},
cookie: {
	create: function( name, value, days ) {
		var expires = '';
		if ( days ) {
			var date = new Date();
			date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
			expires = '; expires=' + date.toGMTString();
		}
		document.cookie = name + '=' + value + expires + '; SameSite=None; Secure; path=/';
	},
	get: function( name ) {
		var nameEQ = name + '=';
		var ca = document.cookie.split( ';' );
		for ( var i = 0; i < ca.length; i++ ) {
			var c = ca[i];
			while ( c.charAt(0) == ' ' ) c = c.substring( 1, c.length );
			if ( c.indexOf( nameEQ ) === 0 ) return c.substring( nameEQ.length, c.length );
		}
		return null;
	},
	erase: function( name ) {
		this.create( name, '', -1 );
	}
},
tabs: function( parent ) {
	var tabs = document.getElementsByClassName( 'md-tab' );
	for ( var i = 0; i < tabs.length; i++ ) {
		tabs[i].onclick = function( e ) {
			var tabID = this.getAttribute( 'data-tab' ),
				parentTabs = document.querySelectorAll( '#' + parent + ' .md-tab' ),
				parentContent = document.querySelectorAll( '#' + parent + ' .md-tab-content' );
			for ( var i = 0; i < parentTabs.length; i++ )
				MD.removeClass( parentTabs[i], 'active' );
			for ( var i = 0; i < parentContent.length; i++ )
				MD.removeClass( parentContent[i], 'active' );
			document.getElementById( parent ).className = 'has-' + tabID;
			MD.addClass( document.getElementById( tabID ), 'active' );
			MD.addClass( document.getElementById( tabID + '_tab' ), 'active' );
		}
	}
},
accordion: function( parent ) {
	var titles = document.getElementsByClassName( 'accordion-title' );
	for ( var i = 0; i < titles.length; i++ ) {
		titles[i].onclick = function( e ) {
			var groups = document.querySelectorAll( '#' + parent + ' .accordion-group' ),
				groupID = this.getAttribute( 'data-accordion' ),
				group = document.getElementById( parent + '_' + groupID );
			for ( var i = 0; i < groups.length; i++ )
				MD.removeClass( groups[i], 'active' );
			MD.addClass( group, 'active' );
		}
	}
},
clipboard: function() {
	var copy = document.getElementsByClassName( 'copy' );
	for ( var i = 0; i < copy.length; i++ ) {
		copy[i].onclick = function( e ) {
			var val = this.getAttribute( 'data-md-copy' );
			navigator.clipboard.writeText( val );
		}
	}
},
toggle: function( item, closeOut = false ) {
	var toggles = document.getElementsByClassName( item + '-toggle' );
	for ( var i = 0; i < toggles.length; i++ ) {
		toggles[i].onclick = function( e ) {
			var toggleID = this.getAttribute( 'data-' + item + '-toggle' ),
				parent = this.closest( '.' + toggleID );
			MD.toggleClass( parent, 'toggle-' + item );
			if ( closeOut == true ) {
				document.onclick = function( e ) {
					if ( ! parent.contains( e.target ) )
						MD.removeClass( parent, 'toggle-' + item );
				}
			}
		}
	}
},

<?php if ( md_has_menu() ) : ?>

headerMenu: function() {
	var header = document.getElementById( 'header' ),
		headerTrigger = document.getElementById( 'header_menu_trigger' );

	if ( headerTrigger ) {
		this.toggle( 'menu' );

		headerTrigger.onclick = function( e ) {
			MD.toggleClass( header, 'has-mobile-menu' );
			<?php if ( md_has_header_search() ) : ?>
			MD.removeClass( header, 'has-search' );
			<?php endif; ?>
		}
	}
},

<?php endif; ?>

mainMenu: function() {
	var menuTrigger = document.getElementById( 'main_menu_trigger' );

	if ( menuTrigger ) {
		var html = document.getElementsByTagName( 'html' )[0];
		this.toggle( 'menu' );

		menuTrigger.onclick = function( e ) {
			MD.addClass( html, 'has-main-menu' );
		}
		document.getElementById( 'main_menu_close' ).onclick = document.getElementById( 'main_menu_overlay' ).onclick = function( e ) {
			MD.removeClass( html, 'has-main-menu' );
		}
	}
},

searchToggle: function() {
	var searchTriggers = document.getElementsByClassName( 'trigger-search' );
	for ( var i = 0; i < searchTriggers.length; i++ ) {
		searchTriggers[i].onclick = function( e ) {
			var parent = this.getAttribute( 'data-md-parent' );
			MD.toggleClass( document.getElementById( parent ), 'has-search' );
			this.closest( '#' + parent ).querySelector( '.input' ).focus();
			MD.removeClass( document.getElementById( 'header' ), 'has-mobile-menu' );
		}
	}
},

<?php if ( has_action( 'md_js_onscroll' ) ) : ?>

onScroll: function() {
	var pos = 0, ticking = false;
	window.onscroll = function( e ) {
		pos = window.scrollY;
		if ( ! ticking ) {
			window.requestAnimationFrame( function() {
				var contentBox = document.getElementById( 'content_box' );
				if ( contentBox == null ) return;
				var contentBoxOffsetTop = contentBox.offsetTop,
					content = document.getElementById( 'the_content' );
				if ( content == null ) return;
				<?php do_action( 'md_js_onscroll' ); ?>
				ticking = false;
			});
		}
		ticking = true;
	}
},

<?php endif; ?>
