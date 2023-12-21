<div class="md-widget md-toggle open md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Byline', 'md' ); ?></h3>

	<?php $this->fields->field( 'builder', array(
		'type' => 'builder',
		'title' => __( 'Edit Byline', 'md' ),
		'wrap_classes' => 'md-widget-item md-tabs',
		'active_tab' => 'archives',
		'tabs' => array(
			'archives' => __( 'Archives', 'md' ),
			'single' => __( 'Single', 'md' )
		),
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
			)
		),
		'elements' => $this->elements()
	) ); ?>

</div>
