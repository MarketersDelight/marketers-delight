<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.loop { margin-bottom: <?php echo $single; ?>px; }

/* POST COLUMNS */

.columns .standard .the-content {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.columns .standard .post-header .byline { margin-bottom: <?php echo $small; ?>px; }

/* BOX STYLE */

.box-style .entry:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

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

@media all and (max-width: <?php echo $site_width; ?>px) {
	#content .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-content { max-width: <?php echo $post_width; ?>px; }
	.post-header, .cover { padding-top: <?php echo $single; ?>px; }
	.cover { padding-bottom: <?php echo $single; ?>px; }
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
	.columns {
		align-items: center;
		display: flex;
		flex-flow: wrap;
		justify-content: center;
		margin-left: -<?php echo $single; ?>px;
	}
	.f2 { flex-basis: 50%; max-width: 50%; }
	.f3 { flex-basis: 33.3333333333%; max-width: 33.3333333333%; }
	.f4 { flex-basis: 25%; max-width: 25%; }
	.f5 { flex-basis: 20%; max-width: 20%; }
	.entry.featured { flex-basis: 100%; max-width: 100%; }
	.columns.slim { margin-left: -<?php echo $half; ?>px; }
	.columns > .entry.standard { padding-left: <?php echo $single; ?>px; }
	.columns.slim > .entry.standard { padding-left: <?php echo $half; ?>px; }
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
}

@media all and (max-width: 900px) {
	.content { margin-bottom: <?php echo $single; ?>px; }
	.loop-default.article .post-box {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed; }
}
