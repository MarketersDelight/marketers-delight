<style type="text/css">

/* BREADCRUMBS */

.breadcrumbs {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	margin-bottom: <?php echo $half; ?>px;
}

.breadcrumbs a { text-decoration: underline; }

.breadcrumbs a:hover { text-decoration: none; }

.breadcrumbs a, .breadcrumbs i, .breadcrumb-text { margin-right: <?php echo $third; ?>px; }

/* PAGE TITLE */

.title-wrap { margin-bottom: <?php echo $small; ?>px; }

.description { margin-bottom: <?php echo $single; ?>px; }

.inline .description { margin-bottom: 0; }

.page-image {
	margin-bottom: <?php echo $half; ?>px;
	margin-left: auto;
	margin-right: auto;
}

.image-left .the-content .featured-image {
	float: left;
	margin-right: <?php echo $half; ?>px;
}

.image-right .the-content .featured-image {
	float: right;
	margin-left: <?php echo $half; ?>px;
}

.image-left .the-content .featured-image,
.image-right .the-content .featured-image { max-width: <?php echo round( $sidebar_width / 2 ); ?>px; }

@media all and (min-width: 700px) {
	.layout .title-wrap {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $content_width; ?>px;
	}
	.layout .title-wrap, .layout .page-image { flex: 1; }
	.image-left.inline, .image-right.inline { flex-flow: wrap; }
/*
	.image-left .title-wrap { margin-bottom: 0; }
*/
	.image-right.inline .title-wrap,
	.image-right.inline .page-image,
	.image-left .page-image, .image-right .title { order: -1; }
	.image-center, .image-above-headline { flex-direction: column; }
	.image-left .the-content .featured-image,
	.image-right .the-content .featured-image { max-width: <?php echo $sidebar_width; ?>px; }
	/* PAGE CTA */
	.page-cta-link + .page-cta-link { margin-left: <?php echo $half; ?>px; }
}

@media all and (max-width: 700px) {
	.page-cta-link {
		display: block;
		margin-bottom: <?php echo $half; ?>px;
		text-align: center;
		width: 100%;
	}
}

/* FEATURED IMAGE */

.featured-image {
	position: relative;
	margin-bottom: <?php echo $half; ?>px;
	z-index: 5;
}

.featured-image a { display: block; }

.featured-image img {
	border-radius: 5px;
	width: 100%;
}

/* BYLINE */

.byline {
	align-items: center;
	color: <?php echo $colors['site']['text-sec']; ?>;
	display: flex;
	flex-flow: wrap;
	gap: <?php echo $half; ?>px;
	position: relative;
}

.byline:not(:last-child) { margin-bottom: <?php echo $third; ?>px; }

.byline a, .byline-item a {
	color: <?php echo $colors['site']['text-sec']; ?>;
	text-decoration: none;
}

.byline-item {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
}

.byline .author-link { border-bottom: 1px solid rgba(0, 0, 0, 0.15); }

.byline .author-link:hover { border-bottom: 0; }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-author .avatar {
	margin-right: <?php echo $small; ?>px;
	position: relative;
}

.byline-date-modified { font-style: italic; }

.byline-sticky {
	color: #22a340;
	display: block;
	font-weight: <?php echo $bold; ?>;
	margin-bottom: <?php echo $half; ?>px;
}

/* POST FOOTER */

.post-footer {
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-top: <?php echo $single; ?>px;
}

.post-footer:not(:last-child) {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-bottom: <?php echo $single; ?>px;
}

.post-footer + .post-footer { border-top: 0; }

.post-box .post-footer.byline { margin-bottom: 0; }

.post-footer.byline { padding-top: <?php echo $half; ?>px; }

.post-footer.byline:not(:last-child) { padding-bottom: <?php echo $half; ?>px; }

/* CAPTIONS */

.wp-caption {
	height: auto;
	max-width: 100%;
}

.post-box .wp-caption-text {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	padding: <?php echo $third; ?>px;
	text-align: center;
}

