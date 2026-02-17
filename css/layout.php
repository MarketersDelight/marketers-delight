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

.loop.row:not(:last-child) { margin-block-end: <?php echo $double; ?>px; }

.row > .entry, .entry > *:not(:last-child) { margin-block-end: <?php echo $mid; ?>px; }

.content-wrap:not(:last-child),
.loop:not(:last-child),
.loop .entry > *:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.loop .image-above.has-cover .featured-image { margin-block-end: 0; }

.entry .cover { padding-inline: <?php echo $half; ?>px; }

/* BOX STYLE */

.box-style.content { background-color: <?php echo $colors['content']['body_color']; ?>; }

.box-style .row.full > .entry:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.box-style .entry > *:not(:last-child) { margin-block-end: 0; }

.box, .box-style .entry {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	width: auto;
}

.slim .entry .cover,
.content-wrap .page-title.cover,
.box-style .item,
.box-style .entry .post-title:not(.cover),
.box-style .entry .the-content,
.box-style .entry .post-footer { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }

.box-style .post-title:not(.cover) + .the-content { padding-block-start: 0; }

.box-style .post-footer { border-block-start: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .entry .byline.post-footer { padding-block: <?php echo $half; ?>px; }

/* BORDER STYLE */

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

/* QUERIES */

@media all and (min-width: <?php echo $post_width; ?>px) {
	.full .entry .cover,
	.content-wrap .page-title.cover,
	.box-style .item,
	.box-style .full .post-title:not(.cover),
	.box-style .full .the-content,
	.box-style .full .post-footer { padding: <?php echo $mid; ?>px; }
	.box-style.expanded .row .the-content { padding-inline: 0; }
	.box-style .slim .entry .post-title:not(.cover) { padding-block-end: <?php echo $half; ?>px; }
	.box-style .slim .entry .the-content { padding-block-start: <?php echo $half; ?>px; }
	.expanded .the-content, .expanded .post-footer .wrap {
		margin-inline: auto;
		max-width: <?php echo $post_width; ?>px;
		width: 100%;
	}
}

@media all and (min-width: 900px) {
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-width { max-width: <?php echo $post_width; ?>px; }
	.sidebar-width { max-width: <?php echo $sidebar_width; ?>px; }
	.compact .content-wrap:not(:last-child) { margin-block-end: 0; }
	.compact > .inner {
		display: grid;
		gap: <?php echo $single; ?>px;
		grid-template-columns: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>% <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%;
	}
	.compact.left > .inner { direction: rtl; }
	.compact.left .content-wrap, .compact.left .sidebar { direction: ltr; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.inner { padding-inline: <?php echo $half; ?>px; }
	.box-style .row .entry, .content-wrap .page-title.cover { margin-inline: -<?php echo $half; ?>px; }
}