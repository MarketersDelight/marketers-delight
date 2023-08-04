<style type="text/css">

<?php echo '/*
	Theme Name: Marketers Delight
	Version: ' . MD_VERSION . '
	Author: Alex Mangini
		Description: Built on a foundation of typography and a vision to fuel powerful features with lightweight performance, Marketers Delight is your website marketing framework for now and into the future. Capture Leads with MD Optins, write and design beautiful long-form content with interactive tools, curate reading lists with the Bookshelf, and much, much more.
	Theme URI: https://marketersdelight.com/
	Author URI: https://kolakube.com/
	Text Domain: md
*/';
?>

/*------------------------------*\
	$ATTRIBUTES
\*------------------------------*/

*, *:before, *:after {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	margin: 0;
	padding: 0;
}

/* FONT ICONS */

@font-face {
	font-family: md-icon;
	font-display: swap;
	src: url('<?php echo md_font_icons_url(); ?>') format('woff');
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
	speak: none;
	text-align: center;
	text-decoration: inherit;
	text-transform: none;
}

.md-icon.icon-data:before { content: attr(data-md-icon); }

#cancel-comment-reply-link:before, .menu-icon a, .list-check li:before {
	display: inline-block;
	font-family: md-icon;
	font-style: normal;
	font-weight: normal;
	line-height: 1;
}

/* ATTRIBUTES */

body {
	background-color: <?php echo $colors['site']['bg_color']; ?>;
	color: <?php echo $colors['site']['text']; ?>;
	position: relative;
}

b, strong, .bold { font-weight: <?php echo $bold; ?>; }

i, em, .italic { font-style: italic; }

.small {
	font-size: 0.85em;
	line-height: 1.5em;
}

<?php
	foreach ( md_editor_colors() as $color_group => $color_fields ) {
		$color_slug = $color_fields['slug'];
		$color_val = $color_fields['color'];
		echo
			".has-$color_slug-background-color { background-color: $color_val; }\n".
			( $color_slug !== 'text' ? ".has-$color_slug-color, .format .has-$color_slug-color { color: $color_val; }\n" : '' );
	}
?>

.has-text-color.has-white-color { color: #fff; }

p { position: relative; }

a {
	color: <?php echo $colors['site']['links']; ?>;
	text-decoration: none;
}

img, a img, .size-auto, .size-full, .size-large, .size-medium, .size-thumbnail {
	height: auto;
	max-width: 100%;
	vertical-align: top;
}

iframe, video, object { max-width: 100%; }

sup { line-height: 1; }

hr {
    border: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    height: 0;
}

pre, code {
	background-color: <?php echo $colors['site']['tertiary']; ?>;
	color: #3e3e3e;
	font-family: Consolas, Monaco, Menlo, Courier, Verdana, sans-serif;
	font-size: 0.9em;
}

pre {
	overflow: auto;
	padding: <?php echo $single; ?>px;
}

code {
	border-radius: 5px;
	padding: 2px 5px;
}

abbr, acronym {
	border-bottom: 1px dotted <?php echo $colors['site']['text-sec']; ?>;
	cursor: help;
	text-decoration: none;
}

/* TRIGGERS */

.trigger {
	cursor: pointer;
	position: relative;
}

.trigger-icon {
	color: <?php echo $colors['header']['color']; ?>;
	font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.3 ); ?>px;
	line-height: 1;
}

.trigger .trigger-text {
	font-size: <?php echo $typography['header']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['header']['line_height']['mobile']; ?>px;
	margin-left: <?php echo $small; ?>px;
}

.hide-label .trigger-text { display: none; }

.has-search .trigger-search .trigger-icon:before,
.has-mobile-menu .trigger-menu .trigger-icon:before {
	color: <?php echo $colors['site']['primary']; ?>;
	content: '\e810';
}
