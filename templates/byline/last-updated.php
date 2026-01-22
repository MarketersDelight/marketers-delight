<?php
if ( get_the_modified_time( 'U' ) > get_the_time( 'U' ) ) {
	$label = ! empty( $fields['name'] ) ? $fields['name'] : __( 'Last updated:', 'md' );
?>

<span class="byline-item byline-date-modified">
	<?php echo md_icon( 'clock' ) . ' ' . esc_html( $label ) . ' ' . get_the_modified_date(); ?>
</span>

<?php }