<div id="md_sidebars" class="md-sidebars md-content-wrap<?php echo esc_attr( $classes ); ?>">

	<h2 class="md-title"><?php echo __( 'Sidebars', 'md' ); ?></h2>

	<p class="md-sep-small"><?php echo sprintf( __( 'Create and manage custom sidebars and apply them throughout your website. <a href="%s">Edit widgets &rarr;</a>', 'md' ), admin_url( 'widgets.php' ) ); ?></p>

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
		'wrap_classes' => 'md-sep-small',
		'description' => __( 'Customize sidebars on any page by going to the Edit Post and Edit Category screens.', 'md' ),
		'options' => array(
			'sitewide' => __( 'Add sidebar to all pages', 'md' )
		)
	) ); ?>

	<?php foreach ( $types as $type => $pages ) :
		$name = '';
		$icon = 'dashicons-admin-post';
		$post_type = get_post_type_object( $type );

		if ( ! empty( $post_type->labels->name ) )
			$name = $post_type->labels->name;

		if ( ! empty( $post_type->menu_icon ) )
			$icon = $post_type->menu_icon;
	?>

		<div class="md-sidebar col-style md-sep-small">

			<h2 class="md-title normal">
				<i class="md-title-icon dashicons <?php echo esc_attr( $icon ); ?>"></i>
				<?php echo esc_html( $name ); ?>
			</h2>

			<hr class="md-sep-small" />

			<?php foreach ( $pages as $page => $val ) :
				if ( $page )
					if ( $page === 'single' )
						$label = $post_type->labels->singular_name;
					elseif ( $page == 'archive' )
						$label = sprintf( __( '%s page', 'md' ), $name );
					else {
						$page_label = str_replace( "{$type}_", '', $page );
						$label = "$name $page_label";
					}
			?>
				<div class="columns-3 columns-single md-sep-micro">
					<div class="col md-sep-micro">
						<?php $this->fields->field( "{$type}_{$page}", array(
							'type' => 'select',
							'label' => $label,
							'empty_label' => __( 'Use Main sidebar', 'md' ),
							'options' => $sidebars
						) ); ?>
					</div>
					<div class="col field-no-label">
						<?php $this->fields->field( "{$type}_{$page}_show", array(
							'type' => 'checkbox',
							'inline' => true,
							'options' => array(
								'enable' => __( 'Enable', 'md' ),
								'disable' => __( 'Disable', 'md' )
							)
						) ); ?>
					</div>
				</div>
			<?php endforeach; ?>

		</div>

	<?php endforeach; ?>

	<?php $this->fields->save(); ?>

</div>