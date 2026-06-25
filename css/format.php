<style type="text/css">

/*------------------------------*\
	$FORMAT
\*------------------------------*/

/* ATTRIBUTES */

a {
	color: <?php echo $colors['site']['links']; ?>;
	text-decoration: underline;
}

a:hover { text-decoration: none; }

strong, b { font-weight: <?php echo $bold; ?>; }

img, a img {
	height: auto;
	max-width: 100%;
	vertical-align: top;
}

iframe, video, object { max-width: 100%; }

abbr { cursor: help; }

cite { color: <?php echo $colors['site']['text-sec']; ?>; }

sup { line-height: 1; }

hr {
	border: 0;
	border-block-start: 1px solid rgba(0, 0, 0, 0.1);
	border-block-end: 1px solid rgba(255, 255, 255, 0.3);
	height: 0;
}

code {
	border-radius: 6px;
	padding: 2px 5px;
}

pre {
	margin-block-end: <?php echo $single; ?>px;
	overflow: auto;
	padding: <?php echo $single; ?>px;
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
.format :is(.wp-caption, .wp-block-image) { margin-block-end: <?php echo $single; ?>px; }

/* HEADINGS */

<?php echo implode( ', ', array_values( $headings ) ) . " { color: {$colors['site']['headline']}; font-family: $h1_font_family; font-weight: $h1_font_weight; }\n";

foreach ( $headings as $attribute => $selector ) {
	$combined = isset( $heading_sizes[$attribute] ) ? "$selector, {$heading_sizes[$attribute]}" : $selector;

	$overrides = '';

	if ( ! empty( $typography[$attribute]['font_family'] ) && $typography[$attribute]['font_family'] !== $h1_font_family )
		$overrides .= 'font-family: ' . $typography[$attribute]['font_family'] . ";\n";

	if ( ! empty( $typography[$attribute]['font_weight'] ) && $typography[$attribute]['font_weight'] !== $h1_font_weight )
		$overrides .= 'font-weight: ' . $typography[$attribute]['font_weight'] . ";\n";

	echo "$combined { font-size: " . $this->fluid( $typography[$attribute]['font_size']['desktop'], $typography[$attribute]['font_size']['mobile'] ?? null ) . "; line-height: " . $this->fluid( $typography[$attribute]['line_height']['desktop'], $typography[$attribute]['line_height']['mobile'] ?? null ) . "; }\n";

	if ( $overrides )
		echo "$selector {\n{$overrides}}\n";
}

foreach ( array_diff_key( $heading_sizes, $headings ) as $attribute => $selector ) {
	echo "$selector { font-size: " . $this->fluid( $typography[$attribute]['font_size']['desktop'], $typography[$attribute]['font_size']['mobile'] ?? null ) . "; line-height: " . $this->fluid( $typography[$attribute]['line_height']['desktop'], $typography[$attribute]['line_height']['mobile'] ?? null ) . "; }\n";
} ?>

:is(<?php echo $heading_selectors ?>) a {
	color: <?php echo $colors['site']['headline-links']; ?>;
	text-decoration: none;
}

:is(<?php echo $heading_selectors ?>) a:hover { text-decoration: underline; }

.format h1, .format h2, .format h3, .format h4, .format h5, .format h6 { margin-block-end: <?php echo $half; ?>px; }

.format :is(<?php echo $heading_selectors; ?>):is(.alignwide, .alignfull) { text-align: center; }

.the-content :is(<?php echo $heading_selectors ?>):not(:first-child) { margin-block-start: <?php echo $mid; ?>px; }


/* LISTS */

.format ul { list-style: square; }

.list-check,
.the-content :is(ul, ol) { margin-inline-start: <?php echo $single; ?>px; }

.format li, .list-check li:not(:last-child) {
	margin-block-end: <?php echo $third; ?>px;
	position: relative;
}

.format ul ul {
	margin-block-end: <?php echo $half; ?>px;
	margin-inline-start: <?php echo $half; ?>px;
}

.format .list { margin-inline-start: 0; }

.list, .list-check,
.format .list, .format [class*="list-"] { list-style: none; }

.list-links li a,
.list li:not(:last-child),
.list > :is(ul, ol):not(:last-child) {
	border-block-end: 1px solid rgba(0, 0, 0, 0.15);
	padding-block-end: <?php echo $half; ?>px;
}

.list-links li { margin-block-end: 0; }

.list-links li a { display: block; }

.list-links li:last-child a { border-block-end: 0; }

.list-check li:before {
	background-color: rgba(0, 0, 0, 0.08);
	border-radius: 50%;
	color: #22a340;
	margin-inline: -<?php echo $single + $small + 2; ?>px <?php echo $third; ?>px;;
	padding: <?php echo $small; ?>px;
}

.text-center ul, .text-center ol, .text-center [class^="list"] { text-align: left; }

/* BLOCK/PULL QUOTES */

blockquote {
	background-color: #fff;
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-inline-start-width: 7px;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
	color: <?php echo $colors['site']['text-sec']; ?>;
	display: block;
	font-style: italic;
	margin-inline: 0;
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
	inset-inline-start: <?php echo $small; ?>px;
}

blockquote:after {
	content: close-quote;
	inset-inline-end: <?php echo $half; ?>px;
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

.format blockquote p { margin-block-end: <?php echo $half; ?>px; }

.format blockquote p + cite {
	display: block;
	margin-block-start: -<?php echo $half; ?>px;
}

@media (min-width: 900px) {
	:is(blockquote, .wp-block-pullquote):is(.alignleft, .alignright) { width: <?php echo ( $single * 6 ); ?>px; }
}

/* SLIM */

.slim {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile'] - 1; ?>px;
}

.slim ul, .slim ol, .slim p, .slim hr,
.slim table, .slim blockquote, .slim pre, .slim .wp-caption,
.slim .wp-block-image, .slim .the-content .featured-media { margin-block-end: <?php echo $half; ?>px; }

.slim :last-child { margin-block-end: 0; }

.slim .entry-title, .slim .title-wrap { row-gap: <?php echo $small; ?>px; }

.slim .entry-title .wrap { column-gap: <?php echo $half; ?>px; }

.slim .byline {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile'] - 1; ?>px;
}

<?php if ( ! has_filter( 'md_filter_disable_format_fix' ) ) : ?>
.format *:last-child { margin-block-end: 0; }
<?php endif; ?>