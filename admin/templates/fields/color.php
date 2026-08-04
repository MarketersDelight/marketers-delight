<?php

$default  = isset( $args['default'] ) ? $args['default'] : '';
$hex_only = ! empty( $args['hex_only'] );
$palette  = md_color_palette();

if ( $hex_only ) :
	$hex = md_color_hex( $option );
?>

<div class="md-color-picker-wrap">
	<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" class="md-color-picker<?php echo ! empty( $hex ) ? ' md-has-color-value' : ''; ?>" placeholder="<?php echo esc_attr( $default ); ?>" value="<?php echo esc_attr( $hex ); ?>" data-jscolor="{ value: '<?php echo esc_attr( $hex ); ?>' }" />
	<div class="md-color-picker-controls">
		<span class="md-color-picker-fill<?php echo ! $default ? ' is-empty' : ''; ?>"<?php echo $default ? md_style( array( 'bg_color' => $default ) ) : ''; ?>></span>
		<span class="md-color-picker-reset" title="<?php echo __( 'Restore default color', 'md' ); ?>">
			<i class="dashicons dashicons-undo"></i>
		</span>
	</div>
</div>

<?php return; endif;

$is_palette = is_string( $option ) && isset( $palette[$option] );
$is_custom = ! empty( $option ) && ! $is_palette;
$mode = $is_palette ? 'palette' : ( $is_custom ? 'custom' : 'default' );
$palette_default = ! empty( $args['palette'] ) && isset( $palette[$args['palette']] ) ? $args['palette'] : '';
$palette_value = $is_palette ? $option : $palette_default;
$custom_value = $is_custom ? md_color_hex( $option ) : '';
$resolved_default = $default;

if ( ! $resolved_default && $palette_default )
	$resolved_default = $palette[$palette_default]['hex'];

if ( ! $palette_value && ! empty( $palette ) )
	$palette_value = key( $palette );

if ( ! empty( $args['fallback_label'] ) ) {
	$default_name = $args['fallback_label'];
	$default_summary = sprintf( __( 'Inherits: %s', 'md' ), $args['fallback_label'] );
}
elseif ( $palette_default ) {
	$default_name = $palette[$palette_default]['name'];
	$default_summary = $default_name;
}
elseif ( $default ) {
	$default_name = __( 'Theme default', 'md' );
	$default_summary = strtoupper( $default );
}
else {
	$default_name = __( 'Transparent', 'md' );
	$default_summary = __( 'Transparent', 'md' );
}

$swatch = $resolved_default;
$source_title = __( 'Theme default', 'md' );
$source_meta = $default_summary;

if ( $mode === 'palette' && isset( $palette[$palette_value] ) ) {
	$swatch = $palette[$palette_value]['hex'];
	$source_title = $palette[$palette_value]['name'];
	$source_meta = __( 'Global palette', 'md' );
}
elseif ( $mode === 'custom' ) {
	$swatch = $custom_value;
	$source_title = sprintf( __( 'Custom · %s', 'md' ), strtoupper( $custom_value ) );
	$source_meta = __( 'Independent value', 'md' );
}

$menu_id = $id . '_source_menu';
?>

