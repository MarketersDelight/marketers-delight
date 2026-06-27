<?php
$default = isset( $args['default'] ) ? $args['default'] : '';
$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : $default;
$inherit_key = isset( $args['inherit'] ) ? $args['inherit'] : '';

if ( $inherit_key ) :
	$palette = apply_filters( 'md_color_palette', array() );
	$inherit_val = is_array( $option ) && isset( $option['inherit'] ) ? $option['inherit'] : $inherit_key;
	$hex_val = is_array( $option ) && isset( $option['hex'] ) ? $option['hex'] : '';
	$is_inherit = empty( $hex_val );
	$swatch_hex = $is_inherit && isset( $palette[$inherit_val] ) ? $palette[$inherit_val]['hex'] : $hex_val;
?>

<div class="md-color-picker-wrap md-color-scheme-wrap <?php echo $is_inherit ? 'is-inherit' : 'is-custom'; ?>" data-default-inherit="<?php echo esc_attr( $inherit_key ); ?>">
	<span class="md-color-scheme-swatch" style="background-color: <?php echo esc_attr( $swatch_hex ?: '#FFF' ); ?>;"></span>

	<select name="<?php echo esc_attr( $name ); ?>[inherit]" class="md-color-scheme-select">
		<?php foreach ( $palette as $key => $color ) : ?>
		<option value="<?php echo esc_attr( $key ); ?>" data-hex="<?php echo esc_attr( $color['hex'] ); ?>" <?php selected( $inherit_val, $key ); ?>>
			<?php echo esc_html( $color['name'] ); ?>
		</option>
		<?php endforeach; ?>
	</select>

	<div class="md-color-scheme-picker">
		<input type="text" name="<?php echo esc_attr( $name ); ?>[hex]" id="<?php echo esc_attr( $id ); ?>" class="md-color-picker<?php echo ! empty( $hex_val ) ? ' md-has-color-value' : ''; ?>" value="<?php echo esc_attr( $hex_val ); ?>" data-jscolor="{ value: '<?php echo esc_attr( $hex_val ); ?>' }" />
		<div class="md-color-picker-controls">
			<span class="md-color-picker-fill"></span>
			<span class="md-color-picker-reset" title="<?php echo __( 'Restore default color', 'md' ); ?>">
				<i class="dashicons dashicons-undo"></i>
			</span>
		</div>
	</div>

	<button type="button" class="md-color-scheme-toggle button">
		<?php echo $is_inherit ? __( 'Palette', 'md' ) : __( 'Custom', 'md' ); ?>
	</button>
</div>

<?php else : ?>

<div class="md-color-picker-wrap">
	<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" class="md-color-picker<?php echo ! empty( $option ) ? ' md-has-color-value' : ''; ?>" placeholder="<?php echo esc_html( $placeholder ); ?>" value="<?php echo esc_attr( $option ); ?>" data-jscolor="{ value: '<?php echo esc_attr( $option ); ?>' }" />
	<div class="md-color-picker-controls">
		<span class="md-color-picker-fill"<?php echo md_style( array( 'bg_color' => $default ) ); ?>></span>
		<span class="md-color-picker-reset" title="<?php echo __( 'Restore default color', 'md' ); ?>"><i class="dashicons dashicons-undo"></i></span>
	</div>
</div>

<?php endif; ?>
