<div id="md_popup_<?php echo esc_attr( $atts['post_type'] ); ?>_<?php echo esc_attr( $atts['post_id'] ); ?>" class="md-popup md-popup-auto">
	<?php if ( isset( $atts['image_id'] ) ) : ?>
		<?php echo wp_get_attachment_image( $atts['image_id'], 'full' ); ?>
	<?php else : ?>
		<?php echo get_the_post_thumbnail( $atts['post_id'], 'full' ); ?>
	<?php endif; ?>
	<div class="md-popup-close md-popup-close-corner">&times;</div>
</div>