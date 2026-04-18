<style type="text/css">

/*------------------------------*\
	$BLOCK_EDITOR
\*------------------------------*/

/* ICONS */

@font-face {
	font-family: md-icon;
	font-display: swap;
	src: url('<?php echo md_font_icons_url(); ?>') format('woff');
	font-style: normal;
	font-weight: 400;
}

[class*="md-icon"]:before {
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

.md-icon.icon-data:before { content: attr(data-md-icon); }

/* LISTS */

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

/* BUTTONS */

.editor-styles-wrapper :is(button, .button, input[type="submit"], .wp-block-button .wp-element-button) {
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