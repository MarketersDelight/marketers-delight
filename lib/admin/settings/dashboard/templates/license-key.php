<div class="md-license md-widget md-sep-small <?php echo ( $status == 'valid' ? 'valid md-toggle' : 'invalid' ); ?> ">
	<h3 class="md-widget-title">
		<span><?php echo __( 'License Key', 'md' ); ?></span>
		<?php if ( ! empty( $message['status'] ) ) : ?>
			<span class="badge"><?php echo $message['status']; ?></span>
		<?php endif; ?>
	</h3>
	<div class="md-widget-item md-clear">
		<div class="md-spacer-small">
			<?php $this->fields->field( 'license_key', array(
				'type' => 'text',
				'placeholder' => __( 'Enter/edit your license key', 'md' )
			) ); ?>
		</div>
		<?php if ( ! empty( $status ) && $status == 'valid' ) : ?>
			<?php submit_button( 'deactivate', 'delete', 'md_license_deactivate', false ); ?>
		<?php else : ?>
			<?php submit_button( 'activate', 'secondary', 'md_license_activate', false ); ?>
		<?php endif; ?>
		<?php if ( ! empty( $message['text'] ) ) : ?>
			<span class="md-license-message"><?php echo $message['text']; ?></span>
		<?php endif; ?>
	</div>
</div>