<div class="columns-2 columns-single">
	<?php foreach ( $fonts as $font => $label ) : ?>
		<div class="col md-sep-small">
			<?php foreach ( $devices as $device ) : ?>
				<div class="md-<?php echo $device; ?>">
					<?php $this->field( array_merge( $field, array( $font, $device ) ), array(
						'type' => 'range',
						'label' => "$label ($device)",
						'placeholder' => isset( $args[$font][$device] ) ? $args[$font][$device] : '',
						'min' => isset( $args[$font][$device] ) ? round( $args[$font][$device] * ( $g / 2 ) ) : '',
						'max' => isset( $args[$font][$device] ) ? round( $args[$font][$device] * $g ) : ''
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
	<div class="col">
		<p>
			<?php $this->field( array_merge( $field, array( 'font_family' ) ), array(
				'type' => 'text',
				'label' => __( 'Font Family', 'md' ),
				'placeholder' => isset( $args['font_family']['placeholder'] ) ? $args['font_family']['placeholder'] : $defaults['typography']['body']['font_family']
			) ); ?>
		</p>
		<?php $this->field( array_merge( $field, array( 'font_type' ) ), array(
			'type' => 'radio',
			'options' => $font_types
		) ); ?>
	</div>
	<div class="col">
		<div class="md-sep-micro">
			<?php $this->field( array_merge( $field, array( 'font_weight' ) ), array(
				'type' => 'select',
				'label' => __( 'Font Weight', 'md' ),
				'empty_label' => isset( $args['font_weight']['empty_label'] ) ? $args['font_weight']['empty_label'] : __( 'Select font weight...', 'md' ),
				'options' => $sanitize->_font_weights
			) ); ?>
		</div>
		<?php if ( isset( $args['bold'] ) ) : ?>
			<div class="md-sep-micro">
				<?php $this->field( array_merge( $field, array( 'bold' ) ), array(
					'type' => 'select',
					'label' => __( 'Bold Text', 'md' ),
					'empty_label' => isset( $args['font_weight']['empty_label'] ) ? $args['font_weight']['empty_label'] : __( 'Select font weight...', 'md' ),
					'options' => $sanitize->_font_weights
				) ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>