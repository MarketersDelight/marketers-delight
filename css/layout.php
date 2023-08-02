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

.header.has-logo .header-logo a, .main-menu-wrap,
<?php if ( md_setting( array( 'header', 'logo', 'url' ) ) ) : ?>
.header-logo a,
<?php endif; ?>
.page-header,
.author-box, .comments-title, .comment-details, .post-nav,
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

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed !important; }
}

/* STRUCTURE  */

@media all and (max-width: <?php echo $site_width; ?>px) {
	.content-box .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
		padding-top: <?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.content-box .inner {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
	.content { width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%; }
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-width { max-width: <?php echo $post_width; ?>px; }
	.sidebar { width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%; }
	/* CONTENT FULL */
	.content-full .content {
		margin-left: auto;
		margin-right: auto;
	}
	.content-full .content { width: 100%; }
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

@media all and (max-width: 800px) {
	.content-box .inner {
		padding-left: 0;
		padding-right: 0;
		padding-top: 0;
	}
	.sidebar {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
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

.page-headline { text-align: center; }

.page-description {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $post_width; ?>px;
}

.page-image { text-align: center; }

.page-description:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

@media all and (min-width: 800px) {
	.page-title {
		padding-bottom: <?php echo $mid; ?>px;
		padding-top: <?php echo $mid; ?>px;
	}
	.page-title.cover { padding-top: <?php echo $triple; ?>px; }
	.header-cover .page-title.cover { padding-top: <?php echo $double; ?>px; }
	.page-title.layout-left, .page-title.layout-right,
	.page-title.layout-left .inner, .page-title.layout-right .inner {
		align-items: center;
		display: flex;
		flex-flow: row wrap;
		justify-content: center;
	}
	.page-headline:not(:last-child),
	.layout-center .page-image:not(:last-child),
	.layout-above_headline .page-image:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
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

@media all and (max-width: 800px) {
	.page-image:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.page-title {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
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

