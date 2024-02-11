<style type="text/css">

.entry { position: relative; }

.loop .entry:not(:last-child), .the-content { margin-bottom: <?php echo $single; ?>px; }

.post-header:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

.post-box .cover {
	border-radius: 5px;
	padding: <?php echo $half; ?>px;
}

.post-box .overlay { border-radius: 5px; }

.image-left .the-content .featured-image {
	float: left;
	margin-right: <?php echo $half; ?>px;
}

.image-right .the-content .featured-image {
	float: right;
	margin-left: <?php echo $half; ?>px;
}

/* FULL-WIDTH */
/*
.full .loop {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $content_width; ?>px;
}
*/
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
.box-style .post-footer.byline,
.box-style .author-box,
.box-style .comments { padding: <?php echo $half; ?>px; }

.box-style .post-header:not(.cover) + .the-content { padding-top: 0; }

.box-style .post-header,
.box-style .the-content,
.box-style.image-before .featured-image,
.box-style.image-after .featured-image,
.box-style.image-before .page-image { margin-bottom: 0; }
/*
.box-style.image-before .overlay,
.box-style.image-before .cover,
.box-style.image-after .featured-image img { border-radius: 0 0 5px 5px; }

.box-style.image-after .overlay,
.box-style.image-after .cover,
.box-style.image-before .featured-image img { border-radius: 5px 5px 0 0; }
*/

.categories .box-style .section-header,
.timeline-left .box-style:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

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

.timeline-left.has-box-style .image-before .featured-image,
.timeline-left.has-box-style .image-after .featured-image { margin-left: -<?php echo $half; ?>px; }

.box-style .category-posts .entry:not(:last-child) {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	margin-bottom: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
}

.box-style .category-posts.columns .entry:not(:last-child) {
	border-bottom: 0;
	margin-bottom: 0;
	padding-bottom: 0;
}

@media all and (max-width: 900px) {
	.article .box-style.entry {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.loop.columns .entry { margin-bottom: 0; }
	.box-style .post-box .cover, .box-style .the-content { padding: <?php echo $half; ?>px; }
	.expanded .box-style .author-box,
	.expanded .box-style .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
}

.timeline-left.has-box-style .post-header,
.timeline-left.has-box-style .the-content { padding-left: <?php echo $half + $third; ?>px; }

/* LOOP FLUID */

.loop-fluid .featured {
	flex-basis: 100%;
	max-width: 100%;
}

@media all and (min-width: 800px) {
	.loop-fluid .box-style .post-box .cover,
	.loop-fluid .box-style .post-header,
	.loop-fluid .box-style .the-content,
	.loop-fluid .box-style .author-box,
	.loop-fluid .box-style .comments { padding: <?php echo $single; ?>px <?php echo $mid; ?>px; }
	.loop-fluid .columns .box-style .post-box .cover,
	.loop-fluid .columns .box-style .post-header,
	.loop-fluid .columns .box-style .the-content,
	.loop-fluid .columns .box-style .author-box,
	.loop-fluid .columns .box-style .comments { padding: <?php echo $half; ?>px; }
}

/* LOOP LIST */

.loop-list .loop .post-box {
	align-items: center;
	display: flex;
	flex-flow: wrap;
	gap: <?php echo $third; ?>px;
}

.loop-list .loop .box-style .post-box { gap: 0; }

.loop-list .loop .post-header { flex: 1; }

.loop-list .image-right .post-header { order: -1; }

.loop-list .title-wrap { margin-bottom: 0; }

.loop-list .box-style.image-left .featured-image {
	margin-right: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	right: -<?php echo $half; ?>px;
}

.loop-list .box-style.image-right .featured-image {
	left: -<?php echo $half; ?>px;
	margin-left: <?php echo $small; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.loop-list .box-style .post-header + .the-content { padding-top: <?php echo $half; ?>px; }

.loop-list .post-footer, .loop-blocks .post-footer { flex-basis: 100%; }

/* LOOP BLOCKS */

.loop-blocks .loop .entry {
	flex-flow: wrap;
	gap: <?php echo $half; ?>px;
}

.loop-blocks .loop .box-style { gap: 0; }

.loop-blocks .post-box { flex: 1; }

.loop-blocks .image-right .post-box { order: -1; }

.loop-blocks .box-style.image-left .featured-image {
	margin-right: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	right: -<?php echo $half; ?>px;
}

.loop-blocks .box-style.image-right .featured-image {
	left: -<?php echo $half; ?>px;
	margin-left: <?php echo $small; ?>px;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

@media all and (min-width: <?php echo $site_width; ?>px) {
	.loop-blocks .loop .entry { align-items: center; }
	.loop-blocks .medium .box-style .featured-image { max-width: <?php echo $sidebar_width; ?>px; }
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

.loop-covers .loop {
	display: grid;
	gap: <?php echo $half; ?>px;
}

.loop-covers .loop .title {
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
	.loop-covers .loop { grid-template-columns: repeat(2, 1fr); }
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
	.loop-covers .loop { grid-template-columns: repeat(4, 1fr); }
}

/* SIZES */

.normal .title {
	font-size: inherit;
	line-height: inherit;
}

.small .byline-item, .normal .byline-item { font-size: <?php echo $typography['body']['font_size']['mobile'] - 1; ?>px; }

.small .the-content p:not(:last-child), .normal .the-content p:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

.small .byline:not(:last-child), .normal .byline:not(:last-child),
.small h1, .small  h2, .small h3, .small h4, .small h5, .small h6,
.normal h1, .normal  h2, .normal h3, .normal h4, .normal h5, .normal h6 { margin-bottom: <?php echo $small; ?>px; }

.normal .image-left .featured-image, .normal .image-right .featured-image,
.small .image-left .featured-image, .small .image-right .featured-image { max-width: <?php echo $double + $half; ?>px; }

.image-left .the-content .featured-image, .image-right .the-content .featured-image,
.medium .image-left .featured-image, .medium .image-right .featured-image { max-width: <?php echo $quad + $single; ?>px; }

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
	.large .image-left .featured-image, .large .image-right .featured-image,
	.image-left .the-content .featured-image, .image-right .the-content .featured-image { max-width: <?php echo $sidebar_width; ?>px; }
	.medium .image-left .featured-image, .medium .image-right .featured-image { max-width: <?php echo round( $sidebar_width / 1.5 ); ?>px; }
	.columns .the-content .featured-image { max-width: <?php echo $quad + $single; ?>px; }
	.small .description, .small .the-content,
	.normal .description, .normal .the-content {
		font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	}
}
