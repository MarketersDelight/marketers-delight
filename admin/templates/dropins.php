<div class="md-dropins md-content-wrap-med">
	<h2 class="md-title md-sep-small">
		<?php echo __( 'Drop-ins', 'md' ); ?>
		&nbsp;<button id="md_upload_dropin_button" class="button"><?php echo __( 'Add new', 'md' ); ?></button>
		&nbsp;<a href="https://marketersdelight.com/dropins/" target="_blank" class="button button-primary"><?php echo __( 'Get Drop-ins &rarr;', 'md' ); ?></a>
	</h2>
	<div id="md_upload_dropin" class="md-dropins-upload md-sep-small">
		<?php $this->fields->field( 'upload', array(
			'type' => 'upload',
			'upload_type' => 'file',
			'upload_action' => 'md_dropin',
			'accept' => '.zip',
			'success_text' => __( 'Drop-in installed!', 'md' ),
			'label' => __( 'Upload your Drop-in package file', 'md' ),
			'description' => __( 'If you have a drop-in in a <code>.zip</code> format, you may install or update it by uploading it here. Find more available drop-ins here at the official <a href="https://marketersdelight.com/dropins/" target="_blank">Drop-ins Library</a>', 'md' )
		) ); ?>
	</div>
	<div class="md-dropins-list md-tabs md-sep-small">
		<div class="md-dropins-title">
			<h3>
				<a href="#" class="md-tab<?php echo md_get_dropins( 'active' ) ? ' nav-tab-active' : ''; ?>" data-md-tab="md-all"><?php echo sprintf( __( 'Installed <span>(%s)</span>', 'md' ), count( md_get_dropins() ) ); ?></a>
				<?php if ( md_get_dropins( 'active' ) ) : ?>
					<a href="#" class="md-tab" data-md-tab="dropin-enabled"><?php echo sprintf( __( 'Active <span>(%s)</span>', 'md' ), count( md_get_dropins( 'active' ) ) ); ?></a>
					<a href="#" class="md-tab" data-md-tab="dropin-inactive"><?php echo sprintf( __( 'Inactive <span>(%s)</span>', 'md' ), count( md_get_dropins( 'inactive' ) ) ); ?></a>
				<?php endif; ?>
			</h3>
		</div>
		<?php if ( ! empty( $installed ) ) : ?>
			<?php foreach ( $installed as $dropin => $fields ) :
				$is_enabled = md_setting( array( 'dropins', 'installed', $dropin, 'status', 'enable' ) ) ? true : false;
				$icon = isset( $fields['icon'] ) ? $fields['icon'] : '';
				$colors = isset( $fields['colors'] ) ? explode( ',', trim( $fields['colors'] ) ) : array();
				$bg_color = isset( $colors[0] ) ? $colors[0] : '';
				$color = isset( $colors[1] ) ? $colors[1] : '';
				$needs_plugin = ! empty( $fields['plugin_name'] ) && ! class_exists( $fields['plugin_class'] ) ? true : false;
				$dropin_name = esc_html( $fields['name'] );
				$path = "$dropin/$dropin.php";
				$has_updates = ! empty( $updates[$path] ) ? true : false;
			?>
				<div class="md-dropin md-tab-content active md-all <?php echo ( $is_enabled ? 'dropin-enabled' : 'dropin-inactive' ) . ( $has_updates ? ' dropin-has-updates' : '' ); ?>">
					<?php if ( $has_updates ) : ?>
						<div class="md-update md-update-theme update-message notice inline notice-warning">
							<span><?php echo sprintf( __( 'There is a new version of <strong>%s</strong> available.', 'md' ), $dropin_name ); ?></span>
							<a href="<?php echo admin_url( wp_nonce_url( 'update.php?action=update-md-dropins&amp;dropin=' . urlencode( $path ), 'upgrade-dropin_' . $path ) ); ?>" class="md-update-button"  data-md-alert="<?php echo sprintf( __( 'NOTICE: You are about to upgrade to the latest version of %s. Any changes made directly to the dropin files in the /md-dropins/ directory will be overwritten with the latest files.', 'md' ), $dropin_name ); ?>"><?php echo sprintf( __( 'Upgrade to <b>%s</b>', 'md' ), $updates[$path]['new_version'] ); ?> <i class="dashicons dashicons-update-alt"></i></a>
						</div>
					<?php endif; ?>
					<div class="md-dropin-inner">
						<div class="columns-2 columns-10-90 columns-half">
							<div class="md-dropin-image col col1">
								<span class="md-dropin-placeholder"<?php echo md_style( array( 'bg_color' => $bg_color, 'color' => $color ) ); ?>>
									<i class="dashicons <?php echo $icon ? esc_attr( $icon ) : 'dashicons-admin-plugins'; ?>"></i>
								</span>
							</div>
							<div class="md-dropin-content col col2">
								<h4 class="md-title"><a href="<?php echo esc_url( $fields['dropin_url'] ); ?>" target="_blank"><?php echo esc_html( $fields['name'] ); ?> <small><?php echo $fields['version']; ?></small></a></h4>
								<div class="md-dropin-controls">
									<?php if ( ! $needs_plugin ) : ?>
										<?php $this->fields->field( array( 'installed', $dropin, 'status' ), array(
											'type' => 'checkbox',
											'options' => array(
												'enable' => __( '<b>Activate</b>', 'md' )
											)
										) ); ?>
									<?php else : ?>
										<span class="md-dropin-plugin"><i class="dashicons dashicons-no"></i> <?php echo sprintf( __( 'Requires <b>%s</b> plugin', 'md' ), $fields['plugin_name'] ); ?></span>
									<?php endif; ?>
								</div>
								<span class="md-delete md-action" data-md-action="delete-dropin" data-md-dropin-id="<?php echo esc_attr( $dropin ); ?>" data-md-alert="<?php echo sprintf( __( "You are about to delete the %s Drop-in. All Drop-in files will be deleted, except from your child theme,\nand not all data will be saved. Do you want to proceed?", 'md' ), $fields['name'] ); ?>"><i class="dashicons dashicons-no"></i> <?php echo __( 'Delete', 'md' ); ?></span>
								<p class="md-dropin-description"><?php echo esc_html( $fields['description'] ); ?></p>
								<p class="md-dropin-byline">
									<?php if ( $is_enabled ) : ?>
										<?php if ( isset( $fields['settings_url'] ) ) : ?>
											<a href="<?php echo admin_url( $fields['settings_url'] ); ?>" class="button button-icon"><i class="dashicons dashicons-admin-generic"></i> <?php echo __( 'Settings', 'md' ); ?></a>
										<?php endif; ?>
									<?php endif; ?>
									<?php echo sprintf( __( '<i>by</i> <a href="%s" target="_blank">%1s</a>', 'md' ), $fields['author_url'], $fields['author'] ); ?></b>
								</p>
							</div>
							<div style="display: none;">
								<?php foreach ( array( 'name', 'author', 'version', 'description', 'dropin_url', 'author_url', 'settings_url', 'icon', 'colors', 'plugin_name', 'priority', 'plugin_class' ) as $field ) : ?>
									<?php $this->fields->field( array( 'installed', $dropin, $field ), array(
										'type' => 'text',
										'hidden' => true
									) ); ?>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="md-dropin">
				<p class="md-dropin-inner"><?php echo __( 'No Drop-ins found.', 'md' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
	<?php $this->fields->save(); ?>
</div>