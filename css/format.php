<style type="text/css">

/*------------------------------*\
	$FORMATTING
\*------------------------------*/

.format { word-wrap: break-word; }

.format ul { list-style: square; }

.format a { text-decoration: underline; }

.format a:hover{ text-decoration: none; }

.format .headline, .format h1, .format h2, .format h3, .format h4, .format h5, .format h6 {
	margin-bottom: <?php echo $half; ?>px;
	position: relative;
}

.format h1 .badge, .format h2 .badge {
	background-color: <?php echo $colors['site']['text-sec']; ?>;
	top: -4px;
}

.format .headline a, .format h1 a, .format h2 a, .format h3 a, .format h4 a, .format h5 a, .format h6 a {
	color: <?php echo $colors['site']['headline-links']; ?>;
	text-decoration: none;
}

.format ul, .format ol, .format dl, .format p, .format hr, .format pre, .format table, .format .wp-caption, .format fieldset, .format .gfield, .format .alert, .format .note, .format .wp-block-image, .format .email-form-wrap { margin-bottom: <?php echo $single; ?>px; }

.format blockquote,
.format input[type="text"], .format textarea,
.format input[type="url"], .format input[type="email"],
.format input[type="password"] { margin-bottom: <?php echo $half; ?>px; }

.format [class*="form-attached"] .form-input, .format [class*="form-attached"] .form-submit { margin-bottom: 0; }

.format ul, .format ol { margin-left: <?php echo $single; ?>px; }

.format li ul, .format li ol { margin-top: <?php echo $third; ?>px; }

.format li, .format dd {
	margin-bottom: <?php echo $third; ?>px;
	position: relative;
}

<?php if ( ! has_filter( 'md_filter_disable_format_fix' ) ) : ?>
	.format *:last-child { margin-bottom: 0; }
<?php endif; ?>

/* HEADLINES */

<?php
	$queries = array( 900 => 'tablet', 700 => 'mobile' );
	$titles = array(
		'huge' => '.huge-title',
		'h1' => 'h1, .large-title, .headline',
		'h2' => 'h2, .main-title',
		'h3' => 'h3, .med-title',
		'h4' => 'h4, .mid-title',
		'h5' => 'h5, .small-title',
		'h6' => 'h6, .micro-title'
	);
	$texts = array(
		'huge' => '.huge-text',
		'h1' => '.large-text',
		'h2' => '.main-text',
		'h3' => '.med-text',
		'h4' => '.mid-text',
		'h5' => '.small-text',
		'h6' => '.micro-text'
	);
	$h1_ff = ! empty( $typography['h1']['font_family'] ) ? $typography['h1']['font_family'] : $font_family;
	$h1_fw = ! empty( $typography['h1']['font_weight'] ) ? $typography['h1']['font_weight'] : $bold;

	foreach ( $titles as $attribute => $selector ) {
		$h_font_family = ! empty( $typography[$attribute]['font_family'] ) ? $typography[$attribute]['font_family'] : $h1_ff;
		$h_font_weight = ! empty( $typography[$attribute]['font_weight'] ) ? $typography[$attribute]['font_weight'] : $h1_fw;
		echo
			"$selector, " . $texts[$attribute] . " {\n".
				"\tfont-size: " . $typography[$attribute]['font_size']['desktop'] . "px;\n".
				"\tline-height: " . $typography[$attribute]['line_height']['desktop'] . "px;\n".
			"}\n".
			"$selector {\n".
				( ! empty( $typography[$attribute]['font_family'] ) || ! empty( $typography['h1']['font_family'] ) ? "\tfont-family: {$h_font_family};\n" : '' ).
				( ! empty( $typography[$attribute]['font_style'] ) ? "\tfont-style: italic;\n" : '' ).
				"\tfont-weight: {$h_font_weight};\n".
			"}\n";
	}

	foreach ( $queries as $w => $d ) {
		echo "@media all and (max-width: {$w}px) {\n";
		foreach ( $titles as $h => $selector ) {
			echo "\t$selector, " . $texts[$h] . " { ".
				 	'font-size: ' . $typography[$h]['font_size'][$d] . 'px; '.
				 	'line-height: ' . $typography[$h]['line_height'][$d] . 'px; '.
				 "}\n";
		}
		echo "}\n";
	}
?>

.content .headline, .content .headline a, .content-text h1, .content-text h2, .content-text h3, .content-text h4, .content-text h5, .content-text h6 { color: <?php echo $colors['site']['headline']; ?>; }

.content-text h2:not(:first-child), .content-text h3:not(:first-child), .content-text h4:not(:first-child), .content-text h5:not(:first-child) { margin-top: <?php echo $mid; ?>px; }

/* TEXT STYLES */

.text-center { text-align: center; }

.text-left { text-align: left; }

.text-right { text-align: right; }

.caps { text-transform: uppercase; }

