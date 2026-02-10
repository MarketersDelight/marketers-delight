<style type="text/css">

/*------------------------------*\
	$TITLES
\*------------------------------*/

.page-title, .header-cover .content > .post-title { margin-block-end: <?php echo $single; ?>px; }

.post-title, .page-title, .title-wrap,
.wide .inner, .wide .wrap,
.image-title .wrap,
.image-inline.inline .wrap {
	display: flex;
	flex-direction: column;
	gap: <?php echo $half; ?>px <?php echo $single; ?>px;
}

.wide, .wide .inner, .wide .wrap {
	justify-content: center;
	row-gap: <?php echo $half + $third; ?>px;
}

/* COVER */

.header-cover.full-cover .content .page-title,
.header-cover.full-cover .content > .post-title { padding-block-start: <?php echo $triple * 2; ?>px; }

.cover {
	background-position: center center;
	background-size: cover;
	padding-block: <?php echo $mid; ?>px;
	position: relative;
}

.content-wrap .cover, .content-wrap .cover .overlay { border-radius: 5px; }

.cover > *:not(.inner):not(.overlay) { position: relative; }

.cover, .cover a, .cover .byline { color: #fff; }

.cover.alt, .cover.alt a, .cover.alt .byline { color: <?php echo $colors['site']['text']; ?>; }

.entry.image-center .cover, .entry.image-below .cover { margin-block-end: 0; }

/* TITLE, LEDE, SUBTITLE, DESC, CTA */

.post-title .title, .post-title .lede, .post-title .subtitle,
.page-title .title, .page-title .lede, .page-title .subtitle { margin-block-end: 0; }

.lede {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.byline + .lede { margin-top: <?php echo $half; ?>px; }

.subtitle { font-weight: normal; }

.cta {
	display: flex;
	column-gap: <?php echo $single; ?>px;
}

/* BYLINE */

.byline { font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px; }

.byline, .byline a, .byline-item a, .byline .circle-icon { color: <?php echo $colors['site']['text-sec']; ?>; }

.byline:empty { display: none; }

.byline .badge, .byline-date a { text-decoration: none; }

.byline-item:not(:last-child) { margin-inline-end: <?php echo $third; ?>px; }

.byline-item i:not(:last-child), .byline-author .avatar { margin-inline-end: <?php echo $small; ?>px; }

.byline .author-link { text-decoration-color: rgba(0, 0, 0, 0.2); }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-sticky {
	color: #22a340;
	display: block;
	font-weight: <?php echo $bold; ?>;
	margin-block-end: <?php echo $half; ?>px;
}

/* QUERIES */

@media (min-width: 800px) {
	.wide, .wide .inner { align-items: center; }
	.image-inline.wide .inner,
	.image-title.wide .wrap { column-gap: <?php echo $mid; ?>px; }
	.image-title .wrap,
	.image-inline.wide, .image-inline.inline .wrap, .image-inline.wide .inner {
		align-items: center;
		flex-flow: row;
	}
	.post-title:not(.image-inline):not(.image-title).wide .title,
	.page-title:not(.image-inline):not(.image-title).wide .title { text-align: center; }
	.image-inline.wide .title, .image-title.wide .title, .image-title.wide .title-wrap,
	.wide .description, .wide .subtitle { max-width: <?php echo $post_width; ?>px; }
	.post-title .subtitle, .page-title .subtitle { margin-block-end: <?php echo $third; ?>px; }
	.page-title.image-inline.image-left .featured-image,
	.image-title.title-left .featured-image { order: -1; }
}