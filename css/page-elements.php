<style type="text/css">

/* PAGE TITLE */

.page-header { margin-bottom: <?php echo $single; ?>px; }

.page-header.image,
.page-header.image .inner {
	align-items: center;
	display: flex;
}

.page-header.image .title-wrap {
	flex: 1;
}

.page-header .page-image {
	margin-left: <?php echo $single; ?>px;
}

/* BYLINE */

.byline {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['desktop'] - 2; ?>px;
	margin-bottom: <?php echo $third; ?>px;
	position: relative;
}

.byline a {
	color: <?php echo $colors['site']['text-sec']; ?>;
	text-decoration: none;
}

.byline .author-link { border-bottom: 1px solid rgba(0, 0, 0, 0.15); }

.byline .author-link:hover { border-bottom: 0; }

.byline-item { display: inline-block; }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-author .avatar {
	margin-right: <?php echo $small; ?>px;
	position: relative;
}

.byline-item:not(:last-child) { margin-right: <?php echo $third; ?>px; }

.byline .badge {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 3; ?>px;
	padding-bottom: 2px;
	padding-top: 2px;
}

.byline-comments-label { display: none; }

.byline-date-modified { font-style: italic; }

/* BREADCRUMBS */

.breadcrumbs {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	margin-bottom: <?php echo $half; ?>px
}

.breadcrumbs a { text-decoration: underline; }

.breadcrumbs a:hover { text-decoration: none; }

.breadcrumbs a, .breadcrumbs i, .breadcrumb-text { margin-right: <?php echo $third; ?>px; }

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

.author-link:not(:last-child) { margin-right: <?php echo $half; ?>px; }

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

/* PAGINATION */

.pagination {
	position: relative;
	text-align: center;
}

.pagination a { text-decoration: none; }

.pagination-sep {
	margin-left: <?php echo $small; ?>px;
	margin-right: <?php echo $small; ?>px;
}

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
