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

.full .the-content > .featured-media,
.full .the-content > :where(.wrap) > .featured-media,
.full:not(.box-style) > :where(.entry.image-full) > .featured-media:not(:last-child) { margin-block-end: var(--md-single); }

/* CAPTIONS */

.wp-caption {
	height: auto;
	max-width: 100%;
}

.wp-caption-text, .wp-element-caption {
	font-size: var(--md-font-size-small);
	font-style: italic;
	line-height: var(--md-line-height-small);
	margin-block-start: 0;
	padding: var(--md-third);
	text-align: center;
}

.entry .wp-caption-text, .wp-element-caption {
	border-bottom: var(--md-border-width) solid var(--md-border);
	color: var(--md-text-muted);
}

.cover .wp-caption-text {
	background-color: var(--md-shade-strong);
	position: absolute;
		inset-block-end: 0;
		inset-inline-end: 0;
	z-index: 10;
}

/* AUTHOR BOX */

.author-box > .wrap {
	display: flex;
	flex-direction: column;
	gap: var(--md-half);
}

.plain-style .author-box > .wrap {
	background-color: var(--md-tint);
	border-radius: var(--md-border-radius);
	padding: var(--md-single);
}

.author-meta {
	align-items: center;
	display: flex;
	gap: calc(var(--md-half) + var(--md-small));
}

.author-avatar { flex: 0 0 calc(var(--md-double) + var(--md-half)); }

.author-heading {
	display: flex;
	flex-direction: column;
	gap: var(--md-small);
}

.author-avatar img {
	display: block;
	height: auto;
	width: 100%;
}

.author-heading > *, .author-description > :last-child { margin: 0; }

.author-subtitle {
	color: var(--md-text-muted);
	font-size: var(--md-font-size-small);
	line-height: var(--md-line-height-small);
}

.author-title {
	font-size: var(--md-h4);
	font-weight: var(--md-bold);
	line-height: var(--md-h4-line-height);
}

.author-description, .author-links {
	font-size: var(--md-font-size-small);
	line-height: var(--md-line-height-small);
}

.author-links {
	display: flex;
	flex-direction: column;
	gap: var(--md-third);
}

.author-sites, .author-profiles {
	display: grid;
	gap: var(--md-third) var(--md-half);
	grid-template-columns: repeat(2, minmax(0, 1fr));
	list-style: none;
	margin: 0;
}

:is(.author-sites, .author-profiles) li { margin: 0; }

.author-sites { grid-template-columns: minmax(0, 1fr); }

.author-box .author-link {
	align-items: center;
	display: inline-flex;
	gap: var(--md-third);
}

.author-profiles .author-link {
	color: var(--md-text-muted);
	text-decoration: none;
}

.author-profiles .author-link:hover { color: var(--md-text); }

.author-website .author-link { font-weight: var(--md-bold); }

@media (min-width: 600px) {
	.author-sites, .author-profiles {
		column-gap: var(--md-single);
		display: flex;
		flex-wrap: wrap;
	}
}

/* PAGINATION */

.pagination:not(:last-child) { margin-block-end: var(--md-single); }

.prev-next {
	display: flex;
	justify-content: space-between;
}

ul.page-numbers, .post-nav-links {
	align-items: center;
	display: flex;
	flex-wrap: wrap;
	gap: var(--md-third);
}

ul.page-numbers {
	justify-content: center;
	list-style: none;
}

.page-numbers li { margin-block-end: 0; }

.post-nav-links {
	background-color: var(--md-tint);
	border-radius: var(--md-border-radius);
	padding: var(--md-half);
}

.pagination :is(a, span), .post-page-numbers {
	align-items: center;
	border-radius: var(--md-border-radius);
	color: var(--md-text-muted);
	display: inline-flex;
	gap: var(--md-third);
	padding: var(--md-third) var(--md-half);
	text-decoration: none;
}

.pagination a:hover, a.post-page-numbers:hover { background-color: var(--md-tint); }

.pagination :is(.prev, .next) {
	color: var(--md-text);
	font-weight: var(--md-bold);
}

.pagination .current, .post-page-numbers.current {
	background-color: var(--md-text);
	color: var(--md-site-background);
	font-weight: var(--md-bold);
}

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
	font-size: var(--md-font-size-small);
	line-height: var(--md-line-height-small);
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

.post-nav { margin-block-start: var(--md-single); }

.main:has(> .post-nav:not(.box):last-child) { padding-block-end: 0; }

.post-nav.box {
	overflow: clip;
	padding-inline: 0;
}

.post-nav.box :where(.post-nav-previous, .post-nav-next) { padding-inline: var(--md-single); }

.post-nav.box :where(.post-nav-previous:hover, .post-nav-next:hover) { background-color: var(--md-color-surface); }

.post-nav :where(.post-nav-previous:hover, .post-nav-next:hover) .post-nav-title { text-decoration: underline; }

@media (min-width: <?php echo $post_width; ?>px) {
	.post-nav {
		align-items: stretch;
		display: flex;
		flex-flow: wrap;
	}
	.post-nav-previous, .post-nav-next { flex: 1; }
	.post-nav-next { text-align: right; }
	.post-nav.box .post-nav-previous + .post-nav-next { border-inline-start: var(--md-border-width) solid var(--md-border); }
}

@media (max-width: <?php echo $post_width; ?>px) {
	.post-nav-next { text-align: left; }
	.post-nav.box :where(.post-nav-previous, .post-nav-next) { padding-inline: var(--md-half); }
	.post-nav.box .post-nav-previous + .post-nav-next { border-block-start: var(--md-border-width) solid var(--md-border); }
}
