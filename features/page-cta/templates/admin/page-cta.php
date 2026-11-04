<div class="md-conditional">

	<div class="columns-2 columns-single md-full-select md-sep-small">

		<div class="col col1">
			<?php $fields->field( 'page_cta', array(
				'empty_label' => __( 'None', 'md' ),
				'inherit' => $inherit,
				'classes' => 'md-conditional-option',
				'options' => $cta_options
			) ); ?>
		</div>

		<div class="col col2">
			<?php $fields->field( 'title_position', array(
				'empty_label' => __( 'After Title', 'md' )
			) ); ?>
		</div>

	</div>

	<?php foreach ( $cta_types as $type_id => $type ) : ?>

		<div id="<?php echo $prefix; ?>_page_cta_<?php echo esc_attr( $type_id ); ?>" class="md-conditional-item md-conditional-<?php echo esc_attr( $type_id ); ?>" style="display: <?php echo $cta_type == $type_id ? 'block' : 'none'; ?>">

		<?php
			if ( ! empty( $type['admin_callback'] ) && is_callable( $type['admin_callback'] ) )
				call_user_func( $type['admin_callback'], $fields, $prefix );
			else
				do_action( 'md_page_cta_admin_fields' );

			do_action( "md_page_cta_admin_fields_{$type_id}" );
		?>

		</div>

	<?php endforeach; ?>

</div>
