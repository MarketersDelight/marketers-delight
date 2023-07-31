<style type="text/css">

<?php
	$header_layout = md_setting( array( 'header', 'layout' ), 'standard' );
	$header_full_width = md_setting( array( 'header', 'display', 'full_width' ) );
?>

/*------------------------------*\
	$HEADER
\*------------------------------*/

.header {
	background-color: <?php echo $colors['header']['bg_color']; ?>;
	color: <?php echo $colors['header']['color']; ?>;
	<?php if ( ! empty( $typography['header']['font_family'] ) ) : ?>
		font-family: <?php echo $typography['header']['font_family']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $typography['header']['font_size']['desktop'] ) ) : ?>
		font-size: <?php echo $typography['header']['font_size']['desktop']; ?>px;
	<?php endif; ?>
	<?php if ( ! empty( $typography['header']['font_weight'] ) ) : ?>
		font-weight: <?php echo $typography['header']['font_weight']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $typography['header']['line_height']['desktop'] ) ) : ?>
		line-height: <?php echo $typography['header']['line_height']['desktop']; ?>px;
	<?php endif; ?>
	position: relative;
}

.header-wrap a { color: <?php echo $colors['header']['menu']['links']; ?>; }

.header-wrap a:hover { color: <?php echo $colors['header']['menu']['hover']; ?>; }

.header .button, .header .button:hover, .header .menu > .current-menu-item.button > a {
	color: #fff;
	line-height: 1;
}

.header-simple { text-align: center; }

/* LOGO */

.header-logo {
	padding: <?php echo $half; ?>px;
	position: relative;
}

.logo {
	flex: 1 0 auto;
	position: relative;
	width: <?php echo $logo_width; ?>px;
}

.logo .custom-logo-link { width: 100%; }

/* SITE TITLE + TAGLINE */

.logo + .header-details { margin-left: <?php echo $third; ?>px; }

.site-title {
	<?php echo ( ! empty( $typography['site_title']['font_family'] ) ? "\tfont-family: " . $typography['site_title']['font_family'] . ";\n" : '' ); ?>
	<?php echo ( ! empty( $typography['site_title']['font_style'] ) ? "\tfont-style: italic;\n" : '' ); ?>
	<?php echo ( ! empty( $typography['site_title']['font_weight'] ) ? "\tfont-weight: " . $typography['site_title']['font_weight'] . ";\n" : '' ); ?>
}

<?php if ( ! empty( $colors['header']['site_title'] ) ) : ?>
	.header .site-title, .header .site-title:hover { color: <?php echo $colors['header']['site_title']; ?>; }
<?php endif; ?>

.tagline {
	color: <?php echo $colors['header']['site_tagline']; ?>;
	<?php if ( ! empty( $typography['site_tagline']['font_family'] ) ) : ?>
		font-family: <?php echo $typography['site_tagline']['font_family']; ?>;
	<?php endif; ?>
	font-size: <?php echo $typography['site_tagline']['font_size']['desktop']; ?>px;
	<?php if ( ! empty( $typography['site_tagline']['font_style'] ) ) : ?>
		font-style: italic;
	<?php endif; ?>
	<?php if ( ! empty( $typography['site_tagline']['font_weight'] ) ) : ?>
		font-weight: <?php echo $typography['site_tagline']['font_weight']; ?>;
	<?php endif; ?>
	line-height: <?php echo $typography['site_tagline']['line_height']['desktop'] ; ?>px;
}

.tagline a { color: <?php echo $colors['header']['site_tagline']; ?>; }

<?php if ( md_setting( array( 'header', 'display', 'tagline_same_line' ) ) ) : ?>
.site-title + .tagline { margin-left: <?php echo $third; ?>px; }
<?php endif; ?>

/* TRIGGERS */

.header-triggers, .header-controls {
	align-items: center;
	display: flex;
}

.header-triggers {
	flex: 1;
	justify-content: end;
	padding: <?php echo $half; ?>px;
}

/* QUERIES */

