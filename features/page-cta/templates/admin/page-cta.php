<div class="md-conditional">

	<div class="columns-2 columns-single md-full-select mb-single">

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

		<div id="<?php echo $prefix; ?>_page_cta_<?php echo esc_attr( $type_id ); ?>" class="md-section md-conditional-item md-conditional-<?php echo esc_attr( $type_id ); ?>" style="display: <?php echo $cta_type == $type_id ? 'block' : 'none'; ?>">

		<?php if ( ! isset( $type['heading'] ) || $type['heading'] ) : ?>
			<h4><?php echo esc_html( $cta_options[$type_id] ); ?></h4>

			<?php if ( ! empty( $type['description'] ) ) : ?>
				<p class="description"><?php echo esc_html( $type['description'] ); ?></p>
			<?php endif; ?>
		<?php endif; ?>

		<?php
			if ( ! empty( $type['admin_callback'] ) && is_callable( $type['admin_callback'] ) )
				call_user_func( $type['admin_callback'], $fields, $prefix );
			else
				do_action( 'md_page_cta_admin_fields' );

			do_action( "md_page_cta_admin_fields_{$type_id}" );
		?>

		</div>

	<?php endforeach; ?>

	<div class="md-section <?php echo esc_attr( $text_classes ); ?>" style="display: <?php echo $text_display; ?>">

		<h4><?php echo __( 'Message', 'md' ); ?></h4>

		<p class="description"><?php echo __( 'Optional words shown with your form or buttons.', 'md' ); ?></p>

		<div class="columns-2 columns-single">

			<div class="col col1 mb-third">
				<?php $fields->field( 'cta_lede' ); ?>
			</div>

			<div class="col col2 mb-third">
				<?php $fields->field( 'cta_title' ); ?>
			</div>

		</div>

		<div class="mb-third">
			<?php $fields->field( 'cta_text', array( 'rows' => 3 ) ); ?>
		</div>

		<?php $fields->field( 'cta_foot', array(
			'placeholder' => __( 'No spam. Unsubscribe anytime.', 'md' ),
			'description' => __( 'Shows at the very bottom.', 'md' )
		) ); ?>

	</div>

</div>
