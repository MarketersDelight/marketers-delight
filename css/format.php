<style type="text/css">

/*------------------------------*\
	$FORMAT
\*------------------------------*/

.format { word-wrap: break-word; }

.format a { text-decoration: underline; }

.format a :hover { text-decoration: none; }

.format :is(ul, ol, p, hr, table, blockquote, pre),
.format :is(.wp-caption, .wp-block-image),
.full .the-content .featured-media { margin-block-end: <?php echo $single; ?>px; }

/* HEADINGS */

.format :is(h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6, .huge) {
	color: <?php echo $colors['site']['headline']; ?>;
	margin-block-end: <?php echo $half; ?>px;
}

.format :is(h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6, .huge) a {
	color: <?php echo $colors['site']['headline-links']; ?>;
	text-decoration: none;
}

.format :is(h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6, .huge):is(.alignwide, .alignfull) { text-align: center; }

.the-content :is(h1, h2, h3, h4, h5, h6) { margin-block-start: <?php echo $mid; ?>px; }

.the-content :is(h1, h2, h3, h4, h5, h6):first-child { margin-block-start: 0; }

/* LISTS */

.format ul { list-style: square; }

.format ul[class^="list"], .format [class^="list"] ul { list-style: none; }

.format li {
	margin-block-end: <?php echo $half; ?>px;
	position: relative;
}

.format ul ul {
	margin-block-end: <?php echo $half; ?>px;
	margin-inline-start: <?php echo $half; ?>px;
}

.text-center [class^="list"], .text-center ul, .text-center ol { text-align: left; }

.the-content [class^="list"] { margin-inline-start: 0; }

.the-content :is(ul, ol, .list-check) { margin-inline-start: <?php echo $single; ?>px; }

ul[class^="list"], [class^="list"] ul { list-style: none; }

.list > ul:not(:last-child), .list li:not(:last-child) {
	border-block-end: 1px solid rgba(0, 0, 0, 0.15);
	padding-block-end: <?php echo $half; ?>px;
}

.list-check { margin-inline-start: <?php echo $single; ?>px; }

ul.list-check li:before {
	background-color: rgba(0, 0, 0, 0.08);
	border-radius: 50%;
	color: #22a340;
	margin-inline: -<?php echo $single + $small + 2; ?>px <?php echo $third; ?>px;;
	padding: <?php echo $small; ?>px;
}

/* QUOTES */

.format blockquote p { margin-block-end: <?php echo $half; ?>px; }

.format blockquote p + cite {
	display: block;
	margin-block-start: -<?php echo $half; ?>px;
}

.format .wp-block-pullquote {
	margin: 0;
	padding: 0;
}

.wp-block-pullquote blockquote { color: inherit; }

.wp-block-pullquote p {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	font-style: normal;
	font-weight: <?php echo $bold; ?>;
	line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
}

.wp-block-pullquote.is-style-plain blockquote {
	background-color: transparent;
	border: 0;
	box-shadow: none;
	font-style: normal;
	padding: 0;
}

.wp-block-pullquote.is-style-plain blockquote:before,
.wp-block-pullquote.is-style-plain blockquote:after { content: ''; }

/* SLIM */

.slim {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile'] - 1; ?>px;
}

.slim ul, .slim ol, .slim p, .slim hr,
.slim table, .slim blockquote, .slim pre, .slim .wp-caption,
.slim .wp-block-image, .slim .the-content .featured-media { margin-block-end: <?php echo $half; ?>px; }

.slim ul, .slim ol { margin-inline-start: <?php echo $half; ?>px; }

@media (min-width: <?php echo $post_width; ?>px) {
	.slim .title {
		font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
	}
}