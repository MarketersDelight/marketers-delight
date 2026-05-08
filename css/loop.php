<style type="text/css">

/*------------------------------*\
	$LOOP
\*------------------------------*/

.loop:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

/* BOX STYLE */

.main:has(.box-style.loop) { background-color: <?php echo $colors['content']['body_color']; ?>; }

.box, .box-style .entry,
.content :is(.page-title.cover, .page-title.cover .overlay),
.entry :is(.cover, .cover .overlay),
.featured-media, .featured-media img,
.box-style .entry .item:nth-child(1 of .item):nth-last-child(1 of .item) { border-radius: 8px; }

.box-style .entry .item:nth-child(1 of .item),
.box-style .entry :is(.cover, .overlay),
.box-style .image-above :is(.featured-media, .featured-media img),
.header-cover .box-style .image-full :is(.featured-media, .featured-media img) { border-radius: 8px 8px 0 0; }

.box-style .entry .item:nth-last-child(1 of .item),
.box-style .image-above :is(.cover, .overlay, .featured-media + .item) { border-radius: 0 0 8px 8px; }

.box-style .image-below .featured-media img,
.box-style .entry.image-below .featured-media + .the-content { border-radius: 0; }

.box, .box-style .entry,
.cover, .featured-media img { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15); }

.box, .box-style .item,
.box-style .image-full .featured-media,
.box-style.columns .entry,
.box-style .comment-details { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style.columns .entry .item {
	background-color: transparent;
	box-shadow: none;
}

.box-style .byline.post-footer { border-block-start: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .post-footer:not(:last-child) { border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box, .box-style .entry { width: auto; }

.box-style.row.full > .item:not(:last-child),
.box-style.row.full .entry:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.box-style .item { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }

.box-style .entry .byline.post-footer { padding: <?php echo $half; ?>px; }

.box-style .entry-title:not(.cover):not(:empty) + .the-content { padding-block-start: 0; }