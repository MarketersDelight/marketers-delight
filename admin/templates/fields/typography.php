<?php

$g = 1.618;
$field = is_array( $field ) ? $field : (array) $field;
$devices = isset( $args['devices'] ) ? $args['devices'] : array( 'desktop', 'mobile' );
$design = new md_design;
$defaults = $design->defaults();
$default = ! empty( $defaults[$this->_clean_id] ) ? $defaults[$this->_clean_id] : array();
$fonts = array(
	'font_size' => __( 'Font Size', 'md' ),
	'line_height' => __( 'Line Height', 'md' )
);
$font_types = array(
	'default' => array(
		'name' => __( 'Default Fonts', 'md' ),
		'image' => MD_URL . 'admin/images/fonts.png'
	),
	'google' => array(
		'name' => __( 'Google Fonts', 'md' ),
		'image' => MD_URL . 'admin/images/google.png'
	)
);

if ( md_setting( array( 'integrations', 'api_keys', 'typekit' ) ) )
	$font_types['typekit'] = array(
		'name' => __( 'Adobe Fonts', 'md' ),
		'image' => MD_URL . 'admin/images/adobe-fonts.gif'
	);
?>

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

		<?php $this->field( array_merge( $field, array( 'font_family' ) ), array(
			'type' => 'text',
			'label' => __( 'Font Family', 'md' ),
			'placeholder' => isset( $args['font_family']['placeholder'] ) ? $args['font_family']['placeholder'] : $defaults['typography']['body']['font_family'],
			'wrap_classes' => 'md-sep-micro'
		) ); ?>

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
				'options' => $this->data->font_weights()
			) ); ?>
		</div>

		<?php if ( isset( $args['bold'] ) ) : ?>
		<div class="md-sep-micro">
			<?php $this->field( array_merge( $field, array( 'bold' ) ), array(
				'type' => 'select',
				'label' => __( 'Bold Text', 'md' ),
				'empty_label' => isset( $args['font_weight']['empty_label'] ) ? $args['font_weight']['empty_label'] : __( 'Select font weight...', 'md' ),
				'options' => $this->data->font_weights()
			) ); ?>
		</div>
		<?php endif; ?>

	</div>

</div>