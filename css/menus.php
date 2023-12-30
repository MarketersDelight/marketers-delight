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
	width: 100%;
}

.menu .trigger-icon:after { content: '\e80e'; }

.menu .button, .menu .button:hover {
	background-color: transparent;
	box-shadow: none;
	padding: 0;
}

/* SUB MENU */

.menu-item-has-children > a { flex-basis: 90%; }

.menu-item-has-children > .trigger { flex-basis: 10%; }

.sub-menu {
	display: none;
	z-index: 50;
}

.sub-menu .menu-item {
	align-items: center;
	display: flex;
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
	.menu > .menu-item-has-children {
		flex: 1 0 auto;
		margin-right: <?php echo $half; ?>px;
	}
	.menu-item-has-children a { padding-right: <?php echo $third; ?>px; }
	.menu-item-has-children .menu-toggle { display: inline-block; }
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
	.menu-item-has-children:hover > .sub-menu { display: block; }
	.sub-menu .menu-item-has-children a { order: 2; }
	.sub-menu .sub-menu {
		top: 0;
		right: <?php echo $submenu_width; ?>px;
	}
	<?php if ( ! empty( $header['submenu']['hover'] ) ) : ?>
	.sub-menu .menu-item a:hover { color: <?php echo $header['submenu']['hover']; ?>; }
	<?php endif; ?>
	.sub-menu .menu-item:not(:last-child) a,
	.sub-menu .menu-item:not(:last-child) .trigger { border-bottom: 1px solid <?php echo $header['border_color']; ?>; }
	.sub-menu .trigger { padding: <?php echo $half; ?>px; }
	.sub-menu .trigger-icon { color: <?php echo $header['submenu']['links']; ?>; }
	.sub-menu .trigger-icon:after { content: '\e816'; }
}

@media all and (max-width: 800px) {
	/* MENU ITEM */
	.menu-item {
		align-items: center;
		display: flex;
		flex-flow: wrap;
	}
	.menu-item:not(:last-child) { border-bottom: 1px solid <?php echo $header['border_color']; ?>; }
	.menu .button, .menu .button:hover {
		padding: <?php echo $half; ?>px;
		width: 100%;
	}
	/* TOGGLE */
	.toggle-menu > .trigger .trigger-icon:after { content: '\e817'; }
	/* SUB MENU */
	.sub-menu { flex-basis: 100%; }
	.toggle-menu > .sub-menu { display: block; }
}
