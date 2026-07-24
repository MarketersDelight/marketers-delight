<?php
	$classes = isset( $args['type'] ) && $args['type'] == 'group' ? ' md-title' : '';
	$icon = isset( $args['label_icon'] ) ? $args['label_icon'] : '';
	$svg = isset( $args['svg'] ) ? $args['svg'] : '';
?>

<p class="md-label-wrap">
	<label for="<?php echo esc_attr( $id ); ?>" class="md-label<?php echo esc_attr( $classes ); ?>">
		<?php echo ( $icon ? '<i class="' . esc_attr( $icon ) . '"></i>' : '' ); ?>
		<?php echo ( $svg ? '<span class="md-label-svg">' . $svg . '</span>' : '' ); ?>
		<?php echo sanitize_text_field( $args['label'] ); ?>
	</label>
</p>
