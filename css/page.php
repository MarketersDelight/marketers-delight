<style type="text/css">

/* SKIP TO CONTENT */

.skip-to-content {
    background-color: var(--md-color-secondary);
	border-radius: var(--md-border-radius);
    color: var(--md-color-white);
    position: absolute;
	    inset-inline-start: var(--md-small);
	    inset-block-start: -100%;
    padding: var(--md-half) var(--md-single);
    transition: top var(--md-transition);
    z-index: 100;
}

.skip-to-content:focus { top: var(--md-small); }

.admin-bar :is(.skip-to-conten, .skip-to-content:focus) { top: calc(var(--wp-admin--admin-bar--height) + var(--md-small)); }

/* BREADCRUMBS */

.breadcrumbs {
	font-size: var(--md-font-size-sm);
	line-height: var(--md-line-height-sm);
	margin-block-end: var(--md-half);
}

.breadcrumbs ol {
	list-style: none;
	overflow-x: scroll;
	scrollbar-width: none;
	white-space: nowrap;
}

.breadcrumbs li {
	display: inline-block;
	margin-block-end: 0;
}

.breadcrumbs li:not(:last-child):after {
	content: '\e80f';
	margin-inline: var(--md-third);
}

.breadcrumbs-home:before {
	content: '\e907';
	margin-inline-end: var(--md-third);
}

.breadcrumbs a { text-decoration: underline; }

.breadcrumbs a:hover { text-decoration: none; }

@media (max-width: 900px) {
	.is-box-style .expanded .breadcrumbs:first-child,
	.is-box-style .cover + .breadcrumbs,
	.is-box-style .compact .breadcrumbs:first-child { margin-block-start: -<?php echo $half; ?>px; }
}

/* FEATURED IMAGE */

.featured-media {
	/*
	margin-inline: auto;
	*/
	position: relative;
	z-index: 5;
}

:is(.image-center, .image-full) .featured-media { text-align: center; }

.full .the-content .featured-media { margin-block-end: var(--md-single); }

/* CAPTIONS */

.wp-caption {
	height: auto;
	max-width: 100%;
}

.wp-caption-text, .wp-element-caption {
	font-size: var(--md-font-size-sm);
	font-style: italic;
	line-height: var(--md-line-height-sm);
	padding: var(--md-third);
	text-align: center;
}

.entry .wp-caption-text, .wp-element-caption {
	border-bottom: 1px solid var(--md-content-border);
	color: var(--md-color-text-secondary);
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

.author-box .circle-icon { margin-inline-end: var(--md-third); }

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

.author-link:not(:last-child) { margin-inline-end: var(--md-small); }

.author-link.twitter .circle-icon {
	background-color: #000;
	color: var(--md-color-white);
}

.author-link.twitter .md-icon-twitter { color: var(--md-color-white); }

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
	background-color: var(--md-content);
	border-radius: var(--md-border-radius);
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	padding: var(--md-third) var(--md-half);
}

.page-numbers li { margin-block-end: 0; }

.page-numbers.current, .post-page-numbers.current {
	cursor: default;
	font-weight: bold;
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

.page-numbers:not(.prev):not(.next):not(.current) { color: var(--md-color-text-secondary); }

.prev-icon, .page-numbers.prev { margin-inline-end: var(--md-third); }

.next-icon, .page-numbers.next { margin-inline-start: var(--md-third); }

/* POST NAV */

.post-nav a { display: block; }

.post-nav-next, .post-nav-previous { position: relative; }

@media (min-width: <?php echo $post_width; ?>px) {
	.post-nav {
		align-items: center;
		display: flex;
		flex-flow: wrap;
	}
	.post-nav-next, .post-nav-previous { flex: 1; }
	.post-nav-previous + .post-nav-next { text-align: right; }
}

@media (max-width: <?php echo $post_width; ?>px) {
	.post-nav-previous:not(:last-child) { margin-block-end: var(--md-single); }
}