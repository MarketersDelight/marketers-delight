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

.image-title .featured-media { margin-inline: auto; }

.page-title.image-inline .featured-media { order: 1; }

.image-title.title-center .wrap {
	align-items: stretch;
	flex-direction: column;
	row-gap: var(--md-single);
}

/* TITLE, LEDE, SUBTITLE, DESC */

.entry-title :is(.title, .lede, .subtitle) { margin-block-end: 0; }

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

.loop:not(.box-style) .byline.entry-top:not(:last-child),
.byline.before-content { margin-block-end: var(--md-half); }

.byline :is(.byline-comments, .byline-edit) a { text-decoration: none; }

.byline-author .byline-label { font-style: italic; }

.byline-item i:not(:last-child), .byline-author .avatar { margin-inline-end: var(--md-small); }

.byline-item > .byline-item:not(:last-child) { margin-inline-end: var(--md-third); }

.byline:not(.can-wrap) .byline-edit:last-child { margin-inline-start: auto; }

/* COVER */

.cover {
	background-position: center;
	background-repeat: no-repeat;
	background-size: cover;
	padding-block: var(--md-single);
	position: relative;
}

.cover.repeat { background-size: auto; }

.entry .cover, .content .page-title.cover { padding-inline: var(--md-half); }

.full-cover .page-title,
.full-cover .main > .post-title { padding-block: calc(var(--md-quad) * 2) var(--md-double); }

.header-cover .main { padding-block-start: 0; }

.cover > :not(.inner, .overlay) { position: relative; }

.cover.text-white { color: var(--md-site-text-contrast); }

.cover :where(a, .byline, .title, .lede) { color: inherit; }
.cover .byline a { color: inherit; }

.cover.text-dark { color: var(--md-text); }


.cover.has-image > .overlay { background-image: linear-gradient(to bottom, transparent, color-mix(in srgb, var(--md-page-cover-overlay) 100%, transparent)); }

.header-cover.has-image > .overlay { background-image: linear-gradient(to bottom, color-mix(in srgb, var(--md-page-cover-overlay) 100%, transparent), transparent 60%); }

.cover.text-white .tag {
	background-color: color-mix(in srgb, currentColor 16%, transparent);
	box-shadow: inset 0 0 0 1px color-mix(in srgb, currentColor 35%, transparent);
	color: inherit;
}

.cover-text .header :is(.site-name a, .tagline),
.cover-text .header :is(.header-controls, .header-triggers, .search-form .triggers) > .trigger,
.cover-text .header .menu > .menu-item > :where(a, .trigger) { color: var(--md-site-text-contrast); }

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
	.title-left .wrap, .title-right .wrap {
		align-items: center;
		flex-flow: row;
	}
	.image-inline {
		display: block;
		text-align: inherit;
	}
	.image-inline:after {
		clear: both;
		content: '';
		display: table;
	}
	.image-inline .featured-media {
		float: right;
		margin-inline-start: var(--md-single);
		order: 0;
	}
	.image-inline .featured-media img { width: 100%; }
	.image-inline.image-left .featured-media {
		float: left;
		margin-inline: 0 var(--md-single);
	}
	.page-title.image-inline .wrap { display: block; }
	.page-title.image-inline .wrap > * + * { margin-block-start: var(--md-half); }
	.image-inline .byline { justify-content: flex-start; }
	.wide.image-inline :is(.subtitle, .description, .byline) { max-width: none; }
	.image-title:is(.title-left, .title-right) .featured-media {
		flex: 0 1 auto;
		margin-inline: 0;
	}
	.image-title:is(.title-left, .title-right) .featured-media,
	.image-inline .featured-media { max-width: min(40%, var(--md-width-sidebar)); }
	.image-title.wide .wrap { column-gap: var(--md-mid); }
	.title-left.wide, .title-left.wide .inner,
	.title-right.wide, .title-right.wide .inner { text-align: inherit; }
	.wide:not(.image-inline) :is(.subtitle, .description) { margin-inline: auto; }
	.wide .byline {
		justify-content: center;
		width: 100%;
	}
	.image-title.wide .subtitle { margin: 0; }
}
