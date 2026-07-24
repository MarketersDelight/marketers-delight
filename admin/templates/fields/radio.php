<?php

$style = array();
$layout = isset( $args['layout'] ) ? esc_attr( $args['layout'] ) : '';
$columns = isset( $args['columns'] ) ? round( ( 100 / $args['columns'] ) - 2 ) : '';

if ( ! empty( $columns ) ) {
	$style['width'] = $columns;
	$style['width_unit'] = '%';
}

foreach ( $args['options'] as $val => $label ) {
	$idval = esc_attr( "{$id}_$val" );
	$image = is_array( $label ) && isset( $label['image'] ) ? esc_url( $label['image'] ) : '';
	$bg_image = $layout !== 'banner' ? ' style="background-image: url(\'' . esc_url( $image ) . '\');"' : '';
	$text = is_array( $label ) ? $label['name'] : $label;
?>

<label for="<?php echo $idval; ?>" class="md-radio <?php echo ( $layout == 'banner' ? 'md-radio-banner md-tooltip-parent' : 'md-radios' ) . ( ! empty( $image ) ? ' md-radio-has-image' : '' ); ?>"<?php echo md_style( $style ); ?>>
	<?php if ( $layout == 'banner' && ! empty( $image ) ) : ?>
		<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_html( $text ); ?>" class="md-radio-image" />
	<?php endif; ?>
	<span class="md-radio-input">
		<input type="radio" name="<?php echo esc_attr( $name ); ?>" id="<?php echo $idval; ?>" class="md-radio-check" value="<?php echo esc_attr( $val ); ?>"<?php echo checked( $option, $val ); ?> />
		<span class="md-radio-label"<?php echo $bg_image; ?>>
			<span class="md-radio-text"><?php echo esc_html( $text ); ?></span>
		</span>
	</span>
	<?php if ( ! empty( $label['description'] ) ) : ?>
	<span class="description<?php echo ( $layout == 'banner' ? ' md-tooltip large' : '' ); ?>"><?php echo esc_html( $label['description'] ); ?></span>
	<?php endif; ?>
</label>

<?php }
