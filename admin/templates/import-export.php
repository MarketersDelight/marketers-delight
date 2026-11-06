<div class="md-content-wrap-wide mb-single">

	<?php if ( ! empty( $notice['message'] ) ) :
		$skipped = ! empty( $notice['items'] ) ? $notice['items'] : array();
		$status = $notice['type'] === 'error' ? 'error' : ( $skipped ? 'warning' : 'success' );
	?>

	<div class="notice notice-<?php echo $status; ?> is-compact inline">

		<p><?php echo md_import_export::message( $notice['message'] ); ?></p>

		<?php if ( $skipped ) : ?>
		<p><strong><?php echo esc_html( sprintf( _n( '%d item was skipped:', '%d items were skipped:', count( $skipped ), 'md' ), count( $skipped ) ) ); ?></strong></p>
		<ul class="md-bullet-list mt-none">
			<?php foreach ( $skipped as $item ) : ?>
			<li><?php echo md_import_export::message( $item ); ?></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

	</div>

	<?php endif; ?>

	<div class="columns-2 columns-half gap-half">

	<!-- Export -->

	<div class="md-widget md-widget-fill">

		<h3 class="md-widget-title"><?php esc_html_e( 'Export', 'md' ); ?></h3>

		<form class="md-widget-item" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

			<p><?php esc_html_e( 'Download this site\'s MD settings as a file. Posts, pages, menus and media go in Tools → Export.', 'md' ); ?></p>

			<p><strong><?php esc_html_e( 'Choose what to export:', 'md' ); ?></strong></p>

			<?php foreach ( $sections as $section => $label ) : ?>
			<p>
				<label><input type="checkbox" name="md_sections[]" value="<?php echo esc_attr( $section ); ?>" checked> <?php echo esc_html( $label ); ?></label>
			</p>
			<?php endforeach; ?>

			<input type="hidden" name="action" value="md_export">
			<?php wp_nonce_field( 'md_export' ); ?>

			<div class="mt-auto">
				<p class="description"><?php esc_html_e( 'Integrations, license keys and logo images are never exported.', 'md' ); ?></p>
			</div>

			<p class="md-widget-footer">
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Export', 'md' ); ?></button>
			</p>

		</form>

	</div>

	<!-- Import -->

	<div class="md-widget md-widget-fill">

		<h3 class="md-widget-title">
			<?php esc_html_e( 'Import', 'md' ); ?>
			<span class="md-badge<?php echo $check ? ' is-warning' : ''; ?>"><?php $check ? esc_html_e( 'Step 2 of 2', 'md' ) : esc_html_e( 'Step 1 of 2', 'md' ); ?></span>
		</h3>

		<?php if ( ! $check ) : ?>

		<form class="md-widget-item" method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

			<p><?php esc_html_e( 'Upload an MD settings file, then choose which parts to import. Moving a whole site? First install the same Drop-ins and import the WordPress XML file under Tools → Import.', 'md' ); ?></p>

			<p><input type="file" name="md_import_file" accept=".json,application/json"></p>

			<input type="hidden" name="action" value="md_import_upload">
			<?php wp_nonce_field( 'md_import_upload' ); ?>

			<p class="md-widget-footer"><button type="submit" class="button button-primary"><?php esc_html_e( 'Upload', 'md' ); ?></button></p>

		</form>

		<?php else : ?>

		<div class="md-widget-item">

			<p><strong><?php esc_html_e( 'Nothing has changed yet.', 'md' ); ?></strong> <?php esc_html_e( 'Check this file\'s details, then choose what to import.', 'md' ); ?></p>

			<ul class="md-detail-list mt-none mb-half">
				<?php if ( $check['site'] ) : ?>
				<li><span class="dashicons dashicons-admin-site-alt3" aria-hidden="true"></span> <?php esc_html_e( 'From', 'md' ); ?> <strong><?php echo esc_html( $check['site'] ); ?></strong></li>
				<?php endif; ?>
				<?php if ( $check['md_version'] ) : ?>
				<li><span class="md-mark" aria-hidden="true"></span> <?php esc_html_e( 'MD version', 'md' ); ?> <strong><?php echo esc_html( $check['md_version'] ); ?></strong></li>
				<?php endif; ?>
				<?php if ( $check['created'] ) : ?>
				<li><span class="dashicons dashicons-calendar-alt" aria-hidden="true"></span> <?php esc_html_e( 'Exported', 'md' ); ?> <strong><?php echo esc_html( mysql2date( get_option( 'date_format' ), $check['created'] ) ); ?></strong></li>
				<?php endif; ?>
			</ul>

			<?php foreach ( $check['missing'] as $name ) : ?>
			<div class="notice notice-warning is-compact inline"><p><?php printf( esc_html__( '%s isn\'t installed, so its settings will be skipped. Install it under Theme → Drop-ins, then upload the file again.', 'md' ), esc_html( $name ) ); ?></p></div>
			<?php endforeach; ?>

			<?php foreach ( $check['inactive'] as $name ) : ?>
			<div class="notice notice-warning is-compact inline"><p><?php printf(
				esc_html__( '%1$s is inactive, so its settings will be skipped. %2$s, then come back to this page.', 'md' ),
				esc_html( $name ),
				'<a href="' . esc_url( admin_url( 'admin.php?page=md_dropins' ) ) . '">' . esc_html__( 'Activate it under Theme → Drop-ins', 'md' ) . '</a>'
			); ?></p></div>
			<?php endforeach; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

				<p><strong><?php esc_html_e( 'Choose what to import:', 'md' ); ?></strong></p>

				<?php foreach ( $check['parts'] as $part => $label ) : ?>
				<p>
					<label><input type="checkbox" name="md_parts[]" value="<?php echo esc_attr( $part ); ?>" checked> <?php echo esc_html( $label ); ?></label>
				</p>
				<?php endforeach; ?>

				<?php if ( $check['skipped'] ) : ?>
				<p><?php esc_html_e( 'These can\'t be imported here:', 'md' ); ?></p>
				<ul class="md-bullet-list mt-none">
					<?php foreach ( $check['skipped'] as $item ) : ?>
					<li><?php echo md_import_export::message( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				<input type="hidden" name="action" value="md_import">
				<?php wp_nonce_field( 'md_import' ); ?>

				<div class="mt-auto">
					<p class="description"><?php esc_html_e( 'Selected parts replace this site\'s current values. Back up first to keep a copy of all its MD settings.', 'md' ); ?></p>
					<p><button type="submit" name="md_backup" value="1" class="button"><?php esc_html_e( 'Back up all settings', 'md' ); ?></button></p>
				</div>

				<p class="md-widget-footer">
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Finish import', 'md' ); ?></button>
					<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=md_import_cancel' ), 'md_import_cancel' ) ); ?>" class="button ml-auto"><?php esc_html_e( 'Cancel', 'md' ); ?></a>
				</p>

			</form>

		</div>

		<?php endif; ?>

	</div>

	</div>

</div>