.text-dark { color: #1e1e1e; }

.text-sec, .entry-subtitle { color: <?php echo $colors['site']['text-sec']; ?>; }

.text-white .text-sec { color: #ddd; }

.text-white { color: #fff; }

.text-intro:first-letter, .has-drop-cap:first-letter, .drop {
	color: <?php echo $colors['site']['links']; ?>;
	float: left;
	font-size: 4.5em;
	line-height: 1;
	margin-bottom: 0.1em;
	margin-right: 0.1em;
}

.text-sep { position: relative; }

.text-sep:after {
	background-color: <?php echo $colors['site']['primary']; ?>;
	content: '';
	display: block;
	height: 4px;
	margin-top: 20px;
	width: 146px;
}

.text-center.text-sep:after, .text-center .text-sep:after {
	margin-left: auto;
	margin-right: auto;
}

.badge {
    background-color: #f58f2a;
    border-radius: 5px;
    color: #fff;
    margin-left: 4px;
	font-size: <?php echo $typography['body']['font_size']['tablet']; ?>px;
	font-weight: normal;
	padding: 4px 7px;
    position: relative;
    text-transform: uppercase;
}

.middot:not(:last-child):after {
	content: '\00b7';
	margin-left: 6px;
	margin-right: 3px;
}

/* BYLINE */

.byline {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop'] - 2; ?>px;
	margin-bottom: <?php echo $third; ?>px;
	position: relative;
}

.byline a {
	color: <?php echo $colors['site']['text-sec']; ?>;
	text-decoration: none;
}

.byline .author-link { border-bottom: 1px solid rgba(0, 0, 0, 0.15); }

.byline .author-link:hover { border-bottom: 0; }

.byline-item { display: inline-block; }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-author .avatar {
	margin-right: <?php echo $small; ?>px;
	position: relative;
}

.byline-item:not(:last-child) { margin-right: <?php echo $third; ?>px; }

.byline .badge {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 3; ?>px;
	padding-bottom: 2px;
	padding-top: 2px;
}

.byline-comments-label { display: none; }

.byline-date-modified { font-style: italic; }

/* CAPTION */

.wp-caption {
	height: auto;
	max-width: 100%;
}

.wp-caption-text {
	border-bottom: 1px solid #ccc;
	color: #444;
	font-size: 14px;
	font-style: italic;
	line-height: 22px;
	padding: 13px;
}

/* FEATURED IMAGE */

.featured-image { position: relative; }

.featured-image a { display: block; }

.featured-image img { width: 100%; }

.image-caption {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	padding: <?php echo $third; ?>px;
	text-align: center;
}

.image-caption { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.cover .image-caption {
	background-color: rgba(0, 0, 0, 0.75);
	border-bottom: 0;
	color: #fff;
	margin-bottom: 0;
	padding: <?php echo $small; ?>px <?php echo $third; ?>px;
	position: absolute;
		bottom: 0;
		right: 0;
	z-index: 10;
}

@media all and (min-width: 800px) {
	.text-intro, .intro, .subtitle {
		font-size: <?php echo round( $typography['h5']['font_size']['tablet'] ); ?>px;
		line-height: <?php echo round( $typography['h5']['line_height']['tablet'] ); ?>px;
	}
	.featured-image.alignleft, .featured-image.alignright { max-width: <?php echo $single * 13; ?>px; }
	.loop .featured-image.alignleft, .loop .featured-image.alignright, .content-sidebar .featured-image.alignleft, .content-sidebar .featured-image.alignright { max-width: <?php echo $single * 10; ?>px; }
}

/* LISTS */

.list, .list > ul, ul.list-check { list-style: none; }

.list li, ul.list-check li { position: relative; }

.list > li:not(:last-child) {
	border-bottom: 1px solid rgba(0, 0, 0, 0.15);
	margin-bottom: <?php echo $third; ?>px;
	padding-bottom: <?php echo $third; ?>px;
}

.list.list-large > li:not(:last-child) {
	margin-bottom: <?php echo $single; ?>px;
	padding-bottom: <?php echo $single; ?>px;
}

.list .children {
	border-left: 1px solid #ddd;
	margin-left: 0;
	margin-top: <?php echo $single; ?>px;
	padding-left: <?php echo $single; ?>px;
}

.list .children li:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.list li.small a {
	border-bottom-color: <?php echo $colors['site']['text-sec']; ?>;
	color: <?php echo $colors['site']['text-sec']; ?>;
}

/* LIST CHECK */

ul.list-check { margin-left: <?php echo $single + $small; ?>px; }

ul.list-check li:not(:last-child) { margin-bottom: <?php echo $third; ?>px; }

ul.list-check li:before {
	background-color: rgba(0, 0, 0, 0.08);
	border-radius: 50%;
	color: #22a340;
	padding: <?php echo $small; ?>px;
	position: absolute;
		left: -<?php echo $single + $small; ?>px;
		top: 0;
}

/* WP BLOCKS */

.wp-block-cover[class*="align"] { width: auto; }

.wp-block-image figcaption {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-style: italic;
	font-size: 0.9em;
	text-align: center;
}

.quote-box-author {
	color: #444;
	font-size: 14px;
	line-height: 21px;
	font-style: italic;
	margin-left: <?php echo $single; ?>px;
	padding-right: <?php echo $double; ?>px;
}

.callout {
	border: 4px solid rgba(0, 0, 0, 0.1);
	border-radius: 5px;
	clear: both;
	position: relative;
}

.callout.has-icon { padding-top: 0; }

.callout-title, .callout-action { text-align: center; }

.callout-icon {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
	color: #fff;
	display: block;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

.callout-icon.icon {
	background-color: #1e1e1e;
	font-size: 43px;
	height: 80px;
	margin-top: -25px;
	padding-top: 18px;
	width: 80px;
}

.callout-icon.image {
	height: 100px;
	margin-top: -35px;
	width: 100px;
}

.callout-icon.image img {
	border-radius: 50%;
	height: 100px;
	width: 100px;
}

.callout-button, .content-upgrade .button { width: 100%; }

.content-upgrade { border-radius: 5px; }

@media all and (min-width: 900px) {
	.box-lr {
		align-items: center;
		display: flex;
	}
	.box-lr .content-upgrade-text { width: 65%; }
	.box-lr .content-upgrade-action {
		padding-left: <?php echo $half; ?>px;
		width: 35%;
	}
}

@media all and (max-width: 900px) {
	.content-upgrade-text { margin-bottom: <?php echo $half; ?>px; }
}