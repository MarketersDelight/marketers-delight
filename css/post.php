<style type="text/css">

/* POST / PAGE HEADER */

.cta {
	align-items: center;
	display: flex;
	gap: <?php echo $half; ?>px <?php echo $single; ?>px;
	position: relative;
}

.cta-link { text-align: center; }

/* BYLINE */

.byline {
	align-items: center;
	color: <?php echo $colors['site']['text-sec']; ?>;
	column-gap: <?php echo $half; ?>px;
	display: flex;
	flex-flow: wrap;
	position: relative;
}

.slim .byline { column-gap: <?php echo $third; ?>px; }

.byline a, .byline-item a {
	color: <?php echo $colors['site']['text-sec']; ?>;
	text-decoration: none;
}

.byline-item { font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px; }

.byline-item i:not(:last-child) { margin-right: <?php echo $small; ?>px; }

.byline .author-link { border-bottom: 1px solid rgba(0, 0, 0, 0.15); }

.byline .author-link:hover { border-bottom: 0; }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-author .avatar {
	margin-right: <?php echo $small; ?>px;
	position: relative;
}

.byline-sticky {
	color: #22a340;
	display: block;
	font-weight: <?php echo $bold; ?>;
	margin-bottom: <?php echo $half; ?>px;
}

/* FEATURED IMAGE */

.featured-image {
	position: relative;
	z-index: 5;
}

.featured-image a { display: block; }

.featured-image img { width: 100%; }

.cover, .cover .overlay, .featured-image img { border-radius: 5px; }

.box-style .image-before .featured-image img,
.box-style .image-after .headline,
.box-style .image-after .cover,
.box-style .image-after .cover .overlay { border-radius: 5px 5px 0 0; }

.box-style .image-after .featured-image img,
.box-style .image-before .headline,
.box-style .image-before .cover,
.box-style .image-before .cover .overlay { border-radius: 0 0 5px 5px; }

/* CAPTIONS */

.wp-caption {
	height: auto;
	max-width: 100%;
}

.wp-caption-text, .wp-element-caption {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	padding: <?php echo $third; ?>px;
	text-align: center;
}

.entry .wp-caption-text,
.wp-element-caption {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	color: <?php echo $colors['site']['text-sec']; ?>;
}

.cover .wp-caption-text {
	background-color: rgba(0, 0, 0, 0.75);
	position: absolute;
		bottom: 0;
		right: 0;
	z-index: 10;
}

/* BLOCKS */

<?php if ( $colors['site']['text'] !== $colors['site']['headline'] ) : ?>
.the-content h1, .the-content h2, .the-content h3, .the-content h4, .the-content h5, .the-content h6 { color: <?php echo $colors['site']['headline']; ?>; }
<?php endif; ?>

.wp-block-cover[class*="align"] { width: auto; }

.wp-block-image figcaption {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-style: italic;
	font-size: 0.9em;
	text-align: center;
}

.callout {
	border: 4px solid rgba(0, 0, 0, 0.1);
	border-radius: 5px;
	clear: both;
	position: relative;
}

.callout.has-icon { padding-top: 0; }

.callout-title, .callout-action { text-align: center; }

.callout-icon {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
	color: #fff;
	display: block;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

.callout-icon.icon {
	background-color: #1e1e1e;
	font-size: 43px;
	height: 80px;
	margin-top: -25px;
	padding-top: 18px;
	width: 80px;
}

.callout-icon.image {
	height: 100px;
	margin-top: -35px;
	width: 100px;
}

.callout-icon.image img {
	border-radius: 50%;
	height: 100px;
	width: 100px;
}

.callout-button, .content-upgrade .button { width: 100%; }

.content-upgrade { border-radius: 5px; }

@media all and (min-width: 900px) {
	.box-lr {
		align-items: center;
		display: flex;
	}
	.box-lr .content-upgrade-text { width: 65%; }
	.box-lr .content-upgrade-action {
		padding-left: <?php echo $half; ?>px;
		width: 35%;
	}
}

@media all and (max-width: 900px) {
	.content-upgrade-text { margin-bottom: <?php echo $half; ?>px; }
}

/* COVERS */

.entry .cover { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }

.cover, .header.has-cover {
	background-position: center center;
	background-size: cover;
	position: relative;
}

<?php if ( ! empty( $colors['header']['cover_image']['url'] ) ) : ?>
.header.has-cover { background-image: url('<?php echo esc_url( $colors['header']['cover_image']['url'] ); ?>'); }
<?php endif; ?>

<?php foreach ( $cover_colors as $text_class => $text_atts ) : $text_class = $text_atts['class']; ?>
.header.has-cover<?php echo $text_class; ?>,
.header.has-cover<?php echo $text_class; ?> .site-name,
.header.has-cover<?php echo $text_class; ?> .site-name a,
.header.has-cover<?php echo $text_class; ?> .tagline,
.header.has-cover<?php echo $text_class; ?> .header-triggers a,
.header.has-cover<?php echo $text_class; ?> .menu > .menu-item > a,
.cover<?php echo $text_class; ?>,
.cover<?php echo $text_class; ?> a,
.cover<?php echo $text_class; ?> .title,
.cover<?php echo $text_class; ?> .widget-title,
.cover<?php echo $text_class; ?> .byline { color: <?php echo esc_attr( $text_atts['color'] ); ?>; }
.cover<?php echo $text_class; ?> .author-link { border-bottom-color: <?php echo $text_atts['border']; ?>; }
<?php endforeach; ?>

.page-headline.cover {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

@media all and (min-width: 800px) {
 	.entry .cover { padding: <?php echo $mid; ?>px; }
	.page-headline.cover {
		padding-bottom: <?php echo $mid; ?>px;
		padding-top: <?php echo $mid; ?>px;
	}
}

@media all and (max-width: 800px) {
	.header.has-cover .sub-menu .menu-item a { color: <?php echo $cover_colors['default']['color']; ?>; }
	.header.has-cover .menu-item:not(:last-child),
	.header.has-cover .menu .toggle { border-color: <?php echo $text_atts['border']; ?>; }
}
