<style type="text/css">

/*------------------------------*\
	$PANEL
\*------------------------------*/

.panel {
	background-color: <?php echo $colors['site']['accent']; ?>;
	border-inline-end: 1px solid <?php echo $colors['site']['tertiary']; ?>;
	padding-block: <?php echo $half; ?>px;
	position: relative;
}

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

@media (max-width: <?php echo $site_width + $triple; ?>px) {
	.panel {
		height: 100%;
		overflow-y: auto;
		position: fixed;
			left: -<?php echo $panel_width; ?>px;
			top: 0;
		transition: 0.3s;
		width: <?php echo $panel_width; ?>px;
		z-index: 100;
	}
	.panel-overlay {
		background-color: rgba(0, 0, 0, 0.5);
		height: 0;
		inset: 0;
		opacity: 0;
		position: fixed;
		transition: opacity 0.3s ease-in-out;
		visibility: hidden;
		z-index: 99;
	}
	.toggle-panel[class*="from-"] .trigger-panel { background-color: rgba(0, 0, 0, 0.08); }
	.toggle-panel[class*="from-"] { overflow: hidden; }
	.toggle-panel[class*="from-"] .panel { left: 0; }
	.toggle-panel[class*="from-"] .panel-overlay {
		height: 100%;
		opacity: 1;
		visibility: visible;
	}
	.admin-bar .panel { top: var(--wp-admin--admin-bar--height); }
}

@media (min-width: 900px) and (max-width: <?php echo $site_width_wide; ?>px) {
	.toggle-panel .inner { padding-inline: <?php echo $half; ?>px; }
}

@media (min-width: <?php echo $site_width + $triple; ?>px) {
	.toggle-panel .trigger-panel { background-color: rgba(0, 0, 0, 0.08); }
	.toggle-panel .inner { max-width: <?php echo $site_width_wide + $double; ?>px; }
	.toggle-panel .content-wrap {
		justify-content: center;
		gap: <?php echo $single; ?>px;
		max-width: <?php echo $site_width_wide + $double; ?>px;
	}
	.toggle-panel .compact .content-wrap { grid-template-columns: minmax(0, <?php echo $panel_width; ?>px) <?php echo $content_width; ?>px minmax(0, <?php echo $sidebar_width; ?>px); }
	.toggle-panel .expanded {
		display: grid;
		gap: <?php echo $single; ?>px;
		grid-template-columns: <?php echo $panel_width; ?>px minmax(0, <?php echo $site_width; ?>px);
		justify-content: center;
	}
	.panel {
		display: none;
		order: -1;
		padding-block: <?php echo $single; ?>px;
	}
	.format .panel {
		margin-block: -<?php echo $single; ?>px;
		margin-inline-start: -<?php echo $half; ?>px;
	}
	.panel:after {
		background-color: inherit;
		content: '';
		position: absolute;
			bottom: 0;
			left: -100vw;
			top: 0;
			right: 0;
		width: 100vw;
	}
	.toggle-panel .panel { display: block; }
	.panel-overlay { display: none; }
}