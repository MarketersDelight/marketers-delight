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
	<?php if ( ! empty( $header['font_weight'] ) ) : ?>
	font-weight: <?php echo $header['font_weight']; ?>;
	<?php endif; ?>
	position: relative;
}

.header-wrap {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	position: relative;
}

.header-wrap a { color: <?php echo $header['menu']['links']; ?>; }

.header-wrap a:hover,
.header-wrap a:hover + .toggle { color: <?php echo $header['menu']['hover']; ?>; }

.header-simple .header-wrap { text-align: center; }

.header-link, .header_aside-link { padding: <?php echo $half; ?>px <?php echo $third; ?>px; }

/* LOGO */

.logo {
	<?php if ( ! empty( $logo['logo_width']['desktop'] ) ) : ?>
	flex-basis: <?php echo $logo['logo_width']['desktop']; ?>px;
	<?php endif; ?>
	flex-shrink: 0;
	position: relative;
}

.header-rtl .logo {
	order: 2;
	margin-right: 0;
	margin-left: <?php echo $half; ?>px;
}

.logo img { width: 100%; }

/* SITE TITLE + TAGLINE */

.site-title {
	align-items: center;
	display: flex;
	gap: <?php echo $small; ?>px <?php echo $third; ?>px;
}

<?php if ( md_setting( array( 'logo', 'display', 'stack_logo' ) ) ) : ?>
.site-title.stack { flex-flow: wrap; }
<?php endif; ?>

.header-rtl .site-title { justify-content: end; }

.site-name {
	<?php if ( ! empty( $logo['site_title']['font_family'] ) ) : ?>
	font-family: <?php echo $logo['site_title']['font_family']; ?>;
	<?php endif; ?>
	font-size: <?php echo $logo['site_title']['font_size']['desktop']; ?>px;
	<?php if ( ! empty( $logo['site_title']['font_weight'] ) ) : ?>
	font-weight: <?php echo $logo['site_title']['font_weight']; ?>;
	<?php endif; ?>
	line-height: <?php echo $logo['site_title']['line_height']['desktop']; ?>px;
}

.site-name, .site-name a, .site-name a:hover { color: <?php echo $logo['site_title']['color']; ?>; }

<?php if ( md_setting( array( 'logo', 'display', 'align_title_tagline' ) ) ) : ?>
.site-details.align {
	align-items: center;
	display: flex;
	flex-flow: wrap;
	gap: 0 <?php echo $third; ?>px;
}
<?php endif; ?>

.header .site-details { width: 100%; }

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

/* MENU */

.header .current-menu-item > a, .header .current-menu-item > .toggle { color: <?php echo $header['menu']['active']; ?>; }

/* QUERIES */

@media all and (min-width: 800px) {
	.header {
		font-size: <?php echo $header['font_size']['desktop']; ?>px;
		line-height: <?php echo $header['line_height']['desktop']; ?>px;
	}
	/* LAYOUT */
	.header-wrap,
	.header-primary,
	.header-aside {
		align-items: center;
		display: flex;
		gap: <?php echo $half; ?>px;
		justify-content: space-between;
	}
	.header-simple .header-wrap {
		justify-content: center;
		gap: <?php echo $half; ?>px;
	}
	/* FLYER */
	.header-flyer .header-primary { order: -1; }
	/* RTL */
	.header-rtl .header-controls { order: 3; }
	/* SEARCH */
	.header-triggers, .header-controls .trigger, .trigger-search,
	.has-search .header-link, .has-search .header_aside-link, .has-search .header-menu, .has-search .header_aside-menu { display: none; }
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
	.header .trigger-icon, .header .hide-label .link-icon, .header .hide-label-mobile .link-icon { font-size: <?php echo round( $header['font_size']['desktop'] * 1.5 ); ?>px; }
	/* DISPLAY */
	.header-menu, .header_aside-menu,
	.header .header-link,
	.header .header_aside-link,
	.header .search-form,
	.hide-label-mobile .link-name,
	.hide-label-mobile .link-subtitle,
	.hide-label-mobile .trigger-text,
	.header.has-search .header-triggers .trigger-search { display: none; }
	.has-mobile-menu .header-menu,
	.has-mobile-menu .header_aside-menu,
	.header-controls .header-link,
	.header-controls .header_aside-link { display: block; }
	.has-search .search-form { display: flex; }
	/* LAYOUT */
	.header-simple .site-title { justify-content: center; }
	.header-controls, .header-triggers {
		align-items: center;
		display: flex;
		gap: <?php echo $half; ?>px;
	}
	.header-controls {
		flex: 1;
		justify-content: space-between;
	}
	/* LOGO */
	.site-title { flex: 1; }
	<?php if ( ! empty( $logo['logo_width']['mobile'] ) ) : ?>
	.logo { flex-basis: <?php echo $logo['logo_width']['mobile']; ?>px; }
	<?php endif; ?>
	<?php if ( md_setting( array( 'logo', 'display', 'hide_title_mobile' ) ) ) : ?>
	.site-name { display: none; }
	<?php endif; ?>
	<?php if ( ! empty( $logo['site_title']['font_size']['tablet'] ) ) : ?>
	.site-name { font-size: <?php echo $logo['site_title']['font_size']['tablet']; ?>px; }
	<?php endif; ?>
	<?php if ( ! empty( $logo['site_title']['line_height']['tablet'] ) ) : ?>
	.site-name { line-height: <?php echo $logo['site_title']['line_height']['tablet']; ?>px; }
	<?php endif; ?>
	<?php if ( md_setting( array( 'logo', 'display', 'hide_tagline_mobile' ) ) ) : ?>
	.tagline { display: none; }
	<?php endif; ?>
	/* RTL */
	.header-rtl .site-title { order: 2; }
	.header-rtl .header-triggers {
		flex-direction: row-reverse;
		order: 1;
	}
	/* MENU */
	.header-primary .menu, .header-aside .menu {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

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

@media all and (max-width: <?php echo $site_width; ?>px) {
	.header .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

<?php if ( ! empty( $logo['site_title']['font_size']['mobile'] ) || ! empty( $logo['site_title']['line_height']['mobile'] ) ) : ?>
@media all and (max-width: 700px) {
	<?php if ( ! empty( $logo['site_title']['font_size']['mobile'] ) ) : ?>
	.site-name { font-size: <?php echo $logo['site_title']['font_size']['mobile']; ?>px; }
	<?php endif; ?>
	<?php if ( ! empty( $logo['site_title']['line_height']['mobile'] ) ) : ?>
	.site-name { line-height: <?php echo $logo['site_title']['line_height']['mobile']; ?>px; }
	<?php endif; ?>
}
<?php endif; ?>
