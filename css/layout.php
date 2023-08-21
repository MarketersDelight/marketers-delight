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

/* QUERIES */

@media all and (max-width: <?php echo $site_width; ?>px) {
	.content-box .inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.content { width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%; }
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-width { max-width: <?php echo $post_width; ?>px; }
	.sidebar { width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%; }
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

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed !important; }
}
