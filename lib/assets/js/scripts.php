<script>

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
toggleMenu: function() {
	var menuToggles = document.getElementsByClassName( 'menu-toggle' );
	for ( var i = 0; i < menuToggles.length; i++ ) {
		menuToggles[i].onclick = function( e ) {
			var menuID = this.getAttribute( 'data-menu-toggle' );
			MD.toggleClass( document.getElementById( menuID ), 'show-submenu' );
		}
	}
},

<?php if ( has_nav_menu( 'header' ) ) : ?>

headerMenu: function() {
	this.toggleMenu();
	var headerTrigger = document.getElementById( 'header-menu-trigger' );
	if ( headerTrigger )
		headerTrigger.onclick = function( e ) {
			MD.toggleClass( document.getElementById( 'header' ), 'has-mobile-menu' );
		}
},

<?php endif; ?>

<?php if ( has_nav_menu( 'main' ) ) : ?>

mainMenu: function() {
	this.toggleMenu();
	var mainMenu = document.getElementById( 'main_menu' ),
		triggers = document.getElementsByClassName( 'menu-trigger' );
	if ( mainMenu == null )
		return;
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

<?php endif; ?>

<?php if ( has_action( 'md_js_onscroll' ) ) : ?>

onScroll: function() {
	var pos = 0, ticking = false;
	window.onscroll = function( e ) {
		pos = window.scrollY;
		if ( ! ticking ) {
			window.requestAnimationFrame( function() {
				var contentBox = document.getElementById( 'content_box' );
				if ( contentBox == null ) return;
				var contentBoxOffsetTop = contentBox.offsetTop,
					content = document.getElementById( 'the_content' );
				if ( content == null ) return;
				<?php do_action( 'md_js_onscroll' ); ?>
				ticking = false;
			});
		}
		ticking = true;
	}
},

<?php endif; ?>