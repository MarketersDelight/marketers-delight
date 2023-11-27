<style type="text/css">

/*------------------------------*\
	$HEADER
\*------------------------------*/

.header {
	background-color: <?php echo $header['bg_color']; ?>;
	<?php if ( ! empty( $header['color'] ) ) : ?>
	color: <?php echo $header['color']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $header['font_family'] ) ) : ?>
	font-family: <?php echo $header['font_family']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $header['font_size']['desktop'] ) ) : ?>
	font-size: <?php echo $header['font_size']['desktop']; ?>px;
	<?php endif; ?>
	<?php if ( ! empty( $header['font_weight'] ) ) : ?>
	font-weight: <?php echo $header['font_weight']; ?>;
	<?php endif; ?>
	<?php if ( ! empty( $header['line_height']['desktop'] ) ) : ?>
	line-height: <?php echo $header['line_height']['desktop']; ?>px;
	<?php endif; ?>
	position: relative;
}

.header a { color: <?php echo $header['menu']['links']; ?>; }

.header a:hover { color: <?php echo $header['menu']['hover']; ?>; }



/* QUERIES */

@media all and (min-width: 800px) {
	.header-triggers { display: none; }
}
