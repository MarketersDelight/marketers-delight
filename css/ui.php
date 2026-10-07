<style type="text/css">

.overlay {
	background-color: var(--md-page-cover-overlay);
	content: '';
	display: block;
	inset: 0;
	position: absolute;
}

.clickable:after, .post-nav a:after {
	content: '';
	position: absolute;
		inset: 0;
	z-index: 50;
}

.clickout {
	position: relative;
	z-index: 60;
}

.close,
.close.circle-icon {
	background-color: transparent;
	color: var(--md-color-danger);
	cursor: pointer;
	font-size: var(--md-h6);
}

.close:hover { background-color: rgba(0, 0, 0, 0.2); }

/* SCROLLER */

.scroller-nav {
	align-items: center;
	display: flex;
	min-width: 0;
}

:where(.scroller-nav:not(:last-child)) { margin-block-end: var(--md-single); }

.scroller-arrow {
	align-self: stretch;
	appearance: none;
	background: transparent;
	border: 0;
	border-radius: 0;
	box-shadow: none;
	color: var(--md-text-muted);
	cursor: pointer;
	flex-shrink: 0;
	font-size: var(--md-h5);
	padding: 0 var(--md-third);
	transform: none;
	transition: color 0.2s;
}

.scroller-arrow:hover {
	background: transparent;
	box-shadow: none;
	color: var(--md-links);
	transform: none;
}

.scroller-list {
	display: flex;
	flex: 1;
	flex-wrap: nowrap;
	gap: var(--md-third);
	min-width: 0;
	overflow-x: auto;
	scroll-behavior: smooth;
	scrollbar-width: none;
	-webkit-overflow-scrolling: touch;
}

.scroller-list::-webkit-scrollbar { display: none; }

.scroller-list > * { flex-shrink: 0; }

.scroller-nav > .scroller-list {
	margin-block: calc(-1 * var(--md-third));
	padding-block: var(--md-third);
}

.scroller-arrow.arrow-hidden { display: none; }

/* TOOLTIP */

.tooltip {
	align-items: center;
	background-color: rgba(0, 0, 0, 0.75);
	border-radius: var(--md-border-radius);
	color: var(--md-site-text-contrast);
	cursor: default;
	display: none;
	font-size: 0.75em;
	gap: var(--md-third);
	justify-content: center;
	line-height: 1;
	padding: var(--md-third);
	position: absolute;
	width: 160px;
}

.tooltip:after {
	border-style: solid;
	border-width: 5px;
	content: '';
	position: absolute;
}

.tooltip-center {
	inset-block-end: calc(100% + var(--md-small));
	inset-inline-start: 50%;
	transform: translateX(-50%);
}

.tooltip-center:after {
	border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
	inset-block-start: 100%;
	inset-inline-start: 50%;
	transform: translateX(-50%);
}

.tooltip-left {
	inset-block-start: 50%;
	inset-inline-end: calc(100% + var(--md-small));
	transform: translateY(-50%);
}

.tooltip-left:after {
	border-color: transparent transparent transparent rgba(0, 0, 0, 0.8);
	inset-block-start: 50%;
	inset-inline-start: 100%;
	transform: translateY(-50%);
}

.tooltip-parent { position: relative; }

.tooltip-parent:hover .tooltip { display: inline-flex; }

/* TABS */

.tabs {
	--md-tab-color: var(--md-text-muted);
	--md-tab-bg: transparent;
	--md-tab-border: transparent;
	--md-tab-active-color: var(--md-color-text);
	--md-tab-active-bg: var(--md-content-box-background);
	--md-tab-active-border: var(--md-border);
	--md-tab-active-weight: var(--md-bold);
	margin-block-end: var(--md-single);
}

.tabs > .tab {
	align-items: center;
	background-color: var(--md-tab-bg);
	border: 1px solid var(--md-tab-border);
	border-radius: var(--md-border-radius) var(--md-border-radius) 0 0;
	border-width: 1px 1px 0;
	color: var(--md-tab-color);
	display: inline-flex;
	gap: var(--md-third);
	padding: var(--md-half);
	text-decoration: none;
}

.tabs > .tab a {
	color: inherit;
	text-decoration: none;
}

.tabs > .tab:hover { color: var(--md-tab-active-color); }