<div class="md-color-source-wrap" data-custom-label="<?php echo esc_attr( __( 'Custom', 'md' ) ); ?>" data-independent-label="<?php echo esc_attr( __( 'Independent value', 'md' ) ); ?>">

	<input type="hidden" name="<?php echo esc_attr( $name ); ?>[mode]" class="md-color-source-mode" value="<?php echo esc_attr( $mode ); ?>" />
	<input type="hidden" name="<?php echo esc_attr( $name ); ?>[palette]" class="md-color-source-palette-value" value="<?php echo esc_attr( $palette_value ); ?>" />

	<button type="button" id="<?php echo esc_attr( $id ); ?>" class="md-color-source-trigger" aria-expanded="false" aria-controls="<?php echo esc_attr( $menu_id ); ?>" aria-haspopup="dialog">
		<span class="md-color-source-swatch<?php echo ! $swatch ? ' is-empty' : ''; ?>"<?php echo $swatch ? md_style( array( 'bg_color' => $swatch ) ) : ''; ?>></span>
		<span class="md-color-source-copy">
			<span class="md-color-source-title"><?php echo esc_html( $source_title ); ?></span>
			<span class="md-color-source-meta"><?php echo esc_html( $source_meta ); ?></span>
		</span>
		<span class="dashicons dashicons-arrow-down-alt2 md-color-source-chevron" aria-hidden="true"></span>
	</button>

	<div id="<?php echo esc_attr( $menu_id ); ?>" class="md-color-source-menu" role="dialog" aria-label="<?php echo esc_attr( sprintf( __( 'Choose %s', 'md' ), $args['label'] ) ); ?>" hidden>

		<div class="md-color-source-section">
			<span class="md-color-source-heading"><?php echo __( 'Theme default', 'md' ); ?></span>

			<button type="button" class="md-color-source-option md-color-source-default<?php echo $mode === 'default' ? ' is-selected' : ''; ?>" data-mode="default" data-hex="<?php echo esc_attr( $resolved_default ); ?>" data-summary-title="<?php echo esc_attr( __( 'Theme default', 'md' ) ); ?>" data-summary-meta="<?php echo esc_attr( $default_summary ); ?>" aria-pressed="<?php echo $mode === 'default' ? 'true' : 'false'; ?>">
				<span class="md-color-source-option-swatch<?php echo ! $resolved_default ? ' is-empty' : ''; ?>"<?php echo $resolved_default ? md_style( array( 'bg_color' => $resolved_default ) ) : ''; ?>></span>
				<span class="md-color-source-copy">
					<span class="md-color-source-title"><?php echo esc_html( $default_name ); ?></span>
					<span class="md-color-source-meta"><?php echo esc_html( $resolved_default ? strtoupper( $resolved_default ) : __( 'No background color', 'md' ) ); ?></span>
				</span>
				<span class="dashicons dashicons-yes md-color-source-check" aria-hidden="true"></span>
			</button>
		</div>

		<div class="md-color-source-section">
			<span class="md-color-source-heading"><?php echo __( 'Global palette', 'md' ); ?></span>

			<div class="md-color-source-palette" role="group" aria-label="<?php echo esc_attr( __( 'Global palette colors', 'md' ) ); ?>">
				<?php foreach ( $palette as $key => $color ) : ?>
				<button type="button" class="md-color-source-palette-option<?php echo $mode === 'palette' && $palette_value === $key ? ' is-selected' : ''; ?>" data-mode="palette" data-palette-key="<?php echo esc_attr( $key ); ?>" data-hex="<?php echo esc_attr( $color['hex'] ); ?>" data-summary-title="<?php echo esc_attr( $color['name'] ); ?>" data-summary-meta="<?php echo esc_attr( __( 'Global palette', 'md' ) ); ?>" title="<?php echo esc_attr( sprintf( '%s · %s', $color['name'], strtoupper( $color['hex'] ) ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%1$s, %2$s', 'md' ), $color['name'], strtoupper( $color['hex'] ) ) ); ?>" aria-pressed="<?php echo $mode === 'palette' && $palette_value === $key ? 'true' : 'false'; ?>">
					<span<?php echo md_style( array( 'bg_color' => $color['hex'] ) ); ?>></span>
					<i class="dashicons dashicons-yes" aria-hidden="true"></i>
				</button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="md-color-source-section">
			<label class="md-color-source-heading" for="<?php echo esc_attr( $id ); ?>_custom"><?php echo __( 'Custom color', 'md' ); ?></label>

			<div class="md-color-source-custom<?php echo $mode === 'custom' ? ' is-selected' : ''; ?>">
				<div class="md-color-source-custom-field">
					<input type="text" name="<?php echo esc_attr( $name ); ?>[custom]" id="<?php echo esc_attr( $id ); ?>_custom" class="md-color-picker<?php echo $custom_value ? ' md-has-color-value' : ''; ?>" placeholder="#000000" value="<?php echo esc_attr( $custom_value ); ?>" data-jscolor="{ value: '<?php echo esc_attr( $custom_value ); ?>' }" />
					<button type="button" class="md-color-source-reset" title="<?php echo esc_attr( __( 'Restore theme default', 'md' ) ); ?>" aria-label="<?php echo esc_attr( __( 'Restore theme default', 'md' ) ); ?>"<?php echo ! $custom_value ? ' disabled' : ''; ?>>
						<i class="dashicons dashicons-undo" aria-hidden="true"></i>
					</button>
				</div>
				<button type="button" class="button button-secondary md-color-source-apply"><?php echo __( 'Apply', 'md' ); ?></button>
			</div>
		</div>

	</div>

</div>
