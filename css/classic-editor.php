<style type="text/css">

/*------------------------------*\
	$CLASSIC_EDITOR
\*------------------------------*/

<?php
if ( locate_template( 'css/fonts.php' ) )
	include locate_template( 'css/fonts.php' );

include locate_template( 'css/--vars.php' );
include md_css( 'format', true );
include md_css( 'buttons', true );
include md_css( 'helpers', true );
include md_css( 'widgets', true );
include md_css( 'loop', true );
?>

/* CLASSIC EDITOR */

.mce-content-body {
	background-color: <?php echo $colors['content']['body_color'] ?: $colors['palette']['background']; ?>;
	color: <?php echo $colors['palette']['text-main']; ?>;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
	max-width: <?php echo $post_width; ?>px;
	margin-inline: auto;
}

.mce-content-body :is(h2, h3, h4, h5, h6):not(:first-child) { margin-block-start: <?php echo $mid; ?>px; }