<div class="md-content-wrap md-sep-small">

	<h2 class="md-title"><?php echo sprintf( __( 'Icons <small>(%s)</small>', 'md' ), $icons_count ); ?></h2>

	<p><?php echo __( 'Customize the <a href="https://marketersdelight.com/font-icons-manager/" target="_blank">MD Icons library</a> with your own font icon set, and update default icons. ', 'md' ); ?></p>

	<div class="md-widget md-toggle">

		<h3 class="md-widget-title"><?php echo __( 'Manage Icons', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="md-sep-micro">
				<?php $this->fields->field( 'upload', array(
					'type' => 'upload',
					'upload_type' => 'file',
					'upload_action' => 'md_icons',
					'accept' => '.json',
					'label' => __( 'Upload font icons JSON file', 'md' ),
					'success_text' => __( 'Icons successfully updated.', 'md' ),
					'description' => __( 'To sync new and removed icons, upload the <code>selection.json</code> file from your <a href="https://icomoon.io/app/#/select" target="_blank">IcoMoon download</a>.<br />You should do this every time you change your <code>md.woff</code> child theme file.', 'md' )
				) ); ?>
			</div>

			<div class="md-sep-small">
				<button class="button md-action" data-md-action="reset-icons" data-md-alert="<?php echo __( "You are about to revert to the default MD font icons set. No files will be deleted, but your current icons\ndata will be removed and any custom icons may no longer show on your website. Do you wish to proceed?", 'md' ); ?>"<?php echo ! md_setting( 'custom_icons' ) ? ' disabled' : ''; ?>><i class="dashicons dashicons-update-alt"></i> <?php echo __( 'Restore default icons', 'md' ); ?></button>
			</div>

		</div>

	</div>

</div>

<div class="md-font-icons columns-5 columns-flex columns-half md-content-wrap-wide md-sep-small">

	<?php foreach ( $icons as $icon => $fields ) : ?>
		<div class="col md-sep-micro">
			<div class="col-style">
				<p><?php echo md_icon( $icon ); ?></p>
				<?php if ( in_array( $icon, $default_icons_ids ) ) : ?>
					<p class="md-med-title"><?php echo esc_html( $fields['label'] ); ?></p>
				<?php else : ?>
					<p>
						<?php $this->fields->field( array( 'data', $icon, 'label' ), array(
							'type' => 'text',
							'placeholder' => __( 'Enter icon name...', 'md' ),
							'classes' => 'md-med-title'
						) ); ?>
					</p>
				<?php endif; ?>
				<p><code><?php echo esc_attr( "md-icon-$icon" ); ?></code></p>
				<p><?php echo sprintf( __( 'Unicode: %s', 'md' ), '<code>\\' . esc_html( $fields['unicode'] ) . '</code>' ); ?></p>
				<?php if ( ! in_array( $icon, $default_icons_ids ) ) : ?>
					<p>
						<?php $this->fields->field( array( 'data', $icon, 'unicode' ), array(
							'type' => 'text',
							'hidden' => true
						) ); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>

	<?php endforeach; ?>

</div>

<?php $this->fields->save(); ?>