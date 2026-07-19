<style type="text/css">

/*------------------------------*\
	$LOOP
\*------------------------------*/

.clear:after,
.entry.image-inline .the-content .wrap:after,
.entry.image-inline .the-content .featured-media + :is(h2, h3, h4, h5, h6) {
	clear: both;
	content: '';
	display: table;
}

.loop:not(.content):not(:last-child) { margin-block-end: var(--md-single); }

.expanded .item:not(.post-title) .wrap {
	margin-inline: auto;
	max-width: var(--md-width-post);
	width: 100%;
}

/* SLIM */

.loop.columns.slim { gap: var(--md-half); }

.slim.box-entry .entry-title:not(.cover):not(:empty) { padding-block-end: var(--md-half); }

/* COVER */

.entry .cover, .content .page-title.cover { padding-inline: var(--md-half); }

.full-cover .page-title,
.full-cover .main > .post-title { padding-block: calc(var(--md-quad) * 2) var(--md-double); }

.header-cover .main { padding-block-start: 0; }

/* BOX STYLE */

.main:has(.box-style.loop) { background-color: var(--md-color-surface); }

.box, .box-entry .entry,
.content :is(.page-title.cover, .page-title.cover .overlay),
.entry :is(.cover, .cover .overlay),
.featured-media, .featured-media img,
.box-entry .entry .item:nth-child(1 of .item):nth-last-child(1 of .item) { border-radius: var(--md-border-radius); }

.box-entry .entry .item:nth-child(1 of .item),
.box-entry .entry :is(.cover, .overlay),
.box-entry .image-above :is(.featured-media, .featured-media img),
.header-cover .box-entry .image-full :is(.featured-media, .featured-media img) { border-radius: var(--md-border-radius) var(--md-border-radius) 0 0; }

.box-entry .entry .item:nth-last-child(1 of .item),
.box-entry .image-above :is(.cover, .overlay, .featured-media + .item) { border-radius: 0 0 var(--md-border-radius) var(--md-border-radius); }

.box-entry .image-below .featured-media img,
.box-entry .entry.image-below .featured-media + .the-content { border-radius: 0; }

.box, .box-entry .entry,
.cover, .featured-media img { box-shadow: var(--md-box-shadow); }

.box, .box-entry .item,
.box-entry .image-full .featured-media,
.box-style .comment-details,
.box-entry.columns .entry { background-color: var(--md-content); }

.box-entry.columns .entry .item {
	background-color: transparent;
	box-shadow: none;
}

.box-entry .byline.post-footer,
.box-style.category-view .post-footer { border-block-start: 1px solid var(--md-color-border); }

.box-entry .post-footer { border-block-start: 1px solid var(--md-color-border); }

.box, .box-entry .entry { width: auto; }

.full > .box-group:not(:last-child),
.box-entry.row.full > .item:not(:last-child),
.box-entry.row.full .entry:not(:last-child) { margin-block-end: var(--md-single); }

.box-entry.loop-article.full .entry { margin-inline: calc(-1 * clamp(0px, calc((100vw - var(--md-width-site)) / -2), var(--md-half))); }

.box-entry .item { padding: var(--md-single) var(--md-half); }

.box-entry .entry .byline.post-footer { padding: var(--md-half); }

.box-entry .entry-title:not(.cover):not(:empty) + .the-content { padding-block-start: 0; }

.compact .box-entry.full .post-title { padding-block-end: var(--md-single); }

.box-group {
	border-radius: var(--md-border-radius);
	box-shadow: var(--md-box-shadow);
	background-color: var(--md-content);
}

/* PLAIN + BORDER STYLES */

.expanded :is(.plain-style, .border-style).loop-article.row > .entry:not(:last-child) { margin-block-end: var(--md-triple); }

.compact :is(.plain-style, .border-style).loop-article.row > .entry,
:is(.plain-style, .border-style).loop-article.row > .entry > .item:not(:last-child) { margin-block-end: var(--md-mid); }

:is(.plain-style, .border-style).columns .entry > *:not(:last-child) { margin-block-end: var(--md-half); }

:is(.plain-style, .border-style).loop-article.image-above.has-cover > .featured-media,
:is(.plain-style, .border-style).loop-article.image-below.has-cover > .post-title { margin-block-end: 0; }

/* BORDER STYLE */

.is-border-style .header:not(.cover) { border-block-end: 1px solid var(--md-header-border); }

.is-border-style .footer { border-block-start: 1px solid var(--md-footer-border); }

.is-border-style .page-title:not(.cover) {
	border-block-end: 1px solid var(--md-color-border);
	padding-block-end: var(--md-mid);
}

.border-style.row .entry:not(:first-child) {
	border-block-start: 1px solid var(--md-color-border);
	padding-block-start: var(--md-mid);
}

.border-style.row :is(.entry.has-cover, .entry.image-above) {
	border-block-start: 0;
	padding-block-start: 0;
}

/* LOOP LIST */

.loop-list > .entry {
	border-block-end: 1px solid var(--md-color-border);
	padding: var(--md-half);
}

.loop-list .title {
	font-family: inherit;
	font-size: inherit;
	font-weight: normal;
	line-height: inherit;
}

.loop-list .title a {
	color: var(--md-links);
	text-decoration: underline;
}

.loop-list .title a:hover { text-decoration: none; }

/* CATEGORY VIEWS */

.category-title { row-gap: var(--md-small); }

.category-posts .category-title { margin-block-end: var(--md-half); }

.category-title .title {
	font-size: var(--md-h3);
	line-height: var(--md-h3-line-height);
}

.category-more a {
	display: block;
	padding: var(--md-half);
	text-align: center;
	width: 100%;
}

.category-view .category-title:not(:last-child) { margin-block-end: var(--md-single); }

.category-view .box-group {
	display: flex;
	flex-direction: column;
	padding: var(--md-single);
}

.category-view.box-style .post-footer {
	margin-top: auto;
	padding-block-start: var(--md-half);
}

/* QUERIES */

@media (min-width: <?php echo $post_width; ?>px) {
	.box-entry.full .item { padding: var(--md-mid); }
	.cover { padding-block: var(--md-mid); }
	.entry .cover, .content .page-title.cover { padding-inline: var(--md-mid); }
	.loop.slim .title {
		font-size: var(--md-h4);
		line-height: var(--md-h4-line-height);
	}
}