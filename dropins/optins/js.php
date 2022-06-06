<script>

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
},