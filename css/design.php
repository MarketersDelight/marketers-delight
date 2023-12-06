<style type="text/css">

<?php
	$cover_image_id = md_setting( array( 'colors', 'header', 'cover_image', 'id' ) );
	$header_colors = array(
		'default' => array(
			'class' => '',
			'color' => ( ! empty( $colors['page_cover']['cover_styles']['text_color'] ) ? $colors['site']['headline'] : '#fff' ),
			'border' => ( ! empty( $colors['page_cover']['cover_styles']['text_color'] ) ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.3)' )
		),
		'alt' => array(
			'class' => '.text-alt',
			'color' => ( empty( $colors['page_cover']['cover_styles']['text_color'] ) ? $colors['site']['headline'] : '#fff' ),
			'border' => ( empty( $colors['page_cover']['cover_styles']['text_color'] ) ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.3)' )
		)
	);
?>

/*------------------------------*\
	$DESIGN
\*------------------------------*/

/* OVERLAY */

.image-overlay {
	background-position: center top;
	background-size: cover;
	display: block;
	position: relative;
	z-index: 0;
}

.image-overlay:after { z-index: -1; }

.overlay, .image-overlay:after {
	background-color: <?php echo $colors['page_cover']['cover_color']; ?>;
	content: '';
	display: block;
	height: 100%;
	position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		top: 0;
	width: 100%;
}

/* SHADOWS */

.shadow, .wp-block-image.shadow img { box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2); }

.shadow-large, .wp-block-image.shadow-large img { box-shadow: 0 5px 55px rgba(0, 0, 0, 0.15); }

.shadow-small, .wp-block-image.shadow-small img { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15); }

.wp-block-image.shadow, .wp-block-image.shadow-large, .wp-block-image.shadow-small { box-shadow: none; }

/* BOXES */

.frame, .note { background-color: #eee; }

.alert { background-color: #fffbcc; }

.sep { border-bottom: 1px solid rgba(0, 0, 0, 0.2); }

.highlight {
	background-color: #fdd169;
	padding-left: <?php echo $small; ?>px;
	padding-right: <?php echo $small; ?>px;
}

/* CIRCLE ICON */

.circle { border-radius: 50%; }

.circle-icon, a.circle-icon, .toc-anchor {
	align-items: center;
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 50%;
	color: <?php echo $colors['site']['text']; ?>;
	display: inline-flex;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	font-weight: normal;
	height: <?php echo $mid; ?>px;
	justify-content: center;
	line-height: 1;
	position: relative;
	width: <?php echo $mid; ?>px;
}

.circle-icon.micro, .toc-anchor {
	height: <?php echo $single; ?>px;
	width: <?php echo $single; ?>px;
}

/* AVATAR */

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* COVERS */

.cover, .header.has-cover {
	background-position: center center;
	<?php if ( ! empty( $cover_image_id ) ) :
		$cover_image = wp_get_attachment_image_src( $cover_image_id );
	?>
	background-size: <?php echo $cover_image[1] < 500 ? 'auto' : 'cover'; ?>;
	<?php else : ?>
	background-size: cover;
	<?php endif; ?>
	position: relative;
}

.header.has-cover {
	<?php echo ( ! empty( $colors['header']['cover_image']['url'] ) ? 'background-image: url(\'' . esc_url( $colors['header']['cover_image']['url'] ) . '\'); ': '' ); ?>
	padding-bottom: 0;
}

<?php foreach ( $header_colors as $text_class => $text_atts ) :
	$text_class = $text_atts['class'];
?>

.header.has-cover<?php echo $text_class; ?>,
.header.has-cover<?php echo $text_class; ?> .site-title,
.header.has-cover<?php echo $text_class; ?> .site-title:hover,
.header.has-cover<?php echo $text_class; ?> .tagline,
.header.has-cover<?php echo $text_class; ?> .header-aside a,
.header.has-cover<?php echo $text_class; ?> .header-triggers a,
.header.has-cover<?php echo $text_class; ?> .menu > .menu-item > a,
.header.has-cover<?php echo $text_class; ?> .trigger-icon,
.header.has-cover<?php echo $text_class; ?> .trigger-text,
.cover<?php echo $text_class; ?>,
.cover<?php echo $text_class; ?> .title a,
.cover<?php echo $text_class; ?> .byline,
.cover<?php echo $text_class; ?> a {
	color: <?php echo esc_attr( $text_atts['color'] ); ?>;
}

.cover<?php echo $text_class; ?> .byline-alt,
.header.has-cover<?php echo $text_class; ?> .menu-header > .menu-item:not(:last-child),
.cover<?php echo $text_class; ?> .author-link { border-bottom-color: <?php echo $text_atts['border']; ?>; }

.header.has-cover<?php echo $text_class; ?> .menu > .menu-item:hover > .menu-toggle { background-color: <?php echo $text_atts['border']; ?>; }

<?php endforeach; ?>

@media all and (max-width: 800px) {
	.header.has-cover<?php echo $header_colors['default']['class']; ?> .sub-menu .toggle-menu,
	.header.has-cover<?php echo $header_colors['default']['class']; ?> .menu .menu-toggle { background-color: <?php echo $header_colors['default']['border']; ?>; }
	.header.has-cover<?php echo $header_colors['alt']['class']; ?> .sub-menu .toggle-menu,
	.header.has-cover<?php echo $header_colors['alt']['class']; ?> .menu .menu-toggle { background-color: <?php echo $header_colors['alt']['border']; ?>; }
	.header.has-cover<?php echo $header_colors['default']['class']; ?> .sub-menu > .menu-item > a { color: <?php echo $header_colors['default']['color']; ?>; }
	.header.has-cover<?php echo $header_colors['alt']['class']; ?> .sub-menu > .menu-item > a { color: <?php echo $header_colors['alt']['color']; ?>; }
}

/* BOUNCE */

@keyframes bounce {
	0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
	40% { transform: translateY(-7px); }
	60% { transform: translateY(-4px); }
}

.bounce { animation: bounce 3s infinite; }

/* SPIN */

@keyframes spin {
	from { transform: rotate(0deg); }
	to { transform: rotate(360deg); }
}

.spin, .button-loading .md-icon-loading { animation: spin 2s linear infinite; }

.button-loading .md-icon-loading { display: none; }

.is-loading .md-icon-loading {
	display: inline-block;
	margin-left: <?php echo $small; ?>px;
}

/* ICONS */

<?php foreach ( md_icons() as $icon => $fields ) {
	if ( ! isset( $fields['unicode'] ) ) continue;
	$selectors = '';
	if ( isset( $fields['classes'] ) )
		foreach ( $fields['classes'] as $selector )
			$selectors .= ",{$selector}:before";
	echo '.md-icon-' . $icon . ":before{$selectors}{content:'\\" . $fields['unicode'] . '\'}';
} ?>
