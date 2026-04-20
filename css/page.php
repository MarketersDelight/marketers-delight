<style type="text/css">

/* BREADCRUMBS */

.breadcrumbs {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	margin-block-end: <?php echo $half; ?>px;
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
	margin-inline: <?php echo $third; ?>px;
}

.breadcrumbs-home:before {
	content: '\e907';
	margin-inline-end: <?php echo $third; ?>px;
}

.breadcrumbs a { text-decoration: underline; }

.breadcrumbs a:hover { text-decoration: none; }

@media (max-width: <?php echo $post_width; ?>px) {
	.is-box-style .expanded .breadcrumbs:first-child,
	.is-box-style .cover + .breadcrumbs { margin-top: -<?php echo $half; ?>px; }
}

@media (max-width: 900px) {
	.is-box-style .compact .breadcrumbs:first-child { margin-top: -<?php echo $half; ?>px; }
}

/* FEATURED IMAGE */

.featured-media {
	margin-inline: auto;
	position: relative;
	z-index: 5;
}

:is(.image-center, .image-full) .featured-media { text-align: center; }

/* CAPTIONS */

.wp-caption {
	height: auto;
	max-width: 100%;
}

.wp-caption-text, .wp-element-caption {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	padding: <?php echo $third; ?>px;
	text-align: center;
}

.entry .wp-caption-text, .wp-element-caption {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	color: <?php echo $colors['site']['text-sec']; ?>;
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
	margin-block-end: <?php echo $half; ?>px;
}

.author-box .circle-icon { margin-inline-end: <?php echo $third; ?>px; }

.author-title {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
	margin-block-end: <?php echo $small; ?>px;
}

.author-description { margin-block-end: <?php echo $half + $small; ?>px; }

.author-avatar {
	flex: 0 1 <?php echo $double; ?>px;
	margin-inline-end: <?php echo $half; ?>px;
}

.author-avatar img { width: 100%; }

.author-links {
	align-items: center;
	display: flex;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	gap: <?php echo $half; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.author-link:not(:last-child) { margin-inline-end: <?php echo $small; ?>px; }

.author-link.twitter .circle-icon {
	background-color: #000;
	color: #fff;
}

.author-link.twitter .md-icon-twitter { color: #fff; }

.author-link.twitter a { color: #1da1f2; }

@media all and (max-width: 700px) {
	.author-links { flex-flow: wrap; }
	.author-link { flex-basis: calc(50% - <?php echo $half; ?>px); }
}

/* PAGINATION */

.pagination:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.prev-next {
	display: flex;
	justify-content: space-between;
}

.post-nav-links {
	background-color: rgba(0, 0, 0, 0.05);
	border-radius: 8px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
	padding: <?php echo $half; ?>px;
}

.pagination .page-numbers { text-decoration: none; }

ul.page-numbers {
	align-items: center;
	display: flex;
	justify-content: center;
	gap: <?php echo $third; ?>px;
	list-style: none;
}

span.page-numbers, a.page-numbers,
.post-nav-links .post-page-numbers {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 8px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	padding: <?php echo $third; ?>px <?php echo $half; ?>px;
}

.page-numbers li { margin-block-end: 0; }

.page-numbers.current,
.post-page-numbers.current {
	cursor: default;
	font-weight: bold;
}

span.page-numbers:hover, a.page-numbers:hover,
.post-nav-links.post-page-numbers:hover { opacity: 0.8; }

.page-numbers.prev, .page-numbers.next { text-decoration: underline; }

.page-numbers.dots,
.page-numbers.prev, .page-numbers.next {
	background-color: transparent;
	border-radius: inherit;
	box-shadow: none;
	border: 0;
	padding: 0;
}

.page-numbers:not(.prev):not(.next):not(.current) { color: <?php echo $colors['site']['text-sec']; ?>; }

.prev-icon, .page-numbers.prev { margin-inline-end: <?php echo $third; ?>px; }
.next-icon, .page-numbers.next { margin-inline-start: <?php echo $third; ?>px; }

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
	.post-nav-previous:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }
}