<style type="text/css">

/*------------------------------*\
	$FONT_ICONS
\*------------------------------*/

@font-face {
	font-family: md-icon;
	font-display: swap;
	src: url('<?php echo md_font_icons_url(); ?>') format('woff2');
	font-style: normal;
	font-weight: 400;
}

[class*="md-icon"] { display: inline-block; }

[class*="md-icon"]:before {
	display: inline-block;
	font-family: md-icon;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 1;
	text-align: center;
	text-decoration: inherit;
	text-transform: none;
}

.list-check li:before,
#cancel-comment-reply-link:before,
.menu .trigger-icon:before,.menu .trigger-icon:after,
.breadcrumbs-home:before, .breadcrumbs li:not(:last-child):after {
	display: inline-block;
	font-family: md-icon;
	font-style: normal;
	font-weight: normal;
	line-height: 1;
}

.md-icon.icon-data:before { content: attr(data-md-icon); }
