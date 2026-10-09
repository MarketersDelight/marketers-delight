<?php $rows = isset( $args['rows'] ) ? $args['rows'] : 7; ?>
<?php $can_edit_code = current_user_can( 'manage_options' ) && current_user_can( 'unfiltered_html' ); ?>

<div class="md-code-editor">
	<textarea name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" class="large-text" rows="<?php echo esc_attr( $rows ); ?>"<?php echo $can_edit_code ? '' : ' readonly'; ?>><?php echo esc_textarea( $option ); ?></textarea>
</div>

<?php if ( $can_edit_code ) wp_enqueue_script( 'md-code-editor' ); ?>
