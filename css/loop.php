<style type="text/css">

/* BOX STYLE */

.box-style {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	margin-bottom: <?php echo $half; ?>px;
}

.box-style .cover,
.box-style .post-header,
.box-style .the-content { padding: <?php echo $half; ?>px; }

.box-style.image-above-headline .featured-image,
.box-style.image-above-headline .page-image { margin-bottom: 0; }

.box-style .cover { border-radius: 5px; }

.box-style.image-above-headline .overlay,
.box-style.image-above-headline .cover,
.box-style.image-below-headline .featured-image img { border-radius: 0 0 5px 5px; }

.box-style.image-below-headline .overlay,
.box-style.image-below-headline .cover,
.box-style.image-above-headline .featured-image img { border-radius: 5px 5px 0 0; }

.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .toggle-comment .comment-content:after { background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, #fefefe 80%); }

@media all and (max-width: <?php echo $site_width; ?>px) {
	.article .box-style {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.box-style { margin-bottom: <?php echo $single; ?>px; }
	.columns .box-style { margin-bottom: 0; }
	.box-style .cover,
	.box-style .post-header,
	.box-style .the-content,
	.box-style .author-box,
	.box-style .comments { padding: <?php echo $single; ?>px <?php echo $mid; ?>px; }
	.expanded .box-style .author-box,
	.expanded .box-style .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
	.columns .box-style .cover,
	.columns .box-style .post-header,
	.columns .box-style .the-content { padding: <?php echo $half; ?>px; }
}

.box-style .post-header:not(.cover) + .the-content { padding-top: 0; }

/* LOOP LIST */

.loop-list .post-header { margin-bottom: <?php echo $half; ?>px; }

.loop-list .title-wrap {
	align-items: center;
	display: flex;
	flex-flow: wrap;
	gap: <?php echo $half; ?>px;
}

.loop-list .image-left .title-wrap,
.loop-list .image-right .title-wrap { flex-flow: inherit; }

.loop-list .image-center .title { order: -1; }

.loop-list .image-right .featured-image,
.loop-list .image-left .featured-image {
	max-width: <?php echo $quad; ?>px;
}

/* SIZES */

@media all and (min-width: <?php echo $post_width; ?>px) {
	.expanded .post-header, .header .post-header { text-align: center; }
	.expanded .byline, .header .byline { justify-content: center; }
	.expanded .byline:not(:last-child), .header .byline:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }
	.expanded .the-content {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $post_width; ?>px;
		padding-left: 0;
		padding-right: 0;
	}
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

.pagination .page-numbers, .post-nav-links .post-page-numbers {
	background-color: #fff;
	border: 0;
	border-radius: 50%;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	display: inline-block;
	margin-right: <?php echo $small; ?>px;
	padding: <?php echo $small; ?>px <?php echo $half; ?>px;
}

.page-numbers.current, .post-page-numbers.current {
	cursor: default;
	font-weight: bold;
}

.pagination .page-numbers:hover, .post-nav-links.post-page-numbers:hover { opacity: 0.8; }

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
