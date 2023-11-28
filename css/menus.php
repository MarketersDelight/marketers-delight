<style type="text/css">

/*------------------------------*\
	$MENUS
\*------------------------------*/

.menu, .menu ul { list-style: none; }

/* MENU ITEM */

.menu-item { position: relative; }

.menu-item a {
	padding: <?php echo $half; ?>px;
	position: relative;
}

/* TOGGLE */

.menu-toggle {
	cursor: pointer;
	font-size: <?php echo round( $header['font_size']['desktop'] * 1.5 ); ?>px;
}

.menu-toggle:before, .menu-toggle:after {
	font-family: md-icon;
	line-height: 1;
}

.menu-toggle:after { content: '\e80e'; }

/* SUB MENU */

.sub-menu {
	font-size: <?php echo $typography['body']['font_size']['tablet']; ?>px;
	display: none;
	line-height: <?php echo $typography['body']['line_height']['tablet']; ?>px;
	z-index: 50;
}

@media all and (min-width: 800px) {
	.menu { display: flex; }
	/* SUB MENU */
	.sub-menu {
		background-color: <?php echo $header['submenu']['bg_color']; ?>;
		border-radius: 5px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		position: absolute;
			right: -<?php echo $single; ?>px;
		width: <?php echo $submenu_width; ?>px;
	}
	.menu-item-has-children:hover > .sub-menu { display: block; }
	.sub-menu .sub-menu {
		right: <?php echo $submenu_width; ?>px;
		top: 0;
	}
    .sub-menu .menu-item a {
		color: <?php echo $header['submenu']['links']; ?>;
		display: block;
	}
	<?php if ( ! empty( $header['submenu']['hover'] ) ) : ?>
	.sub-menu .menu-item a:hover { color: <?php echo $header['submenu']['hover']; ?>; }
	<?php endif; ?>
	.sub-menu .menu-item:not(:last-child) a { border-bottom: 1px solid <?php echo $header['border_color']; ?>; }
	.menu-item-has-children { margin-right: <?php echo $half; ?>px; }
	.menu-item-has-children a { padding-right: <?php echo $third; ?>px; }
}

@media all and (max-width: 800px) {
	/* MENU ITEM */
	.menu-item {
		align-items: center;
		display: flex;
		flex-flow: wrap;
	}
	.menu-item:not(:last-child) { border-bottom: 1px solid <?php echo $header['border_color']; ?>; }
	.menu-item a { width: 100%; }
	.toggle-menu > .sub-menu { display: block; }
	/* TOGGLE */
	.menu-toggle {
		padding: <?php echo $half; ?>px;
		text-align: center;
	}
	/* SUB MENU */
	.sub-menu { flex-basis: 100%; }
	.menu-item-has-children > a { flex-basis: 90%; }
	.menu-item-has-children > .menu-toggle { flex-basis: 10%; }
}
