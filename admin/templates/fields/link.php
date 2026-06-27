<?php
$fields = $this->data->links( $args );
$link_type = $this->module( $fields['type']['field'], 'url' );
$link_style = $this->module( $fields['style']['field'], 'link' );

$classes = array( 'md-group-link', 'md-conditional' );
$classes[] = 'type-' . $link_type;
$classes[] = 'style-' . $link_style;
$classes = join( ' ', $classes );
?>

<div class="<?php echo esc_attr( $classes ); ?>">

	<?php $this->field( $fields['type']['field'], array(
		'type' => 'select',
		'label' => __( 'Type', 'md' ),
		'classes' => 'md-conditional-option',
		'wrap_classes' => 'md-sep-small',
		'empty_label' => __( 'Page URL', 'md' ),
		'options' => array(
			'popup' => __( 'Open Popup', 'md' ),
			'phone' => __( 'Phone Number', 'md' )
		)
	) ); ?>

	<div class="columns-2 columns-20-80 columns-single md-sep-small">
		<div class="col col1">
			<?php $this->field( $fields['icon']['field'], array(
				'type' => 'select',
				'label' => __( 'Icon', 'md' ),
				'empty_label' => __( 'Select icon', 'md' ),
				'options' => md_get_icons( 'options' ),
			) ); ?>
		</div>
		<div class="col col2">
			<div class="md-conditional-item md-conditional-url columns-2 columns-70-30 columns-single<?php echo $link_type == 'url' ? ' is-condition' : ''; ?>">
				<div class="col col1">
					<?php $this->field( $fields['url']['field'], array(
						'type' => 'url',
						'label' => __( 'URL', 'md' )
					) ); ?>
				</div>
				<div class="col col2 field-no-label">
					<?php $this->field( $fields['settings']['field'], array(
						'type' => 'checkbox',
						'wrap_classes' => 'mt-half',
						'options' => array(
							'new' => __( 'Open in new tab', 'md' ),
							'icon_end' => __( 'Show icon at end', 'md' )
						)
					) ); ?>
				</div>
			</div>
			<div class="md-conditional-item md-conditional-phone<?php echo $link_type == 'phone' ? ' is-condition' : ''; ?>">
				<?php $this->field( $fields['phone']['field'], array(
					'type' => 'text',
					'label' => __( 'Phone Number', 'md' ),
					'placeholder' => __( '(999) 999-9999', 'md' )
				) ); ?>
			</div>
			<div class="md-conditional-item md-conditional-popup<?php echo $link_type == 'popup' ? ' is-condition' : ''; ?>">
				<?php $this->field( $fields['popup']['field'], array(
					'type' => 'select',
					'label' => __( 'Open popup', 'md' ),
					'empty_label' => __( 'Select a popup...', 'md' ),
					'options' => md_get_popups( 'options' )
				) ); ?>
			</div>
		</div>
	</div>

	<?php if ( isset( $args['show_title'] ) || isset( $args['show_subtitle'] ) ) : ?>
	<div class="columns-2 columns-half md-sep-micro">
		<?php if ( isset( $args['show_title'] ) ) : ?>
		<div class="col">
			<?php $this->field( $fields['text']['field'], array(
				'type' => 'text',
				'label' => __( 'Text', 'md' )
			) ); ?>
		</div>
		<?php endif; ?>
		<?php if ( isset( $args['show_subtitle'] ) ) : ?>
		<div class="col">
			<?php $this->field( $fields['subtitle']['field'], array(
				'type' => 'text',
				'label' => __( 'Sub Text', 'md' )
			) ); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<div class="columns-2 columns-single md-full-select md-sep-small">

		<div class="col">
			<?php $this->field( $fields['style']['field'], array(
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
			<?php $this->field( $fields['color']['field'], array(
				'type' => 'color',
				'label' => __( 'Color', 'md' ),
				'inherit' => 'secondary'
			) ); ?>
		</div>

	</div>

	<div class="md-sep-small">
		<?php $this->field( $fields['toggle']['field'], array(
			'type' => 'checkbox',
			'classes' => 'md-sep-top-small',
			'inline' => true,
			'options' => array(
				'hide_label' => __( 'Hide label', 'md' ),
				'hide_label_mobile' => __( 'Hide label on mobile', 'md' )
			)
		) ); ?>
	</div>

	<div class="is-button columns-4 columns-single md-sep-micro">

		<div class="col">
			<?php $this->field( $fields['button_style']['field'], array(
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
			<?php $this->field( $fields['size']['field'], array(
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

	<?php $this->visibility_condition( $fields['visibility']['field'] ); ?>

</div>