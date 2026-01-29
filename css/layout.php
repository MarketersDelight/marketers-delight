<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

/*
.clear:after, .inner:after, .menu:after, .compact:after,
.byline:after, .the-content:after, .sidebar:after {
*/
.clear:after, .compact:after {
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

.row > .entry,
.entry > *:not(:last-child) { margin-block-end: <?php echo $mid; ?>px; }

.loop .entry > *:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.entry .cover { padding-inline: <?php echo $half; ?>px; }

/* BOX STYLE */

.box-style.content { background-color: <?php echo $colors['content']['body_color']; ?>; }

.box-style .row .entry:not(:last-child),
.loop:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.box, .box-style .entry {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	width: auto;
}

.box-style .entry > *:not(:last-child) { margin-block-end: 0; }

.slim .entry .cover,
.content-wrap .page-title.cover,
.box-style .item,
.box-style .entry .post-title:not(.cover),
.box-style .entry .the-content,
.box-style .entry .post-footer { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }

.box-style .post-title:not(.cover) + .the-content { padding-block-start: 0; }

.box-style .post-footer { border-top: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .entry .byline.post-footer { padding-block: <?php echo $half; ?>px; }

/* PLAIN STYLE */

.is-plain .header:not(.cover) { border-bottom: 1px solid <?php echo $colors['header']['border_color']; ?>; }
.is-plain .footer { border-top: 1px solid <?php echo $colors['footer']['border_color']; ?>; }

.plain .page-title:not(.cover) {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-block-end: <?php echo $mid; ?>px;
}

.plain .loop.row .entry:not(:first-child) {
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-block-start: <?php echo $mid; ?>px;
}

.plain .loop.row .entry.has-cover,
.plain .loop.row .entry.image-above {
	border-top: 0;
	padding-block-start: 0;
}

/* QUERIES */

@media all and (min-width: <?php echo $post_width; ?>px) {
	.content { padding-block-start: <?php echo $single; ?>px; }
	.full .entry .cover,
	.content-wrap .page-title.cover,
	.box-style .item,
	.box-style .full .post-title:not(.cover),
	.box-style .full .the-content,
	.box-style .full .post-footer { padding: <?php echo $mid; ?>px; }
	.box-style.expanded .row .the-content { padding-inline: 0; }
	.box-style .slim .entry .post-title:not(.cover) { padding-block-end: <?php echo $half; ?>px; }
	.box-style .slim .entry .the-content { padding-block-start: <?php echo $half; ?>px; }
	.page-title.wide .description,
	.page-title.wide .cta,
	.expanded .the-content,
	.expanded .post-footer .wrap {
		margin-inline: auto;
		max-width: <?php echo $post_width; ?>px;
		width: 100%;
	}
}

@media all and (min-width: 900px) {
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-width { max-width: <?php echo $post_width; ?>px; }
	.sidebar-width { max-width: <?php echo $sidebar_width; ?>px; }
	.compact .content-wrap {
		float: left;
		width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%;
	}
	.compact.left .content-wrap { float: right; }
	.compact .sidebar {
		float: left;
		padding-inline-start: <?php echo $single; ?>px;
		width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%;
	}
	.compact.left .sidebar { padding-inline: 0 <?php echo $single; ?>px; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.box-style.content.expanded .breadcrumbs { padding-block-start: <?php echo $half; ?>px; }
	.inner { padding-inline: <?php echo $half; ?>px; }
	.box-style .entry, .content-wrap .page-title.cover { margin-inline-start: -<?php echo $half; ?>px; }
}

@media all and (max-width: 900px) {
	.box-style.content.compact { padding-block-start: 0; }
	.box-style .entry, .content-wrap .page-title.cover { margin-inline-end: -<?php echo $half; ?>px; }
	.sidebar { margin-block-start: <?php echo $single; ?>px; }
}