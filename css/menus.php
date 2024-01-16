<style type="text/css">

/*------------------------------*\
	$MENUS
\*------------------------------*/

.menu, .menu ul { list-style: none; }

/* MENU ITEM */

.menu-item {
	display: flex;
	position: relative;
}

.menu-item a {
	padding: <?php echo $half; ?>px;
	position: relative;
	width: 100%;
}

.menu .trigger-icon:after { content: '\e80e'; }

.menu .button, .menu .button:hover {
	background-color: transparent;
	box-shadow: none;
	padding: 0;
}

/* SUB MENU */

.menu-item-has-children > a { flex: 1; }

.menu-item-has-children > .trigger {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.sub-menu {
	height: 0;
	opacity: 0;
	transform: translateY(-10px);
	visibility: hidden;
	z-index: 50;
}

.sub-menu .menu-item a {
	color: <?php echo $header['submenu']['links']; ?>;
	display: block;
}

@media all and (min-width: 800px) {
	.menu {
		align-items: center;
		display: flex;
	}
	.menu-item-has-children a { padding-right: 0; }
	/* SUB MENU */
	.sub-menu {
		background-color: <?php echo $header['submenu']['bg_color']; ?>;
		border-radius: 5px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		font-size: <?php echo $typography['body']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['tablet']; ?>px;
		position: absolute;
			right: -<?php echo $half; ?>px;
		width: <?php echo $submenu_width; ?>px;
	}
	.menu-item-has-children:hover > .sub-menu {
		height: auto;
		opacity: 1;
		transition: opacity 200ms linear,transform 200ms ease-out;
		transform: translateY(0);
		top: <?php echo $mid + $third; ?>px;
		visibility: visible;
	}
	.sub-menu .menu-item-has-children a { order: 2; }
	.menu-item .sub-menu .sub-menu {
		top: 0;
		right: <?php echo $submenu_width; ?>px;
	}
	.sub-menu .menu-item { align-items: end; }
	<?php if ( ! empty( $header['submenu']['hover'] ) ) : ?>
	.sub-menu .menu-item a:hover { color: <?php echo $header['submenu']['hover']; ?>; }
	<?php endif; ?>
	.sub-menu .menu-item:not(:last-child) a,
	.sub-menu .menu-item:not(:last-child) .trigger { border-bottom: 1px solid <?php echo $header['border_color']; ?>; }
	.sub-menu .trigger { padding: <?php echo $half; ?>px; }
	.sub-menu .trigger-icon { color: <?php echo $header['submenu']['links']; ?>; }
	.sub-menu .trigger-icon:after { content: '\e816'; }
	/* SUB MENU ALT DIRECTION */
	.sub-alt .sub-menu { right: inherit; }
	.sub-alt .sub-menu .sub-menu { right: -<?php echo $submenu_width; ?>px; }
	.sub-alt .sub-menu .menu-item-has-children a { order: inherit; }
	.sub-alt .sub-menu .trigger-icon:after { content: '\e80f'; }
}

@media all and (max-width: 800px) {
	/* MENU ITEM */
	.menu-item { flex-flow: wrap; }
	.menu-item:not(.toggle-menu):hover > a,
	.menu-item:not(.toggle-menu):hover > .menu-toggle { background-color: rgba(0, 0, 0, 0.05); }
	.menu-item:not(:last-child) { border-bottom: 1px solid <?php echo $header['border_color']; ?>; }
	.menu .button, .menu .button:hover {
		padding: <?php echo $half; ?>px;
		width: 100%;
	}
	/* TOGGLE */
	.menu-toggle { border-left: 1px solid <?php echo $header['border_color']; ?>; }
	.toggle-menu > .trigger .trigger-icon:after { content: '\e817'; }
	/* SUB MENU */
	.sub-menu { flex-basis: 100%; }
	.toggle-menu > .sub-menu {
		background-color: rgba(0, 0, 0, 0.1);
		height: auto;
		opacity: 1;
		transition: opacity 200ms linear,transform 200ms ease-out;
		transform: translateY(0);
		visibility: visible;
	}
	.toggle-menu > .sub-menu .sub-menu { background-color: rgba(0, 0, 0, 0.05); }
	.sub-menu .menu-item a {
		font-size: <?php echo $typography['body']['font_size']['mobile'] - 1; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile'] - 2; ?>px;
		padding-bottom: <?php echo $third; ?>px;
		padding-top: <?php echo $third; ?>px;
	}
}

/* MAIN MENU */

.main-menu {
	background-color: #fff;
	color: <?php echo $header['color']; ?>;
}

.main-menu a { color: <?php echo $header['color']; ?>; }

.main-menu-bar {
	background-color: rgba(0, 0, 0, 0.1);
	border-bottom: 1px solid <?php echo $header['border_color']; ?>;
	display: none;
}

.main-menu-title {
	flex: 1;
	font-size: <?php echo $typography['h4']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['h4']['line_height']['mobile']; ?>px;
}

.main-menu-menu { border-bottom: 1px solid <?php echo $header['border_color']; ?>; }

.menu-close { cursor: pointer; }

.main-menu-overlay {
	background-color: rgba(0, 0, 0, 0.8);
	height: 0;
	opacity: 0;
	position: absolute;
		left: 0;
		top: 0;
	transition: opacity 0.15s ease-in-out;
	visibility: hidden;
	width: 100%;
	z-index: 100;
}

@media all and (min-width: 800px) {
	.main-menu-menu { display: none; }
	.menu-main .current-menu-item, .menu-main .menu-item:hover { background-color: <?php echo $colors['site']['button-sec']; ?>; }
	.menu-main .current-menu-item > a, .menu-main .menu-item:hover > a, .menu-main .menu-item:hover > .menu-toggle .trigger-icon { color: <?php echo $colors['site']['button-sec-text']; ?>; }
}

@media all and (max-width: 800px) {
	.main-menu {
		box-shadow: 0 4px 30px rgba(0, 0, 0, 0.6);
		height: 100%;
		position: fixed;
			left: -<?php echo ( $double * 5 ); ?>px;
			top: 0;
		transition: 0.2s;
		width: <?php echo ( $double * 5 ); ?>px;
		z-index: 101;
	}
	.admin-bar .main-menu { top: <?php echo $admin_bar_height; ?>px; }
	.main-menu .inner {
		height: 100%;
		overflow-y: scroll;
	}
	.has-main-menu { overflow: hidden; }
	.has-main-menu .main-menu { left: 0; }
	.has-main-menu .main-menu-bar {
		align-items: center;
		display: flex;
		padding: <?php echo $third; ?>px <?php echo $small; ?>px <?php echo $third; ?>px <?php echo $half; ?>px;
	}
	.has-main-menu .main-menu-overlay {
		height: 100%;
		opacity: 1;
		visibility: visible;
	}
}

@media all and (max-width: 782px) {
	.admin-bar .main-menu { top: <?php echo $admin_bar_height_mobile; ?>px; }
}


