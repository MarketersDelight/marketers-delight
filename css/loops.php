<style type="text/css">

/*------------------------------*\
	$LOOPS
\*------------------------------*/

#content, .the-content {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.post-box .post-header, .the-content {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.post-header { padding-top: <?php echo $single; ?>px; }

.post-header.cover { padding-bottom: <?php echo $single; ?>px; }

@media all and (min-width: 900px) {
	.full .post-header, .header .post-header { text-align: center; }
	.post-box .post-header, .content-sidebar .the-content, .content-sidebar .author-box, .content-sidebar .comments {
		padding-left: <?php echo $mid; ?>px;
		padding-right: <?php echo $mid; ?>px;
	}
	.full .the-content, .full .author-box, .full .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
}

/* BOX STYLE */

.box-style .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	margin-bottom: <?php echo $single; ?>px;
}

.box-style .post-box .post-header, .post-box .overlay { border-radius: 5px 5px 0 0; }

/* HEADER COVER */

.header-cover #content { padding-top: 0; }

.header-cover .post-header { margin-bottom: <?php echo $single; ?>px; }

/* QUERIES */

@media all and (max-width: <?php echo $site_width; ?>px) {
	#content .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-content { max-width: <?php echo $post_width; ?>px; }
	/* CONTENT - SIDEBAR */
	.content-sidebar .content {
		float: left;
		width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%;
	}
	.content-sidebar .sidebar {
		float: left;
		padding-left: <?php echo $single; ?>px;
		width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%;
	}
	.content-sidebar.left .content { float: right; }
}

@media all and (max-width: 900px) {
	#content.loop-default .post-box {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed; }
}





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

.overlay {
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

<?php foreach ( $header_colors as $text_class => $text_atts ) :
	$text_class = $text_atts['class'];
?>

.header.has-cover<?php echo $text_class; ?>,
.header.has-cover<?php echo $text_class; ?> .site-name,
.header.has-cover<?php echo $text_class; ?> .site-name a,
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
