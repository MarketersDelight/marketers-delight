<div class="<?php echo md_has( 'optins' ) ? 'md-content-wrap-med' : 'md-content-wrap'; ?>">

	<h2 class="md-title"><?php echo __( 'Website Integrations', 'md' ); ?></h2>

	<p><?php echo __( 'Connect your website to your favorite third-party services.', 'md' ); ?></p>

	<hr />

	<div class="<?php echo md_has( 'optins' ) ? 'columns-2 columns-60-40 columns-single' : ''; ?>">

		<?php if ( md_has( 'optins' ) ) : ?>
		<div class="col col1">
			<h3><?php echo __( 'Email Services', 'md' ); ?></h3>
			<?php $this->admin_template( array( 'key' => 'email' ) ); ?>
		</div>
		<?php endif; ?>

		<div class="<?php echo md_has( 'optins' ) ? 'col col2' : ''; ?>">
			<h3><?php echo __( 'Site Tools', 'md' ); ?></h3>
			<?php $this->admin_template( array( 'key' => 'site' ) ); ?>
		</div>

	</div>

</div>
