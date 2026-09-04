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

.expanded .loop { --md-loop-content-width: var(--md-width-post); }

.expanded .entry .item:not(.post-title) > .wrap {
	margin-inline: auto;
	max-width: var(--md-loop-content-width);
	width: 100%;
}

.loop.columns { --md-columns: var(--md-loop-columns); }

.loop.has-mobile-columns { --md-columns-mobile: 2; }

.archive-sections-before-loop { margin-block-end: var(--md-single); }

.archive-sections-after-loop { margin-block-start: var(--md-single); }

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

.box-entry { --md-loop-item-padding: var(--md-single) var(--md-half); }

.box {
	background-color: var(--md-content-box-background);
	color: var(--md-text);
}

.box,
.content .page-title.cover,
.entry .cover,
.featured-media { border-radius: var(--md-border-radius); }

.cover .overlay, .featured-media img { border-radius: inherit; }

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
.box-entry.loop-timeline > .entry > :is(.timeline-wrap, .comments),
.box-group,
.box-entry > .entry.image-full > .featured-media,
.box-entry > .entry.image-full > :not(.loop) > .featured-media,
.box-entry > .entry > .comments .comment-details {
	background-color: var(--md-content-box-background);
	color: var(--md-text);
}

.box-entry > .entry,
.box-entry.loop-timeline > .entry > :is(.timeline-wrap, .comments),
.box-group {
	border-radius: var(--md-border-radius);
	box-shadow: var(--md-box-shadow);
}

.box-entry.loop-timeline > .entry {
	background-color: transparent;
	box-shadow: none;
}

.box-entry > .entry > :is(.post-footer, .item-sep),
.box-entry > .entry > :not(.loop) > :is(.post-footer, .item-sep),
.box-entry > .entry > :not(.loop) > .wrap > :is(.post-footer, .item-sep),
.box-style.category-view > .entry > :is(.post-footer, .item-sep) { border-block-start: 1px solid var(--md-border); }

.box-entry.loop-timeline > .entry > .comments { border-block-start: 0; }

.box, .box-entry > .entry { width: auto; }

.single .content.loop > .entry:not(:last-child),
.row > .box-group:not(:last-child),
.box-entry.row.full > .item:not(:last-child),
.box-entry.row.full > .entry:not(:last-child) { margin-block-end: var(--md-single); }

.box-entry.loop-article.full > .entry { margin-inline: calc(-1 * clamp(0px, calc((100vw - var(--md-width-site)) / -2), var(--md-half))); }

.box-entry > .entry > :where(.item),
.box-entry > .entry > :not(.loop) > :where(.item) { padding: var(--md-loop-item-padding); }

.box-entry > .entry > .byline.post-footer,
.box-entry > .entry > :not(.loop) > .byline.post-footer { padding: var(--md-half); }

.box-entry > .entry > .entry-title:not(.cover):not(:empty) + .the-content,
.box-entry > .entry > :not(.loop) > .entry-title:not(.cover):not(:empty) + .the-content { padding-block-start: 0; }

/* PLAIN + BORDER STYLES */

.expanded :is(.plain-style, .border-style).loop-article.row > .entry:not(:last-child) { margin-block-end: var(--md-double); }

.compact :is(.plain-style, .border-style).loop-article.row > .entry { margin-block-end: var(--md-single); }

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
	padding: var(--md-half);
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

.loop-list > .entry > .post-title .title a:hover { text-decoration: none; }

/* LOOP TIMELINE */

.loop-timeline {
	--timeline-cap-size: calc(var(--md-small) * 3);
	--timeline-center: calc(var(--md-half) + var(--md-mid) / 2);
	--timeline-dot-size: var(--md-mid);
	--timeline-empty-dot-size: var(--md-single);
	--timeline-gap: var(--md-mid);
	--timeline-rail-width: var(--md-small);
}

.expanded .loop-timeline {
	--md-loop-content-width: 100%;
	margin-inline: auto;
	max-width: var(--md-width-content);
	width: 100%;
}

.expanded .box-style.loop-timeline { max-width: calc(var(--md-width-content) + var(--md-mid) * 2); }

