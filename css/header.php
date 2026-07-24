<style type="text/css">

/*------------------------------*\
	$HEADER
\*------------------------------*/

.header {
	background-color: var(--md-header);
	color: var(--md-header-color);
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

.header.sticky {
	position: sticky;
		inset-block-start: 0;
		inset-inline: 0;
	z-index: 80;
}

.header a { text-decoration: none; }

<?php if ( ! empty ( $colors['header']['bg_color'] ) ) : ?>
.is-box-style .header.cover { background-color: transparent; }
<?php endif; ?>

.full-cover .header {
	background-color: transparent;
	position: absolute;
		inset-inline-start: 0;
		inset-block-start: var(--md-half);
	width: 100%;
	z-index: 10;
}

.admin-bar.full-cover .header { inset-block-start: var(--wp-admin--admin-bar--height); }

.header .inner {
	align-items: center;
	display: flex;
	justify-content: space-between;
}

.header-controls {
	align-items: center;
	display: flex;
	gap: var(--md-half);
	padding-block: var(--md-third);
}

.header-triggers {
	align-items: center;
	display: flex;
	gap: var(--md-half);
}

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
	gap: var(--md-small) var(--md-half);
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
	gap: var(--md-third);
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

@media (min-width: 900px) {
	.header-primary, .header-aside {
		align-items: center;
		display: flex;
		gap: var(--md-half);
	}
	.header-triggers, .header-controls .trigger { display: none; }
	/* LAYOUTS */
	.right .header-primary { order: 2; }
	.right .header-controls { order: 3; }
	.right .search-form, .right .input-field { flex-direction: row-reverse; }
	.right .form-icons .input { padding-inline: var(--md-half) 0; }
	.center .header-primary { order: -1; }
	.center .header-aside {
		justify-content: end;
		order: 3;
	}
	.center .site-title { justify-content: center; }
	.header.simple .header-primary, .header.simple .header-aside { padding-block: var(--md-third); }
	/* STATES */
	.header.toggle-search .inner { column-gap: var(--md-half); }
	.toggle-search.from-aside .header-primary, .toggle-search.from-primary .header-aside,
	.toggle-search .header-menu, .toggle-search .link { display: none; }
	.toggle-search .search-form,
	.toggle-search.from-primary .header-primary, .toggle-search.from-aside .header-aside { flex: 1; }
}

@media (max-width: 900px) {
	.full-cover .header[class*="toggle-"] { position: static; }
	.header .inner {
		flex-direction: column;
		padding-inline: 0;
	}
	.header-controls {
		padding-inline: var(--md-half);
		width: 100%;
	}
	.header-primary, .header-aside { width: 100%; }
	.header-triggers { flex: 1; }
	.header .primary-menu, .header .aside-menu,
	.header-primary .link, .header-aside .link { display: none; }
	.toggle-menu .primary-menu, .toggle-menu .aside-menu { display: block; }
	/* SEARCH */
	.header .search-form {
		display: none;
		padding: var(--md-half);
	}
	.toggle-search .search-form { display: flex; }
	/* LAYOUTS */
	.left .header-triggers, .center .header-triggers { justify-content: end; }
	.right .header-triggers { order: -1; }
	/* COVER */
	.full-cover .header[class*="toggle-"] :is(.site-name a, .tagline, .trigger, .menu > .menu-item > a) { color: inherit; }
}

@media (min-width: 600px) {
		.admin-bar .header.sticky { inset-block-start: var(--wp-admin--admin-bar--height); }
}

@media (max-width: 600px) {
	<?php echo md_setting( array( 'header', 'display', 'hide_title_mobile' ) ) ? '.site-name span { display: none; }' : ''; ?>
	<?php echo md_setting( array( 'header', 'display', 'hide_tagline_mobile' ) ) ? '.tagline { display: none; }' : ''; ?>
	<?php echo ! empty( $logo['logo_width']['mobile'] ) ? '.logo { flex-basis: ' . $logo['logo_width']['mobile'] . 'px; max-width: ' . $logo['logo_width']['mobile'] . 'px; }' : ''; ?>
}
