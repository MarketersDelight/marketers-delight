<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

/* POST STRUCTURE */

.entry, .post-box { position: relative; }

.post-header, .cover { padding-top: <?php echo $half; ?>px; }

.post-header:not(.cover) + .featured-image { padding-top: <?php echo $single; ?>px; }

.columns.slim .post-header:not(.cover) + .featured-image { padding-top: <?php echo $half; ?>px; }

.cover { padding-bottom: <?php echo $half; ?>px; }

.post-footer {
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

#content > .inner, .the-content {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.cover,
.post-box .post-header,
.the-content, .post-footer {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.content .the-content.full, .header .cover, #content > .cover {
	padding-left: 0;
	padding-right: 0;
}

@media all and (min-width: 900px) {
	.expanded .post-header, .header .post-header, #content > .post-header { text-align: center; }
	.article .header-cover, .header .header-cover-full {
		padding-bottom: <?php echo $mid; ?>px;
		padding-top: <?php echo $mid; ?>px;
	}
	.cover, .post-box .post-header, .the-content, .author-box, .comments, .post-footer {
		padding-left: <?php echo $mid; ?>px;
		padding-right: <?php echo $mid; ?>px;
	}
	.expanded .the-content, .expanded .post-footer, .expanded .author-box, .expanded .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
}

/* POST COLUMNS */

.columns .the-content {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
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
.cover<?php echo $text_class; ?> .title a, .cover<?php echo $text_class; ?> .byline-item {
	color: <?php echo esc_attr( $text_atts['color'] ); ?>;
}

.cover<?php echo $text_class; ?> .author-link { border-bottom-color: <?php echo $text_atts['border']; ?>; }

<?php endforeach; ?>

@media all and (max-width: 800px) {
	.header.has-cover .sub-menu .menu-item a { color: <?php echo $cover_colors['default']['color']; ?>; }
	.header.has-cover .menu-item:not(:last-child) { border-bottom-color: <?php echo $text_atts['border']; ?>; }
}

/* BOX STYLE */

.box-style .entry:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

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
	.post-header, .cover { padding-top: <?php echo $single; ?>px; }
	.cover { padding-bottom: <?php echo $single; ?>px; }
	.full .loop.standard {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $content_width; ?>px;
	}
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
	.columns {
		align-items: center;
		display: flex;
		flex-flow: wrap;
		justify-content: center;
		margin-left: -<?php echo $single; ?>px;
	}
	.f2 { flex-basis: 50%; max-width: 50%; }
	.f3 { flex-basis: 33.3333333333%; max-width: 33.3333333333%; }
	.f4 { flex-basis: 25%; max-width: 25%; }
	.f5 { flex-basis: 20%; max-width: 20%; }
	.columns > .entry { padding-left: <?php echo $single; ?>px; }
	.columns.slim { margin-left: -<?php echo $half; ?>px; }
	.columns.slim > .entry { padding-left: <?php echo $half; ?>px; }
	.columns .post-header { padding-top: <?php echo $half; ?>px; }
	.columns .cover { padding-bottom: <?php echo $half; ?>px; }
	.columns .cover, .columns .post-header, .columns .the-content, .columns .post-footer {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	.columns.wide .post-box .title {
		font-size: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h3']['line_height']['desktop']; ?>px;
	}
	.columns.slim .post-box .title {
		font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
	}
	.columns.slim .the-content {
		font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	}
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
