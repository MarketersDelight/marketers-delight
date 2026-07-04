<style type="text/css">

/*------------------------------*\
	$WIDGETS
\*------------------------------*/

.sidebar, .panel {
	color: var(--md-sidebar-text);
	font-size: <?php echo $typography['sidebar']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['sidebar']['line_height']['desktop']; ?>px;
}

.format .widget:not(:last-child) { margin-block-end: var(--md-single); }

.sidebar .widget :is(ul, ol), .footer .widget :is(ul, ol) {
	list-style: none;
	margin-inline-start: 0;
}

.wp-block-latest-comments__comment { line-height: inherit; }

/* SIDEBAR */

.sidebar { background-color: var(--md-sidebar-background); }

.sidebar a, .panel a { color: var(--md-sidebar-links); }

:is(.sidebar, .panel) :is(.widget-title, .wp-block-heading) { color: var(--md-sidebar-title); }

:is(.sidebar, .panel) :is(.widget-title a, .wp-block-heading a) { color: var(--md-sidebar-title-links); }

/* FOOTER */

.footer {
	background-color: var(--md-footer-background);
	color: var(--md-footer-text);
	font-size: <?php echo $typography['footer']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['footer']['line_height']['desktop']; ?>px;
	position: relative;
	z-index: 88;
}

.footer a { color: var(--md-footer-links); }

.footer :is(.widget-title, .wp-block-heading) { color: var(--md-footer-title); }

.footer :is(.widget-title a, .wp-block-heading a) { color: var(--md-footer-title-links); }

.footer .columns { padding-block: var(--md-mid); }

.footer .list li:not(:last-child) { border-block-end-color: var(--md-footer-border); }

.footer .list a { display: block; }

.footer-copy {
	border-block-start: 1px solid var(--md-footer-border);
	padding-block: var(--md-single);
	text-align: center;
}

/* MENU */

.format .widget_nav_menu :is(.menu, .sub-menu) {
	margin-inline-start: 0;
	margin-block-start: 0;
}

.widget_nav_menu .sub-menu { display: none !important /*temporary*/; }

.widget_nav_menu .menu-item {
	display: block;
	margin-block-end: 0;
}

.widget_nav_menu .menu-item a {
	padding: var(--md-third) 0;
	width: 100%;
}

.widget_nav_menu .menu-item:not(:last-child) { border-block-end: 1px solid var(--md-color-content-border); }

/* SEARCH */

.wp-block-search__input {
	align-self: normal;
	margin-inline-end: 2%;
}

.format .wp-block-search .wp-block-search__input { margin-block-end: 0; }

.wp-block-search__button { align-self: flex-start; }

/* RSS */

.rsswidget img {
	margin-inline-end: 4px;
	margin-block-start: 9px;
}

.rss-date, .widget_rss cite {
	display: block;
	margin-block-start: 13px;
}

.rss-date { margin-block-end: var(--md-half); }

.widget_rss cite:before { content: "\2014\00a0"; }

/* CALENDAR */

#wp-calendar {
	border-collapse: collapse;
	font-size: var(--md-font-size-sm);
	line-height: 1;
	margin-block-end: var(--md-small);
	margin-block-start: 0;
	text-align: center;
	width: 100%;
}

.style-default #wp-calendar {
	background-color: var(--md-color-content-box);
	border-radius: var(--md-border-radius);
	box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
	color: var(--md-color-text);
}

#wp-calendar td { padding: var(--md-third); }

#wp-calendar thead th { padding-block: var(--md-third); }

#wp-calendar thead th { background-color: #f9f9f9; }

#wp-calendar tbody a { font-weight: var(--md-bold); }

#wp-calendar thead tr, #wp-calendar tbody td { border-block-end: 1px solid var(--md-color-content-border); }

#wp-calendar caption {
	background-color: var(--md-color-primary);
	border-radius: var(--md-border-radius) var(--md-border-radius) 0 0;
	color: var(--md-color-white);
	padding: var(--md-half);
}

/* QUERIES */

<?php foreach ( $queries as $w => $d ) {

echo "@media (max-width: {$w}px) {\n";

foreach ( array( 'sidebar', 'footer' ) as $aside )
	if ( ! empty( $typography[$aside]['font_size'][$d] ) || ! empty( $typography[$aside]['line_height'][$d] ) ) {
		echo ".{$aside} { ".
			( ! empty( $typography[$aside]['font_size'][$d] ) ?
				'font-size: ' . $typography[$aside]['font_size'][$d] . 'px; '
			: '' ).
			( ! empty( $typography[$aside]['line_height'][$d] ) ?
				'line-height: ' . $typography[$aside]['line_height'][$d] . 'px; '
			: '' ) . '}';
	}

echo "}\n";

} ?>
