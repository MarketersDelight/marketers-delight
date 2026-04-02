<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.clear:after,
.entry.image-left .the-content:after,
.entry.image-right .the-content:after {
	clear: both;
	content: '';
	display: table;
}

.inner {
	margin-inline: auto;
	max-width: <?php echo $site_width; ?>px;
	position: relative;
	width: 100%;
}

.content {
	<?php if ( $colors['content']['body_color'] !== $defaults['colors']['content']['body_color'] ) : ?>
	background-color: <?php echo $colors['content']['body_color']; ?>;
	<?php endif; ?>
	padding-block: <?php echo $single; ?>px;
}

.header-cover .content { padding-block-start: 0; }

/* LOOP */

.content-wrap:not(:last-child), .loop:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.loop .image-above.has-cover .featured-media { margin-block-end: 0; }

.entry .cover { padding-inline: <?php echo $half; ?>px; }

/* BOX STYLE */

.box-style.content { background-color: <?php echo $colors['content']['body_color']; ?>; }

.box, .box-style .entry {
	border-radius: 8px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	width: auto;
}

.box, .box-style .item,
.box-style .image-full .featured-media { background-color: <?php echo $colors['content']['bg_color']; ?>; }

.box-style .item:first-child,
.box-style .image-above .featured-media { border-radius: 8px 8px 0 0; }

.box-style .item:last-child { border-radius: 0 0 8px 8px; }

.box-style .row.full > .entry:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.box-style .item,
.slim .entry .cover,
.content-wrap .page-title.cover { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }

.box-style .post-title:not(.cover) + .the-content { padding-block-start: 0; }

.box-style .item.post-footer { border-block-start: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .byline.post-footer { padding: <?php echo $half; ?>px; }


/*
.box-style .entry > .post-footer.byline { padding: <?php echo $half; ?>px; }
*/
/*
*/
/*
.box-style .entry .byline.post-footer { padding-block: <?php echo $half; ?>px; }
*/
/* PLAIN + BORDER STYLES */

:is(.plain-style, .border-style) .row > .entry { margin-block-end: <?php echo $mid; ?>px; }

:is(.plain-style, .border-style) .row.full .entry > *:not(:last-child) { margin-block-end: <?php echo $mid; ?>px; }

.is-border-style .header:not(.cover) { border-block-end: 1px solid <?php echo $colors['header']['border_color']; ?>; }

.is-border-style .footer { border-block-start: 1px solid <?php echo $colors['footer']['border_color']; ?>; }

.border-style .page-title:not(.cover) {
	border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-block-end: <?php echo $mid; ?>px;
}

.border-style .loop.row .entry:not(:first-child) {
	border-block-start: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-block-start: <?php echo $mid; ?>px;
}

.border-style .loop.row .entry.has-cover,
.border-style .loop.row .entry.image-above {
	border-block-start: 0;
	padding-block-start: 0;
}

/* SLIM */

.slim.columns .post-title { gap: <?php echo $small; ?>px; }

.columns .entry > *:not(:last-child) { margin-block-end: <?php echo $half; ?>px; }

/* QUERIES */

@media all and (min-width: <?php echo $post_width; ?>px) {
	.full .entry .cover,
	.content-wrap .page-title.cover,
	.box-style .item { padding: <?php echo $mid; ?>px; }
/*
	.box-style.expanded .row .the-content { padding-inline: 0; }
*/
	.box-style .slim .item.post-title:not(.cover) { padding-block-end: <?php echo $half; ?>px; }

	.box-style .slim .item.the-content { padding-block-start: <?php echo $half; ?>px; }

	.expanded .item .wrap {
		margin-inline: auto;
		max-width: <?php echo $post_width; ?>px;
		width: 100%;
	}
	.expanded .image-title .wrap {
		max-width: 100%;
		width: auto;
	}
}

@media all and (min-width: 900px) {
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-width { max-width: <?php echo $post_width; ?>px; }
	.sidebar-width { max-width: <?php echo $sidebar_width; ?>px; }
	.compact .content-wrap:not(:last-child) { margin-block-end: 0; }
	.compact > .inner {
		display: grid;
		gap: <?php echo $mid; ?>px;
		grid-template-columns: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>% <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%;
	}
	.compact.left > .inner { direction: rtl; }
	.compact.left .content-wrap, .compact.left .sidebar { direction: ltr; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.inner { padding-inline: <?php echo $half; ?>px; }
	.box-style .row .entry,
	.content-wrap .page-title.cover { margin-inline: -<?php echo $half; ?>px; }
}