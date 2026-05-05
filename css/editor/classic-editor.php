<style type="text/css">

<?php if ( locate_template( 'css/fonts.php' ) )
	include locate_template( 'css/fonts.php' ); ?>

/*------------------------------*\
	$CLASSIC_EDITOR
\*------------------------------*/

/* ICONS */

@font-face {
	font-family: md-icon;
	font-display: swap;
	src: url('<?php echo md_font_icons_url(); ?>') format('woff');
	font-style: normal;
	font-weight: 400;
}

:is(.mce-content-body, .editor-styles-wrapper) [class*="md-icon"]:before {
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

:is(.mce-content-body, .editor-styles-wrapper) .md-icon.icon-data:before { content: attr(data-md-icon); }

<?php
include md_css( 'format', true );
include md_css( 'buttons', true );
include md_css( 'helpers', true );
?>

/* CLASSIC EDITOR */

.mce-content-body {
	background-color: <?php echo md_setting( array( 'content', 'style' ) ) == 'minimal' ? $colors['site']['bg_color'] : $colors['content']['bg_color']; ?>;
	color: <?php echo $colors['site']['text']; ?>;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
	max-width: <?php echo $post_width; ?>px;
	margin-inline: auto;
}

.mce-content-body :is(<?php echo $heading_selectors ?>):not(:first-child) { margin-block-start: <?php echo $mid; ?>px; }