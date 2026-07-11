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

number: function( value ) {
	const parsed = parseInt( value, 10 );
	return isNaN( parsed ) ? 0 : parsed;
},
hasClass: function( el, className ) {
	return el.classList.contains( className );
},
addClass: function( el, className ) {
	el.classList.add( className );
},
removeClass: function( el, className ) {
	el.classList.remove( className );
},
removeClassByPrefix: function( el, prefix ) {
	Array.from( el.classList ).forEach( function( className ) {
		if ( className.indexOf( prefix ) === 0 )
			el.classList.remove( className );
	});
},
toggleClass: function( el, className ) {
	el.classList.toggle( className );
},
cookie: {
	create: function( name, value, days ) {
		let expires = '';
		if ( days ) {
			const date = new Date();
			date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
			expires = '; expires=' + date.toUTCString();
		}
		document.cookie = name + '=' + value + expires + '; SameSite=None; Secure; path=/';
	},
	get: function( name ) {
		const match = document.cookie.split( '; ' ).find( function( row ) {
			return row.indexOf( name + '=' ) === 0;
		});
		return match ? match.substring( name.length + 1 ) : null;
	},
	erase: function( name ) {
		this.create( name, '', -1 );
	}
},
tabs: function() {
	const tabs = document.querySelectorAll( '.md-tabs' );
	for ( let t = 0; t < tabs.length; t++ ) {
		let tab = tabs[t],
			items = tab.querySelectorAll( '.md-tab' );
		for ( let i = 0; i < items.length; i++ ) {
			items[i].onclick = function( e ) {
				e.preventDefault();
				const allTabs = tab.querySelectorAll( '.md-tab' ),
					  allContent = tab.querySelectorAll( '.md-tab-content' );
				for ( let j = 0; j < allTabs.length; j++ )
					allTabs[j].classList.remove( 'active' );
				for ( let k = 0; k < allContent.length; k++ )
					allContent[k].classList.remove( 'active' );
				MD.removeClassByPrefix( tab, 'has-' );
				this.classList.add( 'active' );
				tab.classList.add( 'has-' + this.getAttribute( 'data-md-tab' ) );
				const content = tab.querySelector( '[data-md-tab-content="' + this.getAttribute( 'data-md-tab' ) + '"]' );
				if ( content )
					content.classList.add( 'active' );
			};
		}
	}
},
clipboard: function() {
	const copy = document.getElementsByClassName( 'copy' );
	for ( let i = 0; i < copy.length; i++ ) {
		copy[i].onclick = function( e ) {
			e.preventDefault();
			const val = this.getAttribute( 'data-md-copy' );
			navigator.clipboard.writeText( val );
		}
	}
},
toggle: function() {
	const toggles = document.getElementsByClassName( 'toggle' );
	for ( let i = 0; i < toggles.length; i++ ) {
		toggles[i].onclick = function( e ) {
			const toggle = this.getAttribute( 'data-toggle' ),
				target = this.getAttribute( 'data-toggle-target' ),
				parent = target ? document.querySelector( target ) : this.closest( '.' + toggle ),
				className = 'toggle-' + toggle;
			if ( ! parent )
				return;
			if ( ! parent.classList.contains( className ) ) {
				const isOpen = document.querySelectorAll( '.' + className );
				for ( let j = 0; j < isOpen.length; j++ )
					if ( ! isOpen[j].contains( parent ) )
						isOpen[j].classList.remove( className );
			}
			parent.classList.toggle( className );
			this.setAttribute( 'aria-expanded', parent.classList.contains( className ) );
			if ( this.getAttribute( 'data-toggle-close' ) )
				document.onclick = function( e ) {
					if ( ! parent.contains( e.target ) )
						parent.classList.remove( 'toggle-' + toggle );
				}
		}
		let menuItem = toggles[i].hasAttribute( 'aria-expanded' ) ? toggles[i].closest( '.menu-item-has-children' ) : null;
		if ( menuItem )
			menuItem.onmouseenter = menuItem.onmouseleave = function( e ) {
				menuItem.querySelector( '.toggle' ).setAttribute( 'aria-expanded', e.type === 'mouseenter' );
			}
	}
},
triggers: function() {
	const triggers = document.getElementsByClassName( 'trigger' );
	for ( let i = 0; i < triggers.length; i++ ) {
		triggers[i].onclick = function( e ) {
			const type = this.getAttribute( 'data-md-trigger' ),
				location = this.getAttribute( 'data-md-location' ),
				parent = this.getAttribute( 'data-md-parent' ),
				container = document.querySelector( '.' + parent ),
				toggleClass = 'toggle-' + type,
				fromClass = 'from-' + location,
				isMobile = window.matchMedia( '(max-width: 1200px)' ).matches,
				isActive = container.classList.contains( toggleClass ) && ( ! isMobile || container.classList.contains( fromClass ) ),
				classes = Array.from( container.classList );

			for ( let t = 0; t < triggers.length; t++ )
				triggers[t].classList.remove( 'toggled' );

			for ( let c = 0; c < classes.length; c++ )
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
sticky: function( items ) {
	if ( ! items ) return;
	if ( typeof items === 'string' )
		items = [items];
	items.forEach( function( selector ) {
		const el = document.querySelector( selector );
		if ( ! el ) return;
		const update = function() {
			el.classList.toggle( 'stuck', el.getBoundingClientRect().top <= 0 );
		};
		update();
		window.addEventListener( 'scroll', update, { passive: true } );
		window.addEventListener( 'resize', update );
	});
},
closeOverlay: function( name, parent ) {
	document.querySelector( '.' + name + '-overlay' ).onclick = function() {
		document.querySelector( parent ).classList.remove( 'toggle-' + name );
		const triggers = document.getElementsByClassName( 'trigger-' + name );
		for ( let i = 0; i < triggers.length; i++ )
			triggers[i].classList.remove( 'toggled' );
	};
},
scrollerNav: function() {
	const wraps = document.getElementsByClassName( 'scroller-nav' );
	for ( let i = 0; i < wraps.length; i++ ) {
		let wrap = wraps[i],
			list = wrap.querySelector( '.scroller-list' ),
			prev = wrap.querySelector( '.scroller-arrow-prev' ),
			next = wrap.querySelector( '.scroller-arrow-next' );

		function updateArrows() {
			const overflow = list.scrollWidth > wrap.clientWidth,
				start = list.scrollLeft <= 0,
				end = list.scrollLeft >= list.scrollWidth - list.clientWidth - 1;

			if ( prev )
				if ( ! overflow || start )
					prev.classList.add( 'arrow-hidden' );
				else
					prev.classList.remove( 'arrow-hidden' );

			if ( next )
				if ( ! overflow || end )
					next.classList.add( 'arrow-hidden' );
				else
					next.classList.remove( 'arrow-hidden' );
		}

		if ( prev )
			prev.onclick = function() {
				list.scrollBy( { left: -160, behavior: 'smooth' } );
			};

		if ( next )
			next.onclick = function() {
				list.scrollBy( { left: 160, behavior: 'smooth' } );
			};

		list.addEventListener( 'scroll', updateArrows, { passive: true } );
		window.addEventListener( 'resize', updateArrows );
		updateArrows();
	}
},
<?php if ( has_action( 'md_hook_js_onscroll' ) ) : ?>
onScroll: function() {
	let pos = 0, ticking = false;
	window.onscroll = function( e ) {
		pos = window.scrollY;
		if ( ! ticking ) {
			window.requestAnimationFrame( function() {
				const contentBox = document.getElementById( 'main' );
				if ( contentBox == null ) return;
				const contentBoxOffsetTop = contentBox.offsetTop,
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