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
				parent = this.closest( '.' + toggle );
			MD.toggleClass( parent, 'toggle-' + toggle );
			if ( this.getAttribute( 'data-toggle-close' ) ) {
				document.onclick = function( e ) {
					if ( ! parent.contains( e.target ) )
						MD.removeClass( parent, 'toggle-' + toggle );
				}
			}
		}
	}
},
headerMenu: function() {
	var header = document.getElementById( 'header' ),
		headerTrigger = document.getElementById( 'header_menu_trigger' );
	if ( headerTrigger ) {
		this.toggle( 'menu' );
		headerTrigger.onclick = function( e ) {
			MD.toggleClass( header, 'has-mobile-menu' );
						MD.removeClass( header, 'has-search' );
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
floatingBars: {
	init: function( floatingBars ) {
		this.opened = this.showing = false;
		this.data = floatingBars;
		MD.floatingBars.open.events();
		MD.floatingBars.close.events();
	},
	open: {
		events: function() {
			for ( var id in MD.floatingBars.data ) {
				if ( MD.floatingBars.opened )
					break;
				MD.floatingBar = MD.floatingBars.data[id];
				if ( MD.floatingBar.show === 'seconds' )
					this.timer();
				if ( MD.floatingBar.show === 'percent' )
					this.percent();
				MD.floatingBars.opened = MD.floatingBar.id;
			}
		},
		show: function() {
			var id = MD.floatingBar.id,
				el = document.getElementById( id );
			MD.removeClass( el, 'hide' );
			MD.addClass( el, 'active' );
			MD.floatingBars.showing = id;
			if ( MD.hasClass( el, 'sticky' ) )
				MD.sticky( '#' + id );
			delete MD.floatingBars.data[id];
			MD.floatingBars.close.events();
		},
		percent: function() {
			window.onscroll = function() {
				var pos = window.scrollY,
					el = document.getElementById( MD.floatingBar.id );
				if ( ! MD.hasClass( el, 'closed' ) ) {
					window.requestAnimationFrame( function() {
						var percent = Math.round( ( pos / document.body.scrollHeight ) * 100 );
						if ( MD.floatingBar.delay <= percent )
							MD.floatingBars.open.show();
						else if ( MD.hasClass( el, 'active' ) )
							MD.removeClass( el, 'active' );
					});
				}
			}
		},
		timer: function( ) {
			setTimeout( function() {
				MD.floatingBars.open.show();
			}, MD.floatingBar.delay * 1000 );
		}
	},
	close: {
		events: function() {
			this.trigger();
		},
		trigger: function() {
			var triggers = document.getElementsByClassName( 'bar-close' );
			for ( var i = 0; i < triggers.length; i++ ) {
				triggers[i].onclick = function() {
					var bar_id = this.getAttribute( 'data-bar' ),
						expires = this.getAttribute( 'data-bar-expires' ),
						el = document.getElementById( bar_id );
					MD.removeClass( el, 'active' );
					MD.addClass( el, 'hide' );
					MD.floatingBars.close.close( bar_id, expires );
				}
			}
		},
		close: function( bar_id, expires ) {
			MD.addClass( document.getElementById( bar_id ), 'closed' );
			if ( ! MD.cookie.get( bar_id ) && expires !== '0' )
				MD.cookie.create( bar_id, true, expires );
//			delete MD.floatingBars.data[bar_id];
			delete MD.floatingBars.opened;
			delete MD.floatingBars.showing;
			MD.removeClass( document.getElementById( bar_id ), 'active' );
			MD.floatingBars.open.events();
		}
	}
},
focusInputs: function( id ) {
	var search = document.querySelector( '#' + id + ' .search-input' ),
		name = document.querySelector( '#' + id + ' .form-input-name' ),
		email = document.querySelector( '#' + id + ' .form-input-email' );
	if ( search )
		search.focus();
	else if ( name )
		name.focus();
	else if ( email )
		email.focus();
},
popups: {
	init: function( popups ) {
		this.opened = this.showing = false;
		this.data = popups;
		MD.popups.open.events();
	},
	open: {
		events: function() {
			this.triggers();
			for ( var id in MD.popups.data ) {
				if ( MD.popups.opened )
					break;
				MD.popup = MD.popups.data[id];
				if ( MD.popup.show === 'seconds' )
					this.timer();
				else if ( MD.popup.show === 'percent' )
					this.percent();
				else if ( MD.popup.show === 'exit' )
					this.exit();
				MD.popups.opened = MD.popup.id;
			}
		},
		triggers: function() {
			var triggers = document.getElementsByClassName( 'popup-trigger' );
			for ( var i = 0; i < triggers.length; i++ ) {
				triggers[i].onclick = function() {
					MD.popups.trigger = this.getAttribute( 'data-popup' );
					MD.popups.open.show();
					return false;
				}
			}
		},
		percent: function() {
			var shown = false;
			window.onscroll = function() {
				if ( shown || MD.popups.trigger )
					return;
				var pos = window.scrollY,
					el = document.getElementById( MD.popup.id );
				if ( el !== null )
					window.requestAnimationFrame( function() {
						var percent = Math.round( ( pos / document.body.scrollHeight ) * 100 );
						if ( MD.popup.delay <= percent ) {
							shown = true;
							MD.popups.open.show();
						}
					});
			}
		},
		timer: function() {
			setTimeout( function() {
				if ( ! MD.popups.trigger )
					MD.popups.open.show();
			}, MD.popup.delay * 1000 );
		},
		exit: function() {
			var shown = false;
			window.document.onmousemove = function( e ) {
				if ( shown || MD.popups.trigger )
					return;
				var scroll = window.pageYOffset || document.documentElement.scrollTop;
				if ( ( e.pageY - scroll ) < 7 ) {
					shown = true;
					MD.popups.open.show();
				}
			}
		},
		show: function() {
			var id = MD.popups.trigger ? MD.popups.trigger : MD.popup.id;
			MD.addClass( document.getElementsByTagName( 'html' )[0], 'has-popup' );
			if ( MD.popups.showing && MD.popups.trigger )
				MD.removeClass( document.getElementById( MD.popups.showing ), 'active' );
			MD.addClass( document.getElementById( id ), 'active' );
			MD.focusInputs( id );
			MD.popups.showing = id;
			if ( ! MD.popups.trigger )
				delete MD.popups.data[MD.popup.id];
			MD.popups.close.events();
		}
	},
	close: {
		events: function() {
			this.trigger();
			this.bg();
			this.esc();
		},
		trigger: function() {
			var triggers = document.getElementsByClassName( 'close' );
			for ( var i = 0; i < triggers.length; i++ ) {
				triggers[i].onclick = function() {
					MD.popups.close.close();
				}
			}
		},
		bg: function() {
			document.getElementById( 'popup_bg' ).onclick = function() {
				MD.popups.close.close();
			}
		},
		esc: function() {
			window.document.onkeydown = function( e ) {
				e = e || window.event;
				if ( e.keyCode == 27 )
					MD.popups.close.close();
			};
		},
		close: function() {
			MD.removeClass( document.getElementsByTagName( 'html' )[0], 'has-popup' );
			if ( MD.popups.trigger ) {
				var id = MD.popups.trigger;
				delete MD.popups.trigger;
			}
			else {
				if ( ! MD.popup )
					return;
				var id = MD.popup.id;
				if ( MD.popup.cookieExp && ! MD.cookie.get( id ) )
					MD.cookie.create( id, true, MD.popup.cookieExp );
			}
			delete MD.popups.opened;
			delete MD.popups.showing;
			MD.removeClass( document.getElementById( id ), 'active' );
			MD.popups.toggleVideo( id );
			MD.popups.open.events();
		}
	},
	toggleVideo: function( id ) {
		var iframe = document.querySelector( '#' + id + ' iframe' ),
			video = document.querySelector( '#' + id + ' video' );
	    if ( iframe !== null ) {
	        var iframeSrc = iframe.src;
	        iframe.src = iframeSrc;
	    }
	    if ( video !== null )
	        video.pause();
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
				request.send( 'action=md_like&post_id=' + post_id + '&type=' + post_type + '&nonce=' + MDJS.nonce );
			}
		}
	}
},
}