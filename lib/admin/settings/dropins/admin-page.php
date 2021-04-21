<div class="md-dropins md-content-wrap-med">
	<h2 class="md-title"><?php echo __( 'Dropins Manager', 'md' ); ?></h2>
	<p class="md-sep-small"><?php echo __( 'Extend and manage your website\'s features with <a href="https://marketersdelight.com/dropins/" target="_blank">MD Dropins</a> and child theme mods.', 'md' ); ?></p>
	<div class="columns-30-70 columns-single">
		<div class="col col1 col-right md-sep-small">
			<h3><?php echo __( 'Features Manager', 'md' ); ?></h3>
			<div class="md-sep-small">
				<?php $this->fields->field( 'features', array(
					'type' => 'checkbox',
					'options' => $options
				) ); ?>
			</div>
			<?php $this->fields->save(); ?>
		</div>
		<div class="col col2">
			<div class="columns-2 columns-half md-sep">
				<?php foreach ( $dropins as $dropin => $fields ) :
					$is_installed = function_exists( $fields['callback'] ) || class_exists( $fields['callback'] ) ? true : false;
				?>
					<div class="col<?php echo $is_installed ? ' md-dropin-installed' : ''; ?>">
						<a href="<?php echo $fields['url']; ?>" target="_blank" class="md-dropin-image"><img src="<?php echo $fields['image']; ?>" /></a>
						<div class="col-inner">
							<p class="md-dropin-byline">
								<b><?php echo __( 'Version', 'md' ); ?>: <?php echo $fields['version']; ?></b>
							</p>
							<h3 class="md-sep-micro"><a href="<?php echo $fields['url']; ?>" target="_blank"><?php echo $fields['name']; ?></a></h3>
							<p class="md-clear">
								<a href="<?php echo $fields['url']; ?>" class="button" target="_blank"><?php echo __( 'Get dropin', 'md' ); ?></a>
								<?php if ( $is_installed ) : ?>
									<span class="md-dropin-status"><i class="dashicons dashicons-yes-alt"></i> <?php echo __( 'Installed', 'md' ); ?></span>
								<?php endif; ?>
							</p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<h2 class="md-title"><?php echo __( 'Child Theme Mods', 'md' ); ?></h2>
	<p class="md-sep-small"><?php echo __( 'Unofficial child theme templates and mods from the <a href="https://mdforums.org/forums/theme-mods.77/ target="_blank">MD community</a>.', 'md' ); ?></p>
	<div class="md-mods columns-4 columns-half">
		<?php foreach ( $mods as $mod => $fields ) : ?>
			<div class="col md-sep-micro">
				<div class="col-inner">
					<h3><a href="<?php echo $fields['url']; ?>" target="_blank"><?php echo $fields['name']; ?></a></h3>
					<p class="mb-none"><?php echo sprintf( __( '<i>by</i> %s', 'md' ), $fields['author'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>