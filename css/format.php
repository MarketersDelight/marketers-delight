<style type="text/css">

/*------------------------------*\
	$FORMAT
\*------------------------------*/

/* ATTRIBUTES */

a {
	color: var(--md-links);
	text-decoration: underline;
}

a:hover { text-decoration: none; }

strong, b { font-weight: var(--md-bold); }

img, a img {
	height: auto;
	max-width: 100%;
	vertical-align: top;
}

iframe, video, object { max-width: 100%; }

abbr { cursor: help; }

cite { color: var(--md-text-muted); }

sup { line-height: 1; }

hr {
	border: 0;
	border-block-end: 1px solid rgba(255, 255, 255, 0.3);
	border-block-start: 1px solid rgba(0, 0, 0, 0.1);
	height: 0;
}

code {
	border-radius: var(--md-border-radius);
	padding: 2px 5px;
}

pre {
	margin-block-end: var(--md-single);
	overflow: auto;
	padding: var(--md-single);
}

code, pre {
	background-color: rgba(0, 0, 0, 0.1);
	color: #3e3e3e;
	font-family: Consolas, Monaco, Menlo, Courier, Verdana, sans-serif;
	font-size: 0.9em;
}

/* FORMAT */

.format { word-wrap: break-word; }

.format :is(ul, ol, p, hr, table, blockquote, pre),
.format :is(.wp-caption, .wp-block-image) { margin-block-end: var(--md-single); }

<?php include md_css( 'headings', true ); ?>

/* LISTS */

.format ul { list-style: square; }

.list-check, .the-content :is(ul, ol) { margin-inline-start: var(--md-single); }

.format li, .list-check li:not(:last-child) {
	margin-block-end: var(--md-third);
	position: relative;
}

.format ul ul {
	margin-block-end: var(--md-half);
	margin-inline-start: var(--md-half);
}

.format .list { margin-inline-start: 0; }

ol.list, ul.list, .list-check, .format .list-check { list-style: none; }

.list-links li a,
.list li:not(:last-child),
.list > :is(ul, ol):not(:last-child) {
	border-block-end: 1px solid rgba(0, 0, 0, 0.15);
	padding-block-end: var(--md-half);
}

.list-links li { margin-block-end: 0; }

.list-links li a { display: block; }

.list-links li:last-child a { border-block-end: 0; }

.list-check li:before {
	background-color: rgba(0, 0, 0, 0.08);
	border-radius: 50%;
	color: var(--md-action-primary);
	margin-inline: -<?php echo $single + $small + 2; ?>px var(--md-third);
	padding: var(--md-small);
}

.text-center ul, .text-center ol, .text-center [class^="list"] { text-align: left; }

/* BLOCK/PULL QUOTES */

blockquote {
	--md-text: var(--md-content-box-text);
	--md-text-muted: var(--md-content-box-text-muted);
	--md-links: var(--md-content-box-links);
	--md-links-muted: var(--md-content-box-text-muted);
	--md-headlines: var(--md-content-box-text);
	--md-headline-links: var(--md-content-box-text);
	--md-border: var(--md-content-box-border);
	background-color: var(--md-content-box-background);
	border: 1px solid var(--md-border);
	border-inline-start-width: 7px;
	border-radius: var(--md-border-radius);
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
	color: var(--md-text-muted);
	display: block;
	font-style: italic;
	margin-inline: 0;
	padding: var(--md-single);
	position: relative;
}

blockquote:before, blockquote:after {
	color: #ddd;
	font-family: Georgia, serif;
	font-size: var(--md-huge);
	font-weight: var(--md-bold);
	position: absolute;
}

blockquote:before {
	content: open-quote;
	inset-inline-start: var(--md-small);
}

blockquote:after {
	content: close-quote;
	inset-inline-end: var(--md-half);
}

.wp-block-pullquote { text-align: center; }

blockquote.is-style-plain, .is-style-plain blockquote {
	background-color: transparent;
	border: 0;
	box-shadow: none;
	font-style: normal;
	padding: 0;
}

blockquote.is-style-plain:before, blockquote.is-style-plain:after,
.is-style-plain blockquote:before, .is-style-plain blockquote:after { content: ''; }

.format blockquote p { margin-block-end: var(--md-half); }

.format blockquote p + cite {
	display: block;
	margin-block-start: -<?php echo $half; ?>px;
}

@media (min-width: 700px) {
	:is(blockquote, .wp-block-pullquote):is(.alignleft, .alignright) { width: <?php echo ( $single * 6 ); ?>px; }
}

/* SLIM */

.slim {
	font-size: var(--md-font-size-sm);
	line-height: calc(var(--md-line-height-sm) - 1px);
}

.slim :is(ul, ol, p, hr, table, blockquote, pre) { margin-block-end: var(--md-half); }

.slim :is(.wp-caption, .wp-block-image),
.slim .the-content .featured-media { margin-block-end: var(--md-half); }

.slim :last-child { margin-block-end: 0; }

.slim .entry-title, .slim .title-wrap { row-gap: var(--md-small); }

.slim .entry-title .wrap { column-gap: var(--md-half); }

.slim .byline {
	font-size: calc(var(--md-font-size-sm) - 2px);
	line-height: calc(var(--md-line-height-sm) - 1px);
}

<?php if ( ! has_filter( 'md_filter_disable_format_fix' ) ) : ?>
.format *:last-child { margin-block-end: 0; }
<?php endif; ?>