.tabs > .tab.active {
	background-color: var(--md-tab-active-bg);
	border-color: var(--md-tab-active-border);
	border-block-end: 1px solid var(--md-tab-active-bg);
	color: var(--md-tab-active-color);
	font-weight: var(--md-tab-active-weight);
	margin-block-end: -1px;
	position: relative;
	z-index: 1;
}

.tabs ~ .tab-panel { border-start-start-radius: 0; }

.tab { cursor: pointer; }

.tab:focus-visible {
	outline: 2px solid var(--md-links);
	outline-offset: 2px;
}

.tab-panel:not(.active):not(.editor-styles-wrapper *) { display: none; }

/* ACCORDION */

.wp-block-details {
	background: linear-gradient(var(--md-color-divider), var(--md-color-divider)) no-repeat center bottom / calc(100% - 2 * var(--md-half)) 1px;
	padding-inline: var(--md-half);
	transition: background-color var(--md-transition), box-shadow var(--md-transition);
}

.wp-block-details:last-child:not([open]) { background-image: none; }

.wp-block-details[open] {
	background-color: var(--md-color-surface);
	background-image: none;
	border-radius: var(--md-border-radius);
	box-shadow: inset 0 0 0 1px var(--md-color-divider);
}

.has-surface-background-color .wp-block-details[open] { background-color: var(--md-color-white); }

.wp-block-details.is-style-plain,
.wp-block-details.is-style-plain[open] {
	background: linear-gradient(var(--md-color-divider), var(--md-color-divider)) no-repeat center bottom / 100% 1px;
	border-radius: 0;
	box-shadow: none;
	padding-inline: 0;
}

.wp-block-details.is-style-plain:last-child { background-image: none; }

@media (min-width: 782px) {
	.the-content .wp-block-details:not(.is-style-plain) { margin-inline: calc(-1 * var(--md-half)); }
}

.wp-block-details summary {
	align-items: center;
	cursor: pointer;
	display: flex;
	gap: var(--md-half);
	justify-content: space-between;
	list-style: none;
	padding-block: var(--md-half);
}

.wp-block-details summary::-webkit-details-marker { display: none; }

.wp-block-details summary::after {
	align-items: center;
	background-color: rgba(0, 0, 0, 0.06);
	border-radius: 50%;
	content: '+';
	display: flex;
	flex-shrink: 0;
	font-size: 20px;
	height: 32px;
	justify-content: center;
	line-height: 1;
	transition: background-color var(--md-transition), color var(--md-transition);
	width: 32px;
}

.wp-block-details[open] summary::after {
	background-color: var(--md-accordion-accent, var(--md-color-primary));
	color: var(--md-color-white);
	content: '\2212';
}

.wp-block-details > :last-child:not(summary) { padding-block-end: var(--md-half); }

/* TABLE */

.wp-block-table { overflow-x: auto; }

figure.wp-block-table table { margin-block-end: 0; }

:is(.format, .wp-block-table) table {
	border: 1px solid var(--md-border);
	border-collapse: separate;
	border-radius: var(--md-border-radius);
	border-spacing: 0;
	font-size: var(--md-font-size-small);
	line-height: var(--md-line-height-small);
	overflow: hidden;
	width: 100%;
}

:is(.format, .wp-block-table) table.has-fixed-layout { table-layout: fixed; }

:is(.format, .wp-block-table) :is(th, td) {
	border-block-end: 1px solid var(--md-border);
	padding: var(--md-half);
	text-align: start;
	vertical-align: top;
}

:is(.format, .wp-block-table) :is(tbody, tfoot) tr:last-child :is(th, td) { border-block-end: 0; }

:is(.format, .wp-block-table) thead th {
	background-color: var(--md-color-tertiary);
	color: var(--md-color-text-secondary);
	font-weight: var(--md-bold);
}

:is(.format, .wp-block-table) tfoot :is(th, td) {
	background-color: var(--md-color-tertiary);
	border-block-start: 1px solid var(--md-border);
	font-weight: 400;
}

.wp-block-table.is-style-stripes tbody tr:nth-child(even) :is(th, td) { background-color: var(--md-color-surface); }

.wp-block-table figcaption {
	color: var(--md-text-muted);
	font-size: var(--md-font-size-small);
	margin-block-start: var(--md-third);
	text-align: center;
}

/* QUERIES */

@media all and (min-width: 700px) {
	.expanded .scroller-list:not(.scroller-overflow) { justify-content: center; }
	.show-mobile { display: none !important; }
}

@media all and (max-width: 700px) {
	.show-desktop { display: none !important; }
}
