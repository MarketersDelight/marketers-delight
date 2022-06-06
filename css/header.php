<style type="text/css">

/*------------------------------*\
	$HEADER
\*------------------------------*/

.header {
	background-color: <?php echo $colors['header']['bg_color']; ?>;
	color: <?php echo $colors['header']['menu']['links']; ?>;
	<?php echo ( ! empty( $typography['header']['font_family'] ) ? "\tfont-family: " . $typography['header']['font_family'] . ";\n" : '' ); ?>
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

.header-simple { text-align: center; }

.header-wrap { position: relative; }

.header-trigger { margin-left: <?php echo $half; ?>px; }

.header.featured-image-cover { padding-bottom: 0; }

/* LOGO + TAGLINE */

.logo {
	<?php echo ( ! empty( $typography['site_title']['font_family'] ) ? "\tfont-family: " . $typography['site_title']['font_family'] . ";\n" : '' ); ?>
	<?php echo ( ! empty( $typography['site_title']['font_style'] ) ? "font-style: italic;\n" : '' ); ?>
	<?php echo ( ! empty( $typography['site_title']['font_weight'] ) ? "\tfont-weight: " . $typography['site_title']['font_weight'] . ";\n" : '' ); ?>
}

.header .site-title, .header .site-title:hover { color: <?php echo $colors['header']['site_title']; ?>; }

.custom-logo-link {
	position: relative;
	<?php echo ( ! empty( $header['logo_width']['desktop'] ) ? 'width: ' . $header['logo_width']['desktop'] . 'px;' : '' ); ?>
	z-index: 10;
}

.header-standard .custom-logo-link {
	display: block;
	float: left;
	margin-right: <?php echo $half; ?>px;
}

.header-simple .custom-logo-link {
	display: inline-block;
	margin-bottom: <?php echo $half; ?>px;
}

.tagline {
	color: <?php echo $colors['header']['site_tagline']; ?>;
	<?php echo ( ! empty( $typography['site_tagline']['font_family'] ) ? "\tfont-family: " . $typography['site_tagline']['font_family'] . ";\n" : '' ); ?>
	font-size: <?php echo $typography['site_tagline']['font_size']['desktop']; ?>px;
	<?php echo ( ! empty( $typography['site_tagline']['font_style'] ) ? "\tfont-style: italic;\n" : '' ); ?>
	<?php echo ( ! empty( $typography['site_tagline']['font_weight'] ) ? "\tfont-weight " . $typography['site_tagline']['font_weight'] . ';' : '' ); ?>
	line-height: <?php echo $typography['site_tagline']['line_height']['desktop'] ; ?>px;
}

/* TRIGGERS */

.header-triggers { text-align: right; }

.header-trigger {
	cursor: pointer;
	display: inline-block;
	font-size: 25px;
	line-height: 1;
	position: relative;
	vertical-align: middle;
}

.header-trigger:before { font-family: 'md-icon'; }
.header-menu-trigger:before { content: '\e815'; }
.has-mobile-menu .header-menu-trigger:before { content: '\e810'; }

/* MENU */

.header a { color: <?php echo $colors['header']['menu']['links']; ?>; }

.header a:hover { color: <?php echo $colors['header']['menu']['hover']; ?>; }

/* QUERIES */

@media all and (min-width: 700px) {
	.header {
		padding-bottom: <?php echo $header['spacing_bottom']['desktop']; ?>px;
		padding-top: <?php echo $header['spacing_top']['desktop']; ?>px;
	}
	.header-wrap {
		display: table;
		width: 100%;
	}
	.header-logo, .header-triggers, .header-aside {
		display: table-cell;
		vertical-align: middle;
	}
	.header-aside { text-align: right; }
	.logo {
		font-size: <?php echo $typography['site_title']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['site_title']['line_height']['desktop'] . 'px'; ?>;
	}
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.header-wrap {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (max-width: 700px) {
	.header {
		<?php if ( ! empty( $typography['header']['font_size']['tablet'] ) ) : ?>
			font-size: <?php echo $typography['header']['font_size']['tablet']; ?>px;
		<?php endif; ?>
		<?php if ( ! empty( $typography['header']['line_height']['tablet'] ) ) : ?>
			line-height: <?php echo $typography['header']['line_height']['tablet']; ?>px;
		<?php endif; ?>
		padding-bottom: <?php echo $header['spacing_top']['tablet']; ?>px;
		padding-top: <?php echo $header['spacing_bottom']['tablet']; ?>px;
	}
	.header-logo { display: inline-block; }
	.logo {
		font-size: <?php echo $typography['site_title']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['site_title']['line_height']['tablet']; ?>px;
	}
	.tagline {
		font-size: <?php echo $typography['site_tagline']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['site_tagline']['line_height']['tablet']; ?>px;
	}
	<?php if ( ! empty( $header['logo_width']['tablet'] ) ) : ?>
		.custom-logo-link { width: <?php echo $header['logo_width']['tablet']; ?>px; }
	<?php endif; ?>
	.header-triggers {
		padding-top: <?php echo $third; ?>px;
		position: absolute;
			top: 0;
			right: <?php echo $half; ?>px;
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
		padding-bottom: <?php echo $header['spacing_top']['mobile']; ?>px;
		padding-top: <?php echo $header['spacing_bottom']['mobile']; ?>px;
	}
	.logo {
		font-size: <?php echo $typography['site_title']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['site_title']['line_height']['mobile']; ?>px;
	}
	.tagline {
		font-size: <?php echo $typography['site_tagline']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['site_tagline']['line_height']['mobile']; ?>px;
	}
	<?php if ( ! empty( $header['logo_width']['mobile'] ) ) : ?>
		.custom-logo-link { width: <?php echo $header['logo_width']['mobile']; ?>px; }
	<?php endif; ?>
}