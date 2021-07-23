<style type="text/css">

/*------------------------------*\
	$POST
\*------------------------------*/

/* BREADCRUMBS */

.breadcrumbs {
	font-size: 0.85em;
	margin-bottom: <?php echo $half; ?>px;
}

@media all and (min-width: 900px) {
	.content-full.style-minimal .breadcrumbs { text-align: center; }
}

/* WP BLOCKS */

.wp-block-image.shadow { box-shadow: none; }

.wp-block-cover[class*="align"] { width: auto; }

.callout {
	border: 4px solid rgba(0, 0, 0, 0.1);
	border-radius: 5px;
	position: relative;
}

.callout.has-icon { padding-top: 0; }

.callout-title, .callout-action { text-align: center; }

.callout-icon {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
	color: #fff;
	display: block;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

.callout-icon.icon {
	background-color: #1e1e1e;
	font-size: 43px;
	height: 80px;
	margin-top: -25px;
	padding-top: 18px;
	width: 80px;
}

.callout-icon.image {
	height: 100px;
	margin-top: -35px;
	width: 100px;
}

.callout-icon.image img {
	border-radius: 50%;
	height: 100px;
	width: 100px;
}

.content-upgrade { border-radius: 5px; }

.callout-button, .content-upgrade .button { width: 100%; }

/* SHARE NOTICE */

.share-notice {
	border-radius: 3px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
	position: relative;
}

.share-notice.alignfull {
	border-radius: 0;
	border-width: 3px 0;
}

.share-notice-outline {
	border-style: solid;
	border-width: 3px;
}

.share-notice-full, .share-notice-full .share-notice-button,
.share-notice-full .share-notice-icon {
	border-color: #fff;
	color: #fff;
}

.share-notice-button { font-weight: bold; }

.share-notice-icon { line-height: 1; }

.share-notice-twitter.share-notice-outline,
.share-notice-twitter.share-notice-outline .button-outline {
	border-color: #1da1f2;
	color: #1da1f2;
}

.share-notice-twitter.share-notice-full,
.share-notice-twitter.share-notice-outline .button-full { background-color: #1da1f2; }

.share-notice-twitter.share-notice-outline .share-notice-icon,
.share-notice-twitter.share-notice-full .button-full { color: #1da1f2; }

.share-notice-facebook.share-notice-outline,
.share-notice-facebook.share-notice-outline .button-outline {
	border-color: #3b5998;
	color: #3b5998;
}

.share-notice-facebook.share-notice-full,
.share-notice-facebook.share-notice-outline .button-full { background-color: #3b5998; }

.share-notice-facebook.share-notice-outline .share-notice-icon,
.share-notice-facebook.share-notice-full .button-full { color: #3b5998; }

.share-notice-pinterest.share-notice-outline,
.share-notice-pinterest.share-notice-outline .button-outline {
	border-color: #bd081c;
	color: #bd081c;
}

.share-notice-pinterest.share-notice-full,
.share-notice-pinterest.share-notice-outline .button-full { background-color: #bd081c; }

.share-notice-pinterest.share-notice-outline .share-notice-icon,
.share-notice-pinterest.share-notice-full .button-full { color: #bd081c; }

.share-notice-linkedin.share-notice-outline,
.share-notice-linkedin.share-notice-outline .button-outline {
	border-color: #0077b5;
	color: #0077b5;
}

.share-notice-linkedin.share-notice-full,
.share-notice-linkedin.share-notice-outline .button-full { background-color: #0077b5; }

.share-notice-linkedin.share-notice-outline .share-notice-icon,
.share-notice-linkedin.share-notice-full .button-full { color: #0077b5; }

@media all and (min-width: 900px) {
	.box-lr {
		align-items: center;
		display: flex;
	}
	.box-lr .content-upgrade-text {
		margin-bottom: 0;
		width: 65%;
	}
	.box-lr .content-upgrade-action {
		padding-left: <?php echo $single; ?>px;
		width: 35%;
	}
	.share-notice-icon {
		float: right;
		font-size: 42px;
		position: relative;
	}
}

@media all and (max-width: 900px) {
	.note-box-list { margin-left: <?php echo $half; ?>px; }
	.share-notice-icon {
		display: block;
		font-size: 150px;
		line-height: 1;
		position: absolute;
			bottom: 0;
			right: <?php echo $half; ?>px;
		opacity: 0.2;
		transform: rotateZ(-4deg);
	}
}

/* SUBTITLES */

.entry-subtitle {
	display: block;
	font-size: 24px;
	line-height: 36px;
	margin-top: 13px;
}

/* BYLINE */

.byline {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: 0.85em;
	position: relative;
}

.byline a {
	border-bottom: 1px solid rgba(0, 0, 0, 0.15);
	color: <?php echo $colors['site']['text-sec']; ?>;
}

.byline-item { display: inline-block; }

.byline a.byline-icon, .byline .byline-icon a { border-bottom: 0; }

.byline-item .md-icon-twitter { color: #1da1f2; }

.byline-author .avatar {
	margin-right: <?php echo $small; ?>px;
	position: relative;
}

.byline-item:not(:last-child) { margin-right: <?php echo $third; ?>px; }

.byline .badge {
	font-size: inherit;
	padding: 4px 7px;
}

.byline-comments-label { display: none; }

/* CAPTION */

.wp-caption {
	height: auto;
	max-width: 100%;
}

.wp-caption-text {
	border-bottom: 1px solid #ccc;
	color: #444;
	font-size: 14px;
	font-style: italic;
	line-height: 22px;
	padding: 13px;
}

/* AUTHOR BOX */

.author-box { text-align: center; }

.author-title { font-weight: normal; }

.author-box .circle-icon { margin-right: <?php echo $small; ?>px; }

.author-box .author-link:not(:last-child) { margin-right: <?php echo $half; ?>px; }

.author-link.twitter .circle-icon {
	background-color: #1da1f2;
	color: #fff;
}

.author-link.twitter .md-icon-twitter { color: #fff; }

.author-link.twitter a {
	border-bottom-color: #1da1f2;
	color: #1da1f2;
}

/* PAGINATION  */

.pagination {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
	position: relative;
	text-align: center;
}

.pagination .page-numbers {
	background-color: #fff;
	border-color: <?php echo $colors['content']['border_color']; ?>;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	display: inline-block;
	margin-bottom: <?php echo $third; ?>px;
	margin-right: <?php echo $small; ?>px;
	padding: <?php echo $small; ?>px <?php echo $half; ?>px;
}

.page-numbers.current {
	cursor: default;
	font-weight: bold;
}

.pagination .page-numbers:hover { opacity: 0.8; }

.page-numbers.dots {
	background-color: transparent;
	box-shadow: none;
	border: 0;
	color: <?php echo $colors['site']['text-sec']; ?>;
	padding: 0;
}

/* POST NAV */

.post-nav {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

/* FEATURED IMAGE */

<?php
	$text_colors = array(
		'default' => array(
			'class' => '',
			'color' => ( ! empty( $content['featured_image']['styles']['text_color'] ) ? $colors['site']['headline'] : '#fff' ),
			'link' => ( ! empty( $content['featured_image']['styles']['text_color'] ) ? '#444' : '#eee' ),
			'border' => ( ! empty( $content['featured_image']['styles']['text_color'] ) ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.2)' )
		),
		'alt' => array(
			'class' => '.text-alt',
			'color' => ( empty( $content['featured_image']['styles']['text_color'] ) ? $colors['site']['headline'] : '#fff' ),
			'link' => ( empty( $content['featured_image']['styles']['text_color'] ) ? '#444' : '#eee' ),
			'border' => ( empty( $content['featured_image']['styles']['text_color'] ) ? 'rgba(0, 0, 0, 0.2)' : 'rgba(255, 255, 255, 0.2)' )
		)
	);

	$featured_image_text = ! empty( $content['featured_image']['styles']['text_color'] ) ? $colors['site']['headline'] : '#fff';
?>

.featured-image { position: relative; }

.featured-image a { border-bottom: 0; }

.featured-image img, .featured-image-tax img { width: 100%; }

.featured-image-tax.alignleft img, .featured-image-tax.alignright img {
	height: 150px;
	width: 150px;
}

.featured-image-cover .featured-image-caption {
	background-color: rgba(0, 0, 0, 0.8);
	color: #fff;
	font-size: 12px;
	line-height: 17px;
	padding: 7px 13px;
	position: absolute;
		bottom: 0;
		left: 0;
	text-align: center;
	z-index: 10;
}

.featured-image-cover {
	<?php echo ( ! empty( $content['featured_image']['cover']['url'] ) ? 'background-image: url(\'' . esc_url( $content['featured_image']['cover']['url'] ) . '\'); ': '' ); ?>
	background-position: center center;
	background-size: <?php echo ( empty( $content['featured_image']['styles']['repeat'] ) ? 'cover' : 'auto' ); ?>;
	position: relative;
}

.header.featured-image-cover { background-color: transparent; }

<?php foreach ( $text_colors as $text_class => $text_atts ) :
	$text_class = $text_atts['class'];
?>
	.featured-image-cover<?php echo $text_class; ?>,
	.featured-image-cover<?php echo $text_class; ?> .logo .site-title,
	.featured-image-cover<?php echo $text_class; ?> .menu > .menu-item > a,
	.featured-image-cover<?php echo $text_class; ?> .header-trigger,
	.featured-image-cover<?php echo $text_class; ?> .headline,
	.featured-image-cover<?php echo $text_class; ?> .headline a,
	.featured-image-cover<?php echo $text_class; ?> .byline {
		color: <?php echo esc_attr( $text_atts['color'] ); ?>;
	}

	.featured-image-cover<?php echo $text_class; ?> .menu > .menu-item > a:hover,
	.featured-image-cover<?php echo $text_class; ?> .tagline,
	.featured-image-cover<?php echo $text_class; ?> .text-sec,
	.featured-image-cover<?php echo $text_class; ?> a,
	.featured-image-cover<?php echo $text_class; ?> .entry-subtitle {
		color: <?php echo esc_attr( $text_atts['link'] ); ?>;
	}
	.featured-image-cover<?php echo $text_class; ?> a { border-bottom-color: <?php echo esc_attr( $text_atts['border'] ); ?>; }
<?php endforeach; ?>

@media all and (max-width: 900px) {
	.byline { font-size: 0.75em; }
	.featured-image-cover<?php echo $text_colors['default']['class']; ?> .sub-menu > .menu-item > a { color: <?php echo esc_attr( $text_colors['default']['color'] ); ?>; }
	.featured-image-cover<?php echo $text_colors['alt']['class']; ?> .sub-menu > .menu-item > a { color: <?php echo esc_attr( $text_colors['alt']['color'] ); ?>; }
}