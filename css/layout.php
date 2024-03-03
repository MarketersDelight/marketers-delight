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
.byline:after, .post-box:after, .the-content:after, .sidebar:after {
	clear: both;
	content: '';
	display: table;
}

/* BLOCK ELEMENTS */

.block .title-wrap, .block .featured-image {
	margin-left: auto;
	margin-right: auto;
}

@media all and (min-width: 700px) {
	.block {
		align-items: center;
		display: flex;
		gap: <?php echo $single; ?>px;
	}
	.block.image-before, .block.image-after, .block.image-center {
		flex-direction: column;
		text-align: center;
	}
	.block .title, .block .description, .block .inline-cta { max-width: <?php echo $content_width; ?>px; }
	.block.image-left .featured-image { order: -1; }
}

@media all and (max-width: 700px) {
	.block .title-wrap:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
}

/* SPACING */

.main > .inner, .query > .inner {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.main + .query > .inner { padding-top: 0; }

@media all and (max-width: <?php echo $site_width; ?>px) {
	.inner {
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

@media all and (min-width: 700px) {
	.f3, .f4, .f5 {
		flex-basis: calc(50% - <?php echo $half; ?>px);
		max-width: calc(50% - <?php echo $half; ?>px);
	}
}

@media all and (max-width: 900px) {
	.content { margin-bottom: <?php echo $single; ?>px; }
}

@media all and (min-width: 900px) {
	<?php for ( $f = 2; $f <= 5; $f++ ) : ?>
	.f<?php echo $f; ?> { flex-basis: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $single; ?>px); max-width: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $single; ?>px); }
	<?php endfor; ?>
	<?php for ( $f = 2; $f <= 5; $f++ ) : ?>
	.box-style.columns > .f<?php echo $f; ?>, .slim .f<?php echo $f; ?> { flex-basis: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $half; ?>px); max-width: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $half; ?>px); }
	.box-style.columns .columns .f<?php echo $f; ?> { flex-basis: <?php echo ( 100 / $f ); ?>%; max-width: <?php echo ( 100 / $f ); ?>%; }
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
