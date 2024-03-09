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

/* SPACING */

.main > .inner, .query > .inner {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.main + .query > .inner, .block + .main > .inner { padding-top: 0; }

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
	.show-mobile { display: none !important; }
	.f3, .f4, .f5 {
		flex-basis: calc(50% - <?php echo $half; ?>px);
		max-width: calc(50% - <?php echo $half; ?>px);
	}
}

@media all and (max-width: 700px) {
	.show-desktop { display: none !important; }
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

/* BLOCK ELEMENTS */

.page-header { margin-bottom: <?php echo $single; ?>px; }

.description:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

.cta {
	align-items: center;
	display: flex;
	gap: <?php echo $single; ?>px;
	position: relative;
}

.cta-link {
	flex: 1;
	text-align: center;
}

.block .block-inner,
.block .featured-image {
	margin-left: auto;
	margin-right: auto;
}

.block.inline { flex-flow: wrap; }

.block.inline .block-inner {
	align-items: center;
	display: flex;
	gap: <?php echo $half; ?>px <?php echo $single; ?>px;
}

.block.inline .title,
.block.inline .description,
.block.inline .cta { flex: 1; }

@media all and (min-width: 700px) {
	.block, .block > .inner {
		align-items: center;
		display: flex;
		gap: <?php echo $half; ?>px <?php echo $single; ?>px;
		text-align: center;
	}
	.inline, .block.image-right, .block.image-left { text-align: left; }
	.block.image-before, .block.image-after, .block.image-center {
		flex-direction: column;
		text-align: center;
	}
	.block.inline .title { margin-bottom: 0; }
	.block.wide .title:not(:last-child),
	.block.wide .description:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
	.block.image-left .description, .block.image-right .description {
		margin-left: 0;
		margin-right: 0;
	}
	.block.image-left .featured-image { order: -1; }
	.block.wide .cta { justify-content: center; }
}

@media all and (max-width: 700px) {
	.block .block-inner:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
	.block.inline .block-inner { flex-direction: column; }
}

@media all and (min-width: <?php echo $content_width; ?>px) {
	.block .title { width: <?php echo $content_width; ?>px; }
	.block .description,
	.block .cta {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $post_width; ?>px;
	}
	.the-content .block .title,
	.the-content .block .description,
	.the-content .block .cta { max-width: 100%; }
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
