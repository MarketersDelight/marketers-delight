<div class="columns-3 columns-half gap-third">

	<div class="col">
		<?php $this->field( array( 'builder', $group, 'toggle' ), array(
			'type' => 'checkbox',
			'label' => __( 'Display', 'md' ),
			'options' => array(
				'search' => __( 'Enable toggle', 'md' ),
				'hide_label' => __( 'Hide label', 'md' ),
				'hide_label_mobile' => __( 'Hide label on mobile', 'md' )
			)
		) ); ?>
	</div>

	<div class="col">
		<?php $this->field( array( 'builder', $group, 'placeholder' ), array(
			'type' => 'text',
			'label' => __( 'Placeholder Text', 'md' ),
			'placeholder' => __( 'Type to search...', 'md' ),
			'wrap_classes' => 'mb-third'
		) ); ?>
		<?php $this->field( array( 'builder', $group, 'submit_text' ), array(
			'type' => 'text',
			'label' => __( 'Submit text', 'md' ),
			'placeholder' => __( 'Search', 'md' )
		) ); ?>
	</div>

</div>
