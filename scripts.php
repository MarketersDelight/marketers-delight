<script>
<?php
/**
 * Compiles to scripts.js. Saving any MD screen or calling md_compile_js()
 * rebuilds it, so conditions here run per site, not per page.
 *
 * @since 5.5
 */
?>

bound: {},
once: function( name ) {
	if ( this.bound[name] )
		return false;

	return this.bound[name] = true;
},
cookie: {
	create: function( name, value, days ) {
		let expires = '';
		if ( days ) {
			const date = new Date();
			date.setTime( date.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
			expires = '; expires=' + date.toUTCString();
		}
		document.cookie = name + '=' + value + expires + '; SameSite=Lax' + ( location.protocol === 'https:' ? '; Secure' : '' ) + '; path=/';
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
	document.querySelectorAll( '.tabbed' ).forEach( function( group ) {
		const tabs = group.querySelectorAll( '.tab' ),
			  panels = Array.from( group.querySelectorAll( '.tab-panel' ) );
		const activate = function( index ) {
			tabs.forEach( function( tab, i ) {
				tab.classList.toggle( 'active', i === index );
				tab.setAttribute( 'aria-selected', i === index );
				tab.tabIndex = i === index ? 0 : -1;
			} );
			panels.forEach( function( panel, i ) {
				panel.classList.toggle( 'active', i === index );
			} );
			const bar = tabs[ index ] && tabs[ index ].parentElement,
				  t = bar && tabs[ index ].getBoundingClientRect(),
				  b = bar && bar.getBoundingClientRect();
			if ( bar && ( t.left < b.left || t.right > b.right ) )
				bar.scrollLeft += t.left - b.left;
		};
		const open = function( hash ) {
			const target = hash ? document.getElementById( hash.slice( 1 ) ) : null;
			const index = target ? panels.indexOf( target.closest( '.tab-panel' ) ) : -1;
			if ( index < 0 )
				return false;
			activate( index );
			( target === panels[ index ] ? group : target ).scrollIntoView();
			return true;
		};
		window.addEventListener( 'hashchange', function() {
			open( location.hash );
		} );
		tabs.forEach( function( tab, i ) {
			tab.setAttribute( 'role', 'tab' );
			tab.addEventListener( 'click', function( e ) {
				e.preventDefault();
				activate( i );
				const href = tab.getAttribute( 'href' );
				if ( href && href.startsWith( '#' ) )
					history.replaceState( null, '', location.pathname + location.search + href );
			} );
			tab.addEventListener( 'keydown', function( e ) {
				const step = { ArrowLeft: -1, ArrowUp: -1, ArrowRight: 1, ArrowDown: 1 }[ e.key ];
				if ( ! step )
					return;
				e.preventDefault();
				const next = ( i + step + tabs.length ) % tabs.length;
				activate( next );
				tabs[ next ].focus();
			} );
		} );
		panels.forEach( function( panel ) {
			panel.setAttribute( 'role', 'tabpanel' );
		} );
		if ( ! open( location.hash ) )
			activate( Math.max( 0, Array.from( tabs ).findIndex( function( tab ) { return tab.classList.contains( 'active' ); } ) ) );
	} );
},
clipboard: function() {
	if ( ! MD.once( 'clipboard' ) )
		return;

	document.addEventListener( 'click', function( e ) {
		const copy = e.target.closest( '.copy[data-md-copy]' );

		if ( ! copy )
			return;

		e.preventDefault();
		navigator.clipboard.writeText( copy.getAttribute( 'data-md-copy' ) );
	} );
},
toggle: function() {
	if ( ! MD.once( 'toggle' ) )
		return;

	const closers = [];

	document.addEventListener( 'click', function( e ) {
		for ( let i = closers.length - 1; i >= 0; i-- ) {
			if ( closers[i].parent.contains( e.target ) )
				continue;

			closers[i].parent.classList.remove( closers[i].className );
			closers[i].trigger.setAttribute( 'aria-expanded', false );
			closers.splice( i, 1 );
		}

		const trigger = e.target.closest( '.toggle' );

		if ( ! trigger )
			return;

		const toggle = trigger.getAttribute( 'data-toggle' ),
			  target = trigger.getAttribute( 'data-toggle-target' ),
			  parent = target ? document.querySelector( target ) : trigger.closest( '.' + toggle ),
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
		trigger.setAttribute( 'aria-expanded', parent.classList.contains( className ) );

		if ( trigger.getAttribute( 'data-toggle-close' ) && parent.classList.contains( className ) )
			closers.push( { parent: parent, trigger: trigger, className: className } );
	} );

	document.querySelectorAll( '.toggle[aria-expanded]' ).forEach( function( toggle ) {
		const menuItem = toggle.closest( '.menu-item-has-children' );

		if ( ! menuItem )
			return;

		const hover = function( e ) {
			toggle.setAttribute( 'aria-expanded', e.type === 'mouseenter' || menuItem.classList.contains( 'toggle-menu-item' ) );
		};

		menuItem.addEventListener( 'mouseenter', hover );
		menuItem.addEventListener( 'mouseleave', hover );
	} );
},
triggers: function() {
	if ( ! MD.once( 'triggers' ) )
		return;

	const onTrigger = function( e ) {
		const type = this.getAttribute( 'data-md-trigger' ),
			location = this.getAttribute( 'data-md-location' ),
			parent = this.getAttribute( 'data-md-parent' ),
			container = document.querySelector( '.' + parent );

		if ( ! container )
			return;

		const triggers = document.getElementsByClassName( 'trigger' ),
			toggleClass = 'toggle-' + type,
			fromClass = 'from-' + location,
			isActive = container.classList.contains( toggleClass ) && container.classList.contains( fromClass ),
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

			const controls = this.getAttribute( 'aria-controls' ),
				  target = controls ? document.getElementById( controls ) : null;

			if ( target )
				target.focus();
		}

		this.setAttribute( 'aria-expanded', ! isActive );

		if ( type === 'search' )
			container.querySelector( '.input' ).focus();

		<?php md_hook_js_custom_triggers(); ?>
	};

	document.addEventListener( 'click', function( e ) {
		const trigger = e.target.closest( '.trigger[data-md-trigger]' );

		if ( trigger )
			onTrigger.call( trigger, e );
	} );
},
closeOverlay: function( name, parent ) {
	const overlay = document.querySelector( '.' + name + '-overlay' );

	if ( ! overlay )
		return;

	overlay.addEventListener( 'click', function() {
		const container = document.querySelector( parent ),
			  triggers = document.getElementsByClassName( 'trigger-' + name );

		if ( container )
			container.classList.remove( 'toggle-' + name );

		for ( let i = 0; i < triggers.length; i++ ) {
			triggers[i].classList.remove( 'toggled' );
			triggers[i].setAttribute( 'aria-expanded', false );
		}
	} );
},
scrollerNav: function() {
	const wraps = document.getElementsByClassName( 'scroller-nav' );
	for ( let i = 0; i < wraps.length; i++ ) {
		let wrap = wraps[i],
			list = wrap.querySelector( '.scroller-list' ),
			prev = wrap.querySelector( '.scroller-arrow-prev' ),
			next = wrap.querySelector( '.scroller-arrow-next' );

		if ( ! list || wrap.mdScroller )
			continue;

		wrap.mdScroller = true;

		function updateArrows() {
			const overflow = list.scrollWidth > wrap.clientWidth,
				start = list.scrollLeft <= 0,
				end = list.scrollLeft >= list.scrollWidth - list.clientWidth - 1;

			list.classList.toggle( 'scroller-overflow', overflow );

			if ( prev )
				prev.classList.toggle( 'arrow-hidden', ! overflow || start );

			if ( next )
				next.classList.toggle( 'arrow-hidden', ! overflow || end );
		}

		if ( prev )
			prev.addEventListener( 'click', function() {
				list.scrollBy( { left: -160, behavior: 'smooth' } );
			} );

		if ( next )
			next.addEventListener( 'click', function() {
				list.scrollBy( { left: 160, behavior: 'smooth' } );
			} );

		list.addEventListener( 'scroll', updateArrows, { passive: true } );
		window.addEventListener( 'resize', updateArrows );
		updateArrows();

		const current = list.querySelector( '.current-menu-item, .current-menu-ancestor' );

		if ( current )
			list.scrollTo( { left: current.getBoundingClientRect().left - list.getBoundingClientRect().left + list.scrollLeft, behavior: 'instant' } );
	}
},
<?php if ( has_action( 'md_hook_js_onscroll' ) ) : ?>
onScroll: function() {
	let pos = 0, ticking = false;
	window.addEventListener( 'scroll', function( e ) {
		pos = window.scrollY;
		if ( ! ticking ) {
			window.requestAnimationFrame( function() {
				ticking = false;
				const contentBox = document.getElementById( 'main' );
				if ( contentBox == null ) return;
				const contentBoxOffsetTop = contentBox.offsetTop,
					content = document.getElementById( 'the_content' );
				if ( content == null ) return;
				<?php md_hook_js_onscroll(); ?>
			});
		}
		ticking = true;
	}, { passive: true } );
},
<?php endif; ?>
