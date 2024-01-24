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

.sticky {
	position: sticky;
		top: -1px;
	z-index: 50;
}

.admin-bar .stuck { padding-top: <?php echo $admin_bar_height; ?>px; }

/* PAGE STRUCTURE */

.loop, .entry, .page-header, .category-row, .category-header { margin-bottom: <?php echo $single; ?>px; }

.query, .entry, .post-box { position: relative; }

.cover, .post-header, .post-box .post-footer {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.post-box .the-content, #content > .inner {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.post-header:not(.cover) + .the-content { padding-top: 0; }

.cover, .post-box .post-header, .post-box .the-content, .post-box .post-footer {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.header .cover, #content > .cover, .post-box .the-content.full {
	padding-left: 0;
	padding-right: 0;
}

.box-style .post-box .post-footer { border-top: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .post-box .post-footer:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.box-style .post-box .post-footer + .post-footer { border-top: 0; }

/* BOX STYLE */

.box-style .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.cover, .cover .overlay { border-radius: 5px; }

.box-style .post-box .cover, .box-style .post-box .overlay { border-radius: 5px 5px 0 0; }

/* QUERIES */

@media all and (min-width: 900px) {
	.header .post-header, .expanded .post-header, #content > .post-header { text-align: center; }
	.post-header, .cover {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
	.article .header-cover,
	.header .header-cover-full {
		padding-bottom: <?php echo $mid; ?>px;
		padding-top: <?php echo $mid; ?>px;
	}
	.cover,
	.box-style .post-box .post-header,
	.box-style .post-box .the-content,
	.box-style .post-box .comments,
	.box-style .post-box .post-footer,
	.author-box {
		padding-left: <?php echo $mid; ?>px;
		padding-right: <?php echo $mid; ?>px;
	}
	.expanded .post-box .the-content,
	.expanded .post-box .post-footer,
	.expanded .author-box,
	.expanded .post-box .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
	.content-sidebar.minimal .post-box .post-header,
	.content-sidebar.minimal .post-box.the-content,
	.content-sidebar.minimal .post-box .post-footer {
		padding-left: 0;
		padding-right: 0;
	}
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-width { max-width: <?php echo $post_width; ?>px; }
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
}

@media all and (max-width: 900px) {
	.content { margin-bottom: <?php echo $single; ?>px; }
	.article .post-box {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	#content .inner, .query .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (max-width: 782px) {
	.admin-bar .stuck { padding-top: <?php echo $admin_bar_height_mobile; ?>px; }
}

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed; }
}