<?php if ( $type == 'media' ) : ?>

	<div class="md-upload md-upload-<?php echo $type; ?><?php echo ! empty( $upload_url ) ? ' has-upload' : ''; ?><?php echo esc_attr( $classes ); ?>">

		<div class="md-uploader">

			<div class="md-upload-preview md-upload-add">

				<div class="md-upload-previewer">
					<span class="dashicons dashicons-upload"></span>
					<p class="md-upload-preview-text"><?php echo __( 'Click to upload', 'md' ); ?></p>
				</div>

				<div class="md-upload-preview-image">
					<img src="<?php echo $upload_url; ?>" alt="<?php echo __( 'Preview Image', 'md' ); ?>" />
				</div>

			</div>

			<div class="md-upload-controls">

				<label class="md-label" for="<?php echo $id; ?>_url"><?php echo __( 'Image URL', 'md' ); ?></label>

				<input type="url" class="md-upload-url regular-text" name="<?php echo $name; ?>[url]" id="<?php echo "{$id}_url"; ?>" value="<?php echo esc_attr( $upload_url ); ?>" placeholder="https://">

				<input type="hidden" class="md-upload-id regular-text" name="<?php echo $name; ?>[id]" id="<?php echo "{$id}_id"; ?>" value="<?php echo esc_attr( $upload_id ); ?>" placeholder="">

				<?php if ( $upload_id ) : ?>
					<div class="md-upload-id-label">
						<?php echo sprintf( __( 'ID: %s', 'md' ), $upload_id ); ?>
					</div>
				<?php endif; ?>

			</div>

		</div>

		<div class="md-upload-buttons">
			<input type="button" class="md-upload-add button" value="<?php echo __( 'Add Image', 'md' ); ?>" />
			<input type="button" class="md-upload-remove button" value="<?php echo __( 'Remove Image', 'md' ); ?>" />
		</div>

	</div>

	<?php wp_enqueue_media(); ?>

<?php elseif ( $type == 'file' ) :
	$alert = isset( $args['alert'] ) ? $args['alert'] : __( 'You are about to upload a new file. Do you want to proceed?', 'md' );
	$success_text = isset( $args['success_text'] ) ? $args['success_text'] : __( 'File successfully updated.', 'md' );
?>

	<div class="md-file-upload">
		<div class="md-file-upload-field">

			<input type="file" name="<?php echo $name; ?>[url]" id="<?php echo esc_attr( "{$id}_file" ); ?>"<?php echo $accept; ?> />

			<span class="md-loading md-file-uploading"><i class="dashicons dashicons-update-alt"></i></span>

			<span class="md-tooltip md-file-upload-success"><i class="dashicons dashicons-yes"></i> <?php echo esc_html( $success_text ); ?></span>

		</div>
	</div>

	<?php wp_add_inline_script( 'marketers-delight', "MD.fileUpload( '" . esc_attr( "{$id}_file" ) . "', '{$upload_action}' );" ); ?>

<?php endif; ?>