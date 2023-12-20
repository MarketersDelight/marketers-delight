<style type="text/css">

/*------------------------------*\
	$POST
\*------------------------------*/

/* ALIGNMENTS */

.alignfull, .alignwide { max-width: initial; }

.alignleft, .alignright, .aligncenter, .alignnone {
	display: block;
	position: relative;
	margin-bottom: <?php echo $single; ?>px;
	z-index: 10;
}

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

@media all and (min-width: 900px) {
	.article.full .alignfull, .article.full .alignleft.wrap { margin-left: -<?php echo $breakout; ?>%; }
	.article.full .alignfull, .article.full .alignright.wrap { margin-right: -<?php echo $breakout; ?>%; }
	.article.full .alignwide, .alignfull, .alignright.wrap { margin-right: -<?php echo $mid; ?>px; }
	.article.full .alignwide, .alignfull, .alignleft.wrap { margin-left: -<?php echo $mid; ?>px; }
	.alignwide, .alignright.wrap-small { margin-right: -<?php echo $half; ?>px; }
	.alignwide, .alignleft.wrap-small { margin-left: -<?php echo $half; ?>px; }
}

@media all and (max-width: 900px) {
	.alignfull, .alignleft.wrap { margin-left: -<?php echo $half; ?>px; }
	.alignfull, .alignright.wrap { margin-right: -<?php echo $half; ?>px; }
}

@media all and (min-width: 700px) {
	.alignleft {
		float: left;
		margin-right: <?php echo $half; ?>px;
	}
	.alignright {
		float: right;
		margin-left: <?php echo $half; ?>px;
	}
	.featured-image.alignleft, .featured-image.alignright { max-width: <?php echo $sidebar_width; ?>px; }
	.columns .featured-image.alignleft {
		margin-left: -<?php echo $half; ?>px;
		max-width: <?php echo round( $sidebar_width / 2 ); ?>px;
	}
	.columns .featured-image.alignright {
		margin-right: -<?php echo $half; ?>px;
		max-width: <?php echo round( $sidebar_width / 2 ); ?>px;
	}
}

@media all and (max-width: 700px) {
	.alignleft.wrap { margin-right: -<?php echo $half; ?>px; }
	.alignright.wrap { margin-left: -<?php echo $half; ?>px; }
}

/* HEADLINES */

.the-content .headline, .the-content .headline a, .the-content h1, .the-content h2, .the-content h3, .the-content h4, .the-content h5, .the-content h6 { color: <?php echo $colors['site']['headline']; ?>; }

.the-content h2:not(:first-child), .the-content h3:not(:first-child), .the-content h4:not(:first-child), .the-content h5:not(:first-child) { margin-top: <?php echo $mid; ?>px; }

/* IMAGES */

.featured-image { position: relative; }

.featured-image a { display: block; }

.featured-image img {
	border-radius: 5px;
	width: 100%;
}

.image-below-headline .featured-image img { border-radius: 0; }

.wp-caption {
	height: auto;
	max-width: 100%;
}

.post-box .wp-caption-text {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	padding: <?php echo $third; ?>px;
	text-align: center;
}

.cover .wp-caption-text {
	background-color: rgba(0, 0, 0, 0.75);
	color: #fff;
	padding: <?php echo $small; ?>px <?php echo $third; ?>px;
	position: absolute;
		bottom: 0;
		right: 0;
	z-index: 10;
}

/* LISTS */

.list, .list > ul, ul.list-check { list-style: none; }

.list li, ul.list-check li { position: relative; }

.list > li:not(:last-child) {
	border-bottom: 1px solid rgba(0, 0, 0, 0.15);
	margin-bottom: <?php echo $third; ?>px;
	padding-bottom: <?php echo $third; ?>px;
}

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

.format ul { list-style: square; }

.format ul, .format ol { margin-left: <?php echo $single; ?>px; }

.format li {
	margin-bottom: <?php echo $third; ?>px;
	position: relative;
}

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

/* WP BLOCKS */

.wp-block-cover[class*="align"] { width: auto; }

.wp-block-image figcaption {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-style: italic;
	font-size: 0.9em;
	text-align: center;
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
