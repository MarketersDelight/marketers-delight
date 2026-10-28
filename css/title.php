<style type="text/css">

/*------------------------------*\
	$TITLES
\*------------------------------*/

.post-title:empty { display: none; }

.page-title, .header-cover .main > .post-title { margin-block-end: var(--md-single); }

.is-border-style .page-title:not(.cover),
.is-border-style .section-title:not(.cover) {
	border-block-end: 1px solid var(--md-border);
	padding-block-end: var(--md-mid);
}

.entry-title, .title-wrap,
.wide .inner, .wide .wrap,
.image-title .wrap,
.image-inline.inline .wrap,
.has-aside .wrap {
	display: flex;
	flex-direction: column;
	gap: var(--md-half) var(--md-single);
}

.title-wrap {
	flex: 1;
	justify-content: center;
	row-gap: calc(var(--md-half) / 2);
}

.has-aside .title-wrap { row-gap: var(--md-half); }

.title-main, .title-aside {
	align-items: inherit;
	align-self: stretch;
	display: flex;
	flex-direction: column;
	gap: inherit;
}

.title-main { row-gap: calc(var(--md-half) / 2); }

.image-title.title-left .featured-media { order: -1; }

.image-title .featured-media {
	margin-inline: auto;
	max-width: var(--md-media-width, 100%);
}

.page-title.image-inline .featured-media { order: 1; }

.image-title.title-center .wrap {
	align-items: stretch;
	flex-direction: column;
	row-gap: var(--md-single);
}

/* TITLE, LEDE, SUBTITLE, DESC */

.entry-title :is(.title, .lede, .subtitle) { margin-block-end: 0; }

.square-icon.category-title-icon {
	background-color: var(--md-category-color, var(--md-color-primary));
	color: var(--md-category-text, var(--md-site-text-contrast));
	font-size: var(--md-h4);
}

.category-title-wrap .square-icon.category-title-icon { flex-grow: 0; }

.category-title-wrap .title-wrap {
	align-items: flex-start;
	text-align: start;
}

.lede {
	color: var(--md-text-muted);
	font-size: var(--md-font-size-small);
	line-height: var(--md-line-height-small);
}

.wide :is(.subtitle, .description, .byline, .cta) { max-width: var(--md-width-post); }

/* BYLINE */

.byline {
	align-items: center;
	color: var(--md-text-muted);
	display: flex;
	flex-wrap: wrap;
	font-size: var(--md-font-size-small);
	gap: var(--md-small) var(--md-half);
	line-height: var(--md-line-height-small);
}

.byline .circle-icon {
	background-color: color-mix(in srgb, currentColor 12%, transparent);
	color: inherit;
}

.image-title .byline { justify-content: flex-start; }

.byline a {
	color: var(--md-links-muted);
	text-decoration-color: color-mix(in srgb, currentColor 45%, transparent);
	text-decoration-thickness: 0.06em;
	text-underline-offset: 0.14em;
}

.byline:empty { display: none; }

.byline-item, .byline-item > a {
	align-items: center;
	display: inline-flex;
	gap: var(--md-small);
	min-width: 0;
	overflow-wrap: break-word;
}

:is(.byline-item, .byline-item > a) > [class*="md-icon-"] { flex-shrink: 0; }

.title-inline { text-wrap: pretty; }

.title-inline > .title { display: inline; }

.title-inline > .inside-title {
	display: inline-flex;
	margin-inline-start: var(--md-small);
	vertical-align: middle;
}

.loop:not(.box-style) .byline.entry-top:not(:last-child),
.byline.before-content { margin-block-end: var(--md-half); }

.byline.after-content { margin-block-start: var(--md-half); }

.byline :is(.byline-comments, .byline-edit) a { text-decoration: none; }

.byline-author .byline-label { font-style: italic; }

.byline:not(.can-wrap) .byline-edit:last-child { margin-inline-start: auto; }

/* COVER */

.cover {
	background-position: center;
	background-repeat: no-repeat;
	background-size: cover;
	position: relative;
}

:where(.cover) { padding-block: var(--md-single); }

.cover.repeat { background-size: auto; }

:where(.entry .cover, .content .page-title.cover, .section-title.cover) { padding-inline: var(--md-half); }

.full-cover .page-title,
.full-cover .main > .post-title { padding-block: calc(var(--md-quad) * 2) var(--md-double-x); }

.full-cover .page-title:is(.image-inline, .image-title) { padding-block-start: var(--md-quad); }

.header-cover .main { padding-block-start: 0; }

.cover > :not(.inner, .overlay) { position: relative; }

.cover {
	--md-text-muted: color-mix(in srgb, currentColor 80%, transparent);
	--md-links-muted: currentColor;
}

.cover.text-white { color: var(--md-site-text-contrast); }

.cover :where(a:not(.button), .byline, .title, .lede) { color: inherit; }

.cover .foot { color: inherit; }
.cover :is(.byline, .title) a { color: inherit; }

.cover.text-dark { color: var(--md-text); }

