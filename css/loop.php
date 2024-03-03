<style type="text/css">

.full .loop, .full .categories,
.full .content > .headline-cover,
.article.full .stream {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $content_width; ?>px;
}

.loop, .categories, .columns {
	display: flex;
	flex-flow: wrap;
	justify-content: space-between;
	row-gap: <?php echo $single; ?>px;
}

.loop, .categories { margin-bottom: <?php echo $single; ?>px; }

/*
.box-style.columns.slim { row-gap: <?php echo $half; ?>px; }
*/
.box-style .columns, .box-style .loop { row-gap: 0; }

.full .loop.columns, .full .categories.columns { max-width: 100%; }

.entry {
	flex-basis: 100%;
	max-width: 100%;
	position: relative;
}

/* LIST: TIMELINE */

<?php $timeline_size = 24; $timeline_space = 12; ?>

.timeline, .timeline-left { position: relative; }

.timeline:before, .timeline-left:before, .timeline.columns > .entry:before {
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

.timeline-left .entry { padding-left: <?php echo $single; ?>px; }

.timeline-left > .entry:after {
	left: -<?php echo $timeline_space; ?>px;
	margin-left: 0;
	top: 0;
}

.timeline.columns > .entry:after { top: -<?php echo $timeline_size + $timeline_space; ?>px; }

/* BOX STYLE */

.box-style > .entry, .timeline-left.box-style > .entry, .the-embed {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.box-style .post-header:not(.cover), .box-style .the-content, .box-style .post-footer { padding: <?php echo $half; ?>px; }

.box-style .post-header, .box-style .the-content { margin-bottom: 0; }

.box-style.categories .section-header, .box-style.timeline-left:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .toggle-comment .comment-content:after { background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, #fefefe 80%); }

.box-style.timeline-left:before { left: <?php echo $half; ?>px; }

.box-style.timeline-left .entry:after {
	left: 2px;
	top: <?php echo $half; ?>px;
}

@media all and (min-width: 800px) {
	.content .box-style:not(.columns) .post-header:not(.cover),
	.content .box-style:not(.columns) .post-footer,
	.content .box-style:not(.columns) .the-content,
	.content.box-style .post-header:not(.cover),
	.content.box-style .post-footer,
	.content.box-style .the-content	{ padding: <?php echo $single; ?>px <?php echo $mid; ?>px; }
}

@media all and (max-width: 900px) {
	.article .content.box-style {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.expanded .box-style .author-box, .expanded .box-style .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
}

.box-style .post-header:not(.cover) + .the-content { padding-top: 0; }

.timeline-left.box-style .post-header, .timeline-left.box-style .the-content { padding-left: <?php echo $half + $third; ?>px; }

/* LOOP FLUID */

.loop-fluid .featured {
	flex-basis: 100%;
	max-width: 100%;
}

/* LOOP LIST */

.loop-list .post-box {
	align-items: center;
	display: flex;
	flex-flow: wrap;
}

.loop-list.box-style .post-box { gap: 0; }

.loop-list .post-header { flex: 1; }

.loop-list .image-right .post-header { order: -1; }

.loop-list.box-style .image-left .featured-image {
	margin-right: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	right: -<?php echo $half; ?>px;
}
/*
.loop-list.box-style .image-right .featured-image {
	left: -<?php echo $half; ?>px;
	margin-left: <?php echo $small; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.loop-list.box-style .post-header + .the-content { padding-top: <?php echo $half; ?>px; }
*/
.loop-list .post-footer, .loop-blocks .post-footer { flex-basis: 100%; }

/* LOOP BLOCKS */

.loop-blocks .entry {
	flex-flow: wrap;
	gap: <?php echo $half; ?>px;
}

.loop-blocks.box-style { gap: 0; }

.loop-blocks .entry { flex: 1; }

.loop-blocks .entry.image-right { order: -1; }

.loop-blocks.box-style.image-left .featured-image {
	margin-right: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	right: -<?php echo $half; ?>px;
}

.loop-blocks.box-style.image-right .featured-image {
	left: -<?php echo $half; ?>px;
	margin-left: <?php echo $small; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

@media all and (min-width: <?php echo $site_width; ?>px) {
	.loop-blocks .entry { align-items: center; }
	.loop-blocks.medium.box-style .featured-image { max-width: <?php echo $sidebar_width; ?>px; }
}

/* LOOP - COVERS */

.loop-covers .inner { padding-bottom: 0; }

.loop-covers .entry {
	background-size: 100%;
	box-shadow: 0 3px 6px rgba(120, 151, 186, .35), 0 3px 6px rgba(120, 151, 186, .45);
	display: flex;
	flex-direction: column;
	justify-content: flex-end;
	min-height: <?php echo $quad + $double; ?>px;
	padding: <?php echo $half; ?>px;
	transition: all 0.3s ease-in-out;
}

.loop-covers .entry:not(:last-child) { margin-bottom: 0; }

.loop-covers .entry:after {
	background: linear-gradient(to bottom,rgba(0, 0, 0, 0) 0%,rgba(0, 0, 0,.3) 80%);
	border-radius: 15px;
	content: '';
	inset: 0;
	position: absolute;
}

.loop-covers .entry:hover { background-size: 125%; }

.loop-covers {
	display: grid;
	gap: <?php echo $half; ?>px;
}

.loop-covers .title {
	font-size: inherit;
	line-height: inherit;
	text-shadow: 0 2px 3px rgba(0, 0, 0, 0.8);
}

.loop-covers .post-header {
	padding: 0;
	position: relative;
	transition: 0.3s;
	z-index: 10;
}

.loop-covers .entry:hover .post-header { transform: translateY(-<?php echo $small; ?>px); }

.loop-covers .standard .byline-badge { display: none; }

.loop-covers .clickable:after { z-index: 5; }

@media all and (min-width: 700px) {
	.loop-covers { grid-template-columns: repeat(2, 1fr); }
	.loop-covers .featured {
		grid-column: 1/3;
		grid-row: 1/3;
		padding: <?php echo $single; ?>px;
	}
	.loop-covers .featured .title {
		font-size: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h3']['line_height']['desktop']; ?>px;
	}
}

@media all and (min-width: 900px) {
	.loop-covers { grid-template-columns: repeat(4, 1fr); }
}

/* SIZES */

@media all and (min-width: <?php echo $post_width; ?>px) {
	.expanded .post-header, .header .post-header { text-align: center; }
	.expanded .byline, .header .byline { justify-content: center; }
	.expanded .the-content {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $post_width; ?>px;
	}
	.expanded .box-style .the-content {
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