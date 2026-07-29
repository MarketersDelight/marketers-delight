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
?>

/* CLASSIC EDITOR */

.mce-content-body {
	background-color: <?php echo $colors['content']['body_color'] ?: $colors['palette']['background']; ?>;
	color: <?php echo $colors['palette']['text-main']; ?>;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: var(--md-font-size);
	line-height: var(--md-line-height);
	max-width: <?php echo $post_width; ?>px;
	margin-inline: auto;
}

@media (min-width: 783px) {
	.mce-content-body {
		--md-font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
		--md-line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
		--md-huge: <?php echo $typography['huge']['font_size']['desktop']; ?>px;
		--md-huge-line-height: <?php echo $typography['huge']['line_height']['desktop']; ?>px;
		--md-h1: <?php echo $typography['h1']['font_size']['desktop']; ?>px;
		--md-h1-line-height: <?php echo $typography['h1']['line_height']['desktop']; ?>px;
		--md-h2: <?php echo $typography['h2']['font_size']['desktop']; ?>px;
		--md-h2-line-height: <?php echo $typography['h2']['line_height']['desktop']; ?>px;
		--md-h3: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
		--md-h3-line-height: <?php echo $typography['h3']['line_height']['desktop']; ?>px;
		--md-h4: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
		--md-h4-line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
		--md-h5: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
		--md-h5-line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
		--md-h6: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
		--md-h6-line-height: <?php echo $typography['h6']['line_height']['desktop']; ?>px;
	}
}

.mce-content-body :is(h2, h3, h4, h5, h6):not(:first-child) { margin-block-start: <?php echo $mid; ?>px; }
