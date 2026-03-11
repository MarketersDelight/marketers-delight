<?php

$type = isset( $args['upload_type'] ) ? $args['upload_type'] : 'media';

if ( $type == 'media' ) :
	$upload_id = ! empty( $option['id'] ) ? esc_attr( $option['id'] ) : '';
	$upload_url = wp_get_attachment_image_url( $upload_id );
	$multiple = isset( $args['multiple'] ) ? ' data-md-multiple="true"' : '';
	$classes = array( 'md-upload', "md-upload-{$type}" );

	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	if ( $upload_url )
		$classes[] = 'has-upload';

	$classes[] = $multiple ? 'is-multiple' : 'is-single';

	$classes = join( ' ', $classes );
?>

<div class="<?php echo esc_attr( $classes ); ?>">

	<?php if ( $multiple ) :
		$upload_ids = array_filter( explode( ',', $upload_id ) );
	?>

	<p><input type="button" class="md-upload-add button" data-md-multiple="true" value="<?php echo __( 'Add Image(s)', 'md' ); ?>" /></p>

	<div class="md-upload-preview-image">
		<?php foreach ( $upload_ids as $image_id )
			echo '<span class="md-upload-multi-remove" data-md-image-id="' . $image_id . '">'.
				 wp_get_attachment_image( $image_id, 'thumbnail' ).
				 '</span>'; ?>
	</div>

	<?php else : ?>

	<div class="md-upload-canvas md-upload-add"<?php echo ( isset( $args['height'] ) ? ' style="height: ' . esc_attr( $args['height'] ) . ';"' : '' ); ?>>
		<div class="md-upload-preview-action">
			<span class="dashicons dashicons-upload"></span>
			<p class="md-upload-preview-text"><?php echo __( 'Click to upload', 'md' ); ?></p>
		</div>
		<div class="md-upload-preview-image">
			<img src="<?php echo esc_url( $upload_url ); ?>" alt="<?php echo __( 'Preview Image', 'md' ); ?>" />
		</div>
	</div>

	<div class="md-upload-actions">
		<?php if ( $upload_id ) : ?>
		<div class="md-upload-id-label">
			<?php echo sprintf( __( 'ID: %s', 'md' ), $upload_id ); ?>
		</div>
		<?php endif; ?>
		<?php if ( $upload_url ) : ?>
		<div class="md-upload-action">
			<span class="md-tooltip-parent">
				<span class="md-clipboard" data-md-clipboard="<?php echo esc_url( $upload_url ); ?>">
					<span class="dashicons dashicons-admin-links"></span>
					<?php echo __( 'Copy', 'md' ); ?>
				</span>
				<span class="md-tooltip"><?php echo __( 'Click to copy URL', 'md' ); ?></span>
			</span>
		</div>
		<?php endif; ?>
		<div class="md-upload-buttons">
			<input type="button" class="md-upload-add button" value="<?php echo __( 'Add Image', 'md' ); ?>" />
			<input type="button" class="md-upload-remove button" value="<?php echo __( 'Remove Image', 'md' ); ?>" />
		</div>
	</div>

	<?php endif; ?>

	<div class="md-upload-values">
		<input type="hidden" class="md-upload-id regular-text" name="<?php echo $name; ?>[id]" id="<?php echo "{$id}_id"; ?>" value="<?php echo esc_attr( $upload_id ); ?>" placeholder="">
	</div>

</div>

<?php wp_enqueue_media(); ?>

<?php elseif ( $type == 'file' ) :
	$upload_action = isset( $args['upload_action'] ) ? $args['upload_action'] : '';
	$alert = isset( $args['alert'] ) ? $args['alert'] : __( 'You are about to upload a new file. Do you want to proceed?', 'md' );
	$success_text = isset( $args['success_text'] ) ? $args['success_text'] : __( 'File successfully updated.', 'md' );
	$accepts = isset( $args['accept'] ) ? $args['accept'] : '';
	$accept = ! empty( $accepts ) ? " accept=\"$accepts\"" : '';
?>

<div class="md-file-upload">
	<div class="md-file-upload-field md-tooltip-parent">

		<input type="file" name="<?php echo esc_attr( $name ); ?>[url]" id="<?php echo esc_attr( "{$id}_file" ); ?>"<?php echo $accept; ?> />

		<span class="md-loading md-file-uploading"><i class="dashicons dashicons-update-alt"></i></span>

		<span class="md-tooltip md-file-upload-success"><i class="dashicons dashicons-yes"></i> <?php echo esc_html( $success_text ); ?></span>

	</div>
</div>

<?php wp_add_inline_script( 'marketers-delight', "MD.fileUpload( '" . esc_attr( "{$id}_file" ) . "', '{$upload_action}' );" ); ?>

<?php endif; ?>