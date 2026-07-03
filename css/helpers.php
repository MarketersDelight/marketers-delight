<style type="text/css">

/*------------------------------*\
	$HELPERS
\*------------------------------*/

/* TEXT */

.underline { text-decoration: underline; }

.no-underline { text-decoration: none; }

.normal { font-weight: <?php echo $font_weight; ?>; }

.bold { font-weight: var(--md-bold); }

.italic, .foot { font-style: italic; }

.body-font { font-family: <?php echo $font_family; ?>; }

.h-font { font-family: <?php echo $h1_font_family; ?>; }

.text-left { text-align: left; }

.text-right { text-align: right; }

.text-center, .has-text-align-center { text-align: center; }

.lh1 { line-height: 1; }

.caps { text-transform: uppercase; }

a.no-underline, .no-underline a { text-decoration: none; }

.small, .text-sec, .foot {
	font-size: 0.9em;
	line-height: 1.5em;
}

.title .small {
	font-size: 0.6em;
	line-height: 1;
}

.text-sec, .foot { color: var(--md-color-text-sec); }

cite, .tiny {
	font-size: calc(var(--md-font-size-sm) - 2px);
	line-height: calc(var(--md-line-height-sm) - 1px);
}

.intro {
	font-size: var(--md-h6);
	line-height: var(--md-lh-h6);
}

.highlight {
	background-color: var(--md-color-highlight);
	padding-inline: var(--md-small);
}

.note {
	background-color: var(--md-color-tertiary);
	border-radius: var(--md-radius);
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
	padding: var(--md-half);
}

.alert {
	background-color: #fefbd1;
	border-radius: var(--md-radius);
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
	padding: var(--md-half);
}

.badge {
    background-color: #f58f2a;
    border-radius: var(--md-radius);
    color: #fff;
	font-size: calc(var(--md-font-size-sm) - 2px);
	font-weight: normal;
	line-height: 1;
	padding: 4px 7px;
    position: relative;
}

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* BORDERS */

.radius, .radius img { border-radius: var(--md-radius); }

.circle { border-radius: 50%; }

.border, .border-tb, .border-bottom {
	border-style: solid;
	border-width: 1px;
}

.border-tb { border-width: 1px 0; }

.border-bottom { border-width: 0 0 1px; }

/* SHADOWS */

.shadow, .wp-block-image.shadow img { box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10); }
.shadow-small { box-shadow: var(--md-shadow); }
.shadow-medium { box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12); }
.shadow-large { box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); }
.shadow-huge { box-shadow: 0 16px 48px rgba(0, 0, 0, 0.20); }
.shadow-none, .wp-block-image.shadow { box-shadow: none; }

.shadow-grow { transition: var(--md-transition); }
.shadow-grow:hover { box-shadow: 0 6px 18px rgba(0, 0, 0,.05); }

/* ICON SHAPES */

.circle-icon, .square-icon {
	align-items: center;
	display: inline-flex;
	flex-shrink: 0;
	justify-content: center;
	line-height: 1;
	position: relative;
}

.circle-icon, a.circle-icon {
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 50%;
	color: var(--md-color-text);
	height: var(--md-single);
	width: var(--md-single);
}

.square-icon, a.square-icon {
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: var(--md-radius);
	height: var(--md-mid);
	width: var(--md-mid);
}

.circle-icon.mid, .square-icon.mid {
	flex: 1 0 calc(var(--md-mid) + var(--md-small));
	height: calc(var(--md-mid) + var(--md-small));
	font-size: <?php echo $h3['font_size']['mobile']; ?>px;
	width: calc(var(--md-mid) + var(--md-small));
}

.circle-icon.large, .square-icon.large {
	flex: 1 0 var(--md-double);
	height: var(--md-double);
	font-size: var(--md-h2);
	width: var(--md-double);
}

/* LAYOUT */

.fl {
	align-items: center;
	display: flex;
	gap: var(--md-half);
}

.auto { margin-inline: auto; }

.grow { flex: 1; }

.shrink { flex-shrink: 0; }

.reverse { flex-direction: row-reverse; }

.column { flex-direction: column; }

.fl-center { justify-content: center; }

.is-vertically-aligned-center { align-self: center; }

.start { align-items: start; }

/* WIDTHS */

.content-width { max-width: var(--md-content-width); }

