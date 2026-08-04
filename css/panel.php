<style type="text/css">

/*------------------------------*\
	$PANEL
\*------------------------------*/

.panel {
	background-color: var(--md-panel-background);
	padding-block: var(--md-half);
	position: relative;
}

.panel-left .panel { border-inline-end: 1px solid var(--md-panel-border); }

.panel-right { overflow-x: hidden; }

.panel-right .panel { border-inline-start: 1px solid var(--md-panel-border); }

/* WIDGETS */

.panel .widget { padding: var(--md-half); }

.panel .widget > .accordion { margin-inline: calc(-1 * var(--md-half)); }

.panel .widget_md_accordion_widget:first-child { margin-block-start: calc(-1 * var(--md-single)); }

/* TRIGGERS */

.header .trigger-panel {
	border-radius: var(--md-border-radius);
	display: revert;
	line-height: 1;
	margin-inline: calc(-1 * var(--md-half) / 2);
	padding: var(--md-third);
	transition: var(--md-transition);
}

.trigger-panel:hover { background-color: rgba(0, 0, 0, 0.08); }

/* QUERIES */

@media (max-width: <?php echo $site_width; ?>px) {
	.panel {
		height: 100%;
		overflow-y: auto;
		position: fixed;
			inset-block-start: 0;
		transition: var(--md-transition);
		width: var(--md-width-panel);
		z-index: 100;
	}
	.panel-left .panel { inset-inline-start: calc(-1 * var(--md-width-panel)); }
	.panel-right .panel { inset-inline-end: calc(-1 * var(--md-width-panel)); }
	.toggle-panel[class*="from-"] { overflow: hidden; }
	.toggle-panel[class*="from-"] .trigger-panel { background-color: rgba(0, 0, 0, 0.08); }
	.panel-left.toggle-panel[class*="from-"] .panel { inset-inline-start: 0; }
	.panel-right.toggle-panel[class*="from-"] .panel { inset-inline-end: 0; }
	.panel-overlay {
		background-color: rgba(0, 0, 0, 0.5);
		height: 0;
		opacity: 0;
		position: fixed;
			inset: 0;
		transition: opacity var(--md-transition) ease-in-out;
		visibility: hidden;
		z-index: 95;
	}
	.toggle-panel[class*="from-"] .panel-overlay {
		height: 100%;
		opacity: 1;
		visibility: visible;
	}
}

@media (min-width: 600px) and (max-width: <?php echo $site_width; ?>px) {
	.admin-bar .panel { inset-block-start: var(--wp-admin--admin-bar--height); }
}

@media (min-width: 900px) and (max-width: <?php echo $site_width_wide; ?>px) {
	.toggle-panel .inner { padding-inline: var(--md-half); }
}

@media (min-width: <?php echo $site_width; ?>px) {
	body:not(.toggle-panel) .category.columns.full { gap: var(--md-mid); }
	.panel {
		display: none;
		padding-block: var(--md-single);
	}
	.format .panel { margin-block: calc(-1 * var(--md-single)); }
	.panel:after {
		background-color: inherit;
		content: '';
		position: absolute;
			inset-block: 0;
		width: 100vw;
	}
	.panel-left .panel {
		margin-inline-start: calc(-1 * var(--md-half));
		order: -1;
	}
	.panel-left .panel:after { inset-inline-start: -100vw; }
	.panel-right .panel {
		margin-inline-end: calc(-1 * var(--md-half));
		order: 3;
	}
	.panel-right .panel:after { inset-inline-end: -100vw; }
	.toggle-panel .panel { display: block; }
	.toggle-panel .trigger-panel { background-color: rgba(0, 0, 0, 0.08); }
	.toggle-panel .inner { max-width: var(--md-width-site-wide); }
	.toggle-panel .content-wrap {
		align-items: stretch;
		display: flex;
		justify-content: center;
		gap: var(--md-single);
	}
	.toggle-panel :is(.panel, .content, .sidebar) { min-width: 0; }
	.toggle-panel .compact .panel { flex: 0 1 <?php echo round( $panel_width / $site_width_wide * 100 ); ?>%; }
	.toggle-panel .compact .content { flex: 0 1 <?php echo round( $content_width / $site_width_wide * 100 ); ?>%; }
	.toggle-panel .compact .sidebar { flex: 0 1 <?php echo round( $sidebar_width / $site_width_wide * 100 ); ?>%; }
	.toggle-panel .expanded .content { flex: 1; }
	.toggle-panel .expanded .panel { flex: 0 0 var(--md-width-panel); }
	.panel-overlay { display: none; }
	.panel .widget:first-child { padding-block-start: 0; }
}
