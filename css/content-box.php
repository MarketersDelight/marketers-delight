<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.clear:after, .inner:after, .menu:after,
.post-box:after, .the-content:after, .byline:after, .sidebar:after,
[class*="columns-"]:after {
	clear: both;
	content: '';
	display: table;
}

.header.has-logo .header-logo a,
.header-triggers, .header-controls,
<?php if ( md_setting( array( 'header', 'logo', 'url' ) ) ) : ?>
.header-logo a,
<?php endif; ?>
.page-header,
.author-title, .comments-title, .comment-details, .post-nav,
.fields-icons .form-field,
.form-inputs, .search-form, .wp-block-search__inside-wrapper {
	align-items: center;
	display: flex;
}

.inner {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $site_width; ?>px;
	position: relative;
}

.content-box { padding-bottom: <?php echo $single; ?>px; }

/* DEFAULT STYLE */

.the-content .featured-image { z-index: 1; }

.the-content .featured-image img,
.post-inner .featured-image img {
	box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
	transition: 0.3s;
}

.post-box:hover .the-content .featured-image img,
.post-box:hover .post-inner .featured-image img { transform: scale(0.97); }

.page-image img,
.style-default .post-box, .style-default .featured-image img,
.headline-wrap.cover, .headline-wrap.cover .overlay { border-radius: 5px; }

.post-box.has-cover.has-below-image .headline-wrap.cover,
.post-box.has-cover.has-below-image .headline-wrap.cover .overlay { border-radius: 5px 5px 0 0; }

.style-default.loop-default .has-below-image .featured-image img { border-radius: 0; }

.style-default .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.style-default .loop .post-box.has-cover,
.style-default .post-box.has-headline-cover,
.style-default .post-box.has-top-image,
.style-default .post-box.has-cover.has-below-image { padding-top: 0; }

.style-default .featured-image { margin-bottom: <?php echo $single; ?>px; }

.style-default .loop .has-cover.has-top-image .featured-image,
.style-default .has-cover.has-below-image .headline-wrap { margin-bottom: 0; }

.loop-default.style-default .author-box {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
}

/* QUERIES */

@media all and (min-width: <?php echo $site_width; ?>px) {
	.loop-default.content-full.is-article .headline-wrap {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
	.loop-default.content-full.is-article .headline {
		margin-left: -<?php echo $quad; ?>px;
		margin-right: -<?php echo $quad; ?>px;
		text-align: center;
	}
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.content-box .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	/* DEFAULT STYLE */
	.content-full.loop-default.style-default .loop {
		margin-left: -<?php echo $quad; ?>px;
		margin-right: -<?php echo $quad; ?>px;
	}
	.content-full.style-default.loop-default.is-article .post-box {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.content { width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%; }
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-width { max-width: <?php echo $post_width; ?>px; }
	.sidebar { width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%; }
	.loop-default .loop .has-inline-image .featured-image { margin-bottom: 0; }
	/* CONTENT FULL */
	.content-full .content {
		margin-left: auto;
		margin-right: auto;
	}
	.content-full .page-headline-wrap {
		flex-basis: auto;
		margin-left: -<?php echo $quad; ?>px;
		margin-right: -<?php echo $quad; ?>px;
		text-align: center;
		width: <?php echo ( $content_width + ( $quad * 2 ) ); ?>px;
	}
	/* CONTENT - SIDEBAR */
	.content-sidebar .content, .content-sidebar .sidebar { float: left; }
	.content-sidebar .sidebar { padding-left: <?php echo $single; ?>px; }
	.content-sidebar.sidebar-left .content { float: right; }
	<?php if ( ! empty( $colors['sidebar']['bg_color'] ) ) : ?>
		.content-sidebar .inner { display: flex; }
		<?php if ( md_setting( array( 'content', 'layout' ) ) == 'sidebar_content' ) : ?>
			.content-sidebar.sidebar-left .sidebar { order: 1; }
			.content-sidebar.sidebar-left .content { order: 2; }
		<?php endif; ?>
	<?php else : ?>
		.content-sidebar.sidebar-left .sidebar {
			padding-left: 0;
			padding-right: <?php echo $single; ?>px;
		}
	<?php endif; ?>
}

@media all and (max-width: 900px) {
	/* DEFAULT STYLE */
	.content-full.loop-default.style-default .loop {
		margin-left: 0;
		margin-right: 0;
	}
	.style-default.loop-default .post-box {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (min-width: 800px) {
	/* CONTENT FULL */
	.content-full.loop-default.is-article .content { width: 100%; }
	.content-full.loop-default.is-article .the-content,
	.content-full.loop-default.is-article .author-box,
	.content-full.loop-default.is-article .comments {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
	.content-full.loop-default.is-article .headline-wrap, .content-full .breadcrumbs { text-align: center; }
}

@media all and (max-width: 800px) {
	.content:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
	/* DEFAULT STYLE */
	.single .has-cover + .content-box.style-default .post-box.has-image,
	.style-default.loop-default .post-box.has-cover.has-inline-image { padding-top: 0; }
}

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed !important; }
}
