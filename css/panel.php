<style type="text/css">

/*------------------------------*\
	$PANEL
\*------------------------------*/

.panel {
	background-color: <?php echo $colors['site']['accent']; ?>;
	padding-block: <?php echo $half; ?>px;
	position: relative;
}

.panel-left .panel { border-inline-end: 1px solid <?php echo $colors['site']['tertiary']; ?>; }
.panel-right { overflow-x: hidden; }
.panel-right .panel { border-inline-start: 1px solid <?php echo $colors['site']['tertiary']; ?>; }

.panel .widget { padding: <?php echo $half; ?>px; }

.panel .widget:first-child { padding-block-start: 0; }

/* TRIGGERS */

.header .trigger-panel {
	border-radius: 6px;
	display: revert;
	line-height: 1;
	margin-inline: -<?php echo round( $half / 2 ); ?>px;
	padding: <?php echo $third; ?>px;
	transition: 0.3s;
}

.trigger-panel:hover { background-color: rgba(0, 0, 0, 0.08); }

/* QUERIES */

@media (max-width: <?php echo $site_width; ?>px) {
	.panel {
		height: 100%;
		overflow-y: auto;
		position: fixed;
			top: 0;
		transition: 0.3s;
		width: <?php echo $panel_width; ?>px;
		z-index: 100;
	}
	.panel-left .panel { left: -<?php echo $panel_width; ?>px; }
	.panel-right .panel { right: -<?php echo $panel_width; ?>px; }
	.admin-bar .panel { top: var(--wp-admin--admin-bar--height); }
	.toggle-panel[class*="from-"] { overflow: hidden; }
	.toggle-panel[class*="from-"] .trigger-panel { background-color: rgba(0, 0, 0, 0.08); }
	.panel-left.toggle-panel[class*="from-"] .panel { left: 0; }
	.panel-right.toggle-panel[class*="from-"] .panel { right: 0; }
	.panel-overlay {
		background-color: rgba(0, 0, 0, 0.5);
		height: 0;
		opacity: 0;
		position: fixed;
			inset: 0;
		transition: opacity 0.3s ease-in-out;
		visibility: hidden;
		z-index: 99;
	}
	.toggle-panel[class*="from-"] .panel-overlay {
		height: 100%;
		opacity: 1;
		visibility: visible;
	}
}

@media (min-width: 900px) and (max-width: <?php echo $site_width_wide; ?>px) {
	.toggle-panel .inner { padding-inline: <?php echo $half; ?>px; }
}

@media (min-width: <?php echo $site_width; ?>px) {
	.panel {
		display: none;
		padding-block: <?php echo $single; ?>px;
	}
	.format .panel { margin-block: -<?php echo $single; ?>px; }
	.panel:after {
		background-color: inherit;
		content: '';
		position: absolute;
			bottom: 0;
			top: 0;
		width: 100vw;
	}
	.panel-left .panel {
		margin-inline-start: -<?php echo $half; ?>px;
		order: -1;
	}
	.panel-left .panel:after { left: -100vw; }
	.panel-right .panel {
		margin-inline-end: -<?php echo $half; ?>px;
		order: 3;
	}
	.panel-right .panel:after { right: -100vw; }
	.toggle-panel .panel { display: block; }
	.toggle-panel .trigger-panel { background-color: rgba(0, 0, 0, 0.08); }
	.toggle-panel .inner { max-width: <?php echo $site_width_wide; ?>px; }
	.toggle-panel .content-wrap {
		align-items: stretch;
		display: flex;
		justify-content: center;
		gap: <?php echo $single; ?>px;
	}
	.toggle-panel :is(.panel, .content, .sidebar) { min-width: 0; }
	.toggle-panel .compact .panel { flex: 0 1 <?php echo round( $panel_width / $site_width_wide * 100 ); ?>%; }
	.toggle-panel .compact .content { flex: 0 1 <?php echo round( $content_width / $site_width_wide * 100 ); ?>%; }
	.toggle-panel .compact .sidebar { flex: 0 1 <?php echo round( $sidebar_width / $site_width_wide * 100 ); ?>%; }
/*
	.sidebar-left.toggle-panel .content-wrap .sidebar { order: 1; }
	.sidebar-left.toggle-panel .content-wrap .content { order: 2; }
*/
	.toggle-panel .expanded .content { flex: 1; }
	.toggle-panel .expanded .panel { flex: 0 0 <?php echo $panel_width; ?>px; }
	.panel-overlay { display: none; }
}