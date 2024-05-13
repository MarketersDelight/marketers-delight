<style type="text/css">

/*------------------------------*\
	$LAYOUT
\*------------------------------*/

.inner {
	margin-left: auto;
	margin-right: auto;
	max-width: <?php echo $site_width; ?>px;
	position: relative;
}

.clear:after, .inner:after, .menu:after, .narrow:after,
.byline:after, .post-box:after, .the-content:after, .sidebar:after {
	clear: both;
	content: '';
	display: table;
}

/* SPACING */

.main > .inner,
.query > .inner {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.main + .query > .inner,
.block + .main > .inner { padding-top: 0; }

@media all and (max-width: <?php echo $site_width; ?>px) {
	.inner {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
}

@media all and (min-width: 900px) {
	.content-width { max-width: <?php echo $content_width; ?>px; }
	.post-width { max-width: <?php echo $post_width; ?>px; }
	.sidebar-width { max-width: <?php echo $sidebar_width; ?>px; }
	.narrow .content {
		float: left;
		width: <?php echo ( ( $content_width / $site_width ) * 100 ); ?>%;
	}
	.narrow.left .content { float: right; }
	.narrow .sidebar {
		float: left;
		padding-left: <?php echo $single; ?>px;
		width: <?php echo ( ( $sidebar_width / $site_width ) * 100 ); ?>%;
	}
	.narrow.left .sidebar {
		padding-left: 0;
		padding-right: <?php echo $single; ?>px;
	}
}

@media all and (max-width: 900px) {
	.content { margin-bottom: <?php echo $single; ?>px; }
	.single .content.box-style > .entry,
	.page .content.box-style > .entry {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
		width: auto;
	}
}

/* BLOCK LAYOUT */

.content > .page-headline,
.content .inner > .page-headline,
.main .inner > .page-headline { margin-bottom: <?php echo $single; ?>px; }

.block,
.block .wrap,
.block.wide .inner {
	display: flex;
	flex-direction: column;
	gap: <?php echo $half; ?>px <?php echo $single; ?>px;
	position: relative;
}

.block.inline .wrap { column-gap: <?php echo $half; ?>px; }
.block.wide .cta { column-gap: <?php echo $single; ?>px; }

.block .wrap:empty { display: none; } /* heh */

.block.wide .wrap { width: 100%; }

.block.wide.image-left .wrap,
.block.wide.image-right .wrap { width: auto; }

.block .title:not(:last-child),
.block .subtitle:not(:last-child) { margin-bottom: 0; }

@media all and (min-width: <?php echo $post_width; ?>px) {
	.block .description { max-width: <?php echo $post_width; ?>px; }
	.block.wide,
	.block.wide .inner { justify-content: center; }
	.block.wide,
	.block.wide .wrap,
	.expanded .page-headline,
	.expanded .page-headline .wrap { align-items: center; }
	.block.wide,
	.expanded .page-headline { text-align: center; }
	.block.wide,
	.block.wide .inner,
	.block.inline .wrap { flex-flow: initial; }
	.block.wide.image-before,
	.block.image-center,
	.block.image-center .wrap { flex-direction: column; }
	.block.image-left .featured-image { order: -1; }
}

@media all and (max-width: <?php echo $post_width; ?>px) {
	.block .featured-image {
		margin-left: auto;
		margin-right: auto;
	}
}

/* ASIDE ELEMENTS */

<?php foreach ( array( 'sidebar', 'footer' ) as $aside ) {
	echo
		".{$aside} {\n".
			( ! empty( $colors[$aside]['bg_color'] ) ? "\tbackground-color: " . $colors[$aside]['bg_color'] . ";\n" : '' ).
			'color: ' . $colors[$aside]['text'] . ";\n".
			'font-size: ' . $typography[$aside]['font_size']['desktop'] . "px;\n".
			'line-height: ' . $typography[$aside]['line_height']['desktop'] . "px;\n".
			"position: relative;\n".
		"}\n".
		".{$aside} a { color: " . $colors[$aside]['links'] . "; }\n".
		".{$aside} .widget-title { color: " . $colors[$aside]['title'] . "; }\n".
		".{$aside} .widget-title a { color: " . $colors[$aside]['title_link'] . "; }\n";
} ?>

.footer .columns {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.footer-copy {
	border-top: 1px solid <?php echo $colors['footer']['border_color']; ?>;
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
	text-align: center;
}

<?php foreach ( $queries as $w => $d ) {

	echo "@media all and (max-width: {$w}px) {\n";

	if ( ! empty( $font_size[$d] ) || ! empty( $line_height[$d] ) )
		echo "\tbody { ".
				( ! empty( $font_size[$d] ) ? 'font-size: ' . $font_size[$d] . 'px; ' : '' ).
				( ! empty( $line_height[$d] ) ? 'line-height: ' . $line_height[$d] . 'px; ' : '' ).
		 	"}\n";

	foreach ( $titles as $h => $selector ) {
		if ( ! empty( $typography[$h]['font_size'][$d] ) || ! empty( $typography[$h]['line_height'][$d] ) )
		echo "\t$selector { ".
				( ! empty( $typography[$h]['font_size'][$d] ) ? 'font-size: ' . $typography[$h]['font_size'][$d] . 'px; ' : '' ).
				( ! empty( $typography[$h]['line_height'][$d] ) ? 'line-height: ' . $typography[$h]['line_height'][$d] . 'px; ' : '' ).
			"}\n";
	}

	foreach ( array( 'sidebar', 'footer' ) as $aside )
		if ( ! empty( $typography[$aside]['font_size'][$d] ) || ! empty( $typography[$aside]['line_height'][$d] ) ) {
			echo ".{$aside} { ".
		 		( ! empty( $typography[$aside]['font_size'][$d] ) ? 'font-size: ' . $typography[$aside]['font_size'][$d] . 'px; ' : '' ).
		 		( ! empty( $typography[$aside]['line_height'][$d] ) ? 'line-height: ' . $typography[$aside]['line_height'][$d] . 'px; ' : '' ) . '}';
	}

	echo "}\n";

} ?>
