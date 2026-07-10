<style type="text/css">

/*------------------------------*\
	$WIDGETS
\*------------------------------*/

.format .widget:not(:last-child) { margin-block-end: var(--md-single); }

.wp-block-latest-comments__comment { line-height: inherit; }

/* BLOCKS */

.callout {
	border-style: solid;
	border-width: 4px;
	border-radius: var(--md-border-radius);
	position: relative;
}

.callout.has-icon { padding-block-start: 0; }

.callout.has-icon:not(:first-child) { margin-block-start: var(--md-double); }

.callout-icon {
	margin-block-start: calc(calc(-1 * var(--md-triple)) / 2);
	margin-inline: auto;
}

/* MENU */

.format .widget_nav_menu :is(.menu, .sub-menu) {
	margin-inline-start: 0;
	margin-block-start: 0;
}

.widget_nav_menu .sub-menu { display: none !important /*temporary*/; }

.widget_nav_menu .menu-item {
	display: block;
	margin-block-end: 0;
}

.widget_nav_menu .menu-item a {
	padding: var(--md-third) 0;
	width: 100%;
}

.widget_nav_menu .menu-item:not(:last-child) { border-block-end: 1px solid var(--md-content-border); }

/* SEARCH */

.wp-block-search__input {
	align-self: normal;
	margin-inline-end: 2%;
}

.format .wp-block-search .wp-block-search__input { margin-block-end: 0; }

.wp-block-search__button { align-self: flex-start; }

/* RSS */

.rsswidget img {
	margin-inline-end: 4px;
	margin-block-start: 9px;
}

.rss-date, .widget_rss cite {
	display: block;
	margin-block-start: 13px;
}

.rss-date { margin-block-end: var(--md-half); }

.widget_rss cite:before { content: "\2014\00a0"; }

/* CALENDAR */

#wp-calendar {
	border-collapse: collapse;
	font-size: var(--md-font-size-sm);
	line-height: 1;
	margin-block-end: var(--md-small);
	margin-block-start: 0;
	text-align: center;
	width: 100%;
}

.style-default #wp-calendar {
	background-color: var(--md-content);
	border-radius: var(--md-border-radius);
	box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
	color: var(--md-color-text);
}

#wp-calendar td { padding: var(--md-third); }

#wp-calendar thead th { padding-block: var(--md-third); }

#wp-calendar thead th { background-color: #f9f9f9; }

#wp-calendar tbody a { font-weight: var(--md-bold); }

#wp-calendar thead tr, #wp-calendar tbody td { border-block-end: 1px solid var(--md-content-border); }

#wp-calendar caption {
	background-color: var(--md-color-primary);
	border-radius: var(--md-border-radius) var(--md-border-radius) 0 0;
	color: var(--md-color-white);
	padding: var(--md-half);
}