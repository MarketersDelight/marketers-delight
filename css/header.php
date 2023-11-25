<style type="text/css">

<?php
	$header_layout = md_setting( array( 'header', 'layout' ), 'standard' );
	$header_full_width = md_setting( array( 'header', 'display', 'full_width' ) );
?>

/*------------------------------*\
	$HEADER
\*------------------------------*/

.header {
	background-color: <?php echo $header['bg_color']; ?>;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	color: <?php echo $header['color']; ?>;
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

.header-wrap a:not(.button) { color: <?php echo $header['menu']['links']; ?>; }
.header-wrap a:not(.button):hover { color: <?php echo $header['menu']['hover']; ?>; }

.header .menu .current-menu-item > a,
.header .menu .current-menu-item > .menu-toggle { color: <?php echo $header['menu']['active']; ?>; }

.header-simple .header-wrap, .header-simple .header-controls { justify-content: center; }

.header-simple .header-menu { flex: 0 1 auto; }

.header-rtl .header-controls, .header-rtl .header-logo { order: 2; }

.header-link, .header_aside-link { padding: <?php echo $half; ?>px; }

/* LOGO */

.header-logo {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	position: relative;
}

.logo {
	flex: 1 0 auto;
	position: relative;
	<?php if ( ! empty( $logo['logo_width']['desktop'] ) ) : ?>
	width: <?php echo $logo['logo_width']['desktop']; ?>px;
	<?php endif; ?>
}

.logo .custom-logo-link { width: 100%; }

/* SITE TITLE + TAGLINE */

.logo + .header-details { margin-left: <?php echo $third; ?>px; }

.site-title {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	line-height: <?php echo $logo['site_title']['line_height']['desktop']; ?>px;
	<?php echo ( ! empty( $logo['site_title']['font_family'] ) ? "\tfont-family: " . $logo['site_title']['font_family'] . ";\n" : '' ); ?>
	<?php echo ( ! empty( $logo['site_title']['font_style'] ) ? "\tfont-style: italic;\n" : '' ); ?>
	<?php echo ( ! empty( $logo['site_title']['font_weight'] ) ? "\tfont-weight: " . $logo['site_title']['font_weight'] . ";\n" : '' ); ?>
}

<?php if ( ! empty( $header['site_title'] ) ) : ?>
	.header .site-title, .header .site-title:hover,
	.header .site-title a, .header .site-title a:hover { color: <?php echo $header['site_title']; ?>; }
<?php endif; ?>

.tagline {
	color: <?php echo $header['site_tagline']; ?>;
	<?php if ( ! empty( $logo['site_tagline']['font_family'] ) ) : ?>
		font-family: <?php echo $logo['site_tagline']['font_family']; ?>;
	<?php endif; ?>
	font-size: <?php echo $logo['site_tagline']['font_size']['desktop']; ?>px;
	<?php if ( ! empty( $logo['site_tagline']['font_style'] ) ) : ?>
		font-style: italic;
	<?php endif; ?>
	<?php if ( ! empty( $logo['site_tagline']['font_weight'] ) ) : ?>
		font-weight: <?php echo $logo['site_tagline']['font_weight']; ?>;
	<?php endif; ?>
	line-height: <?php echo $logo['site_tagline']['line_height']['desktop'] ; ?>px;
}

.tagline a { color: <?php echo $header['site_tagline']; ?>; }

<?php if ( md_setting( array( 'header', 'display', 'tagline_same_line' ) ) ) : ?>
.site-title + .tagline { margin-left: <?php echo $third; ?>px; }
<?php endif; ?>

/* TRIGGERS */

.header-triggers {
	flex: 1;
	justify-content: end;
}

.header-rtl .header-triggers {
	flex-direction: row-reverse;
	justify-content: start;
}

.trigger {
	cursor: pointer;
	position: relative;
}

.trigger-icon {
	color: <?php echo $header['color']; ?>;
	font-size: <?php echo round( $header['font_size']['desktop'] * 1.3 ); ?>px;
	line-height: 1;
}

.button-small .link-icon { font-size: inherit; }

.hide-label .trigger-text { display: none; }

.header .button .link-icon + .trigger-text { margin-left: <?php echo $small; ?>px; }

.has-search .trigger-search .trigger-icon:before, .has-mobile-menu .trigger-menu .trigger-icon:before {
	color: <?php echo $colors['site']['primary']; ?>;
	content: '\e810';
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
	.tagline { max-width: <?php echo $quad * 3; ?>px; }
	/* MENU */
	.header-menu, .header_aside-menu {
		display: flex;
		flex: 1 0 auto;
	}
	.header-standard .header-menu { justify-content: end; }
	/* SEARCH */
	.header-search { padding: <?php echo $half; ?>px; }
	.header.has-search .header-aside, .header.has-search .header-search { flex: 1 0 auto; }
	.header.has-search .header-menu, .header.has-search .header_aside-menu,
	.header.has-search .header-link, .header.has-search .header_aside-link { display: none; }
	.header .search-form .form-submit {
		min-width: 60px;
		width: auto;
	}
	/* HEADER FLYER */
	.header-flyer .header-wrap { justify-content: center; }
	.header-flyer .header-controls {
		flex: 1;
		order: 2;
	}
	.header-flyer.solo .header-controls { order: inherit; }
	.header-primary, .header-aside {
		align-items: center;
		display: flex;
		flex: 1 0 auto;
	}
	.header-aside { flex: 0 1 auto; }
	.header-flyer .header-aside {
		align-items: center;
		justify-content: end;
		order: 3;
	}
	.header-flyer .header-menu, .header-flyer.solo .header-controls, .header-flyer.solo .header-primary { flex: 0 1 auto; }
	.header-flyer.has-search .header-controls { order: inherit; }
	.header-flyer.has-search .search-form { flex: 1; }
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
	<?php
	$header_t_fs = $header_t_lh = '';

	if ( ! empty( $header['font_size']['tablet'] ) )
		$header_t_fs = $header['font_size']['tablet'];

	if ( ! empty( $header['line_height']['tablet'] ) )
		$header_t_lh = $header['line_height']['tablet'];

	if ( $header_t_fs || $header_t_lh ) : ?>
	.header {
		<?php if ( $header_t_fs ) : ?>
			font-size: <?php echo $header_t_fs; ?>px;
		<?php endif; ?>
		<?php if ( $header_t_lh ) : ?>
			line-height: <?php echo $header_t_lh; ?>px;
		<?php endif; ?>
	}
	<?php endif; ?>
	<?php if ( ! empty( $logo['logo_width']['tablet'] ) ) : ?>
		.logo { width: <?php echo $logo['logo_width']['tablet']; ?>px; }
	<?php endif; ?>
	<?php if ( md_setting( array( 'header', 'display', 'hide_title_mobile' ) ) ) : ?>
	.site-title { display: none; }
	<?php endif; ?>
	<?php if ( md_setting( array( 'header', 'display', 'hide_tagline_mobile' ) ) ) : ?>
	.tagline { display: none; }
	<?php endif; ?>
	.header-controls .trigger { margin-left: <?php echo $half; ?>px; }
	/* DISPLAYS */
	.hide-label-mobile .link-text, .header-menu, .header_aside-menu, .header-link, .header_aside-link, .header .search-form { display: none; }
	.has-mobile-menu .header-menu, .has-mobile-menu .header_aside-menu, .header-controls .header-link, .header-controls .header_aside-link { display: block; }
	.header .menu a:hover { color: <?php echo $header['submenu']['hover']; ?>; }
	.header .menu > .menu-item:not(:last-child) { border-bottom: 1px solid <?php echo $header['border_color']; ?>; }
	.header-controls [class*="hide-label"] .trigger-icon { font-size: <?php echo round( $header['font_size']['desktop'] * 1.7 ); ?>px; }
	/* SEARCH */
	.header-search { padding: <?php echo $half; ?>px; }
	.has-search .search-form {
		display: flex;
		flex-basis: 100%;
	}
	.header .search-input { width: 100%; }
}

@media all and (max-width: 900px) {
	<?php
	$site_title_t_fs = $site_title_t_lh = '';

	if ( ! empty( $logo['site_title']['font_size']['tablet'] ) )
		$site_title_t_fs = $logo['site_title']['font_size']['tablet'];

	if ( ! empty( $logo['site_title']['line_height']['tablet'] ) )
		$site_title_t_lh = $logo['site_title']['line_height']['tablet'];

	if ( $site_title_t_fs || $site_title_t_lh ) : ?>
	.site-title {
		<?php if ( $site_title_t_fs ) : ?>
		font-size: <?php echo $site_title_t_fs; ?>px;
		<?php endif; ?>
		<?php if ( $site_title_t_lh ) : ?>
		line-height: <?php echo $site_title_t_lh; ?>px;
		<?php endif; ?>
	}
	<?php endif; ?>
	<?php
	$site_tagline_t_fs = $site_tagline_t_lh = '';

	if ( ! empty( $logo['site_tagline']['font_size']['tablet'] ) )
		$site_tagline_t_fs = $logo['site_tagline']['font_size']['tablet'];

	if ( ! empty( $logo['site_tagline']['line_height']['tablet'] ) )
		$site_tagline_t_lh = $logo['site_tagline']['line_height']['tablet'];

	if ( $site_tagline_t_fs || $site_tagline_t_lh ) : ?>
	.tagline {
		<?php if ( $site_tagline_t_fs ) : ?>
		font-size: <?php echo $site_tagline_t_fs; ?>px;
		<?php endif; ?>
		<?php if ( $site_tagline_t_lh ) : ?>
		line-height: <?php echo $site_tagline_t_lh; ?>px;
		<?php endif; ?>
	}
	<?php endif; ?>
}

@media all and (max-width: 700px) {
	<?php
	$header_m_fs = $header_m_lh = '';

	if ( ! empty( $header['font_size']['mobile'] ) )
		$header_m_fs = $header['font_size']['mobile'];

	if ( ! empty( $header['line_height']['mobile'] ) )
		$header_m_lh = $header['line_height']['mobile'];

	if ( $header_m_fs || $header_m_lh ) : ?>
	.header {
		<?php if ( $header_m_fs ) : ?>
		font-size: <?php echo $header_m_fs; ?>px;
		<?php endif; ?>
		<?php if ( $header_m_lh ) : ?>
		line-height: <?php echo $header_m_lh; ?>px;
		<?php endif; ?>
	}
	<?php endif; ?>
	<?php
	$site_title_m_fs = $site_title_m_lh = '';

	if ( ! empty( $logo['site_title']['font_size']['mobile'] ) )
		$site_title_m_fs = $logo['site_title']['font_size']['mobile'];

	if ( ! empty( $logo['site_title']['line_height']['mobile'] ) )
		$site_title_m_lh = $logo['site_title']['line_height']['mobile'];

	if ( $site_title_m_fs || $site_title_m_lh ) : ?>
	.site-title {
		<?php if ( $header_m_fs ) : ?>
		font-size: <?php echo $header_m_fs; ?>px;
		<?php endif; ?>
		<?php if ( $header_m_lh ) : ?>
		line-height: <?php echo $header_m_lh; ?>px;
		<?php endif; ?>
	}
	<?php endif; ?>
	<?php
	$site_tagline_m_fs = $site_tagline_m_lh = '';

	if ( ! empty( $logo['site_tagline']['font_size']['mobile'] ) )
		$site_tagline_m_fs = $logo['site_tagline']['font_size']['mobile'];

	if ( ! empty( $logo['site_tagline']['line_height']['mobile'] ) )
		$site_tagline_m_lh = $logo['site_tagline']['line_height']['mobile'];

	if ( $site_tagline_m_fs || $site_tagline_m_lh ) : ?>
	.tagline {
		<?php if ( $site_tagline_m_fs ) : ?>
		font-size: <?php echo $site_tagline_m_fs; ?>px;
		<?php endif; ?>
		<?php if ( $site_tagline_m_lh ) : ?>
		line-height: <?php echo $site_tagline_m_lh; ?>px;
		<?php endif; ?>
	}
	<?php endif; ?>
	<?php if ( ! empty( $logo['logo_width']['mobile'] ) ) : ?>
		.logo { width: <?php echo $logo['logo_width']['mobile']; ?>px; }
	<?php endif; ?>
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.header-logo { padding-left: <?php echo $half; ?>px; }
}

<?php if ( $header_full_width ) : ?>
.header.has-search .menu-secondary { display: none; }
@media all and (max-width: <?php echo $site_width_full; ?>px) {
	.header .inner {
		padding-left: <?php echo $single; ?>px;
		padding-right: <?php echo $single; ?>px;
	}
}
@media all and (max-width: <?php echo $site_width_full; ?>px) {
	.header .inner { max-width: 100%; }
}
@media all and (max-width: <?php echo $site_width; ?>px) {
	.header .inner {
		padding-left: 0;
		padding-right: 0;
	}
}
<?php endif; ?>
