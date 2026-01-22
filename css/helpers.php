<style type="text/css">

.fl {
	align-items: center;
	display: flex;
	gap: <?php echo $half; ?>px;
}

.width-full { width: 100%; }

<?php if ( ! has_filter( 'md_filter_disable_format_fix' ) ) : ?>
.format :last-child { margin-bottom: 0; }
<?php endif; ?>

/* TEXT */

.text-white { color: #fff; }
.text-sec { color: <?php echo $colors['site']['text-sec']; ?>; }

.text-left { text-align: left; }
.text-right { text-align: right; }
.text-center { text-align: center; }

.caps { text-transform: uppercase; }
.underline, .format .underline { text-decoration: underline; }

.small {
	font-size: 0.85em;
	line-height: 1.5em;
}

.intro {
	font-size: 1.3em;
	line-height: 1.45em;
}

.foot {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.highlight {
	background-color: #fdd169;
	padding-left: <?php echo $small; ?>px;
	padding-right: <?php echo $small; ?>px;
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

/* DESIGN */

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.border { border-bottom: 1px solid rgba(0, 0, 0, 0.1); }
.shadow, .wp-block-image.shadow img { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); }
.wp-block-image.shadow { box-shadow: none; }

.circle { border-radius: 50%; }

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

.slim[class*="columns-"] { column-gap: <?php echo $half; ?>px; }

.entry, .col {
	position: relative;
	width: 100%;
}

.columns-small { gap: <?php echo $small; ?>px; }
.columns-half { gap: <?php echo $half; ?>px; }
.columns-single { gap: <?php echo $single; ?>px; }
.columns-mid { gap: <?php echo $mid; ?>px; }
.columns-double { gap: <?php echo $double; ?>px; }

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

/* SPACERS */

.mb-quad, .format .mb-quad { margin-bottom: <?php echo $quad; ?>px; }
.mb-triple, .format .mb-triple { margin-bottom: <?php echo $triple; ?>px; }
.mb-double, .format .mb-double { margin-bottom: <?php echo $double; ?>px; }
.mb-mid, .format .mb-mid { margin-bottom: <?php echo $mid; ?>px; }
.mb-single, .format .mb-single { margin-bottom: <?php echo $single; ?>px; }
.mb-half, .format .mb-half { margin-bottom: <?php echo $half; ?>px; }
.mb-third, .format .mb-third { margin-bottom: <?php echo $third; ?>px; }
.mb-small, .format .mb-small { margin-bottom: <?php echo $small; ?>px; }

.mb-none { margin-bottom: 0 !important; }
.ml-half { margin-left: <?php echo $half; ?>px; }
.ml-third { margin-left: <?php echo $third; ?>px; }
.ml-small { margin-left: <?php echo $small; ?>px; }
.mr-third { margin-right: <?php echo $third; ?>px; }
.mr-half { margin-right: <?php echo $half; ?>px; }
.mr-small { margin-right: <?php echo $small; ?>px; }

/* BLOCKS */

.block-half { padding: <?php echo $half; ?>px; }
.block-half-bot { padding-bottom: <?php echo $half; ?>px; }
.block-single { padding: <?php echo $single; ?>px; }
.block-single-bot { padding-bottom: <?php echo $single; ?>px; }
.block-mid { padding: <?php echo $mid; ?>px; }
.block-double { padding: <?php echo $double; ?>px; }
.block-double-bot { padding-bottom: <?php echo $double; ?>px; }
.block-triple { padding: <?php echo $triple; ?>px; }
.block-quad { padding: <?php echo $quad; ?>px; }
.block-half-tb { padding-bottom: <?php echo $half; ?>px; padding-top: <?php echo $half; ?>px; }
.block-single-tb { padding-bottom: <?php echo $single; ?>px; padding-top: <?php echo $single; ?>px; }
.block-mid-tb { padding-bottom: <?php echo $mid; ?>px; padding-top: <?php echo $mid; ?>px; }
.block-mid-top { padding-top: <?php echo $mid; ?>px; }
.block-double-tb { padding-bottom: <?php echo $double; ?>px; padding-top: <?php echo $double; ?>px; }
.block-triple-tb { padding-bottom: <?php echo $triple; ?>px; padding-top: <?php echo $triple; ?>px; }
.block-quad-tb { padding-bottom: <?php echo $quad; ?>px; padding-top: <?php echo $quad; ?>px; }

/* EDITOR COLORS */

<?php foreach ( md_editor_colors() as $color_group => $color_fields ) {
	$color_slug = $color_fields['slug'];
	$color_val = $color_fields['color'];

	echo ".has-$color_slug-background-color { background-color: $color_val; }\n".
		( $color_slug !== 'text' ? ".has-$color_slug-color, .format .has-$color_slug-color { color: $color_val; }\n"
	: '' );
} ?>

.has-text-color.has-white-color { color: #fff; }