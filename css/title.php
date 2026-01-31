<style type="text/css">

/*------------------------------*\
	$TITLES
\*------------------------------*/

.page-title, .header-cover .content > .post-title { margin-block-end: <?php echo $single; ?>px; }

.post-title .title, .page-title .title { margin-block-end: 0; }

.post-title, .page-title, .title-wrap,
.wide .inner, .wide .wrap,
.image-title .wrap,
.image-inline.inline .wrap {
	display: flex;
	flex-direction: column;
	gap: <?php echo $half; ?>px <?php echo $single; ?>px;
}

.header-cover.full-cover .content .page-title,
.header-cover.full-cover .content > .post-title { padding-block-start: <?php echo $triple * 2; ?>px; }

/* CTA, DESCRIPTION, SUBTITLE4 */

.wide .description, .wide .subtitle, .wide .cta {
	margin-inline: auto;
	max-width: <?php echo $post_width; ?>px;
}

.cta {
	align-items: center;
	display: flex;
	column-gap: <?php echo $single; ?>px;
}

/* QUERIES */

@media (min-width: <?php echo $post_width; ?>px) {
	.post-title.wide, .page-title.wide {
		align-items: center;
		text-align: center;
	}
	.image-title .wrap,
	.image-inline.wide, .image-inline.inline .wrap, .image-inline.wide .inner {
		align-items: center;
		flex-flow: row;
		width: 100%;
	}
	.image-title.left .featured-image { order: -1; }
	.wide, .wide .inner, .wide .wrap { row-gap: <?php echo $half + $third; ?>px }
	.image-title.wide .wrap { justify-content: center; }
	.image-title .title-wrap { text-align: left; }
	.title-wrap, .wide.image-inline .wrap, .image-inline.inline .description { flex: 1; }
}

@media (max-width: 900px) {
	.header-cover .compact.box-style > .post-title { margin-block-end: 0; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.header-cover .expanded.box-style > .post-title { margin-block-end: 0; }
}