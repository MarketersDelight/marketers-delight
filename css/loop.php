<style type="text/css">

/* BOX STYLE */

.box-style {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.box-style .cover,
.box-style .post-header,
.box-style .the-content { padding: <?php echo $half; ?>px; }

.box-style .cover { border-radius: 5px; }

.box-style.image-above-headline .overlay, .box-style.image-above-headline .cover,
.box-style.image-below-headline .featured-image img { border-radius: 0 0 5px 5px; }

.box-style.image-below-headline .overlay, .box-style.image-below-headline .cover,
.box-style.image-above-headline .featured-image img { border-radius: 5px 5px 0 0; }

.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .toggle-comment .comment-content:after { background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, #fefefe 80%); }

@media all and (max-width: <?php echo $site_width; ?>px) {
	.article .box-style {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.box-style .cover,
	.box-style .post-header,
	.box-style .the-content { padding: <?php echo $single; ?>px <?php echo $mid; ?>px; }
	.columns .box-style .cover,
	.columns .box-style .post-header,
	.columns .box-style .the-content { padding: <?php echo $half; ?>px; }
}

.box-style .post-header:not(.cover) + .the-content { padding-top: 0; }

/* LOOP LIST */

.loop-list .post-header { margin-bottom: <?php echo $half; ?>px; }

.loop-list .title-wrap {
	align-items: center;
	display: flex;
	flex-flow: wrap;
	gap: <?php echo $half; ?>px;
}

.loop-list .image-left .title-wrap,
.loop-list .image-right .title-wrap { flex-flow: inherit; }

.loop-list .image-center .title { order: -1; }

.loop-list .image-right .featured-image,
.loop-list .image-left .featured-image {
	max-width: <?php echo $quad; ?>px;
}

/* SIZES */
