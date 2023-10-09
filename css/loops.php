<style type="text/css">

/*------------------------------*\
	$LOOPS
\*------------------------------*/

/* LOOP ELEMENTS */

.post-box, .headline-area { position: relative; }

.post-box .headline-area.cover, .content .page-header.cover { padding: <?php echo $mid; ?>px <?php echo $half; ?>px; }

.header .headline-area { text-align: center; }

.content .loop, .content .headline-area { margin-bottom: <?php echo $single; ?>px; }

.the-content, .author-box, .comments, .comments-area:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

/* LOOP TEASERS */

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

/* LOOP BLOCKS */

.loop-blocks .post-inner {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding: <?php echo $half; ?>px;
}

.loop-blocks .post-footer {
	align-items: center;
	display: flex;
	padding: <?php echo $half; ?>px;
}

.loop-blocks .post-footer-actions {
	flex: 0 1 auto;
	margin-right: <?php echo $half; ?>px;
}

.loop-blocks .post-footer-meta {
	flex: 1 0 auto;
	margin-bottom: 0;
}

.loop-blocks .byline-comments a {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo round( $typography['body']['font_size']['desktop'] * 1.3 ); ?>px;
	text-decoration: none;
}

.loop-blocks .byline-comments i { color: <?php echo $colors['site']['button']; ?>; }

.loop-blocks .overlay { display: none; }

.loop-blocks.style-default .post-box { padding: 0; }

/* CATEGORY POSTS */

.category-post {
	margin-bottom: <?php echo $single; ?>px;
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.category-post-title.page-title {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	margin-bottom: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
}

.category-post-title.layout-above_headline .page-image { order: -1; }

.category-post-title.layout-above_headline .page-description {
	margin-bottom: 0;
	order: 2;
}

.category-post-title .page-description {
	font-size: inherit;
	line-height: inherit;
}

.category-post .list {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	list-style: none;
	margin-bottom: <?php echo $half; ?>px;
	margin-left: 0;
}

.category-post .list > .category-list:not(:last-child) {
	margin-bottom: 0;
	padding-bottom: 0;
}

.category-post .category-list a {
	display: block;
	padding-bottom: <?php echo $third; ?>px;
	padding-top: <?php echo $third; ?>px;
}

.category-list-headline {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-weight: normal;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.category-post .category-post-headline {
	flex-basis: 100%;
	margin-bottom: 0;
}

.category-list-headline .badge { font-size: <?php echo $typography['body']['font_size']['mobile'] - 1; ?>px; }

.category-list-read-more i { margin-left: <?php echo $third; ?>px; }

/* QUERIES */

@media all and (min-width: 600px) {
	.loop-blocks .post-inner { display: flex; }
	.loop-blocks .featured-image + .post-content { padding-left: <?php echo $half; ?>px; }
	.loop-blocks .featured-image { flex: 0 0 40%; }
}

@media all and (max-width: 600px) {
	.loop-blocks .featured-image { margin-bottom: <?php echo $half; ?>px; }
}

@media all and (min-width: 700px) {
	/* TEASERS */
	.teaser {
		font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile'] - 2; ?>px;
	}
	/* BLOCKS */
	.loop-blocks .headline {
		font-size: <?php echo $typography['h2']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['h2']['line_height']['tablet']; ?>px;
	}
	.loop-blocks .post-inner, .loop-blocks .post-footer { padding: <?php echo $single; ?>px; }
	.loop-blocks .post-footer-meta {
		margin-right: <?php echo $half; ?>px;
		text-align: center;
	}
	/* CATEGORY POSTS */
	.category-post {
		padding-left: <?php echo $mid; ?>px;
		padding-right: <?php echo $mid; ?>px;
	}
	.category-list-headline {
		font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h6']['line_height']['desktop']; ?>px;
	}
	.category-post .category-list a {
		padding-bottom: <?php echo $half; ?>px;
		padding-top: <?php echo $half; ?>px;
	}
	.style-default .category-post-title.layout-above_headline .page-image,
	.style-default .category-post-title.layout-below_headline .page-image {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (max-width: 700px) {
	/* BLOCKS */
	.loop-blocks .post-footer { flex-flow: row wrap; }
	.loop-blocks .post-footer-meta { text-align: right; }
	.loop-blocks .button {
		margin-top: <?php echo $half; ?>px;
		width: 100%;
	}
}

@media all and (max-width: 800px) {
	.post-box { margin-bottom: <?php echo $half; ?>px; }
	.style-default .post-box .headline-area,
	.style-default.content-sidebar .the-content,
	.style-default.content-sidebar .author-box,
	.style-default.content-sidebar .comments {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (min-width: 800px) {
	.post-box { margin-bottom: <?php echo $single; ?>px; }
	.style-default .post-box .headline-area,
	.style-default.content-sidebar .the-content,
	.style-default.content-sidebar .author-box,
	.style-default.content-sidebar .comments,
	.content .page-header.cover {
		padding-left: <?php echo $mid; ?>px;
		padding-right: <?php echo $mid; ?>px;
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
