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

<?php
	$timeline_size = 24;
	$timeline_space = round( $timeline_size / 2 );
?>

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

.timeline.columns:before { display: none; }

.timeline.columns > .entry:before {
	bottom: <?php echo $timeline_space; ?>px;
	z-index: -1;
}

.timeline.columns { row-gap: <?php echo $single; ?>px; }
.timeline-left:before, .timeline-left > .entry:after { z-index: 10; }

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

.timeline-left:before { left: 0; }

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

.has-box-style.timeline-left .entry:after {
	left: 2px;
	top: <?php echo $half; ?>px;
}

.timeline-left .box-style:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.has-box-style.timeline-left .image-above-headline .featured-image,
.has-box-style.timeline-left .image-below-headline .featured-image { margin-left: -<?php echo $half; ?>px; }

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
