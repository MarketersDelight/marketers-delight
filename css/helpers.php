<style type="text/css">

/*------------------------------*\
	$HELPERS
\*------------------------------*/

.fl {
	align-items: center;
	display: flex;
	gap: <?php echo $half; ?>px;
}

.grow { flex: 1; }
.shrink { flex-shrink: 0; }
.reverse { flex-direction: row-reverse; }
.column { flex-direction: column; }
.fl-center {
	align-items: center;
	justify-content: center;
}
.is-vertically-aligned-center { align-self: center; }

.width-full { width: 100%; }

<?php if ( ! has_filter( 'md_filter_disable_format_fix' ) ) : ?>
.format *:last-child { margin-block-end: 0; }
<?php endif; ?>

/* TEXT */

.normal { font-weight: <?php echo $font_weight; ?>; }

.bold { font-weight: <?php echo $bold; ?>; }

.italic { font-style: italic; }

.h-font { font-family: <?php echo $h1_font_family; ?>; }

.text-white { color: #fff; }

.text-sec, .foot, .wp-block-pullquote cite { color: <?php echo $colors['site']['text-sec']; ?>; }

.text-left { text-align: left; }
.text-right { text-align: right; }
.text-center { text-align: center; }

.caps { text-transform: uppercase; }

.small, .foot, cite {
	font-size: 0.85em;
	line-height: 1.5em;
}

.intro {
	font-size: 1.3em;
	line-height: 1.45em;
}

a.no-underline, .no-underline a { text-decoration: none; }

.highlight {
	background-color: #fdd169;
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

.columns-small, .gap-small { gap: <?php echo $small; ?>px; }
.columns-half, .gap-half { gap: <?php echo $half; ?>px; }
.columns-single, .gap-single { gap: <?php echo $single; ?>px; }
.columns-mid, .gap-mid { gap: <?php echo $mid; ?>px; }
.columns-double, .gap-double { gap: <?php echo $double; ?>px; }

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

/* WP COLUMNS */

.wp-block-columns {
	display: flex;
}

.wp-block-column {
	flex-basis: 0;
	flex-grow: 1;
}

/* SPACERS */

.mb-quad, .format .mb-quad { margin-block-end: <?php echo $quad; ?>px; }
.mb-triple, .format .mb-triple { margin-block-end: <?php echo $triple; ?>px; }
.mb-double, .format .mb-double { margin-block-end: <?php echo $double; ?>px; }
.mb-mid, .format .mb-mid { margin-block-end: <?php echo $mid; ?>px; }
.mb-single, .format .mb-single { margin-block-end: <?php echo $single; ?>px; }
.mb-half, .format .mb-half { margin-block-end: <?php echo $half; ?>px; }
.mb-third, .format .mb-third { margin-block-end: <?php echo $third; ?>px; }
.mb-small, .format .mb-small { margin-block-end: <?php echo $small; ?>px; }

.mb-none { margin-block-end: 0 !important; }
.ml-half { margin-inline-start: <?php echo $half; ?>px; }
.ml-third { margin-inline-start: <?php echo $third; ?>px; }
.ml-small { margin-inline-start: <?php echo $small; ?>px; }
.mr-third { margin-inline-end: <?php echo $third; ?>px; }
.mr-half { margin-inline-end: <?php echo $half; ?>px; }
.mr-small { margin-inline-end: <?php echo $small; ?>px; }

/* GAPS */

.gap-half { gap: <?php echo $half; ?>px; }
.gap-single { gap: <?php echo $single; ?>px; }
.gap-mid { gap: <?php echo $mid; ?>px; }
.gap-double { gap: <?php echo $double; ?>px; }

/* BLOCKS */

<?php $blocks = array(
	'half' => $half,
	'single' => $single,
	'mid' => $mid,
	'triple' => $triple,
	'double' => $double,
	'quad' => $quad
);

foreach ( $blocks as $block => $unit ) echo
	".block-$block { padding: {$unit}px; }\n
	.block-$block-tb { padding-block: {$unit}px; }\n
	.block-$block-lr { padding-inline: {$unit}px; }\n
	.block-$block-top { padding-block-start: {$unit}px; }\n
	.block-$block-bot { padding-block-end: {$unit}px; }\n";
?>

/* EDITOR COLORS */

<?php foreach ( md_editor_colors() as $color_group => $color_fields ) {
	$color_slug = $color_fields['slug'];
	$color_val = $color_fields['color'];

	echo ".has-$color_slug-background-color { background-color: $color_val; }\n".
		 ".has-$color_slug-border-color { border-color: $color_val; }\n".
		 ( $color_slug !== 'text' ? ".has-$color_slug-color { color: $color_val; }\n"
	: '' );
} ?>

.has-text-color.has-white-color { color: #fff; }