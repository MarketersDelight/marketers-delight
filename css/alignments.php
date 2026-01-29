<style type="text/css">

/*------------------------------*\
	$ALIGNMENTS
\*------------------------------*/

.auto { margin-inline: auto; }

.alignleft, .alignright,
.aligncenter, .alignnone {
	display: block;
	position: relative;
	margin-block-end: <?php echo $single; ?>px;
	z-index: 10;
}

.alignnone {
	clear: both;
	float: none;
}

.aligncenter {
	clear: both;
	float: none;
	margin-inline: auto;
	text-align: center;
}

img.alignwide, .alignwide img, img.alignfull, .alignfull img { width: 100%; }

.expanded .alignfull {
	margin-inline: -50vw;
	position: relative;
		inset-inline: 50%;
	max-width: 100vw;
	width: 100vw;
}

@media all and (min-width: 700px) {
	.alignleft {
		float: left;
		margin-inline-end: <?php echo $half; ?>px;
	}
	.alignright {
		float: right;
		margin-inline-start: <?php echo $half; ?>px;
	}
}

@media all and (max-width: 700px) {
	.format .wp-block-image .alignleft, .format .wp-block-image .alignright,
	.format .wp-block-image .aligncenter, .format .wp-block-image .alignnone {
		display: block;
		float: none;
		margin: 0;
		text-align: center;
	}
}

@media all and (max-width: <?php echo $post_width; ?>px) {
	.compact .alignwide, .compact .alignfull {
		margin-inline: -50vw;
		position: relative;
			inset-inline: 50%;
		width: 100vw;
	}
}

@media all and (max-width: <?php echo $content_width + $six; ?>px) {
	.expanded .alignwide {
		margin-inline: -50vw;
		position: relative;
			inset-inline: 50%;
		width: 100vw;
	}
}

@media all and (min-width: <?php echo $content_width + $six; ?>px) {
	.expanded .alignwide, .expanded .alignright.wrap-small { margin-inline-end: -<?php echo ( $six / $content_width ) * 100; ?>%; }
	.expanded .alignwide, .expanded .alignleft.wrap-small { margin-inline-start: -<?php echo ( $six / $content_width ) * 100; ?>%; }
}

@media all and (min-width: <?php echo $site_width; ?>px) {
	.expanded.box-style .alignfull {
		inset-inline: inherit;
		width: auto;
	}
	.expanded.box-style .alignfull,
	.expanded.box-style .alignleft.wrap, .expanded .row .image-left .featured-image,
	.expanded .alignleft, .expanded .wp-block-image .alignleft { margin-inline-start: -<?php echo $breakout; ?>%; }
	.expanded.box-style .alignfull,
	.expanded.box-style .alignright.wrap, .expanded .row .image-right .featured-image,
	.expanded .alignright, .expanded .wp-block-image .alignright { margin-inline-end: -<?php echo $breakout; ?>%; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.expanded.box-style .alignfull {
		margin-inline: -50vw;
		position: relative;
			inset-inline: 50%;
		width: 100vw;
	}
}

@media all and (min-width: <?php echo $post_width; ?>px) {
	.entry.image-left .featured-image {
		float: left;
		margin-inline-end: <?php echo $single; ?>px;
	}
	.entry.image-right .featured-image {
		float: right;
		margin-inline-start: <?php echo $single; ?>px;
	}
	.columns .image-left .featured-image { margin-inline-end: <?php echo $half; ?>px; }
	.columns .image-right .featured-image { margin-inline-start: <?php echo $half; ?>px; }
	.columns .image-inline .featured-image,
	.columns .image-title .featured-image { max-width: <?php echo round( $sidebar_width / 2 ); ?>px; }
	.slim .image-inline .featured-image,
	.slim .image-title .featured-image { max-width: <?php echo $quad; ?>px; }
	.compact.box-style .full .alignfull,
	.compact.box-style .full .alignwide, .compact.box-style .full .alignleft.wrap,
	.compact.box-style .full .image-left .featured-image,
	.box-style .columns.full .image-left .featured-image { margin-inline-start: -<?php echo $mid; ?>px; }
	.compact.box-style .full .alignfull,
	.compact.box-style .full .alignwide, .compact.box-style .full .alignright.wrap,
	.compact.box-style .full .image-right .featured-image,
	.box-style .columns.full .image-right .featured-image { margin-inline-end: -<?php echo $mid; ?>px; }
}