<style type="text/css">

/*------------------------------*\
	$LOOP
\*------------------------------*/

.loop:not(:last-child) { margin-block-end: var(--md-single); }

/* BOX STYLE */

.main:has(.box-style.loop) { background-color: var(--md-color-content-body); }

.box, .box-style .entry,
.content :is(.page-title.cover, .page-title.cover .overlay),
.entry :is(.cover, .cover .overlay),
.featured-media, .featured-media img,
.box-style .entry .item:nth-child(1 of .item):nth-last-child(1 of .item) { border-radius: var(--md-radius); }

.box-style .entry .item:nth-child(1 of .item),
.box-style .entry :is(.cover, .overlay),
.box-style .image-above :is(.featured-media, .featured-media img),
.header-cover .box-style .image-full :is(.featured-media, .featured-media img) { border-radius: var(--md-radius) var(--md-radius) 0 0; }

.box-style .entry .item:nth-last-child(1 of .item),
.box-style .image-above :is(.cover, .overlay, .featured-media + .item) { border-radius: 0 0 var(--md-radius) var(--md-radius); }

.box-style .image-below .featured-media img,
.box-style .entry.image-below .featured-media + .the-content { border-radius: 0; }

.box, .box-style .entry,
.cover, .featured-media img { box-shadow: var(--md-shadow); }

.box, .box-style .item,
.box-style .image-full .featured-media,
.box-style.columns .entry,
.box-style .comment-details { background-color: var(--md-color-content-box); }

.box-style.columns .entry .item {
	background-color: transparent;
	box-shadow: none;
}

.box-style .byline.post-footer { border-block-start: 1px solid var(--md-color-content-border); }

.box-style .post-footer:not(:last-child) { border-block-end: 1px solid var(--md-color-content-border); }

.box, .box-style .entry { width: auto; }

.box-style.row.full > .item:not(:last-child),
.box-style.row.full .entry:not(:last-child) { margin-block-end: var(--md-single); }

.box-style .item { padding: var(--md-single) var(--md-half); }

.box-style .entry .byline.post-footer { padding: var(--md-half); }

.box-style .entry-title:not(.cover):not(:empty) + .the-content { padding-block-start: 0; }