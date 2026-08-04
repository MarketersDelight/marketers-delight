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

.font-size {
	font-size: var(--md-font-size);
	line-height: var(--md-line-height);
}

.small, .foot {
	font-size: 0.9em;
	line-height: 1.5em;
}

.title .small {
	font-size: 0.6em;
	line-height: 1;
}

.has-muted-color, .foot { color: var(--md-site-text-muted); }

.has-muted-color a, .foot a, a.has-muted-color, a.foot { color: var(--md-site-links-muted); }

cite, .tiny {
	font-size: calc(var(--md-font-size-sm) - 2px);
	line-height: calc(var(--md-line-height-sm) - 1px);
}

.intro {
	font-size: var(--md-h6);
	line-height: var(--md-h6-line-height);
}

.highlight {
	background-color: var(--md-color-highlight);
	padding-inline: var(--md-small);
}

.note {
	background-color: var(--md-content-main-background);
	border-radius: var(--md-border-radius);
	box-shadow: var(--md-box-shadow);
	padding: var(--md-half);
}

.alert {
	background-color: #fefbd1;
	border-radius: var(--md-border-radius);
	box-shadow: var(--md-box-shadow);
	padding: var(--md-half);
}

.badge {
    background-color: var(--md-color-warning);
    border-radius: var(--md-border-radius);
	color: var(--md-site-text-contrast);
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

.radius,
.wp-block-image.radius img { border-radius: var(--md-border-radius); }

.circle { border-radius: 50%; }

.border, .border-tb, .border-bottom {
	border-style: solid;
	border-width: 1px;
}

.border-tb { border-width: 1px 0; }

.border-bottom { border-width: 0 0 1px; }

/* SHADOWS */

.shadow, .wp-block-image.shadow img { box-shadow: var(--md-box-shadow); }
.shadow-small { box-shadow: var(--md-box-shadow-small); }
.shadow-medium { box-shadow: var(--md-box-shadow-medium); }
.shadow-large { box-shadow: var(--md-box-shadow-large); }
.shadow-huge { box-shadow: var(--md-box-shadow-huge); }
.shadow-none, .wp-block-image.shadow { box-shadow: none; }

.shadow-grow { transition: var(--md-transition); }
.shadow-grow:hover { box-shadow: var(--md-box-shadow-medium); }

/* ICON SHAPES */

.circle-icon, .square-icon {
	align-items: center;
	display: flex;
	flex-shrink: 0;
	justify-content: center;
	line-height: 1;
	position: relative;
}

.circle-icon, a.circle-icon {
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 50%;
	color: var(--md-site-text);
	height: var(--md-single);
	width: var(--md-single);
}

.square-icon, a.square-icon {
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: var(--md-border-radius);
	height: var(--md-mid);
	width: var(--md-mid);
}

.circle-icon.mid, .square-icon.mid {
	flex: 1 0 calc(var(--md-mid) + var(--md-small));
	height: calc(var(--md-mid) + var(--md-small));
	font-size: <?php echo $h3['font_size']['mobile']; ?>px;
	width: calc(var(--md-mid) + var(--md-small));
}

.circle-icon.double, .square-icon.double {
	flex: 1 0 var(--md-double);
	height: var(--md-double);
	font-size: var(--md-h2);
	width: var(--md-double);
}

.circle-icon.triple, .square-icon.triple {
	flex: 1 0 var(--md-triple);
	height: var(--md-triple);
	font-size: var(--md-h1);
	width: var(--md-triple);
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

.wrap { flex-wrap: wrap; }

.justify-start { justify-content: flex-start; }

.justify-center { justify-content: center; }

.justify-end { justify-content: flex-end; }

.justify-between { justify-content: space-between; }

.items-start { align-items: flex-start; }

.items-center { align-items: center; }

.items-end { align-items: flex-end; }

.items-stretch { align-items: stretch; }

.self-center,
.is-vertically-aligned-center { align-self: center; }

@media (max-width: 900px) {
	.column-mobile { flex-direction: column; }
	.reverse-mobile { flex-direction: row-reverse; }
	.wrap-mobile { flex-wrap: wrap; }
	.justify-center-mobile { justify-content: center; }
	.items-center-mobile { align-items: center; }
	.items-stretch-mobile { align-items: stretch; }
	.width-full-mobile { width: 100%; }
	.text-center-mobile { text-align: center; }
}

/* WIDTHS */

.content-width { max-width: var(--md-width-content); }

.post-width { max-width: var(--md-width-post); }

.sidebar-width { max-width: var(--md-width-sidebar); }

.width-full { width: 100%; }

/* COLUMNS */

.wp-block-columns { display: flex; }

.wp-block-column {
	flex-basis: 0;
	flex-grow: 1;
}

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

@media all and (min-width: 600px) {
	<?php for ( $g = 3; $g <= 6; $g++ ) : ?>
	.columns-<?php echo $g; ?> { grid-template-columns: repeat(2, 1fr); }
	<?php endfor; ?>
}

@media all and (min-width: 800px) {
	<?php for ( $g = 2; $g <= 6; $g++ ) : ?>
	.columns-<?php echo $g; ?> { grid-template-columns: repeat(<?php echo $g; ?>, 1fr); }
	<?php endfor; ?>
}

<?php for ( $g = 2; $g <= 6; $g++ ) {
	$col_min = max( 150, round( ( 800 - $single * ( $g - 1 ) ) / $g ) );
	$col_gap = $g - 1;

	echo ".columns-fluid-$g { grid-template-columns: repeat(auto-fit, minmax(max({$col_min}px, calc((100% - ($col_gap * var(--md-single))) / $g)), 1fr)); }\n";
}

/* MARGIN TOP */

foreach ( array_keys( $spacers ) as $size )
	echo ".mt-$size:not(:first-child) { margin-block-start: var(--md-$size); }\n";

/* MARGIN RIGHT */

foreach ( array( 'half', 'third', 'small' ) as $size )
	echo ".mr-$size { margin-inline-end: var(--md-$size); }\n";

/* MARGIN BOTTOM */

foreach ( array_keys( $spacers ) as $size )
	echo ".mb-$size, .format .mb-$size:not(:last-child) { margin-block-end: var(--md-$size); }\n";

foreach ( array( 'double', 'mid', 'single', 'half' ) as $size )
	echo ".break-$size, .format .break-$size { margin-block-end: calc(-1 * var(--md-$size)); }\n";

echo ".mb-none, .format .mb-none { margin-block-end: 0; }\n";

/* MARGIN LEFT */

foreach ( array( 'half', 'third', 'small' ) as $size )
	echo ".ml-$size { margin-inline-start: var(--md-$size); }\n";

/* GAPS */

foreach ( array_keys( $spacers ) as $size )
	echo ".gap-$size, .columns-$size { gap: var(--md-$size); }\n";

/* BLOCKS / PADDING */

foreach ( array( 'small', 'third', 'half', 'single', 'mid', 'triple', 'double', 'quad' ) as $size ) {
	$inline_var = in_array( $size, array( 'single', 'mid', 'double', 'triple', 'quad' ) ) ? "--md-$size-x" : "--md-$size";

	echo
		".block-$size { padding: var(--md-$size); }\n".
		".block-$size-tb { padding-block: var(--md-$size); }\n".
		".block-$size-lr { padding-inline: var($inline_var); }\n".
		".block-$size-top { padding-block-start: var(--md-$size); }\n".
		".block-$size-bot { padding-block-end: var(--md-$size); }\n";
}

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

@media (prefers-reduced-motion: reduce) {
	.animate-fade,
	.animate-slide-up,
	.animate-slide-down,
	.animate-zoom {
		animation: none;
	}
}
