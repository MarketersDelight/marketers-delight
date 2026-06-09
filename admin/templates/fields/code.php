<?php $rows = isset( $args['rows'] ) ? $args['rows'] : 7; ?>

<div class="md-code-editor">
	<textarea name="<?php echo $name; ?>" id="<?php echo esc_attr( $id ); ?>" class="large-text" rows="<?php echo esc_attr( $rows ); ?>"><?php echo esc_textarea( $option ); ?></textarea>
</div>

<?php wp_enqueue_script( 'md-code-editor' ); ?>