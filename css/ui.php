<style type="text/css">

.overlay {
	background-color: rgba(0, 0, 0, 0.5);
	content: '';
	display: block;
	inset: 0;
	position: absolute;
}

.clickable:after,
.post-nav a:after {
	content: '';
	inset: 0;
	position: absolute;
	z-index: 3;
}

.close {
	background-color: transparent;
	color: #ae2525;
	cursor: pointer;
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
}

.close:hover { background-color: rgba(0, 0, 0, 0.2); }

/* TRIGGERS */

.has-search .triggers { order: 3; }

.trigger {
	align-items: center;
	cursor: pointer;
	display: flex;
	gap: <?php echo $small; ?>px;
	justify-content: center;
	position: relative;
	text-align: center;
}

.hide-label .link-text, .hide-label .trigger-text { display: none; }

.has-search .trigger-search .trigger-icon:before,
.has-mobile-menu .trigger-menu .trigger-icon:before { content: '\e810'; }

.has-search:not(.has-cover) .trigger-search .trigger-icon:before,
.has-mobile-menu:not(.has-cover) .trigger-menu .trigger-icon:before { color: <?php echo $colors['site']['primary']; ?>; }

/* TOGGLES */

.has-search .inputs,
.has-search .input-field { flex: 1; }

.form-toggle .inputs,
.form-toggle .submit,
.has-search .search-form .trigger-text { display: none; }

.has-search .inputs,
.has-search .submit,
.form-toggle .trigger-search { display: block; }

/* STICKY */

.sticky {
	position: sticky;
		top: -1px;
	z-index: 50;
}

.admin-bar .stuck { padding-top: <?php echo $admin_bar_height; ?>px; }

@media all and (max-width: 782px) {
	.admin-bar .stuck { padding-top: <?php echo $admin_bar_height_mobile; ?>px; }
}

@media all and (max-width: 600px) {
	#wpadminbar { position: fixed; }
}

/* SCROLLER NAV */

.scroller-nav {
	align-items: center;
	background-color: <?php echo $colors['site']['tertiary']; ?>;
	display: flex;
	height: 100%;
	font-size: 33px;
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
	position: absolute;
		top: 0;
		right: 0;
}

.scroller-nav i { font-size: inherit; }

@media all and (min-width: <?php echo $site_width; ?>px) {
	.scroller-nav { display: none; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.scroller {
		overflow-x: auto;
		white-space: nowrap;
	}
}

/* TOOLTIP */

.tooltip {
	background-color: rgba(0, 0, 0, 0.8);
	border-radius: 5px;
	color: #fff;
	cursor: default;
	display: none;
	font-size: 14px;
	line-height: 21px;
	margin-left: -80px;
	padding: <?php echo $third; ?>px;
	position: absolute;
		left: 50%;
		top: -40px;
	text-align: center;
	width: 160px;
}

.tooltip:after {
	border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
	border-style: solid;
	border-width: 5px;
	content: '';
	margin-left: -5px;
	position: absolute;
		left: 50%;
		top: 100%;
}

.tooltip-parent { position: relative; }

.tooltip-parent:hover .tooltip { display: block; }

/* TABS */

.md-tab { cursor: pointer; }

.md-tab-content { display: none; }

.md-tab-content.active { display: block; }

.tabs {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	margin-bottom: <?php echo $single; ?>px;
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
	margin-right: <?php echo $third; ?>px;
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
	margin-bottom: -1px;
}

.tab i { margin-right: <?php echo $third; ?>px; }

/* ACCORDION */

.accordion .current { font-weight: <?php echo $bold; ?>; }

.accordion-group:not(:last-child) {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-bottom: <?php echo $third; ?>px;
}

.accordion-group:not(:first-child) { padding-top: <?php echo $half; ?>px; }

.accordion-group.active .accordion-content { display: block; }

.accordion-title {
	color: <?php echo $colors['site']['text']; ?>;
	cursor: pointer;
	font-weight: <?php echo $bold; ?>;
	position: relative;
	width: 100%;
}

.accordion-title:after {
	border-radius: 50%;
	content: '\e80e';
	display: inline-block;
	font-family: 'md-icon';
	font-size: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
	height: <?php echo $mid; ?>px;
	line-height: <?php echo $mid; ?>px;
	margin-top: -<?php echo round( $mid / 2 ); ?>px;
	position: absolute;
		top: 50%;
		right: 0;
	text-align: center;
	width: <?php echo $mid; ?>px;
}

.box-style .accordion-title:after { right: <?php echo $half; ?>px; }

.accordion-group:hover .accordion-title:after { background-color: rgba(0, 0, 0, 0.1); }

.format .accordion-title { margin-bottom: 0; }

.accordion-group.active .accordion-title:after { content: '\e817'; }

.accordion-content {
	display: none;
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.accordion-group:last-child .accordion-content { padding-bottom: 0; }

.accordion .list {
	font-size: 0.9em;
	margin-left: 0;
}

.sidebar .accordion a {
	color: <?php echo $colors['site']['links']; ?>;
	text-decoration: none;
}

/* DISPLAYS */

@media all and (min-width: 700px) {
	.show-mobile { display: none !important; }
}

@media all and (max-width: 700px) {
	.show-desktop { display: none !important; }
}
