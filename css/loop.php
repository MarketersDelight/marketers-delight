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

.expanded .entry > .item:not(.post-title) > .wrap {
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
.box-entry.columns .entry,
.box-group {
	--md-text: var(--md-content-box-text);
	--md-text-muted: var(--md-content-box-text-muted);
	--md-links: var(--md-content-box-links);
	--md-links-muted: var(--md-content-box-text-muted);
	--md-headlines: var(--md-content-box-text);
	--md-headline-links: var(--md-content-box-text);
	--md-border: var(--md-content-box-border);
	background-color: var(--md-content-box-background);
	color: var(--md-text);
}

.box-entry.columns .entry .item {
	background-color: transparent;
	box-shadow: none;
}

.box-entry .byline.post-footer,
.box-style.category-view .post-footer { border-block-start: 1px solid var(--md-border); }

.box-entry .post-footer { border-block-start: 1px solid var(--md-border); }

.box, .box-entry .entry { width: auto; }

.row .box-group:not(:last-child),
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
}

/* PLAIN + BORDER STYLES */

.expanded :is(.plain-style, .border-style).loop-article.row > .entry:not(:last-child) { margin-block-end: var(--md-double); }

.compact :is(.plain-style, .border-style).loop-article.row > .entry { margin-block-end: var(--md-single); }

:is(.plain-style, .border-style).row .post-title:not(:last-child),
:is(.plain-style, .border-style).loop-article.row > .entry > .item:not(:last-child) { margin-block-end: var(--md-single); }

:is(.plain-style, .border-style).columns .entry > *:not(:last-child) { margin-block-end: var(--md-half); }

:is(.plain-style, .border-style).loop-article.image-above.has-cover > .featured-media,
:is(.plain-style, .border-style).loop-article.image-below.has-cover > .post-title { margin-block-end: 0; }

/* BORDER STYLE */

.is-border-style .header:not(.cover) { border-block-end: 1px solid var(--md-header-border); }

.is-border-style .footer { border-block-start: 1px solid var(--md-footer-border); }

.is-border-style .page-title:not(.cover) {
	border-block-end: 1px solid var(--md-border);
	padding-block-end: var(--md-mid);
}

.border-style.row .entry:not(:first-child) {
	border-block-start: 1px solid var(--md-border);
	padding-block-start: var(--md-mid);
}

.border-style.row :is(.entry.has-cover, .entry.image-above) {
	border-block-start: 0;
	padding-block-start: 0;
}

/* LOOP LIST */

.loop-list > .entry {
	border-block-end: 1px solid var(--md-border);
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

/* LOOP TIMELINE */

.loop-timeline {
	--tl-cap: calc(var(--md-small) * 3);
	--tl-center: calc(var(--md-half) + var(--md-mid) / 2);
	--tl-dot: var(--md-mid);
	--tl-rail: var(--md-small);
}

.expanded .loop-timeline {
	margin-inline: auto;
	max-width: var(--md-width-content);
	width: 100%;
}

.expanded .box-style.loop-timeline { max-width: calc(var(--md-width-content) + var(--md-mid) * 2); }

.loop-timeline .post-title.wide {
	align-items: inherit;
	text-align: inherit;
}

.box-style.loop-timeline .entry { box-shadow: none; }

.box-style.loop-timeline .timeline-wrap,
.box-style.loop-timeline .entry > .item {
	border-radius: var(--md-border-radius);
	box-shadow: var(--md-box-shadow);
}

.loop-timeline .timeline-wrap {
	margin-inline-start: calc(var(--md-mid) + var(--md-half));
	position: relative;
}

.loop-timeline.row.full > .entry:not(:last-child) { margin-block-end: var(--md-mid); }

.loop-timeline .timeline-wrap:not(:last-child) { margin-block-end: var(--md-single); }

.loop-timeline .timeline-wrap:before,
.loop-timeline > .entry:last-child > .timeline-wrap:after,
.single .loop-timeline.content > .entry .timeline-wrap:after {
	background-color: var(--md-border);
	content: '';
	display: block;
	position: absolute;
	transition: var(--md-transition);
}

.loop-timeline .timeline-wrap:hover:before,
.loop-timeline > .entry:last-child > .timeline-wrap:hover:after,
.single .loop-timeline.content > .entry .timeline-wrap:hover:after { background-color: var(--md-links); }

.loop-timeline .timeline-wrap:before {
	height: 100%;
	inset-block-end: 0;
	inset-inline-start: calc(-1 * (var(--tl-center) + var(--tl-rail) / 2));
	width: var(--tl-rail);
}

.loop-timeline > .entry:last-child > .timeline-wrap:after,
.single .loop-timeline.content > .entry > .timeline-wrap:after {
	border-radius: 50%;
	height: var(--tl-cap);
	inset-block-end: -1px;
	inset-inline-start: calc(-1 * (var(--tl-center) + var(--tl-cap) / 2));
	width: var(--tl-cap);
}

.archive .loop-timeline > .entry:not(:last-child) > .timeline-wrap:before { inset-block-end: calc(-1 * var(--md-mid)); }

.loop-timeline .timeline-dot {
	align-items: center;
	background-color: var(--md-content-main-background);
	border: 4px solid var(--md-action-secondary);
	border-radius: 50%;
	color: var(--md-action-secondary);
	display: flex;
	font-size: var(--md-h5);
	justify-content: center;
}

.loop-timeline .timeline-wrap:hover .timeline-dot {
	border-color: var(--md-links);
	color: var(--md-links);
}

.loop-timeline .timeline-dot, .loop-timeline .byline .avatar {
	height: var(--tl-dot);
	position: absolute;
		inset-block-start: 0;
		inset-inline-start: calc(-1 * (var(--tl-center) + var(--tl-dot) / 2));
	transition: var(--md-transition);
	width: var(--tl-dot);
}

.loop-timeline .byline .avatar {
	margin: 0;
	z-index: 10;
}

.loop-timeline .timeline-wrap:hover > .timeline-dot,
.loop-timeline > .entry:last-child > .timeline-wrap:hover:after { transform: scale(1.15); }

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

@media (max-width: 800px) {
	.loop-timeline {
		--tl-center: calc((var(--md-half) * 2 + var(--md-small)) / 2);
		--tl-dot: calc(var(--md-half) + var(--md-third));
		--tl-rail: 3px;
	}

	.loop-timeline.row.full > .entry:not(:last-child) { margin-block-end: calc(var(--md-single) - var(--md-small)); }
	.archive .loop-timeline > .entry:not(:last-child) > .timeline-wrap:before { inset-block-end: calc(-1 * (var(--md-single) - var(--md-small))); }
	.loop-timeline .timeline-wrap { margin-inline-start: calc(var(--md-half) + var(--md-small)); }
	.loop-timeline .timeline-dot { border-width: 2px; font-size: 0.75em; }
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