@media all and (min-width: 800px) {
	/* LAYOUT */
	.header-wrap {
		align-items: center;
		display: flex;
	}
	.header-triggers, .header-controls .trigger, .trigger-search { display: none; }
	.form-toggle .trigger-search { display: block; }
	/* LOGO */
	.site-title {
		font-size: <?php echo $typography['site_title']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['site_title']['line_height']['desktop'] . 'px'; ?>;
	}
	.tagline { max-width: <?php echo $quad * 3; ?>px; }
	/* MENU */
	.header-menu, .header_aside-menu {
		display: flex;
		flex: 1 0 auto;
	}
	.header-standard .header-menu, .header-standard .header_aside-menu { justify-content: end; }
	.header-standard .header-controls { flex: 1 0 auto; }
	/* SEARCH */
	.header-search { padding: <?php echo $half; ?>px; }
	.header.has-search .header-aside, .header.has-search .header-search { flex: 1 0 auto; }
	.header.has-search .header-menu, .header.has-search .header_aside-menu,
	.header.has-search .header-link, .header.has-search .header_aside-link { display: none; }
	.header .search-form .form-submit { min-width: 60px; width: auto; }
	/* LINK */
	.header-link, .header_aside-link { padding: <?php echo $half; ?>px <?php echo $third; ?>px; }
	.header .button .link-icon {
		font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.1 ); ?>px;
		margin-right: <?php echo $small; ?>px;
	}
	/* HEADER FLYER */
	.header-flyer .header-controls {
		flex: 1 0 auto;
		order: 2;
	}
	.header-flyer .header-primary, .header-flyer .header-aside {
		align-items: center;
		display: flex;
		flex: 1 0 auto;
	}
	.header-flyer .header-aside {
		align-items: center;
		flex: 0 1 auto;
		justify-content: end;
		order: 3;
	}
	.header-flyer .header-menu  { flex: 1 0 auto; }
	.header-flyer.has-search .header-controls { order: inherit; }
	.header-flyer.has-search .search-form { flex: 1; }
	/* HEADER RTL */
	.header-rtl .header-controls { order: 2; }
	/* REVERSE SUB MENU */
	.header-rtl .sub-menu,
	.header-flyer .header-primary .sub-menu { left: 0; }
	.header-rtl .sub-menu .sub-menu,
	.header-flyer .header-primary .sub-menu .sub-menu { left: <?php echo $submenu_width; ?>px; }
	.header-rtl .menu-toggle,
	.header-flyer .header-primary .menu-toggle {
		left: inherit;
		right: <?php echo $half; ?>px;
	}
	.header-rtl .menu .sub-menu > .menu-item-has-children > a,
	.header-flyer .header-primary .menu .sub-menu > .menu-item-has-children > a {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	.header-rtl .sub-menu .menu-toggle:after,
	.header-flyer .header-primary .sub-menu .menu-toggle:after { content: '\e80f'; }
}

@media all and (max-width: 800px) {
	.header {
		<?php if ( ! empty( $typography['header']['font_size']['tablet'] ) ) : ?>
			font-size: <?php echo $typography['header']['font_size']['tablet']; ?>px;
		<?php endif; ?>
		<?php if ( ! empty( $typography['header']['line_height']['tablet'] ) ) : ?>
			line-height: <?php echo $typography['header']['line_height']['tablet']; ?>px;
		<?php endif; ?>
	}
	.logo { width: <?php echo ! empty( $colors['logo_width']['tablet'] ) ? $colors['logo_width']['tablet'] : 50; ?>px; }
	<?php if ( md_setting( array( 'header', 'display', 'hide_title_mobile' ) ) ) : ?>
	.site-title { display: none; }
	<?php endif; ?>
	<?php if ( md_setting( array( 'header', 'display', 'hide_tagline_mobile' ) ) ) : ?>
	.tagline { display: none; }
	<?php endif; ?>
	.header-controls .trigger, 	.header-link, .header_aside-link { padding-left: <?php echo $half; ?>px; }
	/* MENU */
	.header-menu, .header_aside-menu,
	.header-link, .header_aside-link,
	.header .search-form { display: none; }
	.has-mobile-menu .header-menu,
	.has-mobile-menu .header_aside-menu,
	.header-controls .header-link, .header-controls .header_aside-link { display: block; }
	.header .menu a:hover { color: <?php echo $colors['header']['submenu']['hover']; ?>; }
	.header-primary + .header-aside { border-top: 1px solid <?php echo $colors['header']['border_color']; ?>; }
	.header .menu > .menu-item:not(:last-child) { border-bottom: 1px solid <?php echo $colors['header']['border_color']; ?>; }
	.header-controls .hide-label .trigger-icon { font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.7 ); ?>px; }
	/* SEARCH */
	.header-search { padding: <?php echo $half; ?>px; }
	.has-search .search-form {
		display: flex;
		flex-basis: 100%;
	}
	.header .search-input { width: 100%; }
}

@media all and (max-width: 900px) {
	.site-title {
		font-size: <?php echo $typography['site_title']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['site_title']['line_height']['tablet']; ?>px;
	}
	.tagline {
		font-size: <?php echo $typography['site_tagline']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['site_tagline']['line_height']['tablet']; ?>px;
	}
}

@media all and (max-width: 700px) {
	.header {
		<?php if ( ! empty( $typography['header']['font_size']['mobile'] ) ) : ?>
			font-size: <?php echo $typography['header']['font_size']['mobile']; ?>px;
		<?php endif; ?>
		<?php if ( ! empty( $typography['header']['line_height']['mobile'] ) ) : ?>
			line-height: <?php echo $typography['header']['line_height']['mobile']; ?>px;
		<?php endif; ?>
	}
	.site-title {
		font-size: <?php echo $typography['site_title']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['site_title']['line_height']['mobile']; ?>px;
	}
	.tagline {
		font-size: <?php echo $typography['site_tagline']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['site_tagline']['line_height']['mobile']; ?>px;
	}
	<?php if ( ! empty( $colors['logo_width']['mobile'] ) ) : ?>
		.logo { width: <?php echo $colors['logo_width']['mobile']; ?>px; }
	<?php endif; ?>
}

<?php if ( $header_full_width ) : ?>
.header.has-search .menu-secondary { display: none; }
@media all and (max-width: <?php echo $site_width_full; ?>px) {
	.header .inner, .main-menu .inner {
		padding-left: <?php echo $single; ?>px;
		padding-right: <?php echo $single; ?>px;
	}
}
@media all and (max-width: <?php echo $site_width_full; ?>px) {
	.header .inner, .main-menu .inner { max-width: 100%; }
}
@media all and (max-width: <?php echo $site_width; ?>px) {
	.header .inner, .main-menu .inner  {
		padding-left: 0;
		padding-right: 0;
	}
}
<?php endif; ?>
