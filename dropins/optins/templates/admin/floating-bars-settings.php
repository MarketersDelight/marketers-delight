<div class="md-content-wrap-med">
	<div class="md-sep-small">
		<?php $this->fields->field( 'bars', array(
			'type' => 'group',
			'label' => __( 'Floating Bars', 'md' ),
			'style' => 'boxes',
			'active_key' => 'locations',
			'callback' => array( $this, 'fields' )
		) ); ?>
	</div>
	<hr class="md-sep-small" />
	<?php $this->fields->save(); ?>
</div>