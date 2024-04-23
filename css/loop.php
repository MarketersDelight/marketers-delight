<style type="text/css">

.loop, .columns, .categories {
	display: flex;
	flex-flow: wrap;
	gap: <?php echo $half; ?>px;
	justify-content: space-evenly;
}

.columns:not(.slim) { justify-content: space-between; }

.loop:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

/* GLOBAL */

.entry {
	position: relative;
	width: 100%;
}

.entry .headline, .entry .the-content, .entry .featured-image, .entry .post-footer { margin-bottom: <?php echo $single; ?>px; }

.entry :last-child, .has-cover.image-before .featured-image { margin-bottom: 0; }

/* LIST: TIMELINE */

<?php $timeline_size = 24; $timeline_space = 12; ?>

.timeline, .timeline-left { position: relative; }

.timeline.columns { row-gap: <?php echo $single; ?>px; }

.timeline.columns:before { display: none; }

.timeline.columns > .entry:before {
	bottom: <?php echo $timeline_space; ?>px;
	z-index: -1;
}

.timeline:before,
.timeline-left:before,
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

.timeline-left .entry { padding-left: <?php echo $single; ?>px; }

.timeline-left > .entry:after {
	left: -<?php echo $timeline_space; ?>px;
	margin-left: 0;
	top: 0;
}

.timeline.columns > .entry:after { top: -<?php echo $timeline_size + $timeline_space; ?>px; }

/* BOX STYLE */

.box-style.columns .entry .loop { row-gap: 0; }

.box-style > .entry,
.timeline-left.box-style > .entry,
.the-embed {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.box-style .item,
.box-style .headline:not(.cover),
.box-style .the-content,
.box-style .post-footer { padding: <?php echo $half; ?>px; }

.box-style .featured-image,
.box-style .headline,
.box-style .the-content,
.box-style .post-footer { margin-bottom: 0; }

.box-style .post-footer {
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.box-style .post-footer.byline {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.box-style .post-footer:not(:last-child),
.box-style.categories .category-headline,
.box-style.categories .loop .entry:not(:last-child),
.box-style.timeline-left:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .post-footer + .post-footer { border-top: 0; }

.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .toggle-comment .comment-content:after { background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, #fefefe 80%); }

.box-style.row > .entry .headline:not(.cover) + .the-content { padding-top: 0; }

.box-style.timeline-left:before { left: <?php echo $half; ?>px; }

.box-style.timeline-left .entry:after {
	left: 2px;
	top: <?php echo $half; ?>px;
}

/* QUERIES */

@media all and (min-width: 800px) {
	.box-style.row .item,
	.box-style.row .headline,
	.box-style.row .the-content,
	.box-style.row .post-footer:not(.byline) { padding: <?php echo $single; ?>px <?php echo $mid; ?>px; }
	.box-style.slim .item,
	.box-style.slim .headline,
	.box-style.slim .the-content,
	.box-style.slim .post-footer:not(.byline) { padding: <?php echo $half; ?>px; }
	.expanded .box-style.row .the-content {
		padding-left: 0;
		padding-right: 0;
	}
}

@media all and (min-width: <?php echo $post_width; ?>px) {
	.expanded .content.row .categories { gap: <?php echo $mid; ?>px; }
	.expanded .loop-post.row {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $content_width; ?>px;
	}
	.expanded .the-content,
	.expanded .post-footer .wrap {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $post_width; ?>px;
	}
}
