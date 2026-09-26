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

.expanded { --md-loop-content-width: var(--md-width-post); }

.expanded .entry .item:not(.post-title) > .wrap,
.expanded .content-wrap.builder > .item:not(.post-title) > .wrap {
	margin-inline: auto;
	max-width: var(--md-loop-content-width);
	width: 100%;
}

.loop.columns {
	--md-columns: var(--md-loop-columns);
	--md-columns-base: var(--md-loop-columns-mobile, 1);
}

.loop.has-mobile-columns { --md-columns-mobile: var(--md-loop-columns-mobile, 2); }

.archive-sections-before-loop { margin-block-end: var(--md-single-x); }

.archive-sections-after-loop { margin-block-start: var(--md-single-x); }

.sidebar .archive-sections { --md-columns-base: 1; }

/* SLIM */

.loop.columns.slim { gap: var(--md-half); }

.slim.box-entry > .entry > .entry-title:not(.cover):not(:empty),
.slim.box-entry > .entry > :not(.loop) > .entry-title:not(.cover):not(:empty) { padding-block-end: var(--md-half); }

/* BOX STYLE */

.box-style, .box {
	--md-text: var(--md-content-box-text);
	--md-text-muted: var(--md-content-box-text-muted);
	--md-links: var(--md-content-box-links);
	--md-links-muted: var(--md-content-box-text-muted);
	--md-headlines: var(--md-content-box-text);
	--md-headline-links: var(--md-content-box-text);
	--md-border: var(--md-content-box-border);
	--md-tag-background: var(--md-color-surface);
}

:is(.box-entry, .loop-article.box-group) { --md-loop-item-padding: var(--md-single) var(--md-half); }

.loop-article.box-group { --md-loop-item-padding: var(--md-half) 0; }

.box {
	background-color: var(--md-content-box-background);
	color: var(--md-text);
}

.box,
.content .page-title.cover,
.entry .cover,
.content-wrap.builder > .cover,
.featured-media { border-radius: var(--md-border-radius); }

.cover .overlay, .featured-media :is(a, img) { border-radius: inherit; }

.box-entry > .entry > .cover,
.box-entry > .entry > :not(.loop) > .cover,
.box-entry > .entry.image-above > .featured-media,
.box-entry > .entry.image-above > :not(.loop) > .featured-media,
.header-cover .box-entry > .entry.image-full > .featured-media,
.header-cover .box-entry > .entry.image-full > :not(.loop) > .featured-media { border-radius: var(--md-border-radius) var(--md-border-radius) 0 0; }

.box-entry > .entry.image-above > :is(.cover, .featured-media + .item),
.box-entry > .entry.image-above > :not(.loop) > :is(.cover, .featured-media + .item) { border-radius: 0 0 var(--md-border-radius) var(--md-border-radius); }

.box-entry > .entry.image-below > .featured-media,
.box-entry > .entry.image-below > .featured-media + .the-content,
.box-entry > .entry.image-below > :not(.loop) > .featured-media,
.box-entry > .entry.image-below > :not(.loop) > .featured-media + .the-content { border-radius: 0; }

.box, .cover, .featured-media img { box-shadow: var(--md-box-shadow); }

.box-entry > .entry,
.box-group,
.box-entry > .entry.image-full > .featured-media,
.box-entry > .entry.image-full > :not(.loop) > .featured-media,
.box-entry > .entry > .comments .comment-details {
	background-color: var(--md-content-box-background);
	color: var(--md-text);
}

.box-group, .box-entry > .entry {
	border-radius: var(--md-border-radius);
	box-shadow: var(--md-box-shadow);
}

.entry.box-group > .loop.box-group {
	background: none;
	border-radius: 0;
	box-shadow: none;
}

.box-group { padding: var(--md-single-x); }

.entry.box-group > .box-group { padding: 0; }

.box-entry > .entry > :is(.post-footer, .item-sep),
.box-entry > .entry > :not(.loop) > :is(.post-footer, .item-sep),
.box-entry > .entry > :not(.loop) > .wrap > :is(.post-footer, .item-sep),
.box-style.category-view > .entry > :is(.post-footer, .item-sep) { border-block-start: 1px solid var(--md-border); }

.box, .box-entry > .entry { width: auto; }

.single .content.loop > .entry:not(:last-child),
.row > .box-group:not(:last-child),
.box-entry.row.full > .item:not(:last-child),
.box-entry.row.full > .entry:not(:last-child) { margin-block-end: var(--md-single); }

