<style type="text/css">

/*------------------------------*\
	$CLASSIC_EDITOR
\*------------------------------*/

<?php
if ( locate_template( 'css/fonts.php' ) )
	include locate_template( 'css/fonts.php' );

include md_css( 'format', true );
include md_css( 'buttons', true );
include md_css( 'helpers', true );
include md_css( 'layout', true );
?>

/* CLASSIC EDITOR */

.mce-content-body {
	background-color: <?php echo $colors['content']['body_color'] ?: $colors['site']['bg_color']; ?>;
	color: <?php echo $colors['site']['text']; ?>;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
	max-width: <?php echo $post_width; ?>px;
	margin-inline: auto;
}

.mce-content-body :is(<?php echo $heading_selectors ?>):not(:first-child) { margin-block-start: <?php echo $mid; ?>px; }