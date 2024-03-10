<style type="text/css">

/*------------------------------*\
	$FOOTER
\*------------------------------*/

.footer {
	background-color: <?php echo $colors['footer']['bg_color']; ?>;
	color: <?php echo $colors['footer']['text']; ?>;
	font-size: <?php echo $typography['footer']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['footer']['line_height']['desktop']; ?>px;
	position: relative;
}

.footer .columns {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.footer-copy {
	border-top: 1px solid <?php echo $colors['footer']['border_color']; ?>;
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
	text-align: center;
}

.footer a:not(.button) { color: <?php echo $colors['footer']['links']; ?> }

.footer .widget-title { color: <?php echo $colors['footer']['title']; ?>; }

<?php foreach ( $queries as $w => $d ) :
if ( ! empty( $typography['footer']['font_size'][$d] ) || ! empty( $typography['footer']['line_height'][$d] ) ) : ?>
@media all and (max-width: <?php echo $w; ?>px) {
	.footer {
		<?php echo ( ! empty( $typography['footer']['font_size'][$d] ) ? 'font-size: ' . $typography['footer']['font_size'][$d] . 'px; ' : '' ); ?>
		<?php echo ( ! empty( $typography['footer']['line_height'][$d] ) ? 'line-height: ' . $typography['footer']['line_height'][$d] . 'px; ' : '' ); ?>
	}
}
<?php endif; endforeach; ?>
