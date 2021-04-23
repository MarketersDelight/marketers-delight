<div class="md-dropins md-content-wrap-med">
	<h2 class="md-title md-sep-small"><?php echo __( 'Drop-ins', 'md' ); ?> &nbsp;<button id="md_upload_dropin_button" class="button"><?php echo __( 'Add new', 'md' ); ?></button></h2>
	<div id="md_upload_dropin" class="md-dropins-upload md-sep-small">
		<?php $this->fields->field( 'upload', array(
			'type' => 'upload',
			'upload_type' => 'file',
			'upload_action' => 'md_dropin',
			'accept' => '.zip',
			'label' => __( 'Drop-in file', 'md' ),
		) ); ?>
	</div>
	<div class="md-dropins-list md-tabs md-sep-small">
		<div class="md-dropins-title">
			<h3>
				<a href="#" class="md-tab<?php echo md_get_dropins( 'installed', 'active' ) ? ' nav-tab-active' : ''; ?>" data-md-tab="md-all"><?php echo sprintf( __( 'Installed <span>(%s)</span>', 'md' ), count( md_get_dropins( 'installed' ) ) ); ?></a>
				<?php if ( md_get_dropins( 'installed', 'active' ) ) : ?>
					<a href="#" class="md-tab" data-md-tab="dropin-enabled"><?php echo sprintf( __( 'Active <span>(%s)</span>', 'md' ), count( md_get_dropins( 'installed', 'active' ) ) ); ?></a>
					<a href="#" class="md-tab" data-md-tab="dropin-inactive"><?php echo sprintf( __( 'Inactive <span>(%s)</span>', 'md' ), count( md_get_dropins( 'installed', 'inactive' ) ) ); ?></a>
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
				$needs_plugin = isset( $fields['plugin_name'] ) && ! class_exists( $fields['plugin_class'] ) ? true : false;
			?>
				<div class="md-dropin md-tab-content active md-all <?php echo $is_enabled ? 'dropin-enabled' : 'dropin-inactive'; ?>">
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
							<p class="md-dropin-description"><?php echo esc_html( $fields['description'] ); ?></p>
							<p class="md-dropin-byline">
								<?php if ( $is_enabled ) : ?>
									<?php if ( isset( $fields['settings_url'] ) ) : ?>
										<a href="<?php echo admin_url( $fields['settings_url'] ); ?>" class="button button-icon"><i class="dashicons dashicons-admin-generic"></i> <?php echo __( 'Settings', 'md' ); ?></a>
									<?php endif; ?>
									<a href="<?php echo esc_url( $fields['dropin_url'] ); ?>" class="button" target="_blank"><?php echo __( 'Get updates', 'md' ); ?></a>
								<?php endif; ?>
								<?php echo sprintf( __( '<i>by</i> <a href="%s" target="_blank">%1s</a>', 'md' ), $fields['author_url'], $fields['author'] ); ?></b>
							</p>
						</div>
						<div style="display: none;">
							<?php foreach ( array( 'name', 'author', 'version', 'description', 'dropin_url', 'author_url', 'settings_url', 'icon', 'colors', 'plugin_name', 'plugin_class' ) as $field ) : ?>
								<?php $this->fields->field( array( 'installed', $dropin, $field ), array(
									'type' => 'text',
									'hidden' => true
								) ); ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="md-dropin">
				<p><?php echo __( 'No Drop-ins found.', 'md' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
	<div class="md-dropins-list md-tabs md-sep">
		<div class="md-dropins-title">
			<h3>
				<a href="#" class="md-tab<?php echo $core_active_count ? ' nav-tab-active' : ''; ?>" data-md-tab="md-all"><?php echo sprintf( __( 'Core <span>(%s)</span>', 'md' ), $core_count ); ?></a>
				<?php if ( $core_active_count ) : ?>
					<a href="#" class="md-tab" data-md-tab="dropin-enabled"><?php echo sprintf( __( 'Active <span>(%s)</span>', 'md' ), $core_active_count ); ?></a>
					<a href="#" class="md-tab" data-md-tab="dropin-inactive"><?php echo sprintf( __( 'Inactive <span>(%s)</span>', 'md' ), ( $core_count - $core_active_count ) ); ?></a>
				<?php endif; ?>
			</h3>
		</div>
		<?php foreach ( $core as $dropin => $fields ) :
			$is_enabled = md_setting( array( 'dropins', 'core', $dropin, 'status', 'enable' ) ) ? true : false;
			$icon = isset( $fields['icon'] ) ? $fields['icon'] : '';
			$colors = isset( $fields['colors'] ) ? explode( ',', trim( $fields['colors'] ) ) : array();
			$bg_color = isset( $colors[0] ) ? $colors[0] : '';
			$color = isset( $colors[1] ) ? $colors[1] : '';
			$needs_plugin = isset( $fields['plugin_name'] ) && ! class_exists( $fields['plugin_class'] ) ? true : false;
		?>
			<div class="md-dropin md-tab-content active md-all <?php echo ( $is_enabled ? 'dropin-enabled' : 'dropin-inactive' ) . ( $needs_plugin ? ' dropin-disabled' : '' ); ?>">
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
								<?php $this->fields->field( array( 'core', $dropin, 'status' ), array(
									'type' => 'checkbox',
									'options' => array(
										'enable' => __( '<b>Activate</b>', 'md' )
									)
								) ); ?>
							<?php else : ?>
								<p><i class="dashicons dashicons-no"></i> <?php echo sprintf( __( 'Requires <b>%s</b> plugin', 'md' ), $fields['plugin_name'] ); ?></p>
							<?php endif; ?>
						</div>
						<p class="md-dropin-description"><?php echo esc_html( $fields['description'] ); ?></p>
						<p class="md-dropin-byline">
							<?php if ( $is_enabled && isset( $fields['settings_url'] ) ) : ?>
								<a href="<?php echo admin_url( $fields['settings_url'] ); ?>" class="button button-icon"><i class="dashicons dashicons-admin-generic"></i> Settings</a>
							<?php endif; ?>
							<?php echo sprintf( __( '<i>by</i> <a href="%s" target="_blank">%1s</a>', 'md' ), $fields['author_url'], $fields['author'] ); ?></b>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php $this->fields->save(); ?>
</div>