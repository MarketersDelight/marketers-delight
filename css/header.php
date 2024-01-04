<style type="text/css">

/*------------------------------*\
	$HEADER
\*------------------------------*/

.header {
	background-color: <?php echo $header['bg_color']; ?>;
	<?php if ( ! empty( $header['color'] ) ) : ?>
	color: <?php echo $header['color']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $header['font_family'] ) ) : ?>
	font-family: <?php echo $header['font_family']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $header['font_size']['desktop'] ) ) : ?>
	font-size: <?php echo $header['font_size']['desktop']; ?>px;
	<?php endif; ?>
	<?php if ( ! empty( $header['font_weight'] ) ) : ?>
	font-weight: <?php echo $header['font_weight']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $header['line_height']['desktop'] ) ) : ?>
	line-height: <?php echo $header['line_height']['desktop']; ?>px;
	<?php endif; ?>
	position: relative;
}

.header-wrap a { color: <?php echo $header['menu']['links']; ?>; }

.header-wrap a:hover { color: <?php echo $header['menu']['hover']; ?>; }

/* SITE TITLE + TAGLINE */

.site-title {
	align-items: center;
	display: flex;
}

.header-rtl .site-title { justify-content: end; }

.site-name {
	<?php if ( ! empty( $logo['site_title']['font_family'] ) ) : ?>
	font-family: <?php echo $logo['site_title']['font_family']; ?>;
	<?php endif; ?>
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	<?php if ( ! empty( $logo['site_title']['font_weight'] ) ) : ?>
	font-weight: <?php echo $logo['site_title']['font_weight']; ?>;
	<?php endif; ?>
	line-height: <?php echo $logo['site_title']['line_height']['desktop']; ?>px;
}

.site-name, .site-name a, .site-name a:hover { color: <?php echo $logo['site_title']['color']; ?>; }

.tagline {
	color: <?php echo $logo['site_tagline']['color']; ?>;
	<?php if ( ! empty( $logo['site_tagline']['font_family'] ) ) : ?>
	font-family: <?php echo $logo['site_tagline']['font_family']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $logo['site_tagline']['font_size']['desktop'] ) ) : ?>
	font-size: <?php echo $logo['site_tagline']['font_size']['desktop']; ?>px;
	<?php endif; ?>
	<?php if ( ! empty( $logo['site_tagline']['font_weight'] ) ) : ?>
	font-weight: <?php echo $logo['site_tagline']['font_weight']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $logo['site_tagline']['line_height']['desktop'] ) ) : ?>
	line-height: <?php echo $logo['site_tagline']['line_height']['desktop']; ?>px;
	<?php endif; ?>
}

.tagline a { color: <?php echo $logo['site_tagline']['color']; ?>; }

/* LOGO */

.logo {
	<?php if ( ! empty( $logo['logo_width']['desktop'] ) ) : ?>
	flex-basis: <?php echo $logo['logo_width']['desktop']; ?>px;
	<?php endif; ?>
	flex-shrink: 0;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	position: relative;
}

.header-rtl .logo {
	order: 2;
	margin-right: 0;
	margin-left: <?php echo $half; ?>px;
}

.logo img { width: 100%; }

.logo + .site-details { margin-left: <?php echo $half; ?>px; }

/* MENU */

.header .current-menu-item > a, .header .current-menu-item > .menu-toggle { color: <?php echo $header['menu']['active']; ?>; }

/* QUERIES */

@media all and (max-width: 900px) {
	<?php if ( ! empty( $header['font_size']['tablet'] ) || ! empty( $header['line_height']['tablet'] ) ) : ?>
	.header {
		<?php echo ! empty( $header['font_size']['tablet'] ) ? 'font-size: ' . $header['font_size']['tablet'] . 'px;' : ''; ?>
		<?php echo ! empty( $header['line_height']['tablet'] ) ? 'line-height: ' . $header['line_height']['tablet'] . 'px;' : ''; ?>
	}
	<?php endif; ?>
	<?php if ( ! empty( $logo['logo_width']['tablet'] ) ) : ?>
	.logo { flex-basis: <?php echo $logo['logo_width']['tablet']; ?>px; }
	<?php endif; ?>
}

