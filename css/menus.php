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

.menu-item-has-children > a { flex: 1; }

.menu-item-has-children > .trigger { padding-inline: <?php echo $third; ?>px; }

.menu .trigger-icon:after { content: '\e80e'; }

.menu .button, .menu .button:hover {
	background-color: transparent;
	box-shadow: none;
	padding: 0;
}

.menu-item-title { display: block; }

.menu-item-desc { font-size: 0.85em; }

/* SUB MENU */

.sub-menu {
	height: 0;
	opacity: 0;
	transform: translateY(-10px);
	visibility: hidden;
	z-index: 50;
}

.sub-menu .menu-item a {
	color: <?php echo $colors['submenu']['links']; ?>;
	display: block;
}

@media all and (min-width: 900px) {
	.menu {
		align-items: center;
		display: flex;
	}
	.menu-item-has-children a { padding-inline-end: 0; }
	/* SUB MENU */
	.sub-menu {
		background-color: <?php echo $colors['submenu']['bg_color']; ?>;
		border-radius: 5px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
		position: absolute;
			right: 0;
		width: <?php echo $submenu_width; ?>px;
	}
	.sub-menu .menu-item-has-children { flex-direction: row-reverse; }
	.menu-item-has-children:hover > .sub-menu {
		height: auto;
		opacity: 1;
		transition: opacity 200ms linear,transform 200ms ease-out;
		transform: translateY(0);
		top: <?php echo $mid + $third; ?>px;
		visibility: visible;
	}
	.menu-item .sub-menu .sub-menu {
		top: 0;
		left: -<?php echo $submenu_width; ?>px;
	}
	.sub-menu .menu-item { align-items: end; }
	<?php if ( ! empty( $colors['submenu']['hover'] ) ) : ?>
	.sub-menu .menu-item a:hover { color: <?php echo $colors['submenu']['hover']; ?>; }
	<?php endif; ?>
	.sub-menu .menu-item:not(:last-child) a,
	.sub-menu .menu-item:not(:last-child) .trigger { border-bottom: 1px solid <?php echo $colors['header']['border_color']; ?>; }
	.sub-menu .trigger { padding: <?php echo $half; ?>px; }
	.sub-menu .trigger-icon:after { content: '\e816'; }
	/* SUB MENU ALT DIRECTION */
	.sub-alt .sub-menu { right: inherit; }
	.sub-alt .menu-item-has-children { flex-direction: inherit; }
	.sub-alt .sub-menu .sub-menu { left: <?php echo $submenu_width; ?>px; }
	.sub-alt .sub-menu .menu-item-has-children a { order: inherit; }
	.sub-alt .sub-menu .trigger-icon:after { content: '\e80f'; }
}

@media all and (max-width: 900px) {
	/* MENU ITEM */
	.menu-item { flex-flow: wrap; }
	.menu-item:not(.toggle-menu-item):hover > a,
	.menu-item:not(.toggle-menu-item):hover > .toggle { background-color: rgba(0, 0, 0, 0.1); }
	.menu-item:not(:last-child) { border-bottom: 1px solid <?php echo $colors['header']['border_color']; ?>; }
	.menu .button, .menu .button:hover {
		padding: <?php echo $half; ?>px;
		width: 100%;
	}
	/* TOGGLE */
	.menu .toggle {
		border: 1px solid <?php echo $colors['header']['border_color']; ?>;
		border-width: 0 0 0 1px;
	}
	.toggle-menu-item > .trigger .trigger-icon:after { content: '\e817'; }
	/* SUB MENU */
	.sub-menu { flex-basis: 100%; }
	.toggle-menu-item > .sub-menu {
		background-color: rgba(0, 0, 0, 0.1);
		height: auto;
		opacity: 1;
		transition: opacity 200ms linear, transform 200ms ease-out;
		transform: translateY(0);
		visibility: visible;
	}
	.toggle-menu-item > .sub-menu .sub-menu { background-color: rgba(0, 0, 0, 0.05); }
	.sub-menu .menu-item a {
		font-size: <?php echo $typography['body']['font_size']['mobile'] - 1; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile'] - 2; ?>px;
		padding-block: <?php echo $third; ?>px;
	}
}