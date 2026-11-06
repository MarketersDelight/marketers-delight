<?php
	$status = $license['status'] ?? '';
	$is_valid = $status === 'valid';
	$count = count( $dropins ) + ( empty( $theme ) ? 0 : 1 );

	$count = $is_valid ? $count : 0;

	if ( $is_valid && $count )
		$badge = sprintf( _n( '%d Update', '%d Updates', $count, 'md' ), $count );
	elseif ( $is_valid )
		$badge = __( 'Up to date', 'md' );
	else
		$badge = $license_message['status'];
?>

<div class="md-widget md-toggle open md-dashboard-card md-license md-updates-card <?php echo $is_valid ? 'is-valid' : 'is-invalid'; ?> <?php echo $count ? 'has-updates' : 'no-updates'; ?>">

	<div class="md-widget-title md-updates-head">

		<h2 class="md-updates-title"><?php echo __( 'Updates', 'md' ); ?></h2>

		<?php if ( $status ) : ?>
		<span class="md-badge <?php echo ! $is_valid ? 'is-error' : ( $count ? 'is-warning' : 'is-success' ); ?>"><?php echo esc_html( $badge ); ?></span>
		<?php endif; ?>

		<?php if ( $is_valid && $count ) : ?>
		<button type="button" class="button button-primary md-update-all"><?php printf( esc_html__( 'Update all (%d)', 'md' ), $count ); ?></button>
		<?php endif; ?>

	</div>

	<?php if ( $is_valid && ! empty( $theme ) ) :
		$theme_name = esc_html( str_replace( ' 4', '', $theme['name'] ) );
		$theme_version = esc_html( $theme['new_version'] );
	?>

	<div class="md-theme-update md-update-row" data-md-update="theme" data-slug="<?php echo esc_attr( $slug ); ?>">
		<span class="md-dropin-icon md-dropin-icon-theme md-mark" aria-hidden="true"></span>
		<div class="md-update-info">
			<strong class="md-update-name"><?php printf( esc_html__( '%1$s %2$s available', 'md' ), esc_html( $theme_name ), esc_html( $theme_version ) ); ?></strong>
			<span class="md-update-meta"><?php esc_html_e( 'Updated last, after your Drop-ins.', 'md' ); ?></span>
		</div>
		<a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-theme&theme=' . urlencode( $slug ) ), 'upgrade-theme_' . $slug ) ); ?>" class="button button-primary md-update-button" data-md-alert="<?php esc_attr_e( 'NOTICE: You are about to update the Marketers Delight WordPress theme files. Any changes made directly to the MD parent theme will be erased. Your child theme files will always remain untouched.', 'md' ); ?>"><?php esc_html_e( 'Update theme', 'md' ); ?></a>
	</div>

	<?php endif; ?>

	<?php if ( $is_valid && ! empty( $dropins ) ) : ?>

	<div class="md-update-grid">

		<?php foreach ( $dropins as $dropin_update ) :
			$dropin_name = $dropin_update['name'];
		?>

		<div class="md-update-item md-update-row" data-md-update="dropin" data-slug="<?php echo esc_attr( $dropin_update['slug'] ); ?>">
			<?php echo md_get_dropin_icon( $dropin_update['slug'] ); ?>
			<div class="md-update-info">
				<strong class="md-update-name"><?php echo esc_html( $dropin_name ); ?></strong>
				<span class="md-update-meta"><?php printf( esc_html__( 'Version %s available', 'md' ), esc_html( $dropin_update['new_version'] ) ); ?></span>
			</div>
			<a href="<?php echo esc_url( md_dropins::update_url( $dropin_update['slug'] ) ); ?>" class="button md-update-button" data-md-alert="<?php echo esc_attr( sprintf( __( 'NOTICE: You are about to upgrade to the latest version of %s. Any changes made directly to the Drop-in files in the /md-dropins/ directory will be overwritten with the latest files.', 'md' ), $dropin_name ) ); ?>"><?php esc_html_e( 'Update', 'md' ); ?></a>
		</div>

		<?php endforeach; ?>

	</div>

	<?php endif; ?>

	<?php if ( $is_valid && ! $count ) : ?>

	<div class="md-updates-state is-current">
		<span class="md-status-icon" aria-hidden="true"></span>
		<div>
			<strong><?php printf( esc_html__( 'Marketers Delight %s and all Drop-ins are up to date.', 'md' ), esc_html( MD_VERSION ) ); ?></strong>
			<?php if ( $check_status === 'success' ) : ?>
				<p class="md-license-check-feedback" role="status"><?php esc_html_e( 'Just checked: no updates are available.', 'md' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php elseif ( ! $is_valid && ! empty( $license_message['text'] ) ) : ?>

	<div class="md-updates-state is-invalid">
		<span class="md-status-icon is-error" aria-hidden="true"></span>
		<div><?php echo wp_kses_post( $license_message['text'] ); ?></div>
	</div>

	<?php endif; ?>

	<?php if ( $check_status === 'failed' ) : ?>
		<p class="md-license-check-feedback is-error" role="status"><?php esc_html_e( 'Could not check for updates right now. Please try again.', 'md' ); ?></p>
	<?php endif; ?>

	<div class="md-updates-foot">

		<?php if ( $is_valid && ! empty( $license_message['text'] ) ) : ?>
		<span class="md-license-details"><?php echo wp_kses_post( $license_message['text'] ); ?></span>
		<a href="https://marketersdelight.com/changelog/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View changelog', 'md' ); ?> &rarr;</a>
		<?php endif; ?>

		<span class="md-updates-links">
			<?php if ( $is_valid ) : ?>
			<button type="button" class="md-action md-action-check-updates" data-md-action="check-updates" data-md-canvas="#md_update"><?php esc_html_e( 'Check for updates', 'md' ); ?> <i class="dashicons dashicons-update-alt" aria-hidden="true"></i></button>
			<?php endif; ?>
			<button type="button" class="md-license-toggle" aria-expanded="<?php echo $is_valid ? 'false' : 'true'; ?>" aria-controls="md_license_fields"><?php esc_html_e( 'Edit site license', 'md' ); ?></button>
		</span>

	</div>

	<div class="md-license-fields<?php echo $status && ! $is_valid ? ' open' : ''; ?>">

		<p class="md-license-request-error" role="alert" hidden></p>

		<div id="md_license_fields">

			<?php $this->fields->field( 'license_key', array(
				'type' => 'text',
				'label' => __( 'Enter MD license key', 'md' ),
				'placeholder' => __( 'Enter license key here...', 'md' ),
				'option' => $is_valid && ! empty( $license['key'] ) ? '••••' . substr( $license['key'], -4 ) : '',
				'wrap_classes' => 'mb-third',
				'readonly_after_save' => $is_valid
			) ); ?>

			<div class="md-license-button">
				<span class="button button-primary md-action md-action-activate-license" data-md-action="activate-license" data-md-canvas="#md_update"><?php esc_html_e( 'Activate license key', 'md' ); ?> <i class="dashicons dashicons-update-alt"></i></span>
				<span class="md-delete md-delete-text md-action md-action-deactivate-license" data-md-action="deactivate-license" data-md-canvas="#md_update" data-md-alert="<?php esc_attr_e( 'NOTICE: you are about to disconnect this domain from your MD account and will stop receiving one-click updates to this website. Are you sure?', 'md' ); ?>"><?php esc_html_e( 'Deactivate site', 'md' ); ?> <i class="dashicons dashicons-no"></i></span>
			</div>

		</div>

	</div>

</div>
