<style type="text/css">

.overlay {
	background-color: <?php echo $colors['content']['page_cover']; ?>;
	content: '';
	display: block;
	inset: 0;
	position: absolute;
}

.repeat { background-repeat: repeat; }

.clickable:after, .post-nav a:after {
	content: '';
	position: absolute;
		inset: 0;
	z-index: 50;
}

.close {
	background-color: transparent;
	color: #ae2525;
	cursor: pointer;
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
}

.close:hover { background-color: rgba(0, 0, 0, 0.2); }

/* SCROLLER */

.scroller-nav {
	align-items: center;
	display: flex;
	min-width: 0;
}

.scroller-arrow {
	align-self: stretch;
	appearance: none;
	background: transparent;
	border: 0;
	border-radius: 0;
	box-shadow: none;
	color: <?php echo $colors['site']['text-sec']; ?>;
	cursor: pointer;
	flex-shrink: 0;
	font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
	padding: 0 <?php echo $third; ?>px;
	transform: none;
	transition: color 0.2s;
}

.scroller-arrow:hover {
	background: transparent;
	box-shadow: none;
	color: <?php echo $colors['site']['links']; ?>;
	transform: none;
}

.scroller-list {
	display: flex;
	flex: 1;
	flex-wrap: nowrap;
	gap: <?php echo $third; ?>px;
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
	background-color: rgba(0, 0, 0, 0.8);
	border-radius: 5px;
	color: #fff;
	cursor: default;
	display: none;
	font-size: 14px;
	line-height: 21px;
	margin-inline-start: -80px;
	padding: <?php echo $third; ?>px;
	position: absolute;
		inset-inline-start: 50%;
		inset-block-start: -40px;
	text-align: center;
	width: 160px;
}

.tooltip:after {
	border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
	border-style: solid;
	border-width: 5px;
	content: '';
	margin-inline-start: -5px;
	position: absolute;
		inset-inline-start: 50%;
		inset-block-start: 100%;
}

.tooltip-parent { position: relative; }

.tooltip-parent:hover .tooltip { display: block; }

/* TABS */

.md-tab { cursor: pointer; }

.md-tab-content { display: none; }

.md-tab-content.active { display: block; }

.tabs {
	border-block-end: 1px solid <?php echo $colors['content']['border_color']; ?>;
	margin-block-end: <?php echo $single; ?>px;
}

.tab {
	background-color: #f7f7f7;
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-width: 1px 1px 0;
	border-radius: 5px 5px 0 0;
	color: <?php echo $colors['site']['text-sec']; ?>;
	cursor: pointer;
	display: inline-block;
	line-height: 1;
	margin-inline-end: <?php echo $third; ?>px;
	padding: <?php echo $half; ?>px;
	text-decoration: none;
}

.tab a {
	color: <?php echo $colors['site']['text-sec']; ?>;
	text-decoration: none;
}

.tab.active {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	border-bottom-color: <?php echo $colors['content']['bg_color']; ?>;
	border-bottom-width: 1px;
	margin-block-end: -1px;
}

.tab i { margin-inline-end: <?php echo $third; ?>px; }

/* ACCORDION */

.accordion-item:not(:last-child),
.accordion-item .menu-item:not(:last-child) { border-bottom: 1px solid <?php echo $colors['site']['tertiary']; ?>; }

.accordion-title {
	align-items: center;
	color: <?php echo $colors['sidebar']['title']; ?>;
	cursor: pointer;
	display: flex;
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
	font-weight: <?php echo $bold; ?>;
	line-height: <?php echo $typography['h6']['line_height']['desktop']; ?>px;
	padding: <?php echo $half; ?>px;
}

.accordion-title::marker { content: none; }

.accordion-title:after { content: '\e80e'; }

[open] > .accordion-title:after { content: '\e817'; }

.panel .accordion-item:first-child .accordion-title { padding-block-start: 0; }

.accordion-label { flex: 1; }

.accordion-item .menu-item { margin-block-end: 0; }

.accordion-item .menu-item:hover a,
.accordion-item .current-menu-item a { background-color: rgba(0, 0, 0, 0.03); }

/* DISPLAYS */

@media all and (min-width: 700px) {
	.show-mobile { display: none !important; }
}

@media all and (max-width: 700px) {
	.show-desktop { display: none !important; }
}