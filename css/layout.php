<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.inner {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $site_width; ?>px;
	position: relative;
}

.clear:after, .inner:after, .menu:after, .content-sidebar:after, .post-box:after, .the-content:after, .byline:after, .sidebar:after {
	clear: both;
	content: '';
	display: table;
}

/* PAGE STRUCTURE */

.page-header, .loop, .query, .category-row, .category-header { margin-bottom: <?php echo $single; ?>px; }

.query, .entry, .post-box { position: relative; }

.cover, .post-header { padding-top: <?php echo $half; ?>px; }

.cover, .post-header { padding-bottom: <?php echo $half; ?>px; }

.the-content, #content > .inner {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.post-header:not(.cover) + .the-content { padding-top: 0; }

.cover,
.post-box .post-header,
.the-content,
.post-footer {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.content .the-content.full,
.header .cover,
#content > .cover {
	padding-left: 0;
	padding-right: 0;
}

.post-footer {
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.post-footer:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

/* BOX STYLE */

.box-style .entry { margin-bottom: <?php echo $single; ?>px; }

.box-style .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.cover, .cover .overlay { border-radius: 5px; }

.box-style .post-box .cover,
.box-style .post-box .overlay { border-radius: 5px 5px 0 0; }

.box-style .image-above-headline .cover,
.box-style .image-above-headline .overlay { border-radius: 0; }

/* QUERIES */

@media all and (min-width: 900px) {
	.expanded .post-header,
	.header .post-header,
	#content > .post-header { text-align: center; }
	.header .byline, #content > .header-cover .byline, .expanded .byline { justify-content: center; }
	.post-header, .cover { padding-top: <?php echo $single; ?>px; }
	.post-header, .cover { padding-bottom: <?php echo $single; ?>px; }
	.article .header-cover,
	.header .header-cover-full {
		padding-bottom: <?php echo $mid; ?>px;
		padding-top: <?php echo $mid; ?>px;
	}
	.cover,
	.post-box .post-header,
	.the-content,
	.author-box,
	.comments,
	.post-footer {
		padding-left: <?php echo $mid; ?>px;
		padding-right: <?php echo $mid; ?>px;
	}
	.expanded .the-content,
	.expanded .post-footer,
	.expanded .author-box,
	.expanded .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-content { max-width: <?php echo $post_width; ?>px; }
	.full .loop.standard {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $content_width; ?>px;
	}
	.content-sidebar .content {
		float: left;
		width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%;
	}
	.content-sidebar.left .content { float: right; }
	.content-sidebar .sidebar {
		float: left;
		padding-left: <?php echo $single; ?>px;
		width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%;
	}
	.content-sidebar.left .sidebar {
		padding-left: 0;
		padding-right: <?php echo $single; ?>px;
	}
	.sticky {
		position: sticky;
			top: <?php echo $half; ?>px;
	}
	.admin-bar .sticky { top: <?php echo $admin_bar_height + $half; ?>px; }
}

@media all and (max-width: 900px) {
	.content { margin-bottom: <?php echo $single; ?>px; }
	.loop-default.article .post-box {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	#content .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed; }
}

/* COLUMNS */

.columns .standard .post-header,
.columns .standard .the-content {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.columns .standard .post-header .byline { margin-bottom: <?php echo $small; ?>px; }

@media all and (min-width: 900px) {
	.columns {
		align-items: center;
		display: flex;
		flex-flow: wrap;
		justify-content: center;
		margin-left: -<?php echo $single; ?>px;
	}
	.columns.slim { margin-left: -<?php echo $half; ?>px; }
	.columns > .entry { padding-left: <?php echo $single; ?>px; }
	.columns.slim > .entry { padding-left: <?php echo $half; ?>px; }
	.columns .standard .post-header { padding-top: <?php echo $half; ?>px; }
	.columns .standard .cover { padding-bottom: <?php echo $half; ?>px; }
	.columns .standard .cover,
	.columns .standard .post-header,
	.columns .standard .the-content,
	.columns .standard .post-footer {
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
}
