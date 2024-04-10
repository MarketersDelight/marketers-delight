<style type="text/css">

/* BREADCRUMBS */

.breadcrumbs {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	margin-bottom: <?php echo $half; ?>px;
}

.breadcrumbs ol {
	list-style: none;
	margin-left: 0;
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

.breadcrumbs a { text-decoration: underline; }

.breadcrumbs a:hover { text-decoration: none; }

@media all and (min-width: 700px) {
	.expanded .breadcrumbs { text-align: center; }
	.expanded .box-style .breadcrumbs { text-align: left; }
}

/* AUTHOR BOX */

.author-meta {
	align-items: center;
	display: flex;
	margin-bottom: <?php echo $half; ?>px;
}

.author-box .circle-icon { margin-right: <?php echo $third; ?>px; }

.author-title { margin-bottom: <?php echo $small; ?>px; }

.author-title {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
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

.page-numbers.dots,
.page-numbers.prev,
.page-numbers.next {
	background-color: transparent;
	border-radius: inherit;
	box-shadow: none;
	border: 0;
	color: <?php echo $colors['site']['text']; ?>;
	padding: 0;
}

.page-numbers.prev { margin-right: <?php echo $third; ?>px; }
.page-numbers.next { margin-left: <?php echo $third; ?>px; }

/* POST NAV */

.post-nav {
	align-items: center;
	display: flex;
	flex-flow: wrap;
	gap: <?php echo $single; ?>px;
	margin-top: <?php echo $single; ?>px;
}

.post-nav-next, .post-nav-previous {
	flex-basis: 100%;
	position: relative;
}

.post-nav a { display: block; }

@media all and (min-width: 600px) {
	.post-nav-next { text-align: right; }
	.post-nav-next, .post-nav-previous { flex: 1; }
}

/* BLOCK LAYOUT */

.content > .page-headline,
.content .inner > .page-headline,
.main .inner > .page-headline { margin-bottom: <?php echo $single; ?>px; }

.block, .block .wrap, .block.wide .inner {
	display: flex;
	flex-direction: column;
	gap: <?php echo $half; ?>px;
	position: relative;
}

.block.wide .wrap { width: 100%; }

.block .title { margin-bottom: 0; }

.block .featured-image {
	margin-left: auto;
	margin-right: auto;
}

@media all and (min-width: <?php echo $content_width; ?>px) {
	.block.wide {
		align-items: center;
		justify-content: center;
	}
	.block.wide, .block.wide .inner, .block.inline .wrap { flex-flow: initial; }
	.block.wide.image-before,
	.block.image-center,
	.block.image-center .wrap { flex-direction: column; }
	.expanded .page-headline.wide .wrap {
		align-items: center;
		max-width: <?php echo $content_width; ?>px;
		text-align: center;
	}
	.page-headline.wide .wrap {
		align-items: center;
		text-align: center;
	}
	.block .description { max-width: <?php echo $post_width; ?>px; }
	.block.image-left .featured-image { order: -1; }
}
