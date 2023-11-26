<div class="<?php echo esc_attr( $classes ); ?>">

	<div class="columns-3 columns-half">

		<div class="col col1 md-sep-micro">
			<?php $this->field( $fields['link_type']['field'], array(
				'type' => 'select',
				'label' => __( 'Link Type', 'md' ),
				'classes' => 'md-link-type',
				'options' => array(
					'url' => __( 'Page URL', 'md' ),
					'popup' => __( 'Open Popup', 'md' ),
					'phone' => __( 'Phone Number', 'md' )
				)
			) ); ?>
		</div>

		<div class="col col2">
			<?php $this->field( $fields['link_style']['field'], array(
				'type' => 'select',
				'label' => __( 'Link Style', 'md' ),
				'empty_label' => __( 'Text link', 'md' ),
				'classes' => 'md-link-style',
				'options' => array(
					'button' => __( 'Button', 'md' )
				)
			) ); ?>
		</div>

		<div class="col md-sep-micro">
			<?php $this->field( $fields['toggle']['field'], array(
				'type' => 'checkbox',
				'classes' => 'field-no-label',
				'options' => array(
					'hide_label' => __( 'Hide label', 'md' ),
					'hide_label_mobile' => __( 'Hide label on mobile', 'md' )
				)
			) ); ?>
		</div>

	</div>

	<div class="columns-2 columns-25-50-25 columns-half md-sep-micro">

		<div class="md-link-icon col col1">
			<?php $this->field( $fields['icon']['field'], array(
				'type' => 'select',
				'label' => __( 'Icon', 'md' ),
				'empty_label' => __( 'Select icon', 'md' ),
				'options' => md_get_icons( 'options' ),
			) ); ?>
		</div>

		<div class="col col2 is-url">
			<?php $this->field( $fields['url']['field'], array(
				'type' => 'url',
				'label' => __( 'Link URL', 'md' )
			) ); ?>
		</div>

		<div class="col col2 is-phone">
			<?php $this->field( $fields['phone']['field'], array(
				'type' => 'text',
				'label' => __( 'Phone Number', 'md' ),
				'placeholder' => __( '(999) 999-9999', 'md' )
			) ); ?>
		</div>

		<div class="col col2 is-popup">
			<?php $this->field( $fields['popup']['field'], array(
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

	<div class="is-button columns-25-50-25 columns-half">

		<div class="col col1">
			<?php $this->field( $fields['button_color']['field'], array(
				'type' => 'color',
				'label' => __( 'Button Color', 'md' ),
				'default' => md_setting( array( 'colors', 'site', 'button' ), '#22A340' )
			) ); ?>
		</div>

		<div class="col col2">
			<?php $this->field( $fields['button_style']['field'], array(
				'type' => 'select',
				'label' => __( 'Button Style', 'md' ),
				'empty_label' => __( 'Default', 'md' ),
				'options' => array(
					'outline' => __( 'Outline', 'md' )
				)
			) ); ?>
		</div>

	</div>

</div>
