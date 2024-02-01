<style type="text/css">

/* ALIGNMENTS */

.alignfull, .alignwide { max-width: initial; }

.alignleft, .alignright, .aligncenter, .alignnone {
	display: block;
	position: relative;
	margin-bottom: <?php echo $single; ?>px;
	z-index: 10;
}

.alignwide img, .alignfull img { width: 100%; }

.aligncenter {
	clear: both;
	float: none;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

.alignnone {
	clear: both;
	float: none;
}

@media all and (min-width: 700px) {
	.alignleft {
		float: left;
		margin-right: <?php echo $half; ?>px;
	}
	.alignright {
		float: right;
		margin-left: <?php echo $half; ?>px;
	}
}

@media all and (max-width: 700px) {
	.alignleft.wrap { margin-right: -<?php echo $half; ?>px; }
	.alignright.wrap { margin-left: -<?php echo $half; ?>px; }
}

/* SPACERS */

.mb-double:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }
.mb-mid:not(:last-child) { margin-bottom: <?php echo $mid; ?>px; }
.mb-single:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
.mb-half:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }
.mb-small:not(:last-child) { margin-bottom: <?php echo $small; ?>px; }
.mb-none { margin-bottom: 0 !important; }

/* COVERS / OVERLAY */

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

.overlay {
	background-color: <?php echo $colors['page_cover']['cover_color']; ?>;
	content: '';
	display: block;
	inset: 0;
	position: absolute;
}

<?php foreach ( $cover_colors as $text_class => $text_atts ) :
	$text_class = $text_atts['class'];
?>

.header.has-cover<?php echo $text_class; ?>,
.header.has-cover<?php echo $text_class; ?> .site-name,
.header.has-cover<?php echo $text_class; ?> .site-name a,
.header.has-cover<?php echo $text_class; ?> .tagline,
.header.has-cover<?php echo $text_class; ?> .header-triggers a,
.header.has-cover<?php echo $text_class; ?> .menu > .menu-item > a,
.cover<?php echo $text_class; ?>,
.cover<?php echo $text_class; ?> a,
.cover<?php echo $text_class; ?> .title,
.cover<?php echo $text_class; ?> .byline-item {
	color: <?php echo esc_attr( $text_atts['color'] ); ?>;
}

.cover<?php echo $text_class; ?> .author-link { border-bottom-color: <?php echo $text_atts['border']; ?>; }

<?php endforeach; ?>

@media all and (max-width: 800px) {
	.header.has-cover .sub-menu .menu-item a { color: <?php echo $cover_colors['default']['color']; ?>; }
	.header.has-cover .menu-item:not(:last-child) { border-bottom-color: <?php echo $text_atts['border']; ?>; }
}

/* LISTS */

.list, .list > ul, ul.list-check { list-style: none; }

.list li, ul.list-check li { position: relative; }

.list > li:not(:last-child) {
	border-bottom: 1px solid rgba(0, 0, 0, 0.15);
	margin-bottom: <?php echo $third; ?>px;
	padding-bottom: <?php echo $third; ?>px;
}

ul.list-check { margin-left: <?php echo $single + $small; ?>px; }

ul.list-check li:not(:last-child) { margin-bottom: <?php echo $third; ?>px; }

ul.list-check li:before {
	background-color: rgba(0, 0, 0, 0.08);
	border-radius: 50%;
	color: #22a340;
	padding: <?php echo $small; ?>px;
	position: absolute;
		left: -<?php echo $single + $small; ?>px;
		top: 0;
}

/* GENERAL */

.clickable:after {
	content: '';
	inset: 0;
	position: absolute;
}

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.highlight {
	background-color: #fdd169;
	padding-left: <?php echo $small; ?>px;
	padding-right: <?php echo $small; ?>px;
}

.foot {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.shadow, .wp-block-image.shadow img { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); }

.wp-block-image.shadow { box-shadow: none; }

<?php foreach ( md_editor_colors() as $color_group => $color_fields ) {
	$color_slug = $color_fields['slug'];
	$color_val = $color_fields['color'];

	echo
		".has-$color_slug-background-color { background-color: $color_val; }\n".
		( $color_slug !== 'text' ? ".has-$color_slug-color, .format .has-$color_slug-color { color: $color_val; }\n" : '' );
} ?>

.has-text-color.has-white-color { color: #fff; }

.circle { border-radius: 50%; }

.circle-icon, a.circle-icon, .toc-anchor {
	align-items: center;
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 50%;
	color: <?php echo $colors['site']['text']; ?>;
	display: inline-flex;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	font-weight: normal;
	height: <?php echo $single + $small; ?>px;
	justify-content: center;
	line-height: 1;
	position: relative;
	width: <?php echo $single + $small; ?>px;
}

.close {
	background-color: transparent;
	color: #ae2525;
	cursor: pointer;
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
}

.close:hover { background-color: rgba(0, 0, 0, 0.2); }