.loop-timeline > .entry > .timeline-wrap > .post-title > .byline.before-title {
	border-block-end: 1px solid var(--md-border);
	padding-block-end: var(--md-half);
}

.loop-timeline > .entry > .timeline-wrap > .post-title .byline .share:last-child { margin-inline-start: auto; }

.loop-timeline .post-title.wide {
	align-items: inherit;
	text-align: inherit;
}

.loop-timeline .timeline-wrap {
	margin-inline-start: calc(var(--md-mid) + var(--md-half));
	position: relative;
}

.single .loop-timeline > .entry > .timeline-wrap:not(:last-child) { margin-block-end: var(--md-single); }

.loop-timeline:not(.content) > .entry:not(:last-child) { margin-block-end: var(--timeline-gap); }

.loop-timeline .timeline-wrap:before,
.loop-timeline:not(.content) > .entry:last-child > .timeline-wrap:after,
.single .loop-timeline.content > .entry > .timeline-wrap:after {
	background-color: var(--md-border);
	content: '';
	display: block;
	position: absolute;
	transition: var(--md-transition);
}

.loop-timeline .timeline-wrap:hover:before,
.loop-timeline:not(.content) > .entry:last-child > .timeline-wrap:hover:after,
.single .loop-timeline.content > .entry > .timeline-wrap:hover:after { background-color: var(--md-links); }

.loop-timeline .timeline-wrap:before {
	height: 100%;
	inset-block-end: 0;
	inset-inline-start: calc(-1 * (var(--timeline-center) + var(--timeline-rail-width) / 2));
	width: var(--timeline-rail-width);
}

.loop-timeline:not(.content) > .entry:last-child > .timeline-wrap:after,
.single .loop-timeline.content > .entry > .timeline-wrap:after {
	border-radius: 50%;
	height: var(--timeline-cap-size);
	inset-block-end: -1px;
	inset-inline-start: calc(-1 * (var(--timeline-center) + var(--timeline-cap-size) / 2));
	width: var(--timeline-cap-size);
}

.loop-timeline:not(.content) > .entry:not(:last-child) > .timeline-wrap:before {
	height: calc(100% + var(--timeline-gap));
	inset-block-end: calc(-1 * var(--timeline-gap));
}

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
	height: var(--timeline-dot-size);
	position: absolute;
		inset-block-start: 0;
		inset-inline-start: calc(-1 * (var(--timeline-center) + var(--timeline-dot-size) / 2));
	transition: var(--md-transition);
	width: var(--timeline-dot-size);
}

.loop-timeline .timeline-dot:empty {
	height: var(--timeline-empty-dot-size);
	inset-inline-start: calc(-1 * (var(--timeline-center) + var(--timeline-empty-dot-size) / 2));
	width: var(--timeline-empty-dot-size);
}

.loop-timeline .byline .avatar {
	margin: 0;
	z-index: 10;
}

.loop-timeline .timeline-wrap:hover > .timeline-dot,
.loop-timeline:not(.content) > .entry:last-child > .timeline-wrap:hover:after { transform: scale(1.15); }

@media (max-width: 800px) {
	.loop-timeline {
		--timeline-center: calc((var(--md-half) * 2 + var(--md-small)) / 2);
		--timeline-dot-size: calc(var(--md-half) + var(--md-third));
		--timeline-empty-dot-size: calc(var(--timeline-dot-size) - var(--md-small));
		--timeline-gap: calc(var(--md-single) - var(--md-small));
		--timeline-rail-width: 3px;
	}

	.loop-timeline .timeline-wrap { margin-inline-start: calc(var(--md-half) + var(--md-small)); }
	.loop-timeline .timeline-dot { border-width: 2px; font-size: 0.75em; }
}

/* CATEGORY VIEWS */

.category-title { row-gap: var(--md-small); }

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

.category-view > .entry > .category-title:not(:last-child) { margin-block-end: var(--md-single); }

.category-view > .box-group {
	display: flex;
	flex-direction: column;
	padding: var(--md-single);
}

.category-view.box-style > .entry > .post-footer {
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
