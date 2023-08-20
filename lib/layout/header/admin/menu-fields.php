<div class="columns-3 columns-half">

	<div class="col">
		<?php $this->fields->field( array( 'builder', $group, 'menu' ), array(
			'type' => 'select',
			'label' => __( 'Menu', 'md' ),
			'empty_label' => __( 'Use Header menu', 'md' ),
			'description' => '<a href="' . admin_url( 'nav-menus.php' ) . '">' . __( 'Edit menus &rarr;', 'md' ) . '</a>',
			'options' => $data['menus']['options']
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( array( 'builder', $group, 'toggle' ), array(
			'type' => 'checkbox',
			'label' => __( 'Display', 'md' ),
			'options' => array(
				'hide_label' => __( 'Hide label', 'md' ),
				'hide_label_mobile' => __( 'Hide label on mobile', 'md' )
			)
		) ); ?>
	</div>

	<div class="col md-sep-small">
		<?php $this->fields->field( array( 'builder', $group, 'submenu_width' ), array(
			'type' => 'number',
			'label' => __( 'Submenu Width', 'md' ),
			'placeholder' => ( $data['values']['typography']['body']['line_height']['desktop'] * 10 ),
			'unit' => 'px'
		) ); ?>
	</div>

</div>
