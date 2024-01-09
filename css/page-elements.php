<style type="text/css">

/* PAGE HEADER */

.layout-slim .title-wrap, .description, .page-cta { margin-bottom: <?php echo $single; ?>px; }

.inline .title-wrap { margin-bottom: <?php echo $half; ?>px; }

.page-image {
	margin-bottom: <?php echo $single; ?>px;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

@media all and (min-width: 800px) {
	/* GENERIC */
	.layout-columns.inline .title-wrap { order: -1; }
	.image-right .page-image { margin-left: <?php echo $single; ?>px; }
	.image-left .page-image {
		margin-right: <?php echo $single; ?>px;
		order: -1;
	}
	/* COLUMNS */
	.layout-columns, .layout-columns.outer .inner {
		align-items: center;
		display: flex;
	}
	.layout-columns.outer .title-wrap {
		flex: 1;
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $content_width; ?>px;
	}
	.layout-columns.inline { flex-flow: wrap; }
	.layout-columns.inline .description { flex: 1; }
	/* SLIM */
	.layout-slim.outer .title, .layout-slim.outer .page-cta, .layout-slim.outer .foot { text-align: center; }
	.layout-slim.outer .description, .layout-slim.outer .page-cta {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $post_width; ?>px;
	}
}


@media all and (max-width: 800px) {
	.layout-columns.outer .title-wrap { margin-bottom: <?php echo $single; ?>px; }
}

@media all and (min-width: 700px) {
	.page-cta-link + .page-cta-link { margin-left: <?php echo $half; ?>px; }
}

@media all and (max-width: 700px) {
	.page-cta-link {
		display: block;
		margin-bottom: <?php echo $half; ?>px;
		text-align: center;
		width: 100%;
	}
}

/* BREADCRUMBS */

.breadcrumbs {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	margin-bottom: <?php echo $half; ?>px
}

.breadcrumbs a { text-decoration: underline; }

.breadcrumbs a:hover { text-decoration: none; }

.breadcrumbs a, .breadcrumbs i, .breadcrumb-text { margin-right: <?php echo $third; ?>px; }

/* BYLINE */

.byline {
	align-items: center;
	color: <?php echo $colors['site']['text-sec']; ?>;
	display: flex;
	position: relative;
}

.byline a, .byline-item a {
	color: <?php echo $colors['site']['text-sec']; ?>;
	text-decoration: none;
}

.byline-item {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
}

.byline .author-link { border-bottom: 1px solid rgba(0, 0, 0, 0.15); }

.byline .author-link:hover { border-bottom: 0; }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-author .avatar {
	margin-right: <?php echo $small; ?>px;
	position: relative;
}

.byline-item:not(:last-child) { margin-right: <?php echo $half; ?>px; }

.byline .badge { font-size: <?php echo $typography['body']['font_size']['mobile'] - 1; ?>px; }

.byline-date-modified { font-style: italic; }

.byline-sticky {
	color: #22a340;
	display: block;
	font-weight: <?php echo $bold; ?>;
	margin-bottom: <?php echo $half; ?>px;
}

.post-header .byline:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

/* AUTHOR BOX */

.author-box {
	background-color: <?php echo $colors['site']['action']; ?>;
	padding: <?php echo $single; ?>px <?php echo $half; ?>px <?php echo $half; ?>px;
}

.author-title {
	align-items: center;
	display: flex;
	margin-bottom: <?php echo $half; ?>px;
}

.author-box .circle-icon { margin-right: <?php echo $third; ?>px; }

.author-link:not(:last-child) { margin-right: <?php echo $small; ?>px; }

.author-headline { margin-bottom: <?php echo $small; ?>px; }

.author-headline {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
}

.author-bio { margin-bottom: <?php echo $half + $small; ?>px; }

.author-avatar {
	flex: 0 1 <?php echo $double; ?>px;
	margin-right: <?php echo $half; ?>px;
}

.author-avatar img { width: 100%; }

.author-meta {
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
	font-size: <?php echo round( $typography['body']['font_size']['mobile'] - 1 ); ?>px;
	line-height: <?php echo round( $typography['body']['line_height']['mobile'] - 1 ); ?>px;
}

.author-meta .author-link {
	color: <?php echo $colors['site']['links_sec']; ?>;
	display: inline-block;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.author-link.twitter .circle-icon {
	background-color: #1da1f2;
	color: #fff;
}

.author-link.twitter .md-icon-twitter { color: #fff; }

.author-link.twitter a { color: #1da1f2; }

/* POST NAV */

.post-nav {
	align-items: center;
	display: flex;
	margin-left: -<?php echo $half; ?>px;
}

.post-nav p { margin-bottom: 0; }

.post-nav a {
	display: block;
	text-decoration: none;
}

.post-nav-next { text-align: right; }

.post-nav-previous, .post-nav-next {
	flex: 1;
	margin-left: <?php echo $half; ?>px;
}

.post-nav-title { color: <?php echo $colors['site']['text']; ?>; }

.post-nav-previous:hover .post-nav-title, .post-nav-next:hover .post-nav-title { text-decoration: underline; }

.post-nav-previous .post-nav-subtitle i { margin-right: <?php echo $third; ?>px; }
.post-nav-next .post-nav-subtitle i { margin-left: <?php echo $third; ?>px; }

/* PAGINATION */

.pagination {
	justify-content: space-between;
	position: relative;
	text-align: center;
}

.pagination.prev-next .pagination-wrap {
	display: flex;
	justify-content: space-between;
}

.pagination a { text-decoration: none; }

.post-nav-links {
	background-color: rgba(0, 0, 0, 0.05);
	border-radius: 5px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
	padding: <?php echo $half; ?>px;
}

.pagination .page-numbers,
.post-nav-links .post-page-numbers {
	background-color: #fff;
	border: 0;
	border-radius: 50%;
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

.page-numbers.dots, .page-numbers.prev, .page-numbers.next {
	background-color: transparent;
	border-radius: inherit;
	box-shadow: none;
	border: 0;
	color: <?php echo $colors['site']['text']; ?>;
	padding: 0;
}

.page-numbers.prev { margin-right: <?php echo $third; ?>px; }
.page-numbers.next { margin-left: <?php echo $third; ?>px; }
