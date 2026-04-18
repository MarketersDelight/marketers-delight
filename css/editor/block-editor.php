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

.editor-styles-wrapper a { color: <?php echo $colors['site']['links']; ?>; }

.editor-styles-wrapper :is(h1, h2, h3, h4, h5, h6, .wp-block-heading) {
	color: <?php echo $colors['site']['headline']; ?>;
	font-family: <?php echo ! empty( $typography['h1']['font_family'] ) ? $typography['h1']['font_family'] : $typography['body']['font_family']; ?>;
	font-weight: <?php echo ! empty( $typography['h1']['font_weight'] ) ? $typography['h1']['font_weight'] : ( ! empty( $typography['body']['bold'] ) ? $typography['body']['bold'] : '700' ); ?>;
}

.editor-styles-wrapper h1 {
	font-size: <?php echo $typography['h1']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h1']['line_height']['desktop']; ?>px;
}

.editor-styles-wrapper h2 {
	font-size: <?php echo $typography['h2']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h2']['line_height']['desktop']; ?>px;
}

.editor-styles-wrapper h3 {
	font-size: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h3']['line_height']['desktop']; ?>px;
}

.editor-styles-wrapper h4 {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
}

.editor-styles-wrapper h5 {
	font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
}

.editor-styles-wrapper h6 {
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h6']['line_height']['desktop']; ?>px;
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