<style type="text/css">

/*------------------------------*\
	$LOOPS
\*------------------------------*/

/* LOOP ELEMENTS */

.post-box, .headline-wrap { position: relative; }

.content .headline-wrap { margin-bottom: <?php echo $half; ?>px; }

.the-content, .author-box,
.comments, .comments-area:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.loop .post-box.has-cover.image-above_headline .featured-image,
.post-box.has-cover.image-below_headline .headline-wrap { margin-bottom: 0; }

.the-content:first-child { padding-top: <?php echo $single; ?>px; }

/* DEFAULT */

.loop-default.style-default .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.loop-default.style-default .featured-image,
.post-box.image-below_headline .headline-wrap { margin-bottom: <?php echo $single; ?>px; }

.loop-default.style-default .post-box.image-above_headline { padding-top: 0; }

/* TEASERS */

.teaser {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	padding: <?php echo $half; ?>px;
	position: relative;
}

.loop-teasers .post.featured-col { flex-basis: 100%; }

.loop-teasers .post, .teaser, .loop-teasers .featured-image { transition: 0.3s; }

.loop-teasers .post:hover { transform: translateY(-5px); }

.loop-teasers .post:hover .teaser, .loop-teasers .post:hover .featured-image { box-shadow: 0 1px 9px rgba(0, 0, 0, 0.25); }

.teaser .overlay { display: none; }

.featured-image + .teaser { border-radius: 0 0 5px 5px; }

.loop-teasers .featured-image { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15); }

.loop-teasers .featured-image, .loop-teasers .featured-image img { border-radius: 5px 5px 0 0; }

.teaser .headline {
	font-size: <?php echo $typography['h2']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['h2']['line_height']['mobile']; ?>px;
	margin-bottom: <?php echo $third; ?>px;
}

.teaser .byline {
	font-size: 0.9em;
	margin-bottom: <?php echo $third; ?>px;
}

.teaser p { margin-bottom: <?php echo $third; ?>px; }

/* DOCS */

.loop-docs.style-default .post-box {
	margin-bottom: <?php echo $half; ?>px;
	padding: <?php echo $half; ?>px;
}

.loop-docs .col-style, .loop-docs .post-box { border-bottom: 5px solid <?php echo $colors['site']['secondary']; ?>; }

.loop-docs.style-minimal .col-style, .loop-docs.style-minimal .post-box {
	border-bottom-color: <?php echo $colors['content']['border_color']; ?>;
	border-bottom-width: 1px;
}

.loop-docs.style-default .col-style,
.loop-docs.style-default .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 3px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.loop-docs.style-minimal .post-box,
.loop-docs.style-minimal .col-style {
	margin-bottom: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
}

.loop-docs .col-head { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.loop-docs.style-minimal .col-head {
	margin-bottom: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
}

.loop-docs .featured-image {
	float: left;
	width: 15%;
}

.loop-docs .featured-image + .post-content {
	float: left;
	padding-left: <?php echo $half; ?>px;
	width: 85%;
}

.loop-docs .teaser-title .badge {
	background-color: #999;
	padding-left: 6px;
	padding-right: 6px;
	top: -4px;
}

.loop-docs .list { margin-left: 0; }

.loop-docs.style-default .col-head, .loop-docs.style-default .col-content { padding: <?php echo $half; ?>px; }

.docs-nav {
	margin-bottom: <?php echo $single; ?>px;
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $content_width; ?>px;
}

.content-full .docs-nav {
	margin-bottom: <?php echo $mid; ?>px;
	text-align: center;
}

.content-full.loop-docs .breadcrumbs { text-align: center; }

/* BLOCKS */

.content-full.loop-blocks .content {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $content_width; ?>px;
}

.loop-blocks.style-default .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
}

.loop-blocks .post-box:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

.loop-blocks .content-headline { margin-bottom: <?php echo $third; ?>px; }

.loop-blocks .content-text { color: <?php echo $colors['site']['text-sec']; ?>; }

