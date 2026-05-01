<style type="text/css">

/*------------------------------*\
	$CLASSIC_EDITOR
\*------------------------------*/

<?php if ( locate_template( 'css/fonts.php' ) )
	include locate_template( 'css/fonts.php' ); ?>

/* ICONS */

@font-face {
	font-family: md-icon;
	font-display: swap;
	src: url('<?php echo md_font_icons_url(); ?>') format('woff');
	font-style: normal;
	font-weight: 400;
}

.editor-styles-wrapper [class*="md-icon"]:before,
.mce-content-body [class*="md-icon"]:before {
	display: inline-block;
	font-family: md-icon;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 1;
	text-align: center;
	text-decoration: inherit;
	text-transform: none;
}

.editor-styles-wrapper .md-icon.icon-data:before,
.mce-content-body .md-icon.icon-data:before { content: attr(data-md-icon); }

.mce-content-body {
	background-color: <?php echo md_setting( array( 'content', 'style' ) ) == 'minimal' ? $colors['site']['bg_color'] : $colors['content']['bg_color']; ?>;
	color: <?php echo $colors['site']['text']; ?>;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
	max-width: <?php echo $post_width; ?>px;
	margin: 0 auto;
	padding: <?php echo $half; ?>px;
}

.mce-content-body a { color: <?php echo $colors['site']['links']; ?>; }

.mce-content-body :is(h1, h2, h3, h4, h5, h6) {
	color: <?php echo $colors['site']['headline']; ?>;
	font-family: <?php echo ! empty( $typography['h1']['font_family'] ) ? $typography['h1']['font_family'] : $typography['body']['font_family']; ?>;
	font-weight: <?php echo ! empty( $typography['h1']['font_weight'] ) ? $typography['h1']['font_weight'] : ( ! empty( $typography['body']['bold'] ) ? $typography['body']['bold'] : '700' ); ?>;
	margin-block-end: <?php echo $half; ?>px;
}

.mce-content-body h1 {
	font-size: <?php echo $typography['h1']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h1']['line_height']['desktop']; ?>px;
}

.mce-content-body h2 {
	font-size: <?php echo $typography['h2']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h2']['line_height']['desktop']; ?>px;
}

.mce-content-body h3 {
	font-size: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h3']['line_height']['desktop']; ?>px;
}

.mce-content-body h4 {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
}

.mce-content-body h5 {
	font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
}

.mce-content-body h6 {
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h6']['line_height']['desktop']; ?>px;
}

.mce-content-body :is(ul, ol, p, hr, table, blockquote, pre) { margin-block-end: <?php echo $single; ?>px; }

.mce-content-body ul { list-style: square; }

.mce-content-body li {
	margin-block-end: <?php echo $half; ?>px;
	position: relative;
}

.mce-content-body ul ul {
	margin-block-end: <?php echo $half; ?>px;
	margin-inline-start: <?php echo $half; ?>px;
}

.mce-content-body :is(button, .button, input[type="submit"]) {
	align-items: center;
	appearance: none;
	background-color: <?php echo $colors['site']['button']; ?>;
	border: 0;
	border-radius: 6px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	color: <?php echo $colors['site']['button-text']; ?>;
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

/* BLOCKQUOTE */

.mce-content-body blockquote {
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

.mce-content-body blockquote:before, .mce-content-body blockquote:after {
	color: #ddd;
	font-family: Georgia, serif;
	font-size: <?php echo $typography['huge']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	line-height: 1;
	position: absolute;
}

.mce-content-body blockquote:before {
	content: open-quote;
	inset-block-start: <?php echo $third; ?>px;
	inset-inline-start: <?php echo $third; ?>px;
}

.mce-content-body blockquote:after {
	content: close-quote;
	inset-inline-end: <?php echo $third; ?>px;
}

.mce-content-body blockquote p { margin: 0; }

.mce-content-body blockquote p:not(:last-child) { margin-block-end: <?php echo $half; ?>px; }

.mce-content-body blockquote.alignright,
.mce-content-body blockquote.alignleft { width: <?php echo ( $single * 6 ); ?>px; }

<?php
include md_css( 'format', true );
include md_css( 'helpers', true );
include md_css( 'buttons', true );