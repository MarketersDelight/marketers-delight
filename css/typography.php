<style type="text/css">

/* TYPOGRAPHY */

.format { word-wrap: break-word; }

<?php if ( ! has_filter( 'md_filter_disable_format_fix' ) ) : ?>
.format *:last-child { margin-bottom: 0; }
<?php endif; ?>

.format a { text-decoration: underline; }

.format ul, .format ol, .format p, .format hr, .format pre, .format table, .format blockquote, .format .wp-caption, .format .wp-block-image { margin-bottom: <?php echo $single; ?>px; }

.format ul { list-style: square; }

.format ul, .format ol { margin-left: <?php echo $single; ?>px; }

.format li {
	margin-bottom: <?php echo $third; ?>px;
	position: relative;
}

<?php // Calculate titles/headings font properties

$titles = array(
	'huge' => '.huge, .huge-title',
	'h1' => 'h1, .h1, .large-title',
	'h2' => 'h2, .h2, .main-title',
	'h3' => 'h3, .h3, .med-title',
	'h4' => 'h4, .h4, .mid-title, .slim .title',
	'h5' => 'h5, .h5, .small-title, .slim .slim .title',
	'h6' => 'h6, .h6, .micro-title'
);
$h1_font_family = ! empty( $typography['h1']['font_family'] ) ? $typography['h1']['font_family'] : $font_family;
$h1_font_weight = ! empty( $typography['h1']['font_weight'] ) ? $typography['h1']['font_weight'] : $bold;

foreach ( $titles as $attribute => $selector ) {
	$h_ff = ! empty( $typography[$attribute]['font_family'] ) ? $typography[$attribute]['font_family'] : $h1_font_family;
	$h_fw = ! empty( $typography[$attribute]['font_weight'] ) ? $typography[$attribute]['font_weight'] : $h1_font_weight;

	echo
		"$selector {\n".
			( ! empty( $typography[$attribute]['font_family'] ) || ! empty( $typography['h1']['font_family'] ) ? "\tfont-family: {$h_ff};\n" : '' ).
			"\tfont-size: " . $typography[$attribute]['font_size']['desktop'] . "px;\n".
			"\tfont-weight: {$h_fw};\n".
			"\tline-height: " . $typography[$attribute]['line_height']['desktop'] . "px;\n".
		"}\n";
}
?>

h1, h2, h3, h4, h5, h6 {
	<?php if ( $colors['site']['text'] !== $colors['site']['headline'] ) : ?>
	color: <?php echo $colors['site']['headline']; ?>;
	<?php endif; ?>
	position: relative;
}

h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: <?php echo $colors['site']['headline-links']; ?>; }

.format h1, .format h2, .format h3, .format h4, .format h5, .format h6 { margin-bottom: <?php echo $half; ?>px; }

.format h1 a, .format h2 a, .format h3 a, .format h4 a, .format h5 a, .format h6 a, .format a:hover { text-decoration: none; }

.intro {
	font-size: 1.2em;
	line-height: 1.5em;
}

.s-small {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
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

<?php if ( $colors['site']['text'] !== $colors['site']['headline'] ) : ?>
.the-content .headline, .the-content .headline a, .the-content h1, .the-content h2, .the-content h3, .the-content h4, .the-content h5, .the-content h6 { color: <?php echo $colors['site']['headline']; ?>; }
<?php endif; ?>

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