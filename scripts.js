window.MD = {
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
				container = this.closest( '.' + parent ),
				showClass = 'show-' + type,
				fromClass = 'from-' + location,
				isActive = container.classList.contains( showClass ) && container.classList.contains( fromClass ),
				classes = Array.from( container.classList );
			for ( var c = 0; c < classes.length; c++ )
				if ( classes[c].startsWith( 'show-' ) || classes[c].startsWith( 'from-' ) )
					container.classList.remove( classes[c] );
			if ( ! isActive ) {
				MD.toggleClass( container, showClass );
				MD.toggleClass( container, fromClass );
			}
			if ( type === 'search' )
				container.querySelector( '.input' ).focus();
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
like: function() {
	var name = 'md_likes',
		likes = document.getElementsByClassName( 'share-like' ),
		updateCounts = function( post_id, count, increment ) {
			var counts = document.getElementsByClassName( 'share-count' );
			for ( var i = 0; i < counts.length; i++ ) {
				var el = counts[i].parentElement;
				if ( el.getAttribute( 'data-share-id' ) === post_id ) {
					MD.addClass( el, 'liked' );
					if ( ! MD.hasClass( el, 'share-like-total' ) )
						counts[i].innerHTML = increment ? MD.number( counts[i].innerHTML ) + 1 : count;
				}
			}
		},
		updateTotals = function( post_type, total, increment ) {
			var totals = document.getElementsByClassName( 'share-total' );
			for ( var i = 0; i < totals.length; i++ )
				if ( totals[i].parentElement.getAttribute( 'data-share-type' ) === post_type )
					totals[i].innerHTML = increment ? MD.number( totals[i].innerHTML ) + 1 : total;
		};
	for ( var i = 0; i < likes.length; i++ ) {
		likes[i].onclick = function( e ) {
			e.preventDefault();
			if ( MD.hasClass( this, 'liked' ) )
				return;
			var post_id = this.getAttribute( 'data-share-id' );
			if ( post_id == null )
				return;
			var post_type = this.getAttribute( 'data-share-type' ),
				endpoint = MDJS.rest.likes ? MDJS.rest.likes + post_id + '/like' : null
			if ( ! endpoint )
				return;
			updateCounts( post_id, null, true );
			updateTotals( post_type, null, true );
			MD.addClass( this, 'liked' );
			fetch( endpoint, {
				method: 'POST',
				credentials: 'include',
				headers: {
					'X-WP-Nonce': MDJS.rest.nonce
				},
				body: new URLSearchParams({
					type: post_type
				})
			} )
			.then( function( response ) {
				if ( ! response.ok )
					return null;
				return response.json().catch( function() {
					return null;
				} );
			} )
			.then( function( response ) {
				if ( ! response )
					return;
				var liked = MD.cookie.get( name ) ? JSON.parse( MD.cookie.get( name ) ) : [];
				if ( response.data ) {
					updateCounts( post_id, response.data.likes );
					updateTotals( post_type, response.data.total );
				}
				if ( liked.indexOf( post_id ) === -1 )
					liked.push( post_id );
				MD.cookie.create( name, JSON.stringify( liked ), 365 );
			} );
		}
	}
},
footnotes: function() {
	var footnotes = document.getElementsByClassName( 'footnote' );
	for ( var i = 0; i < footnotes.length; i++ ) {
		footnotes[i].onclick = function( e ) {
			MD.toggleClass( document.getElementById( this.id ), 'footnote-show' );
		}
	}
},
}