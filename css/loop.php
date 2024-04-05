<style type="text/css">

.loop, .columns {
	display: flex;
	flex-flow: wrap;
	justify-content: space-between;
	row-gap: <?php echo $single; ?>px;
}

.loop:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

/* GLOBAL */

.entry {
	position: relative;
	width: 100%;
}

.entry .featured-image,
.entry .headline,
.entry .the-content,
.entry .post-footer { margin-bottom: <?php echo $single; ?>px; }

.entry :last-child,
.has-cover.image-before .featured-image { margin-bottom: 0; }

/* BOX STYLE */

.box-style .entry .loop { row-gap: 0; }

.box-style .featured-image,
.box-style .headline,
.box-style .the-content,
.box-style .post-footer { margin-bottom: 0; }

.box-style > .entry,
.the-embed {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.box-style .post-footer {
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.box-style .post-footer:not(:last-child),
.box-style.categories .category-headline,
.box-style.categories .loop .entry:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .post-footer + .post-footer { border-top: 0; }

.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .toggle-comment .comment-content:after { background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, #fefefe 80%); }

.box-style .headline:not(.cover),
.box-style .the-content,
.box-style .post-footer { padding: <?php echo $half; ?>px; }

.box-style .headline:not(.cover) + .the-content { padding-top: 0; }

@media all and (min-width: 800px) {
	.box-style.row .headline,
	.box-style.row .the-content,
	.box-style.row .post-footer { padding: <?php echo $single; ?>px <?php echo $mid; ?>px; }
}

/* EXPANDED */

@media all and (min-width: <?php echo $post_width; ?>px) {
	.expanded .loop {
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
	.expanded .box-style.row .the-content {
		padding-left: 0;
		padding-right: 0;
	}
}
