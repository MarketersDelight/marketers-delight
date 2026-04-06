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

/* TITLE, LEDE, SUBTITLE, DESC, CTA */

.post-title :is(.title, .lede, .subtitle),
.page-title :is(.title, .lede, .subtitle) { margin-block-end: 0; }

.lede {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.wide .byline + .lede,
.row.full .byline + .lede { margin-block-start: <?php echo $single; ?>px; }

.subtitle {
	font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
}

.columns .subtitle {
	font-size: inherit;
	line-height: inherit;
}

.cta {
	align-items: center;
	gap: <?php echo $half; ?>px <?php echo $single; ?>px;
	display: flex;
}

/* BYLINE */

.byline {
	align-items: center;
	display: flex;
	flex-flow: wrap;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	gap: <?php echo $half; ?>px;
}

.byline, .byline a, .byline .circle-icon { color: <?php echo $colors['site']['text-sec']; ?>; }

.byline a { text-decoration-color: rgba(0, 0, 0, 0.2); }

.byline:empty { display: none; }

.byline .badge, .byline-date a, .byline-comments a { text-decoration: none; }

.byline-item i:not(:last-child), .byline-author .avatar { margin-inline-end: <?php echo $small; ?>px; }

.byline-edit:last-child { margin-inline-start: auto; }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-sticky {
	color: #22a340;
	display: block;
	font-weight: <?php echo $bold; ?>;
	margin-block-end: <?php echo $half; ?>px;
}

/* COVER */

.cover {
	background-position: center center;
	background-size: cover;
	padding: <?php echo $single; ?>px <?php echo $half; ?>px;
	position: relative;
}

.main .cover, .main .cover .overlay { border-radius: 8px; }

.cover > *:not(.inner):not(.overlay) { position: relative; }

.cover, .cover a, .cover .byline, .cover .lede { color: #fff; }

.cover .byline a { text-decoration-color: rgba(255, 255, 255, 0.5); }

.cover.alt, .cover.alt a, .cover.alt .byline { color: <?php echo $colors['site']['text']; ?>; }

.header-cover.full-cover :is(.content .page-title, .content > .post-title) { padding-block: <?php echo $quad * 2; ?>px <?php echo $double; ?>px; }

/* QUERIES */

@media (min-width: 800px) {
	.wide, .wide .inner,
	.title-center .title-wrap {
		align-items: center;
		text-align: center;
	}
	.image-inline.wide { justify-content: center; }
	.image-inline.wide .inner,
	.image-title.wide .wrap { column-gap: <?php echo $mid; ?>px; }
	.title-left .wrap, .title-right .wrap,
	.image-inline.inline .wrap,
	.image-inline.wide, .image-inline.wide .inner {
		align-items: center;
		flex-flow: row;
	}
	.title-left.wide, .title-left.wide .inner,
	.title-right.wide, .title-right.wide .inner { text-align: inherit; }
	.wide :is(.subtitle, .description),
	.image-inline.wide :is(.title, .lede),
	.image-title.wide .title-wrap {
		margin-inline: auto;
		max-width: <?php echo $post_width; ?>px;
	}
	.image-title.wide .subtitle {
		margin: 0;
		max-width: 100%;
	}
	.page-title.image-inline.image-left .featured-media,
	.image-title.title-left .featured-media { order: -1; }
}