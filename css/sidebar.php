<style type="text/css">

/*------------------------------*\
	$SIDEBARS
\*------------------------------*/

.sidebar {
	<?php echo ! empty( $colors['sidebar']['bg_color'] ) ? "\tbackground-color: " . $colors['sidebar']['bg_color'] . ';' : ''; ?>
	color: <?php echo $colors['sidebar']['text']; ?>;
	font-size: <?php echo $typography['sidebar']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['sidebar']['line_height']['desktop']; ?>px;
	<?php echo ( ! empty( $colors['sidebar']['bg_color'] ) ? "padding: {$single}px;" : '' ); ?>
}

.sidebar a { color: <?php echo $colors['sidebar']['links']; ?>; }

.sidebar .widget-title { color: <?php echo $colors['sidebar']['title']; ?>; }

.sidebar .widget-title a { color: <?php echo $colors['sidebar']['title_link']; ?>; }

<?php foreach ( $queries as $w => $d ) :
if ( ! empty( $typography['sidebar']['font_size'][$d] ) || ! empty( $typography['sidebar']['line_height'][$d] ) ) : ?>
@media all and (max-width: <?php echo $w; ?>px) {
	.sidebar {
		<?php echo ( ! empty( $typography['sidebar']['font_size'][$d] ) ? 'font-size: ' . $typography['sidebar']['font_size'][$d] . 'px; ' : '' ); ?>
		<?php echo ( ! empty( $typography['sidebar']['line_height'][$d] ) ? 'line-height: ' . $typography['sidebar']['line_height'][$d] . 'px; ' : '' ); ?>
	}
}
<?php endif; endforeach; ?>
