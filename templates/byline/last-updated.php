<?php $label = ! empty( $fields['title'] ) ? $fields['title'] : __( 'Last updated:', 'md' ); ?>

<span class="byline-item byline-date-modified">
	<?php echo md_icon( 'clock' ) . ' ' . esc_html( $label ) . ' ' . get_the_modified_date(); ?>
</span>
