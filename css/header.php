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

/* LAYOUT */

.header-wrap, .header-primary, .header-aside {
	align-items: center;
	display: flex;
	justify-content: space-between;
}

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
	position: relative;
}

.logo img { width: 100%; }

/* SEARCH */

.header-search { padding: <?php echo $half; ?>px; }

.trigger-search { display: none; }

.form-toggle .trigger-search { display: block; }

/* QUERIES */

@media all and (min-width: 800px) {
	.header-triggers { display: none; }
}
