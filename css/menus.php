<style type="text/css">

<?php
	$m_tb = ( ! empty( $header['menu']['spacing_tb'] ) ? $header['menu']['spacing_tb'] : $half );
	$m_lr = ( ! empty( $header['menu']['spacing_lr'] ) ? $header['menu']['spacing_lr'] : $half );
?>

/*------------------------------*\
	$MENUS
\*------------------------------*/

.menu, .menu ul { list-style: none; }

/* MENU ITEM */

.menu-item {
	cursor: pointer;
	display: inline-block;
	position: relative;
	text-align: left;
}

.menu-item a {
	display: inline-block;
	padding: <?php echo $m_tb; ?>px <?php echo $m_lr; ?>px;
	position: relative;
}

.menu-item-title { position: relative; }

.menu-item-desc {
	display: block;
	font-size: <?php echo round( $font_size['desktop'] * 0.8 ); ?>px;
	line-height: <?php echo round( $line_height['desktop'] * 0.8 ); ?>px;
}

.sub-menu .current-menu-item > a > .menu-item-title { font-weight: <?php echo $bold; ?>; }

/* TRIGGERS */

.menu-trigger { cursor: pointer; }

.menu-toggle:before, .menu-toggle:after {
	font-family: md-icon;
	line-height: 1;
}

.menu-toggle:after { content: '\e80e'; }

/* SUB MENU */

.sub-menu {
	font-size: <?php echo $typography['header']['font_size']['mobile']; ?>px;
	display: none;
	line-height: <?php echo $typography['header']['line_height']['mobile']; ?>px;
	z-index: 50;
}

.sub-menu .menu-toggle {
	height: 100%;
	position: absolute;
		top: 0;
}

/* BUTTON */

.menu-item.button {
	background-color: transparent;
	box-shadow: none;
	padding: 0;
}

.menu-item.button a, .menu-item.button a:hover {
	color: #fff;
	width: 100%;
}

/* QUERIES */

@media all and (min-width: 800px) {
	.menu-toggle {
		font-size: <?php echo round( $font_size['desktop'] * 0.8 ); ?>px;
		line-height: <?php echo round( $line_height['desktop'] * 0.8 ); ?>px;
	}
	.menu-item.button { margin-left: <?php echo $small; ?>px; }
	.menu > .menu-item-right { float: right; }
	.menu-item-has-children:hover > .sub-menu { display: block; }
	.sub-menu {
		background-color: <?php echo $colors['header']['submenu']['bg_color']; ?>;
		border-bottom: 2px solid <?php echo $colors['site']['primary']; ?>;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		position: absolute;
			right: 0;
		width: <?php echo $submenu_width; ?>px;
	}
	.menu-header .sub-menu .sub-menu {
		left: -<?php echo $submenu_width; ?>px;
		top: 0;
	}
	.menu .sub-menu a { border-bottom: 1px solid rgba(0, 0, 0, 0.1); }
	.menu .sub-menu .menu-item, .menu .sub-menu .menu-item a { display: block; }
}

@media all and (max-width: 800px) {
	.menu-item, .menu-item a { display: block; }
	.sub-menu .sub-menu .sub-menu { margin-left: <?php echo $half; ?>px; }
	.menu-item-has-children.show-submenu > a > .menu-item-title { font-weight: <?php echo $bold; ?>; }
	.show-submenu > .menu-toggle:after { content: '\e817'; }
	.menu .menu-toggle {
		background-color: rgba(0, 0, 0, 0.05);
		font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.3 ); ?>px;
		height: 100%;
		padding-left: <?php echo round( $half * 1.5 ); ?>px;
		padding-top: <?php echo $half; ?>px;
		padding-right: <?php echo round( $half * 1.5 ); ?>px;
		position: absolute;
			top: 0;
			right: 0;
	}
}

<?php if ( has_nav_menu( 'header' ) ) : ?>
/*------------------------------*\
	$HEADER_MENU
\*------------------------------*/

.menu-header > .menu-item.current-menu-item > a { color: <?php echo $colors['header']['menu']['active']; ?>; }

