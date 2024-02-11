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

.clear:after, .inner:after, .menu:after, .content-sidebar:after,
.post-box:after, .the-content:after, .byline:after, .sidebar:after {
	clear: both;
	content: '';
	display: table;
}

.layout {
	align-items: center;
	display: flex;
	row-gap: <?php echo $half; ?>px;
}

.columns {
	display: flex;
	justify-content: space-around;
	row-gap: <?php echo $single; ?>px;
}

/* SPACING */

.main > .inner, .query > .inner {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.main + .query > .inner { padding-top: 0; }

.page-header:not(:last-child), .section-header { margin-bottom: <?php echo $single; ?>px; }

@media all and (max-width: <?php echo $site_width; ?>px) {
	.main .inner, .query .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
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

@media all and (max-width: 800px) {
	.layout, .columns { flex-flow: wrap; }
	.content { margin-bottom: <?php echo $single; ?>px; }
}

@media all and (min-width: 800px) {
	.loop.columns, .categories.columns { flex-flow: wrap; }
	.layout { column-gap: <?php echo $single; ?>px; }
	.layout:not(.inline) { row-gap: <?php echo $single; ?>px; }
	<?php for ( $f = 2; $f <= 8; $f++ ) : ?>
	.f<?php echo $f; ?> {
		flex-basis: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $half; ?>px);
		max-width: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $half; ?>px);
	}
	<?php endfor; ?>
}

/* STICKY */

.sticky {
	position: sticky;
		top: -1px;
	z-index: 50;
}

.admin-bar .stuck { padding-top: <?php echo $admin_bar_height; ?>px; }

@media all and (max-width: 782px) {
	.admin-bar .stuck { padding-top: <?php echo $admin_bar_height_mobile; ?>px; }
}

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed; }
}
