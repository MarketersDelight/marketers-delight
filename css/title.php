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
/*
.wide, .wide .inner, .wide .wrap { justify-content: center; }
*/
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

.subtitle {
	font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
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

.byline, .byline a, .byline-item a, .byline .circle-icon { color: <?php echo $colors['site']['text-sec']; ?>; }

.byline:empty { display: none; }

.byline .badge, .byline-date a, .byline-comments a { text-decoration: none; }

.byline-item i:not(:last-child), .byline-author .avatar { margin-inline-end: <?php echo $small; ?>px; }

.byline-edit:last-child { margin-inline-start: auto; }

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
	.wide, .wide .inner,
	.title-center .title-wrap {
		align-items: center;
		text-align: center;
	}
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

	.image-inline.wide .title,
	.image-title.wide :is(.title-wrap, .title),
	.wide :is(.description, .subtitle) {
		margin-inline: auto;
		max-width: <?php echo $post_width; ?>px;
	}

	.image-inline.wide { justify-content: center; }

	.page-title.image-inline.image-left .featured-media,
	.image-title.title-left .featured-media { order: -1; }
}