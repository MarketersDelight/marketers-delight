<style type="text/css">

.loop-header { margin-bottom: <?php echo $half; ?>px; }

.query { position: relative; }

.query.full {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.query.inline { margin-bottom: <?php echo $single; ?>px; }

.query.inline .title-wrap:not(:last-child) { margin-bottom: <?php echo $small; ?>px; }

.query .byline-item {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile'] - 2; ?>px;
}

.size-small .byline-item:not(:last-child) { margin-right: <?php echo $third; ?>px; }

/* SIZES */

.size-normal .title {
	font-size: inherit;
	line-height: inherit;
}

.size-small .title {
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h6']['line_height']['tablet']; ?>px;
}

.size-small .the-content, .size-normal .the-content {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 1; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile'] - 2; ?>px;
}

/* LOOP - LIST */

.loop-list .entry {
	display: flex;
	gap: <?php echo $half; ?>px;
}

.loop-list .image-right .post-content,
.loop-list .image-left .post-content {
	flex: 1;
	max-width: <?php echo $post_width; ?>px;
}

.loop-list .image-right .post-content,
.loop-list .image-center .post-content { order: -1; }

.loop-list .image-center,
.loop-list .image-above-headline { flex-flow: wrap; }

.loop-list .image-right .featured-image,
.loop-list .image-left .featured-image {
	flex-basis: <?php echo $triple; ?>px;
	max-width: <?php echo $triple; ?>px;
}

.loop-list .image-center .featured-image + .post-content,
.loop-list .image-above-headline .featured-image,
.loop-list .image-below-headline .featured-image { margin-bottom: <?php echo $single; ?>px; }

.loop-list .the-content p:not(:last-child) { margin-bottom: <?php echo $third; ?>px; }

@media all and (min-width: 800px) {
	.loop-list.full.size-large .loop { max-width: 100%; }
	.loop-list.full.size-medium .entry,
	.loop-list.full.size-large .entry { gap: <?php echo $single; ?>px; }
	.loop-list.full.size-medium .image-right .featured-image,
	.loop-list.full.size-medium .image-left .featured-image {
		flex-basis: 150px;
		max-width: 150px;
	}
	.loop-list.full.size-large .title {
		font-size: <?php echo $typography['h2']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h2']['line_height']['desktop']; ?>px;
	}
	.loop-list.full.size-large .entry, .loop-list.size-normal .entry { align-items: center; }
	.loop-list.full.size-large .image-right .featured-image,
	.loop-list.full.size-large .image-left .featured-image {
		flex-basis: <?php echo $sidebar_width; ?>px;
		max-width: <?php echo $sidebar_width; ?>px;
	}
}

/* LOOP - COVERS */

.loop-covers .entry {
	background-size: 100%;
	box-shadow: 0 3px 6px rgba(120, 151, 186, .35), 0 3px 6px rgba(120, 151, 186, .45);
	display: flex;
	flex-direction: column;
	justify-content: flex-end;
	margin-bottom: <?php echo $half; ?>px;
	min-height: <?php echo $quad + $double; ?>px;
	padding: <?php echo $half; ?>px;
	transition: all 0.3s ease-in-out;
}

.loop-covers .entry:after {
	background: linear-gradient(to bottom,rgba(0, 0, 0, 0) 0%,rgba(0, 0, 0,.3) 80%);
	border-radius: 15px;
	content: '';
	inset: 0;
	position: absolute;
}

.loop-covers .entry:hover { background-size: 125%; }

.loop-covers .loop .title {
	font-size: inherit;
	line-height: inherit;
	text-shadow: 0 2px 3px rgba(0, 0, 0, 0.8);
}

.loop-covers .post-header {
	padding-bottom: 0;
	padding-top: 0;
	position: relative;
	transition: 0.3s;
	z-index: 10;
}

.loop-covers .entry:hover .post-header { transform: translateY(-<?php echo $small; ?>px); }

.loop-covers .standard .byline-badge { display: none; }

.loop-covers .clickable:after { z-index: 5; }

@media all and (min-width: 700px) {
	.loop-covers .loop {
		display: grid;
		gap: <?php echo $half; ?>px;
		grid-template-columns: repeat(2, 1fr);
	}
	.loop-covers .entry { margin-bottom: 0; }
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

/* LOOP COLUMNS */

.columns .standard .post-header,
.columns .standard .the-content {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.columns .standard .post-header:not(.cover) + .the-content { padding-top: 0; }

@media all and (min-width: 900px) {
	.columns {
		align-items: center;
		display: flex;
		flex-flow: wrap;
		margin-left: -<?php echo $single; ?>px;
	}
	.columns.slim { margin-left: -<?php echo $half; ?>px; }
	.columns > .entry { padding-left: <?php echo $single; ?>px; }
	.columns.slim > .entry { padding-left: <?php echo $half; ?>px; }
	.columns .standard .cover { padding-bottom: <?php echo $half; ?>px; }
	.columns .standard .cover,
	.box-style .columns .standard .post-header,
	.box-style .columns .standard .the-content,
	.box-style .columns .standard .post-footer {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	.columns.wide .standard .post-box .title {
		font-size: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h3']['line_height']['desktop']; ?>px;
	}
	.columns.slim .standard .post-box .title {
		font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
	}
	.columns.slim .standard .the-content {
		font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	}
	.columns .featured-image.alignleft { margin-left: 0; }
	.columns .featured-image.alignright { margin-right: 0; }
	.columns.slim .standard .featured-image.alignleft,
	.columns.slim .standard .featured-image.alignright { max-width: <?php echo round( $sidebar_width / 3 ); ?>px; }
	.columns.wide .standard .featured-image.alignleft,
	.columns.wide .standard .featured-image.alignright { max-width: <?php echo round( $sidebar_width / 2 ); ?>px; }
	.f2 { flex-basis: 50%; max-width: 50%; }
	.f3 { flex-basis: 33.3333333333%; max-width: 33.3333333333%; }
	.f4 { flex-basis: 25%; max-width: 25%; }
	.f5 { flex-basis: 20%; max-width: 20%; }
	.entry.featured { flex-basis: 100%; max-width: 100%; }
	.full .loop .post-box {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $content_width; ?>px;
	}
}
