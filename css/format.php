<style type="text/css">

/*------------------------------*\
	$FORMATTING
\*------------------------------*/

/* BODY */

body {
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-weight: <?php echo $font_weight; ?>;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
}

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
			"}\n";
		echo
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

/* FORMAT */

.format { word-wrap: break-word; }

.format ul { list-style: square; }

.format a { text-decoration: underline; }

.format a:hover{ text-decoration: none; }

.format .headline, .format h1, .format h2, .format h3, .format h4, .format h5, .format h6 {
	margin-bottom: <?php echo $half; ?>px;
	position: relative;
}

.format .headline a, .format h1 a, .format h2 a, .format h3 a, .format h4 a, .format h5 a, .format h6 a {
	color: <?php echo $colors['site']['headline-links']; ?>;
	text-decoration: none;
}

.format ul, .format ol, .format dl, .format p, .format hr, .format blockquote, .format pre, .format table, .format .wp-caption, .format fieldset, .format .gfield, .format .alert, .format .note, .format .wp-block-image, .format .email-form-wrap { margin-bottom: <?php echo $single; ?>px; }

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

.content .headline, .content .headline a, .content-text h1, .content-text h2, .content-text h3, .content-text h4, .content-text h5, .content-text h6 { color: <?php echo $colors['site']['headline']; ?>; }

.content-text h2:not(:first-child), .content-text h3:not(:first-child), .content-text h4:not(:first-child), .content-text h5:not(:first-child) { margin-top: <?php echo $mid; ?>px; }

/* ALIGNMENTS */

.alignleft, .alignright, .aligncenter, .alignnone {
	display: block;
	position: relative;
	margin-bottom: <?php echo $single; ?>px;
}

.alignleft {
	float: left;
	margin-right: <?php echo $half; ?>px;
}

.alignright {
	float: right;
	margin-left: <?php echo $half; ?>px;
}

.alignwide, .alignfull { max-width: initial; }

.alignwide img, .alignfull img { width: 100%; }

.aligncenter {
	clear: both;
	float: none;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

.alignnone {
	clear: both;
	float: none;
}

.width-full {
	clear: both;
	display: block;
	width: 100%;
}

.display-block { display: block; }

.auto {
	margin-left: auto;
	margin-right: auto;
}

@media all and (max-width: 800px) {
	.alignright, .alignleft,
	.wp-block-image .alignleft, .wp-block-image .alignright {
		clear: both;
		display: block;
		float: none;
		margin-left: auto;
		margin-right: auto;
		text-align: center;
	}
	.wp-block-image .aligncenter > figcaption,
	.wp-block-image .alignleft > figcaption,
	.wp-block-image .alignright > figcaption { display: block; }
}

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
    border-radius: 2px;
    color: #fff;
    margin-left: 4px;
    font-size: 13px;
    padding: 3px 5px 3px 4px;
    position: relative;
    text-transform: uppercase;
}

.middot:not(:last-child):after {
	content: '\00b7';
	margin-left: 6px;
	margin-right: 3px;
}

@media all and (min-width: 900px) {
	.text-intro, .intro, .subtitle, .page-description {
		font-size: <?php echo round( $typography['body']['font_size']['desktop'] * 1.2 ); ?>px;
		line-height: <?php echo round( $typography['body']['line_height']['desktop'] * 1.1 ); ?>px;
	}
}

/* LISTS */

.list, .list > ul, ul.list-check { list-style: none; }

.list li, ul.list-check li { position: relative; }

