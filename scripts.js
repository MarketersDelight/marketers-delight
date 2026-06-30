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
tabs: function() {
	var tabs = document.querySelectorAll( '.md-tabs' );
	for ( var t = 0; t < tabs.length; t++ ) {
		( function( tab ) {
			var tabs = tab.querySelectorAll( '.md-tab' );
			for ( var i = 0; i < tabs.length; i++ ) {
				tabs[i].onclick = function( e ) {
					e.preventDefault();
					var allTabs = tab.querySelectorAll( '.md-tab' ),
						allContent = tab.querySelectorAll( '.md-tab-content' );
					for ( var j = 0; j < allTabs.length; j++ )
						MD.removeClass( allTabs[j], 'active' );
					for ( var k = 0; k < allContent.length; k++ )
						MD.removeClass( allContent[k], 'active' );
					MD.removeClassByPrefix( tab, 'has-' );
					MD.addClass( this, 'active' );
					MD.addClass( tab, 'has-' + this.getAttribute( 'data-md-tab' ) );
					var content = tab.querySelector( '[data-md-tab-content="' + this.getAttribute( 'data-md-tab' ) + '"]' );
					if ( content )
						MD.addClass( content, 'active' );
				};
			}
		} )( tabs[t] );
	}
},
clipboard: function() {
	var copy = document.getElementsByClassName( 'copy' );
	for ( var i = 0; i < copy.length; i++ ) {
		copy[i].onclick = function( e ) {
			e.preventDefault();
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
				target = this.getAttribute( 'data-toggle-target' ),
				parent = target ? document.querySelector( target ) : this.closest( '.' + toggle ),
				className = 'toggle-' + toggle;
			if ( ! parent )
				return;
			if ( ! parent.classList.contains( className ) ) {
				var isOpen = document.querySelectorAll( '.' + className );
				for ( var j = 0; j < isOpen.length; j++ )
					if ( ! isOpen[j].contains( parent ) )
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
		MD.removeClass( document.querySelector( parent ), 'toggle-' + name );
		var triggers = document.getElementsByClassName( 'trigger-' + name );
		for ( var i = 0; i < triggers.length; i++ )
			MD.removeClass( triggers[i], 'toggled' );
	};
},
scrollerNav: function() {
	var wraps = document.getElementsByClassName( 'scroller-nav' );
	for ( var i = 0; i < wraps.length; i++ ) {
		( function( wrap ) {
			var list = wrap.querySelector( '.scroller-list' ),
				prev = wrap.querySelector( '.scroller-arrow-prev' ),
				next = wrap.querySelector( '.scroller-arrow-next' );
			function updateArrows() {
				var overflow = list.scrollWidth > wrap.clientWidth,
					start = list.scrollLeft <= 0,
					end = list.scrollLeft >= list.scrollWidth - list.clientWidth - 1;
				if ( prev )
					if ( ! overflow || start )
						MD.addClass( prev, 'arrow-hidden' );
					else
						MD.removeClass( prev, 'arrow-hidden' );
				if ( next )
					if ( ! overflow || end )
						MD.addClass( next, 'arrow-hidden' );
					else
						MD.removeClass( next, 'arrow-hidden' );
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
		})( wraps[i] );
	}
},
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
var toc = document.getElementById( 'table_of_contents' );
if ( toc === null ) return;
var active = -1,
	tocItems = toc.querySelectorAll( '.toc-item' ),
	headings = content.querySelectorAll( 'h2, h3, h4, h5, h6' );
for ( var i = 0; i < headings.length; i++ )
	if ( headings[i].offsetTop + contentBoxOffsetTop <= pos + 20 )
		active = i;
for ( var c = 0; c < tocItems.length; c++ )
	MD.removeClass( tocItems[c], 'active child-active' );
if ( active >= 0 && tocItems[active] ) {
	MD.addClass( tocItems[active], 'active' );
	if ( MD.hasClass( tocItems[active].parentNode, 'toc-sublist' ) )
		MD.addClass( tocItems[active].parentNode.parentNode, 'child-active' );
}				ticking = false;
			});
		}
		ticking = true;
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
beacon_menu: function() {
	document.querySelectorAll( '.beacon-open' ).forEach( function( trigger ) {
		trigger.addEventListener( 'click', function() {
			var wrap = this.closest( '[data-md-beacon], [data-md-beacon-target]' );
			var id = wrap && wrap.dataset.mdBeaconTarget;
			var menu = id
				? document.querySelector( '[data-md-beacon="' + id + '"]' )
				: this.closest( '.beacon-menu' );
			if ( menu ) MD.toggleClass( menu, 'beacon-toggle' );
			MD.removeClass( document.getElementById( 'header' ), 'has-mobile-menu' );
		} );
	} );
},
download: function( config ) {
	MD.tabs();
	MD.clipboard();
	document.addEventListener( 'click', function( e ) {
		var btn = e.target.closest( '[data-download-id]' );
		if ( ! btn )
			return;
		var card = btn.closest( '.edd-download, li' );
		if ( ! card )
			return;
		var counter = card.querySelector( '.byline-download' );
		if ( ! counter )
			return;
		var count = MD.number( counter.textContent ) + 1,
			id = btn.getAttribute( 'data-download-id' );
		document.querySelectorAll( '[data-download-id="' + id + '"]' ).forEach( function( el ) {
			var c = el.closest( '.edd-download, li' );
			if ( c ) {
				var n = c.querySelector( '.byline-download' );
				if ( n )
					n.textContent = count;
			}
		} );
	} );
	if ( config.cart !== undefined ) {
		document.querySelectorAll( '.beacon-avatar' ).forEach( function( avatar ) {
			var badge = document.createElement( 'a' );
			badge.href = config.checkout;
			badge.className = 'beacon-cart-count';
			badge.textContent = config.cart;
			badge.hidden = config.cart < 1;
			avatar.appendChild( badge );
		} );
		var tracker = document.createElement( 'span' );
		tracker.className = 'edd-cart-quantity';
		tracker.hidden = true;
		document.body.appendChild( tracker );
		new MutationObserver( function() {
			var qty = parseInt( tracker.textContent, 10 ) || 0;
			document.querySelectorAll( '.beacon-cart-count' ).forEach( function( badge ) {
				badge.textContent = qty;
				badge.hidden = qty < 1;
			} );
		} ).observe( tracker, { childList: true, characterData: true, subtree: true } );
	}
	if ( ! config || ! config.nonce )
		return;
	var licenseSwitch = document.getElementById( 'license_switch' );
	if ( ! licenseSwitch )
		return;
	licenseSwitch.onclick = function( e ) {
		e.preventDefault();
		this.classList.add( 'md-loading' );
		var data = new FormData();
		data.append( 'action', 'md_license_switch' );
		data.append( 'nonce', config.nonce );
		fetch( MDJS.ajaxurl, {
			method: 'POST',
			body: data
		} ).then( function() { window.location.reload(); } );
	};
},
footnotes: function() {
	var footnotes = document.getElementsByClassName( 'footnote' );
	for ( var i = 0; i < footnotes.length; i++ ) {
		footnotes[i].onclick = function( e ) {
			MD.toggleClass( document.getElementById( this.id ), 'footnote-show' );
		}
	}
},
tableOfContents: function() {
	var toc = document.getElementById( 'table_of_contents' );
	if ( ! toc )
		return;
	var	content = document.getElementById( 'the_content' ),
		labels = toc.querySelectorAll( '.toc-item-label' ),
		headings = content.querySelectorAll( 'h2, h3, h4, h5, h6' );
	function scrollTo( target ) {
		var inEntry = !! toc.closest( '.entry' ),
			preStuck = inEntry && ! MD.hasClass( toc, 'stuck' );
		if ( preStuck ) MD.addClass( toc, 'stuck' );
		var top = 0, el = target;
		while ( el ) { top += el.offsetTop; el = el.offsetParent; }
		var offset = inEntry ? toc.clientHeight : 0;
		if ( preStuck ) MD.removeClass( toc, 'stuck' );
		window.scrollTo({ top: top - offset, behavior: 'smooth' });
	}
	for ( var i = 0; i < labels.length; i++ )
		headings[i].setAttribute( 'id', labels[i].getAttribute( 'href' ).slice( 1 ) );
	toc.addEventListener( 'click', function( e ) {
		var label = e.target.closest( '.toc-item-label' );
		if ( ! label ) return;
		e.preventDefault();
		var id = label.getAttribute( 'href' ).slice( 1 ),
			target = document.getElementById( id );
		if ( ! target ) return;
		MD.removeClass( toc, 'open' );
		scrollTo( target );
		window.history.pushState( {}, '', '#' + id );
	} );
	toc.querySelector( '.widget-title' ).onclick = function() { MD.toggleClass( toc, 'open' ); };
	if ( 'scrollRestoration' in history )
		history.scrollRestoration = 'manual';
	window.addEventListener( 'popstate', function() {
		var hash = window.location.hash;
		if ( hash ) {
			var target = document.getElementById( hash.slice( 1 ) );
			if ( target ) scrollTo( target );
		}
		else window.scrollTo({ top: 0, behavior: 'smooth' });
	} );
},
}