.box-entry.loop-article.full > .entry { margin-inline: calc(-1 * clamp(0px, calc((100vw - var(--md-width-site)) / -2), var(--md-half))); }

.box-entry > .entry > :where(.item),
.box-entry > .entry > :not(.loop) > :where(.item),
.loop-article.box-group > .entry > :where(.item),
.loop-article.box-group > .entry > :not(.loop) > :where(.item) { padding: var(--md-loop-item-padding); }

.loop-article.box-group > .entry:first-child > :where(.item) { padding-block-start: 0; }
.loop-article.box-group > .entry:last-child > :where(.item) { padding-block-end: 0; }

.box-entry > .entry > .byline.post-footer,
.box-entry > .entry > :not(.loop) > .byline.post-footer { padding: var(--md-half); }

.box-entry > .entry > .byline.entry-top,
.box-entry > .entry > :not(.loop) > .byline.entry-top {
	border-block-end: 1px solid var(--md-border);
	padding: var(--md-third) var(--md-half);
}

.box-entry .entry-title:not(.cover, :empty) + .the-content { padding-block-start: 0; }

/* PLAIN + BORDER STYLES */

.expanded :is(.plain-style, .border-style).loop-article.row > .entry:not(:last-child) { margin-block-end: var(--md-double); }

.compact :is(.plain-style, .border-style).loop-article.row > .entry,
:is(.plain-style, .border-style).row > .entry > .post-title:not(:last-child),
:is(.plain-style, .border-style).row > .entry > :not(.loop) > .post-title:not(:last-child),
:is(.plain-style, .border-style).loop-article.row > .entry > .item:not(:last-child) { margin-block-end: var(--md-single); }

:is(.plain-style, .border-style).columns > .entry > *:not(:last-child) { margin-block-end: var(--md-half); }

:is(.plain-style, .border-style).loop-article.image-above.has-cover > .featured-media,
:is(.plain-style, .border-style).loop-article.image-below.has-cover > .post-title { margin-block-end: 0; }

/* BORDER STYLE */

.border-style.row > .entry:not(:first-child) {
	border-block-start: 1px solid var(--md-border);
	padding-block-start: var(--md-mid);
}

.border-style.row > :is(.entry.has-cover, .entry.image-above) {
	border-block-start: 0;
	padding-block-start: 0;
}

/* LOOP LIST */

.loop-list > .entry {
	border-block-end: 1px solid var(--md-border);
	display: flex;
	flex-direction: column;
	gap: var(--md-half);
	padding-block: var(--md-half);
}

.loop-list > .entry:first-child { padding-block-start: 0; }

.loop-list > .entry:last-child {
	border-block-end: 0;
	padding-block-end: 0;
}

.loop-list > .entry > .post-title .title {
	font-family: inherit;
	font-size: inherit;
	font-weight: normal;
	line-height: inherit;
}

.loop-list > .entry > .post-title .title a {
	color: var(--md-links);
	text-decoration: underline;
}

.loop-list > .entry > :is(.post-title, .the-content) {
	margin-inline: auto;
	max-width: var(--md-width-post);
	width: 100%;
}

.loop-list > .entry > .post-title .title a:hover { text-decoration: none; }

/* LOOP DATES */

.loop-date-title {
	font-size: var(--md-h3);
	line-height: var(--md-h3-line-height);
	margin-block-end: var(--md-single);
}

.loop-dates:not(:last-child) { margin-block-end: var(--md-mid); }

/* CATEGORY VIEWS */

.category-title { row-gap: var(--md-small); }

.categories.row > .entry:not(:last-child) { margin-block-end: var(--md-single); }

.category-posts > .entry > .category-title { margin-block-end: var(--md-half); }

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

:is(.category-view, .category-posts) > .entry > .category-title:not(:last-child) { margin-block-end: var(--md-single); }

:is(.category-view, .category-posts) > .box-group {
	display: flex;
	flex-direction: column;
}

:is(.category-view, .category-posts).box-style > .entry > .post-footer {
	margin-top: auto;
	padding-block-start: var(--md-half);
}

/* QUERIES */

@media (min-width: <?php echo $post_width; ?>px) {
	.box-entry.full { --md-loop-item-padding: var(--md-mid); }
	.loop.slim .title {
		font-size: var(--md-h4);
		line-height: var(--md-h4-line-height);
	}
}
