<style type="text/css">

/*------------------------------*\
	$POST
\*------------------------------*/

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

/* VIDEO */

.video-wrap {
	height: 0;
	position: relative;
	padding-bottom: 56.25%;
	padding-top: 25px;
}

.video-wrap iframe {
	height: 100%;
	position: absolute;
		left: 0;
		top: 0;
	width: 100%;
}

.play-button {
	border: 4px solid #fff;
	border-radius: 50%;
	cursor: pointer;
	display: inline-block;
	height: 75px;
	padding: 20px 26px 26px;
	position: relative;
	text-align: center;
	width: 75px;
}

.play-button:after {
	content: '';
	display: block;
	border-style: solid;
	border-width: 15px 0 15px 22px;
	border-color: transparent transparent transparent rgba(255, 255, 255, 1);
}

.play-button-text {
	font-size: 13px;
	font-weight: bold;
	text-transform: uppercase;
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
