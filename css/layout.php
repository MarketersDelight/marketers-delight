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

.box, .box-entry .entry,
.content :is(.page-title.cover, .page-title.cover .overlay),
.entry :is(.cover, .cover .overlay),
.featured-media, .featured-media img,
.box-entry .entry .item:nth-child(1 of .item):nth-last-child(1 of .item) { border-radius: 8px; }

.box-entry .entry .item:nth-child(1 of .item),
.box-entry .entry :is(.cover, .overlay),
.box-entry .image-above :is(.featured-media, .featured-media img),
.header-cover .box-entry .image-full :is(.featured-media, .featured-media img) { border-radius: 8px 8px 0 0; }

.box-entry .entry .item:nth-last-child(1 of .item),
.box-entry .image-above :is(.cover, .overlay, .featured-media + .item) { border-radius: 0 0 8px 8px; }

.box-entry .image-below .featured-media img,
.box-entry .entry.image-below .featured-media + .the-content { border-radius: 0; }

.box, .box-entry .entry,
.cover, .featured-media img { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15); }

.box, .box-entry .item,
.box-entry .image-full .featured-media,
.box-entry.columns .entry { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-entry.columns .entry .item {
	background-color: transparent;
	box-shadow: none;
}

.box, .box-entry .entry { width: auto; }

.box-entry.row.full > .item:not(:last-child),
.box-entry.row.full .entry:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.box-entry .item { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }

.box-entry .entry .byline.post-footer { padding: <?php echo $half; ?>px; }

.box-entry .byline.post-footer,
.box-style.category-view .post-footer { border-block-start: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-entry .post-footer:not(:last-child) { border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-entry .entry-title:not(.cover):not(:empty) + .the-content { padding-block-start: 0; }

.box-group {
	border-radius: 8px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	background-color: <?php echo $colors['content']['bg_color']; ?>;
}

/* PLAIN + BORDER STYLES */

.expanded :is(.plain-style, .border-style).loop-article.row > .entry:not(:last-child) { margin-block-end: <?php echo $triple; ?>px; }

.compact :is(.plain-style, .border-style).loop-article.row > .entry,
:is(.plain-style, .border-style).loop-article.row > .entry > .item:not(:last-child) { margin-block-end: <?php echo $mid; ?>px; }

:is(.plain-style, .border-style).columns .entry > *:not(:last-child) { margin-block-end: <?php echo $half; ?>px; }

:is(.plain-style, .border-style).loop-article.image-above.has-cover > .featured-media,
:is(.plain-style, .border-style).loop-article.image-below.has-cover > .post-title { margin-block-end: 0; }

/* BORDER STYLE */

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

/* LOOP LIST */

.loop-list > .entry {
	border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding: <?php echo $half; ?>px;
}

.loop-list .title {
	font-family: inherit;
	font-size: inherit;
	font-weight: normal;
	line-height: inherit;
}

.loop-list .title a {
	color: <?php echo $colors['site']['links']; ?>;
	text-decoration: underline;
}

.loop-list .title a:hover { text-decoration: none; }

/* CATEGORY VIEWS */

.category-title { row-gap: <?php echo $small; ?>px; }

.category-posts .category-title,
.categories .scroller-nav { margin-block-end: <?php echo $half; ?>px; }

.category-title .title {
	font-size: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h3']['line_height']['desktop']; ?>px;
}

.category-more a {
	display: block;
	padding: <?php echo $half; ?>px;
	text-align: center;
	width: 100%;
}

.category-view .category-title:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.category-view .box-group {
	display: flex;
	flex-direction: column;
	padding: <?php echo $single; ?>px;
}

.category-view.box-style .post-footer {
	margin-top: auto;
	padding-block-start: <?php echo $half; ?>px;
}

/* QUERIES */

@media (min-width: <?php echo $post_width; ?>px) {
	.cover, .box-entry.full .item { padding: <?php echo $mid; ?>px; }
	.compact .box-entry.full .post-title { padding-block-end: <?php echo $single; ?>px; }
	.slim .cover { padding-inline: <?php echo $half; ?>px; }
	.expanded .item:not(.post-title) .wrap {
		margin-inline: auto;
		max-width: <?php echo $post_width; ?>px;
		width: 100%;
	}
}

@media (min-width: 900px) {
	.compact .content-wrap {
		display: flex;
		align-items: stretch;
		gap: <?php echo $mid; ?>px;
	}
	.compact .content {
		flex: 0 1 <?php echo round( $content_width / $site_width * 100 ); ?>%;
		min-width: 0;
	}
	.compact .sidebar {
		flex: 0 1 <?php echo round( $sidebar_width / $site_width * 100 ); ?>%;
		min-width: 0;
	}
	.sidebar-left .sidebar { order: 1; }
	.sidebar-left .content { order: 2; }
	.compact .content-wrap:not(:last-child) { margin-block-end: 0; }
}

@media (max-width: <?php echo $site_width; ?>px) {
	.inner { padding-inline: <?php echo $half; ?>px; }
	.cover .inner {
		padding-inline: 0;
		width: 100%;
	}
	.box-entry.loop-article.full .entry { margin-inline: -<?php echo $half; ?>px; }
}