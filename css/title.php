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

/* CTA, DESCRIPTION, SUBTITLE */

.cta {
	align-items: center;
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
	.page-title.image-inline.left .featured-image,
	.image-title.left .featured-image { order: -1; }
	.page-title.wide, .page-title.wide .inner, .page-title.wide .wrap { row-gap: <?php echo $half + $third; ?>px }
	.image-title.wide .wrap { justify-content: center; }
	.image-title .title-wrap { text-align: left; }
	.title-wrap, .wide.image-inline .wrap, .image-inline.inline .description { flex: 1; }
	.wide .description, .wide .subtitle, .wide .cta {
		margin-inline: auto;
		max-width: <?php echo $post_width; ?>px;
	}
}

@media (max-width: <?php echo $post_width; ?>px) {
	.page-title.image-title .wrap { text-align: center; }
	.page-title.image-title .featured-image { margin-inline: auto; }
}

@media (max-width: 900px) {
	.header-cover .compact.box-style > .post-title { margin-block-end: 0; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.header-cover .expanded.box-style > .post-title { margin-block-end: 0; }
	.header-cover .expanded.box-style .breadcrumbs { padding-block-start: <?php echo $half; ?>px; }
}