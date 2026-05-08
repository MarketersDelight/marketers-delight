<style type="text/css">

/*------------------------------*\
	$BLOCK_EDITOR
\*------------------------------*/

<?php
	include md_css( 'format', true );
	include md_css( 'buttons', true );
	include md_css( 'helpers', true );
	include md_css( 'layout', true );
?>

/* TYPOGRAPHY */

.editor-styles-wrapper {
	background-color: <?php echo md_setting( array( 'content', 'style' ) ) == 'minimal' ? $colors['site']['bg_color'] : $colors['content']['bg_color']; ?>;
	color: <?php echo $colors['site']['text']; ?>;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
}

.edit-post-visual-editor__post-title-wrapper { margin-block-end: <?php echo $mid; ?>px; }

/* SPACING */

.editor-styles-wrapper .wp-block-post-content :is(ul, ol, .list-check) { margin-inline-start: <?php echo $quad; ?>px; }

.editor-styles-wrapper .wp-block-post-content > .wp-block { margin-block-start: 0; }

.editor-styles-wrapper .wp-block-post-content > .wp-block:not([class*="mb-"]) { margin-block-end: <?php echo $single; ?>px; }

.editor-styles-wrapper .wp-block-post-content .wp-block-heading:not([class*="mb-"]):not(:last-child) { margin-block-end: <?php echo $half; ?>px; }

.editor-styles-wrapper .wp-block-post-content .wp-block-heading:not(:first-child) { margin-block-start: <?php echo $mid; ?>px; }

.editor-styles-wrapper .is-layout-flow > .alignleft { margin-inline-end: <?php echo $single; ?>px; }

.editor-styles-wrapper .is-layout-flow > .alignright { margin-inline: <?php echo $single; ?>px 0; }

/* BLOCK/PULL QUOTES */

.editor-styles-wrapper .wp-block-pullquote {
	font-size: inherit;
	padding: 0;
}

.editor-styles-wrapper .wp-block-quote p,
.editor-styles-wrapper .wp-block-pullquote p { margin-block: 0 <?php echo $half; ?>px; }

.editor-styles-wrapper .wp-block-quote p:last-child,
.editor-styles-wrapper .wp-block-pullquote p:last-child { margin-block-end: 0; }

/* MD BLOCKS */

.editor-styles-wrapper .banner-wrap p { margin-block: 0; }
.editor-styles-wrapper .feature-title { margin-block-start: 0; }