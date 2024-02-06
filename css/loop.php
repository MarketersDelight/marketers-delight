<style type="text/css">

.entry { position: relative; }

.loop .entry:not(:last-child), .the-content,
.category-row { margin-bottom: <?php echo $single; ?>px; }

.post-header:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

.post-box .cover {
	border-radius: 5px;
	padding: <?php echo $half; ?>px;
}

.post-box .overlay { border-radius: 5px; }

/* FULL-WIDTH */

.full .loop {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $content_width; ?>px;
}

.full .loop.columns { max-width: 100%; }

/* LIST: TIMELINE */

<?php $timeline_size = 24; $timeline_space = 12; ?>

.timeline, .timeline-left { position: relative; }

.timeline:before, .timeline-left:before,
.timeline.columns > .entry:before {
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

.timeline-left:before { left: 0; }

.timeline-left:before, .timeline-left > .entry:after { z-index: 10; }

.timeline.columns { row-gap: <?php echo $single; ?>px; }

.timeline.columns:before { display: none; }

.timeline.columns > .entry:before {
	bottom: <?php echo $timeline_space; ?>px;
	z-index: -1;
}

.timeline > .entry:not(:last-child):after,
.timeline.columns > .entry:last-child:after,
.timeline-left > .entry:after,
.thread:after {
	background-color: <?php echo $colors['site']['links']; ?>;
	border-radius: 50%;
	content: '';
	display: block;
	height: <?php echo $timeline_size; ?>px;;
	margin-left: -<?php echo $timeline_space; ?>px;
	position: absolute;
		bottom: -<?php echo $timeline_space; ?>px;
		left: 50%;
	width: <?php echo $timeline_size; ?>px;
}

.timeline .entry:not(.box-style) { background-color: <?php echo $colors['site']['bg_color']; ?>; }

.timeline-left .entry { padding-left: <?php echo $single; ?>px; }

.timeline-left > .entry:after {
	margin-left: 0;
	left: -<?php echo $timeline_space; ?>px;
	top: 0;
}

.timeline.columns > .entry:after { top: -<?php echo $timeline_size + $timeline_space; ?>px; }

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
.box-style.image-below-headline .featured-image,
.box-style.image-above-headline .page-image { margin-bottom: 0; }

.box-style.image-above-headline .overlay,
.box-style.image-above-headline .cover,
.box-style.image-below-headline .featured-image img { border-radius: 0 0 5px 5px; }

.box-style.image-below-headline .overlay,
.box-style.image-below-headline .cover,
.box-style.image-above-headline .featured-image img { border-radius: 5px 5px 0 0; }

.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .toggle-comment .comment-content:after { background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, #fefefe 80%); }

.timeline-left.has-box-style:before { left: <?php echo $half; ?>px; }

.timeline-left.has-box-style .entry {
	background-color: transparent;
	border-radius: 0;
	box-shadow: none;
	margin-bottom: 0;
	padding-left: <?php echo $half; ?>px;
}

.timeline-left.has-box-style .entry:after {
	left: 2px;
	top: <?php echo $half; ?>px;
}

.timeline-left .box-style:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.timeline-left.has-box-style .image-above-headline .featured-image,
.timeline-left.has-box-style .image-below-headline .featured-image { margin-left: -<?php echo $half; ?>px; }

.category-row.box-style .post-header:not(.cover) {
	padding-left: 0;
	padding-right: 0;
	padding-top: 0;
}

.category-row.box-style .post-header:not(.cover):last-child { padding-bottom: 0; }

.category-row.box-style .the-content {
	padding-bottom: 0;
	padding-left: 0;
	padding-right: 0;
}

.box-style .section-header {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	margin-bottom: 0;
	padding: <?php echo $half; ?>px <?php echo $half; ?>px;
}

.box-style .category-posts { padding: <?php echo $half; ?>px; }

.box-style .category-posts .entry:not(:last-child) {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	margin-bottom: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
}

@media all and (max-width: 900px) {
	.article .box-style.entry {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.loop.columns .entry { margin-bottom: 0; }
	.box-style.row .post-box .cover,
	.box-style.row .post-header,
	.box-style.row .the-content,
	.box-style .author-box,
	.box-style .comments { padding: <?php echo $single; ?>px <?php echo $mid; ?>px; }
	.expanded .box-style .author-box,
	.expanded .box-style .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
}

.timeline-left.has-box-style .post-header,
.timeline-left.has-box-style .the-content { padding-left: <?php echo $half + $third; ?>px; }

.box-style .post-header:not(.cover) + .the-content { padding-top: 0; }

/* LOOP LIST */

.loop-list .loop .title-wrap {
	align-items: center;
	display: flex;
	flex-flow: wrap;
	gap: <?php echo $half; ?>px;
}

.loop-list .loop .title-wrap:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.loop-list .loop .title { flex: 1; }

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


@media all and (min-width: 800px) {
	.small .image-left .featured-image,
	.small .image-right .featured-image { max-width: <?php echo $double; ?>px; }
	.small .description, .small .the-content {
		font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	}
}
