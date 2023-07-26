<style type="text/css">

/*------------------------------*\
	$MENUS
\*------------------------------*/

.menu, .menu ul { list-style: none; }

.sub-menu {
	font-size: <?php echo $typography['header']['font_size']['tablet']; ?>px;
	display: none;
	line-height: <?php echo $typography['header']['line_height']['tablet']; ?>px;
	z-index: 50;
}

/* MENU ITEM */

.menu-item {
	cursor: pointer;
	display: inline-block;
	position: relative;
}

.menu-item a {
	display: inline-block;
	padding: <?php echo $half; ?>px;
	position: relative;
}

.menu > .menu-item:hover .menu-toggle, .sub-menu .menu-toggle { background-color: <?php echo $colors['header']['border_color']; ?>; }

.menu-item-title { position: relative; }

.menu-item-desc {
	display: block;
	font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 0.8 ); ?>px;
	line-height: <?php echo round( $typography['header']['line_height']['desktop'] * 0.8 ); ?>px;
}

.menu .menu-item.button a { line-height: 1; }

/* TRIGGERS */

.menu-trigger { cursor: pointer; }

.menu-toggle {
	display: inline-block;
	font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.1 ); ?>px;
	line-height: 1;
	padding-left: <?php echo $small; ?>px;
	padding-right: <?php echo $small; ?>px;
	text-align: center;
	top: <?php echo $third + $small; ?>px;
	left: <?php echo $half; ?>px;
}

.menu-toggle:before, .menu-toggle:after {
	font-family: md-icon;
	line-height: 1;
}

.menu-toggle:after { content: '\e80e'; }

.sub-menu .menu-toggle {
	padding: 2px <?php echo $third; ?>px;
	position: absolute;
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
	.menu-item.button { margin-left: <?php echo $small; ?>px; }
	.menu-item.button a { padding: <?php echo $third; ?>px <?php echo $third + $small; ?>px; }
	.menu-item-has-children a { padding-right: <?php echo $small; ?>px; }
	.menu-item-has-children:hover > .sub-menu { display: block; }
	.sub-menu {
		background-color: <?php echo $colors['header']['submenu']['bg_color']; ?>;
		border-radius: 5px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		position: absolute;
			right: -<?php echo $single; ?>px;
		width: <?php echo $submenu_width; ?>px;
	}
	.sub-menu a, .sub-menu .menu-toggle { color: <?php echo $colors['header']['submenu']['links']; ?>; }
	.sub-menu a:hover {
		background-color: rgba(0, 0, 0, 0.08);
		color: <?php echo $colors['header']['submenu']['hover']; ?>;
	}
	.sub-menu .sub-menu {
		right: <?php echo $submenu_width; ?>px;
		top: 0;
	}
	.sub-menu .menu-item:not(:last-child) a { border-bottom: 1px solid <?php echo $colors['header']['border_color']; ?>; }
	.sub-menu > .menu-item:first-child > a:hover { border-radius: 5px 5px 0 0; }
	.sub-menu > .menu-item:last-child > a:hover { border-radius: 0 0 5px 5px; }
	.sub-menu .menu-item, .sub-menu .menu-item a { display: block; }
	.menu .sub-menu > .menu-item-has-children > a { padding-left: <?php echo $mid + $small; ?>px }
	.menu .sub-menu .menu-toggle:after { content: '\e816'; }
}

@media all and (max-width: 800px) {
	.menu-item, .menu-item a, .menu .toggle-menu > .sub-menu { display: block; }
	.menu-secondary:not(:first-child) { border-top: 1px solid <?php echo $colors['header']['border_color']; ?>; }
	.menu .menu-item.button {
		font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.1 ); ?>px;
		padding: <?php echo $half; ?>px;
	}
	.menu-toggle {
		background-color: <?php echo $colors['header']['border_color']; ?>;
		padding: 2px <?php echo $third; ?>px;
		position: absolute;
			left: inherit;
			right: <?php echo $half + $third; ?>px;
	}
	.sub-menu .toggle-menu { background-color: <?php echo $colors['site']['bg_color']; ?>; }
	.toggle-menu > .menu-toggle:after { content: '\e817'; }
	.sub-menu .menu-item a {
		padding-bottom: <?php echo $third; ?>px;
		padding-top: <?php echo $third; ?>px;
	}
}



/*------------------------------*\
	$MAIN_MENU
\*------------------------------*/

.main-menu {
	background-color: <?php echo $colors['main_menu']['bg_color']; ?>;
	border-top: 1px solid <?php echo $colors['header']['border_color']; ?>;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
	color: <?php echo $colors['main_menu']['subtext']; ?>;
	font-size: <?php echo $typography['header']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['header']['line_height']['desktop']; ?>px;
}

.menu-main {
	flex: 1 0 auto;
}
