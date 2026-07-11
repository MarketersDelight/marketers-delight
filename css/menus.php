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
	padding: var(--md-half);
	position: relative;
	width: 100%;
}

.menu-item a, .menu .sub-menu .trigger { color: var(--md-menu-links); }

.menu-item-has-children > a { flex: 1; }

.menu-item-has-children > .trigger { padding-inline: var(--md-half); }

.menu .trigger-icon:after { content: '\e80e'; }

.menu-item-title { display: block; }

.menu-item-desc { font-size: 0.85em; }

.menu-item.current-menu-item > a, .current-menu-item > .toggle { color: var(--md-menu-active); }

/* SUB MENU */

.sub-menu {
	height: 0;
	opacity: 0;
	transform: translateY(-10px);
	visibility: hidden;
	z-index: 50;
}

.sub-menu .menu-item a {
	color: var(--md-submenu-links);
	display: block;
}

@media all and (min-width: 900px) {
	.menu {
		align-items: center;
		display: flex;
	}
	.menu-item:hover, .menu-item:hover > a, .menu-item:hover > .toggle { color: var(--md-menu-hover); }
	.menu-item-has-children a { padding-inline-end: 0; }
	/* SUB MENU */
	.sub-menu {
		background-color: var(--md-submenu);
		border-radius: var(--md-border-radius);
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		font-size: var(--md-font-size-sm);
		line-height: var(--md-line-height-sm);
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
		inset-block-start: calc(var(--md-mid) + var(--md-third));
		visibility: visible;
	}
	.menu-item .sub-menu .sub-menu {
		inset-block-start: 0;
		inset-inline-start: -<?php echo $submenu_width; ?>px;
	}
	.sub-menu .menu-item { align-items: end; }
	.sub-menu .menu-item:hover {
		background-color: rgba(0, 0, 0, 0.08);
		color: var(--md-submenu-hover);
	}
	.sub-menu .menu-item:first-child:hover { border-radius: var(--md-border-radius) var(--md-border-radius) 0 0; }
	.sub-menu .menu-item:last-child:hover { border-radius: 0 0 var(--md-border-radius) var(--md-border-radius); }
	.sub-menu .menu-item:not(:last-child) { border-block-end: 1px solid var(--md-header-border); }
	.sub-menu .trigger { padding: var(--md-half); }
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
    .menu-item:not(:last-child) { border-block-end: 1px solid var(--md-header-border); }
	/* TOGGLE */
	.menu .toggle {
		border: 1px solid var(--md-header-border);
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
		font-size: calc(var(--md-font-size-sm) - 1px);
		line-height: calc(var(--md-line-height-sm) - 2px);
		padding-block: var(--md-third);
	}
}
