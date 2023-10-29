<style type="text/css">

/*------------------------------*\
	$LOOPS
\*------------------------------*/

/* LOOP ELEMENTS */

.post-box, .headline-area, .title-area { position: relative; }

.cover { padding: <?php echo $mid; ?>px <?php echo $half; ?>px; }

.header .headline-area { text-align: center; }

.content .loop, .content .headline-area:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.the-content, .author-box, .comments, .comments-area:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

/* LOOP TEASERS */

.teaser.featured { flex-basis: 100%; }

.teaser .overlay { display: none; }

.teaser, .teaser .featured-image { transition: 0.3s; }

.teaser:not(:last-child), .teaser.standard .featured-image { margin-bottom: <?php echo $half; ?>px; }

.style-default .teaser:hover { transform: translateY(-5px); }

.style-default .teaser .post-box { padding: <?php echo $half; ?>px; }

.style-default .teaser .featured-image {
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	margin-left: -<?php echo $half; ?>px;
	margin-top: -<?php echo $half; ?>px;
	margin-right: -<?php echo $half; ?>px;
}

.style-default .teaser:hover .featured-image { box-shadow: 0 1px 9px rgba(0, 0, 0, 0.25); }

.teaser-content p:not(:last-child), .teaser.standard .byline { margin-bottom: <?php echo $half; ?>px; }

.loop-teasers .teaser.standard .post-title {
	font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
}

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

.category-post { margin-bottom: <?php echo $half; ?>px; }

.category-post .page-title {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-bottom: <?php echo $single; ?>px;
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
	text-align: left;
}

.category-post .page-title.layout-above_headline .page-image { order: -1; }

.category-post .page-title.layout-above_headline .page-description {
	margin-bottom: 0;
	order: 2;
}

.category-list:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.category-list a, .category-footer a {
	display: block;
	padding: <?php echo $half; ?>px;
}

.category-list-headline {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-weight: normal;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.category-post .category-post-headline {
	flex-basis: 100%;
	margin-bottom: <?php echo $small; ?>px;
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
	.style-default .loop .post-title {
		font-size: <?php echo $typography['h2']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h2']['line_height']['desktop']; ?>px;
	}
	/* TEASERS */
	.teaser.standard {
		font-size: <?php echo $typography['body']['font_size']['mobile'] - 1; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile'] - 3; ?>px;
	}
	.teaser:not(.featured) .byline {
		font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px;
		line-height: <?php echo $typography['body']['line_height']['mobile'] - 3; ?>px;
	}
	.teaser.featured .post-title { margin-bottom: <?php echo $third; ?>px; }
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
	.category-post .page-title {
		padding-left: <?php echo $single; ?>px;
		padding-right: <?php echo $single; ?>px;
	}
	.category-list-headline {
		font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h6']['line_height']['desktop']; ?>px;
	}
	.category-post .category-list a,
	.category-post .category-footer a { padding: <?php echo $half; ?>px <?php echo $single; ?>px; }
	.style-default .category-post .page-title.layout-above_headline .page-image,
	.style-default .category-post .page-title.layout-below_headline .page-image {
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
	.style-default .the-content,
	.style-default.content-sidebar .author-box,
	.style-default.content-sidebar .comments,
	.style-default.content-sidebar .loop .share,
	.style-default .post-box .headline-area {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (min-width: 800px) {
	.post-box { margin-bottom: <?php echo $single; ?>px; }
	.style-default .post-box .headline-area,
	.style-default .the-content,
	.style-default.content-sidebar .author-box,
	.style-default.content-sidebar .comments,
	.style-default.content-sidebar .loop .share,
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
	.teaser {
		flex: 0 1 50%;
		padding-left: <?php echo $half; ?>px;
	}
	/* CATEGORY POSTS */
	.loop-category-posts.full .loop { width: 100%; }
	.loop-category-posts.full .category-posts {
		align-items: center;
		display: flex;
		flex-flow: wrap;
		margin-left: -<?php echo $single; ?>px;
	}
	.loop-category-posts.full .category-post {
		flex-basis: 50%;
		padding-left: <?php echo $single; ?>px;
	}
}
