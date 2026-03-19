<div class="md-conditional">

	<?php $this->fields->field( 'page_cta', array(
		'type' => 'select',
		'empty_label' => __( 'Select CTA type...', 'md' ),
		'classes' => 'md-conditional-option',
		'wrap_classes' => 'md-sep-small',
		'options' => $cta_options
	) );

	foreach ( $cta_types as $type_id => $type ) : ?>

		<div id="<?php echo $prefix; ?>_page_cta_<?php echo esc_attr( $type_id ); ?>" class="md-conditional-item md-conditional-<?php echo esc_attr( $type_id ); ?>" style="display: <?php echo $cta_type == $type_id ? 'block' : 'none'; ?>">

		<?php
			if ( ! empty( $type['admin_callback'] ) && is_callable( $type['admin_callback'] ) ) {
				if ( $type['admin_callback'] instanceof Closure )
					$type['admin_callback']->call( $this );
				else
					call_user_func( $type['admin_callback'] );
			}
			else
				do_action( 'md_page_cta_admin_fields' );

			do_action( "md_page_cta_admin_fields_{$type_id}" );
		?>

		</div>

	<?php endforeach; ?>

</div>