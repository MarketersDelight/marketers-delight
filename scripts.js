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
			document.cookie = name + '=' + value + expires + '; path=/';
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
	headerMenu: function() {
		var headerTrigger = document.getElementById( 'header-menu-trigger' );
		if ( headerTrigger )
			headerTrigger.onclick = function( e ) {
				MD.toggleClass( document.getElementById( 'header' ), 'has-mobile-menu' );
			}
	},
	mainMenu: function() {
		var mainMenu = document.getElementById( 'main_menu' ),
			triggers = document.getElementsByClassName( 'menu-trigger' );
		for ( var i = 0; i < triggers.length; i++ ) {
			triggers[i].onclick = function( e ) {
				e.preventDefault();
				var type = this.dataset.menuTrigger;
				if ( MD.hasClass( mainMenu, 'has-' + type ) )
					MD.removeClass( mainMenu, 'has-' + type );
				else {
					mainMenu.className = 'main-menu';
					MD.addClass( mainMenu, 'has-' + type );
				}
				if ( type == 'search' )
					document.getElementById( 'main_menu_search_input' ).focus();
			}
		}
		document.onclick = function( e ) {
			var target = e.target || e.srcElement;
			do {
				if ( mainMenu === target )
					return;
				target = target.parentNode;
			}
			while ( target )
				MD.removeClass( mainMenu, 'has-search' );
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
				document.getElementById( parent ).className = parent + ' has-' + tabID;
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
	footnotes: function() {
		var footnotes = document.getElementsByClassName( 'footnote' );
		for ( var i = 0; i < footnotes.length; i++ ) {
			footnotes[i].onclick = function( e ) {
				MD.toggleClass( document.getElementById( this.id ), 'footnote-show' );
			}
		}
	},
	button: function() {
		var buttons = document.getElementsByClassName( 'button-loading' );
		for ( var i = 0; i < buttons.length; i++ ) {
			buttons[i].onclick = function( e ) {
				var form = this.parentNode;
				form.addEventListener( 'submit', function() {
					MD.addClass( this, 'is-loading' );
				});
			}
		}
	},
	share: {
		init: function() {
			MD.share.window();
			MD.share.like();
			if ( document.getElementById( 'share_side' ) )
				MD.share.sticky();
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
				likes = document.getElementsByClassName( 'share-like' ),
				totals = document.getElementsByClassName( 'share-total' );
			for ( var i = 0; i < likes.length; i++ ) {
				likes[i].onclick = function( e ) {
					e.preventDefault();
					if ( ! MD.hasClass( this, 'liked' ) ) {
						var post_id = this.getAttribute( 'data-share-id' ),
							post_type = this.getAttribute( 'data-share-type' ),
							archive = this.getAttribute( 'data-share-archive' ),
							counts = document.getElementsByClassName( 'share-count' ),
							request = new XMLHttpRequest();
						for ( var i = 0; i < counts.length; i++ )
							if ( likes[i].getAttribute( 'data-share-id' ) === post_id ) {
								counts[i].innerHTML++;
								MD.addClass( likes[i], 'liked' );
							}
						request.open( 'POST', MDJS.ajaxurl, true );
						request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
						request.onreadystatechange = function() {
							if ( request.readyState === 4 && request.status === 200 ) {
								var liked = MD.cookie.get( name ) ? JSON.parse( MD.cookie.get( name ) ) : [];
								liked.push( post_id );
								if ( totals )
									for ( var i = 0; i < totals.length; i++ )
										if ( totals[i].getAttribute( 'data-share-total' ) === post_type )
											totals[i].innerHTML++;
								MD.cookie.create( name, JSON.stringify( liked ), 365 );
							}
						};
						request.send( 'action=md_like&post_id=' + post_id + '&archive=' + archive + '&nonce=' + MDJS.nonce );
					}
				}
			}
		},
		sticky: function() {
			var content = document.getElementById( 'content_box' ),
				contentHeight = content.offsetTop + content.clientHeight,
				share = document.getElementById( 'share_side' ),
				pos = 0,
				ticking = false;
			window.onscroll = function( e ) {
				pos = window.scrollY;
				if ( ! ticking ) {
					window.requestAnimationFrame( function() {
						if ( pos > content.offsetTop )
							MD.addClass( share, 'is-sticky' );
						else
							MD.removeClass( share, 'is-sticky' );

						if ( pos > ( ( content.offsetTop + content.clientHeight ) - 300 ) ) {
							MD.removeClass( share, 'is-sticky' );
							MD.addClass( share, 'is-stuck' );
						}
						else {
							MD.removeClass( share, 'is-stuck' );
							if ( pos > content.offsetTop )
								MD.addClass( share, 'is-sticky' );
						}
						ticking = false;
					});
				}
				ticking = true;
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
	floatingBars: {
		init: function( floatingBars ) {
			this.opened = false;
			this.data = floatingBars;
			MD.floatingBars.open.events();
			MD.floatingBars.close.events();
		},
		open: {
			events: function() {
				for ( var id in MD.floatingBars.data ) {
					if ( MD.floatingBars.opened ) break;
					MD.floatingBar = MD.floatingBars.data[id];
					if ( MD.floatingBar.show === 'seconds' )
						this.timer();
					if ( MD.floatingBar.show === 'percent' )
						this.percent();
					MD.floatingBars.opened = true;
				}
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
			},
			show: function() {
				var element = document.getElementById( MD.floatingBar.id );
				MD.addClass( element, 'active' );
				if ( ! MD.hasClass( element, 'top static' ) )
					setTimeout( function() { MD.focusInputs( MD.floatingBar.id ); }, 100 );
				if ( MD.floatingBar.position === 'top' ) {
					var adminBar = MDJS.hasAdminBar ? 32 : 0;
					document.getElementsByTagName( 'body' )[0].style.paddingTop = ( element.clientHeight + adminBar ) + 'px';
				}
			}
		},
		close: {
			events: function() {
				this.trigger();
			},
			trigger: function() {
				var triggers = document.getElementsByClassName( 'cta-bar-close' )
				for ( var i = 0; i < triggers.length; i++ ) {
					triggers[i].onclick = function() {
						MD.removeClass( document.getElementById( MD.floatingBar.id ), 'active' );
						MD.floatingBars.close.close();
					}
				}
			},
			close: function() {
				if ( MD.floatingBar.position === 'top' )
					document.getElementsByTagName( 'body' )[0].style.paddingTop = '';
				MD.addClass( document.getElementById( MD.floatingBar.id ), 'closed' );
				MD.floatingBars.opened = false;
				if ( ! MD.cookie.get( MD.floatingBar.id ) )
					MD.cookie.create( MD.floatingBar.id, true, MD.floatingBar.cookieExp );
				delete MD.floatingBars.data[MD.floatingBar.id];
				MD.floatingBars.open.events();
			}
		}
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
					if ( MD.popups.opened ) break;
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
				var triggers = document.getElementsByClassName( 'md-popup-trigger' );
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
					if ( shown || MD.popups.trigger ) return;
					var pos = window.scrollY,
						el = document.getElementById( MD.popup.id );
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
					if ( shown || MD.popups.trigger ) return;
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
					MD.removeClass( document.getElementById( MD.popups.showing ), 'md-popup-active' );
				MD.addClass( document.getElementById( id ), 'md-popup-active' );
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
				var triggers = document.getElementsByClassName( 'md-popup-close' );
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
					var id = MD.popup.id;
					if ( MD.popup.cookieExp && ! MD.cookie.get( id ) )
						MD.cookie.create( id, true, MD.popup.cookieExp );
				}
				delete MD.popups.opened;
				delete MD.popups.showing;
				MD.removeClass( document.getElementById( id ), 'md-popup-active' );
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
	}
};