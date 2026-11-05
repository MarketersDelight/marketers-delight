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
$area = end( $field );
$saved = md_setting( 'typography' );
$inherit = md_design::FONT_FAMILY;

if ( $area !== 'body' && ! empty( $saved['body']['font_family'] ) )
	$inherit = $saved['body']['font_family'];

if ( in_array( $area, array( 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) && ! empty( $saved['h1']['font_family'] ) )
	$inherit = $saved['h1']['font_family'];
?>

<div class="columns-2 columns-single">

	<?php foreach ( $fonts as $font => $label ) : ?>

	<div class="col mb-single">

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
			'type' => 'font',
			'label' => __( 'Font Family', 'md' ),
			'default_label' => isset( $args['font_family']['placeholder'] ) ? $args['font_family']['placeholder'] : __( 'Theme default', 'md' ),
			'default_stack' => $inherit
		) ); ?>

		<?php $this->field( array_merge( $field, array( 'font_type' ) ), array(
			'type' => 'text',
			'hidden' => true,
			'classes' => 'md-font-type'
		) ); ?>

	</div>

	<div class="col">

		<?php if ( isset( $args['bold'] ) ) : ?>
		<div class="columns-2 columns-single">
		<?php endif; ?>

		<div class="mb-third<?php echo isset( $args['bold'] ) ? ' col' : ''; ?>">
			<?php $this->field( array_merge( $field, array( 'font_weight' ) ), array(
				'type' => 'select',
				'label' => __( 'Font Weight', 'md' ),
				'empty_label' => isset( $args['font_weight']['empty_label'] ) ? $args['font_weight']['empty_label'] : __( 'Select font weight...', 'md' ),
				'options' => $this->data->font_weights()
			) ); ?>
		</div>

		<?php if ( isset( $args['bold'] ) ) : ?>
		<div class="mb-third col">
			<?php $this->field( array_merge( $field, array( 'bold' ) ), array(
				'type' => 'select',
				'label' => __( 'Bold Text', 'md' ),
				'empty_label' => isset( $args['font_weight']['empty_label'] ) ? $args['font_weight']['empty_label'] : __( 'Select font weight...', 'md' ),
				'options' => $this->data->font_weights()
			) ); ?>
		</div>
		</div>
		<?php endif; ?>

	</div>

</div>
