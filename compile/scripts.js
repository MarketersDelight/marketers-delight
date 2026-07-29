window.MD = {
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
			const trigger = this,
				  toggle = trigger.getAttribute( 'data-toggle' ),
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
			if ( trigger.getAttribute( 'data-toggle-close' ) )
				document.onclick = function( e ) {
					if ( ! parent.contains( e.target ) ) {
						parent.classList.remove( 'toggle-' + toggle );
						trigger.setAttribute( 'aria-expanded', false );
					}
				}
		}
		let menuItem = toggles[i].hasAttribute( 'aria-expanded' ) ? toggles[i].closest( '.menu-item-has-children' ) : null;
		if ( menuItem )
			menuItem.onmouseenter = menuItem.onmouseleave = function( e ) {
				if ( e.type === 'mouseleave' )
					menuItem.classList.remove( 'toggle-menu-item' );
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
				const controls = this.getAttribute( 'aria-controls' ),
					  target = controls ? document.getElementById( controls ) : null;
				if ( target )
					target.focus();
			}
			this.setAttribute( 'aria-expanded', ! isActive );
			if ( type === 'search' )
				container.querySelector( '.input' ).focus();
					}
	}
},
closeOverlay: function( name, parent ) {
	document.querySelector( '.' + name + '-overlay' ).onclick = function() {
		document.querySelector( parent ).classList.remove( 'toggle-' + name );
		const triggers = document.getElementsByClassName( 'trigger-' + name );
		for ( let i = 0; i < triggers.length; i++ ) {
			triggers[i].classList.remove( 'toggled' );
			triggers[i].setAttribute( 'aria-expanded', false );
		}
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
floatingBars: {
	init: function( floatingBars ) {
		this.opened = this.showing = false;
		this.data = floatingBars;
		document.addEventListener( 'click', function( e ) {
			var trigger = e.target.closest( '.bar-close' );
			if ( ! trigger ) return;
			var bar_id = trigger.getAttribute( 'data-bar' ),
				el = document.getElementById( bar_id );
			el.classList.remove( 'active' );
			el.classList.add( 'hide' );
			el.classList.add( 'closed' );
			if ( MD.floatingBar && MD.floatingBar.cookieExp && ! MD.cookie.get( bar_id ) )
				MD.cookie.create( bar_id, true, MD.floatingBar.cookieExp );
			MD.floatingBars.opened = MD.floatingBars.showing = false;
			MD.floatingBars.open.events();
		} );
		MD.floatingBars.open.events();
	},
	open: {
		events: function() {
			for ( var id in MD.floatingBars.data ) {
				if ( MD.floatingBars.opened )
					break;
				MD.floatingBar = MD.floatingBars.data[id];
				if ( MD.floatingBar.show === 'seconds' )
					this.timer();
				else if ( MD.floatingBar.show === 'percent' )
					this.percent();
				MD.floatingBars.opened = MD.floatingBar.id;
			}
		},
		show: function() {
			var id = MD.floatingBar.id,
				el = document.getElementById( id );
			el.classList.remove( 'hide' );
			el.classList.add( 'active' );
			MD.floatingBars.showing = id;
			delete MD.floatingBars.data[id];
		},
		percent: function() {
			window.addEventListener( 'scroll', function() {
				var pos = window.scrollY,
					el = document.getElementById( MD.floatingBar.id );
				if ( ! el.classList.contains( 'closed' ) ) {
					window.requestAnimationFrame( function() {
						var percent = Math.round( ( pos / document.body.scrollHeight ) * 100 );
						if ( MD.floatingBar.delay <= percent )
							MD.floatingBars.open.show();
						else if ( el.classList.contains( 'active' ) )
							el.classList.remove( 'active' );
					});
				}
			} );
		},
		timer: function() {
			setTimeout( function() {
				MD.floatingBars.open.show();
			}, MD.floatingBar.delay * 1000 );
		}
	},
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
		document.addEventListener( 'click', function( e ) {
			var trigger = e.target.closest( '.popup-trigger' );
			if ( trigger ) {
				e.preventDefault();
				MD.popups.trigger = trigger.getAttribute( 'data-popup' );
				MD.popups.open.show();
			}
		} );
		document.addEventListener( 'click', function( e ) {
			if ( e.target.closest( '.close' ) || e.target.closest( '.popup-bg' ) )
				MD.popups.close.close();
		} );
		document.addEventListener( 'keydown', function( e ) {
			if ( e.key === 'Escape' )
				MD.popups.close.close();
		} );
		MD.popups.open.events();
	},
	open: {
		events: function() {
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
		percent: function() {
			var shown = false;
			window.addEventListener( 'scroll', function() {
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
					} );
			} );
		},
		timer: function() {
			setTimeout( function() {
				if ( ! MD.popups.trigger )
					MD.popups.open.show();
			}, MD.popup.delay * 1000 );
		},
		exit: function() {
			var shown = false;
			window.addEventListener( 'mousemove', function( e ) {
				if ( shown || MD.popups.trigger )
					return;
				var scroll = window.pageYOffset || document.documentElement.scrollTop;
				if ( ( e.pageY - scroll ) < 7 ) {
					shown = true;
					MD.popups.open.show();
				}
			} );
		},
		show: function() {
			var id = MD.popups.trigger ? MD.popups.trigger : MD.popup.id;
			document.documentElement.classList.add( 'has-popup' );
			if ( MD.popups.showing && MD.popups.trigger )
				document.getElementById( MD.popups.showing ).classList.remove( 'active' );
			document.getElementById( id ).classList.add( 'active' );
			MD.focusInputs( id );
			MD.popups.showing = id;
			if ( ! MD.popups.trigger )
				delete MD.popups.data[MD.popup.id];
		}
	},
	close: {
		close: function() {
			document.documentElement.classList.remove( 'has-popup' );
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
			document.getElementById( id ).classList.remove( 'active' );
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
					el.classList.add( 'liked' );
					if ( ! el.classList.contains( 'share-like-total' ) )
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
			if ( this.classList.contains( 'liked' ) )
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
			this.classList.add( 'liked' );
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
	document.querySelectorAll( '[data-toggle="beacon-menu"]' ).forEach( function( trigger ) {
		trigger.addEventListener( 'click', function() {
			document.getElementById( 'header' ).classList.remove( 'toggle-menu' );
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
		} )
			.then( function( response ) { return response.json(); } )
			.then( function( response ) {
				if ( response && response.success )
					window.location.reload();
				else
					licenseSwitch.classList.remove( 'md-loading' );
			} )
			.catch( function() { licenseSwitch.classList.remove( 'md-loading' ); } );
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
}