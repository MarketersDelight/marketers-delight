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
	z-index: 50;
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

.scroller-nav:not(:last-child) { margin-block-end: var(--md-single); }

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
	border-block-end: 1px solid var(--md-border);
	margin-block-end: var(--md-single);
}

.tab {
	align-items: center;
	background-color: #f7f7f7;
	border: 1px solid var(--md-border);
	border-width: 1px 1px 0;
	border-radius: var(--md-border-radius) var(--md-border-radius) 0 0;
	color: var(--md-text-muted);
	cursor: pointer;
	display: inline-flex;
	gap: var(--md-third);
	padding: var(--md-half);
	text-decoration: none;
}

.tab a {
	color: var(--md-text-muted);
	text-decoration: none;
}

.tab.active {
	background-color: var(--md-content-box-background);
	border-bottom-color: var(--md-content-box-background);
	border-bottom-width: 1px;
	margin-block-end: -1px;
}

.tab.active a { color: var(--md-content-box-text-muted); }

.md-tab { cursor: pointer; }

.md-tab-content { display: none; }

.md-tab-content.active { display: block; }

/* ACCORDION */

.accordion-item:not(:last-child),
.accordion-item .menu-item:not(:last-child) { border-bottom: 1px solid var(--md-border); }

.accordion-title {
	align-items: center;
	color: var(--md-sidebar-title);
	cursor: pointer;
	display: flex;
	font-size: var(--md-h6);
	font-weight: var(--md-bold);
	line-height: var(--md-h6-line-height);
	padding: var(--md-half);
}

.accordion-title::marker { content: none; }

.accordion-title:after { content: '\e80e'; }

[open] > .accordion-title:after { content: '\e817'; }

.accordion-label { flex: 1; }

.accordion-label-icon {
	color: var(--md-text-muted);
	margin-inline-end: var(--md-third);
}

.accordion-item .menu-item { margin-block-end: 0; }

.accordion-content.small .menu-item a { padding-block: var(--md-third); }

.accordion-title:hover,
.accordion-item .menu-item:hover a,
.accordion-item .current-menu-item a { background-color: rgba(0, 0, 0, 0.03); }

.accordion-nested .accordion-title {
	font-size: inherit;
	line-height: inherit;
}

.accordion-nested > .accordion-item :is(.accordion-title, .menu-item a) { padding-left: var(--md-single); }

/* QUERIES */

@media all and (min-width: 700px) {
	.expanded .scroller-list { justify-content: center; }
	.show-mobile { display: none !important; }
}

@media all and (max-width: 700px) {
	.show-desktop { display: none !important; }
}
