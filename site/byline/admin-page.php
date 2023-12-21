<div class="md-widget md-toggle open md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Byline', 'md' ); ?></h3>

	<div class="md-widget-item">
		<?php $this->fields->field( 'builder', array(
			'type' => 'builder',
			'title' => __( 'Edit Byline', 'md' ),
			'wrap_classes' => 'md-tabs',
			'tabs' => array(
				'archives' => __( 'Archives', 'md' ),
				'single' => __( 'Single', 'md' )
			),
			'active_tab' => 'archives',
			'areas' => array(
				'archives' => array(
					'title' => __( 'Archives', 'md' ),
					'description' => __( 'Customize the byline items that show on archive and category pages.', 'md' ),
					'tab' => 'archives'
				),
				'single' => array(
					'title' => __( 'Single', 'md' ),
					'description' => __( 'Customize the byline items that show on single pages.', 'md' ),
					'tab' => 'single'
				),
			),
			'elements' => array(
				'author' => array(
					'title' => __( 'Author', 'md' ),
					'color' => '#2772af',
					'icon' => 'admin-users',
					'callback' => array( $this, 'author' )
				)
			)
		) ); ?>
	</div>

</div>