.cover .wp-caption-text {
	background-color: rgba(0, 0, 0, 0.75);
	color: #fff;
	padding: <?php echo $small; ?>px <?php echo $third; ?>px;
	position: absolute;
		bottom: 0;
		right: 0;
	z-index: 10;
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

.article .header-cover .inner {
	padding-bottom: <?php echo $mid; ?>px;
	padding-top: <?php echo $mid; ?>px;
}

.header .header-cover-full {
	padding-bottom: <?php echo $double; ?>px;
	padding-top: <?php echo $double; ?>px;
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
.cover<?php echo $text_class; ?> .byline-item { color: <?php echo esc_attr( $text_atts['color'] ); ?>; }
.cover<?php echo $text_class; ?> .author-link { border-bottom-color: <?php echo $text_atts['border']; ?>; }
<?php endforeach; ?>

@media all and (max-width: 800px) {
	.header.has-cover .sub-menu .menu-item a { color: <?php echo $cover_colors['default']['color']; ?>; }
	.header.has-cover .menu-item:not(:last-child),
	.header.has-cover .menu .toggle { border-color: <?php echo $text_atts['border']; ?>; }
}

/* BLOCKS */

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

/* AUTHOR BOX */

.author-box {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.author-meta {
	align-items: center;
	display: flex;
	margin-bottom: <?php echo $half; ?>px;
}

.author-box .circle-icon { margin-right: <?php echo $third; ?>px; }

.author-title { margin-bottom: <?php echo $small; ?>px; }

.author-title {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
}

.author-description { margin-bottom: <?php echo $half + $small; ?>px; }

.author-avatar {
	flex: 0 1 <?php echo $double; ?>px;
	margin-right: <?php echo $half; ?>px;
}

.author-avatar img { width: 100%; }

.author-links {
	align-items: center;
	display: flex;
	font-size: <?php echo round( $typography['body']['font_size']['mobile'] - 1 ); ?>px;
	gap: <?php echo $half; ?>px;
	line-height: <?php echo round( $typography['body']['line_height']['mobile'] - 1 ); ?>px;
}

.author-link { color: <?php echo $colors['site']['links_sec']; ?>; }

.author-link:not(:last-child) { margin-right: <?php echo $small; ?>px; }

.author-link.twitter .circle-icon {
	background-color: #1da1f2;
	color: #fff;
}

.author-link.twitter .md-icon-twitter { color: #fff; }

.author-link.twitter a { color: #1da1f2; }

@media all and (max-width: 700px) {
	.author-links { flex-flow: wrap; }
	.author-link { flex-basis: calc(50% - <?php echo $half; ?>px); }
}

/* PAGINATION */

.pagination {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
	justify-content: space-between;
	position: relative;
	text-align: center;
}

.pagination.prev-next .pagination-wrap {
	display: flex;
	justify-content: space-between;
}

.pagination a { text-decoration: none; }

.post-nav-links {
	background-color: rgba(0, 0, 0, 0.05);
	border-radius: 5px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
	padding: <?php echo $half; ?>px;
}

.pagination .page-numbers, .post-nav-links .post-page-numbers {
	background-color: #fff;
	border: 0;
	border-radius: 50%;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	display: inline-block;
	margin-right: <?php echo $small; ?>px;
	padding: <?php echo $small; ?>px <?php echo $half; ?>px;
}

.page-numbers.current, .post-page-numbers.current {
	cursor: default;
	font-weight: bold;
}

.pagination .page-numbers:hover, .post-nav-links.post-page-numbers:hover { opacity: 0.8; }

.page-numbers.dots, .page-numbers.prev, .page-numbers.next {
	background-color: transparent;
	border-radius: inherit;
	box-shadow: none;
	border: 0;
	color: <?php echo $colors['site']['text']; ?>;
	padding: 0;
}

.page-numbers.prev { margin-right: <?php echo $third; ?>px; }
.page-numbers.next { margin-left: <?php echo $third; ?>px; }

/* POST NAV */

.post-nav {
	align-items: center;
	display: flex;
	margin-left: -<?php echo $half; ?>px;
}

.post-nav p { margin-bottom: 0; }

.post-nav a {
	display: block;
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
	text-decoration: none;
}

.post-nav-next { text-align: right; }

.post-nav-previous, .post-nav-next {
	flex: 1;
	margin-left: <?php echo $half; ?>px;
}

.post-nav-title { color: <?php echo $colors['site']['text']; ?>; }

.post-nav-previous:hover .post-nav-title, .post-nav-next:hover .post-nav-title { text-decoration: underline; }

.post-nav-previous .post-nav-subtitle i { margin-right: <?php echo $third; ?>px; }
.post-nav-next .post-nav-subtitle i { margin-left: <?php echo $third; ?>px; }