.cover.text-dark .byline { color: var(--md-text-muted); }


.header-cover.has-image > .overlay { background-image: linear-gradient(to bottom, color-mix(in srgb, var(--md-overlay, var(--md-page-cover-overlay)) 100%, transparent), transparent 60%); }

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
	:where(.cover) { padding-block: var(--md-mid); }
	:where(.entry .cover, .content .page-title.cover, .section-title.cover) { padding-inline: var(--md-mid); }
}

@media (max-width: 800px) {
	.has-aside :has(> .title-aside) { row-gap: var(--md-single); }
	:is(.image-title, .image-inline) .featured-media {
		margin-inline: auto;
		text-align: center;
	}
	.mobile-hide .featured-media { display: none; }
	.image-title.mobile-beside .wrap {
		align-items: center;
		flex-direction: row;
	}
	.image-title.mobile-beside .featured-media {
		flex: 0 0 auto;
		max-width: min(30%, var(--md-media-width, 100%));
	}
	.image-inline.mobile-beside, .image-inline.mobile-beside .inner { display: flow-root; }
	.image-inline.mobile-beside .featured-media { max-width: min(40%, var(--md-media-width, 100%)); }
	.image-left.mobile-beside .featured-media {
		float: left;
		margin: 0 var(--md-half) var(--md-half) 0;
	}
	.image-right.mobile-beside .featured-media {
		float: right;
		margin: 0 0 var(--md-half) var(--md-half);
	}
	.cover.image-inline .featured-media { max-width: min(60%, var(--md-media-width, 100%)); }
}

@media (min-width: 800px) {
	.expanded .page-title:not(.image-inline, .has-aside) .cta,
	:where(.header-cover .main) > .wide:not(.image-inline, .has-aside) .cta { justify-content: center; }
	.expanded .wide:not(.image-inline, .has-aside), .expanded .wide:not(.image-inline, .has-aside) .inner,
	:where(.header-cover .main) > .wide:not(.image-inline, .has-aside), :where(.header-cover .main) > .wide:not(.image-inline, .has-aside) .inner {
		align-items: center;
		text-align: center;
	}
	.expanded .wide:not(.image-inline, .has-aside) :is(.subtitle, .description),
	:where(.header-cover .main) > .wide:not(.image-inline, .has-aside) :is(.subtitle, .description) { margin-inline: auto; }
	.title-left .wrap, .title-right .wrap {
		align-items: center;
		flex-flow: row;
	}
	.wide:is(.title-left, .title-right) .wrap { text-align: start; }
	.wide:is(.title-left, .title-right) .featured-media { width: var(--md-media-width, auto); }
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
	.page-title.image-inline:not(.has-aside) .wrap { display: block; }
	.page-title.image-inline:not(.has-aside) :is(.description, .cta, .after-title) { margin-block-start: var(--md-half); }
	.wide.image-inline:not(.has-aside) .wrap { max-width: var(--md-width-post); }
	.image-title:is(.title-left, .title-right) .featured-media {
		flex: 0 1 auto;
		margin-inline: 0;
	}
	.image-title:is(.title-left, .title-right) .featured-media,
	.image-inline .featured-media { max-width: min(40%, var(--md-width-sidebar), var(--md-media-width, 100%)); }
	.image-title.wide .wrap { column-gap: var(--md-mid); }
	.wide .byline { width: 100%; }
	.expanded .wide .byline, :where(.header-cover .main) > .wide .byline { justify-content: center; }
	.wide:is(.image-inline, .has-aside) .byline, .wide:is(.title-left, .title-right) .wrap .byline { justify-content: flex-start; }
	.wide .title-inline > .inside-title {
		justify-content: flex-start;
		max-width: none;
		width: auto;
	}
	.image-title.wide .subtitle { margin: 0; }
	.cover.image-inline .inner {
		align-items: center;
		column-gap: var(--md-mid);
		flex-flow: row wrap;
	}
	.cover.image-inline .inner > :not(.wrap, .featured-media) { flex-basis: 100%; }
	.cover.image-inline .wrap { flex: 1; }
	.cover.image-inline .inner > .featured-media {
		flex: 0 1 auto;
		float: none;
		margin: 0;
		max-width: min(45%, var(--md-media-width, 100%));
		order: 1;
	}
	.cover.image-inline.image-left .inner > .featured-media { order: -1; }
	.wide.has-aside .wrap { align-self: stretch; }
	.wide.has-aside :has(> .title-aside) {
		align-items: flex-start;
		align-self: stretch;
		column-gap: var(--md-double);
		flex-direction: row;
		text-align: start;
	}
	.wide.has-aside .title-main {
		flex: 1;
		min-width: 0;
	}
	.wide.has-aside .title-aside {
		align-self: center;
		flex: 0 0 var(--md-title-aside-width, min(30%, var(--md-width-sidebar)));
	}
	.wide.has-aside:is(.image-right, .title-right) .title-aside { order: -1; }
	.cover.image-inline.image-right .inner { justify-content: space-between; }
}
