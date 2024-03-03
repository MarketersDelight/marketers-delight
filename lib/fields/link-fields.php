<div class="<?php echo esc_attr( $classes ); ?>">

	<?php $this->field( $fields['link_display']['field'], array(
		'type' => 'select',
		'label' => __( 'Display', 'md' ),
		'wrap_classes' => 'md-sep-micro',
		'empty_label' => __( 'Always show', 'md' ),
		'options' => array(
			'desktop' => __( 'Show on desktop only', 'md' ),
			'mobile' => __( 'Show on mobile only', 'md' )
		)
	) ); ?>

	<?php if ( empty( $args['disable_text'] ) ) : ?>
		<?php $this->field( $fields['link_text']['field'], array(
			'type' => 'text',
			'label' => __( 'Text', 'md' ),
			'wrap_classes' => 'md-sep-micro'
		) ); ?>
	<?php endif; ?>

	<div class="columns-4 columns-half md-full-select">

		<div class="col md-sep-micro">
			<?php $this->field( $fields['link_type']['field'], array(
				'type' => 'select',
				'label' => __( 'Type', 'md' ),
				'classes' => 'md-link-type',
				'options' => array(
					'url' => __( 'Page URL', 'md' ),
					'popup' => __( 'Open Popup', 'md' ),
					'phone' => __( 'Phone Number', 'md' )
				)
			) ); ?>
		</div>

		<div class="col">
			<?php $this->field( $fields['link_style']['field'], array(
				'type' => 'select',
				'label' => __( 'Style', 'md' ),
				'empty_label' => __( 'Text link', 'md' ),
				'classes' => 'md-link-style',
				'options' => array(
					'button' => __( 'Button', 'md' )
				)
			) ); ?>
		</div>

		<div class="col">
			<?php $this->field( $fields['link_color']['field'], array(
				'type' => 'color',
				'label' => __( 'Color', 'md' )
			) ); ?>
		</div>

		<div class="col">
			<?php $this->field( $fields['link_toggle']['field'], array(
				'type' => 'checkbox',
				'classes' => 'md-sep-top-small',
				'options' => array(
					'hide_label' => __( 'Hide label', 'md' ),
					'hide_label_mobile' => __( 'Hide label on mobile', 'md' )
				)
			) ); ?>
		</div>

	</div>

	<div class="columns-2 columns-25-50-25 columns-half md-sep-micro">

		<div class="md-link-icon col col1">
			<?php $this->field( $fields['link_icon']['field'], array(
				'type' => 'select',
				'label' => __( 'Icon', 'md' ),
				'empty_label' => __( 'Select icon', 'md' ),
				'options' => md_get_icons( 'options' ),
			) ); ?>
		</div>

		<div class="col col2 is-url">
			<?php $this->field( $fields['link_url']['field'], array(
				'type' => 'url',
				'label' => __( 'URL', 'md' )
			) ); ?>
		</div>

		<div class="col col2 is-phone">
			<?php $this->field( $fields['link_phone']['field'], array(
				'type' => 'text',
				'label' => __( 'Phone Number', 'md' ),
				'placeholder' => __( '(999) 999-9999', 'md' )
			) ); ?>
		</div>

		<div class="col col2 is-popup">
			<?php $this->field( $fields['link_popup']['field'], array(
				'type' => 'select',
				'label' => __( 'Open popup', 'md' ),
				'empty_label' => __( 'Select a popup...', 'md' ),
				'options' => md_get_popups( 'options' )
			) ); ?>
		</div>

		<div class="col col3 is-url">
			<?php $this->field( $fields['link_target']['field'], array(
				'type' => 'checkbox',
				'classes' => 'field-no-label',
				'options' => array( 'new' => __( 'Open in new tab', 'md' ) )
			) ); ?>
		</div>

	</div>

	<div class="is-button columns-4 columns-half">

		<div class="col">
			<?php $this->field( $fields['link_button_style']['field'], array(
				'type' => 'select',
				'label' => __( 'Button Style', 'md' ),
				'empty_label' => __( 'Default', 'md' ),
				'options' => array(
					'outline' => __( 'Outline', 'md' )
				)
			) ); ?>
		</div>

		<div class="col">
			<?php $this->field( $fields['link_size']['field'], array(
				'type' => 'select',
				'label' => __( 'Button Size', 'md' ),
				'empty_label' => __( 'Default', 'md' ),
				'options' => array(
					'small' => __( 'Small', 'md' ),
					'large' => __( 'Large', 'md' )
				)
			) ); ?>
		</div>

	</div>

</div>
