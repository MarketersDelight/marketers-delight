<div class="md-license md-widget md-toggle open <?php echo ( ! empty( $license['status'] ) && $license['status'] == 'valid' ? 'valid' : 'invalid' ); ?> <?php echo ( ! empty( $theme ) || ! empty( $dropins ) ) ? 'has-updates' : 'no-updates'; ?>">

	<h3 class="md-widget-title">

		<span><?php echo __( 'Updates', 'md' ); ?></span>

		<?php if ( ! empty( $license['status'] ) ) : ?>
			<?php if ( $license['status'] == 'valid' ) : ?>
				<?php if ( ! empty( $theme ) || ! empty( $dropins ) ) : ?>
				<span class="badge"><?php echo __( 'New!', 'md' ); ?></span>
				<?php else : ?>
				<span class="badge"><?php echo __( 'Up to date', 'md' ); ?></span>
				<?php endif; ?>
			<?php else : ?>
			<span class="badge"><?php echo esc_html( $license['status'] ); ?></span>
			<?php endif; ?>
		<?php endif; ?>

	</h3>

	<div class="md-widget-item md-clear">

		<div class="md-updates">
			<?php if ( ( $license['status'] ?? '' ) === 'valid' && empty( $theme ) && empty( $dropins ) ) : ?>
				<div class="md-update-empty">
					<i class="dashicons dashicons-yes-alt" aria-hidden="true"></i>
					<div>
						<strong><?php printf( esc_html__( 'Marketers Delight %s is up to date.', 'md' ), esc_html( MD_VERSION ) ); ?></strong>
						<p><?php esc_html_e( 'No theme or Drop-in updates are available for this site.', 'md' ); ?></p>
						<?php if ( $check_status === 'success' ) : ?>
							<p class="md-license-check-feedback" role="status"><?php esc_html_e( 'Just checked: no updates are available.', 'md' ); ?></p>
						<?php elseif ( $check_status === 'failed' ) : ?>
							<p class="md-license-check-feedback is-error" role="status"><?php esc_html_e( 'Could not check for updates right now. Please try again.', 'md' ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $theme ) ) :
				$theme_name = esc_html( str_replace( ' 4', '', $theme['name'] ) );
				$theme_version = esc_html( $theme['new_version'] );
			?>

			<div class="md-update md-update-theme md-dropin update-message notice inline notice-warning">

				<div class="md-dropin-image">
					<span class="md-dropin-placeholder"<?php echo md_style( array( 'bg_color' => '#fff', 'color' => '#c82d2b' ) ); ?>>
						<img class="md-theme-mark" src="<?php echo esc_url( MD_URL . 'admin/images/md-mark.png' ); ?>" alt="">
					</span>
				</div>

				<div class="md-dropin-content">
					<h4 class="md-widget-subtitle"><?php echo sprintf( __( '%s <span class="md-version-new">%2s</span>', 'md' ), $theme_name, $theme_version ); ?></h4>
					<a href="<?php echo admin_url( wp_nonce_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $slug ), 'upgrade-theme_' . $slug ) ); ?>" class="button button-primary md-update-button" data-md-alert="<?php echo __( 'NOTICE: You are about to update the Marketers Delight WordPress theme files. Any changes made directly to the MD parent theme will be erased. Your child theme files will always remain untouched.', 'md' ); ?>"><?php echo sprintf( __( 'Upgrade now', 'md' ), $theme_version ); ?> <i class="dashicons dashicons-update-alt"></i></a>
				</div>

			</div>

			<?php endif; ?>

			<?php if ( ! empty( $dropins ) ) : ?>

			<div class="md-update-dropins">

			<?php foreach ( $dropins as $dropin_path => $dropin_update ) :
				$dropin_name = $dropin_update['name'];
				$dropin_version = $dropin_update['version'];
				$dropin_slug = $dropin_update['slug'];
				$dropin_fields = md_dropins_setting( array( 'installed', $dropin_slug ) );
				$icon = isset( $dropin_fields['icon'] ) ? $dropin_fields['icon'] : '';
				$colors = isset( $dropin_fields['colors'] ) ? explode( ',', trim( $dropin_fields['colors'] ) ) : array();
				$bg_color = isset( $colors[0] ) ? $colors[0] : '';
				$color = isset( $colors[1] ) ? $colors[1] : '';
			?>

				<div class="md-update md-dropin">
					<div class="md-dropin-image">
						<span class="md-dropin-placeholder"<?php echo md_style( array( 'bg_color' => $bg_color, 'color' => $color ) ); ?>>
							<i class="dashicons <?php echo $icon ? esc_attr( $icon ) : 'dashicons-admin-plugins'; ?>"></i>
						</span>
					</div>
					<div class="md-dropin-content">
						<h4 class="md-widget-subtitle"><?php echo sprintf( __( '%s <span class="md-version-new">%2s</span>', 'md' ), $dropin_name, $dropin_version ); ?></h4>
						<a href="<?php echo admin_url( wp_nonce_url( 'update.php?action=update-md-dropins&amp;dropin=' . urlencode( $dropin_path ), 'upgrade-dropin_' . $dropin_path ) ); ?>" class="md-update-button"  data-md-alert="<?php echo sprintf( __( 'NOTICE: You are about to upgrade to the latest version of %s. Any changes made directly to the dropin files in the /md-dropins/ directory will be overwritten with the latest files.', 'md' ), $dropin_name ); ?>"><?php echo __( 'Upgrade now', 'md' ); ?> <i class="dashicons dashicons-update-alt"></i></a>
					</div>
				</div>

			<?php endforeach; ?>

			</div>

			<?php endif; ?>

		</div>
		<?php if ( $check_status === 'failed' && ( ! empty( $theme ) || ! empty( $dropins ) ) ) : ?>
			<p class="md-license-check-feedback is-error" role="status"><?php esc_html_e( 'Could not check for updates right now. Please try again.', 'md' ); ?></p>
		<?php endif; ?>

		<?php if ( ( $license['status'] ?? '' ) === 'valid' && ! empty( $license_message['text'] ) ) : ?>
			<div class="md-license-details">
				<span><?php echo wp_kses_post( $license_message['text'] ); ?></span>
				<a href="https://marketersdelight.com/changelog/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View changelog', 'md' ); ?> &rarr;</a>
			</div>
		<?php endif; ?>

		<div class="md-license-fields md-toggle<?php echo ( ! empty( $license['status'] ) && $license['status'] != 'valid' ? ' invalid open' : '' ); ?>">

			<?php if ( ( $license['status'] ?? '' ) === 'valid' ) : ?>
				<div class="md-license-actions">
					<button type="button" class="md-license-toggle" aria-expanded="false" aria-controls="md_license_fields"><?php esc_html_e( 'Edit site license', 'md' ); ?> <i class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></i></button>
					<button type="button" class="md-action md-action-check-updates" data-md-action="check-updates" data-md-canvas="#md_update"><?php esc_html_e( 'Check for updates', 'md' ); ?> <i class="dashicons dashicons-update-alt" aria-hidden="true"></i></button>
				</div>
			<?php endif; ?>
			<p class="md-license-request-error" role="alert" hidden></p>

			<div id="md_license_fields" class="md-widget-item">

				<?php $this->fields->field( 'license_key', array(
					'type' => 'text',
					'label' => __( 'Enter MD license key', 'md' ),
					'placeholder' => __( 'Enter license key here...', 'md' ),
					'option' => ( $license['status'] ?? '' ) === 'valid' && ! empty( $license['key'] ) ? '••••' . substr( $license['key'], -4 ) : '',
					'wrap_classes' => 'md-spacer-small',
					'readonly_after_save' => ! empty( $license['status'] ) && $license['status'] == 'valid'
				) ); ?>

				<div class="md-license-button md-spacer-small">
					<span class="button button-primary md-action md-action-activate-license" data-md-action="activate-license" data-md-canvas="#md_update"><?php echo __( 'Activate license key', 'md' ); ?> <i class="dashicons dashicons-update-alt"></i></span>
					<span class="md-delete md-delete-text md-action md-action-deactivate-license" data-md-action="deactivate-license" data-md-canvas="#md_update" data-md-alert="<?php echo __( 'NOTICE: you are about to disconnect this domain from your MD account and will stop receiving one-click updates to this website. Are you sure?', 'md' ); ?>"><?php echo __( 'Deactivate site', 'md' ); ?> <i class="dashicons dashicons-no"></i></span>
				</div>

				<?php if ( ( $license['status'] ?? '' ) !== 'valid' && ! empty( $license_message['text'] ) ) : ?>
					<span class="md-license-message"><?php echo wp_kses_post( $license_message['text'] ); ?></span>
				<?php endif; ?>

			</div>

		</div>

	</div>

</div>
