<?php
$inherit  = isset( $args['inherit'] ) ? $args['inherit'] : '';
$default  = isset( $args['default'] ) ? $args['default'] : '';
$hex_only = ! empty( $args['hex_only'] );

$palette = md_color_palette();
$is_inherit = ! $hex_only && ( isset( $palette[$option] ) || ( ! empty( $inherit ) && empty( $option ) ) );
$inherit_val = $is_inherit && ! empty( $option ) ? $option : $inherit;
$hex = $is_inherit ? '' : $option;
$swatch_hex  = isset( $palette[$inherit_val] ) ? $palette[$inherit_val]['hex'] : $hex;
?>

<div class="md-color-picker-wrap<?php echo ! $hex_only ? ' md-color-scheme-wrap ' . ( $is_inherit ? 'is-inherit' : 'is-custom' ) : ''; ?>" data-default-inherit="<?php echo esc_attr( $inherit ); ?>">

	<?php if ( ! $hex_only ) : ?>
	<span class="md-color-scheme-swatch<?php echo ! $swatch_hex ? ' is-empty' : ''; ?>"<?php echo $swatch_hex ? ' style="background-color: ' . esc_attr( $swatch_hex ) . ';"' : ''; ?>></span>
	<select name="<?php echo esc_attr( $name ); ?>[inherit]" class="md-color-scheme-select">
		<option value="" <?php selected( $inherit_val, '' ); ?>><?php echo __( 'Select from color palette...', 'md' ); ?></option>
		<?php foreach ( $palette as $key => $color ) : ?>
		<option value="<?php echo esc_attr( $key ); ?>" data-hex="<?php echo esc_attr( $color['hex'] ); ?>" <?php selected( $inherit_val, $key ); ?>>
			<?php echo esc_html( $color['name'] ); ?>
		</option>
		<?php endforeach; ?>
	</select>
	<?php endif; ?>

	<div class="md-color-scheme-picker">
		<input type="text" name="<?php echo esc_attr( $name ); ?>[hex]" id="<?php echo esc_attr( $id ); ?>" class="md-color-picker<?php echo ! empty( $hex ) ? ' md-has-color-value' : ''; ?>" placeholder="<?php echo esc_attr( $default ); ?>" value="<?php echo esc_attr( $hex ); ?>" data-jscolor="{ value: '<?php echo esc_attr( $hex ); ?>' }" />
		<div class="md-color-picker-controls">
			<span class="md-color-picker-fill<?php echo ! $default ? ' is-empty' : ''; ?>"<?php echo $default ? md_style( array( 'bg_color' => $default ) ) : ''; ?>></span>
			<span class="md-color-picker-reset" title="<?php echo __( 'Restore default color', 'md' ); ?>">
				<i class="dashicons dashicons-undo"></i>
			</span>
		</div>
	</div>

	<?php if ( ! $hex_only ) : ?>
	<button type="button" class="md-color-scheme-toggle button">
		<?php echo $is_inherit ? __( 'Palette', 'md' ) : __( 'Custom', 'md' ); ?>
	</button>
	<?php endif; ?>
</div>
