<style type="text/css">

/*------------------------------*\
	$TEXT_STYLES
\*------------------------------*/

.text-center { text-align: center; }

.text-left { text-align: left; }

.text-right { text-align: right; }

.caps { text-transform: uppercase; }

.f-small {
	font-size: 0.85em;
	line-height: 1.5em;
}

.f-normal { font-family: <?php echo $typography['body']['font_family']; ?>; }

.text-sec { color: <?php echo $colors['site']['text-sec']; ?>; }

.text-white { color: #fff; }

.text-dark { color: #1e1e1e; }

.has-drop-cap:first-letter {
	color: <?php echo $colors['site']['links']; ?>;
	float: left;
	font-size: 4.5em;
	line-height: 1;
	margin-bottom: 0.1em;
	margin-right: 0.1em;
}

<?php foreach ( md_editor_colors() as $color_group => $color_fields ) {
	$color_slug = $color_fields['slug'];
	$color_val = $color_fields['color'];
	echo
		".has-$color_slug-background-color { background-color: $color_val; }\n".
		( $color_slug !== 'text' ? ".has-$color_slug-color, .format .has-$color_slug-color { color: $color_val; }\n" : '' );
} ?>

.has-text-color.has-white-color { color: #fff; }

.highlight {
	background-color: #fdd169;
	padding-left: <?php echo $small; ?>px;
	padding-right: <?php echo $small; ?>px;
}



/*------------------------------*\
	$DESIGN
\*------------------------------*/

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.overlay {
	background-color: <?php echo $colors['page_cover']['cover_color']; ?>;
	content: '';
	display: block;
	height: 100%;
	position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		top: 0;
	width: 100%;
}

/* SHADOWS */

.shadow, .wp-block-image.shadow img { box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2); }

.shadow-large, .wp-block-image.shadow-large img { box-shadow: 0 5px 55px rgba(0, 0, 0, 0.15); }

.shadow-small, .wp-block-image.shadow-small img { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15); }

.wp-block-image.shadow, .wp-block-image.shadow-large, .wp-block-image.shadow-small { box-shadow: none; }

/* CIRCLE ICON */

.circle { border-radius: 50%; }

.circle-icon, a.circle-icon, .toc-anchor {
	align-items: center;
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 50%;
	color: <?php echo $colors['site']['text']; ?>;
	display: inline-flex;
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	font-weight: normal;
	height: <?php echo $mid; ?>px;
	justify-content: center;
	line-height: 1;
	position: relative;
	width: <?php echo $mid; ?>px;
}

.circle-icon.micro, .toc-anchor {
	height: <?php echo $single; ?>px;
	width: <?php echo $single; ?>px;
}

/* LAYOUT */

.sep { border-bottom: 1px solid rgba(0, 0, 0, 0.2); }

.display-block { display: block; }

.auto {
	margin-left: auto;
	margin-right: auto;
}

.width-full {
	clear: both;
	display: block;
	width: 100%;
}

.extend {
	width: 100vw;
	position: relative;
	left: 50%;
	right: 50%;
	margin-left: -50vw;
	margin-right: -50vw;
}



/*------------------------------*\
	$COLUMNS
\*------------------------------*/

.col { position: relative; }

@media all and (min-width: 700px) {
	.col { float: left; }
	.columns-flex:not([class*="block-"]) {
		padding-left: 5px;
		padding-right: 5px;
	}
	.columns-flex > .col {
		display: inline-block;
		float: none;
		margin-left: -5px;
		vertical-align: middle;
	}
	[class*="columns-"] .col-right { float: right; }
	.columns-2 > .col { width: 50%; }
	.columns-3 > .col { width: 33.333333333%; }
	.columns-4 > .col { width: 25%; }
	.columns-5 > .col { width: 20%; }
	.columns-6 > .col { width: 16.666666667%; }
	.columns-10-90 > .col2 { width: 90%; }
	.columns-80-20 > .col1,	.columns-20-80 > .col2 { width: 80%; }
	.columns-25-75 > .col2 { width: 75%; }
	.columns-70-30 > .col1, .columns-30-70 > .col2 { width: 70%; }
	.columns-65-35 > .col1, .columns-35-65 > .col2 { width: 65%; }
	.columns-60-40 > .col1, .columns-40-60 > .col2 { width: 60%; }
	.columns-55-45 > .col1, .columns-45-55 > .col2 { width: 55%; }
	.columns-55-45 > .col2, .columns-45-55 > .col1 { width: 45%; }
	.columns-60-40 > .col2, .columns-40-60 > .col1 { width: 40%; }
	.columns-65-35 > .col2, .columns-35-65 > .col1 { width: 35%; }
	.columns-70-30 > .col2, .columns-30-70 > .col1 { width: 30%; }
	.columns-25-75 > .col1 { width: 25%; }
	.columns-80-20 > .col2, .columns-20-80 > .col1 { width: 20%; }
	.columns-10-90 > .col1 { width: 10%; }

	.columns-half { margin-left: -<?php echo $half; ?>px; }
	.columns-half > .col { padding-left: <?php echo $half; ?>px; }
	.columns-single { margin-left: -<?php echo $single; ?>px; }
	.columns-single > .col { padding-left: <?php echo $single; ?>px; }
	.columns-mid { margin-left: -<?php echo $mid; ?>px; }
	.columns-mid > .col { padding-left: <?php echo $mid; ?>px; }
	.columns-double { margin-left: -<?php echo $double; ?>px; }
	.columns-double > .col { padding-left: <?php echo $double; ?>px; }
	.columns-triple { margin-left: -<?php echo $triple; ?>px; }
	.columns-triple > .col { padding-left: <?php echo $triple; ?>px; }
}



/*------------------------------*\
	$SPACERS
\*------------------------------*/

/* SMALL */

.mt-small { margin-top: <?php echo $small; ?>px; }

.mr-small { margin-right: <?php echo $small; ?>px; }

.mb-small:not(:last-child) { margin-bottom: <?php echo $small; ?>px; }

.ml-small { margin-left: <?php echo $small; ?>px; }

.ml-third { margin-left: <?php echo $third; ?>px; }

.mr-third { margin-left: <?php echo $third; ?>px; }

/* HALF */

.mt-half { margin-top: <?php echo $half; ?>px; }

.mr-half { margin-right: <?php echo $half; ?>px; }

.mb-half:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

.ml-half { margin-left: <?php echo $half; ?>px; }

.block-half { padding: <?php echo $half; ?>px; }

.block-half-top { padding-top: <?php echo $half; ?>px; }

.block-half-bot { padding-bottom: <?php echo $half; ?>px; }

.block-half-tb {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.block-half-lr {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}

/* SINGLE */

.mt-single { margin-top: <?php echo $single; ?>px; }

.mr-single { margin-right: <?php echo $single; ?>px; }

.mb-single:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.block-single, .tagcloud { padding: <?php echo $single; ?>px; }

.block-single-tb {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.block-single-lr {
	padding-left: <?php echo $single; ?>px;
	padding-right: <?php echo $single; ?>px;
}

.block-single-top { padding-top: <?php echo $single; ?>px; }

.block-single-bot { padding-bottom: <?php echo $single; ?>px; }

/* MID */

.mt-mid { margin-top: <?php echo $mid; ?>px; }

.mb-mid:not(:last-child) { margin-bottom: <?php echo $mid; ?>px; }

.block-mid { padding: <?php echo $mid; ?>px; }

.block-mid-tb {
	padding-bottom: <?php echo $mid; ?>px;
	padding-top: <?php echo $mid; ?>px;
}

.block-mid-lr {
	padding-left: <?php echo $mid; ?>px;
	padding-right: <?php echo $mid; ?>px;
}

.block-mid-top { padding-top: <?php echo $mid; ?>px; }

.block-mid-bot { padding-bottom: <?php echo $mid; ?>px; }

/* DOUBLE */

.mt-double { margin-top: <?php echo $double; ?>px; }

.mr-double { margin-right: <?php echo $double; ?>px; }

.mb-double:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }

.block-double { padding: <?php echo $double; ?>px; }

.block-double-tb {
	padding-bottom: <?php echo $double; ?>px;
	padding-top: <?php echo $double; ?>px;
}

.block-double-lr {
	padding-left: <?php echo $double; ?>px;
	padding-right: <?php echo $double; ?>px;
}

.block-double-top { padding-top: <?php echo $double; ?>px; }

.block-double-bot { padding-bottom: <?php echo $double; ?>px; }

/* TRIPLE */

.mt-triple:not(:last-child) { margin-top: <?php echo $triple; ?>px; }

.mb-triple:not(:last-child) { margin-bottom: <?php echo $triple; ?>px; }

.block-triple { padding: <?php echo $triple; ?>px; }

.block-triple-tb {
	padding-bottom: <?php echo $triple; ?>px;
	padding-top: <?php echo $triple; ?>px;
}

.block-triple-lr {
	padding-left: <?php echo $triple; ?>px;
	padding-right: <?php echo $triple; ?>px;
}

.block-triple-top { padding-top: <?php echo $triple; ?>px; }

.block-triple-bot { padding-bottom: <?php echo $triple; ?>px; }

/* QUAD */

.mt-quad:not(:last-child) { margin-top: <?php echo $quad; ?>px; }

.mb-quad:not(:last-child) { margin-bottom: <?php echo $quad; ?>px; }

.block-quad { padding: <?php echo $quad; ?>px; }

.block-quad-tb {
	padding-bottom: <?php echo $quad; ?>px;
	padding-top: <?php echo $quad; ?>px;
}

.block-quad-lr {
	padding-left: <?php echo $quad; ?>px;
	padding-right: <?php echo $quad; ?>px;
}

.block-quad-top { padding-top: <?php echo $quad; ?>px; }

.block-quad-bot { padding-bottom: <?php echo $quad; ?>px; }

/* NONE */

.mt-none { margin-top: 0 !important; }

.mr-none { margin-right: 0; }

.mb-none { margin-bottom: 0 !important; }

.ml-none { margin-left: 0; }

.pt-none { padding-top: 0; }

.pr-none { padding-right: 0; }

.pb-none { padding-bottom: 0; }

.pl-none { padding-left: 0; }

/* QUERIES */

@media all and (max-width: 900px) {
	/* TRIPLE */
	.block-quad { padding: <?php echo $triple; ?>px; }
	.block-quad-tb {
		padding-bottom: <?php echo $triple; ?>px;
		padding-top: <?php echo $triple; ?>px;
	}
	.block-quad-top { padding-top: <?php echo $triple; ?>px; }
	.block-quad-bot { padding-bottom: <?php echo $triple; ?>px; }
	.block-quad-lr {
		padding-left: <?php echo $triple; ?>px;
		padding-right: <?php echo $triple; ?>px;
	}
	/* DOUBLE */
	.block-triple { padding: <?php echo $double; ?>px; }
	.block-triple-tb {
		padding-bottom: <?php echo $double; ?>px;
		padding-top: <?php echo $double; ?>px;
	}
	.block-triple-lr {
		padding-left: <?php echo $double; ?>px;
		padding-right: <?php echo $double; ?>px;
	}
	.block-triple-top { padding-top: <?php echo $double; ?>px; }
	.block-triple-bot { padding-bottom: <?php echo $double; ?>px; }
}

@media all and (max-width: 800px) {
	/* TRIPLE */
	.mt-quad:not(:last-child) { margin-top: <?php echo $triple; ?>px; }
	.mb-quad:not(:last-child) { margin-bottom: <?php echo $triple; ?>px; }
	/* DOUBLE */
	.mt-triple:not(:last-child) { margin-top: <?php echo $double; ?>px; }
	.mb-triple:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }
}

@media all and (max-width: 700px) {
	/* SINGLE + HALF */
	.block-quad { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }
	.block-triple, .block-double, .block-mid { padding: <?php echo $single; ?>px; }
	.block-quad-tb, .block-triple, .block-triple-tb, .block-double-tb, .block-double {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
	.block-quad-lr, .block-triple, .block-triple-lr, .block-double, .block-double-lr, .block-single-lr, .block-single, .tagcloud {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	.block-quad-top, .block-triple-top, .block-double-top { padding-top: <?php echo $single; ?>px; }
	.block-quad-bot, .block-triple-bot, .block-double-bot { padding-bottom: <?php echo $single; ?>px; }
	/* DOUBLE */
	.mt-quad:not(:last-child) { margin-top: <?php echo $double; ?>px; }
	.mb-quad:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }
	/* SINGLE */
	.mt-triple:not(:last-child) { margin-top: <?php echo $single; ?>px; }
	.mb-triple:not(:last-child), .mb-double:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
}
