<style type="text/css">

/*------------------------------*\
	$MENUS
\*------------------------------*/

.menu, .menu ul { list-style: none; }

.current-menu-item > a > .menu-item-title { font-weight: <?php echo $bold; ?>; }

/* MENU ITEM */

.menu-item {
	display: inline-block;
	position: relative;
	text-align: left;
}

.menu-item a {
	display: block;
	position: relative;
}

.menu-item-title { position: relative; }

/* SUB MENU */

.sub-menu { z-index: 50; }
.sub-menu a:hover { background-color: rgba(0, 0, 0, 0.05); }

/* BUTTON */

.menu-item.button {
	background-color: transparent;
	box-shadow: none;
}

.menu-item.button a, .menu-item.button a:hover { color: #fff; }

/* QUERIES */

@media all and (min-width: 700px) {
	.menu-item.button {
		margin-left: <?php echo $half; ?>px;
		padding: 0;
		text-align: center;
	}
	.menu > .menu-item-right { float: right; }
	.menu-item a { padding: <?php echo $half; ?>px; }
	.sub-menu {
		background-color: #fff;
		border-bottom: 2px solid <?php echo $colors['site']['primary']; ?>;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		display: none;
		position: absolute;
			right: 0;
		width: <?php echo ( $single * 9 ); ?>px;
	}
	.sub-menu .sub-menu {
		left: -<?php echo ( $single * 9 ); ?>px;
		top: 0;
	}
	.menu .sub-menu a { border-bottom: 1px solid rgba(0, 0, 0, 0.08); }
	.menu-item-has-children:hover > .sub-menu { display: block; }

	.menu > .menu-item-has-children > a > .menu-item-title:after,
	.menu:not(.menu-header) .sub-menu .menu-item-has-children:after,
	.menu-header .sub-menu .menu-item-has-children:before {
		font-family: md-icon;
		margin-left: <?php echo $small; ?>px;
	}

	.menu > .menu-item-has-children > a > .menu-item-title:after {
		content: '\e80e';
		line-height: 1;
	}
	.menu:not(.menu-header) .sub-menu .menu-item-has-children:after,
	.menu-header .sub-menu .menu-item-has-children:before {
		content: '\e80f';
		margin-top: -14px;
		top: 50%;
		right: <?php echo ( $half + $small ); ?>px;
	}
	.menu-header .sub-menu .menu-item-has-children:before {
		content: '\e816';
		color: <?php echo $colors['header']['menu']['links']; ?>;
		left: <?php echo $half; ?>px;
		right: auto;
	}
}

@media all and (max-width: 700px) {
	.sub-menu .sub-menu { border-left: 1px solid rgba(0, 0, 0, 0.15); }
	.sub-menu .sub-menu .sub-menu { margin-left: 16px; }
	.sub-menu .sub-menu a { padding-left: 16px; }
}

<?php if ( has_nav_menu( 'header' ) ) : ?>
/*------------------------------*\
	$HEADER_MENU
\*------------------------------*/

.menu-header .sub-menu .menu-item { display: block; }

.menu-header > .menu-item.current-menu-item > a { color: <?php echo $colors['header']['menu']['active']; ?>; }

.menu-header .menu-item.button a {
	padding-left: <?php echo $single; ?>px;
	padding-right: <?php echo $single; ?>px;
}

.header .button, .header .button:hover, .menu-header > .current-menu-item.button > a { color: #fff; }

@media all and (min-width: 700px) {
	.header-menu { display: inline-block; }
	.header .header-menu-trigger { display: none; }
	.menu-header .menu-item:not(.button):last-child a { padding-right: 0; }
	.sub-menu .menu-item a { padding: <?php echo $half; ?>px; }
	.menu-header > .menu-item > a {
		padding-left: <?php echo $header['menu']['spacing_lr']; ?>px;
		padding-right: <?php echo $header['menu']['spacing_lr']; ?>px;
	}
	.menu-header > .menu-item-has-children > a { padding-right: 26px; }
	.menu-header .sub-menu { background-color: <?php echo $colors['header']['submenu']['bg_color']; ?>; }
	.menu-header .sub-menu .sub-menu { right: <?php echo ( $single * 9 ); ?>px; }
	.menu-header .sub-menu > .menu-item-has-children > a { padding-left: 33px; }
	.menu-header .sub-menu a { color: <?php echo $colors['header']['submenu']['links']; ?>; }
	.menu-header .sub-menu a:hover { color: <?php echo $colors['header']['submenu']['hover']; ?>; }
}

@media all and (max-width: 700px) {
	.header-menu {
		display: none;
		margin-top: <?php echo $half; ?>px;
	}
	.has-mobile-menu .header-menu, .menu-header .menu-item { display: block; }
	.menu-header .sub-menu {
		border-left: 1px solid rgba(0, 0, 0, 0.15);
		margin-left: <?php echo $small; ?>px;
	}
	.menu-header .sub-menu .sub-menu { margin-left: <?php echo $half; ?>px; }
	.menu-header .menu-item-has-children > a { padding-bottom: <?php echo $half; ?>px; }
	.menu-header .menu-item a, .menu .current-menu-item.menu-item a {
		padding-bottom: <?php echo $half; ?>px;
		padding-top: <?php echo $half; ?>px;
	}
	.menu-item.button {
		padding: <?php echo $half; ?>px 0;
		text-align: center;
	}
}
<?php endif; ?>

<?php if ( has_nav_menu( 'main' ) ) : ?>
/*------------------------------*\
	$MAIN_MENU
\*------------------------------*/

<?php
	$mms_tb = ( ! empty( $header['main_menu']['spacing_tb'] ) ? $header['main_menu']['spacing_tb'] : $third );
	$mms_lr = ( ! empty( $header['main_menu']['spacing_lr'] ) ? $header['main_menu']['spacing_lr'] : $half );
?>

.main-menu { background-color: <?php echo $colors['main_menu']['bg_color']; ?>; }

.menu-main-wrap {
	overflow-x: auto;
	scrollbar-width: none;
	white-space: nowrap;
	width: 100%;
	-ms-overflow-style: none;
}

.main-menu-side {
	align-items: center;
	background-color: <?php echo $colors['main_menu']['bg_color']; ?>;
	display: flex;
	height: 100%;
	padding: <?php echo $mms_tb; ?>px <?php echo $mms_lr; ?>px;
	position: absolute;
		top: 0;
		right: 0;
}

.menu-main .menu-item a {
	color: <?php echo $colors['main_menu']['links']; ?>;
	padding: <?php echo $mms_tb; ?>px <?php echo $mms_lr; ?>px;
}

<?php if ( ! empty( $colors['main_menu']['links_hover'] ) ) : ?>
	.menu-main .menu-item a:hover, .menu-main .menu-item:hover .menu-item-desc { color: <?php echo $colors['main_menu']['links_hover']; ?>; }
<?php endif; ?>

.menu-main .sub-menu { background-color: <?php echo $colors['main_menu']['sub_menu']; ?>; }

.menu-main .current-menu-item a,
.menu-main .current-menu-item .menu-item-desc,
.menu-main .menu-item-has-children:hover a,
.menu-main .menu-item-has-children:hover .menu-item-desc { color: <?php echo $colors['main_menu']['active']; ?>; }

.menu-main .button { border-radius: 0; }
.menu-main .button .menu-item-title, .menu-main .button .menu-item-desc { color: #fff; }

.menu-main .sub-menu {
	display: none;
	position: absolute;
	left: 0;
	z-index: 10;
}

.menu-main .sub-menu .sub-menu { left: <?php echo ( $single * 9 ); ?>px; }

.menu-main .menu-item-desc {
	color: <?php echo $colors['main_menu']['subtext']; ?>;
	display: block;
	font-size: <?php echo round( $font_size['desktop'] * 0.8 ); ?>px;
	line-height: 1;
}

/* SEARCH */

.main-menu .search-input, .main-menu .search-input:focus {
	background-color: transparent;
	border: none;
	box-shadow: none;
	margin-bottom: 0;
	padding: <?php echo $mms_tb; ?>px <?php echo $mms_lr; ?>px;
	width: <?php echo $sidebar_width; ?>px;
}

.main-menu .search-submit {
	background-color: transparent;
	border-bottom: none;
	border-radius: 0;
	box-shadow: none;
	padding: 0;
	width: auto;
}

.has-search .main-menu-side { padding: 0; }

.has-search .menu-search { display: block; }

.menu-search, .has-search .menu-triggers, .has-search .menu-trigger, .has-search .menu-social { display: none; }

/* SOCIAL */

.menu-social {
	display: inline-block;
	line-height: 1;
}

.main-menu .menu.menu-social .menu-item a {
	<?php echo ( ! empty( $colors['main_menu']['social'] ) ? 'color: ' . $colors['main_menu']['social'] : '' ); ?>;
	font-family: md-icon;
	font-size: 20px;
	padding: 0;
}

.menu-social .menu-item a:hover { opacity: 0.8; }

.menu-social .menu-item:not(:last-child) { margin-right: <?php echo $third; ?>px; }

.has-social .menu-social { display: inline-block; }

/* TRIGGERS / ICONS */

.menu-triggers { display: inline-block; }

.menu-trigger {
	cursor: pointer;
	margin-left: <?php echo $third; ?>px;
	vertical-align: top;
}

.main-menu .menu-trigger, .main-menu .search-input, .main-menu .search-submit { color: <?php echo $colors['main_menu']['icons']; ?>; }

/* QUERIES */

@media all and (min-width: 700px) {
	.menu-trigger-social { display: none; }
	.menu-social:not(:first-child) { margin-left: <?php echo $half; ?>px; }
}

@media all and (max-width: 700px) {
	.main-menu.has-search .search-input {
		display: inline-block;
		width: 88%;
	}
	.main-menu.has-search .search-submit {
		display: inline-block;
		width: 10%;
	}
	.menu-social, .has-social .menu-scroller { display: none; }
	.has-social .menu-triggers { float: right; }
	.has-social .menu-trigger-search { display: inline-block; }
	.has-social .main-menu-side, .has-search .main-menu-side { width: 100%; }
	.has-social .menu-trigger-social:before { content: '\e810'; }
}

@media all and (min-width: <?php echo $site_width; ?>px) {
	.main-menu-side { padding-right: 0; }
	.menu-main .menu-item:first-child:not(.current-menu-item) a { padding-left: 0; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.main-menu-side { padding-right: <?php echo $half; ?>px; }
}

/* AUTO ICONS */

.menu-social .menu-item a[href*="wordpress.org"],.menu-social .menu-item a[href*="wordpress.com"]{color:#21759b}.menu-social .menu-item a[href*="wordpress.org"]:before,.menu-social .menu-item a[href*="wordpress.com"]:before{content:'\e80b'}.menu-social .menu-item a[href*="facebook.com"]{color:#3b5998}.menu-social .menu-item a[href*="facebook.com"]:before{content:'\f09a'}.menu-social .menu-item a[href*="twitter.com"]{color:#55acee}.menu-social .menu-item a[href*="twitter.com"]:before{content:'\e800'}.menu-social .menu-item a[href*="dribbble.com"]{color:#ea4c89}.menu-social .menu-item a[href*="dribbble.com"]:before{content:'\e80c'}.menu-social .menu-item a[href*="plus.google.com"]{color:#dd4b39}.menu-social .menu-item a[href*="plus.google.com"]:before{content:'\f0d5'}.menu-social .menu-item a[href*="pinterest.com"]{color:#cc2127}.menu-social .menu-item a[href*="pinterest.com"]:before{content:'\e803'}.menu-social .menu-item a[href*="tumblr.com"]{color:#35465c}.menu-social .menu-item a[href*="tumblr.com"]:before{content:'\e808'}.menu-social .menu-item a[href*="youtube.com"]{color:#e52d27}.menu-social .menu-item a[href*="youtube.com"]:before{content:'\e807'}.menu-social .menu-item a[href*="flickr.com"]{color:#0063dc}.menu-social .menu-item a[href*="flickr.com"]:before{content:'\e80a'}.menu-social .menu-item a[href*="vimeo.com"]{color:#162221}.menu-social .menu-item a[href*="vimeo.com"]:before{content:'\e809'}.menu-social .menu-item a[href*="instagram.com"]{color:#3f729b}.menu-social .menu-item a[href*="instagram.com"]:before{content:'\e805'}.menu-social .menu-item a[href*="linkedin.com"]{color:#0976b4}.menu-social .menu-item a[href*="linkedin.com"]:before{content:'\f0e1'}.menu-social .menu-item a[href*="github.com"]{color:#0976b4}.menu-social .menu-item a[href*="github.com"]:before{content:'\e802'}.menu-social .menu-item a[href*="medium.com"]:before{content:'\f23a'}.menu-social .menu-item a[href*="medium.com"]{color:#58595b}.menu-social .menu-item.rss a:before {content:'\f09e';}.menu-social .menu-item.rss a{color:#ff9900;}.menu-social .menu-item a[href*="periscope.tv"]{color:#e94f3c;margin-left:-3px}.menu-social .menu-item a[href*="periscope.tv"]:before{content:'\e81d'}.menu-social .menu-item a[href*="speakerdeck.com"]:before{content:'\e81f';}.menu-social .menu-item a[href*="speakerdeck.com"]{color:#3AB278;margin-left:-5px;}.menu-social .menu-item a[href*="t.me"]:before{content:'\e839';}
<?php endif; ?>