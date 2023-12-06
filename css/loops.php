<style type="text/css">

/*------------------------------*\
	$LOOPS
\*------------------------------*/

/* BOX STYLE */

.box-style .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	margin-bottom: <?php echo $single; ?>px;
}

.box-style .the-content {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.post-header, .the-content {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.post-header { padding-top: <?php echo $single; ?>px; }

.post-header.cover { padding-bottom: <?php echo $single; ?>px; }

@media all and (min-width: 900px) {
	.box-style .post-header, .box-style .post-box .page-header,
	.box-style .the-content {
		padding-left: <?php echo $mid; ?>px;
		padding-right: <?php echo $mid; ?>px;
	}
}

/* QUERIES */

@media all and (min-width: 900px) {
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-content { max-width: <?php echo $post_width; ?>px; }
	/* CONTENT - SIDEBAR */
	.content-sidebar .content {
		float: left;
		width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%;
	}
	.content-sidebar .sidebar {
		float: left;
		padding-left: <?php echo $single; ?>px;
		width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%;
	}
	.content-sidebar.sidebar-left .content { float: right; }
}
