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

body {
	background-color: <?php echo $colors['site']['bg_color']; ?>;
	color: <?php echo $colors['site']['text']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-weight: <?php echo $font_weight; ?>;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
	position: relative;
}

/* ICONS */

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

#cancel-comment-reply-link:before,
.menu .trigger-icon:before,
.menu .trigger-icon:after,
.list-check li:before,
.breadcrumbs li:not(:last-child):after {
	display: inline-block;
	font-family: md-icon;
	font-style: normal;
	font-weight: normal;
	line-height: 1;
}

/* HEADINGS */

h1, h2, h3, h4, h5, h6 {
	<?php if ( $colors['site']['text'] !== $colors['site']['headline'] ) : ?>
	color: <?php echo $colors['site']['headline']; ?>;
	<?php endif; ?>
	position: relative;
}

h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: <?php echo $colors['site']['headline-links']; ?>; }

<?php // Calculate titles/headings font properties

$titles = array(
	'huge' => '.huge, .huge-title',
	'h1' => 'h1, .h1, .large-title',
	'h2' => 'h2, .h2, .main-title',
	'h3' => 'h3, .h3, .med-title',
	'h4' => 'h4, .h4, .mid-title, .slim .title',
	'h5' => 'h5, .h5, .small-title, .slim .slim .title',
	'h6' => 'h6, .h6, .micro-title'
);
$h1_font_family = ! empty( $typography['h1']['font_family'] ) ? $typography['h1']['font_family'] : $font_family;
$h1_font_weight = ! empty( $typography['h1']['font_weight'] ) ? $typography['h1']['font_weight'] : $bold;

foreach ( $titles as $attribute => $selector ) {
	$h_ff = ! empty( $typography[$attribute]['font_family'] ) ? $typography[$attribute]['font_family'] : $h1_font_family;
	$h_fw = ! empty( $typography[$attribute]['font_weight'] ) ? $typography[$attribute]['font_weight'] : $h1_font_weight;

	echo "$selector {\n".
			( ! empty( $typography[$attribute]['font_family'] ) || ! empty( $typography['h1']['font_family'] ) ? "\tfont-family: {$h_ff};\n" : '' ).
			"\tfont-size: " . $typography[$attribute]['font_size']['desktop'] . "px;\n".
			"\tfont-weight: {$h_fw};\n".
			"\tline-height: " . $typography[$attribute]['line_height']['desktop'] . "px;\n".
		"}\n";
}
?>

/* ATTRIBUTES */

p { position: relative; }

.normal { font-weight: <?php echo $font_weight; ?>; }

b, strong, .bold { font-weight: <?php echo $bold; ?>; }

i, em, .italic { font-style: italic; }

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

.caps { text-transform: uppercase; }

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

blockquote.small {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

blockquote.small:before, blockquote.small:after { font-size: <?php echo $typography['h1']['font_size']['desktop']; ?>px; }

blockquote.alignright, blockquote.alignleft { width: <?php echo ( $single * 6 ); ?>px; }

/* POST FORMAT */

.format { word-wrap: break-word; }

<?php if ( ! has_filter( 'md_filter_disable_format_fix' ) ) : ?>
.format *:last-child { margin-bottom: 0; }
<?php endif; ?>

.format a { text-decoration: underline; }

.format h1, .format h2, .format h3, .format h4, .format h5, .format h6 { margin-bottom: <?php echo $half; ?>px; }

.format h1 a, .format h2 a, .format h3 a, .format h4 a, .format h5 a, .format h6 a, .format a:hover { text-decoration: none; }

.format ul, .format ol, .format p, .format hr, .format pre, .format table, .format blockquote, .format .wp-caption, .format .wp-block-image { margin-bottom: <?php echo $single; ?>px; }

.format ul { list-style: square; }

.format ul, .format ol { margin-left: <?php echo $single; ?>px; }

.format li {
	margin-bottom: <?php echo $third; ?>px;
	position: relative;
}

/* SLIM FORMAT */

.slim {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile'] - 1; ?>px;
}

.slim ul, .slim ol, .slim p, .slim hr, .slim pre, .slim table, .slim blockquote, .slim .wp-caption, .slim .wp-block-image { margin-bottom: <?php echo $half; ?>px; }

.slim ul, .slim ol { margin-left: <?php echo $half; ?>px; }
