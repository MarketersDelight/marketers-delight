<style type="text/css">

/*------------------------------*\
	$MENUS
\*------------------------------*/

.menu, .menu ul { list-style: none; }

.current-menu-item > a > .menu-item-title { font-weight: <?php echo $bold; ?>; }

/* MENU ITEM */

.menu-item {
	display: block;
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

@media all and (min-width: 900px) {
	.menu > .menu-item { float: left; }
	.menu-item.button {
		margin-left: <?php echo $half; ?>px;
		padding: 0;
		text-align: center;
	}
	.menu > .menu-item-right { float: right; }
	.menu-item a { padding: 16px; }
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
		position: absolute;
			top: 3px;
			right: -1em;
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


@media all and (max-width: 900px) {
	.menu-item:not(:last-child) { border-bottom: 1px solid rgba(0, 0, 0, 0.15); }
	.sub-menu .sub-menu { border-left: 1px solid rgba(0, 0, 0, 0.15); }
	.sub-menu .sub-menu .sub-menu { margin-left: 16px; }
	.sub-menu .sub-menu a { padding-left: 16px; }
}

<?php if ( has_nav_menu( 'main' ) ) : ?>

/*------------------------------*\
	$MAIN_MENU
\*------------------------------*/

.main-menu, .main-menu-search { background-color: <?php echo $colors['main_menu']['bg_color']; ?>; }

.menu-trigger { cursor: pointer; }

.menu-main .menu-item a {
	color: <?php echo $colors['main_menu']['links']; ?>;
	<?php if ( ! empty( $header['main_menu']['spacing_tb']  ) ) : ?>
		padding-bottom: <?php echo $header['main_menu']['spacing_tb']; ?>px;
		padding-top: <?php echo $header['main_menu']['spacing_tb']; ?>px;
	<?php endif; ?>
	<?php if ( ! empty( $header['main_menu']['spacing_lr']  ) ) : ?>
		padding-left: <?php echo $header['main_menu']['spacing_lr']; ?>px;
		padding-right: <?php echo $header['main_menu']['spacing_lr']; ?>px;
	<?php endif; ?>
}

.menu-main .menu-item-desc {
	color: <?php echo $colors['main_menu']['subtext']; ?>;
	display: block;
	font-size: <?php echo round( $font_size['desktop'] * 0.8 ); ?>px;
	line-height: <?php echo round( $line_height['desktop'] * 0.8 ); ?>px;
}

.menu-trigger:hover, .menu-social.menu .menu-item a:hover { opacity: 0.8; }

.menu-main .button { border-radius: 0; }

.menu-main .button .menu-item-title, .menu-main .button .menu-item-desc { color: #fff; }

.menu-social .menu-item a {
	font-family: md-icon;
	font-size: 20px;
	padding: 0;
}

.main-menu-search {
	position: relative;
	z-index: 15;
}

.main-menu-search, .has-search .menu-social, .has-search .menu-search .menu-trigger { display: none; }

.has-search .main-menu-side, .has-social .main-menu-side, .has-search .main-menu-search, .has-search .menu-search { display: block; }

.main-menu .search-input, .main-menu .search-input:focus {
	background-color: transparent;
	border: none;
	box-shadow: none;
	margin-bottom: 0;
	width: 284px;
}

.main-menu .search-submit {
	background-color: transparent;
	border-bottom: none;
	border-radius: 0;
	box-shadow: none;
	padding-left: 0;
	padding-right: 0;
}

.main-menu .menu-trigger, .main-menu .search-input, .main-menu-triggers .menu-trigger, .main-menu .search-submit, .menu-popup { color: <?php echo $colors['main_menu']['icons']; ?>; }

.menu-popup [class*="md-icon-"] { line-height: 1; }

<?php if ( ! empty( $colors['main_menu']['social'] ) ) : ?>
	.main-menu .menu.menu-social .menu-item a { color: <?php echo $colors['main_menu']['social']; ?>; }
<?php endif; ?>

@media all and (min-width: <?php echo $site_width; ?>px) {
	.menu-trigger, .menu-search { display: inline-block; }
	.main-menu-triggers { display: none; }
	.menu-main .menu-item-has-children a { padding-right: <?php echo $single; ?>px; }
	.menu-main .sub-menu .sub-menu { left: <?php echo ( $single * 9 ); ?>px; }
	.menu-main .sub-menu, .menu-main > .current-menu-item > a, .menu-main > .menu-item-has-children > a:hover, .menu-main > .menu-item-has-children:hover > a { background-color: <?php echo $colors['main_menu']['sub_menu']; ?>; }
	.menu-main .current-menu-item a, .menu-main .menu-item-has-children:hover a, .menu-main .menu-item-has-children:hover .menu-item-desc, .menu-main .current-menu-item .menu-item-desc { color: <?php echo $colors['main_menu']['active']; ?>; }
	.main-menu-content, .menu-main { float: left; }
	.has-social-menu {
		padding-bottom: 16px;
		padding-top: 16px;
	}
	.main-menu .menu-popup {
		float: right;
		margin-left: 20px;
	}
	.main-menu.has-search .menu-popup { margin-top: 15px; }
	.menu-search + .menu-social {
		border-right: 1px solid rgba(0, 0, 0, 0.15);
		margin-right: 20px;
		padding-right: 20px;
	}
	.main-menu.has-search .main-menu-side { margin-top: -29px; }
	.main-menu-side, .main-menu-triggers, .menu-search, .menu-social { float: right; }
	.main-menu-side {
		margin-top: -14px;
		position: absolute;
			top: 50%;
			right: 0;
	}
	.menu-social .menu-item:not(:last-child) { margin-right: <?php echo $half; ?>px; }
}

@media all and (min-wdth: 900px) {
	.menu-main .sub-menu .sub-menu { left: 0; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.menu-content, .main-menu-side, .menu-main.menu .menu-item-desc { display: none; }
	.has-menu .menu-main, .has-social .menu-social { display: block; }
	.menu-trigger, .main-menu .md-popup-trigger {
		background-color: rgba(0, 0, 0, 0.05);
		border-bottom: 1px solid rgba(0, 0, 0, 0.1);
		padding: <?php echo $half; ?>px <?php echo $single; ?>px;
		text-align: center;
	}
	.menu-trigger:not(:last-child) { border-right: 1px solid rgba(0, 0, 0, 0.15); }
	.has-search .main-menu-triggers .menu-trigger-search, .has-menu .menu-trigger-menu, .has-social .menu-trigger-social {
		background-color: transparent;
		border-bottom: 0;
	}
	.menu-trigger-text {
		font-size: 14px;
		font-weight: 500;
		margin-left: 7px;
	}
	.menu-content { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }
	.menu-main { padding-bottom: 0; }
	.menu-social .menu-item:not(:last-child), .menu-main .menu-item:not(:last-child) { border-bottom: none; }
	.menu-main > .menu-item {
		display: block;
		float: left;
		margin-bottom: <?php echo $single; ?>px;
		vertical-align: top;
		width: 25%;
	}
	.menu-main > .menu-item:not(:first-child) { padding-left: <?php echo $half; ?>px; }
	.menu-main .sub-menu a:hover { background-color: transparent; }
	.menu-social { text-align: center; }
	.menu-social.menu .menu-item a { font-size: 26px; }
	.menu-social.menu > .menu-item {
		display: inline-block;
		float: none;
	}
	.menu-social.menu .menu-item:not(:last-child) { margin-right: <?php echo $single; ?>px; }
	.main-menu .main-menu-search .search-input, .main-menu .main-menu-search .search-input:focus {
		float: left;
		padding-left: 0;
		width: 90%;
	}
	.main-menu .search-submit {
		float: right;
		width: 10%;
	}
	.menu-main.menu > .menu-item-has-children > a > .menu-item-title:after, .menu-main .sub-menu .menu-item-has-children:after { content: ''; }
	.menu-main .sub-menu {
		background-color: transparent;
		border-bottom: 0;
		box-shadow: none;
		display: block;
		position: static;
		width: auto;
	}
	.menu-main .sub-menu a { border-bottom: 0; }
	.menu-main .sub-menu .sub-menu {
		border-left: 1px solid rgba(0, 0, 0, 0.15);
		margin-left: 16px;
	}
	.menu-main .sub-menu .sub-menu a { padding-left: 16px; }
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

@media all and (max-width: 900px) {
	.menu-main .sub-menu .sub-menu { margin-left: 0; }
	.menu-main .sub-menu .sub-menu .sub-menu { margin-left: 16px; }
	.main-menu .columns-3 .col {
		float: left;
		width: 33.333333333%;
	}
	.main-menu .columns-4 .col { width: 25%; }
}

@media all and (max-width: 700px) {
	.menu-main > .menu-item {
		margin-bottom: 13px;
		padding-left: 0;
		width: 100%;
	}
	.menu-main > .menu-item:not(:first-child) { padding-left: 0; }
	.menu-trigger-text { display: none; }
}

.menu-social .menu-item a[href*="wordpress.org"],.menu-social .menu-item a[href*="wordpress.com"]{color:#21759b}.menu-social .menu-item a[href*="wordpress.org"]:before,.menu-social .menu-item a[href*="wordpress.com"]:before{content:'\e80b'}.menu-social .menu-item a[href*="facebook.com"]{color:#3b5998}.menu-social .menu-item a[href*="facebook.com"]:before{content:'\f09a'}.menu-social .menu-item a[href*="twitter.com"]{color:#55acee}.menu-social .menu-item a[href*="twitter.com"]:before{content:'\e800'}.menu-social .menu-item a[href*="dribbble.com"]{color:#ea4c89}.menu-social .menu-item a[href*="dribbble.com"]:before{content:'\e80c'}.menu-social .menu-item a[href*="plus.google.com"]{color:#dd4b39}.menu-social .menu-item a[href*="plus.google.com"]:before{content:'\f0d5'}.menu-social .menu-item a[href*="pinterest.com"]{color:#cc2127}.menu-social .menu-item a[href*="pinterest.com"]:before{content:'\e803'}.menu-social .menu-item a[href*="tumblr.com"]{color:#35465c}.menu-social .menu-item a[href*="tumblr.com"]:before{content:'\e808'}.menu-social .menu-item a[href*="youtube.com"]{color:#e52d27}.menu-social .menu-item a[href*="youtube.com"]:before{content:'\e807'}.menu-social .menu-item a[href*="flickr.com"]{color:#0063dc}.menu-social .menu-item a[href*="flickr.com"]:before{content:'\e80a'}.menu-social .menu-item a[href*="vimeo.com"]{color:#162221}.menu-social .menu-item a[href*="vimeo.com"]:before{content:'\e809'}.menu-social .menu-item a[href*="instagram.com"]{color:#3f729b}.menu-social .menu-item a[href*="instagram.com"]:before{content:'\e805'}.menu-social .menu-item a[href*="linkedin.com"]{color:#0976b4}.menu-social .menu-item a[href*="linkedin.com"]:before{content:'\f0e1'}.menu-social .menu-item a[href*="github.com"]{color:#0976b4}.menu-social .menu-item a[href*="github.com"]:before{content:'\e802'}.menu-social .menu-item a[href*="medium.com"]:before{content:'\f23a'}.menu-social .menu-item a[href*="medium.com"]{color:#58595b}.menu-social .menu-item.rss a:before {content:'\f09e';}.menu-social .menu-item.rss a{color:#ff9900;}.menu-social .menu-item a[href*="periscope.tv"]{color:#e94f3c;margin-left:-3px}.menu-social .menu-item a[href*="periscope.tv"]:before{content:'\e81d'}.menu-social .menu-item a[href*="speakerdeck.com"]:before{content:'\e81f';}.menu-social .menu-item a[href*="speakerdeck.com"]{color:#3AB278;margin-left:-5px;}

<?php endif; ?>