.list > li:not(:last-child),
.box-style-list ul > li:not(:last-child) {
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

ul.list-check li:not(:last-child) { margin-bottom: <?php echo $third; ?>px; }

ul.list-check li:before {
	color: green;
	position: absolute;
		left: -<?php echo $single; ?>px;
		top: 3px;
}

.list-check.style-bullets {
	border: 2px solid #21a340;
	border-radius: 2px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	margin-left: 0;
}
.list-check.style-bullets li { padding: 7px 7px 7px 48px; }
.list-check.style-bullets li:not(:last-child) {
	border-bottom: 2px solid #21a340;
	margin-bottom: 0;
}
.list-check.style-bullets li:before {
	background-color: #21a340;
	border-radius: 5px;
	color: #fff;
	left: 7px;
	top: auto;
	padding: 6px;
}

/* QUOTE BOX / BLOCKQUTE */

blockquote, .quote-box {
	background-color: #fff;
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-left-width: 7px;
	border-radius: 3px;
	color: #444;
	display: block;
	font-style: italic;
	padding: <?php echo $single; ?>px;
	position: relative;
}

blockquote:before, .quote-box:before {
	content: "\201C";
	color: #ddd;
	font-family: Georgia, serif;
	font-size: 69px;
	font-weight: bold;
	position: absolute;
		left: 6px;
		top: 28px;
}

blockquote.alignright, blockquote.alignleft { width: <?php echo ( $single * 6 ); ?>px; }

.quote-box, .quote-box img.quote-box-image { margin-bottom: <?php echo $half; ?>px; }

.quote-box {
	border-top: 1px solid #f0f0f0;
	border-right: 1px solid #eee;
	filter: drop-shadow(3px 4px 4px rgba(0, 0, 0, 0.07));
}

.quote-box:after {
	border-width: <?php echo $single; ?>px <?php echo $single; ?>px 0 0;
	border-style: solid;
	border-color: #fff transparent;
	content: '';
	display: block;
	position: absolute;
		bottom: -<?php echo $single; ?>px;
		right: <?php echo $single; ?>px;
	width: 0;
}

.quote-box-author {
	color: #444;
	font-size: 14px;
	line-height: 21px;
	font-style: italic;
	margin-left: <?php echo $single; ?>px;
	padding-right: <?php echo $double; ?>px;
}

/* OVERLAY */

.image-overlay {
	background-position: center top;
	background-size: cover;
	display: block;
	position: relative;
	z-index: 0;
}

.image-overlay:after { z-index: -1; }

.overlay, .image-overlay:after {
	background-color: <?php echo $colors['header']['cover_color']; ?>;
	content: '';
	display: block;
	height: 100%;
	position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		top: 0;
	width: 100%;
}

/* DESIGN */

.circle { border-radius: 50%; }

.shadow, .wp-block-image.shadow img { box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2); }

.shadow-large, .wp-block-image.shadow-large img { box-shadow: 0 5px 55px rgba(0, 0, 0, 0.15); }

.shadow-small, .wp-block-image.shadow-small img { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15); }

.wp-block-image.shadow, .wp-block-image.shadow-large, .wp-block-image.shadow-small { box-shadow: none; }

.box { background-color: #fff; }

.box-sec, .frame, .note { background-color: #eee; }

.box-dark {
	background-color: #1e1e1e;
	color: #fff;
}

.box-dark .text-sec { color: #ddd; }

.alert { background-color: #fffbcc; }

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
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
	content: '\e810';
	color: <?php echo $colors['site']['primary']; ?>;
}

/* CIRCLE ICON */

.circle-icon, a.circle-icon {
	background-color: rgba(0, 0, 0, 0.15);
	border-radius: 50%;
	color: <?php echo $colors['site']['text']; ?>;
	display: inline-block;
	line-height: 1;
	position: relative;
	text-align: center;
}

.circle-icon.micro {
	bottom: -2px;
	font-size: 15px;
	height: 25px;
	padding-top: 5px;
	width: 25px;
}

/* VIDEO */

.video-wrap {
	height: 0;
	position: relative;
	padding-bottom: 56.25%;
	padding-top: 25px;
}

.video-wrap iframe {
	height: 100%;
	position: absolute;
		left: 0;
		top: 0;
	width: 100%;
}

.play-button {
	border: 4px solid #fff;
	border-radius: 50%;
	cursor: pointer;
	display: inline-block;
	height: 75px;
	padding: 20px 26px 26px;
	position: relative;
	text-align: center;
	width: 75px;
}

.play-button:after {
	content: '';
	display: block;
	border-style: solid;
	border-width: 15px 0 15px 22px;
	border-color: transparent transparent transparent rgba(255, 255, 255, 1);
}

.play-button-text {
	font-size: 13px;
	font-weight: bold;
	text-transform: uppercase;
}

/* TWITTER */

.twitter-tweet {
	margin-left: auto;
	margin-right: auto;
}

/* FIXES */

@media all and (max-width: 700px) {
	.close-on-mobile { display: none; }
}
