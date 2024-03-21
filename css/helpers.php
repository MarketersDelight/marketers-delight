<style type="text/css">

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

/* ALIGNMENTS */

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

.auto {
	margin-left: auto;
	margin-right: auto;
}

@media all and (min-width: <?php echo $site_width; ?>px) {
	.expanded .alignfull, .expanded .alignleft.wrap { margin-left: -<?php echo $breakout; ?>%; }
	.expanded .alignfull, .expanded .alignright.wrap { margin-right: -<?php echo $breakout; ?>%; }
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

@media all and (min-width: 900px) {
	.content-sidebar .alignfull {
		margin-left: -<?php echo $mid; ?>px;
		margin-right: -<?php echo $mid; ?>px;
	}
}

@media all and (max-width: 900px) {
	.content-sidebar .alignfull {
		margin-left: -50vw;
		margin-right: -50vw;
		position: relative;
			left: 50%;
			right: 50%;
		width: 100vw;
	}
}

@media all and (min-width: <?php echo $content_width + $triple; ?>px) {
	.expanded .alignwide, .expanded .alignright.wrap { margin-right: -<?php echo $triple; ?>px; }
	.expanded .alignwide, .expanded .alignleft.wrap { margin-left: -<?php echo $triple; ?>px; }
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

@media all and (max-width: <?php echo $post_width; ?>px) {
	.alignwide {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

/* WIDTHS */

@media all and (min-width: 700px) {
	.f3, .f4, .f5 {
		flex-basis: calc(50% - <?php echo $half; ?>px);
		max-width: calc(50% - <?php echo $half; ?>px);
	}
}

@media all and (min-width: 900px) {
	<?php for ( $f = 2; $f <= 5; $f++ ) : ?>
	.f<?php echo $f; ?> { flex-basis: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $single; ?>px); max-width: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $single; ?>px); }
	<?php endfor; ?>
	<?php for ( $f = 2; $f <= 5; $f++ ) : ?>
	.box-style.columns > .f<?php echo $f; ?>, .slim .f<?php echo $f; ?> { flex-basis: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $half; ?>px); max-width: calc(<?php echo ( 100 / $f ); ?>% - <?php echo $half; ?>px); }
	.box-style.columns .columns .f<?php echo $f; ?> { flex-basis: <?php echo ( 100 / $f ); ?>%; max-width: <?php echo ( 100 / $f ); ?>%; }
	<?php endfor; ?>
}

/* SPACERS */

.mb-double:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }
.mb-mid:not(:last-child) { margin-bottom: <?php echo $mid; ?>px; }
.mb-single:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
.mb-half:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }
.mb-small:not(:last-child) { margin-bottom: <?php echo $small; ?>px; }
.mb-none { margin-bottom: 0 !important; }
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

/* TOOLTIP */

.tooltip {
	background-color: rgba(0, 0, 0, 0.8);
	border-radius: 5px;
	color: #fff;
	cursor: default;
	display: none;
	font-size: 14px;
	line-height: 1;
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

/* MISCELLANEOUS */

.clickable:after {
	content: '';
	inset: 0;
	position: absolute;
	z-index: 3;
}

.avatar {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.border { border-bottom: 1px solid rgba(0, 0, 0, 0.1); }

.highlight {
	background-color: #fdd169;
	padding-left: <?php echo $small; ?>px;
	padding-right: <?php echo $small; ?>px;
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

.foot {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	font-style: italic;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.shadow, .wp-block-image.shadow img { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); }
.wp-block-image.shadow { box-shadow: none; }

<?php foreach ( md_editor_colors() as $color_group => $color_fields ) {
	$color_slug = $color_fields['slug'];
	$color_val = $color_fields['color'];
	echo ".has-$color_slug-background-color { background-color: $color_val; }\n".
		( $color_slug !== 'text' ? ".has-$color_slug-color, .format .has-$color_slug-color { color: $color_val; }\n" : '' );
} ?>

.has-text-color.has-white-color { color: #fff; }

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
	height: <?php echo $mid; ?>px;
	font-size: <?php echo $typography['h3']['font_size']['mobile']; ?>px;
	width: <?php echo $mid; ?>px;
}

.close {
	background-color: transparent;
	color: #ae2525;
	cursor: pointer;
	font-size: <?php echo $typography['h6']['font_size']['desktop']; ?>px;
}

.close:hover { background-color: rgba(0, 0, 0, 0.2); }

.overlay {
	background-color: rgba(0, 0, 0, 0.5);
	content: '';
	display: block;
	inset: 0;
	position: absolute;
}

@media all and (min-width: 700px) {
	.show-mobile { display: none !important; }
}

@media all and (max-width: 700px) {
	.show-desktop { display: none !important; }
}
