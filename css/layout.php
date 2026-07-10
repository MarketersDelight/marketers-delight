<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.inner {
	margin-inline: auto;
	max-width: var(--md-width-site);
	position: relative;
	width: 100%;
}

.main {
	background-color: var(--md-color-surface);
	padding-block: var(--md-single);
}

/* SIDEBAR */

.sidebar, .panel {
	color: var(--md-sidebar-text);
	font-size: <?php echo $typography['sidebar']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['sidebar']['line_height']['desktop']; ?>px;
}

.sidebar { background-color: var(--md-sidebar); }

.sidebar a, .panel a { color: var(--md-sidebar-links); }

.sidebar .widget :is(ul, ol),
.footer .widget :is(ul, ol) {
	list-style: none;
	margin-inline-start: 0;
}

:is(.sidebar, .panel) :is(.widget-title, .wp-block-heading) { color: var(--md-sidebar-title); }

:is(.sidebar, .panel) :is(.widget-title a, .wp-block-heading a) { color: var(--md-sidebar-title-links); }

/* FOOTER */

.footer {
	background-color: var(--md-footer);
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

/* QUERIES */

@media (min-width: 900px) {
	.compact .content-wrap {
		display: flex;
		align-items: stretch;
		gap: var(--md-mid);
	}
	.compact .content {
		flex: 0 1 <?php echo round( $content_width / $site_width * 100 ); ?>%;
		min-width: 0;
	}
	.compact .sidebar {
		flex: 0 1 <?php echo round( $sidebar_width / $site_width * 100 ); ?>%;
		min-width: 0;
	}
	.sidebar-left .sidebar { order: 1; }
	.sidebar-left .content { order: 2; }
	.compact .content-wrap:not(:last-child) { margin-block-end: 0; }
}

@media (max-width: <?php echo $site_width; ?>px) {
	.inner { padding-inline: var(--md-half); }
}

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