<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.clear:after,
.entry.image-left .the-content:after,
.entry.image-right .the-content:after {
	clear: both;
	content: '';
	display: table;
}

.inner {
	margin-inline: auto;
	max-width: <?php echo $site_width; ?>px;
	position: relative;
	width: 100%;
}

.main {
	<?php if ( $colors['content']['body_color'] !== $defaults['colors']['content']['body_color'] ) : ?>
	background-color: <?php echo $colors['content']['body_color']; ?>;
	<?php endif; ?>
	padding-block: <?php echo $single; ?>px;
}

.header-cover .main { padding-block-start: 0; }

.loop:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

/* BOX STYLE */

.box-style.main { background-color: <?php echo $colors['content']['body_color']; ?>; }

.box, .box-style .entry,
.page-title.inline.cover, .page-title.inline.cover .overlay,
.featured-media, .featured-media img,
.box-style .entry .item:nth-child(1 of .item):nth-last-child(1 of .item) { border-radius: 8px; }

.box-style .entry .item:nth-child(1 of .item),
.entry :is(.cover, .cover .overlay),
.image-above :is(.featured-media, .featured-media img),
.image-below.has-cover :is(.cover, .overlay),
.header-cover .box-style .image-full :is(.featured-media, .featured-media img) { border-radius: 8px 8px 0 0; }

.box-style .entry .item:nth-last-child(1 of .item),
.image-above :is(.cover, .cover .overlay, .featured-media + .item) { border-radius: 0 0 8px 8px; }

.box-style .image-below .featured-media img { border-radius: 0; }

.box, .box-style .entry,
.cover, .featured-media img { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15); }

.box, .box-style .item,
.box-style .image-full .featured-media,
.box-style .columns .entry,
.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .columns .entry .item {
	background-color: transparent;
	box-shadow: none;
}

.box-style .byline.post-footer { border-block-start: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .post-footer:not(:last-child) { border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box, .box-style .entry { width: auto; }

.box-style .row.full > .item:not(:last-child),
.box-style .row.full .entry:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.box-style .item { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }

.box-style .entry .byline.post-footer { padding: <?php echo $half; ?>px; }

.box-style .post-title:not(.cover) + .the-content { padding-block-start: 0; }

/* PLAIN + BORDER STYLES */

:is(.plain-style.expanded, .border-style.expanded) .loop-article.row .entry { margin-block-end: <?php echo $triple; ?>px; }

:is(.plain-style.compact, .border-style.compact) .loop-article.row .entry,
:is(.plain-style, .border-style) .loop-article.row .entry > *:not(:last-child) { margin-block-end: <?php echo $mid; ?>px; }

:is(.plain-style, .border-style) .columns .entry > *:not(:last-child) { margin-block-end: <?php echo $half; ?>px; }

:is(.plain-style, .border-style) .loop-article.image-above.has-cover .featured-media,
:is(.plain-style, .border-style) .loop-article.image-below.has-cover .post-title { margin-block-end: 0; }

.is-border-style .header:not(.cover) { border-block-end: 1px solid <?php echo $colors['header']['border_color']; ?>; }

.is-border-style .footer { border-block-start: 1px solid <?php echo $colors['footer']['border_color']; ?>; }

.border-style .page-title:not(.cover) {
	border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-block-end: <?php echo $mid; ?>px;
}

.border-style .loop.row .entry:not(:first-child) {
	border-block-start: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-block-start: <?php echo $mid; ?>px;
}

.border-style .loop.row :is(.entry.has-cover, .entry.image-above) {
	border-block-start: 0;
	padding-block-start: 0;
}

/* QUERIES */

@media (min-width: <?php echo $post_width; ?>px) {
	.cover,
	.box-style .full .item { padding: <?php echo $mid; ?>px; }
	.box-style.compact .full .post-title { padding-block-end: <?php echo $single; ?>px; }
	.slim .cover { padding-inline: <?php echo $half; ?>px; }
	.expanded .item .wrap {
		margin-inline: auto;
		max-width: <?php echo $post_width; ?>px;
		width: 100%;
	}
	.expanded .image-title .wrap {
		max-width: 100%;
		width: auto;
	}
}

@media (min-width: 900px) {
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-width { max-width: <?php echo $post_width; ?>px; }
	.sidebar-width { max-width: <?php echo $sidebar_width; ?>px; }
	.compact .content-wrap:not(:last-child) { margin-block-end: 0; }
	.compact .content-wrap {
		display: grid;
		gap: <?php echo $mid; ?>px;
		grid-template-columns: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>% <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%;
	}
	.compact.left .content-wrap { direction: rtl; }
	.compact.left .content-wrap, .compact.left .sidebar { direction: ltr; }
}

@media (max-width: <?php echo $site_width; ?>px) {
	.inner { padding-inline: <?php echo $half; ?>px; }
	.cover .inner { padding-inline: 0; }
/*
	.main .page-title.cover,
	.main.row .cover,
	.main .row .entry .cover,
	.box-style .full :is(.entry .item, .image-full .featured-media) { margin-inline: -<?php echo $half; ?>px; }
*/
}