<style type="text/css">

/*------------------------------*\
	$FOOTER
\*------------------------------*/

.sidebar {
	<?php echo ! empty( $colors['sidebar']['bg_color'] ) ? "\tbackground-color: " . $colors['sidebar']['bg_color'] . ';' : ''; ?>
	color: <?php echo $colors['sidebar']['text']; ?>;
	font-size: <?php echo $typography['sidebar']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['sidebar']['line_height']['desktop']; ?>px;
	<?php echo ( ! empty( $colors['sidebar']['bg_color'] ) ? "padding: {$single}px;" : '' ); ?>
}

.sidebar a:not(.button) { color: <?php echo $colors['sidebar']['links']; ?>; }

.sidebar .sidebar-title, .sidebar h2 {
	color: <?php echo $colors['sidebar']['title']; ?>;
	<?php if ( ! empty( $typography['sidebar_title']['font_family'] ) ) : ?>
		font-family: <?php echo $typography['sidebar_title']['font_family']; ?>;
	<?php endif; ?>
	font-size: <?php echo $typography['sidebar_title']['font_size']['desktop']; ?>px;
	<?php if ( ! empty( $typography['sidebar_title']['font_weight'] ) ) : ?>
		font-weight: <?php echo $typography['sidebar_title']['font_weight']; ?>;
	<?php endif; ?>
	line-height: <?php echo $typography['sidebar_title']['line_height']['desktop']; ?>px;
}

@media all and (max-width: 900px) {
	.sidebar {
		font-size: <?php echo $typography['sidebar']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['sidebar']['line_height']['tablet']; ?>px;
	}
	.sidebar-title, .sidebar h2 {
		font-size: <?php echo $typography['sidebar_title']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['sidebar_title']['line_height']['tablet']; ?>px;
	}
}

@media all and (max-width: 700px) {
	.sidebar {
		font-size: <?php echo $typography['sidebar']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['sidebar']['line_height']['mobile']; ?>px;
	}
	.sidebar-title, .sidebar h2 {
		font-size: <?php echo $typography['sidebar_title']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['sidebar_title']['line_height']['mobile']; ?>px;
	}
}