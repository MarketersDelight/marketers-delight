<style type="text/css">

/*------------------------------*\
	$TITLES
\*------------------------------*/

.post-title:empty { display: none; }

.page-title, .header-cover .main > .post-title { margin-block-end: var(--md-single); }

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

.image-title .wrap { flex-direction: row; }

.image-title.title-left .featured-media { order: -1; }

.image-title.title-center .wrap {
	align-items: center;
	flex-direction: column;
}

/* TITLE, LEDE, SUBTITLE, DESC */

.entry-title :is(.title, .lede, .subtitle) { margin-block-end: 0; }

.lede {
	color: var(--md-color-text-secondary);
	font-size: var(--md-font-size-sm);
	line-height: var(--md-line-height-sm);
}

.wide .byline + .lede, .row.full .byline + .lede { margin-block-start: var(--md-single); }

/* BYLINE */

.byline {
	align-items: center;
	display: flex;
	flex-flow: wrap;
	font-size: var(--md-font-size-sm);
	gap: var(--md-small) var(--md-half);
}

.byline, .byline a, .byline .circle-icon { color: var(--md-color-text-secondary); }

.byline a { text-decoration-color: rgba(0, 0, 0, 0.2); }

.byline:empty { display: none; }

.byline .badge, .byline-comments a { text-decoration: none; }

.byline-author .byline-label { font-style: italic; }

.byline-item i:not(:last-child), .byline-author .avatar { margin-inline-end: var(--md-small); }

.byline-item > .byline-item:not(:last-child) { margin-inline-end: var(--md-third); }

.byline:not(.can-wrap).byline-edit:last-child { margin-inline-start: auto; }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-sticky {
	color: var(--md-button);
	display: block;
	font-weight: var(--md-bold);
	margin-block-end: var(--md-half);
}

/* COVER */

.cover {
	background-position: center center;
	background-size: cover;
	padding-block: var(--md-single);
	position: relative;
}

.cover > *:not(.inner):not(.overlay) { position: relative; }

.cover.text-white, .cover.text-white :is(a, .byline, .title, .lede) { color: var(--md-color-white); }

.cover.text-white .byline a { text-decoration-color: rgba(255, 255, 255, 0.5); }

.cover-text .header :is(.header-triggers, .site-name a, .tagline, .menu > .menu-item > .trigger, .menu > .menu-item > a) { color: var(--md-color-white); }

/* QUERIES */

@media (min-width: 800px) {
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
	.wide :is(.subtitle, .description, .byline) { max-width: var(--md-width-post); }
	.wide:not(.image-inline) :is(.subtitle, .description, .byline) { margin-inline: auto; }
	.image-title.wide .subtitle {
		margin: 0;
		max-width: 100%;
	}
	.image-inline.image-left .featured-media { order: -1; }
}