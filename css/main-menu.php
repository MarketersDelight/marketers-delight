<style type="text/css">

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
	inset: 0;
	opacity: 0;
	position: absolute;
	transition: opacity 0.15s ease-in-out;
	visibility: hidden;
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
		padding-left: 0;
		padding-right: 0;
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
