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
	background-color: var(--md-content-main-background);
	padding-block: var(--md-single);
}

.content-wrap.builder > :where(:not([class*="wp-block"], .sidebar, .panel, .panel-overlay)) {
	margin-inline: auto;
	max-width: var(--md-width-site);
	padding-inline: clamp(0px, calc((100vw - var(--md-width-site)) / -2), var(--md-half));
	width: 100%;
}

.content-wrap.builder .alignwide { max-width: var(--md-width-alignwide); }

.main:has(> .content-wrap.builder > .alignfull:first-child) { padding-block-start: 0; }

.main:has(> .content-wrap.builder > .alignfull:last-child) { padding-block-end: 0; }

<?php foreach ( array( '.sidebar, .panel' => 'sidebar', '.footer' => 'footer' ) as $selector => $key ) {
	$aside_font_size = $this->fluid( $typography[$key]['font_size']['desktop'], $typography[$key]['font_size']['mobile'] ?? null );
	$aside_line_height = $this->fluid( $typography[$key]['line_height']['desktop'], $typography[$key]['line_height']['mobile'] ?? null );

	echo "$selector { font-size: $aside_font_size; line-height: $aside_line_height; }";
} ?>

/* SIDEBAR / PANEL */

.sidebar {
	color: var(--md-sidebar-text);
	position: relative;
}

.sidebar { background-color: var(--md-sidebar-background); }

.sidebar a { color: var(--md-sidebar-links); }

.panel {
	background-color: var(--md-panel-background);
	border-color: var(--md-panel-border);
	color: var(--md-panel-text);
}

.panel a { color: var(--md-panel-links); }

.sidebar .widget :is(ul, ol), .footer .widget :is(ul, ol) {
	list-style: none;
	margin-inline-start: 0;
}

.panel .widget > :is(ul, ol) { padding-inline-start: var(--md-single); }

.sidebar :is(.widget-title, .wp-block-heading) { color: var(--md-sidebar-title); }

.sidebar :is(.widget-title a, .wp-block-heading a) { color: var(--md-sidebar-title-links); }

/* FOOTER */

.footer {
	background-color: var(--md-footer-background);
	color: var(--md-footer-text);
	position: relative;
	z-index: 88;
}

.is-border-style .footer { border-block-start: 1px solid var(--md-footer-border); }

.footer a { color: var(--md-footer-links); }

.footer :is(.widget-title, .wp-block-heading) { color: var(--md-footer-title); }

.footer :is(.widget-title a, .wp-block-heading a) { color: var(--md-footer-title-links); }

.footer > .inner > .columns,
.footer > .columns { padding-block: var(--md-mid); }

.footer .list li:not(:last-child) { border-block-end-color: var(--md-footer-border); }

.footer .list a { display: block; }

.footer-copy {
	border-block-start: 1px solid var(--md-footer-border);
	padding-block: var(--md-single);
	text-align: center;
}

.compact .content:not(:last-child) { margin-block-end: var(--md-single); }

/* QUERIES */

@media (min-width: 900px) {
	.compact .content-wrap {
		display: flex;
		align-items: stretch;
		gap: var(--md-mid);
	}
	.compact .content-wrap:not(:last-child) { margin-block-end: 0; }
	.compact .content:not(:last-child) { margin-block-end: 0; }
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
