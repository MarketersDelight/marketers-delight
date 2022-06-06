<style type="text/css">

/*------------------------------*\
	$SHARE
\*------------------------------*/

.share .share-button, .share a.share-button {
	border-bottom: 0;
	display: inline-block;
	font-size: 20px;
	line-height: 1;
	text-align: center;
    transition: 0.3s;
}

.share .share-button:hover {
	transform: scale(1.08);
	z-index: 95;
}

.share .share-like { color: #1e1e1e !important; }

.share-count, .share-comment-count { font-size: 0.8em; }

.share-button.liked {
	color: #F12020 !important;
	cursor: default;
	font-weight: bold;
}

.share-button.liked .md-icon-heart-empty:before { content: '\e821'; }

.share-minimal {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-top: 1px solid <?php echo $colors['content']['border_color']; ?>;
}

.share-minimal:first-child, .style-default .content-text + .share-minimal { border-top: 0; }

.style-minimal .share-minimal {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.style-minimal .share-minimal:first-child { padding-top: 0; }

.style-default .post-box .share-minimal { padding: <?php echo $half; ?>px; }

.share-minimal .share-comments, .share-minimal .share-like {
	float: right;
	margin-left: <?php echo $half; ?>px;
}

.share-minimal .share-button.share-like .share-icon {
	background-color: transparent;
	height: auto;
	width: auto;
}

.share-minimal .share-comments .share-icon { color: <?php echo $colors['site']['text-sec']; ?> }

.share-minimal .share-button { line-height: 33px; }

.share-minimal .share-button .share-icon {
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 50%;
	height: 36px;
	padding-top: 6px;
	width: 36px;
}

.share-minimal .share-button:not(:last-child) { margin-right: 7px; }

.share-bold {
	font-size: 0;
	overflow: hidden;
	text-align: center;
	text-transform: uppercase;
}

.share-bold .share-button {
	color: #fff;
	padding-bottom: 13px;
	padding-top: 13px;
	vertical-align: bottom;
}

.share-bold .md-icon-twitter:before { color: #fff; }

.share-sticky {
	display: block;
	position: fixed;
	-o-transition: 0.3s;
	-ms-transition: 0.3s;
	-moz-transition: 0.3s;
	-webkit-transition: 0.3s;
	transition: 0.3s;
}

.share-sticky.side-bottom {
	bottom: 0;
	width: 100%;
}

@media all and (min-width: <?php echo $site_width; ?>px) {
	.share-sticky { z-index: 160; }
	.share-sticky .share-icon { display: block; }
	.share-sticky.side-left {
		left: 0;
		top: 40%;
	}
	.share-sticky.side-right {
		top: 40%;
		right: 0;
	}
	.share-sticky.side-v, .share-sticky.side-v .share { width: 50px; }
	.share-sticky.inline {
		position: absolute;
		left: -50px;
		top: <?php echo $single; ?>px;
	}
	.content-sidebar.sidebar-left .share-sticky.inline {
		left: inherit;
		right: -50px;
	}
	.style-minimal.content-sidebar.sidebar-left .share-sticky.inline {
		top: 0;
		right: -<?php echo $triple; ?>px;
	}
	.style-minimal .share-sticky.inline {
		left: -<?php echo $triple; ?>px;
		top: 0;
	}
	.share-sticky.inline .share {
		position: static;
		top: <?php echo $single; ?>px;
	}
	.share-sticky.side-v .share-button {
		display: block;
		width: 100% !important;
	}
	.share-sticky.inline.sticky { top: 0; }
	.share-sticky.inline.sticky .share {
		position: fixed;
			top: <?php echo $single; ?>px;
	}
	.admin-bar .share-sticky.inline.sticky .share { top: <?php echo $single + $admin_bar_height; ?>px; }
	/* inline + right screen */
	.share-sticky.inline .share-button, .share-sticky.side-right .share-button { box-shadow: inset -3px 0 2px rgba(0, 0, 0, 0.15); }
	.share-sticky.side-right .share { border-radius: 3px 0 0 3px; }
	.share-sticky.inline .share-button:first-child, .share-sticky.side-right .share-button:first-child { border-radius: 3px 0 0; }
	.share-sticky.inline .share-button:last-child, .share-sticky.side-right .share-button:last-child { border-radius: 0 0 0 3px; }
	/* left screen */
	.share-sticky.side-left .share { border-radius: 0 3px 3px 0; }
	.content-sidebar.sidebar-left .share-sticky.inline .share-button, .share-sticky.side-left .share-button { box-shadow: inset 3px 0 2px rgba(0, 0, 0, 0.15); }
	.content-sidebar.sidebar-left .share-sticky.inline .share-button:first-child, .share-sticky.side-left .share-button:first-child { border-radius: 0 3px 0 0; }
	.content-sidebar.sidebar-left .share-sticky.inline .share-button:last-child, .share-sticky.side-left .share-button:last-child { border-radius: 0 0 3px 0; }
}

@media all and (min-width: 900px) {
	.content-sidebar .content-headline + .share { margin-top: <?php echo $single;?>px; }
	.content-full .content-headline + .share { margin-top: <?php echo $double;?>px; }
	.content-full .featured-image-cover + .share { margin-top: 0; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.style-minimal .post-box .share-minimal {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
		padding-top: <?php echo $half; ?>px;
	}
	.share-bold .share-count, .share-bold .share-comment-count {
		font-size: 13px;
		line-height: 1;
		position: relative;
		top: -3px;
	}
	.share-bold .share-icon { font-size: 18px; }
	.share-sticky.side-v, .share-sticky.side-v .share { width: 100%; }
	.share-sticky.side-v {
		bottom: 0;
		left: 0;
		top: auto;
	 }
 }

@media all and (max-width: 900px) {
	.style-minimal .content .share {
		margin-bottom: <?php echo $half; ?>px;
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
	.style-minimal .content-headline + .share { margin-bottom: <?php echo $single; ?>px; }
	.content-headline + .share { margin-top: <?php echo $single;?>px; }
	.admin-bar .share-sticky.inline.sticky .share { top: <?php echo $single + $admin_bar_height_mobile; ?>px; }
}