<style type="text/css">

/*------------------------------*\
	$ALIGNMENTS
\*------------------------------*/

.alignleft, .alignright, .aligncenter, .alignnone {
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

@media (min-width: 700px) {
	.alignleft, .format .left {
		float: left;
		margin-inline-end: <?php echo $half; ?>px;
	}
	.alignright, .format .right {
		float: right;
		margin-inline-start: <?php echo $half; ?>px;
	}
}

@media (max-width: 700px) {
	.format .wp-block-image :is(.alignleft, .alignright, .aligncenter, .alignnone) {
		display: block;
		float: none;
		margin: 0;
		text-align: center;
	}
}

@media (max-width: <?php echo $post_width; ?>px) {
	.compact :is(.alignwide, .alignfull) {
		margin-inline: -50vw;
		position: relative;
			inset-inline: 50%;
		width: 100vw;
	}
}

@media (max-width: <?php echo $content_width + ( $quad * 2 ); ?>px) {
	.expanded .alignwide {
		margin-inline: -50vw;
		position: relative;
			inset-inline: 50%;
		width: 100vw;
	}
}

@media (min-width: <?php echo $content_width + ( $quad * 2 ); ?>px) {
	.expanded :is(.alignwide, .alignright.wrap-small) { margin-inline-end: -<?php echo ( $quad / $content_width ) * 100; ?>%; }
	.expanded :is(.alignwide, .alignleft.wrap-small) { margin-inline-start: -<?php echo ( $quad / $content_width ) * 100; ?>%; }
}

@media (min-width: <?php echo $site_width; ?>px) {
	.expanded .box-style .alignfull {
		inset-inline: inherit;
		width: auto;
	}
	.expanded .box-style .alignfull,
	.expanded .box-style .alignleft.wrap, .expanded .row .image-left .featured-media,
	.expanded :is(.alignleft, .wp-block-image .alignleft) { margin-inline-start: -<?php echo $breakout; ?>%; }
	.expanded .box-style .alignfull,
	.expanded .box-style .alignright.wrap, .expanded .row .image-right .featured-media,
	.expanded :is(.alignright, .wp-block-image .alignright) { margin-inline-end: -<?php echo $breakout; ?>%; }
}

@media (max-width: <?php echo $site_width; ?>px) {
	.expanded .box-style .alignfull {
		margin-inline: -50vw;
		position: relative;
			inset-inline: 50%;
		width: 100vw;
	}
}

@media (min-width: <?php echo $post_width; ?>px) {
	.entry.image-left .featured-media {
		float: left;
		margin-inline-end: <?php echo $single; ?>px;
	}
	.entry.image-right .featured-media {
		float: right;
		margin-inline-start: <?php echo $single; ?>px;
	}
	.columns .image-left .featured-media { margin-inline-end: <?php echo $half; ?>px; }
	.columns .image-right .featured-media { margin-inline-start: <?php echo $half; ?>px; }
	.columns :is(.image-inline, .image-title) .featured-media { max-width: <?php echo round( $sidebar_width / 2 ); ?>px; }
	.slim :is(.image-inline, .image-title) .featured-media { max-width: <?php echo $quad; ?>px; }
	.compact .box-style.full :is(.alignfull, .alignwide), .compact .box-style.full .alignleft.wrap,
	.compact .box-style .entry.full .image-left .featured-media,
	.box-style.columns.full .image-left .featured-media { margin-inline-start: -<?php echo $mid; ?>px; }
	.compact .box-style.full :is(.alignfull, .alignwide), .compact .box-style.full .alignright.wrap,
	.compact .box-style .entry.full .image-right .featured-media,
	.box-style.columns.full .image-right .featured-media { margin-inline-end: -<?php echo $mid; ?>px; }
}