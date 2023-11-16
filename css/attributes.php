<style type="text/css">

<?php echo '/*
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
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-weight: <?php echo $font_weight; ?>;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
	position: relative;
}

.normal { font-weight: <?php echo $font_weight; ?>; }

b, strong, .bold { font-weight: <?php echo $bold; ?>; }

i, em, .italic { font-style: italic; }

.f-small {
	font-size: 0.85em;
	line-height: 1.5em;
}

.f-normal { font-family: <?php echo $typography['body']['font_family']; ?>; }

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
	background-color: rgba(0, 0, 0, 0.1);
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

/* BODY */

@media all and (max-width: 900px) {
	body {
		font-size: <?php echo $font_size['tablet']; ?>px;
		line-height: <?php echo $line_height['tablet']; ?>px;
	}
}

<?php if ( $font_size['tablet'] !== $font_size['mobile'] ) : ?>
@media all and (max-width: 700px) {
	body {
		font-size: <?php echo $font_size['mobile']; ?>px;
		line-height: <?php echo $line_height['mobile']; ?>px;
	}
}
<?php endif; ?>

/* BLOCKQUOTE */

blockquote {
	background-color: #fff;
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-left-width: 7px;
	border-radius: 5px;
	color: <?php echo $colors['site']['text-sec']; ?>;
	display: block;
	font-style: italic;
	padding: <?php echo $single; ?>px;
	position: relative;
}

blockquote:before, blockquote:after {
	color: #ddd;
	font-family: Georgia, serif;
	font-size: <?php echo $typography['huge']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	position: absolute;
}

blockquote:before {
	content: open-quote;
	left: <?php echo $small; ?>px;
}

blockquote:after {
	bottom: <?php echo $small; ?>px;
	content: close-quote;
	right: <?php echo $half; ?>px;
}

blockquote + p {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	text-align: right;
}

blockquote.small {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

blockquote.small:before, blockquote.small:after { font-size: <?php echo $typography['h1']['font_size']['desktop']; ?>px; }

blockquote.alignright, blockquote.alignleft { width: <?php echo ( $single * 6 ); ?>px; }
