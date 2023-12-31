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

.footer-columns {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.footer-columns + .footer-copy { background-color: rgba(0, 0, 0, 0.2); }

.footer-copy {
	border-top: 1px solid <?php echo $colors['footer']['border_color']; ?>;
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.footer a:not(.button) { color: <?php echo $colors['footer']['links']; ?> }

.footer .footer-title {
	color: <?php echo $colors['footer']['title']; ?>;
	<?php if ( ! empty( $typography['footer_title']['font_family'] ) ) : ?>
		font-family: <?php echo $typography['footer_title']['font_family']; ?>;
	<?php endif; ?>
	font-size: <?php echo $typography['footer_title']['font_size']['desktop']; ?>px;
	<?php if ( ! empty( $typography['footer_title']['font_weight'] ) ) : ?>
		font-weight: <?php echo $typography['footer_title']['font_weight']; ?>;
	<?php endif; ?>
	line-height: <?php echo $typography['footer_title']['line_height']['desktop']; ?>px;
	margin-bottom: <?php echo $half; ?>px;
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.footer-columns, .footer-copy {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (max-width: 900px) {
	.footer {
		font-size: <?php echo $typography['footer']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['footer']['line_height']['tablet']; ?>px;
	}
	.footer .col:not(:first-child) { margin-top: <?php echo $single; ?>px; }
	.footer-title {
		font-size: <?php echo $typography['footer_title']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['footer_title']['line_height']['tablet']; ?>px;
	}
}

@media all and (max-width: 700px) {
	.footer {
		font-size: <?php echo $typography['footer']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['footer']['line_height']['mobile']; ?>px;
	}
	.footer-title {
		font-size: <?php echo $typography['footer_title']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['footer_title']['line_height']['mobile']; ?>px;
	}
}
