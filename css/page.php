<style type="text/css">

/* BREADCRUMBS */

.breadcrumbs {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	margin-bottom: <?php echo $half; ?>px;
}

.breadcrumbs ol {
	list-style: none;
	overflow-x: scroll;
	scrollbar-width: none;
	white-space: nowrap;
}

.breadcrumbs li {
	display: inline-block;
	margin-bottom: 0;
}

.breadcrumbs li:not(:last-child):after {
	content: '\e80f';
	margin-left: <?php echo $third; ?>px;
	margin-right: <?php echo $third; ?>px;
}

.breadcrumbs-home:before {
	content: '\e907';
	margin-right: <?php echo $third; ?>px;
}

.breadcrumbs a { text-decoration: underline; }

.breadcrumbs a:hover { text-decoration: none; }

@media all and (max-width: 900px) {
	.box-style .breadcrumbs { padding-top: <?php echo $half; ?>px; }
}

@media all and (min-width: <?php echo $post_width; ?>px) {
	.expanded:not(.box-style) .breadcrumbs {
		margin-bottom: <?php echo $single; ?>px;
		text-align: center;
	}
}

/* BYLINE */

.byline { font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px; }

.byline, .byline a, .byline-item a, .byline .circle-icon { color: <?php echo $colors['site']['text-sec']; ?>; }

.byline:empty { display: none; }

.byline .badge, .byline-date a { text-decoration: none; }

.byline-item:not(:last-child) { margin-right: <?php echo $third; ?>px; }

.byline-item i:not(:last-child), .byline-author .avatar { margin-right: <?php echo $small; ?>px; }

.byline .author-link { text-decoration-color: rgba(0, 0, 0, 0.2); }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-sticky {
	color: #22a340;
	display: block;
	font-weight: <?php echo $bold; ?>;
	margin-bottom: <?php echo $half; ?>px;
}

/* FEATURED IMAGE */

.featured-image {
	position: relative;
	z-index: 5;
}

.loop .image-above.has-cover .featured-image { margin-bottom: 0; }

.featured-image a { display: block; }

.featured-image img {
	border-radius: 5px;
	width: 100%;
}

/* COVER */

.cover {
	background-position: center center;
	background-size: cover;
	padding-bottom: <?php echo $mid; ?>px;
	padding-top: <?php echo $mid; ?>px;
	position: relative;
}

.content-wrap .cover, .content-wrap .cover .overlay { border-radius: 5px; }

.cover > *:not(.inner):not(.overlay) { position: relative; }

.cover, .cover a,
.cover .byline,
.cover .site-name a, .cover .tagline,
.cover .menu > .menu-item > a,
.cover .menu > .menu-item > a:hover { color: #fff; }

.entry.image-center .cover,
.entry.image-below .cover { margin-bottom: 0; }

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
		bottom: 0;
		right: 0;
	z-index: 10;
}

/* AUTHOR BOX */

.author-meta {
	align-items: center;
	display: flex;
	margin-bottom: <?php echo $half; ?>px;
}

.author-box .circle-icon { margin-right: <?php echo $third; ?>px; }

.author-title {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
	margin-bottom: <?php echo $small; ?>px;
}

.author-description { margin-bottom: <?php echo $half + $small; ?>px; }

.author-avatar {
	flex: 0 1 <?php echo $double; ?>px;
	margin-right: <?php echo $half; ?>px;
}

.author-avatar img { width: 100%; }

.author-links {
	align-items: center;
	display: flex;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	gap: <?php echo $half; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.author-link:not(:last-child) { margin-right: <?php echo $small; ?>px; }

.author-link.twitter .circle-icon {
	background-color: #1da1f2;
	color: #fff;
}

.author-link.twitter .md-icon-twitter { color: #fff; }

.author-link.twitter a { color: #1da1f2; }

@media all and (max-width: 700px) {
	.author-links { flex-flow: wrap; }
	.author-link { flex-basis: calc(50% - <?php echo $half; ?>px); }
}

/* PAGINATION */

.pagination {
	justify-content: space-between;
	position: relative;
	text-align: center;
}

.pagination:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.pagination.prev-next {
	display: flex;
	justify-content: space-between;
}

.post-nav-links {
	background-color: rgba(0, 0, 0, 0.05);
	border-radius: 5px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
	padding: <?php echo $half; ?>px;
}

.pagination .page-numbers { text-decoration: none; }

.pagination .page-numbers,
.post-nav-links .post-page-numbers {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border: 0;
	border-radius: 5px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	display: inline-block;
	margin-right: <?php echo $small; ?>px;
	padding: <?php echo $small; ?>px <?php echo $half; ?>px;
}

.page-numbers.current,
.post-page-numbers.current {
	cursor: default;
	font-weight: bold;
}

.pagination .page-numbers:hover,
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

.prev-icon, .page-numbers.prev { margin-right: <?php echo $third; ?>px; }
.next-icon, .page-numbers.next { margin-left: <?php echo $third; ?>px; }

/* POST NAV */

.post-nav a { display: block; }

.post-nav-next, .post-nav-previous { position: relative; }

@media all and (min-width: <?php echo $post_width; ?>px) {
	.post-nav {
		align-items: center;
		display: flex;
		flex-flow: wrap;
	}
	.post-nav-next, .post-nav-previous { flex: 1; }
	.post-nav-previous + .post-nav-next { text-align: right; }
}

@media all and (max-width: 900px) {
	.post-nav-previous:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
}