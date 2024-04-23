<style type="text/css">

.small {
	font-size: 0.8em;
	line-height: 1em;
}

.text-sec { color: <?php echo $colors['site']['text-sec']; ?>; }

a.underline { text-decoration: underline; }

/* LISTS */

.list, .list > ul, ul.list-check { list-style: none; }

.list li, ul.list-check li { position: relative; }

.list > li:not(:last-child) {
	border-bottom: 1px solid rgba(0, 0, 0, 0.15);
	margin-bottom: <?php echo $third; ?>px;
	padding-bottom: <?php echo $third; ?>px;
}

ul.list-check { margin-left: <?php echo $single + $small; ?>px; }

ul.list-check li:not(:last-child) { margin-bottom: <?php echo $third; ?>px; }

ul.list-check li:before {
	background-color: rgba(0, 0, 0, 0.08);
	border-radius: 50%;
	color: #22a340;
	padding: <?php echo $small; ?>px;
	position: absolute;
		left: -<?php echo $single + $small; ?>px;
		top: 0;
}

/* ALIGNMENTS */

.text-left {
	justify-content: start;
	text-align: left;
}

.text-right {
	justify-content: right;
	text-align: right;
}

.text-center {
	justify-content: center;
	text-align: center;
}

.auto {
	margin-left: auto;
	margin-right: auto;
}

.alignfull, .alignwide { max-width: initial; }

.alignleft, .alignright, .aligncenter, .alignnone {
	display: block;
	position: relative;
	margin-bottom: <?php echo $single; ?>px;
	z-index: 10;
}

.aligncenter {
	clear: both;
	float: none;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

.alignnone {
	clear: both;
	float: none;
}

.alignwide img, .alignfull img { width: 100%; }

.extend {
	margin-left: -50vw;
	margin-right: -50vw;
	position: relative;
		left: 50%;
		right: 50%;
	width: 100vw;
}

@media all and (min-width: 700px) {
	.alignleft {
		float: left;
		margin-right: <?php echo $half; ?>px;
	}
	.alignright {
		float: right;
		margin-left: <?php echo $half; ?>px;
	}
}

@media all and (min-width: <?php echo $content_width + $triple; ?>px) {
	.expanded .alignwide, .expanded .alignright.wrap-small { margin-right: -<?php echo $triple; ?>px; }
	.expanded .alignwide, .expanded .alignleft.wrap-small { margin-left: -<?php echo $triple; ?>px; }
	.expanded .alignfull, .expanded .alignleft.wrap { margin-left: -<?php echo $breakout; ?>%; }
	.expanded .alignfull, .expanded .alignright.wrap { margin-right: -<?php echo $breakout; ?>%; }
}

@media all and (min-width: 900px) {
	.narrow .alignfull {
		margin-left: -<?php echo $mid; ?>px;
		margin-right: -<?php echo $mid; ?>px;
	}
}

@media all and (max-width: 900px) {
	.narrow .alignfull {
		margin-left: -50vw;
		margin-right: -50vw;
		position: relative;
			left: 50%;
			right: 50%;
		width: 100vw;
	}
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.expanded .alignfull {
		margin-left: -50vw;
		margin-right: -50vw;
		position: relative;
			left: 50%;
			right: 50%;
		width: 100vw;
	}
}

@media all and (max-width: <?php echo $post_width; ?>px) {
	.alignwide {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

/* COLUMNS */

.width-full { max-width: 100%; width: 100%; }

<?php for ( $f = 6; $f <= 9; $f++ ) : ?>
.columns-<?php echo $f; ?> > .entry, .columns-<?php echo $f; ?> > .col { width: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $half; ?>px); }
<?php endfor; ?>

@media all and (min-width: 600px) {
	.columns-3 > .entry, .columns-4 > .entry, .columns-5 > .entry, .columns-3 > .col, .columns-4 > .col, .columns-5 > .col { width: calc(50% - <?php echo $half; ?>px); }
}

@media all and (min-width: 800px) {
	<?php for ( $f = 2; $f <= 5; $f++ ) : ?>
	.columns-<?php echo $f; ?> > .entry, .columns-<?php echo $f; ?> > .col { width: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $half; ?>px); }
	<?php endfor; ?>
}

/* SPACERS */

.mb-double, .format .mb-double { margin-bottom: <?php echo $double; ?>px; }
.mb-mid, .format .mb-mid { margin-bottom: <?php echo $mid; ?>px; }
.mb-single, .format .mb-single { margin-bottom: <?php echo $single; ?>px; }
.mb-half, .format .mb-half { margin-bottom: <?php echo $half; ?>px; }
.mb-small, .format .mb-small { margin-bottom: <?php echo $small; ?>px; }
.mb-none { margin-bottom: 0 !important; }
.ml-half:not(:first-child) { margin-left: <?php echo $half; ?>px; }
.ml-small:not(:first-child) { margin-left: <?php echo $small; ?>px; }
.mr-half:not(:last-child) { margin-right: <?php echo $half; ?>px; }
.mr-small:not(:last-child) { margin-right: <?php echo $small; ?>px; }

/* BLOCKS */

.block-half { padding: <?php echo $half; ?>px; }
.block-single { padding: <?php echo $single; ?>px; }
.block-mid { padding: <?php echo $mid; ?>px; }
.block-double { padding: <?php echo $double; ?>px; }
.block-triple { padding: <?php echo $triple; ?>px; }
.block-quad { padding: <?php echo $quad; ?>px; }
.block-half-tb {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}
.block-single-tb {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}
.block-mid-tb {
	padding-bottom: <?php echo $mid; ?>px;
	padding-top: <?php echo $mid; ?>px;
}
.block-double-tb {
	padding-bottom: <?php echo $double; ?>px;
	padding-top: <?php echo $double; ?>px;
}
.block-triple-tb {
	padding-bottom: <?php echo $triple; ?>px;
	padding-top: <?php echo $triple; ?>px;
}
.block-quad-tb {
	padding-bottom: <?php echo $quad; ?>px;
	padding-top: <?php echo $quad; ?>px;
}

/* HELPERS */

.border { border-bottom: 1px solid rgba(0, 0, 0, 0.1); }

.shadow, .wp-block-image.shadow img { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); }

