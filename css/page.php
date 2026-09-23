<style type="text/css">

/* SKIP TO CONTENT */

.skip-to-content {
	background-color: var(--md-action-secondary);
	border-radius: var(--md-border-radius);
	color: var(--md-action-secondary-text);
    position: absolute;
	    inset-inline-start: var(--md-small);
	    inset-block-start: -100%;
    padding: var(--md-half) var(--md-single);
    transition: top var(--md-transition);
    z-index: 100;
}

.skip-to-content:focus { top: var(--md-small); }

.admin-bar :is(.skip-to-conten, .skip-to-content:focus) { top: calc(var(--wp-admin--admin-bar--height) + var(--md-small)); }

/* FEATURED IMAGE */

.featured-media {
	/*
	margin-inline: auto;
	*/
	position: relative;
	z-index: 5;
}

:is(.image-center, .image-full) .featured-media { text-align: center; }

.full .the-content .featured-media,
.full:not(.box-style) > :where(.entry.image-full) > .featured-media:not(:last-child) { margin-block-end: var(--md-single); }

/* CAPTIONS */

.wp-caption {
	height: auto;
	max-width: 100%;
}

.wp-caption-text, .wp-element-caption {
	font-size: var(--md-font-size-sm);
	font-style: italic;
	line-height: var(--md-line-height-sm);
	margin-block-start: 0;
	padding: var(--md-third);
	text-align: center;
}

.entry .wp-caption-text, .wp-element-caption {
	border-bottom: 1px solid var(--md-border);
	color: var(--md-text-muted);
}

.cover .wp-caption-text {
	background-color: rgba(0, 0, 0, 0.75);
	position: absolute;
		inset-block-end: 0;
		inset-inline-end: 0;
	z-index: 10;
}

/* AUTHOR BOX */

.author-meta {
	align-items: center;
	display: flex;
	margin-block-end: var(--md-half);
}

.author-title {
	font-size: var(--md-h4);
	font-weight: var(--md-bold);
	line-height: var(--md-h4-line-height);
	margin-block-end: var(--md-small);
}

.author-description { margin-block-end: calc(var(--md-half) + var(--md-small)); }

.author-avatar {
	flex: 0 1 var(--md-double);
	margin-inline-end: var(--md-half);
}

.author-avatar img { width: 100%; }

.author-links {
	align-items: center;
	display: flex;
	font-size: var(--md-font-size-sm);
	gap: var(--md-half);
	line-height: var(--md-line-height-sm);
}

.author-links .author-link {
	display: flex;
	gap: var(--md-third);
}

.author-link:not(:last-child) { margin-inline-end: var(--md-small); }

.author-link.twitter .circle-icon {
	background-color: #000;
	color: var(--md-site-text-contrast);
}

.author-link.twitter .md-icon-twitter { color: var(--md-site-text-contrast); }

.author-link.twitter a { color: #1da1f2; }

@media all and (max-width: 700px) {
	.author-links { flex-flow: wrap; }
	.author-link { flex-basis: calc(50% - var(--md-half)); }
}

/* PAGINATION */

.pagination:not(:last-child) { margin-block-end: var(--md-single); }

.prev-next {
	display: flex;
	justify-content: space-between;
}

.post-nav-links {
	background-color: rgba(0, 0, 0, 0.05);
	border-radius: var(--md-border-radius);
	box-shadow: var(--md-box-shadow);
	padding: var(--md-half);
}

.pagination .page-numbers { text-decoration: none; }

ul.page-numbers {
	align-items: center;
	display: flex;
	justify-content: center;
	gap: var(--md-third);
	list-style: none;
}

span.page-numbers, a.page-numbers, .post-nav-links .post-page-numbers {
	background-color: var(--md-content-box-background);
	border-radius: var(--md-border-radius);
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	padding: var(--md-third) var(--md-half);
}

:is(span.page-numbers, a.page-numbers):not(.dots):not(.prev):not(.next),
.post-nav-links .post-page-numbers {
	--md-text: var(--md-content-box-text);
	--md-text-muted: var(--md-content-box-text-muted);
	--md-links: var(--md-content-box-links);
	color: var(--md-text);
}

.page-numbers li { margin-block-end: 0; }

.page-numbers.current, .post-page-numbers.current {
	cursor: default;
	font-weight: var(--md-bold);
}

span.page-numbers:hover, a.page-numbers:hover, .post-nav-links.post-page-numbers:hover { opacity: 0.8; }

.page-numbers.prev, .page-numbers.next { text-decoration: underline; }

.page-numbers.dots, .page-numbers.prev, .page-numbers.next {
	background-color: transparent;
	border-radius: inherit;
	box-shadow: none;
	border: 0;
	padding: 0;
}

.page-numbers:not(.prev):not(.next):not(.current) { color: var(--md-text-muted); }

.prev-icon, .page-numbers.prev { margin-inline-end: var(--md-third); }

.next-icon, .page-numbers.next { margin-inline-start: var(--md-third); }

/* POST NAV */

.post-nav a {
	color: var(--md-headline-links);
	display: block;
	text-decoration: none;
}

.post-nav-previous, .post-nav-next {
	align-items: center;
	display: flex;
	gap: var(--md-half);
	padding-block: var(--md-single);
	position: relative;
	transition: background-color var(--md-transition);
}

.post-nav-text {
	flex: 1;
	min-width: 0;
}

.post-nav-direction {
	color: var(--md-text-muted);
	display: block;
	font-size: var(--md-font-size-sm);
	line-height: var(--md-line-height-sm);
	margin-block-end: var(--md-small);
}

.post-nav-media {
	flex-shrink: 0;
	width: var(--md-double);
}

.post-nav-media img {
	border-radius: var(--md-border-radius);
	box-shadow: var(--md-box-shadow-small);
}

.box-style .post-nav {
	border-block-start: 1px solid var(--md-border);
	border-end-start-radius: inherit;
	border-end-end-radius: inherit;
	overflow: clip;
}

.box-style :where(.post-nav-previous, .post-nav-next) { padding-inline: var(--md-single); }

.box-style :where(.post-nav-previous:hover, .post-nav-next:hover) { background-color: var(--md-color-surface); }

.post-nav :where(.post-nav-previous:hover, .post-nav-next:hover) .post-nav-title { text-decoration: underline; }

@media (min-width: <?php echo $post_width; ?>px) {
	.post-nav {
		align-items: stretch;
		display: flex;
		flex-flow: wrap;
	}
	.post-nav-previous, .post-nav-next { flex: 1; }
	.post-nav-next { text-align: right; }
	.box-style .post-nav-previous + .post-nav-next { border-inline-start: 1px solid var(--md-border); }
}

@media (max-width: <?php echo $post_width; ?>px) {
	.post-nav-next { text-align: left; }
	.box-style .post-nav-previous + .post-nav-next { border-block-start: 1px solid var(--md-border); }
}
