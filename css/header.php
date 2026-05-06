<style type="text/css">

/*------------------------------*\
	$HEADER
\*------------------------------*/

.header {
	<?php if ( ! empty( $colors['header']['bg_color'] ) ) : ?>
	background-color: <?php echo $colors['header']['bg_color']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $colors['header']['color'] ) ) : ?>
	color: <?php echo $colors['header']['color']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $typography['header']['font_family'] ) ) : ?>
	font-family: <?php echo $typography['header']['font_family']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $typography['header']['font_size']['desktop'] ) ) : ?>
	font-size: <?php echo $typography['header']['font_size']['desktop']; ?>px;
	<?php endif; ?>
	<?php if ( ! empty( $typography['header']['font_weight'] ) ) : ?>
	font-weight: <?php echo $typography['header']['font_weight']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $typography['header']['line_height'] ) ) : ?>
	line-height: <?php echo $typography['header']['line_height']['desktop']; ?>px;
	<?php endif; ?>
}

.header a { text-decoration: none; }

.cover-text .header :is(.site-name a, .tagline, .trigger, .menu > .menu-item > a) { color: #fff; }

.cover-alt .header :is(.site-name a, .tagline, .trigger, .menu > .menu-item > a) { color: <?php echo $colors['site']['text']; ?>; }

<?php if ( ! empty ( $colors['header']['bg_color'] ) ) : ?>
.is-box-style .header.cover { background-color: transparent; }
<?php endif; ?>

.full-cover .header {
	background-color: transparent;
	position: absolute;
		inset-inline-start: 0;
		inset-block-start: <?php echo $half; ?>px;
	width: 100%;
	z-index: 10;
}

.header .inner {
	align-items: center;
	display: flex;
}

.header-controls { padding-block: <?php echo $third; ?>px; }

.header-triggers {
	align-items: center;
	display: flex;
	gap: <?php echo $half; ?>px;
}

.header .menu a, .header .menu .sub-menu .trigger { color: <?php echo $colors['menu']['links']; ?>; }

.header .current-menu-item > a, .header .current-menu-item > .toggle { color: <?php echo $colors['menu']['active']; ?>; }

/* LAYOUTS */

.header.simple :is(.inner, .header-primary, .header-aside, .header-controls, .header-triggers) { justify-content: center; }

.left .header-triggers { justify-content: space-between; }

.right .header-triggers {
	flex-direction: row-reverse;
	justify-content: start;
}

.right .logo { order: 2; }

.right .site-title {
	justify-content: end;
	text-align: right;
}

/* SITE TITLE + TAGLINE */

<?php echo ! empty( $logo['logo_width']['desktop'] ) ? '.logo { flex: 1 0 ' . $logo['logo_width']['desktop'] . 'px; max-width: ' . $logo['logo_width']['desktop'] . 'px; }' : ''; ?>

.logo a { display: block; }

.site-title {
	align-items: center;
	display: flex;
	<?php echo md_setting( array( 'header', 'display', 'align_logo' ) ) ? 'flex-direction: column; text-align: center;' : ''; ?>
	gap: <?php echo $small; ?>px <?php echo $half; ?>px;
	position: relative;
}

.site-title a:before {
	content: '';
	height: 100%;
	position: absolute;
		inset-inline-start: 0;
		inset-block-start: 0;
	width: 100%;
}

<?php if ( md_setting( array( 'header', 'display', 'align_tagline' ) ) ) : ?>
.site-details {
	align-items: center;
	display: flex;
	gap: <?php echo $third; ?>px;
}
<?php endif; ?>

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

/* QUERIES */

@media all and (min-width: 900px) {
	.header-primary, .header-aside {
		align-items: center;
		display: flex;
		flex: 1;
		gap: <?php echo $half; ?>px;
	}
	.header-triggers, .header-controls .trigger { display: none; }
	/* LAYOUTS */
	.left .header-primary, .left .header-aside { justify-content: end; }
	.right .header-primary { order: 2; }
	.right .header-controls { order: 3; }
	.right .search-form, .right .input-field { flex-direction: row-reverse; }
	.right .form-icons .input { padding-inline: <?php echo $half; ?>px 0; }
	.center .header-primary { order: -1; }
	.center .header-aside {
		justify-content: end;
		order: 3;
	}
	.center .site-title { justify-content: center; }
	.header.simple .header-primary, .header.simple .header-aside { padding-block: <?php echo $third; ?>px; }
	/* STATES */
	.header.show-search .inner { column-gap: <?php echo $half; ?>px; }
	.show-search.from-aside .header-primary,
	.show-search.from-primary .header-aside,
	.show-search .header-menu, .show-search .link { display: none; }
	.show-search .search-form { flex: 1; }
	.show-search .header-controls { flex: inherit; }
}

@media all and (max-width: 900px) {
	.full-cover .header[class*="show-"] { position: static; }
	.header .inner {
		flex-direction: column;
		padding-inline: 0;
	}
	.header-primary, .header-aside { width: 100%; }
	.header-controls {
		align-items: center;
		display: flex;
		gap: <?php echo $half; ?>px;
		padding-inline: <?php echo $half; ?>px;
		width: 100%;
	}
	.header-triggers { flex: 1; }
	.header .primary-menu, .header .aside-menu,
	.header-primary .link, .header-aside .link { display: none; }
	.show-menu .primary-menu, .show-menu .aside-menu { display: block; }
	<?php echo ! empty( $logo['logo_width']['tablet'] ) ? '.logo { flex-basis: ' . $logo['logo_width']['tablet'] . 'px; max-width: ' . $logo['logo_width']['tablet'] . 'px; }' : ''; ?>
	/* SEARCH */
	.header .search-form {
		display: none;
		padding: <?php echo $half; ?>px;
	}
	.show-search .search-form { display: flex; }
	/* LAYOUTS */
	.left .header-triggers, .center .header-triggers { justify-content: end; }
	.right .header-triggers { order: -1; }
	/* COVER */
	.full-cover .header[class*="show-"] :is(.site-name a, .tagline, .trigger, .menu > .menu-item > a) { color: inherit; }
}

@media all and (max-width: 600px) {
	<?php echo md_setting( array( 'header', 'display', 'hide_title_mobile' ) ) ? '.site-name span { display: none; }' : ''; ?>
	<?php echo md_setting( array( 'header', 'display', 'hide_tagline_mobile' ) ) ? '.tagline { display: none; }' : ''; ?>
	<?php echo ! empty( $logo['logo_width']['mobile'] ) ? '.logo { flex-basis: ' . $logo['logo_width']['mobile'] . 'px; max-width: ' . $logo['logo_width']['mobile'] . 'px; }' : ''; ?>
}

/* STICKY */

.admin-bar.full-cover .header, .admin-bar .stuck { inset-block-start: <?php echo $admin_bar_height; ?>px; }

@media all and (max-width: 782px) {
	.admin-bar.full-cover .header, .admin-bar .stuck { inset-block-start: <?php echo $admin_bar_height_mobile; ?>px; }
}