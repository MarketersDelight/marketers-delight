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

.header a { color: <?php echo $header['menu']['links']; ?>; }

.header a:hover { color: <?php echo $header['menu']['hover']; ?>; }

/* SITE TITLE + TAGLINE */

.site-title {
	align-items: center;
	display: flex;
}

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
	font-size: <?php echo $logo['site_tagline']['font_size']['desktop']; ?>px;
	<?php if ( ! empty( $logo['site_tagline']['font_weight'] ) ) : ?>
	font-weight: <?php echo $logo['site_tagline']['font_weight']; ?>;
	<?php endif; ?>
	line-height: <?php echo $logo['site_tagline']['line_height']['desktop'] ; ?>px;
}

.tagline a { color: <?php echo $logo['site_tagline']['color']; ?>; }

/* LOGO */

.logo {
	<?php if ( ! empty( $logo['logo_width']['desktop'] ) ) : ?>
	flex-basis: <?php echo $logo['logo_width']['desktop']; ?>px;
	<?php endif; ?>
	margin-right: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	position: relative;
}

.logo img { width: 100%; }

/* MENU */

.header .current-menu-item > a, .header .current-menu-item > .menu-toggle { color: <?php echo $header['menu']['active']; ?>; }

.header-link, .header_aside-link { margin-left: <?php echo $half; ?>px; }

/* SEARCH */

.form-toggle .trigger-search { display: block; }

/* QUERIES */

@media all and (min-width: 800px) {
	/* LAYOUT */
	.header-wrap, .header-primary, .header-aside {
		align-items: center;
		display: flex;
		justify-content: space-between;
	}
	/* SEARCH */
	.header-triggers, .trigger-search,
	.has-search .header-link, .has-search .header_aside-link, .has-search .header-menu { display: none; }
	.header-search { padding: <?php echo $half; ?>px; }
	.has-search .header-primary { flex: 1; }
	.has-search .header-search { width: 100%; }
}

@media all and (max-width: 800px) {
	/* DISPLAY */
	.header-menu, .header_aside-menu,
	.header-link, .header_aside-link, .header .search-form,
	.hide-label-mobile .link-text { display: none; }
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
	.header-triggers .trigger { margin-left: <?php echo $half; ?>px; }
	.header .trigger-icon { font-size: <?php echo round( $header['font_size']['desktop'] * 1.5 ); ?>px; }
	/* MENU */
	.header .menu {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
	/* SEARCH */
	.header-search { padding-bottom: <?php echo $half; ?>px; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.header .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}
