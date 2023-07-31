<style type="text/css">

/*------------------------------*\
	$POST
\*------------------------------*/

/* BREADCRUMBS */

.breadcrumbs {
	font-size: 0.85em;
	margin-bottom: <?php echo $small; ?>px;
}

@media all and (min-width: 900px) {
	.content-full.style-minimal .breadcrumbs { text-align: center; }
}

/* WP BLOCKS */

.wp-block-cover[class*="align"] { width: auto; }

.wp-block-image figcaption {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-style: italic;
	font-size: 0.9em;
	text-align: center;
}

.callout {
	border: 4px solid rgba(0, 0, 0, 0.1);
	border-radius: 5px;
	position: relative;
}

.callout.has-icon { padding-top: 0; }

.callout-title, .callout-action { text-align: center; }

.callout-icon {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
	color: #fff;
	display: block;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

.callout-icon.icon {
	background-color: #1e1e1e;
	font-size: 43px;
	height: 80px;
	margin-top: -25px;
	padding-top: 18px;
	width: 80px;
}

.callout-icon.image {
	height: 100px;
	margin-top: -35px;
	width: 100px;
}

.callout-icon.image img {
	border-radius: 50%;
	height: 100px;
	width: 100px;
}

.content-upgrade { border-radius: 5px; }

.callout-button, .content-upgrade .button { width: 100%; }

/* SHARE NOTICE */

.share-notice {
	border-radius: 3px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
	position: relative;
}

.share-notice.alignfull {
	border-radius: 0;
	border-width: 3px 0;
}

.share-notice-outline {
	border-style: solid;
	border-width: 3px;
}

.share-notice-full, .share-notice-full .share-notice-button,
.share-notice-full .share-notice-icon {
	border-color: #fff;
	color: #fff;
}

.share-notice-button { font-weight: bold; }

.share-notice-icon { line-height: 1; }

.share-notice-twitter.share-notice-outline,
.share-notice-twitter.share-notice-outline .button-outline {
	border-color: #1da1f2;
	color: #1da1f2;
}

.share-notice-twitter.share-notice-full,
.share-notice-twitter.share-notice-outline .button-full { background-color: #1da1f2; }

.share-notice-twitter.share-notice-outline .share-notice-icon,
.share-notice-twitter.share-notice-full .button-full { color: #1da1f2; }

.share-notice-facebook.share-notice-outline,
.share-notice-facebook.share-notice-outline .button-outline {
	border-color: #3b5998;
	color: #3b5998;
}

.share-notice-facebook.share-notice-full,
.share-notice-facebook.share-notice-outline .button-full { background-color: #3b5998; }

.share-notice-facebook.share-notice-outline .share-notice-icon,
.share-notice-facebook.share-notice-full .button-full { color: #3b5998; }

.share-notice-pinterest.share-notice-outline,
.share-notice-pinterest.share-notice-outline .button-outline {
	border-color: #bd081c;
	color: #bd081c;
}

.share-notice-pinterest.share-notice-full,
.share-notice-pinterest.share-notice-outline .button-full { background-color: #bd081c; }

.share-notice-pinterest.share-notice-outline .share-notice-icon,
.share-notice-pinterest.share-notice-full .button-full { color: #bd081c; }

.share-notice-linkedin.share-notice-outline,
.share-notice-linkedin.share-notice-outline .button-outline {
	border-color: #0077b5;
	color: #0077b5;
}

.share-notice-linkedin.share-notice-full,
.share-notice-linkedin.share-notice-outline .button-full { background-color: #0077b5; }

.share-notice-linkedin.share-notice-outline .share-notice-icon,
.share-notice-linkedin.share-notice-full .button-full { color: #0077b5; }

@media all and (min-width: 900px) {
	.box-lr {
		align-items: center;
		display: flex;
	}
	.box-lr .content-upgrade-text {
		margin-bottom: 0;
		width: 65%;
	}
	.box-lr .content-upgrade-action {
		padding-left: <?php echo $single; ?>px;
		width: 35%;
	}
	.share-notice-icon {
		float: right;
		font-size: 42px;
		position: relative;
	}
}

@media all and (max-width: 900px) {
	.note-box-list { margin-left: <?php echo $half; ?>px; }
	.share-notice-icon {
		display: block;
		font-size: 150px;
		line-height: 1;
		position: absolute;
			bottom: 0;
			right: <?php echo $half; ?>px;
		opacity: 0.2;
		transform: rotateZ(-4deg);
	}
}

/* SUBTITLES */

.entry-subtitle {
	display: block;
	font-size: 24px;
	line-height: 36px;
	margin-top: 13px;
}

/* BYLINE */

.byline {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	position: relative;
}

.byline a {
	color: <?php echo $colors['site']['text-sec']; ?>;
	text-decoration: none;
}

.byline .author-link { border-bottom: 1px solid rgba(0, 0, 0, 0.15); }

.has-cover .byline .author-link { border-bottom: 1px solid rgba(255, 255, 255, 0.2); }

.byline .author-link:hover { border-bottom: 0; }

.byline-item { display: inline-block; }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-author .avatar {
	margin-right: <?php echo $small; ?>px;
	position: relative;
}

.byline-item:not(:last-child) { margin-right: <?php echo $third; ?>px; }

.byline .badge {
	font-size: inherit;
	padding: 4px 7px;
}

.byline-comments-label { display: none; }

/* CAPTION */

.wp-caption {
	height: auto;
	max-width: 100%;
}

.wp-caption-text {
	border-bottom: 1px solid #ccc;
	color: #444;
	font-size: 14px;
	font-style: italic;
	line-height: 22px;
	padding: 13px;
}

/* AUTHOR BOX */

.author-box {
	background-color: <?php echo $colors['site']['action']; ?>;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
	padding: <?php echo $half; ?>px;
}

.author-box .circle-icon { margin-right: <?php echo $small; ?>px; }

.author-box .author-link:not(:last-child) { margin-right: <?php echo $half; ?>px; }

.author-box .author-title, .author-box .author-bio { margin-bottom: <?php echo $small; ?>px; }

.author-box .author-avatar { flex: 0 1 <?php echo $triple * 2; ?>px; }

.author-box .author-avatar img { width: 100%; }

.author-box .author-content { padding-left: <?php echo $half; ?>px; }

.author-link.twitter .circle-icon {
	background-color: #1da1f2;
	color: #fff;
}

.author-link.twitter .md-icon-twitter { color: #fff; }

.author-link.twitter a { color: #1da1f2; }

@media all and (min-width: 900px) {
	.author-box {
		border-radius: 5px;
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
}

/* PAGINATION  */

.pagination {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	position: relative;
	text-align: center;
}

.pagination a { text-decoration: none; }

.pagination-sep {
	margin-left: <?php echo $small; ?>px;
	margin-right: <?php echo $small; ?>px;
}

.post-nav-links {
	background-color: rgba(0, 0, 0, 0.05);
	border-radius: 5px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
	padding: <?php echo $half; ?>px;
}

.pagination .page-numbers,
.post-nav-links .post-page-numbers {
	background-color: #fff;
	border: 0;
	border-radius: 50%;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	display: inline-block;
	margin-right: <?php echo $small; ?>px;
	padding: <?php echo $small; ?>px <?php echo $half; ?>px;
}

.page-numbers.current,
.post-page-numbers.current {
	cursor: default;
	font-weight: bold;
}

.pagination .page-numbers:hover,
.post-nav-links.post-page-numbers:hover { opacity: 0.8; }

.page-numbers.dots, .page-numbers.prev, .page-numbers.next {
	background-color: transparent;
	border-radius: inherit;
	box-shadow: none;
	border: 0;
	color: <?php echo $colors['site']['text']; ?>;
	padding: 0;
}

.page-numbers.prev { margin-right: <?php echo $third; ?>px; }
.page-numbers.next { margin-left: <?php echo $third; ?>px; }

/* POST NAV */

.post-nav p { margin-bottom: 0; }

.post-nav a {
	display: block;
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
	text-decoration: none;
}

.post-nav-next { text-align: right; }

.post-nav-previous, .post-nav-next { flex: 1; }

.post-nav-title { color: <?php echo $colors['site']['text']; ?>; }

.post-nav-previous:hover .post-nav-title, .post-nav-next:hover .post-nav-title { text-decoration: underline; }

/* FEATURED IMAGE */

.featured-image { position: relative; }

.featured-image a { display: block; }

.featured-image img { width: 100%; }

.image-caption {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	padding: <?php echo $third; ?>px;
	text-align: center;
}

.post .image-caption { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.cover .image-caption {
	background-color: rgba(0, 0, 0, 0.75);
	color: #fff;
	margin-bottom: 0;
	padding: <?php echo $small; ?>px <?php echo $third; ?>px;
	position: absolute;
		bottom: 0;
		right: 0;
	z-index: 10;
}

@media all and (min-width: 800px) {
	.featured-image.alignleft, .featured-image.alignright { max-width: <?php echo $single * 13; ?>px; }
}
