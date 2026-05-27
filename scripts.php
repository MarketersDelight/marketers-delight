<script>
<?php
/**
 * This file compiles to scripts.js to serve to the frontend.
 *
 * Use Conditional Logic to write dynamic JS and get values from
 * the database and user settings. Not conditional on a per-page basis.
 *
 * To re-build the JS file, press the Save button on any MD options screen.
 *
 * Or, use the md_compile_js(); function into your child theme/dropin/plugin
 * to rebuild scripts.js with new code.
 *
 * Tip: Use md_compile(); to rebuild both CSS and JS.
 * Remove: Remove compile functions when done adding custom code.
 *
 * @since 5.5
 */
?>

foreach: function( items, fn ) {
	if ( Object.prototype.toString.call( items ) !== '[object Array]' )
		items = items.split( ' ' );
	for ( var i = 0; i < items.length; i++ )
		fn( items[i], i );
},
number: function( value ) {
	var parsed = parseInt( value, 10 );
	return isNaN( parsed ) ? 0 : parsed;
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
removeClassByPrefix: function( el, prefix ) {
	var regex = new RegExp( '(' + prefix + '(\\s|(-)?(\\w*)(\\s)?)).*?', 'g' );
	el.className = el.className.replace( regex, '' );
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
			MD.toggleClass( group, 'active' );
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
closeOverlay: function( name, parent ) {
	document.querySelector( '.' + name + '-overlay' ).onclick = function() {
		MD.removeClass( document.querySelector( parent ), 'toggle-' + name );
		var triggers = document.getElementsByClassName( 'trigger-' + name );
		for ( var i = 0; i < triggers.length; i++ )
			MD.removeClass( triggers[i], 'toggled' );
	};
},
toggle: function() {
	var toggles = document.getElementsByClassName( 'toggle' );
	for ( var i = 0; i < toggles.length; i++ ) {
		toggles[i].onclick = function( e ) {
			var toggle = this.getAttribute( 'data-toggle' ),
				parent = this.closest( '.' + toggle ),
				className = 'toggle-' + toggle;
			if ( ! parent.classList.contains( className ) ) {
				var isOpen = document.querySelectorAll( '.' + className );
				for ( var j = 0; j < isOpen.length; j++ )
					MD.removeClass( isOpen[j], className );
			}
			MD.toggleClass( parent, className );
			if ( this.getAttribute( 'data-toggle-close' ) )
				document.onclick = function( e ) {
					if ( ! parent.contains( e.target ) )
						MD.removeClass( parent, 'toggle-' + toggle );
				}
		}
	}
},
triggers: function() {
	var triggers = document.getElementsByClassName( 'trigger' );

	for ( var i = 0; i < triggers.length; i++ ) {
		triggers[i].onclick = function( e ) {
			var type = this.getAttribute( 'data-md-trigger' ),
				location = this.getAttribute( 'data-md-location' ),
				parent = this.getAttribute( 'data-md-parent' ),
				container = document.querySelector( '.' + parent ),
				toggleClass = 'toggle-' + type,
				fromClass = 'from-' + location,
				isMobile = window.matchMedia( '(max-width: 1200px)' ).matches,
				isActive = container.classList.contains( toggleClass ) && ( ! isMobile || container.classList.contains( fromClass ) ),
				classes = Array.from( container.classList );

			for ( var t = 0; t < triggers.length; t++ )
				triggers[t].classList.remove( 'toggled' );

			for ( var c = 0; c < classes.length; c++ )
				if ( classes[c].startsWith( 'toggle' ) || classes[c].startsWith( 'from-' ) )
					container.classList.remove( classes[c] );

			if ( ! isActive ) {
				this.classList.add( 'toggled' );
				container.classList.add( toggleClass );
				container.classList.add( fromClass );
			}

			if ( type === 'search' )
				container.querySelector( '.input' ).focus();

			<?php md_hook_js_custom_triggers(); ?>
		}
	}
},
sticky: function( selector ) {
	const el = document.querySelector( selector );

	const observer = new IntersectionObserver(
		( [e] ) => e.target.classList.toggle( 'stuck', e.intersectionRatio < 1 ),
		{ threshold: [1] }
	);

	observer.observe( el );
},

<?php if ( has_action( 'md_hook_js_onscroll' ) ) : ?>

onScroll: function() {
	var pos = 0, ticking = false;
	window.onscroll = function( e ) {
		pos = window.scrollY;
		if ( ! ticking ) {
			window.requestAnimationFrame( function() {
				var contentBox = document.getElementById( 'main' );
				if ( contentBox == null ) return;
				var contentBoxOffsetTop = contentBox.offsetTop,
					content = document.getElementById( 'the_content' );
				if ( content == null ) return;
				<?php md_hook_js_onscroll(); ?>
				ticking = false;
			});
		}
		ticking = true;
	}
},

<?php endif; ?>