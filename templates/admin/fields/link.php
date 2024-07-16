<div class="<?php echo esc_attr( $classes ); ?>">

	<div class="columns-4 columns-half md-sep-micro">
		<div class="col col1">
			<?php $this->field( $fields['link_type']['field'], array(
				'type' => 'select',
				'label' => __( 'Link type', 'md' ),
				'classes' => 'md-conditional-option',
				'wrap_classes' => 'md-sep-small',
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
				'label' => __( 'Style', 'md' ),
				'empty_label' => __( 'Text link', 'md' ),
				'classes' => 'md-link-style',
				'options' => array(
					'button' => __( 'Button', 'md' )
				)
			) ); ?>
		</div>
		<div class="col col3">
			<?php $this->field( $fields['link_icon']['field'], array(
				'type' => 'select',
				'label' => __( 'Icon', 'md' ),
				'empty_label' => __( 'Select icon', 'md' ),
				'options' => md_get_icons( 'options' ),
			) ); ?>
		</div>
	</div>

	<div class="md-conditional-item md-conditional-url columns-2 columns-70-30 columns-single<?php echo $link_type == 'url' ? ' is-condition' : ''; ?> md-sep-micro">
		<div class="col col1">
			<?php $this->field( $fields['link_url']['field'], array(
				'type' => 'url',
				'label' => __( 'URL', 'md' )
			) ); ?>
		</div>
		<div class="col col2 field-no-label">
			<?php $this->field( $fields['link_target']['field'], array(
				'type' => 'checkbox',
				'options' => array( 'new' => __( 'Open in new tab', 'md' ) )
			) ); ?>
		</div>
	</div>

	<div class="md-conditional-item md-conditional-phone<?php echo $link_type == 'phone' ? ' is-condition' : ''; ?> md-sep-micro">
		<?php $this->field( $fields['link_phone']['field'], array(
			'type' => 'text',
			'label' => __( 'Phone Number', 'md' ),
			'placeholder' => __( '(999) 999-9999', 'md' )
		) ); ?>
	</div>

	<div class="md-conditional-item md-conditional-popup<?php echo $link_type == 'popup' ? ' is-condition' : ''; ?> md-sep-micro">
		<?php $this->field( $fields['link_popup']['field'], array(
			'type' => 'select',
			'label' => __( 'Open popup', 'md' ),
			'empty_label' => __( 'Select a popup...', 'md' ),
			'options' => md_get_popups( 'options' )
		) ); ?>
	</div>

	<?php if ( isset( $args['show_title'] ) || isset( $args['show_subtitle'] ) ) : ?>
	<div class="columns-2 columns-half md-sep-micro">
		<?php if ( isset( $args['show_title'] ) ) : ?>
		<div class="col">
			<?php $this->field( $fields['link_text']['field'], array(
				'type' => 'text',
				'label' => __( 'Text', 'md' )
			) ); ?>
		</div>
		<?php endif; ?>
		<?php if ( isset( $args['show_subtitle'] ) ) : ?>
		<div class="col">
			<?php $this->field( $fields['link_subtitle']['field'], array(
				'type' => 'text',
				'label' => __( 'Sub Text', 'md' )
			) ); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<div class="columns-3 columns-half md-full-select md-sep-small">

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
					'hide_label' => __( 'Hide text', 'md' ),
					'hide_label_mobile' => __( 'Hide text on mobile', 'md' )
				)
			) ); ?>
		</div>

	</div>

	<div class="is-button columns-4 columns-single md-sep-small">

		<div class="col">
			<?php $this->field( $fields['link_button_style']['field'], array(
				'type' => 'checkbox',
				'label' => __( 'Button styles', 'md' ),
				'empty_label' => __( 'Default', 'md' ),
				'inline' => true,
				'options' => array(
					'outline' => __( 'Outline', 'md' ),
					'frame' => __( 'Frame', 'md' )
				)
			) ); ?>
		</div>

		<div class="col">
			<?php $this->field( $fields['link_size']['field'], array(
				'type' => 'select',
				'label' => __( 'Button size', 'md' ),
				'empty_label' => __( 'Default', 'md' ),
				'options' => array(
					'small' => __( 'Small', 'md' ),
					'large' => __( 'Large', 'md' )
				)
			) ); ?>
		</div>

	</div>

	<hr class="md-sep-micro" />

	<div class="columns-3 columns-single">
		<div class="col">
			<?php $this->field( $fields['link_user']['field'], array(
				'type' => 'select',
				'label' => __( 'Show to...', 'md' ),
				'empty_label' => __( 'All visitors', 'md' ),
				'options' => array(
					'logged_in' => __( 'Logged in users only', 'md' ),
					'logged_out' => __( 'Logged out users only', 'md' ),
				)
			) ); ?>
		</div>
		<div class="col">
			<?php $this->field( $fields['link_display']['field'], array(
				'type' => 'select',
				'label' => __( 'Visibility', 'md' ),
				'wrap_classes' => 'md-sep-micro',
				'empty_label' => __( 'Always show', 'md' ),
				'options' => array(
					'desktop' => __( 'Show on desktop only', 'md' ),
					'mobile' => __( 'Show on mobile only', 'md' )
				)
			) ); ?>
		</div>
	</div>

</div>
