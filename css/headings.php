<style type="text/css">

/* HEADINGS */

<?php

$headings = array(
    'huge' => '.huge-title',
    'h1' => 'h1, .h1, .wp-block-post-title',
    'h2' => 'h2, .h2',
    'h3' => 'h3, .h3',
    'h4' => 'h4, .h4, .widget-title, .widget .wp-block-heading',
    'h5' => 'h5, .h5',
    'h6' => 'h6, .h6'
);

$heading_sizes = array(
    'huge' => '.huge-size',
    'h1' => '.h1-size',
    'h2' => '.h2-size',
    'h3' => '.h3-size',
    'h4' => '.h4-size',
    'h5' => '.h5-size',
    'h6' => '.h6-size'
);

$heading_selectors = array_values( $headings );
$heading_selectors[] = '.wp-block-heading';
$heading_selectors = join( ', ', $heading_selectors );

echo implode( ', ', $headings ) . " {\n".
     "\tcolor: var(--md-headline);\n".
     "\tfont-family: $h1_font_family;\n".
     "\tfont-weight: $h1_font_weight;\n".
"}\n";

foreach ( $headings as $attribute => $selector ) {
	$combined = "$selector, {$heading_sizes[$attribute]}";
	$heading_font_size = $this->fluid( $typography[$attribute]['font_size']['desktop'], $typography[$attribute]['font_size']['mobile'] );
	$heading_line_height = $this->fluid( $typography[$attribute]['line_height']['desktop'], $typography[$attribute]['line_height']['mobile'] );

	echo "$combined {\n".
         "\tfont-size: $heading_font_size;\n".
         "\tline-height: $heading_line_height;\n".
    "}\n";

	$overrides = '';

	if ( ! empty( $typography[$attribute]['font_family'] ) && $typography[$attribute]['font_family'] !== $h1_font_family )
		$overrides .= "\tfont-family: {$typography[$attribute]['font_family']};\n";

	if ( ! empty( $typography[$attribute]['font_weight'] ) && $typography[$attribute]['font_weight'] !== $h1_font_weight )
		$overrides .= "\tfont-weight: {$typography[$attribute]['font_weight']};\n";

	if ( $overrides )
		echo "$selector {\n$overrides}\n";
}
?>

:is(<?php echo $heading_selectors ?>) a {
	color: var(--md-headline-links);
	text-decoration: none;
}

:is(<?php echo $heading_selectors ?>) a:hover { text-decoration: underline; }

.format :is(h1, h2, h3, h4, h5, h6) { margin-block-end: var(--md-half); }

.format :is(<?php echo $heading_selectors; ?>):is(.alignwide, .alignfull) { text-align: center; }

.the-content :is(h2, h3, h4, h5, h6):not(:first-child) { margin-block-start: var(--md-mid); }