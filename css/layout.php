<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.clear:after,
.entry.image-inline .the-content .wrap:after,
.entry.image-inline .the-content .featured-media + :is(h2, h3, h4, h5, h6) {
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

.loop:not(.content):not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

/* BOX STYLE */

.main:has(.box-style.loop) { background-color: <?php echo $colors['content']['body_color']; ?>; }

.box, .box-style .entry,
.content :is(.page-title.cover, .page-title.cover .overlay),
.entry :is(.cover, .cover .overlay),
.featured-media, .featured-media img,
.box-style .entry .item:nth-child(1 of .item):nth-last-child(1 of .item) { border-radius: 8px; }

.box-style .entry .item:nth-child(1 of .item),
.box-style .entry :is(.cover, .overlay),
.box-style .image-above :is(.featured-media, .featured-media img),
.header-cover .box-style .image-full :is(.featured-media, .featured-media img) { border-radius: 8px 8px 0 0; }

.box-style .entry .item:nth-last-child(1 of .item),
.box-style .image-above :is(.cover, .overlay, .featured-media + .item) { border-radius: 0 0 8px 8px; }

.box-style .image-below .featured-media img,
.box-style .entry.image-below .featured-media + .the-content { border-radius: 0; }

.box, .box-style .entry,
.cover, .featured-media img { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15); }

.box, .box-style .item,
.box-style .image-full .featured-media,
.box-style.columns .entry,
.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style.columns .entry .item {
	background-color: transparent;
	box-shadow: none;
}

.box-style .byline.post-footer { border-block-start: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .post-footer:not(:last-child) { border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box, .box-style .entry { width: auto; }

.box-style.row.full > .item:not(:last-child),
.box-style.row.full .entry:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.box-style .item { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }

.box-style .entry .byline.post-footer { padding: <?php echo $half; ?>px; }

.box-style .entry-title:not(.cover):not(:empty) + .the-content { padding-block-start: 0; }

/* PLAIN + BORDER STYLES */

.expanded :is(.plain-style, .border-style).loop-article.row > .entry:not(:last-child) { margin-block-end: <?php echo $triple; ?>px; }

.compact :is(.plain-style, .border-style).loop-article.row > .entry,
:is(.plain-style, .border-style).loop-article.row > .entry > .item:not(:last-child) { margin-block-end: <?php echo $mid; ?>px; }

:is(.plain-style, .border-style).columns .entry > *:not(:last-child) { margin-block-end: <?php echo $half; ?>px; }

:is(.plain-style, .border-style).loop-article.image-above.has-cover > .featured-media,
:is(.plain-style, .border-style).loop-article.image-below.has-cover > .post-title { margin-block-end: 0; }

.is-border-style .header:not(.cover) { border-block-end: 1px solid <?php echo $colors['header']['border_color']; ?>; }

.is-border-style .footer { border-block-start: 1px solid <?php echo $colors['footer']['border_color']; ?>; }

.is-border-style .page-title:not(.cover) {
	border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-block-end: <?php echo $mid; ?>px;
}

.border-style.row .entry:not(:first-child) {
	border-block-start: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-block-start: <?php echo $mid; ?>px;
}

.border-style.row :is(.entry.has-cover, .entry.image-above) {
	border-block-start: 0;
	padding-block-start: 0;
}

/* QUERIES */

@media (min-width: <?php echo $post_width; ?>px) {
	.cover, .box-style.full .item { padding: <?php echo $mid; ?>px; }
	.compact .box-style.full .post-title { padding-block-end: <?php echo $single; ?>px; }
	.slim .cover { padding-inline: <?php echo $half; ?>px; }
	.expanded .item:not(.post-title) .wrap {
		margin-inline: auto;
		max-width: <?php echo $post_width; ?>px;
		width: 100%;
	}
}

@media (min-width: 900px) {
	.compact .content-wrap {
		display: grid;
		gap: <?php echo $mid; ?>px;
		grid-template-columns: <?php echo $content_width; ?>px minmax(0, <?php echo $sidebar_width; ?>px);
	}
	.compact .content-wrap:not(:last-child) { margin-block-end: 0; }
	.compact.left .content-wrap { direction: rtl; }
	.compact.left :is(.content, .sidebar) { direction: ltr; }
}

@media (max-width: <?php echo $site_width; ?>px) {
	.inner { padding-inline: <?php echo $half; ?>px; }
	.cover .inner {
		padding-inline: 0;
		width: 100%;
	}
	.box-style.loop-article.full .entry { margin-inline: -<?php echo $half; ?>px; }
}