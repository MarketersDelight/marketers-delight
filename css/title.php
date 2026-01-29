<style type="text/css">

/*------------------------------*\
	$TITLES
\*------------------------------*/

.page-title, .header-cover .content > .post-title { margin-block-end: <?php echo $single; ?>px; }

.compact .page-title .title { margin-block-end: 0; }

.page-title.inline,
.image-title .wrap,
.entry .post-title,
.wide, .wide .inner, .wide .wrap,
.image-inline.wide, .image-inline.inline .wrap,
.title-wrap {
	display: flex;
	flex-direction: column;
	gap: <?php echo $half; ?>px <?php echo $single; ?>px;
}

.header-cover.full-cover .content .page-title,
.header-cover.full-cover .content > .post-title { padding-block-start: <?php echo $triple * 2; ?>px; }

/* DESCRIPTION + SUBTITLES */

.wide .description, .wide .subtitle {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $post_width; ?>px;
}

/* CTA */

.cta {
	align-items: center;
	display: flex;
	column-gap: <?php echo $single; ?>px;
}

/* QUERIES */

@media (min-width: <?php echo $post_width; ?>px) {
	.expanded .page-title, .expanded .post-title, .post-title.wide .inner {
		align-items: center;
		text-align: center;
	}
	.expanded .slim .post-title, .expanded .image-title {
		align-items: inherit;
		text-align: inherit;
	}
	.image-inline.wide,
	.image-inline.inline .wrap,
	.image-inline.wide .inner,
	.image-title .wrap {
		align-items: center;
		flex-flow: row;
		width: 100%;
	}
	.wide, .wide .inner, .wide .wrap, .image-inline.wide { row-gap: <?php echo $half + $third; ?>px }
	.title-wrap, .image-inline .wrap, .image-inline.inline .description { flex: 1; }
}

@media (max-width: 900px) {
	.header-cover .compact.box-style > .post-title { margin-block-end: 0; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.header-cover .expanded.box-style > .post-title { margin-block-end: 0; }
}