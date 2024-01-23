<style type="text/css">

/*------------------------------*\
	$WIDGETS
\*------------------------------*/

.format .widget:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.format .widget ul, .format .widget ol {
	list-style: none;
	margin-left: 0;
}

.wp-block-latest-comments__comment { line-height: inherit; }

/* MENU */

.widget_nav_menu .menu {
	align-items: inherit;
	flex-direction: column;
	margin-left: 0;
}

.widget_nav_menu .menu-item { margin-bottom: 0; }

.widget_nav_menu .menu-item:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.footer .widget_nav_menu .menu-item:not(:last-child) { border-bottom: 1px solid <?php echo $colors['footer']['border_color']; ?>; }

/* SEARCH */

.wp-block-search__input {
	align-self: normal;
	margin-right: 2%;
}

.format .wp-block-search .wp-block-search__input { margin-bottom: 0; }

.wp-block-search__button { align-self: flex-start; }

/* CONTENT SPOTLIGHT */

.content-spotlight, .widget_md_content_spotlight a.content-spotlight {
	background-color: #222;
	background-position: center center;
	background-repeat: no-repeat;
	color: #fff;
	display: block;
	text-align: center;
}

.content-spotlight small { text-transform: uppercase; }

.content-spotlight .content-spotlight-title { font-weight: bold; }

/* RSS */

.rsswidget img {
	margin-right: 4px;
	margin-top: 9px;
}

.rss-date, .widget_rss cite {
	display: block;
	margin-top: 13px;
}

.rss-date { margin-bottom: <?php echo $half; ?>px; }

.widget_rss cite:before { content: "\2014\00a0"; }

/* CALENDAR */

#wp-calendar {
	border-collapse: collapse;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: 1;
	margin-bottom: <?php echo $small; ?>px;
	margin-left: 0;
	text-align: center;
	width: 100%;
}

.style-default #wp-calendar {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
	color: <?php echo $colors['site']['text']; ?>;
}

#wp-calendar td { padding: <?php echo $third; ?>px; }

#wp-calendar thead th {
	padding-bottom: <?php echo $third; ?>px;
	padding-top: <?php echo $third; ?>px;
}

#wp-calendar thead th { background-color: #f9f9f9; }

#wp-calendar tbody a { font-weight: <?php echo $bold; ?>; }

#wp-calendar thead tr, #wp-calendar tbody td { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

#wp-calendar caption {
	background-color: <?php echo $colors['site']['primary']; ?>;
	border-radius: 2px 2px 0 0;
	color: #fff;
	padding: <?php echo $half; ?>px;
}

/* ACCORDION */

.accordion {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-top: 4px solid <?php echo $colors['site']['secondary']; ?>;
	border-radius: 5px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.accordion .current { font-weight: <?php echo $bold; ?>; }

.accordion-group:not(:last-child) { border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>; }

.accordion-group.active .accordion-content { display: block; }

.accordion-title {
	color: <?php echo $colors['site']['text']; ?>;
	cursor: pointer;
	font-weight: <?php echo $bold; ?>;
	padding: <?php echo $half; ?>px;
	position: relative;
}

.accordion-title:after {
	content: '\e80e';
	display: inline-block;
	font-family: 'md-icon';
	position: absolute;
		top: <?php echo $half; ?>px;;
		right: <?php echo $half; ?>px;
}

.accordion-group.active .accordion-title:after { content: '\e817'; }

.accordion-content {
	display: none;
	padding-bottom: <?php echo $half; ?>px;
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

.accordion .list {
	font-size: 0.9em;
	margin-left: 0;
}

.sidebar .accordion a {
	color: <?php echo $colors['site']['links']; ?>;
	text-decoration: none;
}