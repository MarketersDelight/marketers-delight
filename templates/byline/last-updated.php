<?php
if ( get_the_modified_time( 'U' ) <= get_the_time( 'U' ) )
	return;

$label = '';

if ( empty( $fields['settings']['label'] ) )
	$label = '<span class="byline-label">' . ( ! empty( $fields['name'] ) ? esc_html( $fields['name'] ) : __( 'Last updated:', 'md' ) ) . '</span>';
?>

<span class="byline-item byline-date-modified">
	<?php printf( '%s %s %s', md_icon( 'clock' ), $label, esc_html( get_the_modified_date() ) ); ?>
</span>