.wp-block-image.shadow { box-shadow: none; }

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.foot {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.alert {
	background-color: #fefbd1;
	border-radius: 5px;
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
	padding: <?php echo $half; ?>px;
}

.note {
	background-color: #ddd;
	border-radius: 5px;
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
	padding: <?php echo $half; ?>px;
}

.highlight {
	background-color: #fdd169;
	padding-left: <?php echo $small; ?>px;
	padding-right: <?php echo $small; ?>px;
}

.circle { border-radius: 50%; }

.circle-icon, a.circle-icon, .toc-anchor {
	align-items: center;
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 50%;
	color: <?php echo $colors['site']['text']; ?>;
	display: inline-flex;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	font-weight: normal;
	height: <?php echo $single + $small; ?>px;
	justify-content: center;
	line-height: 1;
	position: relative;
	width: <?php echo $single + $small; ?>px;
}

.circle-icon.mid {
	height: <?php echo $mid + $small; ?>px;
	font-size: <?php echo $typography['h3']['font_size']['mobile']; ?>px;
	width: <?php echo $mid + $small; ?>px;
}

/* EDITOR COLORS */

<?php foreach ( md_editor_colors() as $color_group => $color_fields ) {
	$color_slug = $color_fields['slug'];
	$color_val = $color_fields['color'];

	echo ".has-$color_slug-background-color { background-color: $color_val; }\n".
		( $color_slug !== 'text' ? ".has-$color_slug-color, .format .has-$color_slug-color { color: $color_val; }\n"
	: '' );
} ?>

.has-text-color.has-white-color { color: #fff; }
