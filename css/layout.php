<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

/* POST HEADER */

.post-header, .cover { padding-top: <?php echo $single; ?>px; }

.post-header.cover,
.image-below-headline .post-header,
.cover { padding-bottom: <?php echo $single; ?>px; }

.post-header.header-cover,
.post-header.header-cover-full {
	padding-bottom: <?php echo $mid; ?>px;
	padding-top: <?php echo $mid; ?>px;
}

.cover,
.box-style .post-box .post-header,
.box-style .the-content {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

#content > .inner, .the-content {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.content .the-content.full,
.header .cover, #content > .cover {
	padding-left: 0;
	padding-right: 0;
}

@media all and (min-width: 900px) {
	.full .post-header, .header .post-header, #content > .post-header { text-align: center; }
	.cover,
	.content-sidebar.box-style .post-box .post-header,
	.full.box-style .post-box .post-header,
	.content-sidebar.box-style .the-content,
	.content-sidebar .author-box,
	.content-sidebar.box-style .comments {
		padding-left: <?php echo $mid; ?>px;
		padding-right: <?php echo $mid; ?>px;
	}
	.full.box-style .the-content,
	.full .author-box,
	.full.box-style .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
}

/* HEADER COVERS */

<?php
	$cover_image_id = md_setting( array( 'colors', 'header', 'cover_image', 'id' ) );
	$cover_colors = array(
		'default' => array(
			'class' => '',
			'color' => ( ! empty( $colors['page_cover']['cover_styles']['text_color'] ) ? $header['color'] : '#fff' ),
			'border' => ( ! empty( $colors['page_cover']['cover_styles']['text_color'] ) ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.3)' )
		),
		'alt' => array(
			'class' => '.alt',
			'color' => ( empty( $colors['page_cover']['cover_styles']['text_color'] ) ? $header['color'] : '#fff' ),
			'border' => ( empty( $colors['page_cover']['cover_styles']['text_color'] ) ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.3)' )
		)
	);
?>

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

<?php if ( ! empty( $colors['header']['cover_image']['url'] ) ) : ?>
.header.has-cover { background-image: url('<?php echo esc_url( $colors['header']['cover_image']['url'] ); ?>'); }
<?php endif; ?>

<?php foreach ( $cover_colors as $text_class => $text_atts ) :
	$text_class = $text_atts['class'];
?>

.header.has-cover<?php echo $text_class; ?>,
.header.has-cover<?php echo $text_class; ?> .site-name, .header.has-cover<?php echo $text_class; ?> .site-name a,
.header.has-cover<?php echo $text_class; ?> .tagline,
.header.has-cover<?php echo $text_class; ?> .header-triggers a,
.header.has-cover<?php echo $text_class; ?> .menu > .menu-item > a,
.cover<?php echo $text_class; ?>, .cover<?php echo $text_class; ?> a,
.cover<?php echo $text_class; ?> .title a, .cover<?php echo $text_class; ?> .byline {
	color: <?php echo esc_attr( $text_atts['color'] ); ?>;
}

.cover<?php echo $text_class; ?> .author-link { border-bottom-color: <?php echo $text_atts['border']; ?>; }

<?php endforeach; ?>

@media all and (max-width: 800px) {
	.header.has-cover .sub-menu .menu-item a { color: <?php echo $cover_colors['default']['color']; ?>; }
	.header.has-cover .menu-item:not(:last-child) { border-bottom-color: <?php echo $text_atts['border']; ?>; }
}

/* BOX STYLE */

.box-style .entry { margin-bottom: <?php echo $single; ?>px; }

.box-style .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.cover, .cover .overlay { border-radius: 5px; }

.box-style .post-box .cover, .box-style .post-box .overlay { border-radius: 5px 5px 0 0; }

.box-style .image-above-headline .cover,
.box-style .image-above-headline .overlay { border-radius: 0; }

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
	.content-sidebar .content {
		float: left;
		width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%;
	}
	.content-sidebar.left .content { float: right; }
	.content-sidebar .sidebar {
		float: left;
		padding-left: <?php echo $single; ?>px;
		width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%;
	}
	.content-sidebar.left .sidebar {
		padding-left: 0;
		padding-right: <?php echo $single; ?>px;
	}
	.columns { margin-left: -<?php echo $single; ?>px; }
	.columns > .entry { padding-left: <?php echo $single; ?>px; }
	.content-sidebar .columns, .columns.slim { margin-left: -<?php echo $half; ?>px; }
	.content-sidebar .columns > .entry, .columns.slim > .entry { padding-left: <?php echo $half; ?>px; }
}

@media all and (max-width: 900px) {
	.content { margin-bottom: <?php echo $single; ?>px; }
	.loop-default.article .post-box {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed; }
}
