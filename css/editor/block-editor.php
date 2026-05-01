<style type="text/css">

/*------------------------------*\
	$BLOCK_EDITOR
\*------------------------------*/

/* BUTTONS */

.editor-styles-wrapper :is(button, .button, input[type="submit"],
.wp-block-button .wp-element-button) {
	align-items: center;
	appearance: none;
	display: inline-flex;
	font-family: inherit;
	font-size: inherit;
	font-style: normal;
	gap: <?php echo $third; ?>px <?php echo $half; ?>px;
	justify-content: center;
	line-height: 1;
	padding: <?php echo $half; ?>px <?php echo $half + $third; ?>px;
	text-decoration: none;
}

/* TYPOGRAPHY */

.editor-styles-wrapper {
	background-color: <?php echo md_setting( array( 'content', 'style' ) ) == 'minimal' ? $colors['site']['bg_color'] : $colors['content']['bg_color']; ?>;
	color: <?php echo $colors['site']['text']; ?>;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
}

.editor-styles-wrapper :is(b, strong) { font-weight: <?php echo $bold; ?>; }

.editor-styles-wrapper a { color: <?php echo $colors['site']['links']; ?>; }

.editor-styles-wrapper :is(h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6, .huge, .wp-block-heading) {
	color: <?php echo $colors['site']['headline']; ?>;
	font-family: <?php echo ! empty( $typography['h1']['font_family'] ) ? $typography['h1']['font_family'] : $typography['body']['font_family']; ?>;
	font-weight: <?php echo ! empty( $typography['h1']['font_weight'] ) ? $typography['h1']['font_weight'] : ( ! empty( $typography['body']['bold'] ) ? $typography['body']['bold'] : '700' ); ?>;
}

.editor-styles-wrapper :is(h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6, .huge):is(.alignwide, .alignfull) { text-align: center; }

.editor-styles-wrapper .huge.wp-block {
	font-size: <?php echo $typography['huge']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['huge']['line_height']['desktop']; ?>px;
}

<?php foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) as $h ) : ?>
.editor-styles-wrapper <?php echo $h; ?>, .editor-styles-wrapper .<?php echo $h; ?> {
	font-size: <?php echo $typography[$h]['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography[$h]['line_height']['desktop']; ?>px;
}
<?php endforeach; ?>

/* SPACING */

.editor-styles-wrapper .wp-block-post-content > .wp-block { margin-block-start: 0; }

.editor-styles-wrapper .wp-block-post-content > .wp-block:not([class*="mb-"]) { margin-block-end: <?php echo $single; ?>px; }

.editor-styles-wrapper .wp-block-post-content .wp-block-heading:not(:first-child) { margin-block-start: <?php echo $mid; ?>px; }

.editor-styles-wrapper .wp-block-post-content .wp-block-heading:not([class*="mb-"]):not(:last-child) { margin-block-end: <?php echo $half; ?>px; }

.editor-styles-wrapper .is-layout-flow > .alignleft { margin-inline-end: <?php echo $single; ?>px; }

.editor-styles-wrapper .is-layout-flow > .alignright { margin-inline: <?php echo $single; ?>px 0; }

/* ATTRIBUTES */

.editor-styles-wrapper :is(pre, code) {
	background-color: rgba(0, 0, 0, 0.1);
	color: #3e3e3e;
	font-family: Consolas, Monaco, Menlo, Courier, Verdana, sans-serif;
	font-size: 0.9em;
}

.editor-styles-wrapper pre {
	margin-block-end: <?php echo $single; ?>px;
	overflow: auto;
	padding: <?php echo $single; ?>px;
}

.editor-styles-wrapper code {
	border-radius: 5px;
	padding: 2px 5px;
}

.editor-styles-wrapper :is(ul, ol) { margin-block-end: <?php echo $single; ?>px; }

.editor-styles-wrapper ul { list-style: square; }

.editor-styles-wrapper :is(ul[class^="list"], [class^="list"] ul) { list-style: none; }

.editor-styles-wrapper li {
	margin-block-end: <?php echo $half; ?>px;
	position: relative;
}

.editor-styles-wrapper ul ul {
	margin-block-end: <?php echo $half; ?>px;
	margin-inline-start: <?php echo $half; ?>px;
}

.editor-styles-wrapper .wp-block-post-content :is(ul, ol, .list-check) { margin-inline-start: <?php echo $single; ?>px; }

/* BLOCKQUOTE / PULLQUOTE */

.editor-styles-wrapper .wp-block-quote:not(.is-style-plain),
.editor-styles-wrapper .wp-block-pullquote:not(.is-style-plain) blockquote {
	background-color: #fff;
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-inline-start-width: 7px;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
	color: <?php echo $colors['site']['text-sec']; ?>;
	display: block;
	font-style: italic;
	padding: <?php echo $single; ?>px;
	position: relative;
}

.editor-styles-wrapper .wp-block-quote:not(.is-style-plain):before,
.editor-styles-wrapper .wp-block-quote:not(.is-style-plain):after,
.editor-styles-wrapper .wp-block-pullquote:not(.is-style-plain) blockquote:before,
.editor-styles-wrapper .wp-block-pullquote:not(.is-style-plain) blockquote:after {
	color: #ddd;
	font-family: Georgia, serif;
	font-size: <?php echo $typography['huge']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	line-height: 1;
	position: absolute;
}

.editor-styles-wrapper .wp-block-quote:not(.is-style-plain):before,
.editor-styles-wrapper .wp-block-pullquote:not(.is-style-plain) blockquote:before {
	content: open-quote;
	inset-block-start: <?php echo $third; ?>px;
	inset-inline-start: <?php echo $third; ?>px;
}

.editor-styles-wrapper .wp-block-quote:not(.is-style-plain):after,
.editor-styles-wrapper .wp-block-pullquote:not(.is-style-plain) blockquote:after {
	content: close-quote;
	inset-inline-end: <?php echo $third; ?>px;
}

.editor-styles-wrapper .wp-block-quote p,
.editor-styles-wrapper .wp-block-pullquote p { margin-block: 0 <?php echo $half; ?>px; }

.editor-styles-wrapper .wp-block-quote p:last-child,
.editor-styles-wrapper .wp-block-pullquote p:last-child { margin-block-end: 0; }

.editor-styles-wrapper .wp-block-quote.alignright,
.editor-styles-wrapper .wp-block-quote.alignleft { width: <?php echo ( $single * 6 ); ?>px; }

.editor-styles-wrapper .wp-block-pullquote {
	font-size: inherit;
	line-height: inherit;
	margin: 0;
	padding: 0;
	text-align: left;
}

.editor-styles-wrapper .wp-block-pullquote:is(.alignleft, .alignright) { width: <?php echo ( $single * 6 ); ?>px; }

.editor-styles-wrapper .wp-block-pullquote p {
	color: <?php echo $colors['site']['text']; ?>;
	font-style: normal;
	font-weight: <?php echo $bold; ?>;
}

.editor-styles-wrapper .wp-block-pullquote cite { color: <?php echo $colors['site']['text-sec']; ?>; }

.editor-styles-wrapper .wp-block-pullquote p + cite { margin-block-start: -<?php echo $half; ?>px; }

/* MD BLOCKS */

.editor-styles-wrapper .banner-wrap p { margin-block: 0; }