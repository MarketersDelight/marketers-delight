window.MD = {
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
toggle: function( item ) {
	var toggles = document.getElementsByClassName( item + '-toggle' );
	for ( var i = 0; i < toggles.length; i++ ) {
		toggles[i].onclick = function( e ) {
			var toggleID = this.getAttribute( 'data-' + item + '-toggle' );
			MD.toggleClass( document.getElementById( toggleID ), 'toggle-' + item );
		}
	}
},
headerMenu: function() {
	this.toggle( 'menu' );
	var header = document.getElementById( 'header' ),
		headerTrigger = document.getElementById( 'header_menu_trigger' );
	if ( headerTrigger )
		headerTrigger.onclick = function( e ) {
			MD.toggleClass( header, 'has-mobile-menu' );
						MD.removeClass( header, 'has-search' );
					}
},
searchToggle: function() {
	var searchTriggers = document.getElementsByClassName( 'trigger-search' );
	for ( var i = 0; i < searchTriggers.length; i++ ) {
		searchTriggers[i].onclick = function( e ) {
			var parent = this.getAttribute( 'data-md-parent' );
			MD.toggleClass( document.getElementById( parent ), 'has-search' );
			this.closest( '#' + parent ).querySelector( '.search-input' ).focus();
			MD.removeClass( document.getElementById( 'header' ), 'has-mobile-menu' );
		}
	}
/*
	window.document.onkeydown = function( e ) {
		e = e || window.event;
		if ( e.keyCode == 27 )
			MD.removeClass( wrapper, 'has-search' );
	};
*/
},
share: {
	init: function() {
		MD.share.window();
		MD.share.like();
	},
	window: function() {
		shares = document.querySelectorAll( '[data-share]' );
		for ( var i = 0; i < shares.length; i++ ) {
			shares[i].onclick = function() {
				window.open( this.getAttribute( 'href' ), 'newWindow','left=100, top=150, width=600, height=300, toolbar=0, resizable=1' );
				return false;
			}
		}
	},
	like: function() {
		var name = 'md_likes',
			likes = document.getElementsByClassName( 'share-like' );
		for ( var i = 0; i < likes.length; i++ ) {
			likes[i].onclick = function( e ) {
				e.preventDefault();
				if ( ! MD.hasClass( this, 'liked' ) ) {
					var post_id = this.getAttribute( 'data-share-id' );
					if ( post_id == null )
						return;
					var post_type = this.getAttribute( 'data-share-type' ),
						page = this.getAttribute( 'data-share-page' ),
						counts = document.getElementsByClassName( 'share-count' ),
						request = new XMLHttpRequest();
					for ( var l = 0; l < counts.length; l++ ) {
						var countLike = counts[l].parentElement;
						if ( countLike.getAttribute( 'data-share-id' ) === post_id ) {
							MD.addClass( countLike, 'liked' );
							if ( ! MD.hasClass( countLike, 'share-like-total' ) )
								counts[l].innerHTML++;
						}
					}
					request.open( 'POST', MDJS.ajaxurl, true );
					request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
					request.onreadystatechange = function() {
						if ( request.readyState === 4 && request.status === 200 ) {
							var liked = MD.cookie.get( name ) ? JSON.parse( MD.cookie.get( name ) ) : [],
								totals = document.getElementsByClassName( 'share-total' );
							liked.push( post_id );
							if ( totals )
								for ( var t = 0; t < totals.length; t++ ) {
									var totalLikes = totals[t].parentElement;
									if ( totalLikes.getAttribute( 'data-share-type' ) === post_type )
										totals[t].innerHTML++;
								}
							MD.cookie.create( name, JSON.stringify( liked ), 365 );
						}
					};
					request.send( 'action=md_like&post_id=' + post_id + '&type=' + post_type + '&page=' + page + '&nonce=' + MDJS.nonce );
				}
			}
		}
	},
},
}