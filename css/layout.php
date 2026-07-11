<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.inner {
	margin-inline: auto;
	max-width: var(--md-width-site);
	padding-inline: clamp(0px, calc((100vw - var(--md-width-site)) / -2), var(--md-half));
	position: relative;
	width: 100%;
}

.main {
	background-color: var(--md-color-surface);
	padding-block: var(--md-single);
}

<?php foreach ( array( '.sidebar, .panel' => 'sidebar', '.footer' => 'footer' ) as $selector => $key ) {
	$aside_font_size = $this->fluid( $typography[$key]['font_size']['desktop'], $typography[$key]['font_size']['mobile'] ?? null );
	$aside_line_height = $this->fluid( $typography[$key]['line_height']['desktop'], $typography[$key]['line_height']['mobile'] ?? null );

	echo "$selector { font-size: $aside_font_size; line-height: $aside_line_height; }";
} ?>

/* SIDEBAR / PANEL */

.sidebar, .panel { color: var(--md-sidebar-text); }

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
	.compact .content-wrap:not(:last-child) { margin-block-end: 0; }
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
}