<style type="text/css">

/*------------------------------*\
	$HELPERS
\*------------------------------*/

/* TEXT */

.underline { text-decoration: underline; }

.no-underline { text-decoration: none; }

.normal { font-weight: <?php echo $font_weight; ?>; }

.bold { font-weight: <?php echo $bold; ?>; }

.italic { font-style: italic; }

.h-font { font-family: <?php echo $h1_font_family; ?>; }

.text-left { text-align: left; }

.text-right { text-align: right; }

.text-center { text-align: center; }

.caps { text-transform: uppercase; }

a.no-underline, .no-underline a { text-decoration: none; }

.small {
	font-size: 0.9em;
	line-height: 1.5em;
}

cite, .tiny {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile'] - 1; ?>px;
}

.intro {
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h6']['line_height']['desktop']; ?>px;
}

.highlight {
	background-color: <?php echo $colors['site']['action']; ?>;
	padding-inline: <?php echo $small; ?>px;
}

.note {
	background-color: #ddd;
	border-radius: 5px;
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
	padding: <?php echo $half; ?>px;
}

.alert {
	background-color: #fefbd1;
	border-radius: 5px;
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
	padding: <?php echo $half; ?>px;
}

.badge {
    background-color: #f58f2a;
    border-radius: 5px;
    color: #fff;
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px;
	font-weight: normal;
	line-height: 1;
	padding: 4px 7px;
    position: relative;
}

/* BORDERS */

.radius, .radius img { border-radius: 6px; }

.circle { border-radius: 50%; }

.border, [class*="border-"] {
	border-style: solid;
	border-width: 1px;
}

.border-tb { border-width: 1px 0; }

.border-bottom { border-width: 0 0 1px; }

/* DESIGN */

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.shadow, .wp-block-image.shadow img { box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; }

.shadow-large { box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06) }

.wp-block-image.shadow { box-shadow: none; }

.circle-icon, a.circle-icon {
	align-items: center;
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 50%;
	color: <?php echo $colors['site']['text']; ?>;
	display: inline-flex;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	height: <?php echo $single + $small; ?>px;
	justify-content: center;
	line-height: 1;
	position: relative;
	width: <?php echo $single + $small; ?>px;
}

.circle-icon.mid {
	flex: 1 0 <?php echo $mid + $small; ?>px;
	height: <?php echo $mid + $small; ?>px;
	font-size: <?php echo $typography['h3']['font_size']['mobile']; ?>px;
	width: <?php echo $mid + $small; ?>px;
}

/* LAYOUT */

.fl {
	align-items: center;
	display: flex;
	gap: <?php echo $half; ?>px;
}

.grow { flex: 1; }

.shrink { flex-shrink: 0; }

.reverse { flex-direction: row-reverse; }

.column { flex-direction: column; }

.fl-center { justify-content: center; }

.is-vertically-aligned-center { align-self: center; }

.width-full { width: 100%; }

/* COLUMNS */

[class*="columns-"] {
	display: grid;
	gap: <?php echo $single; ?>px;
	width: 100%;
}

[class*="columns-"].slim { gap: <?php echo $half; ?>px; }

.entry, .col {
	position: relative;
	width: 100%;
}

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

/* SPACERS */

<?php

/* MARGIN BOTTOM */

foreach ( array( 'quad', 'triple', 'double', 'mid', 'single', 'half', 'third', 'small' ) as $size )
	echo ".mb-$size, .format .mb-$size:not(:last-child) { margin-block-end: {$spacers[$size]}px; }\n";

echo ".mb-none, .format .mb-none { margin-block-end: 0; }\n";

/* MARGIN LEFT */

foreach ( array( 'half', 'third', 'small' ) as $size )
	echo ".ml-$size { margin-inline-start: {$spacers[$size]}px; }\n";

/* MARGIN RIGHT */

foreach ( array( 'third', 'half', 'small' ) as $size )
	echo ".mr-$size { margin-inline-end: {$spacers[$size]}px; }\n";

/* GAPS */

foreach ( array( 'half', 'single', 'mid', 'double' ) as $size )
	echo ".gap-$size, .columns-$size { gap: {$spacers[$size]}px; }\n";

/* BLOCKS / PADDING */

foreach ( array( 'half', 'single', 'mid', 'triple', 'double', 'quad' ) as $size )
	echo
		".block-$size { padding: {$spacers[$size]}px; }\n".
		".block-$size-tb { padding-block: {$spacers[$size]}px; }\n".
		".block-$size-lr { padding-inline: {$spacers[$size]}px; }\n".
		".block-$size-top { padding-block-start: {$spacers[$size]}px; }\n".
		".block-$size-bot { padding-block-end: {$spacers[$size]}px; }\n";

echo ".pb-none { padding-block-end: 0; }\n";

/* EDITOR COLORS */

foreach ( md_editor_colors() as $color_group => $color_fields ) {
	$color_slug = $color_fields['slug'];
	$color_val = $color_fields['color'];

	echo ".has-$color_slug-background-color { background-color: $color_val; }\n".
		 ".has-$color_slug-border-color { border-color: $color_val; }\n".
		 ".has-$color_slug-color, a.has-$color_slug-color { color: $color_val; }\n";
} ?>