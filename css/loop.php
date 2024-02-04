<style type="text/css">

.entry { position: relative; }

.loop .entry:not(:last-child), .the-content { margin-bottom: <?php echo $single; ?>px; }

.post-header:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

.post-box .cover {
	border-radius: 5px;
	padding: <?php echo $half; ?>px;
}

.post-box .overlay { border-radius: 5px; }

/* LIST: TIMELINE */

.timeline, .timeline-left { position: relative; }

.timeline:before, .timeline-left:before {
	background-color: <?php echo $colors['site']['links']; ?>;
	content: '';
	display: block;
	height: 100%;
	margin-left: -2px;
	position: absolute;
		bottom: 0;
		left: 50%;
	width: 4px;
}

.timeline-left:before, .timeline-left > .entry:after { z-index: 10; }

.timeline > .entry:not(:last-child):after,
.timeline-left > .entry:after,
.thread:after {
	background-color: <?php echo $colors['site']['links']; ?>;
	border-radius: 50%;
	content: '';
	display: block;
	height: 24px;
	margin-left: -12px;
	position: absolute;
		bottom: -12px;
		left: 50%;
	width: 24px;
}

.timeline .entry:not(.box-style) { background-color: <?php echo $colors['site']['bg_color']; ?>; }

.timeline-left .entry { padding-left: <?php echo $single; ?>px; }

.timeline-left:before { left: 0; }

.timeline-left > .entry:after {
	margin-left: 0;
	left: -12px;
	top: 0;
}

/* BOX STYLE */

.box-style, .timeline-left.has-box-style {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.box-style .post-header,
.box-style .the-content,
.box-style .post-box .post-footer.byline { padding: <?php echo $half; ?>px; }

.box-style .author-box,
.box-style .comments {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.box-style .post-header,
.box-style .the-content,
.box-style.image-above-headline .featured-image,
.box-style.image-above-headline .page-image { margin-bottom: 0; }

.box-style.image-above-headline .overlay,
.box-style.image-above-headline .cover,
.box-style.image-below-headline .featured-image img { border-radius: 0 0 5px 5px; }

.box-style.image-below-headline .overlay,
.box-style.image-below-headline .cover,
.box-style.image-above-headline .featured-image img { border-radius: 5px 5px 0 0; }

.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .toggle-comment .comment-content:after { background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, #fefefe 80%); }

.has-box-style.timeline-left:before { left: <?php echo $half; ?>px; }

.has-box-style.timeline-left .entry {
	background-color: transparent;
	border-radius: 0;
	box-shadow: none;
	margin-bottom: 0;
	padding-left: <?php echo $half; ?>px;
}

.timeline-left .box-style:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.has-box-style.timeline-left .entry:after {
	left: 2px;
	top: <?php echo $half; ?>px;
}

@media all and (max-width: 900px) {
	.article .box-style.entry {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.loop.columns .entry { margin-bottom: 0; }
	.post-box .cover,
	.content .box-style .post-header,
	.content .box-style .the-content,
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

.loop-list .title-wrap {
	align-items: center;
	display: flex;
	flex-flow: wrap;
	gap: <?php echo $half; ?>px;
}

.loop-list .title { flex: 1; }

.loop-list .image-left .title-wrap,
.loop-list .image-right .title-wrap { flex-flow: inherit; }

.loop-list .image-center .title { order: -1; }

.loop-list .image-right .featured-image,
.loop-list .image-left .featured-image {
	margin-bottom: 0;
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
	.expanded .author-box, .expanded .post-footer, .expanded .comments {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $content_width; ?>px;
	}
	.expanded .box-style .post-footer, .expanded .box-style .author-box, .expanded .box-style .comments { max-width: 100%; }
}

/* PAGINATION */

.pagination {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
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
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
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
