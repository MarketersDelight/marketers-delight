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

.menu-item a, .menu .sub-menu .trigger { color: <?php echo $colors['menu']['links']; ?>; }

.menu-item-has-children > a { flex: 1; }

.menu-item-has-children > .trigger { padding-inline: <?php echo $half; ?>px; }

.menu .trigger-icon:after { content: '\e80e'; }

.menu-item-title { display: block; }

.menu-item-desc { font-size: 0.85em; }

.menu-item.current-menu-item > a, .current-menu-item > .toggle { color: <?php echo $colors['menu']['active']; ?>; }

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
	.menu-item:hover, .menu-item:hover > a { color: <?php echo $colors['menu']['hover']; ?>; }
	.menu-item-has-children a { padding-inline-end: 0; }
	/* SUB MENU */
	.sub-menu {
		background-color: <?php echo $colors['submenu']['bg_color']; ?>;
		border-radius: 8px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
		position: absolute;
			inset-inline-end: 0;
		width: <?php echo $submenu_width; ?>px;
	}
	.sub-menu .menu-item-has-children { flex-direction: row-reverse; }
	.menu-item-has-children:hover > .sub-menu {
		height: auto;
		opacity: 1;
		transition: opacity 200ms linear,transform 200ms ease-out;
		transform: translateY(0);
		inset-block-start: <?php echo $mid + $third; ?>px;
		visibility: visible;
	}
	.menu-item .sub-menu .sub-menu {
		inset-block-start: 0;
		inset-inline-start: -<?php echo $submenu_width; ?>px;
	}
	.sub-menu .menu-item { align-items: end; }
	.sub-menu .menu-item:hover {
		background-color: rgba(0, 0, 0, 0.08);
		<?php if ( ! empty( $colors['submenu']['hover'] ) ) : ?>
		color: <?php echo $colors['submenu']['hover']; ?>;
		<?php endif; ?>
	}
	.sub-menu .menu-item:first-child:hover { border-radius: 8px 8px 0 0; }
	.sub-menu .menu-item:last-child:hover { border-radius: 0 0 8px 8px; }
	.sub-menu .menu-item:not(:last-child) { border-block-end: 1px solid <?php echo $colors['header']['border_color']; ?>; }
	.sub-menu .trigger { padding: <?php echo $half; ?>px; }
	.sub-menu .trigger-icon:after { content: '\e816'; }
	/* SUB MENU ALT DIRECTION */
	.sub-alt .sub-menu { inset-inline-end: inherit; }
	.sub-alt .menu-item-has-children { flex-direction: inherit; }
	.sub-alt .sub-menu .sub-menu { inset-inline-start: <?php echo $submenu_width; ?>px; }
	.sub-alt .sub-menu .menu-item-has-children a { order: inherit; }
	.sub-alt .sub-menu .trigger-icon:after { content: '\e80f'; }
}

@media all and (max-width: 900px) {
	/* MENU ITEM */
	.menu-item { flex-flow: wrap; }
	.menu-item:not(.toggle-menu-item):hover > :is(a, .toggle) { background-color: rgba(0, 0, 0, 0.08); }
    .header.center.toggle-menu .header-primary:not(:last-child),
    .menu-item:not(:last-child) { border-block-end: 1px solid <?php echo $colors['header']['border_color']; ?>; }
	/* TOGGLE */
	.menu .toggle {
		border: 1px solid <?php echo $colors['header']['border_color']; ?>;
		border-width: 0 0 0 1px;
	}
	.toggle-menu-item > .trigger .trigger-icon:after { content: '\e817'; }
	/* SUB MENU */
	.sub-menu { flex-basis: 100%; }
	.toggle-menu-item > .sub-menu {
		background-color: rgba(0, 0, 0, 0.08);
		height: auto;
		opacity: 1;
		transition: opacity 200ms linear, transform 200ms ease-out;
		transform: translateY(0);
		visibility: visible;
	}
	.sub-menu .menu-item a {
		font-size: <?php echo $typography['body']['font_size']['mobile'] - 1; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile'] - 2; ?>px;
		padding-block: <?php echo $third; ?>px;
	}
}