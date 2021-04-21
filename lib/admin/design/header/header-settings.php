<div class="md-content-wrap">
	<?php $this->fields->devices(); ?>
	<h2 class="md-title"><?php echo __( 'Header', 'md' ); ?></h2>
	<p><?php echo __( 'Adjust various header settings and controls.', 'md' ); ?></p>
	<hr class="md-sep" />
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Logo', 'md' ); ?></h3>
		<div class="md-widget-item">
			<div class="md-sep-small">
				<?php $this->fields->field( 'display', array(
					'type' => 'checkbox',
					'options' => array(
						'site_title' => __( 'Hide Site Title', 'md' ),
						'site_tagline' => __( 'Hide Tagline', 'md' ),
					)
				) ); ?>
			</div>
			<div class="md-sep-small">
				<?php foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) : ?>
					<div class="md-<?php echo $device; ?>">
						<?php $this->fields->field( array( 'logo_width', $device ), array(
							'type' => 'range',
							'label' => sprintf( __( 'Logo Width%s', 'md' ), " ($device)" ),
							'max' => 500
						) ); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="columns-2 columns-single md-sep-small">
				<div class="col">
					<?php $this->fields->field( 'logo', array(
						'type' => 'upload',
						'upload_type' => 'media',
						'label' => __( 'Site Logo', 'md' )
					) ); ?>
				</div>
				<div class="col">
					<?php $this->fields->field( 'logo_alt', array(
						'type' => 'upload',
						'upload_type' => 'media',
						'label' => __( 'Alternate Logo', 'md' )
					) ); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Header', 'md' ); ?></h3>
		<div class="md-widget-item">
			<div class="columns-2">
				<div class="col">
					<?php foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) : ?>
						<div class="md-<?php echo $device; ?>">
							<?php $this->fields->field( array( 'spacing_top', $device ), array(
								'type' => 'range',
								'label' => sprintf( __( 'Top Spacing%s', 'md' ), " ($device)" ),
								'placeholder' => $defaults['spacing_top'][$device]
							) ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="col">
					<?php foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) : ?>
						<div class="md-<?php echo $device; ?>">
							<?php $this->fields->field( array( 'spacing_bottom', $device ), array(
								'type' => 'range',
								'label' => sprintf( __( 'Bottom Spacing%s', 'md' ), " ($device)" ),
								'placeholder' => $defaults['spacing_bottom'][$device]
							) ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Header Menu', 'md' ); ?></h3>
		<div class="md-widget-item">
			<div class="md-sep-small">
				<?php $this->fields->field( array( 'menu', 'spacing_lr' ), array(
					'type' => 'range',
					'label' => __( 'Links Left/Right Spacing', 'md' ),
					'placeholder' => $defaults['menu']['spacing_lr']
				) ); ?>
			</div>
		</div>
	</div>
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Main Menu', 'md' ); ?></h3>
		<div class="md-widget-item">
			<div class="md-sep-small">
				<?php $this->fields->field( array( 'main_menu', 'disable' ), array(
					'type' => 'checkbox',
					'options' => array(
						'search' => __( 'Disable Search', 'md' )
					)
				) ); ?>
			</div>
			<h4 class="md-title"><?php echo __( 'Menu Links', 'md' ); ?></h4>
			<div class="columns-2">
				<div class="col">
					<?php $this->fields->field( array( 'main_menu', 'spacing_tb' ), array(
						'type' => 'range',
						'label' => __( 'Top/Bottom Spacing', 'md' )
					) ); ?>
				</div>
				<div class="col">
					<?php $this->fields->field( array( 'main_menu', 'spacing_lr' ), array(
						'type' => 'range',
						'label' => __( 'Left/Right Spacing', 'md' )
					) ); ?>
				</div>
			</div>
		</div>
	</div>
	<hr class="md-spacer" />
	<?php $this->fields->save(); ?>
</div>