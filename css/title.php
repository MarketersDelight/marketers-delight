<style type="text/css">

/*------------------------------*\
	$TITLES
\*------------------------------*/

.post-title:empty { display: none; }

.page-title, .header-cover .main > .post-title { margin-block-end: var(--md-single); }

.is-border-style .page-title:not(.cover) {
	border-block-end: 1px solid var(--md-border);
	padding-block-end: var(--md-mid);
}

.entry-title, .title-wrap,
.wide .inner, .wide .wrap,
.image-title .wrap,
.image-inline.inline .wrap {
	display: flex;
	flex-direction: column;
	gap: var(--md-half) var(--md-single);
}

.title-wrap {
	flex: 1;
	justify-content: center;
}

.image-title.title-left .featured-media { order: -1; }

.image-title.title-center .wrap {
	align-items: center;
	flex-direction: column;
}

/* TITLE, LEDE, SUBTITLE, DESC */

.entry-title :is(.title, .lede, .subtitle) { margin-block-end: 0; }

.page-title .cta { justify-content: flex-start; }

.lede {
	color: var(--md-text-muted);
	font-size: var(--md-font-size-sm);
	line-height: var(--md-line-height-sm);
}

.wide :is(.subtitle, .description, .byline) { max-width: var(--md-width-post); }

/* BYLINE */

.byline {
	align-items: center;
	color: var(--md-text-muted);
	display: flex;
	flex-wrap: wrap;
	font-size: var(--md-font-size-sm);
	gap: var(--md-small) var(--md-half);
}

.byline .circle-icon {
	background-color: color-mix(in srgb, currentColor 12%, transparent);
	color: inherit;
}

.byline a {
	color: var(--md-links-muted);
	text-decoration-color: color-mix(in srgb, currentColor 45%, transparent);
	text-decoration-thickness: 0.06em;
	text-underline-offset: 0.14em;
}

.byline:empty { display: none; }

.byline :is(.byline-comments, .byline-edit) a { text-decoration: none; }

.byline-author .byline-label { font-style: italic; }

.byline-item i:not(:last-child), .byline-author .avatar { margin-inline-end: var(--md-small); }

.byline-item > .byline-item:not(:last-child) { margin-inline-end: var(--md-third); }

.byline:not(.can-wrap) .byline-edit:last-child { margin-inline-start: auto; }

/* COVER */

.cover {
	background-position: center;
	background-size: cover;
	padding-block: var(--md-single);
	position: relative;
}

.entry .cover, .content .page-title.cover { padding-inline: var(--md-half); }

.full-cover .page-title,
.full-cover .main > .post-title { padding-block: calc(var(--md-quad) * 2) var(--md-double); }

.header-cover .main { padding-block-start: 0; }

.cover > :not(.inner, .overlay) { position: relative; }

.cover.text-white { color: var(--md-site-text-contrast); }

.cover.text-white :is(a, .byline, .title, .lede) { color: inherit; }

.cover-text .header :is(.site-name a, .tagline),
.cover-text .header :is(.header-controls, .header-triggers) > .trigger,
.cover-text .header .menu > .menu-item > :is(a, .trigger) { color: var(--md-site-text-contrast); }

/* QUERIES */

@media (min-width: <?php echo $post_width; ?>px) {
	.cover { padding-block: var(--md-mid); }
	.entry .cover, .content .page-title.cover { padding-inline: var(--md-mid); }
}

@media (max-width: 600px) {
	.byline {
		font-size: calc(var(--md-font-size-sm) - 2px);
		line-height: calc(var(--md-line-height-sm) - 1px);
	}
}

@media (max-width: 799px) {
	.image-title .featured-media { text-align: center; }
}

@media (min-width: 800px) {
	.expanded .page-title .cta { justify-content: center; }
	.wide, .wide .inner {
		align-items: center;
		text-align: center;
	}
	.title-left .wrap, .title-right .wrap,
	.image-inline.inline, .image-inline.inline .inner {
		align-items: center;
		flex-flow: row;
	}
	.image-inline.wide, .image-inline.wide .inner {
		align-items: center;
		flex-flow: row;
		justify-content: center;
		text-align: inherit;
	}
	.image-inline.wide .inner, .image-title.wide .wrap { column-gap: var(--md-mid); }
	.image-inline.wide .wrap { max-width: var(--md-width-post); }
	.title-left.wide, .title-left.wide .inner,
	.title-right.wide, .title-right.wide .inner { text-align: inherit; }
	.wide:not(.image-inline) :is(.subtitle, .description) { margin-inline: auto; }
	.wide .byline {
		justify-content: center;
		width: 100%;
	}
	.image-title:is(.title-left, .title-right).wide :is(.byline, .cta) { justify-content: flex-start; }
	.image-title:is(.title-left, .title-right).wide .description { margin-inline: 0; }
	.image-title.wide .subtitle { margin: 0; }
	.image-inline.image-left .featured-media { order: -1; }
}