.loop-blocks .content-inner {
	margin-bottom: <?php echo $half; ?>px;
	max-width: 100%;
}

.loop-blocks.style-default .content-inner { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.loop-blocks.style-minimal .post-box { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.loop-blocks .wp-post-image {
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
	transition: 0.4s;
}

.loop-blocks .post-box:hover .wp-post-image { transform: scale(0.97); }

.loop-blocks .content .byline { margin-bottom: <?php echo $small; ?>px; }

.loop-blocks .content-footer .avatar { top: -5px; }

.loop-blocks .byline-author-name { font-weight: <?php echo $bold; ?>; }

/* RESETS */

.loop-default.style-default .post-box.has-cover, .loop-default.style-default .post-box.has-top-image { padding-top: 0; }

/* QUERIES */

@media all and (min-width: 900px) {
	.the-content, .author-box, .comments { max-width: <?php echo ( ( $post_width / $content_width ) * 100 ); ?>%; }
	.content-full .the-content, .content-full .author-box, .content-full .comments { max-width: <?php echo ( ( $post_width / $site_width ) * 100 ); ?>%; }
}

@media all and (min-width: 800px) {
	.post-box:not(:last-child), .post-box.has-cover .headline-wrap { margin-bottom: <?php echo $single; ?>px; }
	.headline-wrap, .the-content, .author-box, .comments {
		margin-left: auto;
		margin-right: auto;
	}
	.headline-wrap, .the-content, .author-box, .comments { max-width: <?php echo ( ( $post_width / $content_width ) * 100 ); ?>%; }
	.content-full .headline-wrap {
		padding: <?php echo $half; ?>px;
		text-align: center;
	}
	/* TEASERS */
	.loop-teasers .loop {
		align-items: center;
		display: flex;
		flex-wrap: wrap;
		margin-left: -<?php echo $half; ?>px;
	}
	.loop-teasers .post-box {
		flex: 0 1 50%;
		padding-left: <?php echo $half; ?>px;
	}
}

@media all and (min-width: 700px) {
	/* TEASERS */
	.teaser {
		font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile'] - 2; ?>px;
	}
	/* BLOCKS */
	.loop-blocks.style-default .post-box .content-inner, .loop-blocks.style-minimal .post-box { padding-bottom: <?php echo $half + $third; ?>px; }
	.loop-blocks .headline {
		font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
	}
	.loop-blocks .featured-image {
		float: left;
		width: 40%;
	}
	.loop-blocks .featured-image + .post-content {
		float: left;
		padding-left: <?php echo $half + $third; ?>px;
		width: 60%;
	}
	.content-full.loop-blocks .featured-image + .post-content { padding-left: <?php echo $single; ?>px; }
	.loop-blocks .content-footer-author { float: right; }
	.loop-blocks .byline-comments { margin-top: <?php echo $small; ?>px; }
	.loop-blocks.content-full .byline-comments { margin-top: <?php echo $half; ?>px; }
}

@media all and (max-width: 900px) {
	.loop-blocks .featured-image { margin-bottom: <?php echo $half; ?>px; }
	.loop-blocks .content-footer {
		padding-bottom: <?php echo $half; ?>px;
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (max-width: 800px) {
	.post-box:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }
	.headline-wrap, .the-content, .author-box, .comments {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	/* DEFAULT */
	.single .loop-default.style-default .inner { padding: 0; }
	.single .loop-default.style-default .sidebar { padding: <?php echo $half; ?>px; }
	/* TEASERS */
	.loop-teasers .loop { padding: <?php echo $half; ?>px <?php echo $half; ?>px 0; }
}

@media all and (max-width: 700px) {
	.loop-blocks .byline-comments { float: left; }
	.loop-blocks .byline-edit { display: none; }
	.loop-blocks .content-footer-author { text-align: right; }
	.loop-blocks .more-link { width: auto; }
	.loop-blocks .headline {
		font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
	}
}
