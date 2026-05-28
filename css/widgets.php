<style type="text/css">

/*------------------------------*\
	$WIDGETS
\*------------------------------*/

.sidebar, .panel {
	color: <?php echo $colors['sidebar']['text']; ?>;
	font-size: <?php echo $typography['sidebar']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['sidebar']['line_height']['desktop']; ?>px;
}

.format .widget:not(:last-child) { margin-block-end: <?php echo $single; ?>px; }

.sidebar .widget :is(ul, ol), .footer .widget :is(ul, ol) {
	list-style: none;
	margin-inline-start: 0;
}

.wp-block-latest-comments__comment { line-height: inherit; }

/* SIDEBAR */

<?php if ( ! empty( $colors['sidebar']['bg_color'] ) ) : ?>
.sidebar { background-color: <?php echo $colors['sidebar']['bg_color']; ?>; }
<?php endif; ?>

.sidebar a, .panel a { color: <?php echo $colors['sidebar']['links']; ?>; }

:is(.sidebar, .panel) :is(.widget-title, .wp-block-heading) { color: <?php echo $colors['sidebar']['title']; ?>; }

:is(.sidebar, .panel) :is(.widget-title a, .wp-block-heading a) { color: <?php echo $colors['sidebar']['title_link']; ?>; }

/* FOOTER */

.footer {
	<?php if ( $colors['footer']['bg_color'] !== $colors['site']['bg_color'] ) : ?>
	background-color: <?php echo $colors['footer']['bg_color']; ?>;
	<?php endif; ?>
	color: <?php echo $colors['footer']['text']; ?>;
	font-size: <?php echo $typography['footer']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['footer']['line_height']['desktop']; ?>px;
	position: relative;
	z-index: 90;
}

.footer a { color: <?php echo $colors['footer']['links']; ?>; }

.footer :is(.widget-title, .wp-block-heading) { color: <?php echo $colors['footer']['title']; ?>; }

.footer :is(.widget-title a, .wp-block-heading a) { color: <?php echo $colors['footer']['title_link']; ?>; }

.footer .columns { padding-block: <?php echo $mid; ?>px; }

.footer .list li:not(:last-child) { border-block-end-color: <?php echo $colors['footer']['border_color']; ?>; }

.footer .list a { display: block; }

.footer-copy {
	border-block-start: 1px solid <?php echo $colors['footer']['border_color']; ?>;
	padding-block: <?php echo $single; ?>px;
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
	padding: <?php echo $third; ?>px 0;
	width: 100%;
}

.widget_nav_menu .menu-item:not(:last-child) { border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>; }

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

.rss-date { margin-block-end: <?php echo $half; ?>px; }

.widget_rss cite:before { content: "\2014\00a0"; }

/* CALENDAR */

#wp-calendar {
	border-collapse: collapse;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: 1;
	margin-block-end: <?php echo $small; ?>px;
	margin-block-start: 0;
	text-align: center;
	width: 100%;
}

.style-default #wp-calendar {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
	color: <?php echo $colors['site']['text-main']; ?>;
}

#wp-calendar td { padding: <?php echo $third; ?>px; }

#wp-calendar thead th { padding-block: <?php echo $third; ?>px; }

#wp-calendar thead th { background-color: #f9f9f9; }

#wp-calendar tbody a { font-weight: <?php echo $bold; ?>; }

#wp-calendar thead tr, #wp-calendar tbody td { border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>; }

#wp-calendar caption {
	background-color: <?php echo $colors['site']['primary']; ?>;
	border-radius: 2px 2px 0 0;
	color: #fff;
	padding: <?php echo $half; ?>px;
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