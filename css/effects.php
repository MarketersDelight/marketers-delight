<style type="text/css">

<?php
	$cover_image_id = md_setting( array( 'colors', 'header', 'cover_image', 'id' ) );
	$header_colors = array(
		'default' => array(
			'class' => '',
			'color' => ( ! empty( $content['page_cover']['styles']['text_color'] ) ? $colors['site']['headline'] : '#fff' ),
			'border' => ( ! empty( $content['page_cover']['styles']['text_color'] ) ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.3)' )
		),
		'alt' => array(
			'class' => '.text-alt',
			'color' => ( empty( $content['page_cover']['styles']['text_color'] ) ? $colors['site']['headline'] : '#fff' ),
			'border' => ( empty( $content['page_cover']['styles']['text_color'] ) ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.3)' )
		)
	);
?>

/*------------------------------*\
	$ICONS & EFFECTS
\*------------------------------*/

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
.header.has-cover<?php echo $text_class; ?> .menu > .menu-item > a,
.header.has-cover<?php echo $text_class; ?> .trigger-icon,
.header.has-cover<?php echo $text_class; ?> .trigger-text,
.cover<?php echo $text_class; ?>,
.cover<?php echo $text_class; ?> .headline,
.cover<?php echo $text_class; ?> .headline a,
.cover<?php echo $text_class; ?> .byline,
.cover<?php echo $text_class; ?> a {
	color: <?php echo esc_attr( $text_atts['color'] ); ?>;
}

.header.has-cover<?php echo $text_class; ?> .menu-header > .menu-item:not(:last-child),
.cover<?php echo $text_class; ?> .author-link,
.header.has-cover<?php echo $text_class; ?> .author-link { border-bottom-color: <?php echo $text_atts['border']; ?>; }

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

.spin, .md-icon-loading { animation: spin 2s linear infinite; }

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