.header .button, .header .button:hover, .menu-header > .current-menu-item.button > a { color: #fff; }

.menu-header .menu-item-desc { color: <?php echo $colors['header']['color']; ?>; }

.header-trigger { color: <?php echo $colors['header']['menu']['links']; ?>; }

@media all and (min-width: 800px) {
	.header-menu { display: inline-block; }
	.header .header-menu-trigger { display: none; }
	.menu-header .menu-item.button a {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	.menu-header .sub-menu > .menu-item-has-children > a { padding-left: <?php echo $single + $small; ?>px }
	.menu-header .sub-menu { background-color: <?php echo $colors['header']['submenu']['bg_color']; ?>; }
	.menu-header .sub-menu .sub-menu { right: <?php echo ( $single * 9 ); ?>px; }
	.menu-header .sub-menu a { color: <?php echo $colors['header']['submenu']['links']; ?>; }
	.menu-header .sub-menu a:hover {
		background-color: rgba(0, 0, 0, 0.1);
		color: <?php echo $colors['header']['submenu']['hover']; ?>;
	}
	.menu-header .sub-menu .menu-toggle {
		padding-top: <?php echo $half; ?>px;
		left: <?php echo $half; ?>px;
	}
	.menu-header .sub-menu .menu-toggle:before { content: '\e816'; }
	.menu-header .sub-menu .menu-toggle:after { content: ''; }
}

@media all and (max-width: 800px) {
	.header-menu {
		display: none;
		margin-bottom: -<?php echo $header['spacing_bottom']['tablet']; ?>px;
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
	.has-mobile-menu .header-menu, .menu-header .show-submenu > .sub-menu { display: block; }
	.menu-header .menu-item:not(:last-child) { border-bottom: 1px solid rgba(0, 0, 0, 0.15); }
	.menu-header a:hover {
		background-color: rgba(0, 0, 0, 0.1);
		color: <?php echo $colors['header']['submenu']['hover']; ?>;
	}
}
<?php endif; ?>

<?php if ( has_nav_menu( 'main' ) ) : ?>

/*------------------------------*\
	$MAIN_MENU
\*------------------------------*/

.main-menu {
	font-size: <?php echo $typography['header']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['header']['line_height']['desktop']; ?>px;
}

.main-menu, .main-menu-search { background-color: <?php echo $colors['main_menu']['bg_color']; ?>; }

.menu-main .menu-item a { color: <?php echo $colors['main_menu']['links']; ?>; }
.menu-main .menu-item a:hover { background-color: rgba(0, 0, 0, 0.05); }
.menu-main .sub-menu .menu-item { display: block; }
.menu-main .sub-menu .menu-item-has-children a { padding-right: <?php echo $double; ?>px }

.menu-main .menu-item-desc { color: <?php echo $colors['main_menu']['subtext']; ?>; }
.menu-main .button { border-radius: 0; }
.menu-main .button .menu-item-title, .menu-main .button .menu-item-desc { color: #fff; }

/* SEARCH */

.main-menu-search {
	position: relative;
	z-index: 15;
}

.main-menu-search, .has-search .menu-social, .has-search .menu-search .menu-trigger { display: none; }

.has-search .main-menu-side, .has-search .main-menu-search, .has-search .menu-search { display: block; }

.main-menu .search-input {
	background-color: transparent;
	border: none;
	box-shadow: none;
	margin-bottom: 0;
	padding: <?php echo $m_tb; ?>px <?php echo $m_lr; ?>px;
	width: <?php echo $sidebar_width; ?>px;
}

.main-menu .search-input:focus { box-shadow: none; }

.main-menu .search-submit {
	background-color: transparent;
	border-bottom: none;
	border-radius: 0;
	box-shadow: none;
	padding: 0;
}

/* SOCIAL */

.has-social .main-menu-side { display: block; }

.menu-social .menu-item a {
	font-family: md-icon;
	font-size: 20px;
	padding: 0;
}

<?php if ( ! empty( $colors['main_menu']['social'] ) ) : ?>
	.main-menu .menu.menu-social .menu-item a { color: <?php echo $colors['main_menu']['social']; ?>; }
<?php endif; ?>

/* TRIGGERS */

.main-menu .menu-trigger, .main-menu-triggers .menu-trigger,
.menu-popup, .main-menu .search-input, .main-menu .search-submit { color: <?php echo $colors['main_menu']['icons']; ?>; }

.menu-popup [class*="md-icon-"] { line-height: 1; }

/* QUERIES */

@media all and (min-width: 800px) {
	.menu-trigger, .menu-search { display: inline-block; }
	.main-menu-triggers { display: none; }
	.menu-main .menu-toggle {
		padding-left: <?php echo $small; ?>px;
		padding-right: <?php echo $small; ?>px;
	}
	.menu-main .menu-item-has-children a { padding-right: <?php echo $small; ?>px }
	.menu-main .sub-menu .menu-toggle {
		background-color: rgba(0, 0, 0, 0.05);
		font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.2 ); ?>px;
		padding-left: <?php echo $third; ?>px;
		padding-top: <?php echo $half; ?>px;
		padding-right: <?php echo $third; ?>px;
		right: 0;
	}
	.menu-main .sub-menu { left: 0; }
	.menu-main .sub-menu .sub-menu {
		left: <?php echo $submenu_width; ?>px;
		top: 0;
	}
	.menu-main .sub-menu .menu-item-has-children a { padding-right: <?php echo $single; ?>px }
	.menu-main .menu-item-has-children:hover { background-color: rgba(255, 255, 255, 0.7); }
	.menu-main .sub-menu, .menu-main > .current-menu-item, .menu-main > .menu-item-has-children:hover > a, .menu-main > .current-menu-item:hover > a { background-color: <?php echo $colors['main_menu']['sub_menu']; ?>; }
	.menu-main .current-menu-item a, .menu-main .menu-item-has-children:hover { color: <?php echo $colors['main_menu']['active']; ?>; }
	.menu-main .sub-menu .menu-toggle:after { content: '\e80f'; }
	.main-menu-content, .menu-main { float: left; }
	.has-social-menu {
		padding-bottom: <?php echo $half; ?>px;
		padding-top: <?php echo $half; ?>px;
	}
	.main-menu .menu-popup {
		float: right;
		margin-left: <?php echo $half; ?>px;
	}
	.main-menu.has-search .menu-popup { margin-top: 15px; }
	.menu-search + .menu-social {
		border-right: 1px solid rgba(0, 0, 0, 0.15);
		margin-right: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	.main-menu-side, .main-menu-triggers, .menu-search, .menu-social { float: right; }
	.has-search .menu-search { float: none; }
	.main-menu-side {
		margin-top: <?php echo $m_tb; ?>px;
		margin-right: <?php echo $m_tb; ?>px;
	}
	.has-search .main-menu-side { margin-top: 0; }
	.menu-social .menu-item:not(:last-child) { margin-right: <?php echo $half; ?>px; }
}

@media all and (max-width: 900px) {
	.has-search .main-menu-side {
		position: absolute;
			left: 0;
			top: 0;
		width: 100%;
	}
	.main-menu .search-input {
		padding: <?php echo $half; ?>px;
		width: 90%;
	}
	.main-menu .search-submit {
		float: right;
		padding-top: <?php echo $half; ?>px;
		width: 10%;
	}
}

@media all and (max-width: 800px) {
	.menu-content, .main-menu-side, .menu-main.menu .menu-item-desc { display: none; }
	.has-menu .menu-main, .has-social .menu-social { display: block; }
	.has-search .main-menu-side { position: static; }
	.menu-trigger, .main-menu .md-popup-trigger {
		background-color: rgba(0, 0, 0, 0.05);
		border-bottom: 1px solid rgba(0, 0, 0, 0.1);
		padding: <?php echo $third; ?>px;
		text-align: center;
	}
	.menu-trigger:not(:last-child) { border-right: 1px solid rgba(0, 0, 0, 0.15); }
	.has-search .main-menu-triggers .menu-trigger-search, .has-menu .menu-trigger-menu, .has-social .menu-trigger-social {
		background-color: transparent;
		border-bottom: 0;
	}
	.menu-trigger-text {
		font-size: <?php echo round( $typography['header']['font_size']['desktop'] * .9 ); ?>px;
		margin-left: <?php echo $third; ?>px;
	}
	.menu-main { padding-bottom: 0; }
	.menu-social .menu-item:not(:last-child), .menu-main .menu-item:not(:last-child) { border-bottom: none; }
	.menu-main > .menu-item a, .menu-main .show-submenu > .sub-menu { display: block; }
	.menu-main .menu-item:not(:last-child) { border-bottom: 1px solid rgba(0, 0, 0, 0.15); }
	.menu-main > .menu-item-has-children.show-submenu, .menu-main .sub-menu .show-submenu { background-color: rgba(0, 0, 0, 0.08); }
	.menu-social { 
		padding: <?php echo $half; ?>px;
		text-align: center;
	}
	.menu-social.menu .menu-item a { font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px; }
	.menu-social.menu > .menu-item {
		display: inline-block;
		float: none;
	}
	.menu-social.menu .menu-item:not(:last-child) { margin-right: <?php echo $single; ?>px; }
	.menu-main .sub-menu {
		background-color: transparent;
		border-bottom: 0;
		box-shadow: none;
		position: relative;
		width: auto;
	}
	.main-menu-triggers-4 .menu-trigger, .main-menu-triggers-4 .menu-popup {
		float: left;
		width: 25%;
	}
	.main-menu-triggers-3 .menu-trigger, .main-menu-triggers-3 .menu-popup {
		float: left;
		width: 33.333333333%;
	}
	.main-menu-triggers-2 .menu-trigger, .main-menu-triggers-2 .menu-popup {
		float: left;
		width: 50%;
	}
}

@media all and (max-width: 600px) {
	.menu-main .sub-menu .sub-menu { margin-left: 0; }
	.menu-main .sub-menu .sub-menu .sub-menu { margin-left: <?php echo $m_lr; ?>px; }
	.main-menu .columns-3 .col {
		float: left;
		width: 33.333333333%;
	}
	.main-menu .columns-4 .col { width: 25%; }
}

@media all and (max-width: 400px) {
	.menu-trigger-text { display: none; }
}

.menu-social .menu-item a[href*="wordpress.org"],.menu-social .menu-item a[href*="wordpress.com"]{color:#21759b}.menu-social .menu-item a[href*="wordpress.org"]:before,.menu-social .menu-item a[href*="wordpress.com"]:before{content:'\e80b'}.menu-social .menu-item a[href*="facebook.com"]{color:#3b5998}.menu-social .menu-item a[href*="facebook.com"]:before{content:'\f09a'}.menu-social .menu-item a[href*="twitter.com"]{color:#55acee}.menu-social .menu-item a[href*="twitter.com"]:before{content:'\e800'}.menu-social .menu-item a[href*="dribbble.com"]{color:#ea4c89}.menu-social .menu-item a[href*="dribbble.com"]:before{content:'\e80c'}.menu-social .menu-item a[href*="plus.google.com"]{color:#dd4b39}.menu-social .menu-item a[href*="plus.google.com"]:before{content:'\f0d5'}.menu-social .menu-item a[href*="pinterest.com"]{color:#cc2127}.menu-social .menu-item a[href*="pinterest.com"]:before{content:'\e803'}.menu-social .menu-item a[href*="tumblr.com"]{color:#35465c}.menu-social .menu-item a[href*="tumblr.com"]:before{content:'\e808'}.menu-social .menu-item a[href*="youtube.com"]{color:#e52d27}.menu-social .menu-item a[href*="youtube.com"]:before{content:'\e807'}.menu-social .menu-item a[href*="flickr.com"]{color:#0063dc}.menu-social .menu-item a[href*="flickr.com"]:before{content:'\e80a'}.menu-social .menu-item a[href*="vimeo.com"]{color:#162221}.menu-social .menu-item a[href*="vimeo.com"]:before{content:'\e809'}.menu-social .menu-item a[href*="instagram.com"]{color:#3f729b}.menu-social .menu-item a[href*="instagram.com"]:before{content:'\e805'}.menu-social .menu-item a[href*="linkedin.com"]{color:#0976b4}.menu-social .menu-item a[href*="linkedin.com"]:before{content:'\f0e1'}.menu-social .menu-item a[href*="github.com"]{color:#0976b4}.menu-social .menu-item a[href*="github.com"]:before{content:'\e802'}.menu-social .menu-item a[href*="medium.com"]:before{content:'\f23a'}.menu-social .menu-item a[href*="medium.com"]{color:#58595b}.menu-social .menu-item.rss a:before {content:'\f09e';}.menu-social .menu-item.rss a{color:#ff9900;}.menu-social .menu-item a[href*="periscope.tv"]{color:#e94f3c;margin-left:-3px}.menu-social .menu-item a[href*="periscope.tv"]:before{content:'\e81d'}.menu-social .menu-item a[href*="speakerdeck.com"]:before{content:'\e81f';}.menu-social .menu-item a[href*="speakerdeck.com"]{color:#3AB278;margin-left:-5px;}.menu-social .menu-item a[href*="t.me"]:before{content:'\e839';}

<?php endif; ?>