<style type="text/css">

/*------------------------------*\
	$LOOPS
\*------------------------------*/

/* LOOP ELEMENTS */

.post-box, .headline-wrap { position: relative; }

.headline-wrap.cover { padding: <?php echo $mid; ?>px <?php echo $half; ?>px; }

.header .headline-wrap { text-align: center; }

.content .headline-wrap { margin-bottom: <?php echo $single; ?>px; }

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

.loop-blocks .post {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
}

.loop-blocks .post-inner {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding: <?php echo $half; ?>px;
}

.loop-blocks .featured-image img {
	border-radius: 5px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
	transition: 0.3s;
}

.loop-blocks .post:hover .featured-image img { transform: scale(0.97); }

.loop-blocks .post-footer {
	align-items: center;
	display: flex;
	padding: <?php echo $half; ?>px;
}

.loop-blocks .post-footer-actions {
	flex: 0 1 auto;
	margin-right: <?php echo $half; ?>px;
}

.loop-blocks .post-footer-meta { flex: 1 0 auto; }

.loop-blocks .byline-comments a {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo round( $typography['body']['font_size']['desktop'] * 1.3 ); ?>px;
	text-decoration: none;
}

.loop-blocks .byline-comments i { color: <?php echo $colors['site']['button']; ?>; }

.loop-blocks .overlay { display: none; }

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
	.loop-blocks .featured-image + .post-content { padding-left: <?php echo $single; ?>px; }
	.loop-blocks .post-footer-meta {
		margin-right: <?php echo $half; ?>px;
		text-align: center;
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
	.headline-wrap, .the-content, .author-box, .comments {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	.content .has-image .headline-wrap.cover { margin-bottom: 0; }
}

@media all and (min-width: 800px) {
	.post-box, .loop-default .featured-image { margin-bottom: <?php echo $single; ?>px; }
	.headline-wrap, .the-content, .author-box, .comments {
		padding-left: <?php echo $mid; ?>px;
		padding-right: <?php echo $mid; ?>px;
	}
	.headline-wrap.cover {
		max-width: 100%;
		padding-left: <?php echo ( ( $mid / $content_width ) * 100 ); ?>%;
		padding-right: <?php echo ( ( $mid / $content_width ) * 100 ); ?>%;
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

@media all and (max-width: 900px) {
	/* BLOCKS */
	.loop-blocks .loop { padding: <?php echo $half; ?>px <?php echo $half; ?>px 0; }
}
