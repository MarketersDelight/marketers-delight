<style type="text/css">

/* TRIGGERS */

.trigger {
	cursor: pointer;
	position: relative;
}

.trigger-icon {
	color: <?php echo $colors['header']['color']; ?>;
	font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.3 ); ?>px;
	line-height: 1;
}

.trigger .trigger-text {
	font-size: <?php echo $typography['header']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['header']['line_height']['mobile']; ?>px;
	margin-left: <?php echo $small; ?>px;
}

.hide-label .trigger-text { display: none; }

.has-search .trigger-search .trigger-icon:before,
.has-mobile-menu .trigger-menu .trigger-icon:before {
	content: '\e810';
	color: <?php echo $colors['site']['primary']; ?>;
}

/* BREADCRUMBS */

.breadcrumbs {
	font-size: 0.85em;
	margin-bottom: <?php echo $small; ?>px;
}

@media all and (min-width: 900px) {
	.content-full.style-minimal .breadcrumbs { text-align: center; }
}

/* PAGE TITLE */

.page-title {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.page-headline {
	margin-bottom: <?php echo $half; ?>px;
	text-align: center;
}

.page-description {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $post_width; ?>px;
}

.page-image { text-align: center; }

.page-description:not(:last-child), .page-image:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

@media all and (min-width: 800px) {
	.page-title {
		padding-bottom: <?php echo $mid; ?>px;
		padding-top: <?php echo $mid; ?>px;
	}
	.page-title.cover { padding-top: <?php echo $triple; ?>px; }
	.page-title.layout-left, .page-title.layout-right,
	.page-title.layout-left .inner, .page-title.layout-right .inner {
		align-items: center;
		display: flex;
		flex-flow: row wrap;
		justify-content: center;
	}
	.page-headline { margin-bottom: <?php echo $single; ?>px; }
	.layout-left .page-headline, .layout-right .page-headline { flex: 0 0 100%; }
	.layout-left .page-description, .layout-right .page-description {
		flex: 0 0 80%;
		margin-left: 0;
		margin-right: 0;
	}
	.layout-left .page-image, .layout-right .page-image { flex: 0 0 20%; }
	.layout-left .page-image { margin-right: <?php echo $single; ?>px; }
	.layout-right .page-image {
		order: 1;
		margin-left: <?php echo $single; ?>px;
	}
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.page-title {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
		padding-top: <?php echo $mid; ?>px;
	}
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

/* PAGINATION */

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
