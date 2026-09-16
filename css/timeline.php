<style type="text/css">

/*------------------------------*\
	$TIMELINE
\*------------------------------*/

/* BOX STYLE */

.box-entry.loop-timeline > .entry > :is(.timeline-wrap, .comments) {
	background-color: var(--md-content-box-background);
	border-radius: var(--md-border-radius);
	box-shadow: var(--md-box-shadow);
	color: var(--md-text);
}

.box-entry.loop-timeline > .entry {
	background-color: transparent;
	box-shadow: none;
}

.box-entry.loop-timeline > .entry > .comments { border-block-start: 0; }

/* TIMELINE */

.loop-timeline {
	--timeline-cap-size: calc(var(--md-small) * 3);
	--timeline-center: calc(var(--md-half) + var(--md-mid) / 2);
	--timeline-dot-size: var(--md-mid);
	--timeline-empty-dot-size: var(--md-single);
	--timeline-gap: var(--md-mid);
	--timeline-rail-width: var(--md-small);
}

.expanded .loop-timeline {
	--md-loop-content-width: 100%;
	margin-inline: auto;
	max-width: var(--md-width-content);
	width: 100%;
}

.expanded .box-style.loop-timeline { max-width: calc(var(--md-width-content) + var(--md-mid) * 2); }

.loop-timeline > .entry > .timeline-wrap > .post-title > .byline.before-title {
	border-block-end: 1px solid var(--md-border);
	padding-block-end: var(--md-half);
}

.loop-timeline > .entry > .timeline-wrap > .post-title .byline .share:last-child { margin-inline-start: auto; }

.loop-timeline .post-title.wide {
	align-items: inherit;
	text-align: inherit;
}

.loop-timeline .timeline-wrap {
	margin-inline-start: calc(var(--md-mid) + var(--md-half));
	position: relative;
}

@media (min-width: 800px) {
	.loop-timeline .byline.entry-top > .byline-date {
		line-height: var(--timeline-dot-size);
		position: absolute;
			inset-block-start: 0;
			inset-inline-end: calc(100% + var(--timeline-center) + var(--timeline-dot-size) / 2 + var(--md-half));
		white-space: nowrap;
	}

	.loop-timeline .byline.entry-top > .byline-date > i { display: none; }
}

.single .loop-timeline > .entry > .timeline-wrap:not(:last-child) { margin-block-end: var(--md-single); }

.loop-timeline:not(.content) > .entry:not(:last-child) { margin-block-end: var(--timeline-gap); }

.loop-timeline .timeline-wrap:before,
.loop-timeline:not(.content) > .entry:last-child > .timeline-wrap:after,
.single .loop-timeline.content > .entry > .timeline-wrap:after {
	background-color: var(--md-border);
	content: '';
	display: block;
	position: absolute;
	transition: var(--md-transition);
}

.loop-timeline .timeline-wrap:hover:before,
.loop-timeline:not(.content) > .entry:last-child > .timeline-wrap:hover:after,
.single .loop-timeline.content > .entry > .timeline-wrap:hover:after { background-color: var(--md-links); }

.loop-timeline .timeline-wrap:before {
	height: 100%;
	inset-block-end: 0;
	inset-inline-start: calc(-1 * (var(--timeline-center) + var(--timeline-rail-width) / 2));
	width: var(--timeline-rail-width);
}

.loop-timeline:not(.content) > .entry:last-child > .timeline-wrap:after,
.single .loop-timeline.content > .entry > .timeline-wrap:after {
	border-radius: 50%;
	height: var(--timeline-cap-size);
	inset-block-end: -1px;
	inset-inline-start: calc(-1 * (var(--timeline-center) + var(--timeline-cap-size) / 2));
	width: var(--timeline-cap-size);
}

.loop-timeline:not(.content) > .entry:not(:last-child) > .timeline-wrap:before {
	height: calc(100% + var(--timeline-gap) + var(--timeline-dot-size) / 2);
	inset-block-end: calc(-1 * (var(--timeline-gap) + var(--timeline-dot-size) / 2));
}

.loop-timeline .timeline-dot {
	align-items: center;
	background-color: var(--md-content-main-background);
	border: 4px solid var(--md-action-secondary);
	border-radius: 50%;
	color: var(--md-action-secondary);
	display: flex;
	font-size: var(--md-h5);
	justify-content: center;
}

.loop-timeline .timeline-wrap:hover .timeline-dot {
	border-color: var(--md-links);
	color: var(--md-links);
}

.loop-timeline .timeline-dot, .loop-timeline .byline .avatar {
	height: var(--timeline-dot-size);
	position: absolute;
		inset-block-start: 0;
		inset-inline-start: calc(-1 * (var(--timeline-center) + var(--timeline-dot-size) / 2));
	transition: var(--md-transition);
	width: var(--timeline-dot-size);
}

.loop-timeline .timeline-dot:empty {
	height: var(--timeline-empty-dot-size);
	inset-inline-start: calc(-1 * (var(--timeline-center) + var(--timeline-empty-dot-size) / 2));
	width: var(--timeline-empty-dot-size);
}

.loop-timeline .byline .avatar {
	margin: 0;
	z-index: 10;
}

.loop-timeline .timeline-wrap:hover > .timeline-dot,
.loop-timeline:not(.content) > .entry:last-child > .timeline-wrap:hover:after { transform: scale(1.15); }

@media (max-width: 800px) {
	.loop-timeline {
		--timeline-center: calc((var(--md-half) * 2 + var(--md-third)) / 2);
		--timeline-dot-size: var(--md-single);
		--timeline-empty-dot-size: calc(var(--timeline-dot-size) - var(--md-small));
		--timeline-gap: calc(var(--md-single) - var(--md-small));
		--timeline-rail-width: 4px;
	}

	.loop-timeline .timeline-wrap { margin-inline-start: calc(var(--md-half) + var(--md-third)); }
	.loop-timeline .timeline-dot { border-width: 3px; font-size: 0.75em; }
}

</style>
