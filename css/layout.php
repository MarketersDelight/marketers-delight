<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.clear:after, [class*="columns-"]:after, .inner:after, .post-box:after, .content-inner:after, .content-text:after, .byline:after, .menu:after, .sidebar:after {
	clear: both;
	content: '';
	display: table;
}

.inner {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $site_width; ?>px;
	position: relative;
}

.post-box { position: relative; }

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed !important; }
}

/* STRUCTURE  */

.header .content-inner {
	max-width: 100%;
	text-align: center;
}

.cta-box.post-box, .post-box .cta-box { box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1); }

@media all and (max-width: <?php echo $site_width; ?>px) {
	.content-box { padding: <?php echo $half; ?>px; }
	.header .content-inner, .header-cover .content-inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (max-width: 900px) {
	.header .content-inner, .header-cover .content-inner {
		padding-bottom: <?php echo $double; ?>px;
		padding-top: <?php echo $double; ?>px;
	}
}

@media all and (min-width: 900px) {
	.content-width { width: <?php echo ( ( $post_width / $site_width ) * 100 ); ?>%; <?php echo "/* ({$post_width} / {$site_width}) * 100 */"; ?> }
	.content-inner {
		margin-left: auto;
		margin-right: auto;
	}
	.header-cover .content-inner {
		max-width: <?php echo $site_width; ?>px;
		text-align: center;
	}
	.header .content-inner, .header-cover .content-inner {
		padding-bottom: <?php echo $quad; ?>px;
		padding-top: <?php echo $quad; ?>px;
	}
	.content-sidebar .content-inner { max-width: <?php echo ( ( $post_width / $content_width ) * 100 ); ?>%; <?php echo "/* ({$post_width} / {$content_width}) * 100 */"; ?>; }
	.content-full .content-inner { max-width: <?php echo ( ( $post_width / $site_width ) * 100 ); ?>%; <?php echo "/* ({$post_width} / {$site_width}) * 100 */"; ?>; }
	.content-full .content-headline .content-inner {
		max-width: 100%;
		padding-left: <?php echo $triple; ?>px;
		padding-right: <?php echo $triple; ?>px;
		text-align: center;
	}
	.content-sidebar .content {
		float: left;
		width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%; <?php echo "/* ({$content_width} / {$site_width}) * 100 */"; ?>
	}
	.sidebar {
		float: left;
		width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%; <?php echo "/* ({$sidebar_width} / {$site_width}) * 100 */"; ?>
	}
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

<?php if ( $content_style == '' ) : ?>

/* STYLE DEFAULT */

.loop-default.style-default .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
}

.loop-default.style-default .post-box > div { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.loop-default.style-default .post-box > .featured-image { border-bottom: 0; }

.loop-default.style-default.content-sidebar .content-text { padding-top: <?php echo $single; ?>px; }

@media all and (min-width: 900px) {
	.content-box {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
	.loop-default.style-default .content-text,
	.style-default .author-box,
	.style-default .comments {
		padding-bottom: <?php echo $double; ?>px;
		padding-top: <?php echo $double; ?>px;
	}
	.loop-default.style-default .content-headline:last-child, .style-default .featured-image-cover { padding-bottom: <?php echo $double; ?>px; }
	.loop-default.style-default .content-headline { padding-top: <?php echo $double; ?>px; }
	.loop-default.style-default .content-headline + [class*="featured-"] { margin-top: <?php echo $double;?>px; }
	.style-default .featured-col .blog-teaser { padding: <?php echo $single; ?>px; }
}

@media all and (max-width: 900px) {
	.style-default.loop-default .post-box {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
	.style-default.loop-default .content-text,
	.style-default .author-box,
	.style-default .comments {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
	.style-default .content-headline .content-inner { padding-top: <?php echo $single; ?>px; }
	.style-default.loop-default .content-headline + [class*="featured-"] { margin-top: <?php echo $single;?>px; }
	.style-default.loop-default .content-headline:last-child, .style-default .featured-image-cover .content-inner { padding-bottom: <?php echo $single; ?>px; }
	.style-default .content-inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

<?php elseif ( $content_style == 'minimal' ) : ?>

/* STYLE MINIMAL */

.style-minimal .content-headline { margin-bottom: <?php echo $single; ?>px; }

.style-minimal .featured-image-cover {
	padding-bottom: <?php echo $mid; ?>px;
	padding-top: <?php echo $mid; ?>px;
}

.style-minimal.loop-teasers .featured-image { margin-bottom: <?php echo $half; ?>px; }

.style-minimal .share + .content-text { margin-top: <?php echo $single;?>px; }

@media all and (min-width: 900px) {
	.style-minimal {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
	.style-minimal.loop-default .post-box { margin-bottom: <?php echo $double; ?>px; }
	.style-minimal.content-sidebar .alignfull, .style-minimal.content-sidebar .alignwide {
		margin-left: 0;
		margin-right: 0;
	}
	.style-minimal.content-sidebar .content-headline:not(:first-child) { padding-top: <?php echo $single; ?>px; }
	.style-minimal.content-full .content-headline:not(:first-child) { padding-top: <?php echo $double; ?>px; }
	.style-minimal.content-full .share + .content-text { margin-top: <?php echo $double;?>px; }
	.style-minimal.loop-default .content-text:not(:last-child) { padding-bottom: <?php echo $double; ?>px; }
	.style-minimal .author-box, .style-minimal .comments {
		padding-bottom: <?php echo $double; ?>px;
		padding-top: <?php echo $double; ?>px;
	}
	.style-minimal .featured-image-cover {
		padding-left: <?php echo $single; ?>px;
		padding-right: <?php echo $single; ?>px;
	}
}

@media all and (max-width: 900px) {
	.style-minimal .content-text { padding-bottom: <?php echo $single; ?>px; }
	.style-minimal .author-box,
	.style-minimal .comments {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
}

<?php endif; ?>

/* LOOP DOCS */

.loop-docs.style-default .post-box { padding: <?php echo $half; ?>px; }

.loop-docs .col-style, .loop-docs .post-box { border-top: 5px solid <?php echo $colors['site']['primary']; ?>; }

.loop-docs.style-default .col-style,
.loop-docs.style-default .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 3px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.loop-docs.style-minimal .post-box,
.loop-docs.style-minimal .col-style {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
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

@media all and (min-width: 900px) {
	.docs-nav { padding-bottom: 0; }
	.loop-docs .docs-category { margin-bottom: <?php echo $single; ?>px; }
	.loop-docs.style-default .post-box {
		margin-bottom: <?php echo $half; ?>px;
		padding: <?php echo $single; ?>px;
	}
	.loop-docs.style-default .col-head, .loop-docs.style-default .col-content { padding: <?php echo $single; ?>px; }
}

/* LOOP TEASERS */

.blog-teasers .col { margin-bottom: <?php echo $single; ?>px; }

.blog-teasers .post-box {
	margin-bottom: <?php echo $single; ?>px;
	vertical-align: middle;
}

.style-default .blog-teaser {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 3px;
	box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
	padding: <?php echo $half; ?>px;
	position: relative;
}

.loop-teasers .cta-box { border-radius: 3px; }

.blog-teasers .featured-image img {
	border-radius: 3px;
	box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
}

.featured-image + .blog-teaser { top: -<?php echo $half; ?>px; }

.teaser-title {
	font-size: <?php echo $h5['font_size']['desktop']; ?>px;
	line-height: <?php echo $h5['line_height']['desktop']; ?>px;
}

.loop-teasers .teaser-title { margin-bottom: <?php echo $small; ?>px; }

.loop-teasers .content .byline { margin-bottom: <?php echo $third; ?>px; }

/* LOOP BLOCKS */

.loop-blocks .post-box { margin-bottom: <?php echo $mid; ?>px; }

.loop-blocks.style-default .post-box {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
}

.loop-blocks.style-default.content-sidebar .post-box:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.loop-blocks .content-headline { margin-bottom: <?php echo $third; ?>px; }

.loop-blocks .content-text { color: <?php echo $colors['site']['text-sec']; ?>; }

.loop-blocks .post-box .content-inner {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	max-width: 100%;
}

.loop-blocks .wp-post-image {
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
	transition: 0.4s;
}

.loop-blocks .post-box:hover .wp-post-image { transform: scale(0.97); }

.loop-blocks .content .byline { margin-bottom: <?php echo $small; ?>px; }

.loop-blocks .content-footer .avatar { top: -5px; }

.loop-blocks .byline-author-name { font-weight: <?php echo $bold; ?>; }

@media all and (min-width: 900px) {
	.loop-blocks.style-default.content-sidebar .post-box { padding: <?php echo $single; ?>px; }
	.loop-blocks.style-default.content-full .post-box { padding: <?php echo $mid; ?>px; }
}

@media all and (min-width: 700px) {
	.blog-teasers[class*="columns-"] .featured-col, .blog-teasers[class*="columns-"] .cta-box {
		clear: both;
		margin-left: <?php echo $single; ?>px;
	}
	.loop-blocks .post-box .content-inner {
		margin-bottom: <?php echo $single; ?>px;
		padding-bottom: <?php echo $single; ?>px;
	}
	.loop-blocks .featured-image {
		float: left;
		width: 40%;
	}
	.loop-blocks .featured-image + .post-content {
		float: left;
		padding-left: 26px;
		width: 60%;
	}
	.loop-blocks .content-footer-author { float: right; }
	.loop-blocks .byline-comments { margin-top: <?php echo $third; ?>px; }
	.loop-blocks.content-full .byline-comments { margin-top: <?php echo $half; ?>px; }
}

@media all and (max-width: 900px) {
	.loop-blocks .post-box .content-inner {
		margin-bottom: <?php echo $half; ?>px;
		padding-bottom: <?php echo $half; ?>px;
		padding-top: <?php echo $half; ?>px;
	}
	.loop-blocks .featured-image { margin-bottom: <?php echo $half; ?>px; }
	.loop-blocks .content-footer {
		padding-bottom: <?php echo $half; ?>px;
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (max-width: 700px) {
	.loop-blocks .byline-comments { float: left; }
	.loop-blocks .byline-edit { display: none; }
	.loop-blocks .content-footer-author { text-align: right; }
	.loop-blocks .more-link { margin-top: <?php echo $small; ?>px; }
}

/* ARCHIVES TITLE */

.archives-title { margin-bottom: <?php echo $single; ?>px; }
.archives-title .headline { margin-bottom: <?php echo $third; ?>px; }

@media all and (min-width: 900px) {
	.archives-title.featured-image-cover { padding-top: <?php echo $double; ?>px; }
	.style-minimal .archives-title .content-inner,
	.style-default.loop-teasers .archives-title:not(.featured-image-cover) .content-inner,
	.loop-blocks .archives-title:not(.featured-image-cover) .content-inner { max-width: 100%; }
}

@media all and (max-width: 900px) {
	.archives-title.featured-image-cover { padding-top: <?php echo $single; ?>px; }
	.style-minimal .archives-title .content-inner { max-width: 100%; }
}