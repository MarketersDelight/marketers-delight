<div class="md-dropins md-content-wrap-wide">

	<?php

	echo md_dropins::notice();

	if ( $table->status === 'all' )
		echo md_dropins::upsell();

	if ( current_user_can( 'upload_plugins' ) ) : ?>

	<div id="md_dropin_upload" class="md-dropins-upload" hidden>

		<div class="upload-plugin">

			<p class="install-help"><?php esc_html_e( 'If you have Drop-ins in a .zip format, you may install or update them by uploading them here. Choose several at once to install them together.', 'md' ); ?></p>

			<form method="post" enctype="multipart/form-data" class="wp-upload-form md-dropin-upload-form">

				<label class="screen-reader-text" for="md_dropin_zips"><?php esc_html_e( 'Drop-in zip files', 'md' ); ?></label>

				<input type="file" id="md_dropin_zips" name="files[]" accept=".zip" multiple />

				<input type="submit" class="button" value="<?php esc_attr_e( 'Install Now', 'md' ); ?>" disabled />

			</form>

		</div>

		<div class="md-dropin-results" hidden>

			<p class="md-dropin-results-actions" hidden>
				<a class="md-dropin-results-refresh" href="<?php echo esc_url( admin_url( 'admin.php?page=md_dropins' ) ); ?>" hidden><?php esc_html_e( 'Show them in the list', 'md' ); ?></a>
				<button type="button" class="button button-primary md-dropin-replace-all" hidden></button>
			</p>

			<table class="widefat">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Drop-in', 'md' ); ?></th>
						<th><?php esc_html_e( 'Current', 'md' ); ?></th>
						<th><?php esc_html_e( 'Uploaded', 'md' ); ?></th>
						<th><?php esc_html_e( 'Requires MD', 'md' ); ?></th>
						<th><?php esc_html_e( 'Status', 'md' ); ?></th>
					</tr>
				</thead>
				<tbody aria-live="polite"></tbody>
			</table>

		</div>

	</div>

	<?php endif;

	if ( $table->status === 'more' ) : ?>

	<p class="md-dropins-more-intro"><?php esc_html_e( 'These Drop-ins come with MD Pro. Upgrade and they move to Available, ready to install from here.', 'md' ); ?></p>

	<?php endif; ?>

	<form method="post" id="bulk-action-form" action="<?php echo esc_url( md_dropins::url() ); ?>">

		<input type="hidden" name="page" value="md_dropins" />

		<input type="hidden" name="dropin_status" value="<?php echo esc_attr( $table->status ); ?>" />

		<?php $table->display(); ?>

	</form>

</div>
