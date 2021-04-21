<style type="text/css">

/*------------------------------*\
	$WIDGETS
\*------------------------------*/

.sidebar .widget:not(:last-child), .footer .widget:not(:last-child) { margin-bottom: <?php echo $mid; ?>px; }

.box-style, .box-style-list ul, .tagcloud, #wp-calendar { margin-left: 0; }

.style-default .box-style,
.style-default .box-style-list ul,
.style-default .tagcloud,
.style-default #wp-calendar {
	background-color: #fff;
	border-radius: 2px;
	box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
	color: #333;
}

.style-default .box-style-list ul { padding: <?php echo $single; ?>px; }

/* EMAIL FORM */

.widget_md_email .sidebar-title { color: inherit; }

/* CONTENT SPOTLIGHT */

.content-spotlight, .widget_md_content_spotlight a.content-spotlight {
	background-color: #222;
	background-position: center center;
	background-repeat: no-repeat;
	border-bottom-width: 0;
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
	border-radius: 0 0 3px 3px;
	text-align: center;
	width: 100%;
}

#wp-calendar td { padding: 9px <?php echo $half; ?>px; }

#wp-calendar thead th {
	padding-bottom: 9px;
	padding-top: 9px;
}

#wp-calendar thead th { background-color: #f9f9f9; }

#wp-calendar tbody a { font-weight: bold; }

#wp-calendar thead tr, #wp-calendar tbody td { border-bottom: 1px solid #ddd; }

#wp-calendar caption {
	background-color: #ae2525;
	border-radius: 2px 2px 0 0;
	color: #fff;
	padding: <?php echo $half; ?>px;
}

/* ACCORDION */

.accordion {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-top: 4px solid <?php echo $colors['site']['secondary']; ?>;
	border-radius: 3px;
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
	border-bottom: 0;
	color: <?php echo $colors['site']['links']; ?>;
}

.sidebar .accordion a:hover { border-bottom: 0; }