.post-width { max-width: var(--md-post-width); }

.sidebar-width { max-width: var(--md-sidebar-width); }

.width-full { width: 100%; }

/* COLUMNS */

[class*="columns-"] {
	display: grid;
	gap: var(--md-single);
	width: 100%;
}

.entry, .col {
	min-width: 0;
	position: relative;
	width: 100%;
}

.col-full { grid-column: 1 / -1; }

<?php for ( $g = 6; $g <= 6; $g++ ) : ?>
.columns-<?php echo $g; ?> { grid-template-columns: repeat(<?php echo $g; ?>, 1fr); }
<?php endfor; ?>

@media all and (min-width: 600px) {
	<?php for ( $g = 3; $g <= 5; $g++ ) : ?>
	.columns-<?php echo $g; ?> { grid-template-columns: repeat(2, 1fr); }
	<?php endfor; ?>
}

@media all and (min-width: 800px) {
	<?php for ( $g = 2; $g <= 6; $g++ ) : ?>
	.columns-<?php echo $g; ?> { grid-template-columns: repeat(<?php echo $g; ?>, 1fr); }
	<?php endfor; ?>
}

/* WP COLUMNS (EXPERIMENTAL) */

.wp-block-columns { display: flex; }

.wp-block-column {
	flex-basis: 0;
	flex-grow: 1;
}

<?php

/* MARGIN BOTTOM */

foreach ( array_keys( $spacers ) as $size )
	echo ".mb-$size, .format .mb-$size:not(:last-child) { margin-block-end: var(--md-$size); }\n";

foreach ( array( 'double', 'mid', 'single', 'half' ) as $size )
	echo ".break-$size, .format .break-$size { margin-block-end: calc(-1 * var(--md-$size)); }\n";

echo ".mb-none, .format .mb-none { margin-block-end: 0; }\n";

/* MARGIN LEFT */

foreach ( array( 'half', 'third', 'small' ) as $size )
	echo ".ml-$size { margin-inline-start: var(--md-$size); }\n";

/* MARGIN RIGHT */

foreach ( array( 'half', 'third', 'small' ) as $size )
	echo ".mr-$size { margin-inline-end: var(--md-$size); }\n";

/* GAPS */

foreach ( array( 'small', 'third', 'half', 'single', 'mid', 'double' ) as $size )
	echo ".gap-$size, .columns-$size { gap: var(--md-$size); }\n";

/* BLOCKS / PADDING */

foreach ( array( 'small', 'third', 'half', 'single', 'mid', 'triple', 'double', 'quad' ) as $size )
	echo
		".block-$size { padding: var(--md-$size); }\n".
		".block-$size-tb { padding-block: var(--md-$size); }\n".
		".block-$size-lr { padding-inline: var(--md-$size); }\n".
		".block-$size-top { padding-block-start: var(--md-$size); }\n".
		".block-$size-bot { padding-block-end: var(--md-$size); }\n";

echo ".pb-none { padding-block-end: 0; }\n";

/* EDITOR COLORS */

foreach ( md_editor_colors() as $color_group => $color_fields ) {
	$color_slug = $color_fields['slug'];
	$color_val = $color_fields['color'];

	echo ".has-$color_slug-background-color { background-color: $color_val; }\n".
		 ".has-$color_slug-border-color { border-color: $color_val; }\n".
		 ".has-$color_slug-color, a.has-$color_slug-color { color: $color_val; }\n";
} ?>

/* ANIMATIONS */

@keyframes md-fade {
	from { opacity: 0; }
	to { opacity: 1; }
}

@keyframes md-slide-up {
	from { opacity: 0; transform: translateY(60px); }
	to { opacity: 1; transform: none; }
}

@keyframes md-slide-down {
	from { opacity: 0; transform: translateY(-60px); }
	to { opacity: 1; transform: none; }
}

@keyframes md-zoom {
	from { opacity: 0; transform: scale(0.8); }
	to { opacity: 1; transform: none; }
}

.animate-fade { animation: md-fade var(--md-transition-slow) ease-out both; }
.animate-slide-up { animation: md-slide-up var(--md-transition-slow) ease-out both; }
.animate-slide-down { animation: md-slide-down var(--md-transition-slow) ease-out both; }
.animate-zoom { animation: md-zoom var(--md-transition-slow) ease-out both; }