@media all and (min-width: 800px) {
	/* LAYOUT */
	.header-wrap, .header-primary, .header-aside {
		align-items: center;
		display: flex;
		justify-content: space-between;
	}
	<?php if ( md_setting( array( 'header', 'display', 'align_tagline' ) ) ) : ?>
	.header .site-details {
		align-items: center;
		display: flex;
	}
	.site-name + .tagline { margin-left: <?php echo $third; ?>px; }
	<?php endif; ?>
	/* FLYER */
	.header-flyer .header-controls { order: 2; }
	.header-flyer .header-aside { order: 3; }
	.header-flyer .site-title { justify-content: center; }
	/* RTL */
	.header-rtl .header-controls { order: 3; }
	/* SUB MENU */
	.header-flyer .header-primary .sub-menu, .header-rtl .sub-menu { right: inherit; }
	.header-flyer .header-primary .sub-menu .sub-menu, .header-rtl .sub-menu .sub-menu { right: -<?php echo $submenu_width; ?>px; }
	.header-flyer .header-primary .sub-menu .menu-item-has-children a, .header-rtl .sub-menu .menu-item-has-children a { order: inherit; }
	.header-flyer .header-primary .sub-menu .trigger-icon:after, .header-rtl .sub-menu .trigger-icon:after { content: '\e80f'; }
	/* SEARCH */
	.header-triggers, .header-controls .trigger, .trigger-search,
	.has-search .header-link, .has-search .header_aside-link, .has-search .header-menu, .has-search .header_aside-menu { display: none; }
	.header .search-form { padding: <?php echo $half; ?>px; }
	.has-search .header-primary, .has-search .header-aside { flex: 1; }
	.has-search .search-form { width: 100%; }
}

@media all and (max-width: 800px) {
	<?php if ( ! empty( $header['font_size']['mobile'] ) || ! empty( $header['line_height']['mobile'] ) ) : ?>
	.header {
		<?php echo ! empty( $header['font_size']['mobile'] ) ? 'font-size: ' . $header['font_size']['mobile'] . 'px;' : ''; ?>
		<?php echo ! empty( $header['line_height']['mobile'] ) ? 'line-height: ' . $header['line_height']['mobile'] . 'px;' : ''; ?>
	}
	<?php endif; ?>
	/* DISPLAY */
	.header-menu, .header_aside-menu,
	.header .header-link,
	.header .header_aside-link,
	.header .search-form,
	.hide-label-mobile .link-text,
	.hide-label-mobile .trigger-text { display: none; }
	.has-mobile-menu .header-menu, .has-mobile-menu .header_aside-menu,
	.header-controls .header-link, .header-controls .header_aside-link { display: block; }
	.has-search .search-form { display: flex; }
	/* LAYOUT */
	.header-controls, .header-triggers {
		align-items: center;
		display: flex;
	}
	.header-controls {
		flex: 1;
		justify-content: space-between;
	}
	.header-triggers .trigger { padding: <?php echo $half; ?>px <?php echo $third; ?>px; }
	.header-link, .header_aside-link { margin-left: <?php echo $third; ?>px; }
	.header-controls > .trigger-menu { margin-right: <?php echo $half; ?>px; }
	.header .trigger-icon { font-size: <?php echo round( $header['font_size']['desktop'] * 1.5 ); ?>px; }
	/* LOGO */
	.site-title { flex: 1; }
	<?php if ( ! empty( $logo['logo_width']['mobile'] ) ) : ?>
	.logo { flex-basis: <?php echo $logo['logo_width']['mobile']; ?>px; }
	<?php endif; ?>
	<?php if ( md_setting( array( 'header', 'display', 'hide_title_mobile' ) ) ) : ?>
	.site-name { display: none; }
	<?php endif; ?>
	<?php if ( md_setting( array( 'header', 'display', 'hide_tagline_mobile' ) ) ) : ?>
	.tagline { display: none; }
	<?php endif; ?>
	/* RTL */
	.header-rtl .site-title { order: 2; }
	.header-rtl .header-triggers {
		flex-direction: row-reverse;
		order: 1;
	}
	/* MENU */
	.header .menu {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
	/* SEARCH */
	.header .search-form { padding-bottom: <?php echo $half; ?>px; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.header .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}
