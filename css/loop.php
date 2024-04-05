<style type="text/css">

.loop, .columns {
	display: flex;
	flex-flow: wrap;
	justify-content: space-between;
	row-gap: <?php echo $single; ?>px;
}

.loop:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.entry {
	position: relative;
	width: 100%;
}

/* BOX STYLE */

.box-style .entry .loop { row-gap: 0; }

.box-style > .entry,
.the-embed {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.box-style .headline:not(.cover),
.box-style .the-content,
.box-style .post-footer { padding: <?php echo $half; ?>px; }

.box-style .headline:not(.cover) + .the-content { padding-top: 0; }

@media all and (min-width: 800px) {
	.box-style.row .headline:not(.cover),
	.box-style.row .the-content,
	.box-style.row .post-footer { padding: <?php echo $single; ?>px <?php echo $mid; ?>px; }
}

/* EXPANDED */

@media all and (min-width: <?php echo $post_width; ?>px) {
	.expanded .loop,
	.expanded .the-content,
	.expanded .author-box,
	.expanded .post-footer,
	.expanded .comments {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $content_width; ?>px;
	}
}
