<style type="text/css">

/*------------------------------*\
	$SIDEBAR
\*------------------------------*/

.sidebar {
	<?php echo ! empty( $sidebar['bg_color'] ) ? "\tbackground-color: " . $sidebar['bg_color'] . ';' : ''; ?>
	color: <?php echo $sidebar['text']; ?>;
	font-size: <?php echo $sidebar['font_size']['desktop']; ?>px;
	line-height: <?php echo $sidebar['line_height']['desktop']; ?>px;
	<?php echo ( ! empty( $sidebar['bg_color'] ) ? "padding: {$single}px;" : '' ); ?>
}

.sidebar a:not(.button) { color: <?php echo $sidebar['links']; ?>; }

.sidebar .sidebar-title {
	color: <?php echo $sidebar['title']; ?>;
	<?php if ( ! empty( $sidebar['sidebar_title']['font_family'] ) ) : ?>
		font-family: <?php echo $sidebar['sidebar_title']['font_family']; ?>;
	<?php endif; ?>
	font-size: <?php echo $sidebar['sidebar_title']['font_size']['desktop']; ?>px;
	<?php if ( ! empty( $sidebar['sidebar_title']['font_weight'] ) ) : ?>
		font-weight: <?php echo $sidebar['sidebar_title']['font_weight']; ?>;
	<?php endif; ?>
	line-height: <?php echo $sidebar['sidebar_title']['line_height']['desktop']; ?>px;
}

@media all and (max-width: 900px) {
	.sidebar {
		font-size: <?php echo $sidebar['font_size']['tablet']; ?>px;
		line-height: <?php echo $sidebar['line_height']['tablet']; ?>px;
	}
	.sidebar-title {
		font-size: <?php echo $sidebar['sidebar_title']['font_size']['tablet']; ?>px;
		line-height: <?php echo $sidebar['sidebar_title']['line_height']['tablet']; ?>px;
	}
}

@media all and (max-width: 700px) {
	.sidebar {
		font-size: <?php echo $sidebar['font_size']['mobile']; ?>px;
		line-height: <?php echo $sidebar['line_height']['mobile']; ?>px;
	}
	.sidebar-title {
		font-size: <?php echo $sidebar['sidebar_title']['font_size']['mobile']; ?>px;
		line-height: <?php echo $sidebar['sidebar_title']['line_height']['mobile']; ?>px;
	}
}
