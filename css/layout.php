<style type="text/css">

/* TRIGGERS */

.trigger {
	cursor: pointer;
	position: relative;
	text-align: center;
}

.trigger-icon {
	font-size: <?php echo round( $header['font_size']['desktop'] * 1.3 ); ?>px;
	font-style: normal;
	line-height: 1;
}

.trigger .trigger-text { margin-left: <?php echo $small; ?>px; }

.hide-label .trigger-text { display: none; }

.has-search .trigger-search .trigger-icon:before,
.has-mobile-menu .trigger-menu .trigger-icon:before {
	color: <?php echo $colors['site']['primary']; ?>;
	content: '\e810';
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

/* BREADCRUMBS */

.breadcrumbs {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
	margin-bottom: <?php echo $half; ?>px
}

.breadcrumbs a { text-decoration: underline; }

.breadcrumbs a:hover { text-decoration: none; }

.breadcrumbs a, .breadcrumbs i, .breadcrumb-text { margin-right: <?php echo $third; ?>px; }

/* PAGE HEADER */

.page-header { position: relative; }

.header .page-header {
	padding-bottom: <?php echo $mid; ?>px;
	padding-top: <?php echo $double; ?>px;
}

.header .page-header.image-left, .header .page-header.image-right { text-align: left; }

.page-header.headline-image .inner,
.content .page-header.headline-image {
	align-items: center;
	display: flex;
	justify-content: center;
}

.page-title, .page-description, .page-image { position: relative; }

.page-description:not(:last-child), .title-area:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

.page-image img { width: 100%; }

.page-header.image-right .page-image { margin-left: <?php echo $single; ?>px; }
.page-header.image-left .page-image { margin-right: <?php echo $single; ?>px; }
.page-header.image-above_headline .page-image,
.page-header.image-below_headline .page-image { margin-bottom: <?php echo $single; ?>px; }
.page-header.image-below_headline .page-image {
	margin-left: auto;
	margin-right: auto;
}
.page-header.image-center .inner, .page-header.image-above_headline .inner { flex-flow: wrap; }
.page-header.image-center .title-area, .page-header.image-above_headline .title-area { flex-basis: 100%; }

@media all and (min-width: 800px) {
/*
	.full .page-description, .full .breadcrumbs { text-align: center; }
*/
	.header .page-description, .full .page-description, .full .subtitle {
		margin-left: auto;
		margin-right: auto;
		max-width: <?php echo $post_width; ?>px;
		text-align: left;
	}
	.full .page-description:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
}

@media all and (max-width: 800px) {
	.page-header.image-left .title-area, .page-header.image-right .title-area { flex: 1; }
}

/* AUTHOR BOX */

.author-box {
	background-color: <?php echo $colors['site']['action']; ?>;
	padding: <?php echo $single; ?>px <?php echo $half; ?>px <?php echo $half; ?>px;
}

.author-title { margin-bottom: <?php echo $half; ?>px; }

.author-box .circle-icon { margin-right: <?php echo $third; ?>px; }

.author-link:not(:last-child) { margin-right: <?php echo $half; ?>px; }

.author-headline { margin-bottom: <?php echo $small; ?>px; }

.author-headline {
	font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
}

.author-bio { margin-bottom: <?php echo $half + $small; ?>px; }

.author-avatar {
	flex: 0 1 <?php echo $double; ?>px;
	margin-right: <?php echo $half; ?>px;
}

.author-avatar img { width: 100%; }

.author-meta {
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
	font-size: <?php echo round( $typography['body']['font_size']['mobile'] - 1 ); ?>px;
	line-height: <?php echo round( $typography['body']['line_height']['mobile'] - 1 ); ?>px;
}

.author-meta .author-link {
	color: <?php echo $colors['site']['links_sec']; ?>;
	display: inline-block;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.author-link.twitter .circle-icon {
	background-color: #1da1f2;
	color: #fff;
}

.author-link.twitter .md-icon-twitter { color: #fff; }

.author-link.twitter a { color: #1da1f2; }

/* POST NAV */

.post-nav { margin-left: -<?php echo $half; ?>px; }

.post-nav p { margin-bottom: 0; }

.post-nav a {
	display: block;
	text-decoration: none;
}

.post-nav-next { text-align: right; }

.post-nav-previous, .post-nav-next {
	flex: 1;
	margin-left: <?php echo $half; ?>px;
}

.post-nav-title { color: <?php echo $colors['site']['text']; ?>; }

.post-nav-previous:hover .post-nav-title, .post-nav-next:hover .post-nav-title { text-decoration: underline; }

/* PAGINATION */

.pagination {
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

/* QUERIES */

@media all and (max-width: 800px) {
	.hide-label-mobile .trigger-text { display: none; }
}
