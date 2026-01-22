<style type="text/css">

/*------------------------------*\
	$TITLES
\*------------------------------*/

.page-title,
.header-cover .content > .post-title { margin-bottom: <?php echo $single; ?>px; }

.page-title.inline,
.image-title .wrap,
.entry .post-title,
.wide, .wide .inner, .wide .wrap,
.image-inline.wide,
.image-inline.inline .wrap,
.title-wrap {
	display: flex;
	flex-direction: column;
	gap: <?php echo $half; ?>px <?php echo $single; ?>px;
}

@media all and (min-width: <?php echo $post_width; ?>px) {
	.expanded .page-title,
	.expanded .post-title,
	.post-title.wide .inner {
		align-items: center;
		text-align: center;
	}
	.expanded .slim .post-title,
	.expanded .image-title {
		align-items: inherit;
		text-align: inherit;
	}
	.image-inline.wide,
	.image-inline.inline .wrap,
	.image-inline.wide .inner,
	.image-title .wrap {
		align-items: center;
		flex-flow: row;
		width: 100%;
	}
	.title-wrap, .image-inline .wrap, .image-inline.inline .description { flex: 1; }
}

@media all and (max-width: 900px) {
/*
	.box-style .page-title:first-child { padding-top: <?php echo $single; ?>px; }
*/
	.header-cover .compact.box-style > .post-title { margin-bottom: 0; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.header-cover .expanded.box-style > .post-title { margin-bottom: 0; }
}