<style type="text/css"><?php echo '
/*
	Theme Name: Marketers Delight
	Theme URI: https://marketersdelight.com/
	Author: Alex, Kolakube
	Author URI: https://kolakube.com/
	Description: Start a website that delights. Marketers Delight adds powerful content marketing and design tools to make publishing on your WordPress website fun and productive. Extend your website\'s features with Drop-ins and develop your own layouts with MD development tools.
	GitHub Theme URI: https://github.com/MarketersDelight/marketers-delight
	Text Domain: md
	Version: ' . MD_VERSION . '
	Table of contents:' . $style_guide .
'*/';

if ( locate_template( 'css/fonts.php' ) )
	include locate_template( 'css/fonts.php' );
?>

/* GLOBAL */

*, *:before, *:after {
	box-sizing: border-box;
	margin: 0;
	padding: 0;
}

body {
	background-color: <?php echo $colors['site']['bg_color']; ?>;
	color: <?php echo $colors['site']['text']; ?>;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $font_weight; ?>;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
}

/* FONT ICONS */

@font-face {
	font-family: md-icon;
	font-display: swap;
	src: url('<?php echo md_font_icons_url(); ?>') format('woff2');
	font-style: normal;
	font-weight: 400;
}

[class*="md-icon"] { display: inline-block; }

[class*="md-icon"]:before {
	display: inline-block;
	font-family: md-icon;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 1;
	text-align: center;
	text-decoration: inherit;
	text-transform: none;
}

.list-check li:before,
#cancel-comment-reply-link:before,
.menu .trigger-icon:before,.menu .trigger-icon:after,
.breadcrumbs li:not(:last-child):after, .breadcrumbs-home:before {
	display: inline-block;
	font-family: md-icon;
	font-style: normal;
	font-weight: normal;
	line-height: 1;
}

.md-icon.icon-data:before { content: attr(data-md-icon); }