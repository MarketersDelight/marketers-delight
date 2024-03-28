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
	font-size: <?php echo round( $typography['body']['font_size']['mobile'] - 1 ); ?>px;
	gap: <?php echo $half; ?>px;
	line-height: <?php echo round( $typography['body']['line_height']['mobile'] - 1 ); ?>px;
}

.author-link { color: <?php echo $colors['site']['links_sec']; ?>; }

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
	margin-top: <?php echo $single; ?>px;
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.post-nav-next { margin-top: <?php echo $single; ?>px; }

.post-nav-next, .post-nav-previous { position: relative; }

.post-nav a {
	display: block;
	margin-top: <?php echo $small; ?>px;
}

@media all and (min-width: <?php echo $site_width; ?>px) {
	.post-nav {
		padding-left: 0;
		padding-right: 0;
	}
}

@media all and (min-width: 900px) {
	.post-nav-next {
		margin-top: 0;
		text-align: right;
	}
	.post-nav-next, .post-nav-previous { flex: 1; }
}

/* BLOCK LAYOUT */

.block, .block > .wrap {
	display: flex;
	flex-flow: wrap;
	gap: <?php echo $half; ?>px <?php echo $single; ?>px;
	position: relative;
}

.block.wide { align-items: center; }

.block .featured-image {
	margin-left: auto;
	margin-right: auto;
}

.block.image-left .featured-image { order: -1; }

.block .subtitle, .block .title, .block .byline, .block .description, .block .cta {
	margin-bottom: 0;
	width: 100%;
}

@media all and (min-width: <?php echo $content_width; ?>px) {
	.block .description,
	.block .cta { max-width: <?php echo $post_width; ?>px; }
	.block.wide, .block.inline > .wrap { flex-flow: initial; }
	.block.wide > .wrap {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $content_width; ?>px;
	}
	.block.wide > .wrap,
	.block.wide .cta,
	.expanded .block .wrap,
	.expanded .block .byline,
	.expanded .block .cta {
		justify-content: center;
		text-align: center;
	}
	.header .block .wrap,
	.expanded .block .wrap {
		max-width: 100%;
		width: 100%;
	}
}