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

.body-font { font-family: <?php echo $font_family; ?>; }

.h-font { font-family: <?php echo $h1_font_family; ?>; }

.text-left { text-align: left; }

.text-right { text-align: right; }

.text-center, .has-text-align-center { text-align: center; }

.lh1 { line-height: 1; }

.caps { text-transform: uppercase; }

a.no-underline, .no-underline a { text-decoration: none; }

.small, .text-sec {
	font-size: 0.9em;
	line-height: 1.5em;
}

.title .small {
	font-size: 0.6em;
	line-height: 1;
}

.text-sec { color: <?php echo $colors['site']['text-secondary']; ?>; }

cite, .tiny {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile'] - 1; ?>px;
}

.intro {
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h6']['line_height']['desktop']; ?>px;
}

.highlight {
	background-color: <?php echo $colors['site']['accent']; ?>;
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

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
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

/* SHADOWS */

.shadow, .wp-block-image.shadow img { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15); }

.shadow-large { box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; }

.shadow-grow { transition: 0.3s; }

.shadow-grow:hover { box-shadow: 0 6px 18px rgba(0, 0, 0,.05); }

.wp-block-image.shadow { box-shadow: none; }

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
	color: <?php echo $colors['site']['text-main']; ?>;
	height: <?php echo $single; ?>px;
	width: <?php echo $single; ?>px;
}

.square-icon, a.square-icon {
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 10px;
	height: <?php echo $mid; ?>px;
	width: <?php echo $mid; ?>px;
}

.circle-icon.mid, .square-icon.mid {
	flex: 1 0 <?php echo $mid + $small; ?>px;
	height: <?php echo $mid + $small; ?>px;
	font-size: <?php echo $typography['h3']['font_size']['mobile']; ?>px;
	width: <?php echo $mid + $small; ?>px;
}

.circle-icon.large, .square-icon.large {
	flex: 1 0 <?php echo $double; ?>px;
	height: <?php echo $double; ?>px;
	font-size: <?php echo $typography['h2']['font_size']['desktop']; ?>px;
	width: <?php echo $double; ?>px;
}

/* LAYOUT */

.fl {
	align-items: center;
	display: flex;
	gap: <?php echo $half; ?>px;
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

.content-width { max-width: <?php echo $content_width; ?>px; }

.post-width { max-width: <?php echo $post_width; ?>px; }

.sidebar-width { max-width: <?php echo $sidebar_width; ?>px; }

.width-full { width: 100%; }

/* COLUMNS */

[class*="columns-"] {
	display: grid;
	gap: <?php echo $single; ?>px;
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
	echo ".mb-$size, .format .mb-$size:not(:last-child) { margin-block-end: {$spacers[$size]}px; }\n";

foreach ( array( 'double', 'mid', 'single', 'half' ) as $size )
	echo ".break-$size, .format .break-$size { margin-block-end: -{$spacers[$size]}px; }\n";

echo ".mb-none, .format .mb-none { margin-block-end: 0; }\n";

/* MARGIN LEFT */

foreach ( array( 'half', 'third', 'small' ) as $size )
	echo ".ml-$size { margin-inline-start: {$spacers[$size]}px; }\n";

/* MARGIN RIGHT */

foreach ( array( 'half', 'third', 'small' ) as $size )
	echo ".mr-$size { margin-inline-end: {$spacers[$size]}px; }\n";

/* GAPS */

foreach ( array( 'small', 'third', 'half', 'single', 'mid', 'double' ) as $size )
	echo ".gap-$size, .columns-$size { gap: {$spacers[$size]}px; }\n";

/* BLOCKS / PADDING */

foreach ( array( 'small', 'third', 'half', 'single', 'mid', 'triple', 'double', 'quad' ) as $size )
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