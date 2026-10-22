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
	--md-text: var(--md-sidebar-text);
	--md-links: var(--md-sidebar-links);
	--md-headlines: var(--md-sidebar-title);
	--md-headline-links: var(--md-sidebar-title-links);
	background-color: var(--md-sidebar-background);
	color: var(--md-text);
	position: relative;
}

.panel {
	--md-text: var(--md-panel-text);
	--md-links: var(--md-panel-links);
	--md-headlines: var(--md-panel-text);
	--md-headline-links: var(--md-panel-text);
	--md-border: var(--md-panel-border);
	background-color: var(--md-panel-background);
	border-color: var(--md-border);
	color: var(--md-text);
}

:is(.sidebar, .panel, .footer) a { color: var(--md-links); }

.sidebar .widget :is(ul, ol), .footer .widget :is(ul, ol) {
	list-style: none;
	margin-inline-start: 0;
}

.panel .widget > :is(ul, ol) { padding-inline-start: var(--md-single); }

.sidebar :is(.widget-title, .wp-block-heading) { color: var(--md-headlines); }

.sidebar :is(.widget-title a, .wp-block-heading a) { color: var(--md-headline-links); }

/* FOOTER */

.footer {
	--md-text: var(--md-footer-text);
	--md-links: var(--md-footer-links);
	--md-headlines: var(--md-footer-title);
	--md-headline-links: var(--md-footer-title-links);
	--md-border: var(--md-footer-border);
	background-color: var(--md-footer-background);
	color: var(--md-text);
	position: relative;
	z-index: 88;
}

.is-border-style .footer { border-block-start: 1px solid var(--md-border); }

.footer :is(.widget-title, .wp-block-heading) { color: var(--md-headlines); }

.footer :is(.widget-title a, .wp-block-heading a) { color: var(--md-headline-links); }

.footer > .inner > .columns,
.footer > .columns { padding-block: var(--md-mid); }

.footer .list li:not(:last-child) { border-block-end-color: var(--md-border); }

.footer .list a { display: block; }

.footer-copy {
	border-block-start: 1px solid var(--md-border);
	padding-block: var(--md-single);
	text-align: center;
}

.compact .content:not(:last-child) { margin-block-end: var(--md-single); }

/* QUERIES */

@media (min-width: 900px) {
	.compact .content-wrap {
		display: flex;
		align-items: stretch;
		gap: var(--md-single);
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
