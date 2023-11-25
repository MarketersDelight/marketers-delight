<div id="md_sidebars" class="md-sidebars md-content-wrap<?php echo esc_attr( $classes ); ?>">

	<?php $this->fields->devices(); ?>

	<h2 class="md-title"><?php echo __( 'Sidebars', 'md' ); ?></h2>

	<p class="md-sep-small"><?php echo sprintf( __( 'Create and manage custom sidebars and apply them throughout your website.<br /><a href="%s">Edit widgets &rarr;</a>', 'md' ), admin_url( 'widgets.php' ) ); ?></p>

	<?php include( 'design-fields.php' ); ?>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Manage Sidebars', 'md' ); ?></h3>

		<div class="md-widget-item">

			<?php $this->fields->field( 'areas', array(
				'type' => 'group',
				'wrap_classes' => 'md-sep-micro',
				'description' => sprintf( __( 'Create custom sidebars and assign them to post types in the settings below.', 'md' ), admin_url( 'widgets.php' ) ),
				'callback' => array( $this, 'widget_areas' )
			) ); ?>

			<?php $this->fields->save(); ?>

		</div>

	</div>

	<hr class="md-sep-small" />

	<?php $this->fields->field( 'display', array(
		'type' => 'checkbox',
		'description' => __( 'Customize the sidebar on any page by going to the Edit Post and Edit Category screens.', 'md' ),
		'wrap_classes' => 'md-sep-small',
		'options' => array(
			'sitewide' => __( 'Add sidebar to all pages', 'md' )
		)
	) ); ?>

	<?php $this->fields->display_fields( $types, array(
		'callback' => array( $this, 'display_fields' )
	) ); ?>

	<?php $this->fields->save(); ?>

</div>
